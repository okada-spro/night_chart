<?php 

    require_once("userClass.php");
    require_once("zoomClass.php");
    require_once("videoviewClass.php");

    //ユーザー
     $user = wp_get_current_user();

      //クラス作成
    $users_data= new UserClass();
    $zoom_data= new ZoomClass();
    $video_data= new VideoViewClass();


    //ZOOMデータの作成
    $users_data->getZoomRow();
    $zoom_data->getZoomMonthRow();

    //今年、今月
    $now_year =  date("Y");
    $now_month =  date("m");

    
    //CSVファイル用の配列を準備
    $csv_export_array =array();

	//ユーザーの全データを取得
	$users = get_users( array('orderby'=>'ID','order'=>'ASC') ); 



	//var_dump($users);

    $update_str = "";

    //レベルを更新
    if(isset($_POST['is_save']) ){

        //var_dump($_POST);

        //動画追加データ(何もなかった場合は取得してこないので注意) *タイプなどの変更があった場合はリセットする
        if(isset($_POST['is_video_data']) && isset($_POST['is_details']) ){

             $users_data->UpdataUserVideoAdd($_POST['id'],$_POST['is_video_data']);
        }
        else if(!isset($_POST['is_video_data']) && isset($_POST['is_details']) ){

           //echo "追加データなし";
            $users_data->UpdataUserVideoAdd($_POST['id'],"");
        }
       

        if(isset($_POST['input_level'])){

            //タイプが変わるので追加動画をリセット
            if(get_the_author_meta('member_level',$_POST['id']) !=  $_POST['input_level'])
            {
                $users_data->UpdataUserVideoAdd($_POST['id'],"");
            }

            update_user_meta($_POST['id'], 'member_level', $_POST['input_level']);

            $update_str = "更新しました";
        }


        if(isset($_POST['input_type'])){


             //タイプが変わるので追加動画をリセット
            if(get_the_author_meta('member_type',$_POST['id']) !=  $_POST['input_type'])
            {
                $users_data->UpdataUserVideoAdd($_POST['id'],"");
            }

            update_user_meta($_POST['id'], 'member_type', $_POST['input_type']);

            $update_str = "更新しました";
        }

        if(isset($_POST['input_kunren_type'])){
            update_user_meta($_POST['id'], 'kunren_type', $_POST['input_kunren_type']);

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

        if(isset($_POST['input_ban'])){

            $input_ban = false;

            if($_POST['input_ban'] == 1){
                $input_ban = true;
            }

            update_user_meta($_POST['id'], 'login_ban', $input_ban);

            $update_str = "更新しました";
        }

        if(isset($_POST['input_add_mtg'])){
            update_user_meta($_POST['id'], 'add_mtg_num', $_POST['input_add_mtg']);
            update_user_meta($_POST['id'], 'add_mtg_save_month',$now_month);
            update_user_meta($_POST['id'], 'add_mtg_save_year', $now_year);

            $update_str = "更新しました";
        }



        if(isset($_POST['input_user_is_updata'])){


            if($_POST['input_user_is_updata'] == "")
            {
                update_user_meta($_POST['id'], 'user_is_updata', "");
            }
            else{
                update_user_meta($_POST['id'], 'user_is_updata',1);
            }
            

            $update_str = "更新しました";
        }

         if(isset($_POST['input_user_updata_day'])){

            if($_POST['input_user_updata_day']  != "")
            {

                update_user_meta($_POST['id'], 'user_updata_day', $_POST['input_user_updata_day'] . " 00:00:00");
            }
            else{
                update_user_meta($_POST['id'], 'user_updata_day',"");
            }
           
            $update_str = "更新しました";
        }


        if(isset($_POST['input_user_updata_interval'])){

            update_user_meta($_POST['id'], 'user_updata_interval', $_POST['input_user_updata_interval']);
           
            $update_str = "更新しました";
        }

        if(isset($_POST['input_user_updata_memo'])){

            update_user_meta($_POST['id'], 'user_memo', $_POST['input_user_updata_memo']);
           
            $update_str = "更新しました";
        }

        

    }

    //リスト削除関連
    if(isset($_POST["post_list_delete"]))
    {
        update_user_meta($_POST['id'], 'list_delete', "1"); //リストから削除
    }
    else  if(isset($_POST["post_list_up"]))
    {
        update_user_meta($_POST['id'], 'list_delete', "0");//リスト復活
    }

    $input_disp_table = get_user_meta($user->ID, 'user-status-page-disp',true );//生存(0)or離脱(1)orALL(2)
    $disp_level_table =  get_user_meta($user->ID, 'user-level-page-disp',true );//

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
    else if($disp_level_table == 8){ //特別セミナー
        $input_level_table = UserClass::SPECIAL_SEMINAR;
    }
    else if($disp_level_table == 9){ //特別セミナーを非表示
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

    $('table.lecture-table')
        .tablesorter({})
        .tablesorterPager({
            container: $(".pager"),
            size: 500,
    });
});
</script>

<script type="text/javascript"> 
<!-- 

function submitCheckFnc( id ){

	document.getElementById("trade_page_form_" + id).submit();
    
}

function submitCheckMailFnc( id ){

	document.getElementById("mail_send_user_" + id).submit();
    
}

// -->
</script>


<div class="history_list_admin_title_area">
    <div class="history_list_admin_title">
        <div class="history_list_admin_title_str">生徒一覧</div>
    </div>
</div>


<div class="page_div_box">
    <p>
    <div class="user-list-table_sarch">
        <form action="<?php  $id = 11; echo get_page_link( $id );?>" method="post">
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

        <form action="<?php  $id = 11; echo get_page_link( $id );?>" method="post">
            <input type="text"  name="input_serch" value="<?php echo $serch_str;?>"  style="height:50px;display: block;margin: 50px auto 0 auto;"></input>
            <input type="submit" value="検索・更新"   class="same-user-select">
        </form>
    </div>
    </p>

<?php 
    if($disp_user_data)
    { 
?>
        <div class="user-table-container mode-pc">
            <table class="lecture-table" id="userTable">
               <thead>
                <tr>
                    <th class="fixed_th_1">ID</th>
                    <th class="fixed_th_2 mode-tab">ユーザー名</th>
                    <th class="fixed_th_3">名前</th>
                    <th>会員</th>
                    
                    <th>生存</th>
                    <th>BAN</th>
                    <th>ザラ場</th>
                    <th>講義</th>
                    <th>メールアドレス</th>
                    <th>電話番号</th>
                    <th><font size="5">最終ﾛｸﾞｲﾝ/登録日</font></th>
                    <th>レポート</th>
                    <th>詳細</th>
                </tr>
                </thead>
                <tbody>
            <?php 

                $id_count = 0;
                
                foreach ($disp_user_data as $row)
                {
                   
            
                    $Withdrawal =get_the_author_meta('member_withdrawal',$row->ID);

                    $member_ban = get_the_author_meta('login_ban',$row->ID);

                    //リスト削除
                    $list_delete =  get_the_author_meta('list_delete',$row->ID);
                
                    if($list_delete == 1)
                    {
                        continue;
                    }

                    if($Withdrawal == "" || $Withdrawal == NULL)
                    {
                        $Withdrawal = 0;
                    }

                    

                    if(($input_disp_table == 2) || ($input_disp_table == 1 && $member_ban == true)|| ($input_disp_table == 0 && $Withdrawal == 0)|| ($input_disp_table == 4 && $Withdrawal == 0 && $member_ban == false)   )
                    {
                        $member_level = get_the_author_meta('member_level',$row->ID);
                        $member_type = get_the_author_meta('member_type',$row->ID);
                        $member_ban = get_the_author_meta('login_ban',$row->ID);
                        $kunren_type = get_the_author_meta('kunren_type',$row->ID);

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
                        else if( $disp_level_table == 8 && $member_level == UserClass::SPECIAL_SEMINAR ){ //特別セミナーだけを表示
                            $is_disp = true; 
                        }
                        else if( $disp_level_table == 9 && $member_level != UserClass::SPECIAL_SEMINAR ){ //特別セミナーを非表示
                            $is_disp = true; 
                        }
                        else if( $disp_level_table == 6 && $member_level != UserClass::DOGA && $member_level != UserClass::NEW_DOGA  && $member_level != UserClass::SPECIAL_SEMINAR && $member_type != 2){ //株のみ表示
                            $is_disp = true; 
                        }
                        else if( $disp_level_table == 7 && $member_level != UserClass::DOGA && $member_level != UserClass::NEW_DOGA && $member_level != UserClass::SPECIAL_SEMINAR && $member_type != 1){ //FXのみ表示
                            $is_disp = true;
                        }
                        else if( $disp_level_table == 11 && $member_level != UserClass::NEW_DOGA ){ //新動画会員を非表示
                            $is_disp = true; //新動画会員を非表示
                        }

                        else if( $disp_level_table == UserClass::DOGA && $member_level == UserClass::DOGA ){ //動画会員
                            $is_disp = true;
                        }
                        else if( $disp_level_table == 10 && $member_level == UserClass::NEW_DOGA ){ //動画会員
                            $is_disp = true;
                        }
                        

                        if($is_disp )
                        {
                             $id_count++;

                            $zoom_plans_num =  $zoom_data->checkUserZoomPlans($row->ID);
                            $kougi_plans_num =  $zoom_data->checkUserKougiPlans($row->ID);

                            //最終ﾛｸﾞｲﾝ
                            $last_login = get_the_author_meta('last_login',$row->ID);

                            if($last_login)
                            {
                            //  $date = new DateTime($last_login);
                            //  $date1 = $date->modify('+9 hours');

                                $the_login_date =  date('Y年m月d日 H時i分', $last_login);
                            }
                            else{
                                $the_login_date = "";
                            }
                            //新規登録
                            $registerd = get_the_author_meta('user_registered',$row->ID);
                            $the_registerd_date =  date('Y年m月d日 H時i分', strtotime($registerd));

                            if( $row->ID != 1 && $row->ID != 19 && $row->ID != 123){

                                array_push($csv_export_array,$row->ID);

                            }
            ?>
                <tr>

                      <form action="<?php  $id = 37; echo get_page_link( $id );?>" method="post" id="trade_page_form_<?php echo  $row->ID;?>" name="trade_page_form_<?php echo  $row->ID;?>" target="_blank">
                            <input type="hidden" name="years" value="<?php echo $now_year;?>">
                            <input type="hidden" name="id" value="<?php echo  $row->ID;?>">
                            <input type="hidden" name="is_list" value="checklist">
                     </form>
                        
                    
                        <td class="fixed_th_1">
                            <a href="javaScript:submitCheckFnc(<?php echo  $row->ID;?>)" >
                                <?php echo $id_count;?>
                            </a>
                        </td>
                    

                    <td class="fixed_th_2 mode-tab user-data"><?php echo $row->user_login;?></td>
                    <td  class="fixed_th_3">
                        <?php echo get_the_author_meta('last_name',$row->ID);?>　<?php echo get_the_author_meta('first_name',$row->ID);?>
                       


                    </td>
                    <td>
                        <?php if( $member_level == 0 && $member_type > 0){ //訓練生 ?>
                            <?php echo $users_data->checkMemberTypeStr($member_type);?><br>
                        <?php } ?>

                        <?php echo $users_data->checkLevelStr($member_level);?>
                        
                    </td>

                    <td><?php echo $users_data->checkWithdrawalStr($Withdrawal);?></td>

                    <td <?php if($member_ban == true){echo 'bgcolor=salmon';}?>>
                        <?php
                            if($member_ban == true){
                                echo "停";
                            }
                            else{
                                echo "常";
                            }
                        ?>
                    </td>

                    <td <?php if($zoom_plans_num >= 5){echo 'bgcolor=salmon';}?>   class="user-nowrap">
                       <?php 
                            if(($kunren_type == UserClass::KUNREN_TYPE_KOUGI && $input_level_table == UserClass::KUNRENSEI && $member_type ==1) || ($member_level == UserClass::KUNRENSEI  && $member_type == 2))
                            {
                               
                            }else{
                        ?>

                             <?php echo $zoom_plans_num;?>回

                        <?php } ?>
                    </td>
                    <td>
                       <?php echo $kougi_plans_num;?>回
                    </td>
                  
                    
                     <form action="<?php  $id = 806; echo get_page_link( $id );?>" method="post" id="mail_send_user_<?php echo  $row->ID;?>" name="mail_send_user_<?php echo  $row->ID;?>" target="_blank">
                        <input type="hidden" name="send_user_id" value="<?php echo  $row->ID;?>">
                    </form>


                      <td>
                            <a href="javaScript:submitCheckMailFnc(<?php echo  $row->ID;?>)" >
                                 <?php echo $row->user_email;?>
                            </a>
                      </td>

                   
                    
                   
                    <td><?php echo get_the_author_meta('billing_phone',$row->ID);?></td>
                    <td style="font-size:18px;"><?php echo $the_login_date;?><br><?php echo $the_registerd_date;?></td>

                   

                    <td>
                        <?php if($member_level != UserClass::DOGA && $member_level != UserClass::NEW_DOGA){ //動画会員はなし?>
                            <form action="<?php  $id = 463; echo get_page_link( $id );?>" method="post" target="_blank">
                                <input type="hidden" name="user_id" value="<?php echo $row->ID;?>">
                                <input type="hidden" name="report_admin_list" value="report_admin_list">
                                <input type="submit" value="レポート"   style="background-color: rosybrown;">
                            </form>
                        <?php } ?>
                    </td>
                    <td>
                        <form action="<?php  $id = 11; echo get_page_link( $id );?>" method="post" target="_blank">
                            <input type="hidden" name="id" value="<?php echo $row->ID;?>">
                            <input type="hidden" name="is_details" value="details">
                            <input type="submit" value="詳細">
                        </form>
                    </td>
                </tr>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            </tbody>
            </table>
        </div>

        <div class="user-table-container mode-sp">
            <table class="lecture-table" id="userTable">

        <?php  
            foreach ($disp_user_data as $row)
            {
        
                $Withdrawal =get_the_author_meta('member_withdrawal',$row->ID);

                 $member_ban = get_the_author_meta('login_ban',$row->ID);

               //リスト削除
               $list_delete =  get_the_author_meta('list_delete',$row->ID);
                
               if($list_delete == 1)
               {
                   continue;
               }


                if($Withdrawal == "" || $Withdrawal == NULL)
                {
                    $Withdrawal = 0;
                }

                if(($input_disp_table == 2) || ($input_disp_table == 1 && $member_ban == true)|| ($input_disp_table == 0 && $Withdrawal == 0)|| ($input_disp_table == 4 && $Withdrawal == 0 && $member_ban == false)   )
                {
                    $member_level = get_the_author_meta('member_level',$row->ID);
                    $member_type = get_the_author_meta('member_type',$row->ID);
                    $member_ban = get_the_author_meta('login_ban',$row->ID);

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
                    else if( $disp_level_table == 8 && $member_level == UserClass::SPECIAL_SEMINAR ){ //特別セミナーだけを表示
                        $is_disp = true; 
                    }
                    else if( $disp_level_table == 9 && $member_level != UserClass::SPECIAL_SEMINAR ){ //特別セミナーを非表示
                        $is_disp = true; 
                    }
                    else if( $disp_level_table == 6 && $member_level != UserClass::DOGA && $member_level != UserClass::NEW_DOGA  && $member_level != UserClass::SPECIAL_SEMINAR && $member_type != 2){ //株のみ表示
                        $is_disp = true; 
                    }
                    else if( $disp_level_table == 7 && $member_level != UserClass::DOGA && $member_level != UserClass::NEW_DOGA && $member_level != UserClass::SPECIAL_SEMINAR && $member_type != 1){ //FXのみ表示
                        $is_disp = true;
                    }
                    else if( $disp_level_table == 11 && $member_level != UserClass::NEW_DOGA ){ //新動画会員を非表示
                        $is_disp = true; //新動画会員を非表示
                    }

                    else if( $input_level_table == UserClass::DOGA && $member_level == UserClass::DOGA ){ //動画会員
                        $is_disp = true;
                    }
                    else if( $disp_level_table == 10 && $member_level == UserClass::NEW_DOGA ){ //動画会員
                        $is_disp = true;
                    }


                    if($is_disp )
                    {
                        $zoom_plans_num =  $zoom_data->checkUserZoomPlans($row->ID);
                        $kougi_plans_num =  $zoom_data->checkUserKougiPlans($row->ID);

                        //最終ﾛｸﾞｲﾝ
                        $last_login = get_the_author_meta('last_login',$row->ID);
                        
                        if($last_login)
                        {
                          //  $date = new DateTime($last_login);
                          //  $date1 = $date->modify('+9 hours');

                            $the_login_date =  date('Y年m月d日 H時i分', $last_login);
                        }
                        else{
                            $the_login_date = "";
                        }

                         //新規登録
                         $registerd = get_the_author_meta('user_registered',$row->ID);
                         $the_registerd_date =  date('Y年m月d日 H時i分', strtotime($registerd));
        ?>
            <tbody>
            <!-- <thead> -->
                <tr class="disp_open" data-id=<?php echo $row->ID;?> style="background: #3f3f3f;">
                    <th>ID▽</th>
                    <th colspan="2">名前</th>
                    <th>会員</th>
                </tr>
            <!-- </thead> -->
            <!-- <tbody> -->
                <tr class="disp_open" data-id=<?php echo $row->ID;?>>

                      <form action="<?php  $id = 37; echo get_page_link( $id );?>" method="post" id="trade_page_form_<?php echo  $row->ID;?>" name="trade_page_form_<?php echo  $row->ID;?>" target="_blank">
                            <input type="hidden" name="years" value="<?php echo $now_year;?>">
                            <input type="hidden" name="id" value="<?php echo  $row->ID;?>">
                            <input type="hidden" name="is_list" value="checklist">
                     </form>
                    <td>
                        <a href="javaScript:submitCheckFnc(<?php echo  $row->ID;?>)" >
                            <?php echo $row->ID;?>
                        </a>
                    </td>
                    <!-- <td colspan="2"><?php //echo $row->user_login;?></td> -->
                    <td colspan="2"><?php echo get_the_author_meta('last_name',$row->ID);?>　<?php echo get_the_author_meta('first_name',$row->ID);?></td>
                    <td>
                        <?php if( $member_level == 0 && $member_type > 0){ //訓練生 ?>
                            <?php echo $users_data->checkMemberTypeStr($member_type);?><br>
                        <?php } ?>
                        <?php echo $users_data->checkLevelStr($member_level);?>
                    </td>
                </tr>

                <tr class="disp_close<?php echo $row->ID;?>">
                    <th>生存</th>
                    <th>BAN</th>
                    <th>ザラ場</th>
                    <th>講義</th>
                </tr>
                <tr class="disp_close<?php echo $row->ID;?>">
                    <td><?php echo $users_data->checkWithdrawalStr($Withdrawal);?></td>

                    <td <?php if($member_ban == true){echo 'bgcolor=salmon';}?>>
                        <?php
                            if($member_ban == true){
                                echo "停";
                            }
                            else{
                                echo "常";
                            }
                        ?>
                    </td>

                    <td <?php if($zoom_plans_num >= 5){echo 'bgcolor=salmon';}?>   class="user-nowrap">
                    <?php echo $zoom_plans_num;?>回
                    </td>
                    <td>
                    <?php echo $kougi_plans_num;?>回
                    </td>
                </tr>
                
                <tr class="disp_close<?php echo $row->ID;?>">
                    <th colspan="4">メールアドレス</th>
                </tr>
                <tr class="disp_close<?php echo $row->ID;?>">
                    
                    <form action="<?php  $id = 806; echo get_page_link( $id );?>" method="post" id="mail_send_user_<?php echo  $row->ID;?>" name="mail_send_user_<?php echo  $row->ID;?>" target="_blank">
                        <input type="hidden" name="send_user_id" value="<?php echo  $row->ID;?>">
                    </form>

                    <td colspan="4">
                        <a href="javaScript:submitCheckMailFnc(<?php echo  $row->ID;?>)" >
                                <?php echo $row->user_email;?>
                        </a>
                    </td>
                </tr>

                <tr class="disp_close<?php echo $row->ID;?>">
                    <th colspan="4">電話番号</th>
                </tr>
                <tr class="disp_close<?php echo $row->ID;?>">
                    <td colspan="4"><?php echo get_the_author_meta('billing_phone',$row->ID);?></td>
                </tr>

                <tr class="disp_close<?php echo $row->ID;?>">
                    <th colspan="4">最終ﾛｸﾞｲﾝ/登録日</th>
                </tr>
                <tr class="disp_close<?php echo $row->ID;?>">
                    <td colspan="4" style="font-size:18px;"><?php echo $the_login_date;?><br><?php echo $the_registerd_date;?></td>
                </tr>

                <tr class="disp_close<?php echo $row->ID;?>">
                    <th colspan="2">レポート</th>
                    <th colspan="2">詳細</th>
                </tr>
                <tr class="disp_close<?php echo $row->ID;?>"> 
                    <td colspan="2">
                        <?php if($member_level != UserClass::DOGA && $member_level != UserClass::NEW_DOGA){ //動画会員はなし?>
                            <form action="<?php  $id = 463; echo get_page_link( $id );?>" method="post" target="_blank">
                                <input type="hidden" name="user_id" value="<?php echo $row->ID;?>">
                                <input type="hidden" name="report_admin_list" value="report_admin_list">
                                <input type="submit" value="レポート"   style="background-color: rosybrown;">
                            </form>
                        <?php } ?>
                    </td>
                    <td colspan="2">
                        <form action="<?php  $id = 11; echo get_page_link( $id );?>" method="post" target="_blank">
                            <input type="hidden" name="id" value="<?php echo $row->ID;?>">
                            <input type="hidden" name="is_details" value="details">
                            <input type="submit" value="詳細">
                        </form>
                    </td>
                </tr>
                <tr> 
                    <td colspan="4" style="border:none;height:10px"></td>
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
                <option  value="100">100</option>
                <option selected="selected" value="500">500</option>
            </select>
        </div>
        <?php if($serch_str !=""){ //検索がない時のみ ?>



        <?php } ?>


        
        <div class="user-list-csv-button-area">

             <form action="<?php  $id = $page_post; echo get_page_link( $id );?>" method="post"  name="csv_post_ex">

                <?php foreach ($csv_export_array as  $csv_key => $csv_value ){ ?>

                     <input type="hidden" name="csv_ids[]" value="<?php echo $csv_value;?>">

                <?php } ?>

                <input type="hidden" name="csv_file_name" value="seito_member_list">
                <input type="hidden" name="csv_ex" value="csv_ex">

                <div class="user-list-csv-button">
                    <input type="submit" value="表示中のユーザーcsv出力する">
                </div>
             </form>

        </div>

        <div class="user-list-mail-button-area">

             <form action="<?php  $id = "952"; echo get_page_link( $id );?>" method="post"  name="mail_post_ex" target="_blank">

                <?php foreach ($csv_export_array as  $csv_key => $csv_value ){ ?>

                     <input type="hidden" name="mail_ids[]" value="<?php echo $csv_value;?>">

                <?php } ?>

                <input type="hidden" name="mail_all" value="mail_all">

                <div class="user-list-mail-button">
                    <input type="submit" value="表示中のユーザーにメールを送信する">
                </div>
             </form>

        </div>


    <?php }else{?>
        <p>
            <font color="red">表示するデータがありません</font>
        </p>

    <?php } ?>
</div>



<?php }elseif(isset($_POST['is_details']) ){ ?>


    
<script type="text/javascript"> 
<!-- 

function check(){

	if(window.confirm('削除してよろしいですか？')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else{ // 「キャンセル」時の処理

		window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止

	}

}

// -->
</script>

    <?php if($update_str != ""){ ?>
        <div class="index_updata_center">
            <p><?php echo $update_str;?></p>
        </div>
   
    <?php }?>

     <?php $user_info = get_userdata( $_POST['id'] );?>

     <form action="<?php  $id = 11; echo get_page_link( $id );?>" method="post">
     <div class="user-table-container">
     <table class="user-table-two">
        <colgroup span="2"></colgroup>
        <tr>
            <th>項目</th>
            <th>詳細</th>
        </tr>
        <tr>
            <td>ID</td>
            <td><?php echo $user_info->ID;?></td>
        </tr>
        <tr>
            <td>ユーザー名</td>
            <td><?php echo $user_info->user_login;?></td>
        </tr>
        <tr>
            <td>名前</td>
            <td><?php echo get_the_author_meta('last_name',$user_info->ID);?>　<?php echo get_the_author_meta('first_name',$user_info->ID);?></td>
        </tr>
        <tr>
            <td>会員</td>
            <?php $level = 0;?>
            <?php if(get_the_author_meta('member_level',$user_info->ID) != NULL){
                        $level = get_the_author_meta('member_level',$user_info->ID);
                        
                  }?>
            <td>
                
                <select name="input_level" class="level-width-list" style="width: 100%;text-align: center;">
                <?php foreach ($users_data->level_array_data as $key => $value) {?>
                   <option value="<?php echo $key;?>" <?php if($level == $key){ echo "selected";}?>><?php echo $value;?></option>
                <?php } ?>
                </select>
            </td>
        </tr>

        <tr>
            <td>タイプ(訓練生のみ適用)</td>
            <?php $member_type = 0;?>
            <?php if(get_the_author_meta('member_type',$user_info->ID) != NULL){
                        $member_type = get_the_author_meta('member_type',$user_info->ID);
                        
                  }?>
            <td>
                
                <select name="input_type" class="same-width-list">
                <?php foreach ($users_data->member_type_array as $key => $value) {?>
                   <option value="<?php echo $key;?>" <?php if($member_type == $key){ echo "selected";}?>><?php echo $value;?></option>
                <?php } ?>
                </select>
            </td>
        </tr>
         <tr>
            <td>参加タイプ(訓練生のみ適用)</td>
            <?php $kunren_type = 0;?>
            <?php if(get_the_author_meta('kunren_type',$user_info->ID) != NULL){
                        $kunren_type = get_the_author_meta('kunren_type',$user_info->ID);
                        
                  }?>
            <td>
                
                <select name="input_kunren_type" class="same-width-list">
                <?php foreach ($users_data->kunren_type_data as $key => $value) {?>
                   <option value="<?php echo $key;?>" <?php if($kunren_type == $key){ echo "selected";}?>><?php echo $value;?></option>
                <?php } ?>
                </select>
            </td>
        </tr>

         <tr>
            <td>生存・脱落</td>
            <?php $withdrawal = 0;?>
            <?php if(get_the_author_meta('member_withdrawal',$user_info->ID) != NULL){
                        $withdrawal = get_the_author_meta('member_withdrawal',$user_info->ID);
                  }?>
            <td>
                 <select name="input_withdrawal" class="same-width-list">
                 <?php foreach ($users_data->withdrawal_array as $key => $value) {?>
                    <option value="<?php echo $key;?>" <?php if($withdrawal == $key){ echo "selected";}?>><?php echo $value;?></option>
                 <?php } ?>
                 </select>
            </td>
        </tr>

         <tr>
            <td>操作停止</td>
            <?php $ban = false;?>
            <?php if(get_the_author_meta('login_ban',$user_info->ID) != NULL){
                        $ban = get_the_author_meta('login_ban',$user_info->ID);
                  } 
                  
                  if($ban == 1){
                    $ban = true;
                  }
                  else{
                    $ban = false;
                  }

                  $list_delete =  get_the_author_meta('list_delete',$user_info->ID);
                
                  ?>
            <td>
                <?php if($list_delete == 1){ ?>

                    <font color="red">*現在BAN状態です。<br>変更する場合はリストに戻してから変更してください</font>

                    <input type="hidden" name="input_ban" value="1">

                <?php }else{ ?>
                 <select name="input_ban" class="same-width-list">
                    <option value="0" <?php if($ban == false){ echo "selected";}?>>通常</option>
                    <option value="1"  <?php if($ban == true){ echo "selected";}?>>停止</option>
                 </select>
                 <?php } ?>
            </td>
        </tr>


         <tr>
            <td>ZOOMの参加可能回数（月）</td>
            <?php $max_mtg_num = 3;?>

            <?php if(get_the_author_meta('max_mtg_num',$user_info->ID) != NULL){
                        $max_mtg_num = get_the_author_meta('max_mtg_num',$user_info->ID);
                  }?>
            <td>
                 <input type="number"  name="input_max_mtg" value='<?php echo $max_mtg_num;?>'   style="width:80px;" min="0">回</input>
            </td>
        </tr>
         <tr>
            <td>ZOOMの追加回数（月）</td>
            <?php $add_mtg_num = 0;?>
            <?php $add_mtg_save_month = 0;?>
            <?php $add_mtg_save_year = 0;?>

            <?php if(get_the_author_meta('add_mtg_num',$user_info->ID) != NULL){
                        $add_mtg_num = get_the_author_meta('add_mtg_num',$user_info->ID);
                  }?>

            <?php if(get_the_author_meta('add_mtg_save_month',$user_info->ID) != NULL){
                        $add_mtg_save_month = get_the_author_meta('add_mtg_save_month',$user_info->ID);
                  }?>

            <?php if(get_the_author_meta('add_mtg_save_year',$user_info->ID) != NULL){
                        $add_mtg_save_year = get_the_author_meta('add_mtg_save_year',$user_info->ID);
                  }?>

            <?php
                //別の月になっていたらリセット
                if($now_year != $add_mtg_save_year || $now_month != $add_mtg_save_month)
                {
                    $add_mtg_num = 0;
                }
            ?>


            <td>
                 <input type="number"  name="input_add_mtg" value='<?php echo $add_mtg_num;?>'   style="width:80px;"  min="0">回</input>
            </td>
        </tr>

      
         <tr>
            <td>今月のZOOM参加</td>
            <td><?php echo $users_data->checkUserZoomPlans($user_info->ID);?>回</td>
        </tr>
        <tr>
            <td>メール</td>
            <td><?php echo $user_info->user_email;?></td>
        </tr>
        <tr>
            <td>郵便番号</td>
            <td><?php echo get_the_author_meta('billing_postcode',$user_info->ID);?></td>
        </tr>
         <tr>
            <td>住所</td>
            <td><?php echo get_the_author_meta('billing_address_1',$user_info->ID);?>　<?php echo get_the_author_meta('billing_address_2',$user_info->ID);?></td>
        </tr>
         <tr>
            <td>電話番号</td>
            <td><?php echo get_the_author_meta('billing_phone',$user_info->ID);?></td>
        </tr>

        <?php
             $zoom_plans_num =  $zoom_data->checkUserZoomPlans($row->ID);
             $kougi_plans_num =  $zoom_data->checkUserKougiPlans($row->ID);

             //最終ﾛｸﾞｲﾝ
             $last_login = get_the_author_meta('last_login',$user_info->ID);
             
             if($last_login)
             {
               //  $date = new DateTime($last_login);
               //  $date1 = $date->modify('+9 hours');

                 $the_login_date =  date('Y年m月d日 H時i分', $last_login);
             }
             else{
                 $the_login_date = "";
             }

              //新規登録
              $registerd = get_the_author_meta('user_registered',$user_info->ID);
              $the_registerd_date =  date('Y年m月d日 H時i分', strtotime($registerd));

             //更新日
             $updata_month_base = $users_data->getUserUpdataDay($user_info->ID);
             $updata_month =  date('Y年m月d日 H時i分', strtotime($updata_month_base));
        ?>

       

        <tr>
            <td>登録日</td>
            <td><?php echo $the_registerd_date;?></td>
        </tr>
        
         <tr>
            <td>最終ﾛｸﾞｲﾝ</td>
            <td><?php echo $the_login_date;?></td>
        </tr>
        <tr>
            <td>次回更新日</td>
            <td>
                <input type="date"  name="input_user_updata_day" value='<?php echo date('Y-m-d', strtotime($updata_month_base));?>' ></input>
            </td>
        </tr>
        <tr>
            <td>更新</td>
            <?php 
                $is_updata = "";
                
                if(get_the_author_meta('user_is_updata',$user_info->ID) != ""){
                        $is_updata = 1;
                }?>
            <td>
                 <select name="input_user_is_updata" class="same-width-list">
                    <option value="" <?php if($is_updata == ""){ echo "selected";}?>>更新する</option>
                    <option value="1" <?php if($is_updata != ""){ echo "selected";}?>>更新無し</option>
                 </select>
            </td>
        </tr>
        <tr>
            <td>更新間隔</td>
            <?php 
                $updata_interval = 0;
                
                if(get_the_author_meta('user_updata_interval',$user_info->ID) != ""){
                        $updata_interval = get_the_author_meta('user_updata_interval',$user_info->ID);
                }?>
            <td>
                 <select name="input_user_updata_interval" class="same-width-list">
                    <option value="0" <?php if($updata_interval == 0){ echo "selected";}?>>１年更新</option>
                    <option value="1" <?php if($updata_interval == 1){ echo "selected";}?>>一ヶ月更新</option>
                 </select>
            </td>
        </tr
        <tr>
            <td>メモ</td>
            <td>
                <textarea name="input_user_updata_memo" rows="4" cols="40"><?php echo get_the_author_meta('user_memo',$user_info->ID);?></textarea>
            </td>
        </tr>



        <tr>
            <td>動画閲覧</td>
            <td>
                <?php 
                          //初期化
                    $video_data->init();

                    // データ取得クエリ実行
                    $video_data->getReleaseDateViewRow();

                     // カテゴリ取得クエリ実行
                    $video_data->getCategoryDataRow();

                    //閲覧可能動画取得
                    $is_video_array = $video_data->checkMemberEnableVideo($level,$member_type);

                    //追加データ
                    $uder_vide_add_data = $users_data->GetUserVideoAdd(  $user_info->ID   );


                    //var_dump($uder_vide_add_data);

                ?>

                <div class="user_detail_video_area">
                    <?php foreach ($video_data->view_category_row as $row) { ?>

                        <?php if($row->ID == 1){continue;} ?>

                        <div class="">

                            <?php if( isset($uder_vide_add_data[$row->ID]) ){?>

                                 <input type="checkbox" name="is_video_data[]"  value="<?php echo $row->ID;?>" checked="checked">　<?php echo $row->post_category_name; ?>
                        

                            <?php }else{ ?>

                                <input type="checkbox" <?php if( isset($is_video_array[$row->ID]) ){ ?>name="video_data[]" <?php }else{ ?> name="is_video_data[]" <?php } ?>  value="<?php echo $row->ID;?>" <?php if( isset($is_video_array[$row->ID]) ){ ?>checked="checked"  disabled<?php } ?>>　<?php echo $row->post_category_name; ?>
                        
                             <?php } ?>
                        </div>

                    <?php } ?>
                </div>

                <div class="user_detail_video_alert">*会員、もしくはタイプを変更すると<br>リセットされるので必ず再確認してください</div>
            </td>


            
        </tr>


        <tr>
            <td colspan="2">
                <input type="hidden" name="id" value="<?php echo $_POST['id'];?>">
                <input type="hidden" name="is_save" value="save">
                <input type="hidden" name="is_details" value="details">
                <p><input  class="index_history_button"type="submit" value="更新"></p>
            </td>
        </tr>



        


        </form>

        <?php if($ban == true){ ?>
            <tr>
                <td colspan="2">
                    <div class="list-delete-area">

                        <?php $list_delete =  get_the_author_meta('list_delete',$user_info->ID);?>
            
                        <?php if($list_delete == 1){ ?>

                            <form action="<?php  $id = 11; echo get_page_link( $id );?>" method="post">
                                <input type="hidden" name="id" value="<?php echo $_POST['id'];?>">
                                <input type="hidden" name="post_list_up" value="post_list_up">
                                <input type="hidden" name="is_details" value="details">
                                <input  class="index_history_button"type="submit" value="この情報をリストに戻す"  style="background-color: crimson;">
                            </form>

                        <?php }else{ ?>

                            <form action="<?php  $id = 11; echo get_page_link( $id );?>" method="post" onSubmit="return check()">
                                <input type="hidden" name="id" value="<?php echo $_POST['id'];?>">
                                <input type="hidden" name="post_list_delete" value="post_list_delete">
                                <input type="hidden" name="is_details" value="details">
                                <input  class="index_history_button"type="submit" value="この情報をリストから削除する" style="background-color: crimson;">
                            </form>

                        <?php } ?>
                    </div>
                
                </td>
            </tr>
        <? } ?>
     </table>

     
     <p>
        
            <button type="button" class="user-tabclose-button"  onClick="javascript:window.close();">閉じる</button>
        
	</p>
     <p>
	    <a class="index_button" href="<?php $id = 11; echo get_page_link( $id );?>"   >一覧に戻る</a>
	</p>
<?php } ?>

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