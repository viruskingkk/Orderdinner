<?php
define('NO_LOGIN',true);
class ErrorController extends FormerController
{
	//登錄的首頁
	public function actionIndex()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
}
?>
