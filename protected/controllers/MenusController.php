<?php

class MenusController extends Controller
{
	//設置篩檢程式
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	//存取控制的規則
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','form'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','audit','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	//創建
	public function actionCreate()
	{
		$model=new Menus;
		//處理圖片
		if($_FILES['index_pic'] && !$_FILES['index_pic']['error'])
		{
			$imgInfo = Yii::app()->material->upload('index_pic');
			if($imgInfo)
			{
				$_POST['Menus']['index_pic'] = $imgInfo['id'];
			}
		}
		
		if(isset($_POST['Menus']))
		{
			$model->attributes=$_POST['Menus'];
			$model->create_time = time();
			if($model->save())
			{
				$model->order_id = $model->id;
				$model->save();
				$this->redirect(array('index'));
			}
			else 
			{
				throw new CHttpException(404,'param error');
			}
		}
		else
		{
			throw new CHttpException(404,'no post param');
		}
	}

	//更新
	public function actionUpdate()
	{
		$id = Yii::app()->request->getParam('id');
		if(!isset($id))
		{
			throw new CHttpException(404,'param id is not exists');
		}
		
		//處理圖片
		if($_FILES['index_pic'] && !$_FILES['index_pic']['error'])
		{
			$imgInfo = Yii::app()->material->upload('index_pic');
			if($imgInfo)
			{
				$_POST['Menus']['index_pic'] = $imgInfo['id'];
			}
		}
		
		$model=$this->loadModel($id);
		if(isset($_POST['Menus']))
		{
			$model->attributes=$_POST['Menus'];
			if($model->save())
			{
				$this->redirect(array('menus/index'));
			}
			else 
			{
				throw new CHttpException(404,'param error');
			}
		}
		else
		{
			throw new CHttpException(404,'no post param');
		}
	}
	
	//表單頁
	public function actionForm()
	{
		$id = Yii::app()->request->getParam('id');
		if($id)
		{
			$model = $this->loadModel($id);
			$data = CJSON::decode(CJSON::encode($model));
			$data['index_pic'] = $data['index_pic']?Yii::app()->params['img_url'] . $model->image->filepath . $model->image->filename:'';
		}
		
		//查詢出商家的資訊
		$shops = Shops::model()->findAll();
		$shops = CJSON::decode(CJSON::encode($shops));
		
		$this->render('_form',array(
			'data' 	=> $data,
			'shops' => $shops,
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
		$this->redirect(Yii::app()->createUrl('menus/index'));
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
				case 2:$status = 3;break;
				case 3:$status = 2;break;
			}
			
			$model->status = $status;
			if($model->save())
			{
				$this->output(array('status' => $status,'status_text' => Yii::app()->params['menu_status'][$status],'status_color' => Yii::app()->params['status_color'][$status]));
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

	//列表
	public function actionIndex()
	{
		//創建查詢準則
		$menuname = Yii::app()->request->getParam('k');
		$shopId = Yii::app()->request->getParam('shop_id');
		$criteria = new CDbCriteria();
		$criteria->order = 't.order_id DESC';
		if($menuname)
		{
			$criteria->compare('t.name',$menuname,true);
		}
		
		if($shopId)
		{
			$criteria->compare('t.shop_id',$shopId);
		}
		$count=Menus::model()->count($criteria);
		//構建分頁
		$pages = new CPagination($count);
 		$pages->pageSize = Yii::app()->params['pagesize'];
		$pages->applyLimit($criteria);
		$model = Menus::model()->with('food_sort','image','shops')->findAll($criteria);
		$data = array();
		foreach($model AS $k => $v)
		{
			$data[$k] = $v->attributes;
			$data[$k]['index_pic'] = $v->index_pic?Yii::app()->params['img_url'] . $v->image->filepath . $v->image->filename:'';
			$data[$k]['sort_name'] = $v->food_sort->name;
			$data[$k]['shop_name'] = $v->shops->name;
			$data[$k]['create_time'] = Yii::app()->format->formatDate($v->create_time);
			$data[$k]['status_text'] = Yii::app()->params['menu_status'][$v->status];
			$data[$k]['status_color'] = Yii::app()->params['status_color'][$v->status];			
			$data[$k]['price'] = $v->price . '元/份';
		}
		
		//取出所有店家供前端選擇
		$shops = Shops::model()->findAll();
		$shops = CJSON::decode(CJSON::encode($shops));
		//輸出到前端
		$this->render('index', array(
			'data' 	=> $data,
			'shops' => $shops,
			'pages'	=> $pages
		));
	}
	
	//載入模型資料
	public function loadModel($id)
	{
		$model=Menus::model()->with('food_sort','image')->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
