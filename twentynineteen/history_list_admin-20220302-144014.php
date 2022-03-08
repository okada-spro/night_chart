<?php

if(current_user_can('administrator'))
{

    require_once("historyClass.php");
     require_once("userClass.php");

    $user = wp_get_current_user();

    //クラス作成
    $input_data= new HistoryClass();

    //ユーザークラス作成
    $users_data= new UserClass();

    //初期化
    $input_data->init();


    //ユーザーの全データを取得
	$users = get_users( array('orderby'=>'ID','order'=>'ASC') );

    //年選択
    if(!isset($_GET["years"])){

?>

    <?php

        // データ取得クエリ実行(全データ取得)
        $input_data->getTradeHistoryRowAll();

        if($input_data->history_row){

            $yeas_array = $input_data->getIsYearsArray();
        }
    ?>



    <?php if(!$input_data->history_row){?>
        <p>データがありません</p>
    <?php }else{?>

         <?php foreach ($yeas_array as $row) {?>
            <p>
               <a href="<?php $id = 43; echo get_page_link( $id )."?years=" .$row ;?>"  class="index_button"><?php echo $row;?></a>
            </p>

         <?php } ?>

    <?php }?>









<?php
    }elseif(isset($_GET["years"])){
?>


         <?php

            // データ取得クエリ実行(全データ取得)
            $input_data->getTradeHistoryRowYear();

            if($input_data->history_row){

               $trade_array = $input_data->setUserTradeData($_GET["years"]);
            }

            $input_disp_table =  get_user_meta($user->ID, 'grades-most-page-disp',true );
            $input_arrive_table =  get_user_meta($user->ID, 'grades-status-page-disp',true );
            $disp_level_table =  get_user_meta($user->ID, 'grades-level-page-disp',true );
            $disp_submission_table =  get_user_meta($user->ID, 'trade-submission-disp',true );;


             //直近表示
            if(isset($_POST['disp_table']) )
            {
                $input_disp_table = $_POST['disp_table'];

                update_user_meta($user->ID, 'grades-most-page-disp', $input_disp_table);
            }


             //生存or離脱
            if(isset($_POST['arrive_table']) )
            {
                $input_arrive_table = $_POST['arrive_table'];

                update_user_meta($user->ID, 'grades-status-page-disp', $input_arrive_table);
            }


            //訓練生or門下生
            if(isset($_POST['level_table']) )
            {
                $disp_level_table = $_POST['level_table'];

                update_user_meta($user->ID, 'grades-level-page-disp', $disp_level_table);
            }


             //提出者、未提出
            if(isset($_POST['is_trade_table']) )
            {
                $disp_submission_table = $_POST['is_trade_table'];

                update_user_meta($user->ID, 'trade-submission-disp', $disp_submission_table);
            }



            //表示配列から実際の表示用の変数に入れ返る
            if($disp_level_table <= 2){ //訓練生
                $input_level_table = UserClass::KUNRENSEI;
            }
            else if($disp_level_table == 3){ //門下生
                $input_level_table = UserClass::MONKASEI;
            }
            else if($disp_level_table == 4){ //動画
                $input_level_table = UserClass::DOGA;
            }
            else if($disp_level_table == 5){ //動画会員を非表示
                $input_level_table = $disp_level_table;
            }
            else{
                $input_level_table = $disp_level_table;
            }


            //echo $input_level_table;

            $start_calendar = 1;
            $end_calendar = 12;

            //今月
            $now_year = date('Y');

            //今月
            $now_month = date('m');

            if( $_GET["years"] == $now_year )
            {

                if($input_disp_table == 0)
                {
                    //3ヶ月
                    $end_calendar = $now_month;

                    $start_calendar = ($end_calendar - 3);


                    if($start_calendar < 0)
                    {
                        $end_calendar += $start_calendar *-1;

                        $start_calendar = 1;
                    }
                }
                else if($input_disp_table == 1)
                {
                    //6ヶ月
                    $end_calendar = $now_month;

                    $start_calendar = ($end_calendar - 6);


                    if($start_calendar < 0)
                    {
                        $end_calendar += $start_calendar *-1;

                        $start_calendar = 1;
                    }
                }
            }

            //検索
            $serch_str = "";

            if( isset( $_POST["input_serch"]) )
            {
                //優先はPOST
                $serch_str =  $_POST["input_serch"];
            }

           // var_dump($trade_array);

        ?>

<script>
$(document).ready(function() {

    $('table.histroy-list-table')
        .tablesorter({})
        .tablesorterPager({
            container: $(".pager"),
            size: 200,
    });
});
</script>


<div class="history_list_admin_title_area">
    <div class="history_list_admin_title">
        <div class="history_list_admin_title_str"><?php echo  $_GET["years"];?>年度　成績一覧</div><div class="history_list_admin_title_year_str">(<a href="<?php $id = 43; echo get_page_link( $id );?>">別の年を選択</a>)</div>
    </div>
</div>

<div class="page_div_box">
    <p>
    <div class="histroy-list-table_sarch" style="">
        <form action="<?php  $id = 43; echo get_page_link( $id )."?years=" .$_GET["years"]; ?>" method="post">
            <select name="level_table"  class="same-user-select">
                <?php foreach ($users_data->disp_only_level_array_data as $key => $value) {?>
                   <option value="<?php echo $key;?>" <?php if($disp_level_table == $key){ echo "selected";}?>><?php echo $value;?></option>
                <?php } ?>
            </select>

            <select name="arrive_table"  class="same-user-select">
                <?php foreach ($users_data->disp_array_data as $key => $value) {?>
                   <option value="<?php echo $key;?>" <?php if($input_arrive_table == $key){ echo "selected";}?>><?php echo $value;?></option>
                <?php } ?>
            </select>

            <?php if( $_GET["years"] == $now_year ){ //今年以外は全部表示 ?>
                <select name="disp_table"  class="same-user-select">
                    <?php foreach ($input_data->disp_array_data as $key => $value) {?>
                        <option value="<?php echo $key;?>" <?php if($input_disp_table == $key){ echo "selected";}?>>　<?php echo $_GET["years"]."年 " .$value;?></option>
                    <?php } ?>
                </select>
             <?php }else{  ?>
                 <input type="hidden"  name="disp_table" value="<?php echo 2;?>"></input>
             <?php }  ?>


             <select name="is_trade_table"  class="same-user-select">
                <?php foreach ($input_data->is_trade_array_data as $key => $value) {?>
                   <option value="<?php echo $key;?>" <?php if($disp_submission_table == $key){ echo "selected";}?>><?php echo $value;?></option>
                <?php } ?>
            </select>

            <input type="submit" value="表示変更"   class="same-user-select">
        </form>

        <form action="<?php  $id = 43; echo get_page_link( $id )."?years=" .$_GET["years"]; ;?>" method="post">
            <input type="text"  name="input_serch" value="<?php echo $serch_str;?>"  style="height:50px;"></input>
            <input type="submit" value="ID・ユーザー検索"   class="same-user-select">
        </form>
    </div>
    </p>

    <table class="histroy-list-table">
        <thead>
        <tr>
            <th class="fixed_th_1" width="70">履歴</th>
            <th class="fixed_th_2" width="70">ID</th>
            <th class="fixed_th_3">ユーザー名</th>
            <th width="150" class="fixed_th_4">会員</th>
            <?php for($i=$start_calendar;$i<=$end_calendar;$i++) {?>
                <th style="min-width:100px"><?php echo $i;?>月</th>
            <?php } ?>
            <th width="70">詳細</th>
        </tr>
        </thead>

        <?php //var_dump($trade_array); ?>
        <?php foreach ($trade_array  as $key => $value) {?>

            <?php $user_info = get_userdata( $key ); //var_dump($user_info); echo "<br/><br/>";?>


            <?php $total_profit = 0;$total_interest = 0; ?>

            <?php
                $member_level = get_the_author_meta('member_level',$key);
                $member_type = get_the_author_meta('member_type',$key);
            ?>
            <?php  if($member_level == "" || $member_level == NULL){$member_level = 0;}?>

            <?php  $Withdrawal =get_the_author_meta('member_withdrawal',$key);?>
            <?php  if($Withdrawal == "" || $Withdrawal == NULL){$Withdrawal = 0;}?>


            <?php $disp_row = false;

                  //全て表示
                 if(  $disp_level_table == 99  && $input_level_table == UserClass::ALL_DISP_CONST){
                     $disp_row = true;
                 }
                 else if( $input_level_table == $member_level && $input_level_table == UserClass::MONKASEI  ){ //門下生の時
                     $disp_row = true;
                 }
                 else if( $disp_level_table == 0 && $input_level_table == UserClass::KUNRENSEI && $member_level == UserClass::KUNRENSEI ){ //訓練生の時の全表示
                     $disp_row = true;
                 }
                 else if( $disp_level_table == 1 && $input_level_table == UserClass::KUNRENSEI && $member_level == UserClass::KUNRENSEI  && $member_type == 1){ //訓練生の時の株表示
                     $disp_row = true;
                 }
                 else if( $disp_level_table == 2 && $input_level_table == UserClass::KUNRENSEI && $member_level == UserClass::KUNRENSEI  && $member_type == 2){ //訓練生の時のFX表示
                     $disp_row = true;
                 }
                 else if( $disp_level_table == 5 && $member_level != UserClass::DOGA ){ //動画会員を非表示
                        $disp_row = true; //動画会員を非表示
                 }
                 else if( $disp_level_table == 6 && $member_level != UserClass::DOGA && $member_type != 2){ //株のみ表示
                        $disp_row = true;
                 }
                 else if( $disp_level_table == 7 && $member_level != UserClass::DOGA && $member_type != 1){ //FXのみ表示
                        $disp_row = true;
                 }

                 else if( $input_level_table == UserClass::DOGA && $member_level == UserClass::DOGA ){ //動画会員
                        $disp_row = true;
                 }
                 else{
                      //echo $key;
                 }

                 //提出未提出
                 if($disp_submission_table == 0 && !$value["disp"]) //提出者表示（未提出非表示)
                 {
                    if($disp_row)
                    {
                        $disp_row = false;
                    }
                 }
                 else  if($disp_submission_table == 1 && $value["disp"]) //未提出者表示（提出非表示)
                 {
                    if($disp_row)
                    {
                        $disp_row = false;
                    }
                 }
                 else  if($disp_submission_table == 2 ) //全表示
                 {
                   
                 }

            ?>


            <?php


                //最後にユーザー名を検索
                if($serch_str !="")
                {
                    if(( strpos( $key , $serch_str) !== false ) ||
                       ( strpos( $user_info->first_name , $serch_str) !== false ) ||
                       ( strpos( $user_info->last_name , $serch_str) !== false ) ||
                       ( strpos( $user_info->user_login , $serch_str) !== false ) )
                    {
                        //検索があった
                        $disp_row = true;
                    }
                    else{
                        $disp_row = false;
                    }
                }
            ?>


            <?php if($disp_row){?>

                <tr>
                    <td class="fixed_th_1">
                        <form action="<?php  $id = 37; echo get_page_link( $id );?>" method="post" target="_blank">
                            <input type="hidden" name="years" value="<?php echo $_GET["years"];?>">
                            <input type="hidden" name="id" value="<?php echo $key;?>">
                            <input type="hidden" name="is_list" value="checklist">

                            <input type="submit" value="確認">
                        </form>
                    </td>

                    <td class="fixed_th_2"><?php echo $key;?> </td>

                    <td class="fixed_th_3 mode-pc"><p><?php echo $user_info->last_name ;?>　<?php echo $user_info->first_name;?>(<?php echo $user_info->user_login;?>)</p></td>
                    <td class="fixed_th_3 mode-sp"><p><?php echo $user_info->last_name ;?>　<?php echo $user_info->first_name;?></p></td>

                    <td class="fixed_th_4">
                        <?php if( $member_level == 0 && $member_type > 0){ //訓練生 ?>
                            <?php echo $users_data->checkMemberTypeStr($member_type);?>
                        <?php } ?>
                        <div class="mode-pc">
                            <?php echo $users_data->checkLevelStr($member_level);?>(<?php echo $users_data->checkWithdrawalStr($Withdrawal);?>)
                        </div>
                        <div class="mode-tab">
                        <?php echo $users_data->checkLevelStr($member_level);?><br>
                        (<?php echo $users_data->checkWithdrawalStr($Withdrawal);?>)
                        </div>
                    </td>

                    <?php  for($i=$start_calendar;$i<=$end_calendar;$i++) {?>
                        <?php if(array_key_exists($i,$value["days"])){?>
                        <?php
                            $balance = $value["days"][$i]["balance"];

                            if($balance != 0)
                            {
                                $balance  =  floor($balance/ 1000) / 10;

                            }

                            $interest = $value["days"][$i]["interest"];
                            $total_profit += $balance;
                            $total_interest += $interest;
                        ?>

                        <td>
                            <p>
                                <?php echo $balance;?>
                                <?php if($input_disp_table > 0){ //6ヶ月以上は２行 ?>
                                <br>
                                <?php } ?>
                                (
                                    <?php if($interest < 0){?>
                                        <font color="red"><?php echo $interest;?> </font>％
                                    <?php }else{ ?>
                                        <?php echo $interest;?> ％
                                    <?php } ?>
                                )
                            </p>
                        </td>
                        <?php }else{ ?>
                            <td>0</td>
                        <?php } ?>
                    <?php } ?>
                     <td>
                        <form action="<?php  $id = 11; echo get_page_link( $id );?>" method="post" target="_blank">
                            <input type="hidden" name="id" value="<?php echo $key;?>">
                            <input type="hidden" name="is_details" value="details">
                            <input type="submit" value="詳細">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    </table>

    <p>
     <div class="pager">
            <button type='button' class='first'>&lt;&lt;</button>
            <button type='button' class='prev'>&lt;</button>

            <span class="pagedisplay" ></span>


            <button type='button' class='next'>&gt;</button>
            <button type='button' class='last'>&gt;&gt;</button>

            <select class="pagesize">
                <option value="50">50</option>
                <option selected="selected" value="100">100</option>
                <option value="200">200</option>
            </select>
     </div>
     </p>
</div>
<?php
     }
 }



?>
