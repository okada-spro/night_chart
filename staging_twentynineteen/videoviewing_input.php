<?php 

    require_once("videoviewClass.php");

    // wpdbオブジェクト
    global $wpdb;

    $user = wp_get_current_user();

    //クラス作成
    $input_data= new VideoViewClass();

    //初期化
    $input_data->init();

    // データ取得クエリ実行
    $input_data->getInputViewRow();

    // カテゴリー取得クエリ実行
    $input_data->getCategoryDataRow();

     //文字用の変数
    $insert_str = "";
    $updata_str = "";
    $new_not_str = "";

    //一覧ページの確認
   


    //保存
    if(isset($_POST['is_save']) )
    {
         //postデータのセット
        $input_data->setPostData($_POST);

        //新規
        if(isset($_POST['input_id']) )
        {
             if($input_data->getViewData("input_id") == 0)
             {
                //同じタイトルがあるかどうかを確認
                if($input_data->checkTitle($_POST))
                {
                    $insert_str =   '同じタイトル名が登録されています。新規登録の場合は別の名前で登録してください。';
                }
                else {

                    $insert_sql = $input_data->insertViewData($user->ID);

                    if ( $insert_sql == false ) {
	                    $insert_str =  "「" .$_POST["input_title"].'」の登録に失敗しました';
                    } else {
	                    $insert_str =  "「" .$_POST["input_title"].'」を登録しました';

                      // データ取得クエリ実行(再更新)
                      $input_data->getNewViewRow();

                       $new_not_str = "<font color='red'>＊新しい動画を登録する際は必ず新規登録画面より登録してください</font>";

                    }
                }
            }
            else 
            {
                $updata_sql = $input_data->upDataViewData($user->ID);

                if ( $updata_sql == false ) {
	                $updata_str =   "「" .$_POST["input_title"].'」の更新に失敗しました';
                } else {
	                $updata_str = "「" .$_POST["input_title"].'」を更新しました';

                     $new_not_str = "<font color='red'>＊新しい動画を登録する際は必ず新規登録画面より登録してください</font>";
                }
            }

        }

       
    }
    else if(isset($_POST['viewing_edit']) )
    {
         //postデータのセット
        $input_data->setEditData($_POST);
    }

?>

<script> 


$(function() {

 
  //セレクトボックスが切り替わったら発動
  $('#input_category').change(function() {
 
    //選択したvalue値を変数に格納
    var select_val =  $('#input_category option:selected').text();
    var day_val =  $('#input_release_date').val();

    var res = "";

    if( day_val != "" )
    {
        var day_array = day_val.split('T');

         res = day_array[0].replace( /-/g, "/" );
    }

    //選択したvalue値をp要素に出力
    $('#input_title').val(res + "  " + select_val);


  });

  $('#input_release_date').change(function() {
 
    //選択したvalue値を変数に格納
    var select_val =  $('#input_category option:selected').text();
    var day_val =  $('#input_release_date').val();

    //選択したvalue値をp要素に出力
    var res = "";

    if( day_val != "" )
    {
        var day_array = day_val.split('T');

        res = day_array[0].replace( /-/g, "/" );
    }

    //選択したvalue値をp要素に出力
    $('#input_title').val(res + "  " + select_val);


  });

});
</script>

<script type="text/javascript"> 
<!-- 

function update_check(){

	if(window.confirm('本当に上書きしてもよろしいですか？')){ // 確認ダイアログを表示
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



<form action="<?php  $id = 129; echo get_page_link( $id );?>" method="post"    <?php if($input_data->getViewData("input_id") != 0){?> onSubmit="return update_check();" <?php } ?>>
    <div class="c-table-container">
        <table class="c-table">
        <tbody>

        <input type="hidden" name="input_id" value="<?php echo $input_data->getViewData("input_id");?>">
        <input type="hidden"  name="input_flame_width" value='<?php echo $input_data->getViewData("input_flame_width");?>'  >
         <input type="hidden"  name="input_flame_height" value='<?php echo $input_data->getViewData("input_flame_height");?>'  >

        <?php  if(isset($_POST["list"])){?>
            <input type="hidden" name="list" value="<?php echo $_POST["list"];?>">
        <?php } ?>


        <tr>
            <th>カテゴリー</th>
            <td>
                <select name="input_category" id="input_category"  class="same-user-select">
                    <?php foreach ($input_data->view_category_row as $value) {?>
                        <option value="<?php echo $value->ID;?>"  <?php if($input_data->getViewData("input_category") == $value->ID){ echo "selected";}?>><?php echo $value->post_category_name;?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>公開日</th>
            <td>
                <input type="datetime-local" name="input_release_date" id="input_release_date" value="<?php echo $input_data->getViewData("input_release_date");?>" >*公開順に並びます
            </td>
        </tr>

        <tr>
            <th>動画タイトル</th>
            <td><input type="text" name="input_title" id="input_title" value='<?php echo $input_data->getViewData("input_title");?>'  ></input></td>
        </tr>
   
        <tr>
            <th>埋め込み番号</th>
            <td>
                <input type="number"  name="input_flame" value='<?php echo $input_data->getViewData("input_flame");?>'   size="60"></input>
            </td>
        </tr>
        <tr>
            <th>埋め込みURL</th>
            <td>
                <input type="text"  name="input_url" value='<?php echo $input_data->getViewData("input_url");?>'   size="60"></input>
            </td>
        </tr>
        <?php /*
         <tr>
            <th>表示横幅（width）</th>
            <td>
                <input type="number"  name="input_flame_width" value='<?php echo $input_data->getViewData("input_flame_width");?>'   size="60"></input>
            </td>
        </tr>

          <tr>
            <th>表示縦幅（height）</th>
            <td>
                <input type="number"  name="input_flame_height" value='<?php echo $input_data->getViewData("input_flame_height");?>'   size="60"></input>
            </td>
        </tr>
        */?>
        

        <tr>
            <th>公開</th>
            <td>
                <select name="input_disp" id="input_disp">
                    <option value="0" <?php if($input_data->getViewData("input_disp") == 0){echo "selected";}  ?>>非公開</option>
                    <option value="1" <?php if($input_data->getViewData("input_disp") == 1){echo "selected";}  ?>>公開</option>
                 </select>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
    <p>
         <input type="hidden" name="is_save" value="save">
    </p>
     <?php if($input_data->getViewData("input_id") != 0){?>
     
        <div class="index_center">
            <?php echo  $new_not_str; ?>
        </div>

        

        <input class="index_history_button" type="submit" value="更新" >
        <p>
            <a class="index_updata_button" href="<?php $id = 129; echo get_page_link( $id );?>">動画新規登録へ</a>
        </p>
     <?php }else{ ?>
         <input class="index_history_button" type="submit" value="登録">

     <?php } ?>
</form>
    <p>
        <?php  if(isset($_POST["list"])){?>
            <a class="index_updata_button" href="<?php $id = 54; echo get_page_link( $id );?>?list=<?php echo $_POST["list"];?>">動画一覧（管理者）へ</a>
        <?php }else{ ?>
            <a class="index_updata_button" href="<?php $id = 54; echo get_page_link( $id );?>">動画一覧（管理者）へ</a>
        <?php } ?>
    </p>