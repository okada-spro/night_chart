<?php 

   
    require_once("newsClass.php");

    $user = wp_get_current_user();

    //クラス作成
    $input_data= new NewstClass();

    //初期化
    $input_data->init();

	 //保存したお知らせを取得
	 $new_id = get_user_meta($user->ID, 'news-new-check',true );

	 if( $new_id == ""){
		$new_id = 0;
	 }

	 //最後の記事
	 $save_id = get_the_latest_ID();

    if( $new_id != $save_id)
    {
        update_user_meta($user->ID, 'news-new-check', $save_id);
    }
?>

<table class="news_list_table">
<?php
    query_posts("&posts_per_page=30&paged=$paged");
    if (have_posts()) :
    while ( have_posts() ) : the_post();
?>
 

<tr>
    <td width="200"><?php echo get_post_time( 'Y年n月j日', false, null, true ); ?></td>
    <td><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
</tr> 


<?php
    endwhile; endif;
?>

</table>

<div class="page_div_box">
<?php
    //posts_nav_link でページ送り
   // posts_nav_link(' | ', '前へ', '次へ');
    //ループをリセット
    wp_reset_query();
?>

</div>