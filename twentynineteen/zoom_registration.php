<?php



    require_once("zoomClass.php");
    require_once("userClass.php");


    $user = wp_get_current_user();

    //クラス作成
    $input_data= new ZoomClass();
    $users_data= new UserClass();

    //初期化
    $input_data->init();

    //自分のメンバーレベルとメンバータイプを取得
    $member_level = get_the_author_meta('member_level',$user->ID);
    $member_type = get_the_author_meta('member_type',$user->ID);

    // データ取得クエリ実行
    $input_data->getListZoomRow($user->ID);

    //文字用の変数
    $insert_str = "";
    $updata_str = "";

     //保存
    if(isset($_POST['viewing_plan']) )
    {

        $input_data->upDataZoomPlansDataForID($_POST['id'],$user->ID);

         // データ取得クエリ実行
        $input_data->getListZoomRow($user->ID);
    }



if(isset($_POST["viewing_url"]))
{

    if(isset($_POST["id"]))
    {
        $input_data->getInputZoomForID($_POST["id"]);
    }

    if($input_data->zoom_row){

         foreach($input_data->zoom_row as $value){
?>

        <div class="index_center">
			<div class="top-index-txt">
			<p>
			    <font size="5">
                <?php if($value->post_zoom_category == ZoomClass::ZOOM_ZARABA_CATEGORY){?>ザラ場指導<?php } ?>
                <?php if($value->post_zoom_category == ZoomClass::ZOOM_KOUGI_CATEGORY){?>講義<?php } ?>

                「<?php echo $value->post_zoom_title; ?>」は【<?php echo $value->post_zoom_start_day;?>】から開催予定です</font><br>
                <p>
				    参加URL::<a href= "<?php echo $value->post_zoom_url;?>" target="_blank" ><?php echo $value->post_zoom_url;?></a>
                </p>
			</p>
			</div>
		</div>
        <?php }?>
    <?php }?>

    <p>
        <a class="index_button" href="<?php $id = 185; echo get_page_link( $id );?>">一覧に戻る</a>
    </p>
<?php
}elseif(!isset($_GET['zoom_list']) ){



     date_default_timezone_set('Asia/Tokyo');

     $now_year = date('Y');
     $now_month = date('m');
     $now_time = new DateTime(date('Y-M-d H:i:s'));


        //$now_time1 = date('Y-M-d H:i:s');





?>

<script type="text/javascript">
<!--

function add_check(){

	if(window.confirm('本当に参加して宜しいですか？\nOKした場合、月の参加回数に加算されます')){ // 確認ダイアログを表示
		return true; // 「OK」時は送信を実行
	}
	else{ // 「キャンセル」時の処理
		//window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止
	}
}

function add_check_kougi(){

	if(window.confirm('本当に参加して宜しいですか？')){ // 確認ダイアログを表示
		return true; // 「OK」時は送信を実行
	}
	else{ // 「キャンセル」時の処理
		//window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止
	}
}

// -->
</script>



        <?php $max_mtg_num = 5;?>
        <?php $add_mtg_num = 0;?>

        <?php $input_data->getZoomMonthRow(); //今月分を並べなおす?>
        <?php $plans_num = $input_data->checkUserZoomPlans($user->ID);//参加回数?>


        <?php if(get_the_author_meta('max_mtg_num',$user->ID) != NULL){
            $max_mtg_num = get_the_author_meta('max_mtg_num',$user->ID); //参加予定回数
        }?>

         <?php if(get_the_author_meta('add_mtg_num',$user->ID) != NULL){
            $add_mtg_num = get_the_author_meta('add_mtg_num',$user->ID); //追加回数
         }?>




        <?php if(get_the_author_meta('add_mtg_save_month',$user->ID) != NULL){
            $add_mtg_save_month = get_the_author_meta('add_mtg_save_month',$user->ID); //追加変更した月
        }?>

       <?php if(get_the_author_meta('add_mtg_save_year',$user->ID) != NULL){
            $add_mtg_save_year = get_the_author_meta('add_mtg_save_year',$user->ID);//追加変更した年
        }?>

        <?php
            //別の月になっていたらリセット
            if($now_year != $add_mtg_save_year || $now_month != $add_mtg_save_month)
            {
                $add_mtg_num = 0;
            }


         ?>


        <?php
            $enable_num = $max_mtg_num - $plans_num;//可能回数

            if( $member_level == UserClass::MONKASEI )
            {
                $enable_num = 999;
            }

        ?>


<p style="text-align:center;">
予定一覧に募集が表示されない場合はブラウザーの「更新」を押してください
</p>

 <?php

    foreach ($input_data->zoom_catagory_array_data as $key=>$value) {

        //カテゴリなしは飛ばす
        if($key == ZoomClass::ZOOM_NO_CATEGORY){continue;}

         //FXはザラ場が必要ない
        if($key == ZoomClass::ZOOM_ZARABA_CATEGORY && $member_level != UserClass::MONKASEI)
        {
            if($member_level == UserClass::KUNRENSEI && $member_type == UserClass::FX)
            {
                continue;
            }
        }

        //FX訓練生
        if( $member_type == UserClass::FX && $member_level != UserClass::MONKASEI)
        {
            if($key == ZoomClass::ZOOM_ZARABA_CATEGORY || $key == ZoomClass::ZOOM_KOUGI_CATEGORY)
            {
                 continue;
            }
        }

        //株訓練生
        if( $member_type == UserClass::KABU && $member_level != UserClass::MONKASEI)
        {
            if($key == ZoomClass::ZOOM_FX_KOUGI_CATEGORY)
            {
                 continue;
            }
        }

 ?>

    <div class="lecture_center">
        <?php if($key ==  ZoomClass::ZOOM_ZARABA_CATEGORY ){ //ザラ場のみ ?>


        <?php if( $member_level == UserClass::MONKASEI ){ ?>
            <p>
                <div class="index_center">
                    <font size="5">門下生の参加制限はありません</font>
                </div>
            </p>

        <?php }else{ ?>
            <p>
                <div class="index_center">
                    <font size="5">今月、ザラ場指導に参加できるのは残り<font color="red"><?php echo ($enable_num + $add_mtg_num);?>回<?php if($add_mtg_num > 0){ echo "(追加 " .$add_mtg_num ."回)"; }?></font>となります</font>
                </div>
            </p>
        <?php } ?>
        <?php }elseif($key ==  ZoomClass::ZOOM_KOUGI_CATEGORY ){ //講義のみ ?>
        <p>
            <div class="index_center">
                <font size="5">講義の参加に回数制限はありません</font>
            </div>
        </p>

        <?php }?>

    <table class="lecture-table">
        <colgroup span="1"></colgroup>

        <tr>
            <th  colspan="7"><?php echo $value;?> 開催予定一覧</th>
        </tr>

        <tr style="background-color:lemonchiffon;">
            <td><?php echo "申込"; ?></td>
            <td><?php echo "タイトル"; ?></td>
            <td><?php echo "募集"; ?>　/ <font color="red"><?php echo "締切"; ?></font><?php echo "時間"; ?></td>
            <td><?php echo "講義時間"; ?></td>
            <?php /*<td><?php echo "講義時間"; ?></td>*/ ?>
            <?php /*<td><?php echo "募集"; ?></td>*/ ?>
            <td><?php echo "予定"; ?></td>

        </tr>



        <?php
            if($input_data->zoom_row){
                foreach ($input_data->zoom_row as $row) {

        ?>

            <?php if($row->post_zoom_category ==  $key){

                    $year_m =  date('Y',  strtotime($row->post_zoom_day));
                    $hour_m =  date('m',  strtotime($row->post_zoom_day));
                    $recruitment_time = new DateTime($row->post_zoom_day); //募集開始時間


                    //訓練生は見れない
                    if( $row->post_zoom_jointype == ZoomClass::ZOOM_JOIN_NO_KUNRENSEI && $member_level == UserClass::KUNRENSEI)
                    {
                        continue;
                    }

                    //門下生は見れない(基本的に全部見れるのでマスク)
                    if( $row->post_zoom_jointype == ZoomClass::ZOOM_JOIN_NO_MONKASEI && $member_level == UserClass::MONKASEI)
                    {
                        //continue;
                    }

                    //訓練生と動画性見れない
                    if( $row->post_zoom_jointype == ZoomClass::ZOOM_JOIN_NO_KUNRENSEI_DOUGA && $member_level ==  UserClass::KUNRENSEI)
                    {
                        continue;
                    }
                    else if( $row->post_zoom_jointype == ZoomClass::ZOOM_JOIN_NO_KUNRENSEI_DOUGA && $member_level ==  UserClass::DOGA)
                    {
                        continue;
                    }

                     //動画閲覧は見れない
                    if( $row->post_zoom_jointype == ZoomClass::ZOOM_JOIN_NO_DOUGA && $member_level == UserClass::DOGA)
                    {
                        continue;
                    }







                    if((($now_year == $year_m && $hour_m == $now_month ) || ($now_year < $year_m) ||    ($now_year == $year_m && $hour_m > $now_month )) && ($recruitment_time < $now_time))
                    {

                        $start_date = new DateTime( date("Y-M-d H:i:s", strtotime($row->post_zoom_start_day . "+4 hour")));
                        $deadline_date = new DateTime( date("Y-M-d H:i:s", strtotime($row->post_zoom_deadline)));


                         //参加予定かどうかを確認
                         $plan_input_value = 1;
                         $zoom_time = $deadline_date;

                         if(isset($row->post_zoom_participant_plans))
                         {
                            $zoom_plan_data =explode(",",$row->post_zoom_participant_plans);

                            for($i=0;$i<count($zoom_plan_data);$i++){
                                if( $user->ID == $zoom_plan_data[$i]){
                                    $plan_input_value = 0;
                                    $zoom_time = $start_date;
                                    break;
                                }
                            }
                        }

                         //echo $zoom_time->format('Y-m-d H:i:s');

                        $join_now = "募集中";
                        $table_color = "";

                        if($zoom_time < $now_time)
                        {
                            $table_color = 'bgcolor="darkgray"';
                            $join_now = "締切";
                        }

                        if($plan_input_value == 1)
                        {
                            $plan_str = "参加予定なし";
                            $plan_input_str = "参加する";
                        }
                        else{
                            $plan_str = "参加予定";
                        }
            ?>

                        <tr <?php echo $table_color;?>>



                             <?php  if($zoom_time < $now_time){  ?>

                                <td><?php echo "締切"; ?></td>

                            <?php }else{ ?>

                                <td>
                                    <?php if($plan_input_value == 0){?>
                                         <form action="<?php  $id = 185; echo get_page_link( $id );?>" method="post">
                                             <input type="hidden" name="id" value="<?php echo $row->ID;?>">
                                             <input type="hidden" name="viewing_url" value="viewing_url">
                                             <input type="submit" value="URL確認"  style="background-color: rosybrown;">
                                         </form>
                                    <?php }else{ ?>
                                        <?php if($enable_num + $add_mtg_num > 0 || $key ==  ZoomClass::ZOOM_KOUGI_CATEGORY){?>
                                            <?php if( $key ==  ZoomClass::ZOOM_KOUGI_CATEGORY){ ?>
                                                <form action="<?php  $id = 185; echo get_page_link( $id );?>" method="post"  onSubmit="return add_check_kougi()">
                                            <?php }else{ ?>
                                                <form action="<?php  $id = 185; echo get_page_link( $id );?>" method="post"  onSubmit="return add_check()">
                                            <?php } ?>
                                                <input type="hidden" name="id" value="<?php echo $row->ID;?>">
                                                <input type="hidden" name="viewing_plan" value="<?php echo $plan_input_value;?>">
                                                <input type="submit" value="<?php echo $plan_input_str;?>">
                                            </form>
                                        <?php }else{ ?>
                                            参加できません
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                            <?php } ?>


                            <td><?php echo $row->post_zoom_title; ?></td>
                            <td><?php echo date('Y/m/d H:i',  strtotime($row->post_zoom_day)); ?>　/　<font color="red"><?php echo date('H:i',  strtotime($row->post_zoom_deadline)); ?></font></td>
                            <td><?php echo date('Y/m/d H:i',  strtotime($row->post_zoom_start_day)); ?></td>
                            <?php /*<td><?php echo date('Y年m月d日 H時i分',  strtotime($row->post_zoom_start_day)); ?></td>
                            <td><?php echo $join_now; ?></td>*/?>



                            <?php  if($zoom_time < $now_time){

                                $join_str = "不参加";

                                if(isset($row->post_zoom_participant_plans))
                                {


                                    $zoom_join_data =explode(",",$row->post_zoom_participant_plans);

                                    for($i=0;$i<count($zoom_join_data);$i++){
                                        if( $user->ID == $zoom_join_data[$i]){
                                            $join_str = "参加";break;
                                        }
                                    }
                                }
                            ?>

                                <td><?php echo $join_str; ?></td>

                            <?php }else{ ?>



                                <td><?php echo $plan_str; ?></td>

                            <?php } ?>
                        </tr>
                <?php } ?>
            <?php } ?>
        <?php } ?>
        <?php } ?>
    </table>

</div>
<?php } ?>



<?php } ?>
