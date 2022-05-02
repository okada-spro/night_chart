<main id="site-content">

	<?php

	// 記事取得
	$args = array("post_type" => "post", "posts_per_page" => 10);
	$myposts = get_posts($args); 

	// ページデータ取得
	$post_data = get_post();
	$page_name = $post_data->post_title;

	// 画面サイズ設定
	$size=wp_is_mobile();	//false:PC
	if ($size == true){
		$item_connection_count = 2;
	}else{
		$item_connection_count = 5;
	}

	//カスタムフィールド取得
	$custom_data = get_post_meta( get_the_ID() );
	get_header();
	?>

	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
	<script>
		// マイナス処理
		function CountMinus(){
			var now = document.getElementsByClassName('item-count-no')[0].value;

			if(now > 1){
				document.getElementsByClassName('item-count-no')[0].value = Number(now) - 1; 
			}
		}

		// プラス処理
		function CountPlus(){
			var now = document.getElementsByClassName('item-count-no')[0].value;
			document.getElementsByClassName('item-count-no')[0].value = Number(now) + 1;
		}

		// 画像情報取得
		let count = 0;
		var image_name = "<?php echo $page_name ?>";
		var image_content = new Object();
		image_content = {
			"観葉植物パキラ（白）":4,
			"卓上観葉植物":3,
			"plate":2,
			"カトラリーGold":4,
			"カトラリーステンレス":3,
			"ケトル":3,
			"コーヒーカップ":2,
			"スキンケアセット":2,
			"鉄瓶":2,
			"保湿石鹸":3,
			"和食器（黒）":2,
		}

		// 画像を配列に格納
		var img_box = new Array(image_content[image_name]);
		for(var i = 1; i <= image_content[image_name];i++){
			img_box[i-1] = image_name + i;
		}
		setTimeout("ImgChange('image_name')", 3000);
		
		// 画像自動切り替え
		// function ImgChange(name){

		// 	if(count == img_box.length){
		// 		count = 0;
		// 	}
		// 	document.getElementById("main_img").src = "<?php echo get_template_directory_uri()?>/assets/images/" + img_box[count] + ".png";
		// 	count++;
		// 	setTimeout("ImgChange('image_name')", 3000);

		// }

		// 画像切り替え
		function ImgClickChange(no){
			no--;//配列用に調整
			document.getElementById("main_img").src = "<?php echo get_template_directory_uri()?>/assets/images/" + img_box[no] + ".png";
		}

		var item_list_no = 0;

		function PrevItem(){
			item_list_no--;
			document.getElementById("item" + item_list_no).style.display = "block";
			document.getElementById("item" + (item_list_no + 5)).style.display = "none";


			// 前ボタン
			if(item_list_no <= 0){
				document.getElementsByClassName("item-prev")[0].style.display = "none";
			}else{
				document.getElementsByClassName("item-prev")[0].style.display = "block";
			}
		}

		function NextItem(){
			document.getElementById("item" + item_list_no).style.display = "none";
			document.getElementById("item" + (item_list_no + 5)).style.display = "block";
			item_list_no++;

			// 次ボタン
			if(item_list_no >= 5){
				document.getElementsByClassName("item-next")[0].style.display = "none";
			}else{
				document.getElementsByClassName("item-next")[0].style.display = "block";
				document.getElementsByClassName("item-prev")[0].style.display = "block";
			}
		}

		// カートに商品入れる	cookie保存
		function CartIn(){
			// 1日で削除
			document.cookie = '<?php echo $page_name?> = ' + document.getElementsByClassName('item-count-no')[0].value + ';max-age=86400;path=<?php echo home_url();?>';
		}


	</script>
	<div class="item-detail-area">
		<!-- 商品画像 -->
		<div class="item-left">
			<div class="item-main">
				<img id="main_img" src="<?php echo get_template_directory_uri()?>/assets/images/<?=$page_name ?>1.png" alt="">
			</div>
	
			<div class="item-sub">
				<?php for($i = 1 ; $i <= 4; $i++):?>
					<div class="item-sub-img">
						<img onerror="this.remove()" src="<?php echo get_template_directory_uri()?>/assets\images\<?=$page_name . $i ?>.png" alt="" onclick="ImgClickChange(<?=$i ?>)">
					</div>
				<?php endfor;?>
			</div>
		</div>
	
		<!-- 商品詳細 -->
		<div class="item-right-container">
			<div class="item-right">
				<div class="item-disp">
					<div class="item-tags">
						<?php 
							$tags = get_the_tags();
								foreach( $tags as $tag) { 
								echo '<li><a href="'. get_tag_link($tag->term_id) .'">#' . $tag->name . ' </a></li>';
							}
						?>
					</div>
					<div class="item-name"><?=$page_name ?></div>
					<div class="item-price">
						<?php echo $custom_data['金額'][0]; ?>
					</div>
				</div>
	
				<div class="item-line"></div>
	
				<div class="item-count">
					<div class="item-count-area">
						<input class="item-count-sub" type="button" value="-" onclick="CountMinus()">
						<input class="item-count-no" type="text" name="" value="1">
						<input class="item-count-add" type="button" value="+" onclick="CountPlus()">
					</div>
					<input type="button" class="item-cart-btn" value="カートに入れる" onclick="CartIn()">
				</div>
	
				<div class="item-line"></div>
	
				<div class="item-info">
					<div class="item-exp">詳細情報</div>
					
					<?php 
						foreach($custom_data as $key => $data){
							if(	$key == "_edit_last" || $key == "_edit_lock" || $key == "_wp_page_template" || 
								$key == "在庫" || $key == "金額" || $key == "商品説明" || $key == "_dp_original") continue;
							echo '<div class="detail-area">';
							echo '<div class="detail-left">'.$key.'</div>';
							echo '<div class="detail-right">'.$data[0].'</div>';
							echo '</div>';
						}
					?>
					
				</div>
	
				<div class="item-line"></div>
	
				<div class="item-detail">
					<div class="item-exp">商品説明</div>
					<?php echo $custom_data['商品説明'][0];?>
	
				</div>
			</div>
		</div>
	</div>

	<div class="item-connection-line"></div>

	<!-- 関連商品 -->
	<div class="item-connection">
		<div class="item-connection-title">関連商品</div>

		<div class="item-connection-form">
			<input type="button" value="<" class="item-prev" onclick="PrevItem()" style="display:none;">
			<?php
				$count = 0;
				foreach($myposts as $page_data){
					// カスタムフィールド取得
					$custom_field_data = get_post_meta(  $page_data->ID );
					if($count >= $item_connection_count){
						echo '<a href="' . $page_data->guid . '" id=item'.$count.' style="display: none;">';
					}else{
						echo '<a href="' . $page_data->guid . '" id=item'.$count.'>';
					}
					echo '<img src="'.get_template_directory_uri().'/assets/images/' . $page_data->post_title . '1.png" alt="">';
					echo  $page_data->post_title."<br>";
					echo  $custom_field_data['金額'][0];
					echo '</a>';
					$count++;
				}
			?>
			<input type="button" value=">" class="item-next"  onclick="NextItem()">
		</div>
	</div>


</main><!-- #site-content -->

<?php get_footer(); ?>
