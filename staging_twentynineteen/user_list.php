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


        if(isset($_POST['input_type'])){
            update_user_meta($_POST['id'], 'member_type', $_POST['input_type']);

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



    window.addEventListener('scroll', ()=> {
        var Yposi = window.pageYOffset;
        console.log(Yposi);
        if(Yposi >= 650){
            $(".tablesorter-headerRow").css("position","sticky");
            $(".tablesorter-headerRow").css("top",(Yposi - 650) + "px");
            $(".fixed_th_1.tablesorter-header").css("top",(Yposi - 650) + "px");
            $(".fixed_th_1.tablesorter-header").css("z-index","1");
            $(".fixed_th_2.tablesorter-header").css("top",(Yposi - 650) + "px");
            $(".fixed_th_2.tablesorter-header").css("z-index","1");
            $(".fixed_th_3.tablesorter-header").css("top",(Yposi - 650) + "px");
            $(".fixed_th_3.tablesorter-header").css("z-index","1");
        }else{
            $(".tablesorter-headerRow").css("position","unset");
            $(".fixed_th_1.tablesorter-header").css("top","0");
            $(".fixed_th_2.tablesorter-header").css("top","0");
            $(".fixed_th_3.tablesorter-header").css("top","0");
        }
    });
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
        <div class="user-table-container">
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
                    <th>最終ﾛｸﾞｲﾝ</th>
                    <th>レポート</th>
                    <th>詳細</th>
                </tr>
                </thead>
                <tbody>
        <?php 
            foreach ($disp_user_data as $row)
            {
        
                $Withdrawal =get_the_author_meta('member_withdrawal',$row->ID);

                if($Withdrawal == "" || $Withdrawal == NULL)
                {
                    $Withdrawal = 0;
                }

                if(($input_disp_table == 2) || ($input_disp_table == $Withdrawal) )
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
        ?>
                <tr>

                      <form action="<?php  $id = 37; echo get_page_link( $id );?>" method="post" id="trade_page_form_<?php echo  $row->ID;?>" name="trade_page_form_<?php echo  $row->ID;?>" target="_blank">
                            <input type="hidden" name="years" value="<?php echo $now_year;?>">
                            <input type="hidden" name="id" value="<?php echo  $row->ID;?>">
                            <input type="hidden" name="is_list" value="checklist">
                     </form>
                        
                    
                        <td class="fixed_th_1">
                            <a href="javaScript:submitCheckFnc(<?php echo  $row->ID;?>)" >
                                <?php echo $row->ID;?>
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
                       <?php echo $zoom_plans_num;?>回
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
                    <td style="font-size:18px;"><?php echo $the_login_date;?></td>

                   

                    <td>
                        <?php if($member_level != UserClass::DOGA){ //動画会員はなし?>
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





    <?php }else{?>
        <p>
            <font color="red">表示するデータがありません</font>
        </p>

    <?php } ?>
</div>



<?php }elseif(isset($_POST['is_details']) ){ ?>

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
                
                <select name="input_level" class="level-width-list">
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
            <td>生存・脱落</td>
            <?php $level = 0;$withdrawal = 0;?>
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
                 
                
                  
                  ?>
            <td>
                 <select name="input_ban" class="same-width-list">
                    <option value="0" <?php if($ban == false){ echo "selected";}?>>通常</option>
                    <option value="1"  <?php if($ban == true){ echo "selected";}?>>停止</option>
                 </select>
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

        <tr>
            <td colspan="2">
                <input type="hidden" name="id" value="<?php echo $_POST['id'];?>">
                <input type="hidden" name="is_save" value="save">
                <input type="hidden" name="is_details" value="details">
                <p><input  class="index_history_button"type="submit" value="更新"></p>
            </td>
        </tr>
        </form>
     </table>

     
     <p>
        
            <button type="button" class="user-tabclose-button"  onClick="javascript:window.close();">閉じる</button>
        
	</p>
     <p>
	    <a class="index_button" href="<?php $id = 11; echo get_page_link( $id );?>"   >一覧に戻る</a>
	</p>
<?php } ?>

