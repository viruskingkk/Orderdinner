<?php
//前端頁面控制器
class SiteController extends FormerController
{
	private $order;//購物車裡面的訂單資料
	//控制幾個頁面的訪問
	public function filters()
	{
		return array(
			'checkLoginControl + confirmorder,orderok,membercenter,myorder,modifypassword,domodify,systemnotice,cancelorder,seeconsume',//檢測是否登錄
			'checkIsCartEmpty + lookcart,confirmorder',//檢測購物車是否為空
			'checkReqiest + doregister,domodify,submitmessage,replymessage',//判斷是不是ajax請求
			'checkIsOnTime +lookmenu,lookcart,confirmorder',//判斷是否在訂餐時間內
		);
	}
	
	//控制會員是否登錄
	public function filtercheckLoginControl($filterChain)
	{
		if(!isset(Yii::app()->user->member_userinfo))
		{
			$this->redirect(Yii::app()->createUrl('site/login'));
		}
		$filterChain->run();
	}
	
	//檢測購物車是否為空
	public function filtercheckIsCartEmpty($filterChain)
	{
		$_product = Yii::app()->request->cookies['cart'];
		$order = array();
		if($_product)
		{
			$order = json_decode($_product->value,1);
			if($order['Items'])
			{
				foreach ($order['Items'] AS $k => $v)
				{
					$order['Items'][$k]['smallTotal'] = $v['Count'] * $v['Price'];
				}
			}
		}
		
		//如果購物車裡面沒有東西就報錯
		if(!$order || !$order['Items'])
		{
			throw new CHttpException(404,Yii::t('yii','當前購物車沒有美食'));
		}
		
		$this->order = $order;
		$filterChain->run();
	}
	
	//判斷是不是ajax請求
	public function filtercheckReqiest($filterChain)
	{
		if(!Yii::app()->request->isAjaxRequest)
		{
			throw new CHttpException(404,Yii::t('yii','非法操作'));
		}
		
		if(!Yii::app()->request->isPostRequest)
		{
			throw new CHttpException(404,Yii::t('yii','非法操作'));
		}
		$filterChain->run();
	}
	
	public function filtercheckIsOnTime($filterChain)
	{
		if(!Yii::app()->check_time->isOnTime())
		{
			throw new CHttpException(404,Yii::t('yii','不在訂餐時間內'));
		}
		$filterChain->run();
	}
	
	public function actions()
	{
		return array(
              'captcha' => array(
                    'class'		=>'CCaptchaAction',
                    'maxLength'	=> 4,       // 最多生成幾個字元
                    'minLength'	=> 4,       // 最少生成幾個字元
					'testLimit' => 999,
					//'fixedVerifyCode' => substr(md5(time()),11,4), //每次都刷新驗證碼
            ), 
         ); 
	}

	//前臺首頁
	public function actionIndex()
	{
		//取出商家的數據
		$model = Shops::model()->with('image')->findAll('t.status=:status',array(':status' => 2));
		$shopData = array();
		foreach($model AS $k => $v)
		{
			$shopData[$k] = $v->attributes;
			$shopData[$k]['logo'] = $shopData[$k]['logo']?Yii::app()->params['img_url'] . $v->image->filepath . $v->image->filename:'';
		}
		
		//取出公告數據
		$notice = Announcement::model()->findAll(array('order' => 'create_time DESC','condition' => 'status=:status','params'=>array(':status'=>2)));
		$notice = CJSON::decode(CJSON::encode($notice));
		
		//查詢出會員帳戶餘額小於10元的用戶
		$members = Members::model()->findAll('balance < :balance',array(':balance' => 20));
		$members = CJSON::decode(CJSON::encode($members));

		//輸出資料
		$output = array(
			'shops' 	=> $shopData,
			'announce' 	=> $notice,
			'members'	=> $members,
			'isOnTime'  => Yii::app()->check_time->isOnTime(),
		);		
		$this->render('index',$output);
	}
	
	//進入某個餐廳查看菜單
	public function actionLookMenu()
	{
		$shop_id = Yii::app()->request->getParam('shop_id');
		if(!isset($shop_id))
		{
			throw new CHttpException(404,Yii::t('yii','請選擇一家餐廳'));
		}
		
		//查詢出改商店的一些詳細資訊
		$shopData = Shops::model()->findByPk($shop_id);
		if(!$shopData)
		{
			throw new CHttpException(404,Yii::t('yii','您選擇的這家餐廳不存在'));
		}
		$shopData = CJSON::decode(CJSON::encode($shopData));
		
		//判斷改商家有沒有下市場
		if(intval($shopData['status']) != 2)
		{
		    throw new CHttpException(404,Yii::t('yii','您選擇的這家餐廳不存在或者已經倒閉了！'));
		}

		//根據店鋪id查詢出該店鋪的菜單
		$menuData = Menus::model()->with('food_sort','image','shops')->findAll(array('condition' => 't.shop_id=:shop_id AND t.status=:status','params' => array(':shop_id' => $shop_id,':status' => 2)));
		$data = array();
		foreach($menuData AS $k => $v)
		{
			$data[$k] = $v->attributes;
			$data[$k]['index_pic'] = $v->index_pic?Yii::app()->params['img_url'] . $v->image->filepath . $v->image->filename:'';
			$data[$k]['sort_name'] = $v->food_sort->name;
			$data[$k]['shop_name'] = $v->shops->name;
			$data[$k]['create_time'] = Yii::app()->format->formatDate($v->create_time);
			$data[$k]['status'] = Yii::app()->params['menu_status'][$v->status];
			$data[$k]['price'] = $v->price;
		}
		
		//獲取該店的留言
		$criteria = new CDbCriteria();
		$criteria->order = 't.order_id DESC';
		$criteria->condition = 't.shop_id=:shop_id AND t.status=:status';
		$criteria->params = array(':shop_id' => $shop_id,':status' => 1);
		$count=Message::model()->count($criteria);
		//構建分頁
		$pages = new CPagination($count);
 		$pages->pageSize = Yii::app()->params['pagesize'];
		$pages->applyLimit($criteria);
		$messageMode = Message::model()->with('members','shops','replys')->findAll($criteria);
		$message = array();
		foreach($messageMode AS $k => $v)
		{
			$message[$k] = $v->attributes;
			$message[$k]['shop_name'] = $v->shops->name;
			$message[$k]['user_name'] = $v->members->name;
			$message[$k]['create_time'] = date('Y-m-d H:i:s',$v->create_time);
			$message[$k]['status_text'] = Yii::app()->params['message_status'][$v->status];
			$message[$k]['status_color'] = Yii::app()->params['status_color'][$v->status];
			
			$_replys = Reply::model()->with('members')->findAll(array(
					'condition' => 'message_id=:message_id',
					'params'	=> array(':message_id' => $v->id),
			));
			
			if(!empty($_replys))
			{
				foreach ($_replys AS $kk => $vv)
				{
					$message[$k]['replys'][$kk] = $vv->attributes;
					$message[$k]['replys'][$kk]['create_time'] 	= date('Y-m-d H:i:s',$vv->create_time);
					$message[$k]['replys'][$kk]['user_name'] 	= ($vv->user_id == -1)?'前台女神說':$vv->members->name;
				}
			}
		}
		
		$this->render('lookmenu',array(
			'menus' 	=> $data,
			'shop' 		=> $shopData,
			'pages'		=> $pages,
			'message'	=> $message,
		));
	}
	
	//查看購物車
	public function actionLookCart()
	{
		$this->render('lookcart',array('order' => $this->order));
	}
	
	//確認下單
	public function actionConfirmOrder()
	{
		//確認訂單之前查看使用者餘額夠不夠付
		$memberInfo = Members::model()->find('id=:id',array(':id' => Yii::app()->user->member_userinfo['id']));
		if($memberInfo->balance < $this->order['Total'] && !in_array(Yii::app()->user->member_userinfo['id'], Yii::app()->params['allow_user_id'])) 
		{
			throw new CHttpException(404,Yii::t('yii','親！您的帳戶餘額不足，不能下單哦，到前台女神處交錢吧！'));
		}
		
		//構建數據
		$foodOrder = new FoodOrder();
		$foodOrder->shop_id = $this->order['shop_id'];
		$foodOrder->order_number = date('YmdHis',time()) . Common::getRandNums(6);
		$foodOrder->food_user_id = Yii::app()->user->member_userinfo['id'];
		$foodOrder->total_price = $this->order['Total'];
		$foodOrder->create_time = time();
		$foodOrder->product_info = serialize($this->order['Items']);
		
		if($foodOrder->save())
		{
			//記錄訂單動態
			$foodOrderLog = new FoodOrderLog();
			$foodOrderLog->food_order_id = $foodOrder->id;
			$foodOrderLog->create_time = time();
			if($foodOrderLog->save())
			{
				//清空購物車
				unset(Yii::app()->request->cookies['cart']);
				$this->redirect(Yii::app()->createUrl('site/orderok',array('ordernumber' => $foodOrder->order_number)));
			}
		}
		else 
		{
			throw new CHttpException(404,Yii::t('yii','下單失敗'));
		}
	}
	
	//下單成功頁面
	public function actionOrderOk()
	{
		//判斷有沒有該訂單
		$ordernumber = Yii::app()->request->getParam('ordernumber');
		if(!$ordernumber)
		{
			throw new CHttpException(404,Yii::t('yii','沒有訂單號'));
		}
		
		//根據當前使用者的id與訂單號查詢出有沒有該訂單
		$criteria=new CDbCriteria;
		$criteria->select = 'order_number,total_price,create_time';
		$criteria->condition = 'order_number = :order_number AND food_user_id = :food_user_id';
		$criteria->params = array(':order_number' => $ordernumber,':food_user_id' => Yii::app()->user->member_userinfo['id']);
		$data = FoodOrder::model()->find($criteria);
		if(!$data)
		{
			throw new CHttpException(404,Yii::t('yii','您沒有該訂單'));
		}
		
		$data = CJSON::decode(CJSON::encode($data));
		$data['create_time'] = date('Y年m月d日 H時i分s秒',$data['create_time']);
		$data['username'] = Yii::app()->user->member_userinfo['username'];
		$this->render('orderok',array('order_info' => $data));
	}
	
	//用戶中心
	public function actionMemberCenter()
	{
		//查詢出使用者的基本資訊
		$member_id = Yii::app()->user->member_userinfo['id'];
		$criteria=new CDbCriteria;
		$criteria->select = 'name,sex,avatar,email,balance';
		$criteria->condition = 'id=:id';
		$criteria->params = array(':id' => $member_id);
		$memberData = Members::model()->find($criteria);
		$memberData = CJSON::decode(CJSON::encode($memberData));
		$this->render('membercenter',array('member' => $memberData));
	}
	
	//查詢使用者自己的訂單
	public function actionMyOrder()
	{
		$is_today = Yii::app()->request->getParam('today');
		$member_id = Yii::app()->user->member_userinfo['id'];
		$criteria = new CDbCriteria;
		$criteria->order = 't.create_time DESC';
		$criteria->select = '*';
		$criteria->condition = 'food_user_id=:food_user_id';
		$criteria->params = array(':food_user_id' => $member_id);
		
		//構建分頁
		$count=FoodOrder::model()->count($criteria);
		$pages = new CPagination($count);
 		$pages->pageSize = Yii::app()->params['pagesize'];
		$pages->applyLimit($criteria);
		//按條件獲取資料
		
		$model = FoodOrder::model()->with('shops','food_log')->findAll($criteria);
		$orderData = array();
		foreach ($model AS $k => $v)
		{
			if($is_today)
			{
				//只取今天的訂單
				if(date('Ymd',$v->create_time) != date('Ymd',time()))
				{
					continue;
				}
			}
			else 
			{
				//排除今天的訂單
				if(date('Ymd',$v->create_time) == date('Ymd',time()))
				{
					continue;
				}
			}

			$orderData[$k] = $v->attributes;
			$orderData[$k]['shop_name'] = $v->shops->name;
			$orderData[$k]['product_info'] = unserialize($v->product_info);
			$orderData[$k]['create_order_date'] = date('Y-m-d',$v->create_time);
			$orderData[$k]['create_time'] = date('H:i:s',$v->create_time);
			$orderData[$k]['status_text'] = Yii::app()->params['order_status'][$v->status];
			//訂單狀態日誌
			$status_log = CJSON::decode(CJSON::encode($v->food_log));
			foreach ($status_log AS $kk => $vv)
			{
				$status_log[$kk]['status_text'] = Yii::app()->params['order_status'][$vv['status']];
				$status_log[$kk]['create_time'] = date('H:i:s',$vv['create_time']);
			}
			$orderData[$k]['status_log'] = $status_log;
		}
		$cur_title = $is_today?'今日訂單':'歷史訂單';
		$this->render('myorder',array('order' => $orderData,'cur_title' => $cur_title,'pages' => $pages));
	}
	
	//前臺會員登陸介面
	public function actionLogin()
	{
		//如果已經登陸就直接跳到訂單中心（使用者中心）
		if(isset(Yii::app()->user->member_userinfo))
		{
			$this->redirect(Yii::app()->createUrl('site/membercenter'));
		}
		else 
		{
			$this->render('login');	
		}
	}
	
	//執行登錄操作
	public function actionDoLogin() 
	{
		$name = Yii::app()->request->getParam('name');
		$password = Yii::app()->request->getParam('password');

		if(!$name)
		{
			$this->errorOutput(array('error' => 1));
		}
		
		if(!$password)
		{
			$this->errorOutput(array('error' => 2));
		}
		
		//利用MemberIdentity來驗證
		$identity=new MemberIdentity($name,$password);
		$identity->authenticate();
		
		//登錄成功
		if($identity->errorCode===MemberIdentity::ERROR_NONE)
		{
			$duration = 3600*24*30;//保持一個月
			Yii::app()->user->login($identity,$duration);
			$this->errorOutput(array('error' => 4));
		}
		else
		{
			$this->errorOutput(array('error' => 3));		
		}		
	}
	
	//會員註冊頁面
	public function actionRegister()
	{
		$this->render('register');
	}
	
	//執行註冊操作
	public function actionDoRegister()
	{
		$name = Yii::app()->request->getPost('name');
		$password1 = Yii::app()->request->getPost('password1');
		$password2 = Yii::app()->request->getPost('password2');

		if(!$name)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '姓名不能為空'));
		}
		else if(strlen($name) > 15)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '姓名太長不能超過15個字元'));
		}

		if(!$password1 || !$password2)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '密碼不能為空'));
		}
		else if(strlen($password1) > 15 || strlen($password2) > 15)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '兩次密碼不能超過15個字元'));
		}
		else if($password1 !== $password2)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '兩次密碼不相符'));
		}
		
		//判斷該用戶是不是已經存在了
		$_member = Members::model()->find('name=:name',array(':name' => $name));
		if($_member)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '該用戶已經存在'));
		}
		
		//隨機長生一個干擾碼
		$salt = Common::getGenerateSalt();
		$model = new Members();
		$model->name = $name;
		$model->salt = $salt;
		$model->password = md5($salt . $password1);
		$model->create_time = time();
		$model->update_time = time();
		if($model->save())
		{
			$model->order_id = $model->id;
			$model->save();
			$this->output(array('success' => 1,'successText' => '註冊成功'));
		}
		else 
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '註冊失敗'));
		}
	}
	
	//會員退出
	public function actionLogout()
	{
		if(isset(Yii::app()->user->member_userinfo))
		{
			unset(Yii::app()->user->member_userinfo);
		}
		$this->redirect(array('site/login'));
	}
	
	//修改密碼頁面
	public function actionmodifyPassword()
	{
		$this->render('modifypassword');
	}
	
	//確認修改
	public function actionDomodify()
	{
		$cur_password = Yii::app()->request->getPost('cur_password');
		$new_password = Yii::app()->request->getPost('new_password');
		$comfirm_password = Yii::app()->request->getPost('comfirm_password');

		if(!$cur_password)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '當前密碼不能為空'));
		}

		if(!$new_password || !$comfirm_password)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '新密碼不能為空'));
		}
		else if(strlen($new_password) > 15 || strlen($comfirm_password) > 15)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '新密碼不能超過15個字元'));
		}
		else if($new_password !== $comfirm_password)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '兩次密碼不相符'));
		}
		
		//判斷該用戶是不是已經存在了
		$_member = Members::model()->find('id=:id',array(':id' => Yii::app()->user->member_userinfo['id']));
		if(!$_member)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '當前用戶不存在'));
		}
		else if(md5($_member->salt . $cur_password) != $_member->password)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '當前密碼輸入錯誤'));
		}
		
		//隨機長生一個干擾碼
		$salt = Common::getGenerateSalt();
		$_member->salt = $salt;
		$_member->password = md5($salt . $new_password);
		$_member->update_time = time();
		if($_member->save())
		{
			$this->output(array('success' => 1,'successText' => '修改成功'));
		}
		else 
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '修改失敗'));
		}
	}
	
	//系統公告
	public function actionSystemNotice()
	{
		//查詢出公告資料
		$notice = Announcement::model()->findAll(array('order' => 'create_time DESC','condition' => 'status=:status','params'=>array(':status'=>2)));
		$notice = CJSON::decode(CJSON::encode($notice));
		foreach($notice AS $k => $v)
		{
			$notice[$k]['create_time'] = date('Y-m-d',$v['create_time']);
		}
		$this->render('systemnotice',array('announce' => $notice));
	}
	
	//使用者取消訂單
	public function actionCancelOrder()
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			$food_order_id = Yii::app()->request->getParam('id');
			if(!$food_order_id)
			{
				$this->errorOutput(array('errorCode' => 1,'errorText' => '沒有id'));
			}
			
			//判斷當前時間有沒有已經過了訂餐的時間，如果過了訂餐的時間，就不能取消訂單了，只能讓妹子操作後臺取消
    		if(!Yii::app()->check_time->isOnTime())
    		{
    			$this->errorOutput(array('errorCode' => 1,'errorText' => '已經過了訂餐時間，您暫時不能取消訂單，如果確實需要取消，請聯繫前台女神'));
    		}
			
			$orderInfo = FoodOrder::model()->find('id=:id AND food_user_id=:food_user_id',array(':id' => $food_order_id,':food_user_id' => Yii::app()->user->member_userinfo['id']));
			if(!$orderInfo)
			{
				$this->errorOutput(array('errorCode' => 1,'errorText' => '該訂單不存在'));
			}
			else if($orderInfo->status != 1)
			{
				$this->errorOutput(array('errorCode' => 1,'errorText' => '該訂單不能被取消'));
			}
			
			$orderInfo->status = 3;
			if($orderInfo->save())
			{
				//創建一條訂單日誌
				$foodOrderLog = new FoodOrderLog();
				$foodOrderLog->food_order_id = $food_order_id;
				$foodOrderLog->status = $orderInfo->status;
				$foodOrderLog->create_time = time();
				if($foodOrderLog->save())
				{
					$this->output(array('success' => 1,'successText' => '取消訂單成功'));
				}
				else 
				{
					$this->errorOutput(array('errorCode' => 1,'errorText' => '更新訂單狀態失敗'));
				}
			}
			else 
			{
				$this->errorOutput(array('errorCode' => 1,'errorText' => '取消訂單失敗'));
			}
		}
		else 
		{
			throw new CHttpException(404,Yii::t('yii','非法操作'));
		}
	}
	
	//美食分享
	public function actionFoodShare()
	{
		$this->render('foodshare');
	}
	
	//提交留言
	public function actionSubmitMessage()
	{
		$content = Yii::app()->request->getParam('content');
		$validate_code = Yii::app()->request->getParam('validate_code');
		$shop_id = Yii::app()->request->getParam('shop_id');
		
		if(!isset(Yii::app()->user->member_userinfo))
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '你還未登錄，請先去登錄'));
		}
		else 
		{
			$user_id = Yii::app()->user->member_userinfo['id'];
		}
		
		if(!$content)
		{
			$this->errorOutput(array('errorCode' => 2,'errorText' => '留言內容不能為空'));
		}
		
		if(!$validate_code)
		{
			$this->errorOutput(array('errorCode' => 3,'errorText' => '驗證碼不能為空'));
		}
		
		if(!$shop_id)
		{
			$this->errorOutput(array('errorCode' => 4,'errorText' => '沒有商店id'));
		}
		
		//驗證驗證碼是否正確
		if(!$this->createAction('captcha')->validate($validate_code,false))
		{
			$this->errorOutput(array('errorCode' => 5,'errorText' => '驗證碼有誤'));
		}
		
		$model = new Message();
		$model->shop_id = $shop_id;
		$model->user_id = $user_id;
		$model->content = $content;
		$model->create_time = time();
		if($model->save())
		{
			$model->order_id = $model->id;
			if($model->save())
			{
				$this->output(array('success' => 1,'successText' => '留言成功'));
			}
			else 
			{
				$this->errorOutput(array('errorCode' => 6,'errorText' => '留言失敗'));
			}
		}
		else 
		{
			$this->errorOutput(array('errorCode' => 6,'errorText' => '留言失敗'));
		}
	}
	
	//回復留言
	public function actionReplyMessage()
	{
		$message_id = Yii::app()->request->getParam('reply_id');
		$reply_content = Yii::app()->request->getParam('reply_content');
		
		if(!isset(Yii::app()->user->member_userinfo))
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '你還未登錄，請先去登錄'));
		}
		else 
		{
			$user_id = Yii::app()->user->member_userinfo['id'];
		}
		
		if(!$reply_content)
		{
			$this->errorOutput(array('errorCode' => 2,'errorText' => '回復內容不能為空'));
		}
		
		if(!$message_id)
		{
			$this->errorOutput(array('errorCode' => 3,'errorText' => '未選擇回復留言'));
		}
		
		$model = new Reply();
		$model->message_id = $message_id;
		$model->user_id = $user_id;
		$model->content = $reply_content;
		$model->create_time = time();
		if($model->save())
		{
			$this->output(array('success' => 1,'successText' => '回復成功'));
		}
		else 
		{
			$this->errorOutput(array('errorCode' => 4,'errorText' => '回復失敗'));
		}
	}
	
	//查看消費記錄（充值與扣款的記錄）
	public function actionSeeConsume()
	{
	    $userId = Yii::app()->user->member_userinfo['id'];
	    $criteria = new CDbCriteria;
		$criteria->condition = 't.user_id=:user_id';
		$criteria->order = 't.create_time DESC';
		$criteria->params=array(':user_id' => $userId);
		
		$count = RecordMoney::model()->count($criteria);
		//構建分頁
		$pages = new CPagination($count);
 		$pages->pageSize = Yii::app()->params['pagesize'];
		$pages->applyLimit($criteria);
		$record = RecordMoney::model()->with('members')->findAll($criteria);
		$data = array();
		foreach ($record AS $k => $v)
		{
			$data[$k] = $v->attributes;
			$data[$k]['type_text'] = Yii::app()->params['record_money'][$v['type']];
			$data[$k]['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
			$data[$k]['user_name'] = $v->members->name;
		}
		$this->render('seeconsume',array(
			'data' => $data,
			'pages'	=> $pages
		));
	}
}
