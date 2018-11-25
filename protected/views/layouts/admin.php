<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>訂餐系統</title>
<?php
/*載入js*/
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/assets/js/jquery-1.3.2.min.js");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/assets/js/common.js");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/assets/js/jquery.wysiwyg.js");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/assets/js/facebox.js");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/assets/js/simpla.jquery.configuration.js");

/*載入css*/
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/assets/css/reset.css");
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/assets/css/style.css");
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/assets/css/invalid.css");
?>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
<div id="body-wrapper">
  <div id="sidebar">
    <div id="sidebar-wrapper">
      <!-- Sidebar with logo and menu -->
      <h1 id="sidebar-title"><a href="#">訂餐系統</a></h1>
      <a href="#"><img id="logo" src="<?php echo Yii::app()->baseUrl;?>/assets/images/logo.png" alt="訂餐系統" /></a>
      <!-- Sidebar Profile links -->
      <div id="profile-links"> 你好, <a href="#" title="Edit your profile"><?php echo Yii::app()->user->admin_userinfo['username'];?></a> | <a href="<?php echo Yii::app()->createUrl('user/logout'); ?>" title="退出">退出</a> </div>
        <?php $this->widget('application.widgets.CMenuList'); ?>
    </div>
  </div>
<div id="main-content">
<!-- Page Head -->
<h2>歡迎使用訂餐系統</h2>
<p id="page-intro"><a href="<?php echo Yii::app()->createUrl('site');?>" target="_blank">前臺首頁</a></p>
<!-- End .shortcut-buttons-set -->
  <div class="clear"></div>
    <?php echo $content;?>
  <div class="clear"></div>
    <div id="footer"> 
    <small>
      &#169; Copyright 2018 viruskingkk | Powered by <a href=""</a> | <a href="#">Top</a>
    </small> 
    </div>
  </div>
</div>
</body>
</html>
