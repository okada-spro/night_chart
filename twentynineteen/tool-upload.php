<?php 

    require_once("toolClass.php");

    // wpdbオブジェクト
    global $wpdb;

    $user = wp_get_current_user();

    //クラス作成
    $input_data= new ToolClass();

    //初期化
    $input_data->init();

    
     //文字用の変数
    $insert_str = "";
    $updata_str = "";
   


if(isset($_GET["type"])){


    $input_id = 0;
    $defoult_select = 1;
    $input_title = "";


    //編集の場合はIDが指定されている
    if(isset($_POST["edit"])){

        $input_data->getToolForID( $_POST["input_id"]);

        if($input_data->tool_row)
        {
            foreach ($input_data->tool_row as $rows) 
            {
                 $input_id= $_POST["input_id"];
                 $input_title= $rows->post_title;
                 $defoult_select =  $rows->post_category;
            }
        }
        else{
            $input_id = $input_data->getNextID();
        }
       


    }else{
        //新規の場合は最新のデータを取得
        $input_data->getInputToolLastRow();

        //次の予定しているIDを取得
        if($input_data->tool_row)
        {
            $input_id = $input_data->getNextID();
        }
        else{
            $input_id = 1;
        }
    }

    //新規登録(POSTなのでここ優先)
    if(isset($_POST["fileup"]))
    {
        if($input_data->checkToolForID ($_POST["input_id"]))
        {
            //編集(ファイルとは別になる)
            if(isset($_POST["fileup"]) && $_SERVER['REQUEST_METHOD'] === 'POST')
            {
                if($_FILES['userfile']['size'] === 0) {
                    //ファイルサイズがゼロの場合はタイトルなどの編集のみ
               

                    if($input_data->upload_file_data($_POST,""))
                    {
                        $insert_str = "データの更新が完了しました";
                    }
                    else{
                        $insert_str = "データの更新に失敗しました";
                    }
                }
                else {

                     $filetype = mime_content_type($_FILES['userfile']['tmp_name']);

                    if(!strpos($filetype, 'zip'))
                    {
                        $insert_str = 'この拡張子ではアップロードできません';
                    }
                    else if ($_FILES['userfile']['error'] === 2) {
                        $insert_str = '送信したファイルサイズを小さくしてください'; //エラーを確認
                
                    }elseif ($_FILES['userfile']['error'] === 0) {

                        if($input_data->upload_file_data($_POST,$input_data->mb_basename($_FILES['userfile']['name'])))
                        {
                            $input_data->tool_upload($_POST,$_FILES);

                            $insert_str = "ファイルアップロードが完了しました";
                        }
                        else{
                            $insert_str = "ファイルアップロードに失敗しました";
                        }
                }
                else{
                     $insert_str = 'ファイルのアップロートに失敗しています';
                }   

                }

            }



            
        }
        else
        {
            //新規（必ずファイルは必要）
            if(isset($_POST["fileup"]) && $_SERVER['REQUEST_METHOD'] === 'POST' && $_FILES['userfile']['tmp_name'] != "")
            {
               
                $filetype = mime_content_type($_FILES['userfile']['tmp_name']);

                //  echo $filetype;

                if(!strpos($filetype, 'zip'))
                {
                    $insert_str = 'この拡張子ではアップロードできません';
                }
                else if ($_FILES['userfile']['error'] === 2) {
                    $insert_str = '送信したファイルサイズを小さくしてください'; //エラーを確認
                }elseif($_FILES['userfile']['size'] === 0) {
                     $insert_str = 'ファイルを選択してください'; // ③サイズが0だった場合（ファイルが空）
                }elseif ($_FILES['userfile']['error'] === 0) {

                    

                    if($input_data->save_file_new($_POST,$_FILES))
                    {
                        $insert_str = "ファイルアップロードが完了しました";
                    }
                    else{
                        $insert_str = "ファイルアップロードに失敗しました";
                    }
                }
                else{
                     $insert_str = 'ファイルのアップロートに失敗しています';
                }
                
            }
        }

        $input_id= $_POST["input_id"];
        $input_title= $_POST["input_title"];
        $defoult_select = $_POST["input_category"];
    }

    $edit = false;

    //IDがある場合は更新
    if($input_data->checkToolForID ($input_id))
    {
        $edit = true;
    }
    
    
   

    

?>

<script type="text/javascript"> 
<!-- 

function up_check(){

	if(window.confirm('本当に更新してよろしいですか？（新規登録ではありません）')){ // 確認ダイアログを表示
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

<?php if($updata_str != ""){ ?>
    <div class="index_updata_center">
        <p><?php echo $updata_str;?></p>
    </div>
   
<?php }?>

         <?php if(!$edit){?>
            <form enctype="multipart/form-data"   action="<?php  $id = 537; echo get_page_link( $id );?>?type=upload" method="post" name="input_data">
         <?php }else{?>
             <form enctype="multipart/form-data"   action="<?php  $id = 537; echo get_page_link( $id );?>?type=upload" method="post" name="input_data"  onSubmit="return up_check()">
        <?php }?>
        
        <input type="hidden" name="input_id" value="<?php echo $input_id;?>" >
        <div class="c-table-container">
            <table class="c-table">
                <tbody>
                    <tr>
                        <th>アップロードファイル名</th>
                        <td><input type="text"   name="input_title"  value="<?php echo $input_title;?>"   size="40" maxlength="300"></input></td>
                    </tr>
                    <tr>
                        <th>アップロードカテゴリ</th>
                        <td>
                             <select name="input_category" >
                                <?php foreach ($input_data->disp_tool_category_data as $key => $value) {?>
                            <option value="<?php echo $key;?>" <?php if($defoult_select == $key){ echo "selected";}?>><?php echo $value[0];?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>アップロードファイル</th>
                        <td>
                              <input type="hidden" name="fileup" value="" />
                              <input name="userfile" type="file" accept=".zip"/>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <?php if(!$edit){?>
                                <input  class="index_table_button"type="submit" value="アップロード">
                            <?php }else{?>
                                <input  class="index_table_button"type="submit" value="更新">
                            <?php }?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>

    <?php if($edit){?>
    <div class="index_center">
        <p>
            <font color="red">データを新規で登録する場合は必ず「新規ツールアップロード」から行ってください
        </p>
    </div>
     <p>
		<a class="index_button" href="<?php $id = 537; echo get_page_link( $id );?>?type=upload">新規ツールアップロード</a>
	</p>
    <?php }?>
    <p>
	<a class="index_button" href="<?php $id = 537; echo get_page_link( $id );?>">一覧へ</a>
	</p>

<?php
}else{


    //削除
    if(isset($_POST["delete"]))
    {
        //ファイルがある場合は削除
        if($input_data->checkToolForID ($_POST['input_id']))
        {
            $input_data->deleteToolData($_POST['input_id']);
        }
    }






    //全データ取得
    $input_data->getIDRow();

    //表示変更
    $input_disp_table = 0;

     //ソート
    if(isset($_POST['sort_table']) )
    {
        $input_disp_table = $_POST['sort_table'];
    }
     
    

    //表示配列を作ってしまう
    $table_array = $input_data->make_tool_array_datatable($input_data->tool_row,$input_disp_table);



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
<script>
$(document).ready(function() {

    $('table.user-table-two')
        .tablesorter({})
        .tablesorterPager({
            container: $(".pager"),
            size: 100,
    });
});
</script>
<div class="page_div_box">
    <p>
        <form action="<?php  $id = 537; echo get_page_link( $id );?>" method="post">
            <select name="sort_table"  class="same-user-select">
                <?php foreach ($input_data->disp_tool_category_data as  $key =>$value) {?>
                   <option value="<?php echo $key;?>" <?php if($input_disp_table == $key){ echo "selected";}?>><?php echo $value[2];?></option>
                <?php } ?>
            </select>
            <input type="submit" value="表示変更"   class="same-user-select">
        </form>
    </p>



    <table class="user-table-two">
        <thead>
        <tr>
            <th>編集</th>
            <th>削除</th>
            <th>ID</th>
            <th>表示名</th>
            <th>カテゴリ</th>
            <th>ファイル名</th>
        </tr>
        </thead>
        <?php foreach ($table_array as $key =>$value) { ?>

       

        <tr >
            <td>
                <form  action="<?php  $id = 537; echo get_page_link( $id );?>?type=upload" method="POST">
                    <input type="hidden" name="input_id" value="<?php echo $value["input_id"]; ?>" />
                    <input type="hidden" name="edit" value="edit" />
                    <input type="submit" value="編集" />
                </form>
            </td>
            <td>
                 <form  action="<?php  $id = 537; echo get_page_link( $id );?>" method="POST"   onSubmit="return delete_check()">
                    <input type="hidden" name="input_id" value="<?php echo $value["input_id"]; ?>" />
                    <input type="hidden" name="delete" value="delete" />
                    <input type="submit" value="削除" />
                </form>
            </td>
            <td><?php echo $value["input_id"]; ?></td>
            <td><?php echo $value["input_title"]; ?></td>
            <td><?php echo $input_data->disp_tool_category_data[$value["input_category"]][0]; ?></td>
            <td><?php echo $value["input_file_name"]; ?></td>
         </tr>
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
                <option  value="100">100</option>
                <option selected="selected" value="500">500</option>
            </select>
        </div>
    </p>

     <p>
		<a class="index_button" href="<?php $id = 537; echo get_page_link( $id );?>?type=upload">新規ツールアップロード</a>
	</p>
</div>
<?php
}

?>