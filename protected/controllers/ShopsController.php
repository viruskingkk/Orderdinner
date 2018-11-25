<?php

class ShopsController extends Controller
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
				'actions'=>array('create','update','audit','delete','importmenu','doimport'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	//創建店鋪
	public function actionCreate()
	{
		$model=new Shops();
		//處理圖片
		if($_FILES['logo'] && !$_FILES['logo']['error'])
		{
			$imgInfo = Yii::app()->material->upload('logo');
			if($imgInfo)
			{
				$_POST['Shops']['logo'] = $imgInfo['id'];
			}
		}
		
		if(isset($_POST['Shops']))
		{
			$model->attributes=$_POST['Shops'];
			$model->create_time = time();
			$model->update_time = time();
			if($model->save())
			{
				$model->order_id = $model->id;
				$model->save();
				$this->redirect(array('index'));
			}
			else 
			{
				throw new CHttpException(404,'創建失敗');
			}
		}
		else 
		{
			throw new CHttpException(404,'no post param');
		}
	}

	//更新店鋪
	public function actionUpdate()
	{
		$id = Yii::app()->request->getParam('id');
		if(!isset($id))
		{
			throw new CHttpException(404,'param id is not exists');
		}
		
		//處理圖片
		if($_FILES['logo'] && !$_FILES['logo']['error'])
		{
			$imgInfo = Yii::app()->material->upload('logo');
			if($imgInfo)
			{
				$_POST['Shops']['logo'] = $imgInfo['id'];
			}
		}
		
		$model=$this->loadModel($id);
		if(isset($_POST['Shops']))
		{
			$model->attributes=$_POST['Shops'];
			if($model->save())
			{
				$this->redirect(array('shops/index'));
			}
			else 
			{
				throw new CHttpException(404,'更新失敗');
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
			$data['logo'] = $data['logo']?Yii::app()->params['img_url'] . $model->image->filepath . $model->image->filename:'';
		}
		
		$this->render('_form',array(
			'data' => $data,
		));
	}

	//刪除店鋪
	public function actionDelete()
	{
		$id = Yii::app()->request->getParam('id');
		if(!$id)
		{
			throw new CHttpException(404,Yii::t('沒有id'));
		}
		
		$this->loadModel($id)->delete();
		$this->redirect(Yii::app()->createUrl('shops/index'));
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
				$this->output(array('status' => $status,'status_text' => Yii::app()->params['shop_status'][$status],'status_color' => Yii::app()->params['status_color'][$status]));
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
		$criteria = new CDbCriteria();
		$criteria->order = 'order_id DESC';
		$count = Shops::model()->count($criteria);
		//構建分頁
		$pages = new CPagination($count);
 		$pages->pageSize = Yii::app()->params['pagesize'];
		$pages->applyLimit($criteria);
		$model = Shops::model()->with('image')->findAll($criteria);
		$data = array();
		foreach($model AS $k => $v)
		{
			$data[$k] = $v->attributes;
			$data[$k]['logo'] = $v->logo?Yii::app()->params['img_url'] . $v->image->filepath . $v->image->filename:'';
			$data[$k]['create_time'] = Yii::app()->format->formatDate($v->create_time);
			$data[$k]['update_time'] = Yii::app()->format->formatDate($v->update_time);
			$data[$k]['status_text'] = Yii::app()->params['shop_status'][$v->status];
			$data[$k]['status_color'] = Yii::app()->params['status_color'][$v->status];	
		}
		//輸出到前端
		$this->render('index', array(
			'data' 	=> $data,
			'pages'	=> $pages
		));
	}
	
	//導入功能表介面
	public function actionImportMenu()
	{
		$id = Yii::app()->request->getParam('id');
		if(!$id)
		{
			throw new CHttpException(404,Yii::t('yii','請選擇一家餐廳'));
		}
		
		$model = $this->loadModel($id);
		$data = CJSON::decode(CJSON::encode($model));
		$this->render('import_menu',array(
				'data' => $data,
		));
	}
	
	//開始導入
	public function actionDoImport()
	{
		$id = Yii::app()->request->getParam('id');
		if(!$id)
		{
			throw new CHttpException(404,Yii::t('yii','請選擇您要導入哪家餐廳'));
		}
		
		//處理上傳的功能表檔
		if($_FILES['menufile'] && !$_FILES['menufile']['error'])
		{
			$menuFile = Yii::app()->menu_upload->upload('menufile');
			if(file_exists($menuFile))
			{
				//讀取功能表內容
				$menuData = file_get_contents($menuFile);
				if($menuData)
				{
					$menuArr = explode("|",$menuData);
					if($menuArr)
					{
						foreach ($menuArr AS $k => $v)
						{
							if(!$v)
							{
								break;
							}
							
							$_data = explode('#',$v);
							$model = new Menus();
							$model->name = $_data[0];
							$model->price = $_data[1];
							$model->create_time = time();
							$model->shop_id = $id;
							$model->status = 2;
							if($model->save())
							{
								$model->order_id = $model->id;
								$model->save();
							}
						}
					}
				}
				$this->redirect(Yii::app()->createUrl('menus/index',array('shop_id' => $id)));
			}
			else 
			{
				throw new CHttpException(404,Yii::t('yii','檔上傳失敗'));
			}
		}
		else 
		{
			throw new CHttpException(404,Yii::t('yii','請上傳要導入的菜單檔'));
		}
	}
	
	//載入模型
	public function loadModel($id)
	{
		$model=Shops::model()->with('image')->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
