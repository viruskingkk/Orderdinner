<?php

class MembersController extends Controller
{
	//過濾
	public function filters()
	{
		return array(
			'accessControl',
			'checkReqiest + dorecharge,dodeduct,resetpassword',
		);
	}

	//存取控制
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('audit','delete','recharge','dorecharge','deduct','dodeduct','seerecord','resetpassword'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
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

	//刪除
	public function actionDelete()
	{
		$id = Yii::app()->request->getParam('id');
		if(!$id)
		{
			throw new CHttpException(404,Yii::t('yii','沒有id'));
		}
		
		$this->loadModel($id)->delete();
		$this->redirect(array('index'));
	}

	//清單顯示
	public function actionIndex()
	{
		//創建查詢準則
		$username = Yii::app()->request->getParam('k');
		$criteria = new CDbCriteria();
		$criteria->order = 'order_id DESC';
		if($username)
		{
			$criteria->compare('name',$username,true);
		}
		$count=Members::model()->count($criteria);
		//構建分頁
		$pages = new CPagination($count);
 		$pages->pageSize = Yii::app()->params['pagesize'];
		$pages->applyLimit($criteria);
		$model = Members::model()->findAll($criteria);
		$data = array();
		foreach($model AS $k => $v)
		{
			$data[$k] = $v->attributes;
			$data[$k]['create_time'] = Yii::app()->format->formatDate($v->create_time);
			$data[$k]['update_time'] = Yii::app()->format->formatDate($v->update_time);
			$data[$k]['balance'] 	 = $v->balance . '元';
			$data[$k]['sex'] = Yii::app()->params['member_sex'][$data[$k]['sex']];
			$data[$k]['status_text'] = Yii::app()->params['member_status'][$v->status];
			$data[$k]['status_color'] = Yii::app()->params['status_color'][$v->status];		
		}
		
		//獲取總的會員金額
		$total_money = $this->getAllMembersMoney();
		//輸出到前端
		$this->render('index', array(
			'data' 	=> $data,
			'pages'	=> $pages,
			'total_money' => $total_money,
		));
	}
	
	//審核
	public function actionAudit()
	{
		//審核設定成只能ajax訪問
		if(Yii::app()->request->isAjaxRequest)
		{
			$id = Yii::app()->request->getParam('id');
			if(!$id)
			{
				$this->output(array('errorCode' => 1,'errorText' => '沒有id'));
			}
			
			//查詢出原來的狀態
			$model = $this->loadModel($id);
			switch (intval($model->status))
			{
				case 1:$status = 2;break;
				case 2:$status = 1;break;
				case 3:$status = 2;break;
			}

			$model->status = $status;
			if($model->save())
			{
				$this->output(array('status' => $status,'status_text' => Yii::app()->params['member_status'][$status],'status_color' => Yii::app()->params['status_color'][$status]));
			}
			else 
			{
				$this->output(array('errorCode' => 1,'errorText' => '審核失敗'));
			}
		}
		else 
		{
			throw new CHttpException(404,Yii::t('yii','非法訪問'));
		}
	}
	
	//充值頁面
	public function actionRecharge()
	{
		$id = Yii::app()->request->getParam('id');
		if(!$id)
		{
			throw new CHttpException(404,Yii::t('yii','沒有id'));
		}
		
		$member = $this->loadModel($id);
		$member = CJSON::decode(CJSON::encode($member));
		
		$data = array(
			'member_id' 	=> $id,
			'recharge'  	=> Yii::app()->params['member_recharge'],
			'member_name' 	=> $member['name'],
		);
		$this->render('recharge',$data);
	}
	
	//充值
	public function actionDoRecharge()
	{
		$member_id 	= Yii::app()->request->getPost('member_id');
		$money 		= Yii::app()->request->getPost('money');
		
		$member = $this->loadModel($member_id);
		if(!$member)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '該用戶不存在'));
		}
		
		if(!in_array(intval($money),Yii::app()->params['member_recharge']))
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '充值額度非法'));
		}
		
		$member->balance += $money;
		if($member->save())
		{
			//記錄充值記錄
			Yii::app()->record->record($member_id,$money,1);
			$this->output(array('success' => 1,'successText' => '恭喜，您剛剛為' .$member->name.'充值了' . $money . '元,目前帳戶餘額' . $member->balance . '元'));
		}
		else 
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => ''));
		}
	}
	
	//扣款頁面
	public function actionDeduct()
	{
		$id = Yii::app()->request->getParam('id');
		if(!$id)
		{
			throw new CHttpException(404,Yii::t('yii','沒有id'));
		}
		
		$member = $this->loadModel($id);
		$member = CJSON::decode(CJSON::encode($member));
		
		$data = array(
			'member_id' 	=> $id,
			'member_name' 	=> $member['name'],
		);
		$this->render('deduct',$data);
	}
	
	//扣錢
	public function actionDoDeduct()
	{
		$member_id 	= Yii::app()->request->getPost('member_id');
		$money 		= Yii::app()->request->getPost('money');
		
		$member = $this->loadModel($member_id);
		if(!$member)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '該用戶不存在'));
		}
		
		if(!intval($money) || intval($money) < 0)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '扣款額度有誤'));
		}
		
		if($member['balance'] < $money)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '扣款超過帳戶額度'));
		}
		
		$member->balance -= $money;
		if($member->save())
		{
			//記錄扣款記錄
			Yii::app()->record->record($member_id,$money);
			$this->output(array('success' => 1,'successText' => '您剛剛為' .$member->name.'扣除了' . $money . '元,目前帳戶餘額' . $member->balance . '元'));
		}
		else 
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '扣款失敗'));
		}
	}
	
	public function actionSeerecord()
	{
		$user_id = Yii::app()->request->getParam('user_id');
		if(!$user_id)
		{
			throw new CHttpException(404,Yii::t('yii','沒有用戶id'));
		}
		$criteria = new CDbCriteria;
		$criteria->condition='t.user_id=:user_id';
		$criteria->order = 't.create_time DESC';
		$criteria->params=array(':user_id'=>$user_id);
		
		$count=RecordMoney::model()->count($criteria);
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
		$this->render('record',array(
			'data' => $data,
			'pages'	=> $pages
		));
	}
	
	//獲取所有會員帳戶總金額
	private function getAllMembersMoney()
	{
		$money =  Yii::app()->db->createCommand('SELECT SUM(balance) AS total FROM {{members}}')->queryScalar();
		if(!$money)
		{
			return 0;
		}
		return $money;
	}
	
	//重置某個使用者的密碼
	public function actionResetpassword()
	{
	    $member_id 	= Yii::app()->request->getPost('member_id');
		$member = $this->loadModel($member_id);
		if(!$member)
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '該用戶不存在'));
		}
		
		//開始重置密碼：重置之後的密碼是：123456
	    $salt = Common::getGenerateSalt();
		$member->salt = $salt;
		$member->password = md5($salt . '123456');
		$member->update_time = time();
		if($member->save())
		{
			$this->output(array('success' => 1,'successText' => '重置 -- ' .$member->name. ' -- 的密碼成功!'));
		}
		else 
		{
			$this->errorOutput(array('errorCode' => 1,'errorText' => '重置 -- ' .$member->name. ' -- 的密碼失敗!'));
		}
	}

	//載入模型
	public function loadModel($id)
	{
		$model=Members::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
