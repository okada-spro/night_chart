<?php 



    require_once("zoomClass.php");

    $user = wp_get_current_user();

    //クラス作成
    $input_data= new ZoomClass();

    //初期化
    $input_data->init();

    
    // データ取得クエリ実行
    $input_data->getInputZoomRow($user->ID);

    //文字用の変数
    $insert_str = "";
    $updata_str = "";

     //保存
    if(isset($_POST['is_save']) )
    {
        
        $input_data->setPostData($_POST); //POSTデータを入れなおす


        //更新かどうかを確認
        if($input_data->getZoomData("input_id") != 0)
        {
            //IDがあるので更新
            $updata_sql = $input_data->upDataTradeData($user->ID);

            if ( $updata_sql == false ) {
	             $updata_str =  '更新に失敗しました';
             } else {
	             $updata_str = '更新しました';
             }
        }
        else {
            //IDがないので新規
            $insert_sql = $input_data->insertTradeData($user->ID);

             if ( $insert_sql == false ) {
	            $insert_str =   '登録に失敗しました';
            } else {
	             $insert_str =  '登録しました';

                 //登録できた場合、最新のIDがこれになるので最新IDを取り直す



                  // データ取得クエリ再実行
                  $input_data->getInputZoomLastRow();

                 //  var_dump($input_data->zoom_last_row);

                  //IDを最新にセット
                 $input_data->setNewID();
            }
            
	    }
       


    }
    
    else if(isset($_POST['is_edit']) )
    {
        $users = get_users(array('orderby'=>'ID','order'=>'ASC',)); 

         //データ取得
        $input_data->setEditData($_POST);
    }


    if( isset($_GET["catagory"]))
    {
        //初期値を設定(新規登録時)
        if($_GET["catagory"] == 1)
        {
            //ザラ場
            $input_data->init_zaraba();
        }
        else  if($_GET["catagory"] == 2)
        {
            //株講義
             $input_data->init_kabu_kougi();
        }
         else  if($_GET["catagory"] == 3)
        {
            //FX講義
             $input_data->init_fx_kougi();
        }

    }

   

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

function clickBtn1(){
	// 値を取得
	var  t1 = document.input_data.input_zoom_day.value;


	document.getElementById("input_zoom_deadline").value  = t1;
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


<form action="<?php  $id = 51; echo get_page_link( $id );?>" method="post" name="input_data">
    <div class="c-table-container">
        <table class="c-table">
        <tbody>
            <tr>
                <th>募集開始時間</th>
                <td> <input type="datetime-local" name="input_zoom_day"  id="input_zoom_day" value="<?php echo $input_data->changeSetData($input_data->getZoomData("input_zoom_day"));?>" step="1800"></input></td>
            </tr>
            <tr>
                <th><font color="red">講義締切時間</font></th>
                <td><input type="datetime-local" name="input_zoom_deadline"  id="input_zoom_deadline" value="<?php echo $input_data->changeSetData($input_data->getZoomData("input_zoom_deadline"));?>" ></input>*締切時刻です</td>
            </tr>
            <tr>
                <th>講義開始予定時間</th>
                <td> <input type="datetime-local" name="input_zoom_start_day"  id="input_zoom_start_day" value="<?php echo $input_data->changeSetData($input_data->getZoomData("input_zoom_start_day"));?>" step="1800"></input></td>
            </tr>

             
             <tr>
                <th>参加URL</th>
                <td><input type="text"  name="input_zoom_url" value="<?php echo $input_data->getZoomData("input_zoom_url");?>"   size="40" maxlength="300"></input></td>
            </tr>
            <tr>
                <th>タイトル</th>
                <td><input type="text"   name="input_zoom_title"  value="<?php echo $input_data->getZoomData("input_zoom_title");?>"   size="40" maxlength="300"></input></td>
            </tr>
            <tr>
                <th>カテゴリー</th>
                <td>
                     <select name="input_zoom_category" >
                        <?php foreach ($input_data->zoom_catagory_array_data as $key => $value) {?>
                            <option value="<?php echo $key;?>" <?php if($input_data->getZoomData("input_zoom_category") == $key){ echo "selected";}?>><?php echo $value;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
              <tr>
                <th>参加不可</th>
                <td>
                     <select name="input_zoom_joinType" >
                        <?php foreach ($input_data->zoom_join_array_data as $key => $value) {?>
                            <option value="<?php echo $key;?>" <?php if($input_data->getZoomData("input_zoom_joinType") == $key){ echo "selected";}?>><?php echo $value;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </tbody>
        
        <input type="hidden" name="input_id" value="<?php echo $input_data->getZoomData("input_id");?>">
        <input type="hidden" name="is_save" value="save">
    
        <tr>
            <th></th>
            <td>
    <?php if($input_data->getZoomData("input_id") > 0){ ?>
        <input  class="index_table_button"type="submit" value="更新">
    <?php }else{ ?>
        <input  class="index_table_button"type="submit" value="登録">
    <?php } ?>
            </td>
        </tr>
</form>
        <?php if($input_data->getZoomData("input_id") > 0){ ?>
            <tr>
                <th></th>
                <td>
                    <form action="<?php  $id = 46; echo get_page_link( $id );?>" method="post"  onSubmit="return delete_check()">
                        <input type="hidden" name="id" value="<?php echo $input_data->getZoomData("input_id");?>">
                        <input type="hidden" name="is_delete" value="delete">
                        <input type="submit" value="削除"  class="index_table_button">
                    </form>
                </td>
            </tr>
        
<?php /*
        <tr>
            <th></th>
            <td>
                <form action="<?php  $id = 46; echo get_page_link( $id );?>" method="post">
                    <input type="hidden" name="id" value="<?php echo $input_data->getZoomData("input_id");?>">
                    <input type="hidden" name="is_join" value="join">
                    <input type="submit" value="出席者編集へ"  class="index_table_button">
                </form>
            </td>
        </tr>
        */?>
        <?php } ?>
</table>
     
  
     <p>
        <a class="index_updata_button" href="<?php $id = 46; echo get_page_link( $id );?>">直近のミーティング一覧へ</a>
     </p>

     <?php 
        if(isset($_POST['is_edit']) ) {

            //echo $input_data->getZoomData("input_zoom_day");

            $now_time = new DateTime(date('Y-M-d H:i:s', strtotime('+9hour')));
            $zoom_time = new DateTime($input_data->getZoomData("input_zoom_day"));

            $table_array = $input_data->input_data["input_plans"];
            $table_str = "参加者一覧";

           
             //var_dump($table_array);
            //echo count($input_data->input_data["input_join"]);

            if($table_array)
            {
     ?>
                 <div class="index_center">
                    <font size="6"><?php echo $table_str;?></font>
                 </div>

                <table class="user-table"  width="650">
                    <colgroup span="6"></colgroup>
                    <tr>
                        <th>ID</th>
                        <th>名前</th>
                    </tr>
                    <?php foreach ($table_array as $row){?>
                        <?php if($row != ""){?>
                        <tr>
                            <?php 
                                if( in_array($row,(array)$table_array) )
                                { 
                                    
                                     $joins_info = get_userdata( $row );

                                     if($joins_info)
                                     {
                            ?>
                                <td><?php echo $row;?></td>
                                <td><?php echo get_the_author_meta('last_name',$row);?>　<?php echo get_the_author_meta('first_name',$row);?>(<?php echo get_the_author_meta('user_login',$row);?>)</td>
                            <?php    
                                    }
                                 }
                            ?>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                 </table>
        <?php } ?>
     <?php } ?>