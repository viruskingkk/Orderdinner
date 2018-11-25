<?php

class FoodSortController extends Controller
{
	//過濾
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
				'actions'=>array('create','update','delete'),
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
			$model = CJSON::decode(CJSON::encode($model));
		}
		
		$this->render('_form',array(
			'data' => $model,
		));
	}

	//創建
	public function actionCreate()
	{
		$model=new FoodSort();
		if(isset($_POST['FoodSort']))
		{
			$model->attributes=$_POST['FoodSort'];
			$model->depath = intval($model->fid) + 1;
			if($model->save())
			{
				$model->order_id = $model->id;
				$model->save();
				$this->redirect(array('index'));
			}
			else 
			{
				throw new CHttpException(404,Yii::t('yii','創建失敗'));
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
		
		$model=$this->loadModel($id);
		if(isset($_POST['FoodSort']))
		{
			$model->attributes=$_POST['FoodSort'];
			if($model->save())
			{
				$this->redirect(array('foodsort/index'));
			}
			else 
			{
				throw new CHttpException(404,Yii::t('yii','更新失敗'));
			}
		}
		else
		{
			throw new CHttpException(404,'no post param');
		}
	}

	//刪除
	public function actionDelete($id)
	{
		$id = Yii::app()->request->getParam('id');
		if(!$id)
		{
			throw new CHttpException(404,Yii::t('沒有id'));
		}
		
		$this->loadModel($id)->delete();
		$this->redirect(array('foodsort/index'));
	}

	//列表
	public function actionIndex()
	{
		//創建查詢準則
		$criteria = new CDbCriteria();
		$criteria->order = 'order_id DESC';
		$count=FoodSort::model()->count($criteria);
		//構建分頁
		$pages = new CPagination($count);
 		$pages->pageSize = Yii::app()->params['pagesize'];
		$pages->applyLimit($criteria);
		$model = FoodSort::model()->findAll($criteria);
		//物件轉換成陣列
		$data = CJSON::decode(CJSON::encode($model));
		//輸出到前端
		$this->render('index', array(
			'data' 	=> $data,
			'pages'	=> $pages
		));
	}

	public function loadModel($id)
	{
		$model=FoodSort::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
