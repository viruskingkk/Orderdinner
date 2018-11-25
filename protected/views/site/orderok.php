<div id="wrap" style="width: 750px;">
        <div id="order" class="shadow">
                <div class="suc_text">
                    <div class="clearfix">
                        <h2 class="fl">恭喜您：<?php echo $order_info['username'];?>  您的訂單提交成功!</h2>
                    </div>
                </div>
                <div class="suc_block">
                    <p>訂單號：<?php echo $order_info['order_number'];?>  下單時間：<?php echo $order_info['create_time'];?></p>
                    <p>美食將在13點之前送到，請好好上班！</p>
                </div>

                <p class="goto">
                    <a href="<?php echo Yii::app()->createUrl('site/myorder',array('today' => 1));?>">追蹤訂單狀態</a> | <a href="<?php echo Yii::app()->createUrl('site');?>">返回首頁</a>
                </p>
        </div>
</div>
