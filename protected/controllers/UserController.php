<?php
define('NO_LOGIN',true);
class UserController extends Controller
{
	public $defaultAction='login';
	//登錄的首頁
	public function actionLogin()
	{
		if(isset(Yii::app()->user->admin_userinfo))
		{
			$this->redirect(Yii::app()->createUrl('index'));
		}
		else 
		{
			$this->renderPartial('login');
		}
	}
	
	//執行登錄
	public function actionDoLogin()
	{
		$model=new LoginForm();
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            if($model->validate() && $model->login())//驗證成功跳到後臺首頁
            {
                $this->redirect(Yii::app()->createUrl('index'));
            }
            else 
            {
            	$this->redirect(Yii::app()->createUrl('user/login'));
            }
        }
	}
	
	//後臺退出登陸
	public function actionLogout()
	{
		if(isset(Yii::app()->user->admin_userinfo))
		{
			unset(Yii::app()->user->admin_userinfo);
		}
		
		$this->redirect(Yii::app()->createUrl('user/login'));
	}
}
?>
