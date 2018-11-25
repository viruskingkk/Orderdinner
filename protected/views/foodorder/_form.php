<div class="content-box">
      <!-- Start Content Box -->
      <div class="content-box-header">
        <h3>訂單詳情</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">表單</a></li>
        </ul>
        <div class="clear"></div>
      </div>
	  <div class="content-box-content">
		<div class="tab-content default-tab" id="tab1">
          <form action="#" method="post" enctype="multipart/form-data">
            <fieldset>
	            <p>
	              <label>訂單號：<?php echo $data['order_number'];?></label>
	            </p>
	            
	            <p>
	              <label>餐廳名：<?php echo $data['shop_name'];?></label>
	            </p>
	            
	            <p>
	              <label>下單人：<?php echo $data['user_name'];?></label>
	            </p>
	            
	            <p>
	              <label>總價：<?php echo $data['total_price'];?>元</label>
	            </p>
	            
	            <p>
	               <?php foreach($data['product_info'] AS $k => $v):?>
	              <label><?php echo $v['Name'];?>x<?php echo $v['Count'];?>------------------------<?php echo $v['Price'];?>元</label>
	              <?php endforeach;?>
	            </p>
	            
	            <p>
	              <label>下單時間：<?php echo $data['create_time'];?></label>
	            </p>
	            
	            <p>
	              <label>訂單動態</label>
	            </p>
	            
            </fieldset>
            <div class="clear"></div>
            <!-- End .clear -->
          </form>
        </div>
     </div>
 </div>
