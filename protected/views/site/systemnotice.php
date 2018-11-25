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
                        <h1>
                            系統公告</h1>
                        <div class="sys_con">
                            	<?php if(!$announce):?>
                            	<p class="not_title">
                                <span>暫時還沒有系統公告...</span>
                                </p>
                                <?php else:?>
                                <?php foreach ($announce AS $k => $v):?>
                                <p class="not_title">
                                <span><?php echo $v['content'];?>-----------<?php echo $v['create_time'];?></span>
                                </p>
                                <?php endforeach;?>
                                <?php endif;?>
                        </div>
                    </div>
                </div>
</div>
<!--end of pCenter -->
