<?php


    require_once("zoomClass.php");

    $user = wp_get_current_user();

    //クラス作成
    $input_data= new ZoomClass();

    //初期化
    $input_data->init();


    //削除
    if(isset($_POST['is_delete']) )
    {
        //var_dump($_POST);
        //削除
       $input_data->deleteZOOMData($_POST["id"]);

    }


    // データ取得クエリ実行
    $input_data->getListZoomRow($user->ID);

    // 配列を計算して出しなおす
    $rows = $input_data->zoom_row;

    //更新用変数
    $updata_str = "";



     if(!isset($_POST['is_details']) && !isset($_POST['is_join']) && !isset($_POST['is_mail']) && !isset($_GET['list']))
     {

        $disp_count = 0;

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

<div class="zoom-list-area">

    <div class="index_center">
        <font size="6">直近のミーティング一覧</font>
    </div>
    <?php if($rows){?>
        <table class="user-table">
            <colgroup span="6"></colgroup>
            <tr>
                <th class="fixed_th_1" style="text-align: center">詳細</th>
                <?php /*<th>出席</th>*/ ?>
                <th class="fixed_th_2" style="text-align: center">開始予定時間</th>
                <th class="fixed_th_3" style="text-align: center">カテゴリ</th>
                <th style="text-align: center">タイトル</th>
                <th style="text-align: center">URL</th>
                <?php /*<th>予定人数</th>*/ ?>
                <th style="text-align: center">予定人数</th>
            </tr>
            <?php foreach ($rows as $row) { $disp_count++;?>
            <?php
                if($disp_count < 30)
                {
                    $today = date("Y-M-d H:i:s", strtotime('+9hour'));
                    $target_day = date("Y-M-d H:i:s",strtotime($row->post_zoom_deadline));

                    $table_color = "";

                    $url_link = '<a href="'.$row->post_zoom_url .'"  target="_blank" rel="noopener noreferrer">' .$row->post_zoom_url .'</a>';

                    if( strtotime($today) > strtotime($target_day) ){
                        $table_color = 'bgcolor="darkgray"';
                        $back_table_color = 'style="background-color:darkgray"';
                        $url_link = $row->post_zoom_url;
                    }else{
                        $back_table_color = 'style="background-color:white"';
                    }
            ?>

            <tr <?php echo $table_color;?>>
                <td class="fixed_th_1" <?php echo $back_table_color;?>>
                    <form action="<?php  $id = 51; echo get_page_link( $id );?>" method="post">
                        <input type="hidden" name="id" value="<?php echo $row->ID;?>">
                        <input type="hidden" name="is_edit" value="edit">
                        <input type="submit" value="詳細">
                    </form>
                </td>
                <?php /*
                <td>
                    <?php  if( strtotime($today) > strtotime($target_day) ){ ?>
                        <form action="<?php  $id = 46; echo get_page_link( $id );?>" method="post">
                            <input type="hidden" name="id" value="<?php echo $row->ID;?>">
                            <input type="hidden" name="is_join" value="join">
                            <input type="submit" value="出席簿">
                        </form>
                     <?php }else{ ?>
                         <form action="<?php  $id = 46; echo get_page_link( $id );?>" method="post">
                            <input type="hidden" name="id" value="<?php echo $row->ID;?>">
                            <input type="hidden" name="is_mail" value="mail">
                            <input type="submit" value="メール">
                        </form>
                     <?php } ?>
                </td>
                  */ ?>

                <td class="fixed_th_2" <?php echo $back_table_color;?>><?php echo $row->post_zoom_start_day;?> </td>
                <td class="fixed_th_3" <?php echo $back_table_color;?>><?php echo $input_data->zoom_catagory_array_data[$row->post_zoom_category];?> </td>
                <td><?php echo $row->post_zoom_title;?> </td>
                <td><?php echo $url_link;?> </td>
                <?php /*<td><?php echo $input_data->getParticNum($row->post_zoom_participant);?>人 </td>*/ ?>
                <td><?php echo $input_data->getPlanNum($row->post_zoom_participant_plans);?>人 </td>
            </tr>
                <?php } ?>
            <?php } ?>
        </table>

        <p>
            <a class="index_button" href="<?php $id = 46; echo get_page_link( $id );?>?list=1">過去の講義一覧へ</a>
        </p>

     <?php }else{ ?>
        <div class="index_center">
            <?php echo  "データがありません"; ?>
        </div>
     <?php } ?>
</div>

<?php
        }elseif(isset($_POST['is_details'])){
?>


<?php
        }elseif(isset($_POST['is_join'])){




            //参加データのみを保存
            if(isset($_POST['is_save'])){

                //データのセット
                $input_data->setZoomData("input_join",$_POST['input_join']);

                //アップデート処理
                $updata = $input_data->upDataZoomJoineData($_POST['id']);

                if ( $updata == false ) {
	                $updata_str = "参加者の更新に失敗しました";
                } else {
	                $updata_str = "参加者を更新しました";
                }

                // 再更新
                $input_data->getListZoomRow($user->ID);
            }


?>
            <?php
                $users = get_users( array('orderby'=>'ID','order'=>'ASC') );

                // var_dump($users);

                //データ取得
                $input_data->setEditData($_POST);

                $id = 46;

                $return_list = get_page_link( $id );

                if(isset($_GET['list'])){
                    $return_list = get_page_link( $id ). "?list=" .$_GET['list'];
                }


                //echo $input_data->input_data["input_join"];



                if(isset($input_data->input_data["input_join"]))
                {
                    $zoom_join_data =$input_data->input_data["input_join"];
                }


                if(isset($input_data->input_data["input_plans"]))
                {
                    $zoom_plan_data =$input_data->input_data["input_plans"];
                }

            ?>
             <div class="zoom_title">
                <p><font size="6">ZOOM参加者チェック</font></p>


                <p>
                    <?php echo $input_data->input_data["input_zoom_title"];?>（公開日: <?php echo $input_data->input_data["input_zoom_day"];?>）
                    <a href="<?php echo $return_list;?>">一覧に戻る</a>
                </p>
             </div>

             <?php if($updata_str != ""){ ?>
                <div class="index_updata_center">
                    <p><?php echo $updata_str;?></p>
            </div>
            <?php }?>

             <form action="<?php  echo $return_list;?>" method="post">
             <?php /*
                 <p><input  class="index_history_button"type="submit" value="保存"></p>
                 */
              ?>
                <input type="hidden" name="is_join" value="join">
                <input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>">
                <input type="hidden" name="is_save" value="save">

                <table class="user-table-two"  width="650">
                    <colgroup span="6"></colgroup>
                    <tr>
                        <?php /*<th>参加・不参加</th>*/ ?>
                        <th>ID</th>
                        <th>名前</th>
                         <?php /*<th>参加予定</th>*/ ?>
                    </tr>
                    <?php foreach ($users as $row){?>
                        <?php

                            $plan_user = false;



                            if($zoom_plan_data)
                            {
                                if(count($zoom_plan_data) >= 1 && $zoom_plan_data[0] != "")
                                {
                                    for($i=0;$i<count($zoom_plan_data);$i++){
                                        if( $row->ID == $zoom_plan_data[$i]){
                                            $plan_user = true;
                                            break;
                                        }
                                    }
                                }
                            }



                    ?>

                    <?php if($plan_user){?>
                    <tr>
                        <?php /*<td><input type="checkbox" name="input_join[]"  value="<?php echo $row->ID;?>" <?php if( $join_user){echo "checked";} ?>></td>*/?>
                        <td><?php echo $row->ID;?></td>
                        <td><?php echo get_the_author_meta('last_name',$row->ID);?>　<?php echo get_the_author_meta('first_name',$row->ID);?>(<?php echo $row->user_login;?>)</td>
                        <?php /*<td><?php if($plan_user){ echo "参加予定";}else{ echo "参加予定なし";} ?></td>*/ ?>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                </table>
                 <?php /*<p><input  class="index_history_button"type="submit" value="保存"></p>*/ ?>
                </form>
<?php
        }elseif(isset($_POST['is_mail'])){
?>
            <?php
                $users = get_users( array('orderby'=>'ID','order'=>'ASC') );

                //データ取得
                $input_data->setEditData($_POST);

                //今月分のデータをセットする
                $input_data->getZoomMonthRow();

                $id = 46;

                $return_list = get_page_link( $id );

                if(isset($_GET['list'])){
                    $return_list = get_page_link( $id ). "?list=" .$_GET['list'];
                }


            ?>
             <div class="zoom_title">
                <p><font size="7">参加予定者にメールを送る</font></p>

                <p>
                    <?php echo $input_data->input_data["input_zoom_title"];?>（公開日: <?php echo $input_data->input_data["input_zoom_day"];?>）
                    <a href="<?php echo $return_list;?>" >一覧に戻る</a>
                </p>
             </div>

             <?php if($updata_str != ""){ ?>
                <div class="index_updata_center">
                    <p><?php echo $updata_str;?></p>
            </div>
            <?php }?>

             <form action="<?php  $id = 46; echo get_page_link( $id );?>" method="post">
             <?php /*
                 <p><input  class="index_history_button"type="submit" value="保存"></p>
                 */
              ?>
                <input type="hidden" name="is_join" value="join">
                <input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>">
                <input type="hidden" name="is_save" value="save">
                 <p><input  class="index_history_button"type="submit" value="メールを送信"></p>

                <table class="user-table-two">
                    <colgroup span="6"></colgroup>
                    <tr>
                        <th>メール送信<br>(参加予定)</th>
                        <th>ID</th>
                        <th>名前</th>

                    </tr>
                    <?php foreach ($users as $row){?>
                    <?php if( in_array($row->ID,(array)$input_data->input_data["input_plans"]) ){ ?>
                        <tr>

                            <td><input type="checkbox" name="input_plans[]"  value="<?php echo $row->ID;?>" <?php if( in_array($row->ID,(array)$input_data->input_data["input_plans"]) ){echo "checked";}?>></td>
                            <td><?php echo $row->ID;?></td>
                            <td><?php echo get_the_author_meta('last_name',$row->ID);?>　<?php echo get_the_author_meta('first_name',$row->ID);?>(<?php echo $row->user_login;?>)</td>
                        </tr>
                    <?php } ?>
                    <?php } ?>
                </table>

                </form>

<?php   }elseif(isset($_GET['list'])){ ?>

<?php
    if($rows)
    {
        $tabel_max = 10;

        $page_max = ceil(count($rows) / $tabel_max);

        $table_start = $_GET['list'];

        $table_page_max = $table_start + 3;
        $table_page_min = $table_start - 3;

        if($table_page_max > $page_max)
        {
            $table_page_max = $page_max;
        }

        if($table_page_min < 1)
        {
            $table_page_min = 1;
        }

        //表示配列を作ってしまう
        $table_array = $input_data->make_past_zoom_datatable($tabel_max,$rows);

        //var_dump($table_array);
        //$cnt = count($rows);
      //echo $page_max;
?>

    <div class="index_center">
        <font size="6">過去の講義一覧</font>
    </div>

    <table class="user-table">
        <colgroup span="6"></colgroup>
        <tr>
            <th>出席</th>
            <th>開催時間</th>
            <th>タイトル</th>
            <th>出席人数</th>
       </tr>
       <?php foreach ($table_array[$table_start] as $key =>$value) {?>
       <?php
            $today = date("Y-M-d H:i:s", strtotime('+9hour'));
            $target_day = date("Y-M-d H:i:s",strtotime($value["input_zoom_start_day"]));

            $table_color = "";

            $join_num = 0;



            if( strtotime($today) > strtotime($target_day) )
            {
                $table_color = 'bgcolor="darkgray"';

                 //if( $value["input_plans"] )
                 //{
                 //   $join_num =count($value["input_plans"]);
                // }

            }
            /*
            else{
                 if( $value["input_join"] )
                 {
                     $join_num =count($value["input_join"]);
                 }
            }
            */



            if( $value["input_plans"] )
            {
                $join_num =count($value["input_plans"]);
             }
       ?>

       <tr <?php echo $table_color;?> >
            <td>
               <form action="<?php  $id = 46; echo get_page_link( $id );?>?list=<?php echo $table_start;?>" method="post">
                <input type="hidden" name="id" value="<?php echo $value ["input_id"];?>">
                <input type="hidden" name="is_join" value="join">
                <input type="submit" value="出席簿">
               </form>
            </td>
            <td><?php echo $value ["input_zoom_start_day"];?> </td>
            <td><?php echo $value ["input_zoom_title"];?> </td>
            <td>
                    <?php echo $join_num;?>人
            </td>
       </tr>
       <?php } ?>
    </table>

    <div class="index_center">
        <ul class="table_page_nation">

            <?php if($table_start > 1){?>
                <a href="<?php $id = 46; echo get_page_link( $id );?>?list=<?php echo ($table_start - 1);?>"><li>前へ</li></a>
            <?php }else{ ?>
                <li>前へ</li>
            <?php   }?>

            <?php if($page_max >= $table_page_min){?>
                <?php for($i=$table_page_min;$i<=$page_max;$i++){?>
                    <?php if($table_start == $i){?>
                        <li class="table_page_nation_this" ><?php echo $i;?></li>
                    <?php }else{ ?>
                        <a href="<?php $id = 46; echo get_page_link( $id );?>?list=<?php echo $i;?>"><li><?php echo $i;?></li></a>
                    <?php } ?>
                <?php   }?>
            <?php   }?>

            <?php if($table_start < $page_max){?>
                <a href="<?php $id = 46; echo get_page_link( $id );?>?list=<?php echo ($table_start + 1);?>"><li>次へ</li></a>
            <?php }else{ ?>
                <li>次へ</li>
            <?php   }?>
        </ul>
    </div>
    <?php   }?>

     <p>
        <a class="index_button" href="<?php $id = 46; echo get_page_link( $id );?>">直近の会議一覧へ</a>
    </p>

<?php   }?>
