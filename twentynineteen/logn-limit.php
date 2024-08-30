<?php



?>

<div class="index_center">

	<div class="ban-keikoku-main">

		<div class="ban-keikoku-str">ログイン制限中</div>

		<div class="ban-keikoku-main-area">

		<b>現在、ご利用期限が切れている為、<br>お客様の権限が停止されております。</b>
		<br>
		<br>運営までご連絡を下さい。
		<br>
		info@nightchart.jp<br>
		<br>
		</div>

		<p>
			<a class="index_user_button index_top_button_setting_color" href="<?php $id = 762; echo get_page_link( $id );?>">お問い合わせ</a>
		</p>
			
		<p>
			<a class="index_user_button index_top_button_logout_color" href="<?php  echo wp_logout_url();?>">ログアウト</a>
		</p>

	</div>

</div>