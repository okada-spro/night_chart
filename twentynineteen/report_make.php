<?php 

   
    require_once("reportClass.php");
    require_once("userClass.php");

    $user = wp_get_current_user();

    //クラス作成
    $input_data= new ReportClass();
    $users_data= new UserClass();

    //初期化
    $input_data->init();
 



     //文字用の変数
    $insert_str = "";
    $updata_str = "";

  
    // 次のIDだけ取得する
    $input_data->getInputReportLastReID();

    //新規作成
    if(isset($_POST["make"])){ 

        if(!$input_data->getSerchReportForID($_POST["input_id"]))
        {
            //POSTデータ整形
            $input_data->setPostData($_POST);

            //作成
            $newdata = $input_data->insertRepotData();

            if ( $newdata == false ) {
	            $insert_str = "レポート内容の作成に失敗しました";
            } else {
	            $insert_str = "レポート内容を作成しました";
            }

             // 再更新
             $input_data->getInputReportLastRow();

             //取得データを変更
             $input_data->setInputDataForPost();

             //フォルダの作成
             $input_data->make_reportfolder($input_data->getReportData("input_id"));
        }
        else {
            $insert_str = "すでに最新のIDは登録されています";

            //POSTデータ整形
            $input_data->setPostData($_POST);
        }

    } 
    elseif(isset($_POST["edit"])){ 

        if(isset($_POST["input_id"]))
        {
           
            if(isset($_POST["updata"]))
            {
                 //POSTデータ整形
                $input_data->setPostData($_POST);

                //アップデート
                $newdata = $input_data->upDataRepotData();

                if ( $newdata == false ) {
	                $updata_str = "レポート内容の更新に失敗しました";
                } else {
	                $updata_str = "レポート内容の更新しました";
                }
            }

             $input_data->getInputReportForID($_POST["input_id"]);
        }
        else {
            $input_data->getInputReportForID($_POST["id"]);
        }

        $input_data->setInputDataForPost();
    } 
?>


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
        

    <form action="<?php  $id = 482; echo get_page_link( $id );?>" method="post" name="input_data">

        <input type="hidden" name="input_id" value="<?php echo $input_data->getReportData("input_id");?>" >
        <div class="c-table-container">
            <table class="c-table">
                <tbody>
                    <tr>
                        <th>講義日</th>
                        <td> <input type="date" name="input_eventdate"  id="input_eventdate" value="<?php echo $input_data->getReportData("input_eventdate");?>" ></input></td>
                    </tr>
                    <tr>
                        <th>講義名</th>
                        <td><input type="text"   name="input_title"  value="<?php echo $input_data->getReportData("input_title");?>"   size="40" maxlength="300"></input></td>
                    </tr>
                    <tr>
                         <th>タイプ</th>
                         <td>
                            <select name="input_type" class="same-width-list">
                                <?php foreach ($users_data->member_type_array as $key => $value) {?>
                                    <option value="<?php echo $key;?>" <?php if($input_data->getReportData("input_type") == $key){ echo "selected";}?>><?php echo $value;?></option>
                                <?php } ?>
                            </select>
                            <font size="2" color="red">*なしの場合は株・FXの訓練生両方が提出できます</font>
                        </td>
                     </tr>
                    <tr>
                        <th></th>
                        <td>
                            <?php if(isset($_POST["edit"])){  ?>
                                <input type="hidden" name="edit" value="edit">
                                <input type="hidden" name="updata" value="updata">
                                <input  class="index_table_button"type="submit" value="更新">
                            <?php }else{ ?>
                                <input type="hidden" name="make" value="make">
                                <input type="hidden" name="edit" value="edit">
                                <input  class="index_table_button"type="submit" value="作成">
                            <?php } ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>

    <p>
        <a class="index_button" href="<?php $id = 482; echo get_page_link( $id );?>">新規登録</a>
    </p>>


    <p>
        <a class="index_button" href="<?php $id = 487; echo get_page_link( $id );?>">レポート提出一覧へ</a>
    </p>>
