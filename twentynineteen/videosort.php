<?php 

    require_once("videoviewClass.php");

    // wpdbオブジェクト
    global $wpdb;

    $user = wp_get_current_user();

    //
    $user_level =  get_the_author_meta('member_level',$user->ID) ;
    $user_member_type = get_the_author_meta('member_type',$user->ID);
   

    //クラス作成
    $input_data= new VideoViewClass();

    //初期化
    $input_data->init();

    // データ取得クエリ実行
    $input_data->getReleaseDateViewRow();

     // カテゴリ取得クエリ実行
    $input_data->getCategoryDataRow();

    //本数計算
    $input_data->checkCategoryNum($user_level,$user_member_type,$user->ID);

  
?>
        


<?php if(!isset($_GET["video_category"])){ ?>


    <div class="" style="margin-bottom: 30px;">

        <div class="" style="text-align: center;font-size: 33px;flood-color: black;">並べ変える動画の選択</div>
    </div>


    <table class="user-table-two">
        <colgroup span="3"></colgroup>
        <tr>
            <th></th>
            <th>カテゴリー名</th>
            <th>本数</th>
        </tr>
       <?php foreach ($input_data->view_category_row as $row) { ?>
            <?php if($input_data->view_category_number[$row->ID] > 0 && $row->ID != 1 ){?>
                <tr>
                    <td>
                        <?php if($row->ID != 7){ ?>
                        
                                <form action="<?php  $id = 1794; echo get_page_link( $id );?>?video_category=<?php echo  $row->ID;?>" method="post">
                                    <input type="hidden" name="category_id" value="<?php echo  $row->ID;?>">
                                    <input type="hidden" name="viewing_category" value="viewing_category">
                                    <input type="submit" value="並べ替える">
                                </form>
                           
                        <?php } ?>
                     </td>
                    <td><?php echo $row->post_category_name; ?></td>
                    <td><?php echo $input_data->view_category_number[$row->ID]; ?>本</td>
                </tr>
            <?php } ?>
        <?php } ?>
    </table>




<?php }else{ ?>

    <?php


          $open_array = $input_data->setOpenData(false);
          $vide_add_array =  $input_data->getAddVideo( );//追加用のデータ


          //保存
          if(isset($_POST["save_sort"])){


            foreach ($open_array as $row) {

                if(isset($_POST["sort_" .$row["input_id"] ] )){

                    if(isset($vide_add_array[$row["input_id"]])){

                        //追加データがある
                        if( $_POST["sort_" . $row["input_id"] ]  != "" )
                        {
                              update_field( "item_add_vdeo_sort_num", $_POST["sort_" . $row["input_id"] ] , $vide_add_array[  $row["input_id"] ]);
                        }
                        else{
                           
                             update_field( "item_add_vdeo_sort_num", "" , $vide_add_array[  $row["input_id"] ]);
                        }

                       // echo $row["input_id"] . "<br>";

                    }
                    else{

                        //追加データがない
                         if( $_POST["sort_" . $row["input_id"] ]  != "" ) //データがあるものだけ
                         {
                             $add_id = $input_data->setAddVideo( $row["input_id"] ,  $row["input_title"] );

                             update_field( "item_add_vdeo_sort_num", $_POST["sort_" . $row["input_id"] ] , $add_id);
                         }
                         //echo $row["input_id"] . "<br>";
                    }

                }

            }

              
          }
        

          //順番に上から入れていく
          $sort_open_array = $input_data->setSortVideoArray( $open_array , $vide_add_array );

          


         //var_dump($sort_open_array);

          $count = 1;

    ?>


<style>

.video_sort_table_area{
    max-width: 930px;
    margin-left: auto;
    margin-right: auto;
}

table.video_sort_table{
    width: 100%;
}


table.video_sort_table th{

}


table.video_sort_table tr{

}


table.video_sort_table td{
        border: 1px solid black;
}

.admin-spirit-edit-updown{
    cursor: pointer;
    color: blue;
    text-decoration: underline;
}

.video_sort_send_button{
    text-align: center;
    width: 100%;
    margin-bottom: 30px;
}

.video_sort_messege{
        font-size: 17px;
    font-weight: bold;
    color: black;
    margin-bottom: 20px;
    text-align: center;
}

</style>


        
        <form action="<?php $id = 1794; echo get_page_link( $id );?>" method="post">

            <div class="video_sort_send_button">
                <button type="submit" style="width: 175px;background-color: gray;">選択に戻る</button>
            </div>

        </form>

        <div class="video_sort_table_area">

            <form action="<?php $id = 1794; echo get_page_link( $id );?>?video_category=<?php echo $_GET["video_category"]; ?>" method="post">

                 <input type="hidden" name="save_sort" value="save_sort">
                 <input type="hidden" name="category_id" value="<?php echo   $_GET["video_category"];?>">
                 <div class="video_sort_send_button">
                    <button type="submit" style="width: 300px;">順番を保存</button>
                 </div>


                 <div class="video_sort_messege">
                    順番に１以上の数値を入力してください。<br>
                    入力がない場合は新しく登録した順番に並びます。
                 </div>


                <table class="video_sort_table">

                    <tr>
                        <th style="width: 10%;text-align: center;font-size: 15px;">並び順</th>
                        <th style="width: 15%;text-align: center;font-size: 15px;">順番</th>
                        <th style="text-align: center;font-size: 15px;">タイトル</th>

                    </td>



                    <?php foreach ($sort_open_array as $row) {?>

                        <?php if($row["input_category"] == $_POST['category_id'] && $row["input_disp"] == 1){ ?>

                        <tr>
                            <td style="width: 10%;text-align: center;font-size: 15px;"><?php echo $count; $count++; ?></td>
                            <td style="width: 15%;text-align: center;font-size: 15px;">

                                <?php 
                                
                                    $sortnum = "";

                                    if( isset( $vide_add_array[  $row["input_id"] ] ))
                                    {
                                         if( get_field( 'item_add_vdeo_sort_num',$vide_add_array[  $row["input_id"] ]) != "")
                                         {
                                             $sortnum = get_field( 'item_add_vdeo_sort_num',$vide_add_array[  $row["input_id"] ]);
                                         }
                                    }
                                
                                ?>

                                <input type="number" name="sort_<?php echo $row["input_id"];?>" id="" value="<?php echo $sortnum; ?>"/>
                            </td>
                        

                            <td>

                                <div class="video_list_area_flrex">

                                    <div class="video_list_area_link">

                                          <?php echo $row["input_title"]; ?> <?php if($set_category_id != 20){ echo "（".$row["input_release_date"]."）";} ?>


                                     </div>

                                </div>


                            </td>
                        </tr>
                
                        <?php } ?>
                    <?php } ?>
                </table>

            </form>

        </div>




<?php } ?>




 <script>

 
//テーブル↑移動
function moveUp(btn) {
    const row = btn.closest('tr');
    const prevRow = row.previousElementSibling;
    if (prevRow && prevRow.tagName === 'TR') {
        row.parentNode.insertBefore(row, prevRow);
        updateRowColors();
    }
}
//テーブル↓移動
function moveDown(btn) {
    const row = btn.closest('tr');
    const nextRow = row.nextElementSibling;
    if (nextRow && nextRow.tagName === 'TR') {
        row.parentNode.insertBefore(nextRow, row);
        updateRowColors();
    }
}
//テーブルが移動しても色を入れなおす
function updateRowColors() {
    const rows = document.querySelectorAll('#adminSpiritEditTableID tbody tr');
    rows.forEach((row, index) => {
        if (index % 2 === 0) {
            row.classList.add('admin-spirit-edit-row');
        } else {
            row.classList.remove('admin-spirit-edit-row');
        }
    });
}



 </script>