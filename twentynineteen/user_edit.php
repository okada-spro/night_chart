<?php
  require_once("userClass.php");

  //クラス作成
  $users_data= new UserClass();

  //ユーザー
  $user = wp_get_current_user();

  $index_updata_center = "";


  if( isset($_POST["data_updata"]) )
  {
		//情報アップデート
        update_user_meta($user->ID, 'last_name', $_POST['last_name']);
        update_user_meta($user->ID, 'first_name', $_POST['first_name']);
        update_user_meta($user->ID, 'last_name_furi', $_POST['last_name_furi']);
        update_user_meta($user->ID, 'first_name_furi', $_POST['first_name_furi']);
        update_user_meta($user->ID, 'billing_postcode', $_POST['billing_postcode']);
		update_user_meta($user->ID, 'billing_address_1', $_POST['billing_address_1']);
		update_user_meta($user->ID, 'billing_address_2', $_POST['billing_address_2']);
		update_user_meta($user->ID, 'billing_phone', $_POST['billing_phone']);
		//update_user_meta($user->ID, 'user_email', $_POST['user_email']);
		update_user_meta($user->ID, 'message', $_POST['message']);

		//メールアドレスの変更
		$users_data->changeUserMail($user->ID,$_POST['user_email']);
	
		$index_updata_center = "更新しました";

  }


  $last_name =  get_the_author_meta('last_name',$user->ID);
  $first_name=  get_the_author_meta('first_name',$user->ID);
  $last_name_furi=  get_the_author_meta('last_name_furi',$user->ID);
  $first_name_furi =  get_the_author_meta('first_name_furi',$user->ID);
  $billing_postcode =  get_the_author_meta('billing_postcode',$user->ID);
  $billing_address_1 =  get_the_author_meta('billing_address_1',$user->ID);
  $billing_address_2 =  get_the_author_meta('billing_address_2',$user->ID);
  $billing_phone =  get_the_author_meta('billing_phone',$user->ID);
  $user_email =  get_the_author_meta('user_email',$user->ID);
  $confirm_email =  get_the_author_meta('user_email',$user->ID);
  $message =  get_the_author_meta('message',$user->ID);


?>
<script type="text/javascript"> 
<!-- 

function Check(){

    if(document.input_form.last_name.value==""){
        alert("苗字を入力してください。");
        return false;
    }

    if(document.input_form.first_name.value==""){
        alert("名前を入力してください。");
        return false;
    }

      if(document.input_form.last_name_furi.value==""){
        alert("苗字(ヨミガナ)を入力してください。");
        return false;
    }

    if(document.input_form.first_name_furi.value==""){
        alert("名前(ヨミガナ)を入力してください。");
        return false;
    }


    if(document.input_form.user_email.value==""){
        alert("メールアドレスを入力してください。");
        return false;
    }

    if(document.input_form.confirm_email.value==""){
        alert("確認メールアドレスを入力してください。");
        return false;
    }

    if(document.input_form.user_email.value != document.input_form.confirm_email.value){
        alert("メールアドレスが確認用と違います");
        return false;
    }
    

    if(document.input_form.billing_phone.value==""){
        alert("電話番号を入力してください。");
        return false;
    }

    if(document.input_form.billing_postcode.value==""){
        alert("郵便番号を入力してください。");
        return false;
    }

   // if(document.input_form.billing_postcode.value.length != 7){
   //     alert("郵便番号が７桁ではありません");
   //     return false;
   // }
    
    


     if(document.input_form.billing_address_1.value==""){
        alert("住所を入力してください。");
        return false;
    }

   

    if(window.confirm('本当に確定してよろしいですか？')){ // 確認ダイアログを表示
		return true; // 「OK」時は送信を実行
	}
	else{ // 「キャンセル」時の処理
		//window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止
	}
    
}

// -->
</script>

<?php if($index_updata_center != ""){ ?>
    <div class="index_updata_center">
        <p><?php echo $index_updata_center;?></p>
    </div>
  
<?php }?>

<div class="user-edit-area">

	<form action="<?php  $id = 24; echo get_page_link( $id );?>" method="post"  name="input_form"  onsubmit="return Check()">
	
	 <input type="hidden" name="data_updata" value="data_updata">
	
	<table  class="user_edit_table">
	<tr>
		<td  colspan="2">
			<p>
			<div class="user_edit_title">
				登録情報の編集
			</div>
			</p>
		</td>
		
	</tr>
	<tr>
		<td colspan="2">
			<div>苗字<font color="red">＊</font>：
			<div><input type="text"  name="last_name" value="<?php echo $last_name;?>"></input>	</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div>名前<font color="red">＊</font>：</div>
			<div><input type="text"  name="first_name" value="<?php echo $first_name;?>"></input></div>
		</td>
	</tr>
	
	<tr>
		<td  colspan="2">
			<div>苗字(フリガナ)<font color="red">＊</font>：</div>
			<div>	<input type="text"  name="last_name_furi" value="<?php echo $last_name_furi;?>"></input></div>
		</td>
	</tr>
	<tr>
		<td  colspan="2">
			<div>名前（フリガナ）<font color="red">＊</font>：</div>
			<div><input type="text"  name="first_name_furi" value="<?php echo $first_name_furi;?>"></input></div>
		</td>
	</tr>
	
	
	<tr>
		<td  colspan="2">
			<div>郵便番号<font color="red">＊</font>：</div>
			<div><input type="text"  name="billing_postcode" value="<?php echo $billing_postcode;?>"></input></div>
		</td>
	</tr>
	
	
	<tr>
		<td  colspan="2">
			<div>住所<font color="red">＊</font>：</div>
			<div><input type="text"  name="billing_address_1" value="<?php echo $billing_address_1;?>" style="width:100%;"></input></div>
		</td>
		
	</tr>
	
	<tr>
		<td  colspan="2">
			<div>マンション名、部屋番号等：</div>
			<div><input type="text"  name="billing_address_2" value="<?php echo $billing_address_2;?>" style="width:100%;"></input></div>
		</td>
		
	</tr>
	
	<tr>
		<td colspan="2">
			<div>電話番号<font color="red">＊</font>：</div>
			<div><input type="text"  name="billing_phone" value="<?php echo $billing_phone;?>"></input></div>
		</td>
	</tr>
	
	
	<tr>
		<td colspan="2">
			<div>メール<font color="red">＊</font>：</div>
			<div><input type="text"  name="user_email" value="<?php echo $user_email;?>" style="width:100%;"></input></div>
		</td>
		
	</tr>
	
	
	<tr>
		<td  colspan="2">
			<div>メールアドレス確認<font color="red">＊</font>：</div>
			<div><input type="text"  name="confirm_email" value="<?php echo $confirm_email;?>" onpaste="return false" style="width:100%;"></input></div>
		</td>
		
	</tr>
	
	
	
	
	<tr>
		<td colspan="2">
			<div>メッセージ</div>
			<div><textarea cols="20" rows="5" name="message" id="message" class="textarea"><?php echo $message;?></textarea></div>
		</td>
		
	
	<tr>
		<td  colspan="2">
			<p>
			<div><input type="submit" value="プロフィール更新"   class="same-user-select"> <font color="red">＊</font>必須項目</div>
			</p>
		</td>
		
	</tr>
	</table>
	
	
	 
	
	</form>
</div>




