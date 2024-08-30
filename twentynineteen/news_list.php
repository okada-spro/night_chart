<?php 

   
    require_once("newsClass.php");

    $user = wp_get_current_user();

    //クラス作成
    $input_data= new NewstClass();

    //初期化
    $input_data->init();

    //ニュースを取得
    $disp_post_array = $input_data->getNewsPost($user->ID);

	 //保存したお知らせを取得
	 $new_id = get_user_meta($user->ID, 'news-new-check',true );

	 if( $new_id == ""){
		$new_id = 0;
	 }

	 //最後の記事
	 $save_id = $disp_post_array[0]["ID"];

    if( $new_id != $save_id)
    {
        update_user_meta($user->ID, 'news-new-check', $save_id);
    }

?>

<table class="news_list_table">
<?php
     foreach ($disp_post_array as  $key => $value){
?>
 



<tr>
    
    <td width="200"><?php echo $value["day"]; ?></td>
    <td><a href="<?php echo $value["permalink"]; ?>"><?php echo $value["title"]; ?></a></td>
</tr> 


<?php
     }
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