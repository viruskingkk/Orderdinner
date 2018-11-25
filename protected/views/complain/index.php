<div class="content-box">
      <!-- Start Content Box -->
      <div class="content-box-header">
        <h3>功能表資訊</h3>
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
                <th>吐槽內容</th>
                <th>創建時間</th>
                <th>操作</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td>
                  <a href="<?php echo Yii::app()->createUrl('menus/form');?>" class="button" style="float:left;margin-left:10px;">新增</a>
                </td>
                <td colspan="7">
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
                      <input type="checkbox" name="line_{$v['appid']}" value="{$v['appid']}" />
                    </td>
                    <td><?php echo $v['user_name'];?></td>
                    <td><?php echo $v['content'];?></td>
                    <td><?php echo $v['create_time'];?></td>
                    <td>
                      <a href="<?php echo Yii::app()->createUrl('complain/delete',array('id' => $v['id']));?>" title="Delete"><img src="<?php echo Yii::app()->baseUrl;?>/assets/images/icons/cross.png" alt="Delete" /></a>
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
