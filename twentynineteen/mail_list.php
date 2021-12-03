<?php  


require_once("mailClass.php");

$mail_data= new MailClass();


//メールIDを取得
$send_mail_id_list = $mail_data->getMailList();




?>
 

<?php if( !isset($_POST["send_post_mail_id"] ) ){ ?>


    <?php if($send_mail_id_list ){ ?>

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

<div class="page_div_box">
        <div class="user-table-container">
            <table class="lecture-table" id="userTable" style="max-width: 1200px;">
               <thead>
                <tr>
                    <th>詳細</th>
                    <th>日付</th>
                    <th>送信先</th>
                    <th>送信者</th>
                    <th>タイトル</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                         foreach ($send_mail_id_list as $row)
                         {


                            $send_day = get_field( 'sendmail_list_send_day',$row);//日付
                            $send_user = get_field( 'sendmail_list_user_name',$row);//送信先
                            $send_owner = get_field( 'sendmail_list_owner_name',$row);//送信先
                            $send_title = get_field( 'sendmail_list_title',$row);//送信先
                    
                    ?>

                        <tr>
                            <form action="<?php  $id = 810; echo get_page_link( $id );?>" method="post" id="mail_send_user_<?php echo  $row;?>" name="mail_send_user_<?php echo  $row;?>" target="_blank">
                                <input type="hidden" name="send_post_mail_id" value="<?php echo  $row;?>">
                            </form>
                        
                    
                            <td>
                                <a href="javaScript:submitCheckFnc(<?php echo  $row;?>)" >
                                    詳細
                                </a>
                            </td>
                            <td><?php echo $send_day;?></td>
                            <td><?php echo $send_user;?></td>
                            <td><?php echo $send_owner;?></td>
                            <td><?php echo $send_title;?></td>
                        </tr>


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
 </div>
    <?php }else{ ?>

    <div class="mail-send-main-area">

	    <div class="mail-send-main-contents">

            <div class="mail-check-post-label">
			送信履歴はありません
		    </div>
        </div>
    </div>

    <?php } ?>
<?php }else if( isset($_POST["send_post_mail_id"] ) ){ ?>


<?php

    $send_day = get_field( 'sendmail_list_send_day',$_POST["send_post_mail_id"]);//日付
    $send_user = get_field( 'sendmail_list_user_name',$_POST["send_post_mail_id"]);//送信先
    $send_user_mail = get_field( 'sendmail_list_user_mail',$_POST["send_post_mail_id"]);//送信先メール
    $send_owner = get_field( 'sendmail_list_owner_name',$_POST["send_post_mail_id"]);//送信元
    $send_owner_mail = get_field( 'sendmail_list_owner_mail',$_POST["send_post_mail_id"]);//送信元メール
    $send_title = get_field( 'sendmail_list_title',$_POST["send_post_mail_id"]);//送信タイトル

?>



<div class="mail-send-main-area">

	<div class="mail-send-main-contents">


		    <div class="mail-send-check-area">

				<div class="mail-send-check-contents">

					<div class="mail-send-check-label">送信日</div>
					<div class="mail-send-check-str">
						<?php echo $send_day; ?>
					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>

			<div class="mail-send-check-area">

				<div class="mail-send-check-contents">

					<div class="mail-send-check-label">送信元</div>
					<div class="mail-send-check-str">
						<?php echo $send_owner; ?>
					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>


			<div class="mail-send-check-area">

				<div class="mail-send-check-contents">

					<div class="mail-send-check-label">送信元メール</div>
					<div class="mail-send-check-str">
						<?php echo $send_owner_mail; ?>
					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>


			<div class="mail-send-check-area">

				<div class="mail-send-check-contents">

					<div class="mail-send-check-label">送信先</div>
					<div class="mail-send-check-str">
						<?php echo $send_user; ?>< <?php echo $send_user_mail; ?> >
					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>


			<div class="mail-send-check-area">

				<div class="mail-send-check-contents">

					<div class="mail-send-check-label">件　名</div>
					<div class="mail-send-check-str"> <?php echo $send_title; ?>
					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>


            内容
			<div class="mail-send-check-textarea">

				<div class="mail-send-check-textarea-contents">

					<div class="mail-send-textarea-str"> <?php echo nl2br(get_field( 'sendmail_list_textarea',$_POST["send_post_mail_id"])); ?></div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>


	</div><?php //mail-send-main-contents?>

    <button type="button" class="user-tabclose-button"  onClick="javascript:window.close();">閉じる</button>

</div><?php //mail-send-main-area?>

 <?php } ?>
