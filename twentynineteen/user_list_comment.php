<?php 

    require_once("userClass.php");

    //ユーザー
     $user = wp_get_current_user();

      //クラス作成
    $users_data= new UserClass();

  
    //ZOOMデータの作成
    $users_data->getZoomRow();

    //今年、今月
    $now_year =  date("Y");
    $now_month =  date("m");



	//ユーザーの全データを取得
	$users = get_users( array('orderby'=>'ID','order'=>'ASC') ); 

	//var_dump($users);

    $update_str = "";

    //レベルを更新
    if(isset($_POST['is_save']) ){
        if(isset($_POST['input_level'])){
            update_user_meta($_POST['id'], 'member_level', $_POST['input_level']);

            $update_str = "更新しました";
        }

        if(isset($_POST['input_withdrawal'])){
            update_user_meta($_POST['id'], 'member_withdrawal', $_POST['input_withdrawal']);

            $update_str = "更新しました";
        }
        if(isset($_POST['input_max_mtg'])){
            update_user_meta($_POST['id'], 'max_mtg_num', $_POST['input_max_mtg']);

            $update_str = "更新しました";
        }

        if(isset($_POST['input_add_mtg'])){
            update_user_meta($_POST['id'], 'add_mtg_num', $_POST['input_add_mtg']);
            update_user_meta($_POST['id'], 'add_mtg_save_month',$now_month);
            update_user_meta($_POST['id'], 'add_mtg_save_year', $now_year);

            $update_str = "更新しました";
        }


    }


    $input_disp_table = get_user_meta($user->ID, 'user-status-page-disp',true );//生存(0)or離脱(1)orALL(2)
    $disp_level_table =  get_user_meta($user->ID, 'user-level-page-disp',true );//訓練生(0)or門下生(1)orALL(2)

    //生存or離脱
    if(isset($_POST['disp_table']) )
    {
        $input_disp_table = $_POST['disp_table'];

        update_user_meta($user->ID, 'user-status-page-disp', $input_disp_table);

    }
   

    //訓練生or門下生
    if(isset($_POST['level_table']) )
    {
        $disp_level_table = $_POST['level_table'];

        update_user_meta($user->ID, 'user-level-page-disp', $disp_level_table);
    }
   
    
    //表示配列から実際の表示用の変数に入れ返る
    if($disp_level_table <= 2){ //訓練生
        $input_level_table = UserClass::KUNRENSEI;
    }
    else if($disp_level_table == 3){ //門下生
        $input_level_table = UserClass::MONKASEI;
    }
    else if($disp_level_table == 4){ //動画会員
        $input_level_table = UserClass::DOGA;
    }
    else if($disp_level_table == 5){ //動画会員を非表示
        $input_level_table = $disp_level_table;
    }
    else{
        $input_level_table = $disp_level_table;
    }



if(!isset($_POST['is_details']) ){

    $serch_str = "";

    if( isset( $_POST["input_serch"]) )
    {
        //優先はPOST
        $serch_str =  $_POST["input_serch"];
    }
    elseif( isset( $_GET["serch_word"]))
    {
        $serch_str =  $_GET["serch_word"];
    }

    $disp_user_data = $users;

    //検索のものに変更
    if($serch_str !="")
    {
         $disp_user_data = $users_data->serchUserData($serch_str,$disp_user_data);
    }
  

?>

<script>
$(document).ready(function() {

    $('table.lecture-comment-table')
        .tablesorter({})
        .tablesorterPager({
            container: $(".pager"),
            size: 500,
    });
});
</script>

<script>
    // 表示折り畳み
    window.onload = function(){
        $("[class^='disp_close']").css("display","none");
    }

    // sp用テーブルスライド
$(function(){
    $(".disp_open").click(function(){
        var id = $(this).data('id');
        var t_text = $(this).find("th").eq(0).text();   //マーク取得

        console.log(id);
        console.log(t_text);
        
        if($(".disp_close" + id).css("display") == "none"){
            t_text = t_text.replace("▽","△");
            $(".disp_close" + id).slideDown(100);
        }else{
            t_text = t_text.replace("△","▽");
            $(".disp_close" + id).css("display","none");
        }
        $(this).find("th").eq(0).text(t_text)
    });
});
</script>


<div class="page_div_box">
    <p>
    <div class="histroy-list-comment-table_sarch">
        <form action="<?php  $id = 694; echo get_page_link( $id );?>" method="post">
            <select name="level_table"  class="same-user-select">
            <?php foreach ($users_data->disp_only_level_array_data as $key => $value) {?>
                   <option value="<?php echo $key;?>" <?php if($disp_level_table == $key){ echo "selected";}?>><?php echo $value;?></option>
            <?php } ?>
            </select>
            <select name="disp_table"  class="same-user-select">
                <?php foreach ($users_data->disp_array_data as $key => $value) {?>
                   <option value="<?php echo $key;?>" <?php if($input_disp_table == $key){ echo "selected";}?>><?php echo $value;?></option>
                <?php } ?>
            </select>
            <input type="hidden"  name="input_serch" value="<?php echo $serch_str;?>">
            <input type="submit" value="表示変更"   class="same-user-select">
        </form>

        <form action="<?php  $id = 694; echo get_page_link( $id );?>" method="post">
            <input type="text"  name="input_serch" value="<?php echo $serch_str;?>"  style="height:50px;"></input>
            <input type="submit" value="検索・更新"   class="same-user-select">
        </form>
    </div>
    </p>

<?php 
    if($disp_user_data)
    {
?>
        <div class="user-table-comment-container mode-pc">
            <table class="lecture-comment-table" id="userTable">
               <thead>
                <tr>
                    <th class="fixed_th_1" >ID</th>
                    <th >ユーザー名</th>
                    <th >名前</th>
                    <th >会員</th>
                    <th >生存</th>
                    <th  class="lecture-comment-message">コメント</th>
                </tr>
                </thead>
                <tbody>
        <?php 
            foreach ($disp_user_data as $row)
            {
        
                $Withdrawal =get_the_author_meta('member_withdrawal',$row->ID);
                 $member_type = get_the_author_meta('member_type',$row->ID);

                if($Withdrawal == "" || $Withdrawal == NULL)
                {
                    $Withdrawal = 0;
                }

                if(($input_disp_table == 2) || ($input_disp_table == $Withdrawal) )
                {
                    $member_level = get_the_author_meta('member_level',$row->ID);

                    if($member_level == "" || $member_level == NULL)
                    {
                        $member_level = 0;
                    }

                    $is_disp = false;


                  //全て表示
                    if(  $disp_level_table == 99 && $input_level_table == UserClass::ALL_DISP_CONST ){
                        $is_disp = true;
                    }
                    else if( $input_level_table == $member_level && $input_level_table == UserClass::MONKASEI  ){ //門下生の時
                        $is_disp = true;
                    }
                    else if( $disp_level_table == 0 && $input_level_table == UserClass::KUNRENSEI && $member_level == UserClass::KUNRENSEI ){ //訓練生の時の全表示
                        $is_disp = true;
                    }
                    else if( $disp_level_table == 1 && $input_level_table == UserClass::KUNRENSEI && $member_level == UserClass::KUNRENSEI  && $member_type == 1){ //訓練生の時の株表示
                        $is_disp = true;
                    }
                    else if( $disp_level_table == 2 && $input_level_table == UserClass::KUNRENSEI && $member_level == UserClass::KUNRENSEI  && $member_type == 2){ //訓練生の時のFX表示
                        $is_disp = true;
                    }
                    else if( $disp_level_table == 5 && $member_level != UserClass::DOGA ){ //動画会員を非表示
                        $is_disp = true; //動画会員を非表示
                    }
                    else if( $disp_level_table == 6 && $member_level != UserClass::DOGA && $member_type != 2){ //株のみ表示
                        $is_disp = true; 
                    }
                    else if( $disp_level_table == 7 && $member_level != UserClass::DOGA && $member_type != 1){ //FXのみ表示
                        $is_disp = true;
                    }

                    else if( $input_level_table == UserClass::DOGA && $member_level == UserClass::DOGA ){ //動画会員
                        $is_disp = true;
                    }

                    if($is_disp )
                    {
                        //$zoom_num =  $users_data->checkUserZoomJoin($row->ID);
                        $zoom_plans_num =  $users_data->checkUserZoomPlans($row->ID);
        ?>
                <tr>
                    <td class="fixed_th_1" ><?php echo $row->ID;?></td>
                    <td><?php echo $row->user_login;?></td>
                    <td class="fixed_th_2"><?php echo get_the_author_meta('last_name',$row->ID);?>　<?php echo get_the_author_meta('first_name',$row->ID);?></td>
                     <td class="fixed_th_3">
                       
                        
                        <?php if( $member_level == 0 && $member_type > 0){ //訓練生 ?>
                            <?php echo $users_data->checkMemberTypeStr($member_type);?>
                        <?php } ?>

                         <?php echo $users_data->checkLevelStr($member_level);?>
                    </td>


                    <td><?php echo $users_data->checkWithdrawalStr($Withdrawal);?></td>
                    <td class="lecture-comment-message">
                        <?php echo get_the_author_meta('message',$row->ID);?>
                    </td>
                </tr>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            </tbody>
            </table>
        </div>

        <div class="user-table-comment-container mode-sp">
            <table class="lecture-comment-table" id="userTable">
                <tbody>
        <?php 
            foreach ($disp_user_data as $row)
            {
        
                $Withdrawal =get_the_author_meta('member_withdrawal',$row->ID);
                 $member_type = get_the_author_meta('member_type',$row->ID);

                if($Withdrawal == "" || $Withdrawal == NULL)
                {
                    $Withdrawal = 0;
                }

                if(($input_disp_table == 2) || ($input_disp_table == $Withdrawal) )
                {
                    $member_level = get_the_author_meta('member_level',$row->ID);

                    if($member_level == "" || $member_level == NULL)
                    {
                        $member_level = 0;
                    }

                    $is_disp = false;


                  //全て表示
                    if(  $disp_level_table == 99 && $input_level_table == UserClass::ALL_DISP_CONST ){
                        $is_disp = true;
                    }
                    else if( $input_level_table == $member_level && $input_level_table == UserClass::MONKASEI  ){ //門下生の時
                        $is_disp = true;
                    }
                    else if( $disp_level_table == 0 && $input_level_table == UserClass::KUNRENSEI && $member_level == UserClass::KUNRENSEI ){ //訓練生の時の全表示
                        $is_disp = true;
                    }
                    else if( $disp_level_table == 1 && $input_level_table == UserClass::KUNRENSEI && $member_level == UserClass::KUNRENSEI  && $member_type == 1){ //訓練生の時の株表示
                        $is_disp = true;
                    }
                    else if( $disp_level_table == 2 && $input_level_table == UserClass::KUNRENSEI && $member_level == UserClass::KUNRENSEI  && $member_type == 2){ //訓練生の時のFX表示
                        $is_disp = true;
                    }
                    else if( $disp_level_table == 5 && $member_level != UserClass::DOGA ){ //動画会員を非表示
                        $is_disp = true; //動画会員を非表示
                    }
                    else if( $disp_level_table == 6 && $member_level != UserClass::DOGA && $member_type != 2){ //株のみ表示
                        $is_disp = true; 
                    }
                    else if( $disp_level_table == 7 && $member_level != UserClass::DOGA && $member_type != 1){ //FXのみ表示
                        $is_disp = true;
                    }

                    else if( $input_level_table == UserClass::DOGA && $member_level == UserClass::DOGA ){ //動画会員
                        $is_disp = true;
                    }

                    if($is_disp )
                    {
                        //$zoom_num =  $users_data->checkUserZoomJoin($row->ID);
                        $zoom_plans_num =  $users_data->checkUserZoomPlans($row->ID);
        ?>

                <tr class="disp_open"  data-id=<?php echo $row->ID;?>>
                    <th colspan="3">名前▽</th>
                </tr>
                <tr class="disp_open" data-id=<?php echo $row->ID;?>>
                    <td colspan="3"><?php echo get_the_author_meta('last_name',$row->ID);?>　<?php echo get_the_author_meta('first_name',$row->ID);?></td>
                </tr>
                <tr class="disp_close<?php echo $row->ID;?>">
                    <th colspan="3">ユーザー名</th>
                </tr>
                <tr class="disp_close<?php echo $row->ID;?>">
                    <td colspan="3"><?php echo $row->user_login;?></td>
                </tr>
                <tr class="disp_close<?php echo $row->ID;?>">
                    <th >ID</th>
                    <th >会員</th>
                    <th >生存</th>
                </tr>
                <tr class="disp_close<?php echo $row->ID;?>">
                    <td ><?php echo $row->ID;?></td>
                    <td >
                       
                        
                        <?php if( $member_level == 0 && $member_type > 0){ //訓練生 ?>
                            <?php echo $users_data->checkMemberTypeStr($member_type);?>
                        <?php } ?>

                         <?php echo $users_data->checkLevelStr($member_level);?>
                    </td>


                    <td><?php echo $users_data->checkWithdrawalStr($Withdrawal);?></td>
                </tr>
                <tr class="disp_close<?php echo $row->ID;?>">
                    <th colspan="3" class="lecture-comment-message">コメント</th>
                    <!-- <th colspan="3" class="lecture-comment-message">コメント</th> -->
                </tr>
                <tr class="disp_close<?php echo $row->ID;?>">
                    <!-- <td colspan="3" class="lecture-comment-message"> -->
                    <td colspan="3" class="lecture-comment-message">
                        <?php echo get_the_author_meta('message',$row->ID);?>
                    </td>
                </tr>
                <tr> 
                    <td style="border:none;height:10px"></td>
                </tr>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            </tbody>
            </table>
        </div>


         <div class="pager">
            <button type='button' class='first'>&lt;&lt;</button>
            <button type='button' class='prev'>&lt;</button>

            <span class="pagedisplay" ></span>
          

            <button type='button' class='next'>&gt;</button>
            <button type='button' class='last'>&gt;&gt;</button>

            <select class="pagesize">
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="500" selected="selected" >500</option>
            </select>
        </div>
     
    <?php }else{?>
        <p>
            <font color="red">表示するデータがありません</font>
        </p>

    <?php } ?>
</div>

<?php }

