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
?>
<?php

	$user = wp_get_current_user();

	//CSV作成
	if($user->ID == 1)
	{
		if( isset($_POST["csv_ex"] ))
		{
			require_once("CsvUtilClass.php");
			$csv_util= new CsvUtilClass();
			$csv_util->createCsv($_POST["csv_file_name"],$_POST["csv_ids"]);
		}
	}

?>
<!doctype html>
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
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/user_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/header_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/reportsubmission_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/user_list_comment_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/videoviewing_input_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/videoviewing_list_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/videoviewing_make_category_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/videoviewing_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/report_list_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/zoom_list_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/zoom_input_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/tool_upload_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/tool_download_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/user_edit_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/news_list_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/top_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/zoom_registration_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/contactus_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/trainee_list_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/mail_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/login_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/register_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
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


});

$(function(){

	$(function(){
		

		$('#layer_board_area_video').layerBoard({
			delayTime: 10, // 表示までの待ち時間(初期値：200)
			fadeTime : 100, // 表示のフェード時間(初期値：500)
			alpha : 0.8, // レイヤーの透明度(初期値：0.5)
			limitMin : 60, // 何分経過後に再度表示するか(初期値：1) 0で再表示なし
			easing: 'swing', // イージング(初期値：linear)
			limitCookie : 0, // cookie保存期間(初期値：1日)（0で開くたび毎回表示される）
			countCookie : 1000	//何回目のアクセスまで適用するか(cookie保存期間でリセット)
		});
	});


});

// ページが読み込まれた時点でモーダルを表示する
  window.onload = function() {
    var modal = document.getElementById("myModal");
    modal.style.display = "block";
    
    // モーダルの閉じるボタンにイベントリスナーを追加する
    var span = document.getElementsByClassName("top_close")[0];
    span.onclick = function() {
      modal.style.display = "none";
    }
  }


function sendlinkData() {
	// フォームを作成
	var form = document.createElement('form');
	form.method = 'POST';
	form.action = 'https://nightchart.jp/videoviewing/'; // 送信先のURLを指定
  
	// input要素1を作成
	var input1 = document.createElement('input');
	input1.type = 'hidden'; // 隠しフィールドとして設定
	input1.name = 'id'; // フィールド名を指定
	input1.value = document.getElementById('pop_id').value; // 値を取得
  
	// input要素2を作成
	var input2 = document.createElement('input');
	input2.type = 'hidden'; // 隠しフィールドとして設定
	input2.name = 'viewing'; // フィールド名を指定
	input2.value = document.getElementById('pop_viewing').value; // 値を取得


	// input要素3を作成
	var input3 = document.createElement('input');
	input3.type = 'hidden'; // 隠しフィールドとして設定
	input3.name = 'category_id'; // フィールド名を指定
	input3.value = document.getElementById('pop_category_id').value; // 値を取得
  
	// フォームにinput要素を追加
	form.appendChild(input1);
	form.appendChild(input2);
	form.appendChild(input3);
  
	// フォームをbody要素に追加し、送信する
	document.body.appendChild(form);
	form.submit();
}

</script>


<div class="body-container">

<div id="page" class="">


	<header id="masthead" class="">

		
	
		
	<?php 

		require_once("userClass.php");

		if (!is_user_logged_in()){
	?>

		<div class="top_header_area">

			<div class="top_header_flex">

				<div class="top_header_log"><a href="<?php echo home_url();?>">UBS</a></div>
	
				<div class="top_header_menu_area" style="margin-right:50px;">
	
					<div class="top_header_menu_link">
						<a class="" href="https://mpwks.jp/privacy_policy/" style="color:white">プライバシーポリシー</a>
					</div>
				</div>
			</div>
		</div>
										

	<?php 
		}else{

	?>

	
			<div class="header_div">

			<?php
				if(current_user_can('administrator'))
				{

					$now_year = date('Y');
			?>

				<div class="top_header_area">

					<div class="top_header_log"><a href="<?php echo home_url();?>">UBS</a></div>

				</div>

				<div class="mode-pc mode-tab">
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
				</div>
			
				<div class="mode-sp">
					<ul class="header-dropmenu">
						<li>
							<a href="#">▼メニュー</a>
							<ul>
								<li>----生徒----</li>
								<li><a href="<?php $id = 11; echo get_page_link( $id );?>">生徒一覧（管理者）</a></li>
								<li><a href="<?php $id = 849; echo get_page_link( $id );?>">訓練生一覧（管理者）</a></li>
								<li><a href="<?php $id = 694; echo get_page_link( $id );?>">コメント一覧（管理者）</a></li>
								<li>----成績----</li>
								<li><a href="<?php $id = 43; echo get_page_link( $id );?>?years=<?php echo $now_year;?>">成績一覧（管理者）</a></li>
								<li><a href="<?php $id = 35; echo get_page_link( $id );?>">成績入力</a></li>
								<li><a href="<?php $id = 37; echo get_page_link( $id );?>">成績一覧</a></li>
								<li>----動画----</li>
								<li><a href="<?php $id = 129; echo get_page_link( $id );?>">登録（管理者）</a></li>
								<li><a href="<?php $id = 54; echo get_page_link( $id );?>">一覧（管理者）</a></li>
								<li><a href="<?php $id = 209; echo get_page_link( $id );?>">カテゴリ編集(管理者)</a></li>
								<li><a href="<?php $id = 134; echo get_page_link( $id );?>">閲覧</a></li>
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
				</div>
			<?php
				}else if (  is_user_logged_in()){ //ユーザー

					$user = wp_get_current_user();

					$users_data= new UserClass();

					//生徒タイプ
					$member_level = get_the_author_meta('member_level',$user->ID);

					//操作停止
					$login_ban = get_the_author_meta('login_ban',$user->ID);

					if($login_ban == ""){
						$login_ban = false;
					}
				//}

			?>

					<div class="top_pc">
						<div class="top_header_area">

							<div class="top_header_flex">

								<div class="top_header_log"><a href="<?php echo home_url();?>">UBS</a></div>
								<div class="top_header_menu_area">

									<?php if(!$login_ban){ ?>

										<div class="top_header_menu_link">
											<a href="<?php  echo esc_url( home_url( '/' ) );?>">トップページ</a>
										</div>

										<div class="top_header_menu_link">
											<a href="<?php $id = 134; echo get_page_link( $id );?>">動画閲覧</a>
										</div>

									<?php } ?>

									<?php if( ( $member_level ==  UserClass::DOGA || $member_level ==  UserClass::SPECIAL_SEMINAR  || $member_level ==  UserClass::NEW_DOGA)  && ( !$login_ban )){ //動画会員?>

										
									<?php }else if(!$login_ban){ ?>

										<div class="top_header_menu_link">
											<a  href="<?php $id = 185; echo get_page_link( $id );?>">講義管理</a>
										</div>

										<div class="top_header_menu_link">
											<a  href="<?php $id = 35; echo get_page_link( $id );?>">成績入力</a>
										</div>

										<div class="top_header_menu_link">
											<a  href="<?php $id = 37; echo get_page_link( $id );?>">成績一覧</a>
										</div>

										<div class="top_header_menu_link">
											<a  href="<?php $id = 463; echo get_page_link( $id );?>">レポート提出</a>
										</div>

									<?php } ?>

								</div>

								<div class="top_header_menu_else">

									<?php if(!$login_ban){ ?>

										<div class="top_header_menu_img_news">
											 <a class="header_link" href="<?php $id = 499; echo get_page_link( $id );?>"><img src="<?php echo get_template_directory_uri().'/images/bell.svg' ?>" alt="bell_mark" /></a>
										</div>

									<?php } ?>

									<div class="top_header_menu_img_user">

										<div class="tooltip5">

											 <img src="<?php echo get_template_directory_uri().'/images/user.svg' ?>" alt="user_mark" />
											 <div class="description5">

												<ul>
													<li><p>ユーザー名(<?php echo $user->user_login; ?>)</p></li>
													<li><a class="header_link" href="<?php $id = 24; echo get_page_link( $id );?>">登録情報の変更</a></li>
													<li><a class="header_link" href="<?php $id = 743; echo get_page_link( $id );?>">パスワードの変更</a></li>
													<li><a class="header_link" href="<?php $id = 969; echo get_page_link( $id );?>">個別相談申し込み</a></li>
													<li style="padding-top: 80px;border-bottom: 0px;"><a class="header_link" href="<?php echo wp_logout_url();?>">ログアウト</a></li>
												</ul>
												
											 
											 
											 </div>
										</div>
    

									</div>

								</div>


							</div>

						</div>



			</div>

				<?php //SP用?>
				<div class="mode-sp ">

					<div class="top_header_area">

						<div class="top_header_log"><a href="<?php echo home_url();?>">UBS</a></div>

					</div>


					<ul class="header-dropmenu">
					<?php if($member_level ==  UserClass::DOGA  && !$login_ban){ //動画会員?>
						<li>
							<a href="#">▼メニュー</a>
							<ul>
								<li><a class="header_link" href="<?php  echo esc_url( home_url( '/' ) );?>">TOP</a></li>
								<li><a class="header_link" href="<?php $id = 134; echo get_page_link( $id );?>">動画閲覧</a></li>
								<li><a class="header_link" href="<?php $id = 24; echo get_page_link( $id );?>">設定変更</a></li>
								<li><a class="header_link" href="<?php $id = 499; echo get_page_link( $id );?>">お知らせ</a></li>
								<li><a class="header_link" href="<?php echo wp_logout_url();?>">ログアウト</a></li>
								<li><div class="heder-disp-username"><p>ユーザー名(<?php echo $user->user_login; ?>)</p></div></li>
							</ul>
						</li>
					<?php }else if(!$login_ban){ ?>
						<li>
							<a href="#">▼メニュー</a>
							<ul>
								<li><a class="header_link" href="<?php  echo esc_url( home_url( '/' ) );?>">TOP</a></li>
								<li><a class="header_link" href="<?php $id = 35; echo get_page_link( $id );?>">成績入力</a></li>
								<li><a class="header_link" href="<?php $id = 134; echo get_page_link( $id );?>">動画閲覧</a></li>
								<li><a class="header_link" href="<?php $id = 463; echo get_page_link( $id );?>">レポート提出</a></li>
								<li><a class="header_link" href="<?php $id = 185; echo get_page_link( $id );?>">講義管理</a></li>
								<li><a class="header_link" href="<?php $id = 24; echo get_page_link( $id );?>">設定変更</a></li>
								<li><a class="header_link" href="<?php $id = 499; echo get_page_link( $id );?>">お知らせ</a></li>
								<li><a class="header_link" href="<?php echo wp_logout_url();?>">ログアウト</a></li>
								<li><div class="heder-disp-username"><p>ユーザー名(<?php echo $user->user_login; ?>)</p></div></li>
							</ul>
						</li>
					</ul>
					<?php } ?>

					<?php } ?>
				</table>
		<?php }?>





		</div><!-- #masthead -->

		<?php 
			//}
		?>

		</header><!-- #masthead -->

	<div id="content" class="site-content">


<?php

if (  is_user_logged_in()  && !current_user_can('administrator')){ //ログイン

	require_once("newsClass.php");

     //クラス作成
    $news_data= new NewstClass();

	$user = wp_get_current_user();

	$disp_post_array = $news_data->getNewsPost($user->ID);

	//保存したお知らせを取得
	$new_id = get_user_meta($user->ID, 'news-new-check',true );

	if( $new_id == ""){
		$new_id = 0;
	}

	//最後の記事
	 $save_id = $disp_post_array[0]["ID"];
	 //$save_id = get_the_latest_ID();

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
	<?php 
	
	}else{
		//ここからは動画のアップデートチェック
		//if($user->ID == 19)
		{

			$video_news_id = get_user_meta($user->ID, 'user_video_upload_id',true );
			$video_news_category = get_user_meta($user->ID, 'user_video_upload_category',true );
			$video_news_title = get_field( 'item_add_vdeo_title',$video_news_id); 
			$pop_id =  get_field( 'item_afc_video_id',$video_news_id);


			if($video_news_id != "" )
			{

				//if($user->ID == 19)
				{
					require_once("videoviewClass.php");

					$video_view_data= new VideoViewClass();

					 //初期化
					$video_view_data->init();

					// データ取得クエリ実行
					$video_view_data->getReleaseDateViewRow();

					  // カテゴリ取得クエリ実行
					$video_view_data->getCategoryDataRow();

					$video_open_array = $video_view_data->setOpenData(false,true);

					//var_dump($video_open_array);


					if( isset( $video_open_array[$pop_id] ))
					{
						//echo $video_news_category;

						$video_news_category = $video_open_array[$pop_id]["input_category"];
						$video_news_title = $video_open_array[$pop_id]["input_title"];
					}

				}

				update_user_meta($user->ID,'user_video_upload_id',"");

				if($video_news_category == "" && $video_news_title != "" && $video_news_category != "" && $pop_id != "")
				{
					
?>



<div id="myModal" class="top_modal">
  <div class="modal-content">
    
    <p>新しい動画がアップロード<br>されています。</p>
	<span class="top_close">閉じる</span>
  </div>
</div>

	<?php		}else{ ?>



<div id="myModal" class="top_modal">
  <div class="modal-content">
    
    <p><a href="#" onclick="sendlinkData()">「<?php echo $video_news_title; ?>」</a>がアップロード<br>されています。</p>
	<span class="top_close">閉じる</span>
  </div>
</div>


<input type="hidden" id="pop_id" value="<?php echo $pop_id;?>">
<input type="hidden" id="pop_viewing" value="viewing">
<input type="hidden" id="pop_category_id" value="<?php echo $video_news_category; ?>">





<?php

				}
			}

			//echo get_field( 'item_add_vdeo_title',$video_news_id);


		}


	
	
	}
	
	?>

<?php }?>
