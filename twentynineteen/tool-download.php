<?php 

    require_once("toolClass.php");
    require_once("userClass.php");

    // wpdbオブジェクト
    global $wpdb;

    $user = wp_get_current_user();
    $users_data= new UserClass();

     //自分のメンバーレベルとメンバータイプを取得
    $member_level = get_the_author_meta('member_level',$user->ID);
    $member_type = get_the_author_meta('member_type',$user->ID);

    //クラス作成
    $input_data= new ToolClass();

    //初期化
    $input_data->init();

    //データ取得
    $input_data->getIDRow();

if(!isset($_GET["category"])){

        $category_array = $input_data->get_category_array();

        //var_dump($category_array);

?>

        <?php foreach( $category_array as $key => $value){ ?>
            <?php if($value  > 0 && $users_data->checkLevelTypeMatch($key,$member_level,$member_type)){ ?>
                 <p>
		            <a class="index_button" href="<?php $id = 539; echo get_page_link( $id );?>?category=<?php echo $key; ?>"><?php echo $input_data->disp_tool_category_data[$key][0]; ?>(<?php echo $value; ?>件)</a>
	            </p>
             <?php } ?>
        <?php } ?>
<?php
  }else{
     $category_array = $input_data->get_dl_array();
?>

    <table class="user-table-two">
        <colgroup span="6"></colgroup>
        <tr>
            <th>ツール名</th>
            <th>更新日</th>
            <th>DL</th>
        </tr>
        <?php foreach ($category_array as $key =>$value) { ?>
            <?php if( $_GET["category"] == $value["input_category"] && $users_data->checkLevelTypeMatch($_GET["category"],$member_level,$member_type) ){ ?>
            <tr >
                <td><?php echo $value["input_title"]; ?></td>
                <td><?php echo $value["input_update"]; ?></td>
                <td><a href="<?php echo $value["input_url"]; ?>" download="<?php echo $value["input_file_name"]; ?>">ダウンロード</a></td>
            </tr>
            <?php } ?>
        <?php } ?>
    </table>
    
    <p>
        <a class="index_button" href="<?php $id = 539; echo get_page_link( $id );?>">カテゴリー 一覧に戻る</a>
    </p>
<?php
  }

?>