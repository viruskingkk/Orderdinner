<script type="text/javascript">
$(function(){
	$('#LoginIn').click(function(){
		var name = $('#name').val();
		var password = $('#password').val();
		$.ajax({
		   type: "POST",
		   url: "<?php echo Yii::app()->createUrl('site/dologin');?>",
		   data: "name="+name+"&password="+password,
		   success: function(msg)
		   {
				var obj = eval('('+msg+')');		     	
				if(obj.error == 1)
				{
					alert('用戶名不能為空');
					return;
				}

				if(obj.error == 2)
				{
					alert('密碼不能為空');
					return;
				}

				if(obj.error == 3)
				{
					alert('用戶名或者密碼錯誤');
					return;
				}

				window.location.href="<?php echo Yii::app()->request->urlReferrer;?>";
				
		   }
		});
	})
})
</script>
<div class="login shadow">
                <div class="login-title">
                    <h3>
                        用戶登錄</h3>
                </div>
                <ul>
                    <li class="login_mail">
                        <label for="Account">
                            用戶名：</label>
                        <input type="text" name="name" id="name" />
                    </li>
                    <li class="login_password">
                        <label for="Password">
                            密碼：</label>
                        <input type="password" id="password" name="password" >
                    </li>
                    <li class="login_btn">
                        <input type="submit" id="LoginIn" value="登錄" >
                        <p>
                            <a href="#">忘記密碼？</a></p>
                    </li>
                </ul>
                <div class="member">
                    <span>還沒有開吃吧帳號？</span><a href="<?php echo Yii::app()->createUrl('site/register')?>">註冊</a>
                </div>
</div>
