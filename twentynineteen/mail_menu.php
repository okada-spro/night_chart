<?php 


    require_once("userClass.php");

   //ユーザー
     $user = wp_get_current_user();

      //クラス作成
    $users_data= new UserClass();
  
	//ユーザーの全データを取得
	$users = get_users( array('orderby'=>'ID','order'=>'ASC') ); 


?>



<?php if( !isset( $_GET["page_type"] )){ //メニュー ?>

	<div class="mail-menu-area">
		<div class="mail-menu-contents">

			<div class="mail-menu-button-area">
				<a class="mail-menu-button" href="<?php $id = 808; echo get_page_link( $id );?>?page_type=list">メール作成</a>
			</div>

			<div class="mail-menu-button-area">
				<a class="mail-menu-button" href="<?php $id = 810; echo get_page_link( $id );?>">過去メール一覧</a>
			</div>

            <div class="mail-menu-button-area">
				<a class="mail-menu-button" href="<?php $id =  808; echo get_page_link( $id );?>?page_type=setting">メール設定初期値</a>
			</div>

		</div>
	</div><?php //mail-menu-area ?>


<?php }else if( $_GET["page_type"] ==  "list"){  ?>


<?php

    
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

	document.getElementById("mail_send_user_" + id).submit();
    
}

// -->
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
    <div>
        <form action="<?php  $id = 808; echo get_page_link( $id );?>?page_type=list" method="post">
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

        <form action="<?php  $id = 808; echo get_page_link( $id );?>?page_type=list" method="post">
            <input type="text"  name="input_serch" value="<?php echo $serch_str;?>"  style="height:50px;"></input>
            <input type="submit" value="検索・更新"   class="same-user-select">
        </form>
    </div>
    </p>

<?php 
    if($disp_user_data)
    {
?>
        <div class="user-table-container mode-pc">
            <table class="lecture-table" id="userTable" style="max-width: 1200px;">
               <thead>
                <tr>
                    <th class="fixed_th_1"></th>
                    <th class="fixed_th_2">ユーザー名</th>
                    <th>名前</th>
                    <th>会員</th>
                    <th>生存</th>
                   <th>メールアドレス</th>
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

                    if($member_level == "" || $member_level == NULL)
                    {
                        $member_level = 0;
                    }

                    $is_disp = false;

                    //表示
                    $is_disp = $users_data->checkDispStudents( $disp_level_table , $input_level_table , $member_level , $member_type);


                    if($is_disp )
                    {
        ?>
                <tr>

                      <form action="<?php  $id = 806; echo get_page_link( $id );?>" method="post" id="mail_send_user_<?php echo  $row->ID;?>" name="mail_send_user_<?php echo  $row->ID;?>" target="_blank">
                            <input type="hidden" name="send_user_id" value="<?php echo  $row->ID;?>">
                     </form>
                        
                    
                        <td class="fixed_th_1">
                            <a href="javaScript:submitCheckFnc(<?php echo  $row->ID;?>)" >
                                作成
                            </a>
                        </td>
                    

                    <td class="fixed_th_2"><?php echo $row->user_login;?></td>
                    <td>
                        <?php echo get_the_author_meta('last_name',$row->ID);?>　<?php echo get_the_author_meta('first_name',$row->ID);?>
                    </td>
                    <td>
                        <?php if( $member_level == 0 && $member_type > 0){ //訓練生 ?>
                            <?php echo $users_data->checkMemberTypeStr($member_type);?><br>
                        <?php } ?>

                        <?php echo $users_data->checkLevelStr($member_level);?>

                    </td>

                    <td><?php echo $users_data->checkWithdrawalStr($Withdrawal);?></td>

                   

                   
                    <td><?php echo $row->user_email;?></td>
                   

                </tr>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            </tbody>
            </table>
        </div>

        <div class="user-table-container mode-sp">
            <table class="lecture-table" id="userTable" style="max-width: 1200px;">
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

                    if($member_level == "" || $member_level == NULL)
                    {
                        $member_level = 0;
                    }

                    $is_disp = false;

                    //表示
                    $is_disp = $users_data->checkDispStudents( $disp_level_table , $input_level_table , $member_level , $member_type);


                    if($is_disp )
                    {
        ?>
        <tr class="disp_open" data-id=<?php echo $row->ID;?>>
            <th>▽</th>
            <th>ユーザー名</th>
        </tr>
        <tr class="disp_open" data-id=<?php echo $row->ID;?>>
            <form action="<?php  $id = 806; echo get_page_link( $id );?>" method="post" id="mail_send_user_<?php echo  $row->ID;?>" name="mail_send_user_<?php echo  $row->ID;?>" target="_blank">
                <input type="hidden" name="send_user_id" value="<?php echo  $row->ID;?>">
            </form>
            <td>
                <a href="javaScript:submitCheckFnc(<?php echo  $row->ID;?>)" >
                    作成
                </a>
            </td>
            <td><?php echo $row->user_login;?></td>
        </tr>
        <tr class="disp_close<?php echo $row->ID;?>">
            <th>会員</th>
            <th>名前</th>
        </tr>
        <tr class="disp_close<?php echo $row->ID;?>">
            <td>
                <?php if( $member_level == 0 && $member_type > 0){ //訓練生 ?>
                    <?php echo $users_data->checkMemberTypeStr($member_type);?><br>
                <?php } ?>
                <?php echo $users_data->checkLevelStr($member_level);?>
            </td>
            <td>
                <?php echo get_the_author_meta('last_name',$row->ID);?>　<?php echo get_the_author_meta('first_name',$row->ID);?>
            </td>
        </tr>
        <tr class="disp_close<?php echo $row->ID;?>">
            <th>生存</th>
            <th>メールアドレス</th>
        </tr>
        <tr class="disp_close<?php echo $row->ID;?>">
            <td><?php echo $users_data->checkWithdrawalStr($Withdrawal);?></td>
            <td style="font-size:13px"><?php echo $row->user_email;?></td>
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


<?php }else if( $_GET["page_type"] ==  "setting"){ //設定 ?>


<?php

 $user = wp_get_current_user();


 //更新
 if( isset($_POST["up_data"] ) )
 {
    update_user_meta($user->ID, 'mail_send_owner_name', $_POST['owner_name']);
    update_user_meta($user->ID, 'mail_send_owner_add', $_POST['owner_mail']);
 }


$send_owner_name = get_user_meta($user->ID, 'mail_send_owner_name',true );
$send_owner_mail = get_user_meta($user->ID, 'mail_send_owner_add',true );

?>



<div class="mail-send-main-area">

	<div class="mail-send-main-contents">


		<form action="<?php $id =  808; echo get_page_link( $id );?>?page_type=setting" method="post" id="mail_setting_form" name="mail_setting_form" >
			<input type="hidden" name="up_data" value="1">


			<div class="mail-send-input-area">

				<div class="mail-send-input-contents">

					<div class="mail-send-input-label">送　信　元</div>
					<div class="mail-send-input-str">
						<input type="text"  name="owner_name" value="<?php echo $send_owner_name;?>"></input>
					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>


			<div class="mail-send-input-area">

				<div class="mail-send-input-contents">

					<div class="mail-send-input-label">送信メール</div>
					<div class="mail-send-input-str">
						<input type="text"  name="owner_mail" value="<?php echo $send_owner_mail;?>"></input>
					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>



			<div class="mail-send-submit-area">
				<div class="mail-send-submit-contents">

				<input type="submit" value="変更"   class="mail-send-submit">

				</div><?php //mail-send-submit-contents?>
			</div><?php //mail-send-submit-area?>

		</form>


        <div class="mail-send-submit-return-area">

			<form action="<?php  $id = 808; echo get_page_link( $id );?>" method="post" id="mail_send_form" name="mail_send_form" >
				<input type="submit" value="メニューに戻る"   class="mail-return-submit" style="background-color: cornflowerblue;margin-top: 3%;">
			</form>

		</div>


	</div><?php //mail-send-main-contents?>

</div><?php //mail-send-main-area?>




<?php }  ?>