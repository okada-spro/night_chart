
<div class="login_area">

	<div class="login_box">

		<div class="login_box_str">ログイン</div>


		<?php if(isset( $_GET["err_code"])){ ?>

			<div class="login_err">ユーザー名、メールアドレスかパスワードに誤りがあります</div>

		<?php } ?>

		<div class="login_box_input">

			<form action="<?php echo home_url();?>/wp-login.php" method="POST"  name="loginform" id="loginform">

				<div class="login_box_input_area">
					<div class="login_box_input_title">ユーザー名またはメールアドレス</div>
					
					<div class="login_box_input_textbox">
						
						<input type="text" name="log" id="user_login" class="input" value=""  autocapitalize="off" autocomplete="username">

					</div>

					<div class="login_box_input_title">パスワード</div>
					
					<div class="login_box_input_textbox">
						<input type="password" name="pwd" id="user_pass" class="input password-input" value="" autocomplete="current-password" spellcheck="false">
					</div>
				</div>
				
				<input name="redirect_to" type="hidden" value="<?php echo home_url();?>">
				<input type="hidden" name="testcookie" value="1">


				<div class="login_box_input_checkbox">	
					<input name="rememberme" type="checkbox" value="forever"><label class="login_box_input_label">ログイン状態を保存する</label>
				</div>

				<div class="login_box_input_submit">	
					<input type="submit" name="wp-submit" id="wp-submit" value="ログイン" >
				</div>
			</form>
		</div>


		<div class="login_box_input_link_area" style="font-size:20px">	
			<a href="<?php echo home_url();?>/register/">新規登録</a>
		</div>
		
			<div class="login_box_input_link_area">

				<a class="" href="https://rakurakunikki.com/easy_trade_diary/wp-content/themes/easy_trade_diary/manual/裏歴史研究会新規登録方法.mp4" target="_blank" >
				<!-- <a class="watch-btn" href="https://rakurakunikki.com/easy_trade_diary/wp-content/themes/easy_trade_diary/manual/裏歴史研究会新規登録方法.mp4" target="_blank"> -->
					新規会員登録方法を動画で見る
				</a>
			</div>
		

		<div class="login_box_input_link_area">	
			<a href="<?php echo home_url();?>/%e3%83%97%e3%83%ad%e3%83%95%e3%82%a3%e3%83%bc%e3%83%ab%e3%83%9a%e3%83%bc%e3%82%b8/?a=pwdreset">パスワードを忘れた場合</a>
		</div>
		<div class="login_box_input_link_area">	
			
			<a class="" href="https://mpwks.jp/privacy_policy/">特商法・プライバシーポリシー</a>
		</div>
		
		
	</div>
</div>

<style>
	a.watch-btn{
		background: transparent linear-gradient(284deg, #265FAB 0%, #08287F 100%) 0% 0% no-repeat padding-box;
		box-shadow: 0px 3px 0px #001F6F;
		border-radius: 5px;
		opacity: 1;
		font-size: 18px;
		font-weight: 300;
		padding: 20px;
		margin: 20px 40px;
		color: #fff;
		text-align: center;
		display: block;

	}
</style>