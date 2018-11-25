<script type="text/javascript">
	$(function(){
		//顯示回復框
		$('.showReplyBox').click(function(){
			$('#show_box_title').text('回復：' + $(this).attr('_user_name'));
			$('#messageId').val($(this).attr('_id'));
			$('#send_reply').show();
		})

		//關閉顯示框
		$('#closeReplyBtn').click(function(){
			$('#send_reply').hide();
			$('#show_box_title').text('');
			$('#messageId').val('');
		})

		//發送消息
		$('#sendReplyBtn').click(function(){
			var reply_content = $('#replyMsg').val();
			var message_id = $('#messageId').val();
			if(!reply_content)
			{
				alert('回復的內容不能為空');
				return;
			}

			var url = "<?php echo Yii::app()->createUrl('message/replymsg');?>";
			$.ajax({
				type:'POST',
				url:url,
				dataType: "json",
				data:{content:reply_content,message_id:message_id},
				success:function(data){
					if(data.errorCode)
					{
						alert(data.errorText);
					}
					else if(data.success)
					{
						alert('回復成功');
						$('#closeReplyBtn').click();
					}
				}
			})
		})
	})
</script>
<div class="content-box">
      <!-- Start Content Box -->
      <div class="content-box-header">
        <h3>留言信息</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">列表</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <!-- End .content-box-header -->
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
          <table style="table-layout:fixed;">
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                <th>店名</th>
                <th>留言人</th>
                <th>留言內容</th>
                <th>留言時間</th>
                <th>狀態</th>
                <th>操作</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="7">
                 <?php $this->widget('application.widgets.MyLinkPager', array(
                 			'pages' 			=> $pages,
                 			'firstPageLabel' 	=> '首頁',
                 			'lastPageLabel' 	=> '末頁',
                 			'prevPageLabel' 	=> '前一頁',
                 			'nextPageLabel' 	=> '下一頁',
                 			'firstPageLabel' 	=> '首頁',
                 			'maxButtonCount' 	=> '5',
                 			'header'			=> '',
                 		));
                 ?>
                  <div class="clear"></div>
                </td>
              </tr>
            </tfoot>
            <tbody>
               <?php if(isset($data)):?>
               		<?php foreach ($data AS $k => $v):?>
		              <tr>
		                <td>
		                  <input type="checkbox" name="infolist[]" value="<?php echo $v['id'];?>" />
		                </td>
		                <td><?php echo $v['shop_name'];?></td>
		                <td><?php echo $v['user_name'];?></td>
		                <td style="overflow:hidden;text-overflow:ellipsis;word-break:keep-all;white-space:nowrap;"><?php echo $v['content'];?></td>
		                <td><?php echo $v['create_time'];?></td>
		                <td  _url="<?php echo Yii::app()->createUrl('message/audit',array('id' => $v['id'])); ?>"  class="status_row" style="cursor:pointer;color:<?php echo $v['status_color'];?>"><?php echo $v['status_text'];?></td>
		                <td>
		                  <!-- 
		                  <a href="javascript:void(0);" _href=""  class="button">詳情</a>
		                   -->
		                  <a href="javascript:void(0);"  class="button showReplyBox" _user_name="<?php echo $v['user_name'];?>" _id="<?php echo $v['id'];?>">回復</a>
		                  <a href="javascript:void(0);" _href="<?php echo Yii::app()->createUrl('message/delete',array('id' => $v['id']));?>"  class="remove_row button">刪除</a>
		                </td>
		              </tr>
              		<?php endforeach;?>
              <?php endif;?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- End .content-box-content -->
      <div id="send_reply" style="float:left;position:absolute;top: 168.2px; left: 515px; display:none;">       
      	<div style="width:450px;height:200px;border:10px solid gray;background:#fff;border-radius:10px;">
      		<div style="width:426px;height:160px;margin-left:10px;margin-top:20px;">
      			<form action="#" method="post">
		          <h4 id="show_box_title"></h4>
		          <fieldset>
		          	<textarea class="textarea" id="replyMsg"  cols="79" rows="5"></textarea>
		          </fieldset>
		          <fieldset>
		          	<input class="button" type="button" value="發送" id="sendReplyBtn" />
		          	<input class="button" type="button" value="關閉" id="closeReplyBtn" style="float:right;" />
		          	<input type="hidden" value="" id="messageId" />
		          </fieldset>
		        </form>
      		</div>
      	</div>
      </div>
</div>
