<?php 


 require_once("mailClass.php");

  $mail_data= new MailClass();

$last_name =  "";
$first_name= "";
$user_email =  "";
$user_email_title = "";
$user_email_str = "";





//メール送信者
$send_owner_name = "";
$send_owner_mail = "";

$mail_array = array();

if( isset( $_POST["mail_all"] ) )
{
	

	//ユーザー
	$user = wp_get_current_user();

	foreach ($_POST["mail_ids"] as $key=>$value)
	{
		$mail_array[$value] =array();

		$mail_array[$value]["姓"] = get_the_author_meta('last_name',$value);
		$mail_array[$value]["名"] = get_the_author_meta('first_name',$value);
		$mail_array[$value]["mail"] = get_the_author_meta('user_email',$value);
	}
	

	$send_owner_name = get_user_meta($user->ID, 'mail_send_owner_name',true );
	$send_owner_mail = get_user_meta($user->ID, 'mail_send_owner_add',true );


}

//var_dump($mail_array);


?> 




<?php if( !isset( $_POST["mail_step"] )){ //メニュー ?>

 

<?php


	if(isset(  $_POST["user_email_title"] ))
	{
		$user_email_title =  $_POST["user_email_title"];
	}

	if(isset(  $_POST["user_email_text"] ))
	{
		$user_email_str =  $_POST["user_email_text"];
	}

	if(isset(  $_POST["owner_name"] ))
	{
		$send_owner_name =  $_POST["owner_name"];
	}

	if(isset(  $_POST["owner_mail"] ))
	{
		$send_owner_mail =  $_POST["owner_mail"];
	}

?>




<div class="mail-send-main-area">

	<div class="mail-send-main-contents">


		<form action="<?php  $id = 952; echo get_page_link( $id );?>" method="post" id="mail_send_form" name="mail_send_form" >
			<input type="hidden" name="mail_step" value="check">

			<div class="mail-send-input-area">

				<div class="mail-send-input-contents">

					<div class="mail-send-input-label">送　信　元</div>
					<div class="mail-send-input-str">
						<input type="text"  name="owner_name" value="<?php echo $send_owner_name;?>" ></input>
					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>


			<div class="mail-send-input-area">

				<div class="mail-send-input-contents">

					<div class="mail-send-input-label">送信メール</div>
					<div class="mail-send-input-str">
						<input type="text"  name="owner_mail" value="<?php echo $send_owner_mail;?>" ></input>
					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>


			<div class="mail-send-input-area">

				<div class="mail-send-input-contents">

					<div class="mail-send-input-label"> 　</div>
					<div class="mail-send-input-str">
						
					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>



			<div class="mail-send-input-area">

				<div class="mail-send-input-contents">

					<div class="mail-send-input-label">送　信　先(BCC)</div>
					<div class="mail-send-input-str">
						
						<?php 
							foreach ($mail_array as $key=>$value)
							{
								echo $value["姓"] . " ". $value["名"] . ",";
							}
						
						?>

					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>


			<div class="mail-send-input-area">

				<div class="mail-send-input-contents">

					<div class="mail-send-input-label">件　　　名</div>
					<div class="mail-send-input-str">
						<input type="text"  name="user_email_title" value="<?php echo $user_email_title;?>" ></input>
					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>


			<div class="mail-send-input-textarea">

				<div class="mail-send-input-textarea-contents">

					<div class="mail-send-textarea-label">内容</div>
					<div class="mail-send-textarea-str">
						<textarea name="user_email_text" rows="15" cols="60"><?php echo $user_email_str;?></textarea>
					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>



			<div class="mail-send-submit-area">
				<div class="mail-send-submit-contents">

				<?php foreach ($mail_array as $key=>$value){?>

					<input type="hidden" name="mail_ids[]" value="<?php echo $key;?>" >

				<?php } ?>

				<input type="hidden" name="mail_all" value="mail_all" >
				<input type="submit" value="確認"   class="mail-send-submit">

				</div><?php //mail-send-submit-contents?>
			</div><?php //mail-send-submit-area?>

		</form>


	</div><?php //mail-send-main-contents?>

</div><?php //mail-send-main-area?>







<?php }else if( ( $_POST["mail_step"] == "check")){ //メール確認 ?>


<?php



?>




<div class="mail-send-main-area">

	<div class="mail-send-main-contents">


		<form action="<?php  $id = 952; echo get_page_link( $id );?>" method="post" id="mail_send_form" name="mail_send_form" >
			<input type="hidden" name="mail_step" value="post">
			<?php foreach ($mail_array as $key=>$value){?>

				<input type="hidden" name="mail_ids[]" value="<?php echo $key;?>" >

			<?php } ?>
			<input type="hidden" name="mail_all" value="mail_all" >
			<input type="hidden" name="user_email_title" value="<?php echo  $_POST["user_email_title"];?>">
			<input type="hidden" name="user_email_text" value="<?php echo  $_POST["user_email_text"];?>">
			<input type="hidden" name="owner_name" value="<?php echo  $_POST["owner_name"];?>">
			<input type="hidden" name="owner_mail" value="<?php echo  $_POST["owner_mail"];?>">


			<div class="mail-send-check-area">

				<div class="mail-send-check-contents">

					<div class="mail-send-check-label">送信元</div>
					<div class="mail-send-check-str">
						<?php echo $_POST["owner_name"]; ?>
					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>


			<div class="mail-send-check-area">

				<div class="mail-send-check-contents">

					<div class="mail-send-check-label">送信メール</div>
					<div class="mail-send-check-str">
							<?php echo $_POST["owner_mail"]; ?>

					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>


			<div class="mail-send-check-area">

				<div class="mail-send-check-contents">

					<div class="mail-send-check-label">送信先</div>
					<div class="mail-send-check-str">
						<div class="mail-send-input-str">
						
						<?php 
							foreach ($mail_array as $key=>$value)
							{
								echo $value["姓"] . " ". $value["名"] . ",";
							}
						
						?>
					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>


			<div class="mail-send-check-area">

				<div class="mail-send-check-contents">

					<div class="mail-send-check-label">件　名</div>
					<div class="mail-send-check-str"> <?php echo $_POST["user_email_title"]; ?>
					</div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>


			<div class="mail-send-check-textarea">

				<div class="mail-send-check-textarea-contents">

					<div class="mail-send-textarea-str"> <?php echo nl2br($_POST["user_email_text"]); ?></div>

				</div><?php //mail-send-input-contents?>


			</div><?php //mail-send-input-area?>


			<div class="mail-check-post-label">
				この内容でお間違いない場合は【送信】ボタンを押してください
			</div>

			<div class="mail-send-submit-area">
				<div class="mail-send-submit-contents">

				<input type="submit" value="送信"   class="mail-send-submit">

				</div><?php //mail-send-submit-contents?>
			</div><?php //mail-send-submit-area?>

		</form>


		<div class="mail-send-submit-return-area">

			<form action="<?php  $id = 952; echo get_page_link( $id );?>" method="post" id="mail_send_form" name="mail_send_form" >
				<?php foreach ($mail_array as $key=>$value){?>

					<input type="hidden" name="mail_ids[]" value="<?php echo $key;?>" >

				<?php } ?>
				<input type="hidden" name="mail_all" value="mail_all" >
				<input type="hidden" name="user_email_title" value="<?php echo  $_POST["user_email_title"];?>">
				<input type="hidden" name="user_email_text" value="<?php echo  $_POST["user_email_text"];?>">
				<input type="hidden" name="owner_name" value="<?php echo  $_POST["owner_name"];?>">
				<input type="hidden" name="owner_mail" value="<?php echo  $_POST["owner_mail"];?>">
				<input type="submit" value="入力に戻る"   class="mail-return-submit" style="background-color: cornflowerblue;margin-top: 3%;">
			</form>

		</div>

	</div><?php //mail-send-main-contents?>

</div><?php //mail-send-main-area?>



<?php }else  if(  $_POST["mail_step"] == "post"){ ?>


<?php

//メール送信

$subj =  $_POST["user_email_title"];
$body = $_POST["user_email_text"];

$owner_name = $_POST["owner_name"];
$owner_mail = $_POST["owner_mail"];

$mail_data->getMailIDALL($owner_name,$owner_mail,$subj,$body,$mail_array);


//保存
$mail_data->setMailListAll( $user->ID,$owner_name,$owner_mail,$subj,$body,$mail_array);

?>



<div class="mail-send-main-area">

	<div class="mail-send-main-contents">
	<div class="mail-check-post-label">
		メールを送信しました。
	</div>

	<div class="mail-send-submit-return-area">

		<form action="<?php  $id = 810; echo get_page_link( $id );?>" method="post" id="mail_send_form" name="mail_send_form" >
			<input type="submit" value="送信リストへ"   class="mail-return-submit" style="background-color: cornflowerblue;margin-top: 3%;">
		</form>

	</div>

	  <button type="button" class="user-tabclose-button"  onClick="javascript:window.close();">閉じる</button>

</div><?php //mail-send-main-contents?>

</div><?php //mail-send-main-area?>


<?php }?>