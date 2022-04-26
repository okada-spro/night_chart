<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 */


get_header();

?>


	<div id="primary" class="content-area">
		<main id="main" class="site-main">
		
<?php if ( ! is_user_logged_in()){?>
		<p>
			<a class="index_button" href="<?php $id = 12; echo get_page_link( $id );?>">ログイン</a>
		</p>

		<p>
			<a class="index_button" href="<?php $id = 13; echo get_page_link( $id );?>">新規登録</a>
		</p>

<?php  }else{?>
		
		<?php 

		$user = wp_get_current_user();

		$login_ban = get_the_author_meta('login_ban',$user->ID);

		if($login_ban == "")
		{
			$login_ban = false;
		}



		if($login_ban)
		{
			include("logn-ban.php");
		}
		else{
			require_once("zoomClass.php");
			require_once("userClass.php");

			 $user = wp_get_current_user();

			//クラス作成
			$input_data= new ZoomClass();
		    $users_data= new UserClass();

			 //初期化
			 $input_data->init();

    
			// データ取得クエリ実行
			$input_data->getZoomNowDayAfterRow();

			//最新の参加するデータ取得
			$mtg_data = $input_data->getPlanNew($user->ID,ZoomClass::ZOOM_ALL_CATEGORY);

			//生徒タイプ
			$member_level = get_the_author_meta('member_level',$user->ID);
		
			
		?>

		<?php if($mtg_data){?>
			<div class="index_center">
				<div class="top-index-txt">
				<p>
				<font size="5">
					<?php if($mtg_data["input_zoom_category"] == ZoomClass::ZOOM_ZARABA_CATEGORY){?>次のザラ場指導<?php } ?>
					<?php if($mtg_data["input_zoom_category"] == ZoomClass::ZOOM_KOUGI_CATEGORY){?>次の講義<?php } ?>
				
				
				は【<?php echo $mtg_data["input_zoom_start_day"];?>】から開催予定です</font><br>
				参加URL::<a href= "<?php echo $mtg_data["input_zoom_url"];?>" target="_blank" ><?php echo $mtg_data["input_zoom_url"];?></a>
				</p>
				</div>
			</div>
		<?php } ?>

		<?php if(current_user_can('administrator')){ ?>

			<div class="mode-pc mode-tab">
				<table class="top-table">
					<tr>
						<td><a class="index_top_button index_top_button_st_color" href="<?php $id = 11; echo get_page_link( $id );?>">生徒一覧（管理者）</a></td>
						<td><a class="index_top_button index_top_button_grades_color" href="<?php $id = 43; echo get_page_link( $id );?>?years=<?php echo date('Y');?>">成績一覧（管理者）</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_st_color" href="<?php $id = 694; echo get_page_link( $id );?>">コメント一覧（管理者）</a></td>
						<td></td>
	
					</tr>
				</table>
	
				<p>
				<table class="top-table">
					<tr>
						<td><a class="index_top_button index_top_button_video_color" href="<?php $id = 129; echo get_page_link( $id );?>">動画登録（管理者）</a></td>
						<td><a class="index_top_button index_top_button_report_color" href="<?php $id = 482; echo get_page_link( $id );?>">レポート提出作成（管理者）</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_video_color" href="<?php $id = 54; echo get_page_link( $id );?>">動画一覧（管理者）</a></td>
						<td><a class="index_top_button index_top_button_report_color" href="<?php $id = 487; echo get_page_link( $id );?>">レポート提出一覧（管理者）</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_video_color" href="<?php $id = 209; echo get_page_link( $id );?>">動画カテゴリ作成・編集</a></td>
						<td></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_video_color" href="<?php $id = 134; echo get_page_link( $id );?>">動画閲覧</a></td>
						<td></td>
					</tr>
				</table>
				</p>
	
				<p>
				<table class="top-table">
					<tr>
						
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=1">ザラ場 登録（管理者）</a></td>
						<td><a class="index_top_button index_top_button_tool_color" href="<?php $id = 808; echo get_page_link( $id );?>">メールメニュー</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=2">株 講義登録（管理者）</a></td>
						
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=3">FX 講義登録（管理者）</a></td>
						<td><a class="index_top_button index_top_button_tool_color" href="<?php $id = 537; echo get_page_link( $id );?>">ツールアップロード</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 46; echo get_page_link( $id );?>">ZOOM登録一覧（管理者）</a></td>
						<td><a class="index_top_button index_top_button_tool_color" href="<?php $id = 539; echo get_page_link( $id );?>">ツールダウンロード</a></td>
						
					</tr>
					
				</table>
				</p>
	
				<p>
				<table class="top-table">
					<tr>
						<td><a class="index_top_button index_top_button_setting_color" href="<?php $id = 24; echo get_page_link( $id );?>">設定変更</a></td>
						<td><a class="index_top_button index_top_button_news_color" href="<?php $id = 499; echo get_page_link( $id );?>">お知らせ</a></td>
					
					</tr>
					<tr>
						<td></td>
						<td><a class="index_top_button index_top_button_logout_color" href="<?php echo wp_logout_url();?>">ログアウト</a></td>
					</tr>
				</table>
				</p>
			</div>

			<div class="mode-sp">
			<table class="top-table">
					<tr>
						<td><a class="index_top_button index_top_button_st_color" href="<?php $id = 11; echo get_page_link( $id );?>">生徒一覧（管理者）</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_st_color" href="<?php $id = 694; echo get_page_link( $id );?>">コメント一覧（管理者）</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_grades_color" href="<?php $id = 43; echo get_page_link( $id );?>?years=<?php echo date('Y');?>">成績一覧（管理者）</a></td>
					</tr>
				</table>
	
				<p>
				<table class="top-table">
					<tr>
						<td><a class="index_top_button index_top_button_video_color" href="<?php $id = 129; echo get_page_link( $id );?>">動画登録（管理者）</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_video_color" href="<?php $id = 54; echo get_page_link( $id );?>">動画一覧（管理者）</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_video_color" href="<?php $id = 209; echo get_page_link( $id );?>">動画カテゴリ作成・編集</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_video_color" href="<?php $id = 134; echo get_page_link( $id );?>">動画閲覧</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_report_color" href="<?php $id = 482; echo get_page_link( $id );?>">レポート提出作成（管理者）</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_report_color" href="<?php $id = 487; echo get_page_link( $id );?>">レポート提出一覧（管理者）</a></td>
					</tr>
				</table>
				</p>
	
				<p>
				<table class="top-table">
					<tr>
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=1">ザラ場 登録（管理者）</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=2">株 講義登録（管理者）</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=3">FX 講義登録（管理者）</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 46; echo get_page_link( $id );?>">ZOOM登録一覧（管理者）</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_tool_color" href="<?php $id = 808; echo get_page_link( $id );?>">メールメニュー</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_tool_color" href="<?php $id = 537; echo get_page_link( $id );?>">ツールアップロード</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_tool_color" href="<?php $id = 539; echo get_page_link( $id );?>">ツールダウンロード</a></td>
					</tr>
				</table>
				</p>
	
				<p>
				<table class="top-table">
					<tr>
						<td><a class="index_top_button index_top_button_setting_color" href="<?php $id = 24; echo get_page_link( $id );?>">設定変更</a></td>					
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_news_color" href="<?php $id = 499; echo get_page_link( $id );?>">お知らせ</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_logout_color" href="<?php echo wp_logout_url();?>">ログアウト</a></td>
					</tr>
				</table>
				</p>
			</div>

		<?php } ?>


		<?php if( !current_user_can('administrator') || isset($_GET["user_p"])){ ?>
		
			<?php if($member_level ==  UserClass::DOGA ){ //動画会員?>

				<p>
					<a class="index_user_button index_top_button_video_color" href="<?php $id = 134; echo get_page_link( $id );?>">動画閲覧</a>		
				</p>
				<p>
					<a class="index_user_button index_top_button_setting_color" href="<?php $id = 499; echo get_page_link( $id );?>">お知らせ</a>		
					<a class="index_user_button index_top_button_setting_color" href="<?php $id = 24; echo get_page_link( $id );?>">設定変更</a>
				</p>
				<p>
					<a class="index_user_button index_top_button_logout_color" href="<?php  echo wp_logout_url();?>">ログアウト</a>
				</p>
			<?php }else{ ?>
			
			<p>
				<a class="index_user_button index_top_button_grades_color" href="<?php $id = 35; echo get_page_link( $id );?>">成績入力</a>
				<a class="index_user_button index_top_button_grades_color" href="<?php $id = 37; echo get_page_link( $id );?>">成績一覧</a>
			</p>

			<p>
				<a class="index_user_button index_top_button_video_color" href="<?php $id = 134; echo get_page_link( $id );?>">動画閲覧</a>		
				<a class="index_user_button index_top_button_zoom_color" href="<?php $id = 463; echo get_page_link( $id );?>">レポート提出</a>		
				<a class="index_user_button index_top_button_zoom_color" href="<?php $id = 185; echo get_page_link( $id );?>?category=<?php echo ZoomClass::ZOOM_ZARABA_CATEGORY; ?>">ザラ場指導・講義管理</a>
			</p>
		
			<p>
				<a class="index_user_button index_top_button_setting_color" href="<?php $id = 499; echo get_page_link( $id );?>">お知らせ</a>		
				<a class="index_user_button index_top_button_setting_color" href="<?php $id = 539; echo get_page_link( $id );?>">ダウンロード</a>		
				<a class="index_user_button index_top_button_setting_color" href="<?php $id = 762; echo get_page_link( $id );?>">お問い合わせ</a>
			</p>

			<p>
				<a class="index_user_button index_top_button_setting_color" href="<?php $id = 24; echo get_page_link( $id );?>">設定変更</a>
				<a class="index_user_button index_top_button_setting_color" href="<?php $id = 743; echo get_page_link( $id );?>">パスワード変更</a>
			</p>
			<p>
				<a class="index_user_button index_top_button_logout_color" href="<?php  echo wp_logout_url();?>">ログアウト</a>
			</p>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php }?>



		</main><!-- .site-main -->
	</div><!-- .content-area -->
	
	
<?php
get_footer();
