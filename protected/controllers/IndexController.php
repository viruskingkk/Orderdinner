<?php

class IndexController extends Controller
{
	//後台首页
	public function actionIndex()
	{
		$data['home_pic'] = Yii::app()->baseUrl . '/assets/images/' . Yii::app()->params['homeIndexPic'];
		$this->render('index',$data);
	}
}