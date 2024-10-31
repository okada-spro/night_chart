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

		<?php 
		
			include("top_login.php");
		?>

<?php  }else{?>
		
		<?php 

		$user = wp_get_current_user();

		$login_ban = get_the_author_meta('login_ban',$user->ID);

		if($login_ban == "")
		{
			$login_ban = false;
		}

		require_once("pageFunction.php");//ページ用

		//期限切れ
		$updata_page_index = getUserUpdataDayPage($user->ID);

		if($login_ban)
		{
			include("logn-ban.php");
		}
		else if(strtotime($updata_page_index) <= strtotime( date('Y-m-d H:i:s') )) //期限切れ
		{
			include("logn-limit.php");
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
			$member_type = get_the_author_meta('member_type',$user->ID);
		
			
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
						<td><a class="index_top_button index_top_button_st_color" href="<?php $id = 882; echo get_page_link( $id );?>">動画会員一覧（管理者）</a></td>
						<td><a class="index_top_button index_top_button_grades_color" href="<?php $id = 43; echo get_page_link( $id );?>?years=<?php echo date('Y');?>">成績一覧（管理者）</a></td>
					</tr>
					<tr>
					<td><a class="index_top_button index_top_button_st_color" href="<?php $id = 849; echo get_page_link( $id );?>">訓練生一覧（管理者）</a></td>
						<td><a class="index_top_button index_top_button_st_color" href="<?php $id = 694; echo get_page_link( $id );?>">コメント一覧（管理者）</a></td>
					<tr>
						<td><a class="index_top_button index_top_button_st_color" href="<?php $id = 11; echo get_page_link( $id );?>">登録者一覧（管理者）</a></td>
						<td><a class="index_top_button index_top_button_st_color" href="<?php $id = 1037; echo get_page_link( $id );?>">更新用一覧（管理者）</a></td>
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
						<td><a class="index_top_button index_top_button_report_color" href="https://rakurakunikki.com/easy_trade_diary/top/">トレード日記ツール</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_video_color" href="<?php $id = 134; echo get_page_link( $id );?>">動画閲覧</a></td>
						<td><a class="index_top_button index_top_button_report_color" href="<?php $id = 1895; echo get_page_link( $id );?>">動画マニュアル</a></td>

					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_video_color" href="<?php $id = 1794; echo get_page_link( $id );?>">動画順番並べ替え</a></td>
						<td></td>
					</tr>
				</table>
				</p>
	
				<p>
				<table class="top-table">
					<tr>
						
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=1">ザラ場 登録（管理者）</a></td>
						<!-- <td><a class="index_top_button index_top_button_tool_color" href="<?php $id = 808; echo get_page_link( $id );?>">メールメニュー</a></td> -->
						<!-- staging -->
						<td><a class="index_top_button index_top_button_tool_color" href="<?php $id = 1400; echo get_page_link( $id );?>">送信メール一覧</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=2">株 講義登録（管理者）</a></td>
						<td><a class="index_top_button index_top_button_tool_color" href="<?php $id = 537; echo get_page_link( $id );?>">ツールアップロード</a></td>
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=3">FX 講義登録（管理者）</a></td>
						<td><a class="index_top_button index_top_button_tool_color" href="<?php $id = 539; echo get_page_link( $id );?>">ツールダウンロード</a></td>
					</tr>
					<tr><td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=4">株 作業会登録（管理者）</a></td></tr>
					<tr>
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=5">FX 作業会登録（管理者）</a></td>
					
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=6">特別ｾﾐﾅｰ登録（管理者）</a></td>
						
					</tr>
					<?php /*<tr>
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=7">ZOOM座談会登録（管理者）</a></td>
						
					</tr>*/?>
					<tr>
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=8">質問会（管理者）</a></td>
						
					</tr>
					<tr>
						<td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 46; echo get_page_link( $id );?>">ZOOM登録一覧（管理者）</a></td>
						
					</tr>
					
				</table>
				</p>
	
				<p>
				<table class="top-table">
					<tr>
						<td><a class="index_top_button index_top_button_setting_color" href="<?php $id = 24; echo get_page_link( $id );?>">設定変更</a></td>
						<td><a class="index_top_button index_top_button_setting_color" href="<?php $id = 969; echo get_page_link( $id );?>">個別相談申し込み</a></td>
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
					<tr><td><a class="index_top_button index_top_button_st_color" href="<?php $id = 849; echo get_page_link( $id );?>">訓練生一覧（管理者）</a></td></tr>
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
					<tr><td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=4">株 作業会登録（管理者）</a></td></tr>
					<tr><td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=5">FX 作業会登録（管理者）</a></td></tr>
					<tr><td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=6">特別ｾﾐﾅｰ登録（管理者）</a></td></tr>
					<?php /*<tr><td><a class="index_top_button index_top_button_zoom_color" href="<?php $id = 51; echo get_page_link( $id );?>?catagory=7">ZOOM座談会登録（管理者）</a></td></tr>*/?>
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
					<td><a class="index_top_button index_top_button_setting_color" href="<?php $id = 969; echo get_page_link( $id );?>">個別相談申し込み</a></td>
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
		
			<div class="menu-box-area">
				<div class="menu-box-title" style="margin-top: 30px;">動画マニュアル</div>

					<div class="menu-box-table">
					
						<table class="menu-table">


							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 1895; echo get_page_link( $id );?>">マニュアル一覧を見る</a>
									</div>
								</td>

							</tr>

						</table>
					
					
					</div>
				</div>
			</div>
			<?php if($member_level ==  UserClass::DOGA || $member_level ==  UserClass::NEW_DOGA ){ //動画会員?>

				
				<div class="menu-box-area">

					<div class="menu-box-title" style="margin-top: 30px;">動画について</div>

					<div class="menu-box-table">
					
						<table class="menu-table">


							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 134; echo get_page_link( $id );?>">動画閲覧</a>
									</div>
								</td>

							</tr>
							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 185; echo get_page_link( $id );?>">質問会</a>
									</div>
								</td>
							</tr>

						</table>
					
					
					</div>

					<div class="menu-box-title"  style="margin-top: 30px;">成績について</div>
	
						<div class="menu-box-table">

							<table class="menu-table">
								<tr>
									<td>
										<div class="menu-box-str-link">
											<a href="https://rakurakunikki.com/easy_trade_diary/top/">内田博史トレード説教部屋へ</a>
										</div>
									</td>

								</tr>

							</table>
						
						
						</div>

				</div>

				<div class="menu-box-area" >

					<div class="menu-box-title">その他</div>

					<div class="menu-box-table">
					
						<table class="menu-table">

							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 499; echo get_page_link( $id );?>">お知らせ</a>
									</div>
								</td>

							</tr>
							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 969; echo get_page_link( $id );?>">個別相談申し込み</a>
									</div>
								</td>
							</tr>
						</table>
					
					
					</div>

				</div>
		
				<div class="menu-box-area" >

					<div class="menu-box-title">個人の設定</div>

					<div class="menu-box-table">
					
						<table class="menu-table">

							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 24; echo get_page_link( $id );?>">設定変更</a>
									</div>
								</td>

							</tr>
							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 743; echo get_page_link( $id );?>">パスワード変更</a>
									</div>
								</td>
							</tr>
							
						</table>
					
					
					</div>

				</div>
		
				<div class="menu-box-logout" >
					<a class="" href="<?php  echo wp_logout_url();?>">ログアウト</a>
				</div>


				
			<?php }else if($member_level ==  UserClass::SPECIAL_SEMINAR ){ //特別セミナー?>

				<p>
					<a class="index_user_button index_top_button_video_color" href="<?php $id = 134; echo get_page_link( $id );?>">動画閲覧</a>
				</p>
				<p>
					<a class="index_user_button index_top_button_setting_color" href="<?php $id = 24; echo get_page_link( $id );?>">設定変更</a>
				</p>
				<p>
					<a class="index_user_button index_top_button_logout_color" href="<?php  echo wp_logout_url();?>">ログアウト</a>
				</p>
			<?php }else{ ?>


				<div class="menu-box-area" style="margin-top: 30px;">

					<div class="menu-box-title">動画について</div>

					<div class="menu-box-table">
					
						<table class="menu-table">

							<tr>


							</tr>
							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 134; echo get_page_link( $id );?>">動画閲覧</a>
									</div>
								</td>

							</tr>
							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 185; echo get_page_link( $id );?>">ザラ場指導・講義・質問会</a>
									</div>
								</td>
							</tr>
						</table>
					
					
					</div>

				</div>

				<div class="menu-box-area" >

					<div class="menu-box-title">成績について</div>

					<div class="menu-box-table">
					
						<table class="menu-table">
							<?php 
								//門下生、株訓練生、動画会員
								if(	$member_level ==  UserClass::MONKASEI || ($member_level ==  UserClass::KUNRENSEI && $member_type == UserClass::KABU) ){
							?>
								<tr>
									<td>
										<div class="menu-box-str-link">
											<a href="https://rakurakunikki.com/easy_trade_diary/top/">内田博史トレード説教部屋へ</a>
										</div>
									</td>

								</tr>

							<?php } ?>

							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 35; echo get_page_link( $id );?>">成績入力</a>
									</div>
								</td>

							</tr>
							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 37; echo get_page_link( $id );?>">成績一覧</a>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 463; echo get_page_link( $id );?>">レポート提出</a>
									</div>
								</td>
							</tr>

						</table>
					
					
					</div>

				</div>


				<div class="menu-box-area" >

					<div class="menu-box-title">その他</div>

					<div class="menu-box-table">
					
						<table class="menu-table">

							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 499; echo get_page_link( $id );?>">お知らせ</a>
									</div>
								</td>

							</tr>
							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 969; echo get_page_link( $id );?>">個別相談申し込み</a>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 539; echo get_page_link( $id );?>">ダウンロード</a>
									</div>
								</td>
							</tr>
						</table>
					
					
					</div>

				</div>
		
				<div class="menu-box-area" >

					<div class="menu-box-title">個人の設定</div>

					<div class="menu-box-table">
					
						<table class="menu-table">

							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 24; echo get_page_link( $id );?>">設定変更</a>
									</div>
								</td>

							</tr>
							<tr>
								<td>
									<div class="menu-box-str-link">
										<a href="<?php $id = 743; echo get_page_link( $id );?>">パスワード変更</a>
									</div>
								</td>
							</tr>
							
						</table>
					
					
					</div>

				</div>
		

			
				<div class="menu-box-logout" >
					<a class="" href="<?php  echo wp_logout_url();?>">ログアウト</a>
				</div>
			
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php }?>



		</main><!-- .site-main -->
	</div><!-- .content-area -->
	
	
<?php
get_footer();
