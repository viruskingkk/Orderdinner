<div id="left">
                <!--線上餐廳開始-->
                <div id="onlineSup" class="sup_list">
                    <div class="sup_list_title">
                        <h2>在線訂餐</h2>
                        <p class="si_filter">
                            <!--  <input id="showOnLine" class="fl" type="checkbox"><span class="fl">僅顯示營業中</span>-->
                        </p>
                        <p class="step">
                            <span>交預付款--></span><span>選餐廳--></span><span>選美食--></span><span>下訂單--></span><span>由前台女神統一訂購</span>
                        </p>
                    </div>
                    <div class="sup_list_body shadow" id="hallListOnline">
                        <table cellpadding="0" cellspacing="0" id="SupplierListBody">       
                         <tbody>
                            <?php 
                                $_tr_num = count($shops)/4;
                                for($i = 0;$i<$_tr_num;$i++):
                            ?>
                                <tr>
                                    <?php for($j = 0;$j<4;$j++):
                                            $_index = $i * 4 + $j;
                                            if($_index >= (count($shops)))
                                            {
                                                break;
                                            }
                                    ?>  
                                    <td>
                                        <div class="si_block<?php if(!$isOnTime):?> si_closed <?php endif;?>" sid="0">
                                            <div class="si_logo">
                                                <?php if($isOnTime):?>
                                                <a href="<?php echo Yii::app()->createUrl('site/lookmenu',array('shop_id' => $shops[$_index]['id']));?>">
                                                <?php else:?>
                                                <a href="javascript:alert('不好意思啦，已經結束啦');">
                                                <?php endif;?>
                                                    <img src="<?php echo $shops[$_index]['logo'];?>"   width="43px" height="43px" style="display: inline;">
                                                </a>
                                            </div>
                                            <div class="si_info">
                                                <p class="si_name">
                                                    <?php if($isOnTime):?>
                                                    <a href="<?php echo Yii::app()->createUrl('site/lookmenu',array('shop_id' => $shops[$_index]['id']));?>">
                                                    <?php else:?>
                                                    <a href="javascript:alert('不好意思啦，已經打烊啦');">
                                                    <?php endif;?>
                                                    <?php echo $shops[$_index]['name'];?>
                                                    </a>
                                                </p>
                                                <?php if($isOnTime):?>
                                                <p class="si_rec star">推薦度：0星</p>
                                                <p class="si_com"><em>&nbsp;</em></p>
                                                <?php if ($shops[$_index]['url']):?>
                                                    <a href="<?php echo $shops[$_index]['url'];?>" target="_blank">商家網站</a>
                                                <?php endif;?>
                                                <?php else:?>
                                                <span class="rest"></span>
                                                <p class="rest">已休息</p>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </td>
                                    <?php endfor;?>
                               </tr> 
                               <?php endfor;?>                               
                        </tbody>
                        </table>
                    </div>
                </div>
                <!--線上餐廳結束-->
</div>

<div id="right">
    <div class="right_item shadow" id="siteNotice">
        <h3>餐廳公告</h3>
        <div class="ri_body">
        <?php foreach ($announce AS $_k => $_v):?>
        <p><?php echo $_v['content'];?></p>
        <?php endforeach;?>
        </div>
    </div>
    <div class="right_item shadow" id="customerService">
        <h3>
            訂餐服務</h3>
        <div class="ri_body">
            <p>前台服務:Anita</p>
            <p>技術支持:技術部</p>
        </div>
    </div>

    <div class="right_item shadow" id="customerService">
        <h3>帳戶餘額不足20元的會員名單</h3>
        <div class="ri_body">
            <?php if($members):?>
            <?php foreach ($members AS $_k => $_v):?>
                <p><?php echo $_v['name'];?>-------找零<?php echo $_v['balance'];?>元</p>
            <?php endforeach;?>
            <?php else:?>
                <p>暫時沒有</p>
            <?php endif;?>
        </div>
    </div>
    <div class="right_item shadow" id="focusUs">
        <h3>吃飯啦</h3>
        <div class="ri_body">
            <script charset="Shift_JIS" src="http://chabudai.sakura.ne.jp/blogparts/honehoneclock/honehone_clock_tr.js"></script>
        </div>
    </div>
</div>
