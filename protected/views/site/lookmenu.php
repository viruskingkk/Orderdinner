<?php 
$_cartInfo = array();
if($_COOKIE['cart'])
{
	$_cartInfo = json_decode($_COOKIE['cart'],1);
}
?>
<script type="text/javascript">
$(function(){
	var _cart = new CartHelper();
	//點擊購物車小圖示顯示購物車的商品
	$('#basketshow').click(function(){
		if($('#onlineOrder').css('display') == 'none')
		{
			$('#onlineOrder').slideDown('fast');
		}
		else
		{
			$('#onlineOrder').slideUp('fast');
		}
	})

	//清空購物車
	$('#emptyOrder').click(function(){
		$('#OrderBody').html('');
		$('#tbbasket').hide();
		$('#no_send').show();
		$('.addToOrder').removeClass('addMore');
		$('#totalPrice').html(0);
		_cart.Clear();
	})

	//添加商品到購物車
	$('.addToOrder').click(function(){
		var shop_id = $('#shop_id').val();
		if(_cart.Read().shop_id && (parseInt(shop_id) != parseInt(_cart.Read().shop_id)))
		{
			alert('當前購物車裡面的菜來自:《'+_cart.Read().shop_name+'》，請清空購物車之後再選菜');
			return;
		}
		else if(!_cart.Read().shop_id)
		{
			_cart.Init(shop_id,$('#shop_name').val());

		}
		
		//獲取菜的資訊
		var foodInfo = $(this).parent().parent();
		var food = {id:foodInfo.attr('food-foodid'),name:foodInfo.attr('food-foodname'),price:foodInfo.attr('food-price')};
		var _index = _cart.Find(food.id);

		if(_index > -1)
		{	
			//如果已經存在
			var o_num = _cart.Read().Items[_index].Count;
			var n_num = parseInt(o_num) + 1;
			$('#_num_'+food.id).html(n_num);
			_cart.Change(food.id,n_num);
		}
		else
		{
			var html  = '<tr food-id="'+food.id+'" id=food_list_'+food.id+'>';
			html += '<td><p class="food_name">'+food.name+'</p></td>';
			html += '<td><p class="food_tip">'+food.tip+'</p></td>';
			html += '<td>';
			html += '<div class="order_num">';
			html += '<a class="minus" href="javascript:void(0);">&nbsp;</a> ';
			html += '<span id=_num_'+food.id+'>1</span>';
			html += ' <a class="add"  href="javascript:void(0);">&nbsp;</a>';
			html += '</div>';
			html += '</td>';
			html += '<td>'+food.price+'</td>';
			html += '<td><a class="del" href="javascript:void(0);">刪除</a></td>';
			html += '</tr>';
	    	$('#OrderBody').append(html);
			_cart.Add(food.id,food.name,1,food.price);
		}

		//更新總的價格
		$('#totalPrice').html(_cart.Read().Total);

		//更改按鈕樣式
		$(this).addClass('addMore');
		$('#tbbasket').show();
		//樣式
		$('#no_send').hide();
		if($('#onlineOrder').css('display') == 'none')
		{
			$('#onlineOrder').slideDown('fast');
		}
	})
	
	//減少購物車裡面的數量
	$('.minus').live('click',function(){
		var next = $(this).next();
		var obj = next.parent().parent().parent();
		var food_id = obj.attr('food-id');
		var num = _cart.Read().Items[_cart.Find(food_id)].Count;//獲取當前該商品的數量
		if(--num <= 0)
		{
			num = 0;
			obj.remove();
			_cart.Del(food_id);
			$('#liFood_'+food_id).find('.addToOrder').removeClass('addMore');
			if(!_cart.Read().Count)
			{
				$('#no_send').show();
				$('#tbbasket').hide();
			}
		}
		else
		{
			next.html(num);
			//更新購物車的數量
			_cart.Change(food_id,num);
		}
		
		$('#totalPrice').html(_cart.Read().Total);
	})
	
	//增加購物車裡面的數量
	$('.add').live('click',function(){
		var prev = $(this).prev();
		var obj = prev.parent().parent().parent();
		var food_id = obj.attr('food-id');
		var num = _cart.Read().Items[_cart.Find(food_id)].Count;
		num++;
		prev.html(num);
		//更新購物車的數量
		_cart.Change(food_id,num);
		$('#totalPrice').html(_cart.Read().Total);
	})
	
	//刪除購物車裡面的一件商品
	$('.del').live('click',function(){
		var obj = $(this).parent().parent();
		var food_id = obj.attr('food-id');
		obj.remove();
		$('#liFood_'+food_id).find('.addToOrder').removeClass('addMore');
		_cart.Del(food_id);
		
		//如果購物車裡面沒有商品
		if(!_cart.Read().Count)
		{
			$('#no_send').show();
			$('#tbbasket').hide();
			//如果購物車裡面沒有商品，還要清除cookie
			_cart.Clear();
		}
		
		$('#totalPrice').html(_cart.Read().Total);
	})
	
	//點擊下單按鈕提交查看訂單的頁面
	$('#ComfirmOrder').click(function(){
		if(!_cart.Read().Count)
		{
			alert('您還沒有選擇菜，請選完之後再下單');
			return;
		}
		window.location.href=$(this).attr('_href');
	})
	
	if(_cart.Read().Count)
	{
		$('#onlineOrder').show();
		$('#tbbasket').show();
		$('#no_send').hide();
	}
	else
	{
		$('#tbbasket').hide();
		$('#no_send').show();
	}

	//為選項卡綁定事件
	$('#s_tab a').click(function(){
		if(!$(this).hasClass('active'))
		{
			//獲取當前顯示的元素
			var obj = $('#s_tab .active');
			//顯示自己
			$(this).addClass('active');
			$('#'+$(this).attr('_id')).show();
			//隱藏別人
			obj.removeClass('active');
			$('#'+obj.attr('_id')).hide();
		}
	})

	//提交留言
	$('#sendMessageBtn').click(function(){
		var content = $('#message_content').val();
		var validate_code = $('#validate_code').val();
		var shop_id = $('#shop_id').val();
		if(!content)
		{
			alert('留言不能為空');
			return;
		}

		if(!validate_code)
		{
			alert('驗證碼不能為空');
			return;
		}

		var url = "<?php echo Yii::app()->createUrl('site/submitmessage');?>";
		$.ajax({
			type:'POST',
			url:url,
			dataType: "json",
			data:{content:content,validate_code:validate_code,shop_id:shop_id},
			success:function(data){
				if(data.errorCode)
				{
					alert(data.errorText);
					if(parseInt(data.errorCode) == 1)
					{
						window.location.href = "<?php echo Yii::app()->createUrl('site/login');?>";
					}
				}
				else if(data.success)
				{
					alert(data.successText);
					window.location.href = $('#self_url').val() + '&show_msg=1';
				}
			}
		})
	})

	//判斷是否已進入頁面就顯示留言部分
	if(parseInt($('#show_msg').val()) || parseInt($('#page_num').val()))
	{
		$('#scrollPagerTab').removeClass('active');
		$('#scrollPager').hide();
		$('#tab_comment_tab').addClass('active');
		$('#tab_comment').show();
	}

	//回復
	$('.sm_reply').click(function(){
		$('#reply_id').val($(this).attr('_msg_id'));
		$('#reply_title').text('回復：' + $(this).parent().find('.sm_nick').text());		
		$('#reply_box').show();
	})

	//取消回復按鈕
	$('#cancel_reply').click(function(){
		$('#reply_box').hide();
		$('#reply_id').val('');
	})

	//提交回復
	$('#submit_reply').click(function(){
		var reply_content = $('#reply_content').val();//獲取回復的內容
		var reply_id = $('#reply_id').val();//獲取針對哪一條留言進行回復
		if(!reply_content)
		{
			alert('回復的內容不能為空');
			return;
		}

		if(!reply_id)
		{
			alert('請選擇回復哪條留言');
			return;
		}

		//提交回復
		var url = "<?php echo Yii::app()->createUrl('site/replymessage');?>";
		$.ajax({
			type:'POST',
			url:url,
			dataType: "json",
			data:{reply_content:reply_content,reply_id:reply_id},
			success:function(data){
				if(data.errorCode)
				{
					alert(data.errorText);
					if(parseInt(data.errorCode) == 1)
					{
						window.location.href = "<?php echo Yii::app()->createUrl('site/login');?>";
					}
				}
				else if(data.success)
				{
					//alert(data.successText);
					window.location.href = $('#self_url').val() + '&show_msg=1';
				}
			}
		})
	})
})
</script>
<div id="school">
   <em>當前餐廳：<?php echo $shop['name'];?></em>
   <input type="hidden" id="shop_id" value="<?php echo $shop['id'];?>" />
   <input type="hidden" id="shop_name" value="<?php echo $shop['name'];?>" />
   <input type="hidden" id="show_msg" value="<?php echo Yii::app()->request->getParam('show_msg');?>" />
   <input type="hidden" id="self_url" value="<?php echo Yii::app()->createUrl('site/lookmenu',array('shop_id' => $shop['id']));?>" />
   <input type="hidden" id="page_num" value="<?php echo Yii::app()->request->getParam('page');?>" />
</div>
<div id="rank" class="clearfix"></div>
<div class="clearfix">
    <div id="left" class="shadow s_menu">          
	    <div id="s_tab">
	        <a href="#" class="active" _id="scrollPager" id="scrollPagerTab">看菜單</a>
	        <a href="#" _id="tab_comment" id="tab_comment_tab">給餐廳留言</a>
	    </div>
	    <div id="scrollPager">
	                <div class="foodList clearfix">
	                    <div class="foodListImg">
	                          <table cellpadding="0" cellspacing="0">
			                            <tbody>
				                            <?php 
				                         		$_tr_num = count($menus)/3;
				                         		for($i = 0;$i<$_tr_num;$i++):
				                         	?>
				                         	<tr>
				                              		<?php for($j = 0;$j<3;$j++):
				                              				$_index = $i * 3 + $j;
				                                    		if($_index >= (count($menus)))
				                                    		{
				                                    			break;
				                                    		}
				                              		?>	
						                            <td class="foodListItem" id="liFood_<?php echo $menus[$_index]['id'];?>" food-foodid="<?php echo $menus[$_index]['id'];?>" food-foodname="<?php echo $menus[$_index]['name'];?>"  food-price="<?php echo $menus[$_index]['price'];?>" food-foodstate="0" food-foodunit="份" >
						                                <img src="<?php if($menus[$_index]['index_pic']):?><?php echo $menus[$_index]['index_pic']; ?><?php else:?><?php echo Yii::app()->baseUrl;?>/assets/images/defaultMenu.jpg<?php endif;?>" width="190px" height="139px" >
						                                <p class="foodTitle">
						                                    <?php echo $menus[$_index]['name'];?><span class="unit">(份)</span>
						                                </p>
						                                <p class="food_remark">
						                              	</p>
														<p>備註：<input class="text-input small-input" type="text"  name="Menus[tip]" style=width:120px value="無"<?php echo CHtml::encode($data['tip']); ?>"/></p>
														<br>
						                                <p class="price_outer">
						                                    <span class="fr addToOrder">來一份</span>
						                                    <span class="price"><?php echo $menus[$_index]['price'];?>元</span>
						                                </p>
						                            </td>
						                            <?php endfor;?>
						                      </tr>
						                      <?php endfor;?>
			                            </tbody>
	                           </table> 
	                    </div> 
	                </div>
	    </div>
	    <!-- 留言區域 start-->
	    <div id="tab_comment" style="display:none;">
	    			<?php if($message):?>
	    			<?php foreach ($message AS $_k => $_v):?>
	                <div class="sm_list">
	                    <p><?php echo $_v['content'];?></p>
	                    <p>
	                        <span class="sm_nick"><?php echo $_v['user_name'];?></span>
	                        <span class="sm_time"><?php echo $_v['create_time'];?></span>
	                        <a href="javascript:;"  class="sm_reply" _msg_id="<?php echo $_v['id'];?>">回復</a>
	                    </p>
	                    <?php if($_v['replys']):?>
	                    <?php foreach ($_v['replys'] AS $kk => $vv):?>
                        <div class="reply_info">
                            <p><?php echo $vv['content'];?></p>
                            <p>
                               <span class="sm_nick">[<?php echo ($kk + 1);?>樓] <?php echo $vv['user_name'];?></span>
                               <span class="sm_time"><?php echo $vv['create_time'];?></span>
                            </p>
                        </div>
                        <?php endforeach;?>
                        <?php endif;?>
	                </div>
	                <?php endforeach;?>
	                <?php endif;?>
	        <div id="pageHtml">
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
	        </div>
	        <div class="make_msg">
	            <div>
	                <label>留言：</label>
	                <textarea name="content" id="message_content"></textarea>
	            </div>
	            <div class="chk_code">
	                <label>驗證碼：</label>
	                <input type="text" id="validate_code" />
	                <div id="ValidateCode1" style="margin-top:0px;">
					    <?php $this->widget('CCaptcha',array(
					        'showRefreshButton'	=> true,
					        'clickableImage'	=> false,
					    	'buttonLabel'		=>'刷新驗證碼',
					        'imageOptions'		=> array(
										            'style'		=>'cursor:pointer;width:90px;height:28px;border:0px solid #ddd',
										            'padding'	=>'10'
					    						)
					        )); 
					     ?>
					 </div>
	            </div>
	            <div class="btn">
	                <input type="button" value="提交" id="sendMessageBtn">
	                <span class="validRs"></span>
	            </div>
	        </div>
	        <div class="clear"></div>
	    </div>
	    <!-- 留言區域 end-->
    </div>
    <!--end of left-->
	<div id="right">
        <div class="right_item shadow" style="margin-top: 0px;">

             <p class="shop-info">
                  電話：<?php echo $shop['tel'];?><br>
                  連絡人：<?php echo $shop['linkman'];?><br>
                  餐廳地址：<?php echo $shop['address'];?>
             </p>
        </div>
                  
        <div id="foodBasket">
              <div class="right_item shadow" id="onlineOrder" style="display: none; border-top-color: rgb(125, 181, 0);">
                            <table id="tbbasket" cellpadding="0" cellspacing="0" width="100%" style="display: none;">
                                <caption>美食籃</caption>
                                <thead id="OrderHead">
                                    <tr>
                                        <th class="col1">
                                            菜品
                                        </th>
                                        <th class="col2">
                                            份數
                                        </th>
                                        <th class="col3">
                                            單價
                                        </th>
                                        <th class="col4">
                                            備註
                                        </th>
                                        <th class="col5">
                                            <a href="javascript:void(0)" id="emptyOrder">清除</a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="OrderBody">
                                	<?php if($_cartInfo['Items']):?>
                                		<?php foreach($_cartInfo['Items'] AS $k => $v):?>
                                		<tr food-id="<?php echo $v['Id'];?>" id="food_list_<?php echo $v['Id'];?>">
	                                		<td>
	                                			<p class="food_name"><?php echo $v['Name'];?></p>
	                                		</td>
	                                		<td>
		                                		<div class="order_num">
			                                		<a class="minus" href="javascript:void(0);">&nbsp;</a> 
			                                			<span id="_num_<?php echo $v['Id'];?>"><?php echo $v['Count'];?></span> 
			                                		<a class="add" href="javascript:void(0);">&nbsp;</a>
		                                		</div>
	                                		</td>
	                                		<td><?php echo $v['Price'];?></td>
	                                		<td>
	                                			<p class="food_tip"><?php echo $menus['tip'];?></p>
	                                		</td>
	                                		<td><?php echo $tip;?></td>
	                                		<td><a class="del" href="javascript:void(0);">刪除</a></td>
                                		</tr>
                                		<?php endforeach;?>
                                	<?php endif;?>
                                </tbody>
                                <tfoot id="OrderFoot">
                                    <tr id="delivery" style="display: none;">
                                        <td colspan="5" class="food_name" style="">
                                            
                                        </td>
                                    </tr>
                                    <tr class="last">
                                        <td colspan="3" class="order_total">
                                            <p>總價</p>
                                        </td>
                                        <td>
                                            <p id="totalPrice" class="order_price"><?php echo $_cartInfo['Total'];?></p>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div id="no_send" style="display:none;">
                                <p id="no_send_inner">美食籃是空的！</p>
                            </div>
            	</div>
                <div id="createOrder">
                            <div class="shadow" style="height: 38px; background-color: #2c2c2c;">
                                <i id="basketshow" class="fl">
                                    <img src="<?php echo Yii::app()->baseUrl;?>/assets/images/front/no_food.png" width="28px" height="28px">
                                </i>
                                <a id="ComfirmOrder" href="javascript:void(0);" _href="<?php echo Yii::app()->createUrl('site/lookcart');?>" class="fr">去下單&gt; </a>
                                <a id="seeNum" href="javascript:;" class="fr" style="display: none;">查看電話&gt; </a>
                            </div>
                </div>
          </div>
    </div>
    <div class="select_address shade_shadow" id="reply_box">
        <h2 id="reply_title"></h2>
        <div style="max-height: 250px; overflow-y: auto; overflow-x: hidden">
	            <textarea id="reply_content" style="width:550px;height:100px;"></textarea>
        </div>
        <p class="mt_20">
            <a href="javascript:;" class="orange_btn" id="submit_reply">提交</a> 
            <a href="javascript:;" class="cancel_btn" id="cancel_reply">取消</a>
        </p>
        <input type="hidden" id="reply_id" value="" />
    </div>
</div>
