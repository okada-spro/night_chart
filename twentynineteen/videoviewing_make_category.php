<?php 

    require_once("videoviewClass.php");
    require_once("userClass.php");

    // wpdbオブジェクト
    global $wpdb;

    $user = wp_get_current_user();

    //クラス作成
    $input_data= new VideoViewClass();
    $users_data= new UserClass();

    //文字用の変数
    $insert_str = "";

    //新規登録
    if(isset($_POST['new_save']) ){

        $c_name = "カテゴリーなし";

        if(isset($_POST['input_title']))
        {
            if( $_POST['input_title'] != "")
            {
                $c_name = $_POST['input_title'];
            }
        }
        
        //新規登録
         $insert_sql = $input_data->insertCategoryData($user->ID,$c_name,$_POST['input_type']);

         
         if ( $insert_sql == false ) {
	        $insert_str =  "「" .$_POST["input_title"].'」の登録に失敗しました';
         } else {
	        $insert_str =  "「" .$_POST["input_title"].'」を登録しました';
        }
    }

    else if(isset($_POST['new_edit']) ){

        $c_name = "カテゴリーなし";

        if(isset($_POST['input_title']))
        {
            if( $_POST['input_title'] != "")
            {
                $c_name = $_POST['input_title'];
            }
        }
        
        //新規登録
         $insert_sql = $input_data->upDataCategoryData($user->ID,$_POST['id'],$c_name,$_POST['input_type']);

         
         if ( $insert_sql == false ) {
	        $insert_str =  "ID:" .$_POST["id"].'の更新に失敗しました';
         } else {
	        $insert_str =  "ID:" .$_POST["id"].'を更新しました';
        }
    }
     else if(isset($_POST['is_delete']) ){

       
        //削除
         $insert_sql = $input_data->deleteCategoryData($_POST['id']);

         
         if ( $insert_sql == false ) {
	        $insert_str =  "ID:" .$_POST["id"].'の削除に失敗しました';
         } else {
	        $insert_str =  "ID:" .$_POST["id"].'を削除しました';
        }
    }



    
    //初期化
    $input_data->init();

    // データ取得クエリ実行
    $input_data->getCategoryDataRow();

?>
<script type="text/javascript"> 
<!-- 

function delete_check(){

	if(window.confirm('本当に削除してよろしいですか？')){ // 確認ダイアログを表示
		return true; // 「OK」時は送信を実行
	}
	else{ // 「キャンセル」時の処理
		//window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止
	}
}
// -->
</script>


<?php if($insert_str != ""){ ?>
    <div class="index_updata_center">
        <p><?php echo $insert_str;?></p>
    </div>
   
<?php }?>

<div class="page_div_box  videoviewing_make_category">
    

     <p>
    <div class="videoviewing_input_category">
        <?php if(isset($_POST['edit']) ){ ?>
      
        <form action="<?php  $id = 209; echo get_page_link( $id );?>" method="post">
            <input type="hidden" name="id" value="<?php echo $_POST['id'];?>">
            <input type="hidden" name="new_edit" value="new_edit">

            <table class="videoviewing_input_category_table">
                <tr>
                    <td>カテゴリ名</td>
                    <td> <input type="text" name="input_title" value='<?php echo $_POST['word'];?>' ></td>
                </tr>
                <tr>
                    <td>タイプ</td>
                    <td style="text-align:left;">
                        <select name="input_type" class="same-width-list">
                            <?php foreach ($users_data->member_type_array as $key => $value) {?>
                                <option value="<?php echo $key;?>" <?php if($_POST['type'] == $key){ echo "selected";}?> ><?php echo $value;?></option>
                            <?php } ?>
                        </select>
                        <input type="submit" value="変更"   class="same-user-select">
                    </td>
                </tr>
            </table>
           
        </form>
        

    <?php }else{ ?>
        <form action="<?php  $id = 209; echo get_page_link( $id );?>" method="post">
            <input type="hidden" name="new_save" value="new_save">
            <table class="videoviewing_input_category_table">
                <tr>
                    <td>新規登録</td>
                    <td><input type="text" name="input_title" value='' ></td>
                </tr>
                <tr>
                    <td>タイプ</td>
                    <td style="text-align:left;">
                        <select name="input_type" class="same-width-list">
                            <?php foreach ($users_data->member_type_array as $key => $value) {?>
                                <option value="<?php echo $key;?>"><?php echo $value;?></option>
                            <?php } ?>
                        </select>
                        <input type="submit" value="登録"   class="same-user-select">
                    </td>
                </tr>
            </table>
        </form>
    <?php }?>
    </div>
    </p>

    <table class="user-table-two">
        <colgroup span="3"></colgroup>
        <tr>
            <th class="fixed_th_1">編集</th>
            <th class="fixed_th_2">削除</th>
            <th>ID</th>
            <th>カテゴリー名</th>
            <th>タイプ</th>
        </tr>
        <?php foreach ($input_data->view_category_row as $row) { ?>
        <tr>
            <td  class="fixed_th_1">
                <?php if(($row->ID > 1 && $row->ID != 7)){ ?>
                    <form action="<?php  $id = 209; echo get_page_link( $id );?>" method="post">
                        <input type="hidden" name="id" value="<?php echo $row->ID;?>">
                        <input type="hidden" name="word" value="<?php echo $row->post_category_name;?>">
                        <input type="hidden" name="type" value="<?php echo $row->post_type;?>">
                        <input type="hidden" name="edit" value="edit">
                        <input type="submit" value="編集">
                    </form>
                <?php } ?>
            </td>
             <td class="fixed_th_2">
                <?php if($row->ID > 1 && $row->ID != 7){ ?>
                    <form action="<?php  $id = 209; echo get_page_link( $id );?>" method="post"  onSubmit="return delete_check()">
                        <input type="hidden" name="id" value="<?php echo $row->ID;?>">
                        <input type="hidden" name="is_delete" value="delete">
                        <input type="submit" value="削除">
                    </form>
                <?php } ?>
            </td>
            <td><?php echo $row->ID; ?></td>
            <td><?php echo $row->post_category_name; ?></td>
            <td><?php echo $users_data->member_type_array[$row->post_type]; ?></td>
         </tr>
        <?php } ?>
    </table>

     <?php if(isset($_POST['edit']) ){ ?>
        <p>
        <a class="index_button" href="<?php $id = 209; echo get_page_link( $id );?>">新規登録へ</a>
        </p>
     <?php } ?>
</div>