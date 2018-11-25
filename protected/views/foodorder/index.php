<script type="text/javascript">
$(function(){
	$('.deduct_money').click(function(){
		if(confirm('您確認要扣款'))
		{
			var url = $(this).attr('_href');
			$.ajax({
				type:'GET',
				url:url,
				dataType: "json",
				success:function(data){
					if(data.errorCode)
					{
						alert(data.errorText);
					}
					else if(data.success)
					{
						alert('扣款成功');
						window.location.reload();
					}
				}
			})
		}
	})

	$('.cancel_order').click(function(){
		if(confirm('您確認要取消訂單'))
		{
			var url = $(this).attr('_href');
			$.ajax({
				type:'GET',
				url:url,
				dataType: "json",
				success:function(data){
					if(data.errorCode)
					{
						alert(data.errorText);
					}
					else if(data.success)
					{
						alert('取消訂單成功');
						window.location.reload();
					}
				}
			})
		}
	})

	//根據日期查詢訂單
	$('#searchOrder').click(function(){
		var date = $('#date').val();
		if(!date)
		{
			alert('請輸入要查詢訂單的日期，格式如：2014-05-20');
			return;
		}

		var url = "<?php echo Yii::app()->createUrl('foodorder/todayorder');?>";
		window.location.href = url+'&date='+date;
	})

	//當天訂單一健扣款
	$('#onekey').click(function(){
		if(confirm('您確定要為今天未付款訂單扣款嗎？'))
		{
			var url = "<?php echo Yii::app()->createUrl('foodorder/onekey');?>";
			$.ajax({
				type:'GET',
				url:url,
				dataType: "json",
				success:function(data){
					if(data.errorCode)
					{
						alert(data.errorText);
					}
					else if(data.success)
					{
						alert(data.successText);
						window.location.reload();
					}
				}
			})
		}
	})
})
</script>
<div class="content-box">
      <!-- Start Content Box -->
      <div class="content-box-header">
        <h3>訂單資訊</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">列表</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <!-- End .content-box-header -->
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
        	<input type="button" value="一鍵扣款" class="button" id="onekey" />
        	<label style="color: red">溫馨提示：只扣除今天的未付款訂單，帳戶餘額不足的不予處理</label>
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                <th>訂單號</th>
                <th>所屬商家</th>
                <th>用戶名</th>
                <th>訂單狀態</th>
                <th>總價</th>
                <th>創建時間</th>
                <th>操作</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
              	<td  colspan="2">
              		<a href="<?php echo Yii::app()->createUrl('foodorder/todayorder');?>" class="button">今日訂單統計快速通道</a>
              	</td>
              	<td>
              		<label>按日期查詢訂單：</label>
              	</td>
              	<td colspan="2">
              		<input class="text-input small-input" type="date"  id="date" />
              		<input type="button" class="button" value="查詢" id="searchOrder" />
              	</td>
                <td colspan="3">
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
                  <!-- End .pagination -->
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
		                <td><?php echo $v['order_number'];?></td>
		                <td><?php echo $v['shop_name'];?></td>
		                <td><?php echo $v['user_name'];?></td>
		                <td style="cursor:pointer;color:<?php echo $v['status_color'];?>"><?php echo $v['status_text'];?></td>
		                <td><?php echo $v['total_price'];?></td>
		                <td><?php echo $v['create_time'];?></td>
		                <td>
		                  <!-- Icons -->
		                  <a href="javascript:void(0);" _href="<?php echo Yii::app()->createUrl('foodorder/deductmoney',array('id' => $v['id']));?>" class="deduct_money button">扣款</a>
		                  <a href="javascript:void(0);" _href="<?php echo Yii::app()->createUrl('foodorder/cancelorder',array('id' => $v['id']));?>" class="cancel_order button">取消訂單</a>
		                  <a href="<?php echo Yii::app()->createUrl('foodorder/form',array('id' => $v['id']));?>" title="查看"><img src="<?php echo Yii::app()->baseUrl;?>/assets/images/icons/information.png" alt="查看" /></a> 
		                  <a href="javascript:void(0);" _href="<?php echo Yii::app()->createUrl('foodorder/delete',array('id' => $v['id']));?>"  class="remove_row"><img src="<?php echo Yii::app()->baseUrl;?>/assets/images/icons/cross.png" alt="Delete" /></a>
		                </td>
		              </tr>
              		<?php endforeach;?>
              <?php endif;?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- End .content-box-content -->
</div>
