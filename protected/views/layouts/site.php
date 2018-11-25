<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head id="phead"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>文德福訂餐網</title>
<?php 
/*載入css*/
foreach(array('base','common','main','login','home','order','food') AS $k => $v)
{
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/css/front/' .$v. '.css');
}

/*載入js*/

foreach(array('jquery-1.7.1.min','jquery.cookie','jquery.json-2.4.min') AS $k => $v)
{
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/' .$v. '.js');
}
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/front/cart.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/front/jquery.wookmark.js');


?>
</head>
<body>
<div id="top">
    <div id="top_inner">
        <a class="logo" href="<?php echo Yii::app()->createUrl('site/index');?>">
            <img src="<?php echo Yii::app()->baseUrl;?>/assets/images/front/tranlogo1.png">
        </a>
        <ul class="top_NewNava">
            <li class="active"><a href="<?php echo Yii::app()->createUrl('site')?>">今天想吃些甚麼</a></li>
            <!-- <li><a href="#">我要吐槽</a></li> -->
        </ul>
        <div id="top_quickmenu" class="quick_menu">
            <dl>
                <dd>
                    <?php if(isset(Yii::app()->user->member_userinfo)):?>
                    <a id="top_MyOrderLink" class="MyOrderLink" href="<?php echo Yii::app()->createUrl('site/membercenter');?>">用戶中心</a>
                    <?php endif;?>
                </dd>
                <dd class="all_mess">
                    <span id="top_notification" class="notification"><b class="message_prompt">
                        &nbsp;</b> <span id="top_num" class="num" style="display:none;"></span>
                        <div id="top_MessageContent" class="layer_top_account layer_top_Message br4 shadow " style="display: none;">
                            <span class="arrow"></span>
                            <ul id="top_MessageBox" class="MessageBox"><li class="No_Message_Type"><div>今日沒有消息</div></li><li class="btn clearfix noPointer"><input type="submit" class="setallmessage" value="知道了"></li></ul>
                        </div>
                    </span> 
                </dd>
                <dd id="top_top_user" class="Newaccount">
                    <div class="login_regist">
                        <?php if(isset(Yii::app()->user->member_userinfo)):?>
                        <a href="<?php echo Yii::app()->createUrl('site/membercenter');?>" class="log_btn"><?php echo Yii::app()->user->member_userinfo['username'];?></a> <span>|</span>
                        <a href="<?php echo Yii::app()->createUrl('site/logout');?>" class="log_btn regist">退出</a>
                        <?php else: ?>
                        <a href="<?php echo Yii::app()->createUrl('site/login');?>" class="log_btn">登錄</a> <span>|</span>
                        <a href="<?php echo Yii::app()->createUrl('site/register');?>" class="log_btn regist">註冊</a>
                        <?php endif;?>
                        <div class="login-type" style="display: none">
                            <ul>
                                <li class="login-type1"><a href="#">本站帳號登錄</a></li>
                                <li class="login-type2"><a href="#">新浪微博登錄</a></li>
                                <li class="login-type3"><a href="#">人人網登錄</a></li>
                                <li class="login-type4"><a href="#">QQ登錄</a></li>
                            </ul>
                        </div>
                    </div>
                </dd>
            </dl>
        </div>
        <!--end of quickmenu-->
    </div>
</div>


<div id="wrap">
    <div id="container" class="clearfix">
               <?php echo $content;?>
    </div>
</div>

<div id="footer">
    <hr>
    <p>Copyright © 2018 viruskingkk All Rights Reserved </p>
</div>

<div style="text-align: center;">
<a href="http://exp.jiankongbao.com/load.php?host_id=10758" target="_blank"></a>
</div>
</body>
</html>
