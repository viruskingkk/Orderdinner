<div class="content-box">
      <!-- Start Content Box -->
      <div class="content-box-header">
        <h3>使用者資訊</h3>
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
                <th>用戶名</th>
                <th>類型</th>
                <th>額度</th>
                <th>操作時間</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="5">
                 <?php $this->widget('application.widgets.MyLinkPager', array(
                      'pages'       => $pages,
                      'firstPageLabel'  => '首頁',
                      'lastPageLabel'   => '末頁',
                      'prevPageLabel'   => '前一頁',
                      'nextPageLabel'   => '下一頁',
                      'firstPageLabel'  => '首頁',
                      'maxButtonCount'  => '5',
                      'header'      => '',
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
                    <td><?php echo $v['user_name'];?></td>
                    <?php 
                      if($v['type'])
                      {
                        $color = 'blue';
                      }
                      else 
                      {
                        $color = 'red';
                      }
                    ?>
                    <td style="color:<?php echo $color;?>"><?php echo $v['type_text'];?></td>
                    <td><?php echo $v['money'];?></td>
                    <td><?php echo $v['create_time'];?></td>
                 </tr>
                  <?php endforeach;?>
              <?php endif;?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- End .content-box-content -->
</div>
