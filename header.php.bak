<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/my-style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	
	<?php // 追加スタイル?>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/history_list_admin_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/history_list_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/input_history_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />




	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/my-mail.css?<?php echo date('Ymd-Hi'); ?>" type="text/css" />
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/zoom-style.css?<?php echo date('Ymd-Hi'); ?>" type="text/css" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/js/jquery.tablesorter.min.js"></script>
	<script type="text/javascript" src="http://mottie.github.io/tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
	<link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/layerBoard.css?<?php echo date('Ymd-Hi'); ?>" media="all" />
	<script  type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.cookie.js"></script>
	<script  type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.layerBoard.js"></script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<style type="text/css">
<!--
ul.header-dropmenu {
  list-style: none;
  width: 70%;
  height: 40px;
  margin: 0 auto;
  padding: 0;
  display: table;
  table-layout: fixed;
}
@media screen and (max-width: 1024px){
    /* 12/3 sp対応 作業中*/
    ul.header-dropmenu {
        width: 95%;
    }
}
@media screen and (max-width: 560px){
    /* 12/3 sp対応 作業中*/
    ul.header-dropmenu {
        width: 100%;
    }
}
ul.header-dropmenu > li {
  position: relative;
  display: table-cell;
  vertical-align: middle;
  border: 1px solid #f8f8f8;
  background: #CCCCCC; /* 背景色*/
}
ul.header-dropmenu li a {
  display: block;
  text-align: center;
  line-height: 40px;
  font-weight: bold;
  text-decoration: none;
  font-size: 14px;
}
ul.header-dropmenu li ul {
  visibility: hidden;
  width: 100%;
  list-style: none;
  position: absolute;
  top: 100%;
  left: -1px;
  margin: 0;
  padding: 0;
  border: 1px solid #222; /* マウスオーバー時の枠線 */
  border-top: none;
}
ul.header-dropmenu li:hover ul {
  visibility: visible;
  z-index: 1;
}
ul.header-dropmenu li ul li {
  background: #fff;
  transition: all .2s ease;
}
ul.header-dropmenu > li:hover {
  background: #fff;
  border: 1px solid #222; /* マウスオーバー時の枠線 */
  border-bottom: none;
}
ul.header-dropmenu li:hover ul li:hover {
  background: #f8f8f8;
}
-->

/* 12/6 sp対応 */
.mode-pc{
	display:block;
}
.mode-tab,
.mode-sp,
ul.header-dropmenu li a.mode-tab{
	display:none;
}
@media screen and (max-width: 1024px){
    .mode-pc,
	ul.header-dropmenu li a.mode-pc{
		display:none;
	}
	.mode-tab,
	ul.header-dropmenu li a.mode-tab{
		display:block;
	}
}
@media screen and (max-width: 560px){
	.mode-sp{
		display:block;
	}
}
</style>


<script>
$(function(){

	$(function(){
    $('#layer_board_area').layerBoard({
        delayTime: 10, // 表示までの待ち時間(初期値：200)
        fadeTime : 100, // 表示のフェード時間(初期値：500)
        alpha : 0.8, // レイヤーの透明度(初期値：0.5)
        limitMin : 60, // 何分経過後に再度表示するか(初期値：1) 0で再表示なし
        easing: 'swing', // イージング(初期値：linear)
        limitCookie : 1, // cookie保存期間(初期値：1日)（0で開くたび毎回表示される）
		countCookie : 1000	//何回目のアクセスまで適用するか(cookie保存期間でリセット)
    });
});

})
</script>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'twentynineteen' ); ?></a>

		<header id="masthead" class="<?php echo is_singular() && twentynineteen_can_show_post_thumbnail() ? 'site-header featured-image' : 'site-header'; ?>">

		<div class="index_center">
				<?php get_template_part( 'template-parts/header/site', 'branding' ); ?>
		</div><!-- .site-branding-container -->

		<?php 

			if (is_page('login')){

			?>

			<div class="login_header_banner_area">

				<div class="login_header_banner_contents">
					<img class="login_header_banner_img" src="<?php echo get_stylesheet_directory_uri(); ?>/images/screenshot_95.jpg" alt="">

				</div>
			</div>
		<?php 
			}else{

		?>


		<div class="header_div">

			<?php
				if(current_user_can('administrator')){

					 $now_year = date('Y');
			?>


			<ul class="header-dropmenu">

				<li>
					<a href="#" class="mode-pc">▼生徒&成績</a>
					<a href="#" class="mode-tab">▼成績</a>
					<ul>
						<li>----生徒----</li>
						<li><a href="<?php $id = 11; echo get_page_link( $id );?>">生徒一覧（管理者）</a></li>
						<li><a href="<?php $id = 849; echo get_page_link( $id );?>">訓練生一覧（管理者）</a></li>
						<li><a href="<?php $id = 694; echo get_page_link( $id );?>">コメント一覧（管理者）</a></li>
						<li>----成績----</li>
						<li><a href="<?php $id = 43; echo get_page_link( $id );?>?years=<?php echo $now_year;?>">成績一覧（管理者）</a></li>
						<li><a href="<?php $id = 35; echo get_page_link( $id );?>">成績入力</a></li>
						<li><a href="<?php $id = 37; echo get_page_link( $id );?>">成績一覧</a></li>
					</ul>
				</li>
				<li>
					<a href="#" >▼動画</a>
					<ul>
						<li><a href="<?php $id = 129; echo get_page_link( $id );?>">登録（管理者）</a></li>
						<li><a href="<?php $id = 54; echo get_page_link( $id );?>">一覧（管理者）</a></li>
						<li><a href="<?php $id = 209; echo get_page_link( $id );?>">カテゴリ編集(管理者)</a></li>
						<li><a href="<?php $id = 134; echo get_page_link( $id );?>">閲覧</a></li>
					</ul>
				</li>
				<li>
					<a href="#" class="mode-pc">▼ザラ場指導・講義</a>
					<a href="#" class="mode-tab">▼講義</a>
					<ul>
						<li>----レポート----</li>
						<li><a href="<?php $id = 482; echo get_page_link( $id );?>">提出作成（管理者）</a></li>
						<li><a href="<?php $id = 487; echo get_page_link( $id );?>">提出一覧（管理者）</a></li>
						<li><a href="<?php $id = 463; echo get_page_link( $id );?>">提出</a></li>
						<li>----講義----</li>
						<li><a href="<?php $id = 51; echo get_page_link( $id );?>?catagory=1">ザラ場 登録</a></li>
						<li><a href="<?php $id = 51; echo get_page_link( $id );?>?catagory=2">株 講義登録</a></li>
						<li><a href="<?php $id = 51; echo get_page_link( $id );?>?catagory=3">FX 講義登録</a></li>
						<li><a href="<?php $id = 51; echo get_page_link( $id );?>?catagory=4">株 作業会登録</a></li>
						<li><a href="<?php $id = 51; echo get_page_link( $id );?>?catagory=5">FX 作業会登録</a></li>
						<li><a href="<?php $id = 51; echo get_page_link( $id );?>?catagory=6">特別ｾﾐﾅｰ登録</a></li>
						<li><a href="<?php $id = 51; echo get_page_link( $id );?>?catagory=7">ZOOM座談会登録</a></li>
						<li><a href="<?php $id = 46; echo get_page_link( $id );?>">講義一覧（管理者）</a></li>
						<li><a href="<?php $id = 185; echo get_page_link( $id );?>?category=1">講義管理</a></li>
					</ul>
				</li>
				<li>
					<a href="#">▼その他 </a>
					<ul>
						<li>----メール----</li>
						<li><a href="<?php $id = 808; echo get_page_link( $id );?>">メールメニュー</a></li>
						<li>----ツール----</li>
						<li><a href="<?php $id = 537; echo get_page_link( $id );?>">ツールアップロード</a></li>
						<li><a href="<?php $id = 539; echo get_page_link( $id );?>">ツールダウンロード</a></li>
						<li>----その他----</li>
						<li><a href="<?php $id = 24; echo get_page_link( $id );?>">設定変更</a></li>
						<li><a href="<?php $id = 499; echo get_page_link( $id );?>">お知らせ</a></li>
						<li><a href="<?php echo wp_logout_url();?>">ログアウト</a></li>
					</ul>
				</li>
			</ul>


			<?php
				}else if (  is_user_logged_in()){

					  $user = wp_get_current_user();

					  require_once("userClass.php");

					  $users_data= new UserClass();

					 //生徒タイプ
					 $member_level = get_the_author_meta('member_level',$user->ID);

					 //操作停止
					 $login_ban = get_the_author_meta('login_ban',$user->ID);

					 if($login_ban == ""){
						$login_ban = false;
					 }

			?>



			<table class="header_table">

				<?php if( ( $member_level ==  UserClass::DOGA || $member_level ==  UserClass::SPECIAL_SEMINAR )  && ( !$login_ban )){ //動画会員?>
					<tr>
					<td><a class="header_link" href="<?php  echo esc_url( home_url( '/' ) );?>">TOP</a></td>
					<td><a class="header_link" href="<?php $id = 134; echo get_page_link( $id );?>">動画閲覧</a></td>
					<td><a class="header_link" href="<?php $id = 24; echo get_page_link( $id );?>">設定変更</a></td>
					<td><a class="header_link" href="<?php $id = 499; echo get_page_link( $id );?>">お知らせ</a></td>
					<td><a class="header_link" href="<?php echo wp_logout_url();?>">ログアウト</a></td>
				</tr>
				<tr>

					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><div class="heder-disp-username"><p>ユーザー名(<?php echo $user->user_login; ?>)</p></div></td>

				</tr>

				<?php }else if(!$login_ban){ ?>

				<tr>
					<td><a class="header_link" href="<?php  echo esc_url( home_url( '/' ) );?>">TOP</a></td>
					<td><a class="header_link" href="<?php $id = 35; echo get_page_link( $id );?>">成績入力</a></td>
					<td><a class="header_link" href="<?php $id = 37; echo get_page_link( $id );?>">成績一覧</a></td>
					<td><a class="header_link" href="<?php $id = 134; echo get_page_link( $id );?>">動画閲覧</a></td>
					<td><a class="header_link" href="<?php $id = 463; echo get_page_link( $id );?>">レポート提出</a></td>
				</tr>
				<tr>

					<td><a class="header_link" href="<?php $id = 185; echo get_page_link( $id );?>">講義管理</a></td>
					<td><a class="header_link" href="<?php $id = 24; echo get_page_link( $id );?>">設定変更</a></td>
					<td><a class="header_link" href="<?php $id = 499; echo get_page_link( $id );?>">お知らせ</a></td>
					<td><a class="header_link" href="<?php echo wp_logout_url();?>">ログアウト</a></td>
					<td><div class="heder-disp-username"><p>ユーザー名(<?php echo $user->user_login; ?>)</p></div></td>

				</tr>

				<?php } ?>
			</table>
			<?php }?>
		</div><!-- #masthead -->

		<?php 
			}
		?>

		</header><!-- #masthead -->

	<div id="content" class="site-content">


<?php

if (  is_user_logged_in()  && !current_user_can('administrator')){ //ログイン

	 $user = wp_get_current_user();

	 //保存したお知らせを取得
	 $new_id = get_user_meta($user->ID, 'news-new-check',true );

	 if( $new_id == ""){
		$new_id = 0;
	 }

	 //最後の記事
	 $save_id = get_the_latest_ID();

	 if( $new_id != $save_id)
	 {
?>

<!-- layer_board -->
<div id="layer_board_area"> <!-- id名任意 -->
	<div class="layer_board_bg"> <!-- クラス名固定 -->
	<div class="layer_board"> <!-- クラス名固定 -->
		新しいお知らせがあります。<br>
		お知らせページより確認してください。<br>

		<p class="btn_close"><a href="#">閉じる</a></p> <!-- クラス名固定 -->
		</div>
	</div>
</div>
<!-- //layer_board -->
	<?php }?>
<?php }?>
