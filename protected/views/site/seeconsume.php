<div class="shadow clearfix" id="pCenter">
                 <div id="pMenu" class="gray" style="height: 519px;">
                    <div class="border">
                        <dl>
                        <dt>個人中心</dt>
                        <dd><a href="<?php echo Yii::app()->createUrl('site/membercenter');?>" class="n1">我的帳戶</a></dd>
                        <dd><a href="<?php echo Yii::app()->createUrl('site/modifypassword');?>" class="n10">修改密碼</a></dd>
                        </dl>
                    </div>
                    <div class="border">
                        <dl>
                        <dt>訂單中心</dt>
                        <dd><a href="<?php echo Yii::app()->createUrl('site/myorder',array('today' => 1));?>" class="n2">今日訂單</a></dd>
                        <dd><a href="<?php echo Yii::app()->createUrl('site/myorder');?>" class="n3">歷史訂單</a></dd>
                        <dd><a href="<?php echo Yii::app()->createUrl('site/seeconsume');?>" class="n3">消費記錄</a></dd>
                        </dl>
                    </div>
                    <div class="border">
                        <dl>
                        <dt>資訊中心</dt>
                        <dd><a href="<?php echo Yii::app()->createUrl('site/systemnotice');?>" class="n7">系統公告</a></dd>
                        </dl>
                    </div>
                </div>
                
                <div id="pContent">
                    <div id="sysNotice">
                        <h1>消費記錄</h1>
                        <div class="sys_con">
                                <?php if(!$data):?>
                                <p class="not_title">
                                <span>暫時還沒有消費記錄...</span>
                                </p>
                                <?php else:?>
                                <?php foreach ($data AS $k => $v):?>
                                <p class="not_title">
                                <span><?php echo $v['user_name'];?>--------<span style="color:<?php if($v['type']):?>blue;<?php else:?>red;<?php endif;?>"><?php echo $v['type_text'];?></span>--------<?php echo $v['money'];?>元--------<?php echo $v['create_time'];?></span>
                                </p>
                                <?php endforeach;?>
                                <?php endif;?>
                        </div>
                    </div>
                    <?php $this->widget('application.widgets.MyLinkPager', array(
                            'pages'             => $pages,
                            'firstPageLabel'    => '首頁',
                            'lastPageLabel'     => '末頁',
                            'prevPageLabel'     => '前一頁',
                            'nextPageLabel'     => '下一頁',
                            'maxButtonCount'    => '5',
                            'header'            => '',
                        ));
                    ?>
                </div>
</div>
<!--end of pCenter -->
