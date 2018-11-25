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
						<dd><a href="<?php echo Yii::app()->createUrl('site/seeconsume');?>" class="n3">消費紀錄</a></dd>
						</dl>
					</div>
					<div class="border">
						<dl>
						<dt>資訊中心</dt>
				        <dd><a href="<?php echo Yii::app()->createUrl('site/systemnotice');?>" class="n7">系統公告</a></dd>
						</dl>
					</div>
				</div>

                <div id="pAccount">
                    <div id="ac_info" class="acc_con">
                        <p class="ac_title">個人資訊</p>
                        <div class="ac_content">
                            <table>
                                <colgroup>
                                <col class="info_title">
                                <col class="info_con">
                                <col class="info_title">
                                <col class="info_con">
                                </colgroup>
                                <tbody>
	                                <tr>
	                                    <td>
	                                        <b>我的郵箱</b>
	                                    </td>
	                                    <td>
	                                        <?php echo $member['email'];?>
	                                    </td>
	                                    <td>
	                                        <b>我的名稱：</b>
	                                    </td>
	                                    <td>
	                                        <p id="nickName" class="pos_relative">
	                                            <span id="dispName">
	                                            <!--<a href="javascript:;" class="fr" id="chgName">修改昵稱</a>-->
	                                            <?php echo $member['name'];?>
	                                            </span>
	                                            <span id="updateName" style="display: none">
	                                                <input name="NewNickname" type="text" value="<?php echo $member['name'];?>" id="NewNickname">
	                                                <input type="submit" name="btn_info" value="確認修改" onclick="return checkNickname();" id="btn_info">
	                                            </span>
	                                            <span id="success" class="tip_layer tl_right br6" style="display: none">修改成功！</span> 
	                                            <span class="tip_layer tl_error br6" style="display: none" id="nicknameNotEmpty">昵稱不能為空！</span>
	                                        </p>
	                                    </td>
	                                </tr>
	                                <tr>
	                                    <td>
	                                        <b>帳戶餘額：</b>
	                                    </td>
	                                    <td>
	                                        <em class="fl">￥<?php echo $member['balance'];?></em>
	                                    </td>
	                                    <td>
	                                        <b>綁定手機：</b>
	                                    </td>
	                                    <td>
	                                        <span id="bindPhone" class="fl">未綁定手機</span>
	                                        <!-- <a class="fr click_bind" href="javascript:;">去綁定</a> -->
	                                    </td>
	                                </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
</div>
<!--end of pCenter -->
