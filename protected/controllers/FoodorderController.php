<?php

class FoodOrderController extends Controller
{
	//過濾
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	//存取控制
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','form'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('audit','delete','deductmoney','cancelorder','todayorder','onekey'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	//表單頁
	public function actionForm()
	{
		$id = Yii::app()->request->getParam('id');
		if($id)
		{
			$model = $this->loadModel($id);
			$data = CJSON::decode(CJSON::encode($model));
			$data['product_info'] = $data['product_info']?unserialize($data['product_info']):array();
			$data['user_name'] = $model->members->name;
			$data['shop_name'] = $model->shops->name;
			$data['create_time'] = date('Y-m-d H:i:s',$model->create_time);
		}
		else 
		{
			throw new CHttpException(404,Yii::t('yii','沒有id'));
		}
		
		$this->render('_form',array(
			'data' 	=> $data,
		));
	}

	//刪除
	public function actionDelete()
	{
		$id = Yii::app()->request->getParam('id');
		if(!$id)
		{
			throw new CHttpException(404,Yii::t('沒有id'));
		}
		
		$this->loadModel($id)->delete();
		$this->redirect(array('index'));
	}

	//列表
	public function actionIndex()
	{
		//創建查詢準則
		$criteria = new CDbCriteria();
		$criteria->order = 't.create_time DESC';
		$count=FoodOrder::model()->count($criteria);
		//構建分頁
		$pages = new CPagination($count);
 		$pages->pageSize = Yii::app()->params['pagesize'];
		$pages->applyLimit($criteria);
		$model = FoodOrder::model()->with('shops','members')->findAll($criteria);
		$data = array();
		foreach($model AS $k => $v)
		{
			$data[$k] = $v->attributes;
			$data[$k]['shop_name'] = $v->shops->name;
			$data[$k]['user_name'] = $v->members->name;
			$data[$k]['create_time'] = date('Y-m-d H:i:s',$v->create_time);
			$data[$k]['status_text'] = Yii::app()->params['order_status'][$v->status];
			$data[$k]['status_color'] = Yii::app()->params['status_color'][$v->status];	
		}
		
		//輸出到前端
		$this->render('index', array(
			'data' 	=> $data,
			'pages'	=> $pages
		));
	}
	
	//扣錢操作
	public function actionDeductMoney()
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			$food_order_id = Yii::app()->request->getParam('id');
			if(!$food_order_id)
			{
				$this->errorOutput(array('errorCode' => 1,'errorText' => '沒有id'));
			}
			
			$orderInfo = $this->loadModel($food_order_id);
			if(!$orderInfo)
			{
				$this->errorOutput(array('errorCode' => 1,'errorText' => '該訂單不存在'));
			}
			else if($orderInfo->status != 1)
			{
				$this->errorOutput(array('errorCode' => 1,'errorText' => '該訂單不能付款'));
			}
			
			//查詢出該訂單裡使用者的帳戶餘額
			$member = Members::model()->find('id=:id',array(':id' => $orderInfo->food_user_id));
			if(!$member)
			{
				$this->errorOutput(array('errorCode' => 1,'errorText' => '該訂單的使用者不存在'));
			}
			
			//判斷用戶的帳戶錢夠不夠扣錢
			if($member->balance < $orderInfo->total_price)
			{
				$this->errorOutput(array('errorCode' => 1,'errorText' => '該用戶帳戶餘額不足只有' .$member->balance. '元'));
			}
			
			$member->balance -= $orderInfo->total_price;
			if($member->save())
			{
				$orderInfo->status = 2;
				if($orderInfo->save())
				{
					//創建一條訂單日誌
					$foodOrderLog = new FoodOrderLog();
					$foodOrderLog->food_order_id = $food_order_id;
					$foodOrderLog->status = $orderInfo->status;
					$foodOrderLog->create_time = time();
					if($foodOrderLog->save())
					{
						//記錄扣款記錄
						Yii::app()->record->record($orderInfo->food_user_id,$orderInfo->total_price);
						$this->output(array('success' => 1,'successText' => '扣款成功'));
					}
					else 
					{
						$this->errorOutput(array('errorCode' => 1,'errorText' => '訂單狀態更新失敗'));
					}
				}
				else 
				{
					$this->errorOutput(array('errorCode' => 1,'errorText' => '訂單狀態更新失敗'));
				}
				
			}
			else 
			{
				$this->errorOutput(array('errorCode' => 1,'errorText' => '扣款失敗'));
			}
		}
		else 
		{
			throw new CHttpException(404,Yii::t('yii','非法操作'));
		}
	}
	
	//取消訂單
	public function actionCancelOrder()
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			$food_order_id = Yii::app()->request->getParam('id');
			if(!$food_order_id)
			{
				$this->errorOutput(array('errorCode' => 1,'errorText' => '沒有id'));
			}
			
			$orderInfo = $this->loadModel($food_order_id);
			if(!$orderInfo)
			{
				$this->errorOutput(array('errorCode' => 1,'errorText' => '該訂單不存在'));
			}
			else if($orderInfo->status != 1)
			{
				$this->errorOutput(array('errorCode' => 1,'errorText' => '該訂單不能被取消'));
			}
			
			$orderInfo->status = 4;
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
					$this->errorOutput(array('errorCode' => 1,'errorText' => '訂單狀態更新失敗'));
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
	
	//今日訂單統計
	public function actionTodayOrder()
	{
		//創建查詢準則
		$criteria = new CDbCriteria();
		$criteria->order = 't.create_time DESC';//按時間倒序排
		
		//如果沒有指定日期，預設查詢當天的訂單統計
		$date = Yii::app()->request->getParam('date');
		if($date)
		{
			$today = strtotime(date($date));
			if(!$today)
			{
				throw new CHttpException(404,'日期格式設置有誤');
			}
			else if($today > time()) 
			{
				throw new CHttpException(404,'設置的日期不能超過今天');
			}
			$tomorrow = $today + 24*3600;
		}
		else 
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('Y-m-d',time()+24*3600));
		}
		
		$criteria->condition = '(t.status = :status1 OR t.status = :status2) AND t.create_time > :today AND t.create_time < :tomorrow';
		$criteria->params = array(':status1' => 1,':status2' => 2,':today' => $today,':tomorrow' => $tomorrow);
		$model = FoodOrder::model()->with('shops','members')->findAll($criteria);
		$data = array();
		$_total_price = 0;
		$tongji = array();
		foreach($model AS $k => $v)
		{
			$_total_price += $v->total_price;
			$data[$k] = $v->attributes;
			$data[$k]['product_info'] = unserialize($v->product_info);
			$data[$k]['shop_name'] = $v->shops->name;
			$data[$k]['user_name'] = $v->members->name;
			$data[$k]['create_time'] = date('Y-m-d H:i:s',$v->create_time);
			$data[$k]['status_text'] = Yii::app()->params['order_status'][$v->status];
			$data[$k]['status_color'] = Yii::app()->params['status_color'][$v->status];	
			//統計
			$tongji[$v->shop_id]['name'] = $v->shops->name . '(' . $v->shops->tel . ')';
			$tongji[$v->shop_id]['product'][] = unserialize($v->product_info);
		}
		
		//統計結果
		$result = array();
		foreach ($tongji AS $k => $v)
		{
			$result[$k]['name'] = $v['name'];
			$shop_total_price = 0;
			foreach($v['product'] AS $_k => $_v)
			{
				foreach ($_v AS $kk => $vv)
				{
					$shop_total_price += $vv['smallTotal'];
					$result[$k]['product'][$vv['Id']]['name'] = $vv['Name'];
					if($result[$k]['product'][$vv['Id']]['count'])
					{
						$result[$k]['product'][$vv['Id']]['count'] += $vv['Count'];
					}
					else 
					{
						$result[$k]['product'][$vv['Id']]['count'] = $vv['Count'];
					}
					
					if($result[$k]['product'][$vv['Id']]['smallTotal'])
					{
						$result[$k]['product'][$vv['Id']]['smallTotal'] += $vv['smallTotal'];
					}
					else 
					{
						$result[$k]['product'][$vv['Id']]['smallTotal'] = $vv['smallTotal'];
					}
				}
			}
			$result[$k]['shop_total_price'] = $shop_total_price;
		}
		
		//輸出到前端
		$this->render('today_order', array(
			'data' 	=> $data,
			'statistics' => $result,
			'total_price' => $_total_price,
			'date' => $date,
		));
	}
	
	//一健扣款（扣除當天的訂單的錢）
	public function actionOneKey()
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			//查詢出今日待付款的訂單
			$criteria = new CDbCriteria();
			$criteria->order = 't.create_time DESC';//按時間倒序排
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('Y-m-d',time()+24*3600));
			$criteria->condition = '(t.status = :status) AND t.create_time > :today AND t.create_time < :tomorrow';
			$criteria->params = array(':status' => 1,':today' => $today,':tomorrow' => $tomorrow);
			$model = FoodOrder::model()->with('shops','members')->findAll($criteria);
			
			//迴圈扣款
			foreach($model AS $k => $cur_order)
			{
				$user_balance = $cur_order->members->balance;//獲取用戶的帳戶餘額
				//如果用戶帳戶餘額不夠就直接下一個
				if($user_balance < $cur_order->total_price)
				{
					continue;
				}
				
				$cur_order->members->balance -= $cur_order->total_price;
				if($cur_order->members->save())
				{
					$cur_order->status = 2;
					if($cur_order->save())
					{
						//創建一條訂單日誌
						$foodOrderLog = new FoodOrderLog();
						$foodOrderLog->food_order_id = $cur_order->id;
						$foodOrderLog->status = $cur_order->status;
						$foodOrderLog->create_time = time();
						if($foodOrderLog->save())
						{
							//記錄扣款記錄
							Yii::app()->record->record($cur_order->food_user_id,$cur_order->total_price);
						}
					}
				}
			}
			$this->output(array('success' => 1,'successText' => '一健扣款成功'));
		}
		else 
		{
			throw new CHttpException(404,Yii::t('yii','非法操作'));
		}
	}
	
	//載入模型
	public function loadModel($id)
	{
		$model=FoodOrder::model()->with('shops','members')->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
