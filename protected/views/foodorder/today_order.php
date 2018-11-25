<div class="content-box">
      <!-- Start Content Box -->
      <div class="content-box-header">
        <h3><?php if($date):?><?php echo $date;?><?php else:?>今日<?php endif;?> 訂單統計</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">列表</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <!-- End .content-box-header -->
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                <th>所屬商家</th>
                <th>用戶名</th>
                <th>訂單狀態</th>
                <th>訂餐內容</th>
                <th>總價</th>
                <th>下單時間</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="7">
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
                    <td style="cursor:pointer;color:<?php echo $v['status_color'];?>"><?php echo $v['status_text'];?></td>
                    <td>
                      <?php foreach($v['product_info'] AS $_k => $_v):?>
              <p><?php echo $_v['Name'];?> : <?php echo $_v['Price'];?> x <?php echo $_v['Count'];?>------<?php echo $_v['smallTotal'];?>元</p>                      
              <?php endforeach;?>
                    </td>
                     <td>¥ <?php echo $v['total_price'];?></td>
                     <td><?php echo $v['create_time'];?></td>
                  </tr>
                  <?php endforeach;?>
              <?php endif;?>
            </tbody>
          </table>
        </div>
      </div>
      
      <span style="display:block;height:30px;font-size:20px;text-align:center;">統計結果如下：</span>
      
      <div class="content-box-content">
        <table>
          <thead>
              <tr>
                <th>商家</th>
                <th>詳情</th>
                <th>小計</th>
              </tr>
            </thead>
            <tbody>
          <?php foreach($statistics AS $k => $v):?>
          <tr>
            <td><?php echo $v['name'];?></td>
            <td>
              <?php foreach ($v['product'] AS $_k => $_v):?>
              <p><?php echo $_v['name'];?> x <?php echo $_v['count'];?>------小計：<?php echo $_v['smallTotal'];?>元</p>
              <?php endforeach;?>
            </td>
            <td>
            <?php echo $v['shop_total_price'];?>元
            </td>
          </tr>
          <?php endforeach;?>
          </tbody>
          <tfoot>
            <tr>
              <td>總計：</td>
              <td></td>
              <td><?php echo $total_price;?>元</td>
            </tr>
          </tfoot>
        </table>
      </div>
</div>
