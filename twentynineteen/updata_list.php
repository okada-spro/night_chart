<?php 

    require_once("userClass.php");
    require_once("zoomClass.php");

    //ユーザー
     $user = wp_get_current_user();

      //クラス作成
    $users_data= new UserClass();
    $zoom_data= new ZoomClass();
  
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

    //日本のタイムゾーン
    date_default_timezone_set('Asia/Tokyo');

	//var_dump($users);

    $update_str = "";

    
    $input_disp_table = get_user_meta($user->ID, 'user-status-updata-page-disp',true );//生存(0)or離脱(1)orALL(2)
    $disp_level_table =  get_user_meta($user->ID, 'user-level-updata-page-disp',true );//

    $updata_limit_year = "";
    $updata_limit_month = "";

    $updata_post_interval = -1;

    //生存or離脱
    if(isset($_POST['disp_table']) )
    {
        $input_disp_table = $_POST['disp_table'];

        update_user_meta($user->ID, 'user-status-updata-page-disp', $input_disp_table);

    }
   

    //訓練生or門下生
    if(isset($_POST['level_table']) )
    {
        $disp_level_table = $_POST['level_table'];

        update_user_meta($user->ID, 'user-level-updata-page-disp', $disp_level_table);

      
    }

    //更新年
    if(isset($_POST['updata-year']) )
    {
        $updata_limit_year = $_POST['updata-year'];
    }

     //更新月
    if(isset($_POST['updata-month']) )
    {
        $updata_limit_month = $_POST['updata-month'];
    }

    //更新間隔
    if(isset($_POST['updata-interval']) )
    {
        $updata_post_interval = $_POST['updata-interval'];
    }
   

    //簡易更新（今月）
    if(isset($_POST["limit_now_month"]))
    {
        $updata_limit_year =  date('Y');
        $updata_limit_month = date('n');
    }

    //簡易更新（来月）
    if(isset($_POST["limit_next_month"]))
    {
         $updata_limit_year = date('Y', strtotime('1 month'));;
         $updata_limit_month =date('n', strtotime('1 month'));;
    }

     //簡易更新リセット
    if(isset($_POST["limit_reset"]))
    {
         $updata_limit_year = "";
         $updata_limit_month ="";
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
   
   
   
    //更新
    if(isset($_POST["is_limit_updata"]))
    {
        $users_data->upDataUserUpdataDay(  $_POST["id"]   );
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


function save_check() {


	if (window.confirm('期限を更新しますか？')) { // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else { // 「キャンセル」時の処理

		return false; // 送信を中止

	}

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
        <form action="<?php  $id = 1037; echo get_page_link( $id );?>" method="post">

            <select name="level_table"  class="same-user-select">
            <?php foreach ($users_data->disp_only_level_array_data as $key => $value) {?>
                   <option value="<?php echo $key;?>" <?php if($disp_level_table == $key){ echo "selected";}?>><?php echo $value;?></option>
            <?php } ?>
            </select>

            <?php /*
            <select name="disp_table"  class="same-user-select">
                <?php foreach ($users_data->disp_array_data as $key => $value) {?>
                   <option value="<?php echo $key;?>" <?php if($input_disp_table == $key){ echo "selected";}?>><?php echo $value;?></option>
                <?php } ?>
            </select>
            
           */?>

            <?php $select_year = date('Y'); ?>

            <select name="updata-year"  class="same-user-select">
                    <option value="">次回更新年　</option>
                    <?php for($i=0;$i<3;$i++){ ?>
                    <?php $select_set_year = (int)$select_year + $i;?>
                        <option value="<?php echo $select_set_year; ?>"  <?php if($updata_limit_year == $select_set_year){ echo "selected";}?> ><?php echo $select_set_year;?></option>
                    <?php } ?>
            </select>

            <select name="updata-month"  class="same-user-select">
                <option value="">次回更新月　</option>
                <?php for($i=1;$i<13;$i++){ ?>
                <option value="<?php echo $i; ?>"  <?php if($updata_limit_month == $i){ echo "selected";}?>><?php echo $i;?>月</option>
                <?php } ?>
            </select>


            <select name="updata-interval"  class="same-user-select">
                <option value="-1">更新間隔　</option>
                <option value="0"  <?php if($updata_post_interval == 0){ echo "selected";}?>>年</option>
                <option value="1"  <?php if($updata_post_interval == 1){ echo "selected";}?>>月</option>
            </select>


            <input type="hidden"  name="input_serch" value="<?php echo $serch_str;?>">
            <input type="submit" value="表示変更"   class="same-user-select">
        </form>

        <div style="">
            <div style="display: flex;justify-content: center;margin-top: 10px;">

                <form action="<?php  $id = 1037; echo get_page_link( $id );?>" method="post">

                    <input type="hidden"  name="limit_now_month" value="limit_now_month">
                    <input type="hidden"  name="updata-interval" value="<?php echo $updata_post_interval; ?>">
                    <input type="submit" value="今月更新予定"   class="same-user-select">
                </form>

                <form action="<?php  $id = 1037; echo get_page_link( $id );?>" method="post">

                    <input type="hidden"  name="limit_next_month" value="limit_next_month">
                    <input type="hidden"  name="updata-interval" value="<?php echo $updata_post_interval; ?>">
                    <input type="submit" value="来月更新予定"   class="same-user-select">
                </form>

                <form action="<?php  $id = 1037; echo get_page_link( $id );?>" method="post">

                    <input type="hidden"  name="limit_reset" value="limit_reset">
                    <input type="submit" value="更新予定リセット"   class="same-user-select">
                </form>
            </div>
         </div>

        <?php /*
        <form action="<?php  $id = 1037; echo get_page_link( $id );?>" method="post">

            <div class="" style="margin-top: 10px;">
                <input type="text"  name="input_serch" value="<?php echo $serch_str;?>">
                <input type="submit" value="検索・更新"   class="same-user-select"  style="margin-bottom: 5px;">
            </div>
        </form>

        */?>

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
                   
                    <th>メールアドレス</th>
                    <th>電話番号</th>
                    <th><font size="5">更新</font></th>
                    <th>ﾒﾓ</th>
                    <?php /*<th><font size="5">ログイン停止日</font></th>*/?>
                    <th><font size="5">最終ﾛｸﾞｲﾝ</font></th>
                    <th><font size="5">更新期限</font></th>
                    <th><font size="5">登録日</font></th>
                    
                    <th>更新</th>
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
                        else if( $disp_level_table == 6 && $member_level != UserClass::DOGA  && $member_level != UserClass::SPECIAL_SEMINAR && $member_type != 2){ //株のみ表示
                            $is_disp = true; 
                        }
                        else if( $disp_level_table == 7 && $member_level != UserClass::DOGA  && $member_level != UserClass::SPECIAL_SEMINAR && $member_type != 1){ //FXのみ表示
                            $is_disp = true;
                        }


                        else if( $input_level_table == UserClass::DOGA && $member_level == UserClass::DOGA ){ //動画会員
                            $is_disp = true;
                        }


                        //更新予定
                        if($updata_limit_year != "" && $updata_limit_month != "")
                        {
                            $updata_month_base = $users_data->getUserUpdataDay($row->ID);

                            //echo strtotime($updata_month_base)."<br>";
                            //echo strtotime( $updata_limit_year ."-0" .$updata_limit_month ."-01 00:00:00") ."<br>";

                            $str_updata_limit_month = $updata_limit_month;

                            if($str_updata_limit_month < 10)
                            {
                                $str_updata_limit_month = "0" .$str_updata_limit_month;
                            }
                           
                            $search_day = $updata_limit_year ."-" .$str_updata_limit_month ."-01 00:00:00";

                            $check_year=date('Y', strtotime($updata_month_base));
                            $search_year=date('Y', strtotime($search_day));
                            $check_month=date('m', strtotime($updata_month_base));
                            $search_month=date('m', strtotime( $search_day));

                            

                            if($check_month != $search_month || $check_year != $search_year )
                            {
                                $is_disp = false;
                            }
                        }

                        if(get_the_author_meta('user_is_updata',$row->ID) != ""){

                            $is_disp = false;
                        }

                        //更新月年
                        $updata_interval = 0;

                        if(get_the_author_meta('user_updata_interval',$row->ID) != ""){
                            $updata_interval = get_the_author_meta('user_updata_interval',$row->ID);
                        }
                        
                        //更新間隔
                        if($updata_post_interval >= 0)
                        {
                            if($updata_post_interval != $updata_interval)
                            {
                                 $is_disp = false;
                            }
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

                            $updata_month_base = $users_data->getUserUpdataDay($row->ID);
                            $updata_month =  date('Y年m月d日 H時i分', strtotime($updata_month_base));

                            //ログイン停止日
                            $lmit_day = new DateTime($updata_month_base);
                            $lmit_day->modify('+1 day');

                            //最終ﾛｸﾞｲﾝ
                            $last_login = get_the_author_meta('last_login',$row->ID);

                           

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

                    
                  
                    
                     <form action="<?php  $id = 806; echo get_page_link( $id );?>" method="post" id="mail_send_user_<?php echo  $row->ID;?>" name="mail_send_user_<?php echo  $row->ID;?>" target="_blank">
                        <input type="hidden" name="send_user_id" value="<?php echo  $row->ID;?>">
                    </form>


                      <td>
                            <a href="javaScript:submitCheckMailFnc(<?php echo  $row->ID;?>)" >
                                 <?php echo $row->user_email;?>
                            </a>
                      </td>

                    <td><?php echo get_the_author_meta('billing_phone',$row->ID);?></td>
                   
                    <td>
                        <?php
                            if($updata_interval == 0){
                                echo "年";
                            }
                            else{
                                echo "月";
                            }
                        ?>
                    </td>

                    <td>
                        <?php
                            if(get_the_author_meta('user_memo',$row->ID) != ""){
                                echo "有";
                            }
                        ?>
                    </td>
                    <?php /*
                    <td style="font-size:18px;"<?php if(strtotime($lmit_day->format("Y-m-d H:i:s")) <= strtotime(date("Y-m-d H:i:s"))){echo 'bgcolor=salmon';}?>> <?php echo $lmit_day->format("Y年m月d日 H時i分");?></td>
                    */?>

                     <td style="font-size:18px;"><?php echo $the_login_date;?></td>
                    <td style="font-size:18px;"><?php echo $updata_month;?></td>
                    <td style="font-size:18px;"><?php echo $the_registerd_date;?></td>
                   
                    <td>
                        <?php if(get_the_author_meta('user_is_updata',$row->ID) == ""){?>
                            <form action="<?php  $id = 1037; echo get_page_link( $id );?>" method="post" onSubmit="return save_check()">
                                <input type="hidden" name="id" value="<?php echo $row->ID;?>">
                                 <input type="hidden" name="updata-year" value="<?php echo $updata_limit_year;?>">
                                 <input type="hidden" name="updata-month" value="<?php echo $updata_limit_month;?>">
                                 <input type="hidden"  name="updata-interval" value="<?php echo $updata_post_interval; ?>">
                                <input type="hidden" name="is_limit_updata" value="is_limit_updata">
                                <input type="submit" value="更新">
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
                    else if( $disp_level_table == 6 && $member_level != UserClass::DOGA  && $member_level != UserClass::SPECIAL_SEMINAR && $member_type != 2){ //株のみ表示
                        $is_disp = true; 
                    }
                    else if( $disp_level_table == 7 && $member_level != UserClass::DOGA  && $member_level != UserClass::SPECIAL_SEMINAR && $member_type != 1){ //FXのみ表示
                        $is_disp = true;
                    }


                    else if( $input_level_table == UserClass::DOGA && $member_level == UserClass::DOGA ){ //動画会員
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
                   
                    <th colspan="2">詳細</th>
                </tr>
                <tr class="disp_close<?php echo $row->ID;?>"> 
                    
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