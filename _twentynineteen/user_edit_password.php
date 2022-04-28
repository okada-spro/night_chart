<?php


   ini_set('display_errors', 0);

  //ユーザー
  $user = wp_get_current_user();

  $index_updata_center = "";


  if( isset($_POST["change_pass_data_updata"]) )
  {
		$user = wp_get_current_user();

		$user_id = $user->ID;
		$user_pass = $_POST['password'];

		$user_id = wp_update_user( array( 'ID' => $user_id, 'user_pass' => $user_pass ) );

		if ( is_wp_error( $user_id ) ) {
			// エラー発生、おそらくユーザーが存在しなかった。
			$index_updata_center = "更新に失敗しました";
		} else {
			// 成功!
			$index_updata_center = "更新しました";
		?>
  
		<meta http-equiv="Refresh" content="0; URL=/login">
 
  <?php
			return;

		}	
		
  }


  $user_password =  "";//get_the_author_meta('password',$user->ID);


?>
<script type="text/javascript"> 
<!-- 

function Check(){

   

    if(document.input_form.password.value==""){
        alert("パスワードを入力してください。");
        return false;
    }

    if(document.input_form.confirm_password.value==""){
        alert("確認パスワードを入力してください。");
        return false;
    }

    if(document.input_form.password.value != document.input_form.confirm_password.value){
        alert("パスワードが確認用と違います");
        return false;
    }
    

  
    if(window.confirm('本当に確定してよろしいですか？\n\n確定後は自動でログアウトします')){ // 確認ダイアログを表示
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

<form action="<?php  $id = 743; echo get_page_link( $id );?>" method="post"  name="input_form"  onsubmit="return Check()">

 <input type="hidden" name="change_pass_data_updata" value="change_pass_data_updata">

<table  class="user_edit_table">
<tr>
	<td  colspan="2">
		<p>
		<div class="user_edit_title">
			パスワードの変更
		</div>
		</p>
	</td>
	
</tr>


<tr>
	<td colspan="2">
		<div>新規パスワード<font color="red">＊半角英数 6文字以上</font>：</div>
		<div><input type="text"  name="password" value="<?php echo $user_password;?>" style="width:100%;" pattern="^([a-zA-Z0-9]{6,})$"></input></div>
	</td>
	
</tr>


<tr>
	<td  colspan="2">
		<div>新規パスワード確認<font color="red">＊</font>：</div>
		<div><input type="password"  name="confirm_password" value="" onpaste="return false" style="width:100%;" pattern="^[0-9A-Za-z]+$"></input></div>
	</td>
	
</tr>




<tr>
	<td  colspan="2">
		<p>
		<div><input type="submit" value="パスワード更新"   class="same-user-select"></div>
		</p>
	</td>
	
</tr>
</table>


 

</form>




