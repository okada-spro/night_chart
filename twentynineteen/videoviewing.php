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
    $input_data->checkCategoryNum($user_level,$user_member_type);

   function setVideoLink( $data_array, $set_day_count , $set_now_month)
   {
?>
        <td  class="calendar-td" bgcolor="darkorange">
            <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post" class="calendar-form" name="cl_form<?php echo $set_day_count;?>" >
                <input type="hidden" name="id" value="<?php echo $data_array[$set_day_count]["input_id"];?>">
                <input type="hidden" name="viewing" value="viewing">
                 <input type="hidden" name="set_day_count " value="<?php echo $set_day_count;?>">
                <input type="hidden" name="video_month" value="<?php echo $set_now_month;?>">
                <input type="hidden" name="vimeo-id" value="<?php echo $data_array[$set_day_count]["input_flame"];?>">
                <a href="javascript:document.cl_form<?php echo $set_day_count;?>.submit()" class ="calendar-a"><?php echo $set_day_count; ?></a>
               
           </form>
       </td>
       

<?php
   }

if($input_data->view_row){



    if(isset($_POST['viewing']) )
    {

        //var_dump($_POST);

        //視聴(データ取得)
        $input_data->setEditData($_POST);

        $make_url = "https://player.vimeo.com/video/" .$input_data->input_data["input_flame"];
        $make_width = $input_data->input_data["input_flame_width"];
        $make_height = $input_data->input_data["input_flame_height"];

        if($input_data->input_data["input_disp"] == 1 || current_user_can('administrator'))
        {
?>
    <div class="index_center">
        <?php echo  $input_data->input_data["input_title"]; ?>
    </div>
    <div class="viewing_div">
        <iframe src=<?php echo $make_url;?> width="<?php echo $make_width;?>" height="<?php echo $make_height;?>" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
    </div>
<?php
        }else{
?>
            
<?php
        }
?>


    <?php if(isset($_POST['video_month'])){?>

         <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
            <input type="hidden" name="video_month" value="<?php echo $_POST['video_month'];?>">
            <input type="hidden" name="category_id" value="7">
            <input type="hidden" name="viewing_category" value="viewing_category">
            <input type="submit" value="動画一覧へ"   class="index_updata_button">
        </form>

    <?php }else{ ?>
         <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
            <input type="hidden" name="category_id" value="<?php echo $_POST['category_id'];?>">
            <input type="hidden" name="viewing_category" value="viewing_category">
            <input type="submit" value="動画一覧へ"   class="index_updata_button">
        </form>
    <?php } ?>

     <?php if(current_user_can('administrator')){ ?>
     <p>
          <a class="index_updata_button" href="<?php $id = 54; echo get_page_link( $id );?>">動画管理一覧へ</a>
     </p>
     <?php } ?>
<?php
    }
    else if(isset($_POST['viewing_category']) &&  $_POST['category_id'] == 7 )
    {
        $cl_year =  date("Y");
        $cl_month =  date("m");

         //株ザラ場
        if(isset($_POST['video_month']))
        {
             $dates = explode("-",$_POST["video_month"]);

            $cl_year = intval($dates[0]);
            $cl_month = intval($dates[1]);
        }
       
        //前の月は？
        $cl_befor_month = $cl_month - 1;
        $cl_befor_year = $cl_year;

        if($cl_befor_month <= 0){
            $cl_befor_month = 12;
            $cl_befor_year -= 1; 
        }

        //次の月は？
        $cl_after_month = $cl_month + 1;
        $cl_after_year = $cl_year;

        if($cl_after_month > 12){
            $cl_after_month = 1;
            $cl_after_year += 1; 
        }


        //今月
        $now_month = $cl_year."-".$cl_month;
       

        $input_value = $cl_year."-".sprintf('%02d', $cl_month);


        $input_data->getCategoryIDViewRow(7,$cl_year,$cl_month);


        $lastday = date( 't' , strtotime($cl_year . "/" . $cl_month . "/01"));

        $date = $cl_year."-".$cl_month."-01";
        $datetime = new DateTime($date);
        $week = array("日", "月", "火", "水", "木", "金", "土");
        $w = (int)$datetime->format('w');

        //echo $week[$w];
        //echo $lastday;

        $day_count = 1;
?>
    <div class="page_div_box">
    <p>
        <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
            <input type="month" name="video_month" value="<?php echo $input_value;?>"  class="same-user-select">
            <input type="hidden" name="category_id" value="7">
            <input type="hidden" name="viewing_category" value="viewing_category">
            <input type="submit" value="選択月に移動"   class="same-user-select">
        </form>
    </p>
    </div>

     
    <div class="calendar-container">
   
            <div class="vimeo-top-table-container">
                <table class="video-top-table">
                <td>
                    <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
                        <input type="hidden" name="video_month" value="<?php echo $cl_befor_year."-".$cl_befor_month;?>">
                        <input type="hidden" name="category_id" value="7">
                        <input type="hidden" name="viewing_category" value="viewing_category">
                        <input type="submit" value="<?php echo $cl_befor_month;?>月" id="submit_month">
                    </form>
                </td>

                <td><div class="calendar-h1"><?php echo $cl_year?>年<?php echo $cl_month?>月</div></td>

                <td>
                    <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
                        <input type="hidden" name="video_month" value="<?php echo $cl_after_year."-".$cl_after_month;?>">
                        <input type="hidden" name="category_id" value="7">
                        <input type="hidden" name="viewing_category" value="viewing_category">
                        <input type="submit" value="<?php echo $cl_after_month;?>月" id="submit_month">
                    </form>
            </td>
            </table>
        </div>

        <table class="calendar">
            <tr>
                <th class="calendar-th">日</th>
                <th class="calendar-th">月</th>
                <th class="calendar-th">火</th>
                <th class="calendar-th">水</th>
                <th class="calendar-th">木</th>
                <th class="calendar-th">金</th>
                <th class="calendar-th">土</th>
            </tr>
        <?php //一列目 ?>
        <?php $srart_day = false; ?>

        <tr  class="day">
            <?php for($i=0;$i<7;$i++){ ?>
                <?php if(!$srart_day){ ?>

                    <?php if($i==$w){$srart_day = true; ?>
                         <?php if(isset($input_data->view_category_view_row[$day_count])){ ?>
                            <?php setVideoLink($input_data->view_category_view_row,$day_count,$now_month);?>
                            <?php $day_count++; ?>
                        <?php }else{ ?>
                            <?php echo $input_data->getVideoLink($day_count);$day_count++; ?>
                        <?php } ?>

                    <?php }else{ ?>
                        <td class="calendar-td"></td>
                    <?php }?>

                 <?php }else{ ?>

                    <?php if(isset($input_data->view_category_view_row[$day_count])){ ?>
                        <?php setVideoLink($input_data->view_category_view_row,$day_count,$now_month);?>
                        <?php $day_count++; ?>
                    <?php }else{ ?>
                           <?php echo $input_data->getVideoLink($day_count);$day_count++; ?>
                     <?php } ?>
                 <?php }?>
            <?php } ?>
        </tr>
        <?php //二列目以降 ?>
        <?php
            $gyou = 0;

            for($j=0;$j<7;$j++){
                if($lastday >= $day_count)
                {
            ?>
                <tr  class="day">
                    <?php for($i=0;$i<7;$i++){ ?>
                        <?php if($lastday >= $day_count){ ?>
                             <?php if(isset($input_data->view_category_view_row[$day_count])){ ?>
                               <?php setVideoLink($input_data->view_category_view_row,$day_count,$now_month);?>
                            <?php }else{ ?>
                                <?php echo $input_data->getVideoLink($day_count); ?>
                            <?php } ?>
                       <?php } ?>
                       <?php $day_count++; ?>
                    <?php }?>
                </tr>
            <?php

                }
            }
        ?>


    </table>
    </div>



<?php
    }
    else if(isset($_POST['viewing_category']) &&  $_POST['category_id'] != 7 )
    {
        //カテゴリ表示


        $open_array = $input_data->setOpenData(false);

    //var_dump($open_array);
  
                    
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
$(function(){
   // $("#button1").click(
        //function(){
    $('.vimeo-thumbnail-item').each(function() {
       
        
        /*
        function(){
            // vimeoのサムネイル画像表示処理
            //$('.js-vimeo-thumbnail-item').each(function() {
 */
        // リストアイテム取得
         var $this = $(this);

        var vimeoId = $this.find('.js-vimeo-thumbnail-id').val();
 
        // vimeoID取得
        var vimeoURL = "https://vimeo.com/api/oembed.json?url=https://vimeo.com/" + vimeoId;//$this.find('.js-vimeo-thumbnail-id').val();
 
        // JSON取得
        $.getJSON(
            vimeoURL,
            null,
            function(data) {
                // thumbnailURL格納
                $this.find('.js-vimeo-thumbnail-img').attr("src", data.thumbnail_url);
                // ついでにタイトルもとってくる
                $this.find('.js-vimeo-thumbnail-title').text(data.title);

                
            }
        );
 
    });


});

</script>

    <?php

         if(isset($_POST["disp_table"])){
             update_user_meta($user->ID, 'video_disp_type', $_POST['disp_table']);

         }

         $set_category_id = "";

         if(isset($_POST["category_id"]))
         {
            $set_category_id = $_POST["category_id"];
         }

         $disp_type = get_the_author_meta('video_disp_type',$user->ID);

         if($disp_type == "" || $disp_type == NULL)
         {
             $disp_type = 0;
         }
    
    ?>

    <div class="index_center">
    <p>
        <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
            <select name="disp_table"  class="same-user-select">
            <?php foreach ($input_data->disp_array_data as $key => $value) {?>
                <option value="<?php echo $key;?>" <?php if($disp_type == $key){ echo "selected";}?>><?php echo $value;?></option>
            <?php } ?>
            </select>

            <input type="hidden" name="viewing_category" value="viewing_category">
            <input type="hidden" name="category_id" value="<?php echo  $_POST['category_id']; ?>">
            <input type="submit" value="表示変更"   class="same-user-select">
        </form>
    </p>
    </div>



    <?php if($disp_type == 0){ //サムネイル形式 ?>

    <div class="vimeo-thumbnail-range">
        <div class="vimeo-thumbnail-container">
            <?php foreach ($open_array as $row) {?>
                <?php if($row["input_category"] == $_POST['category_id'] && $row["input_disp"] == 1){?>
                
                    <div class="vimeo-thumbnail-list">
                        <div class="ileUep">
                            <div class="vimeo-thumbnail-item">
                                <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
                                    <input type="hidden" name="id" value="<?php echo $row["input_id"];?>">
                                    <input type="hidden" name="viewing" value="viewing">
                                    <input type="hidden" name="category_id" value="<?php echo  $_POST['category_id']; ?>">
                                    <input type="hidden" name="vimeo-id" value="<?php echo $row["input_flame"];?>" class="js-vimeo-thumbnail-id">
                                    <input type="image"  src="<?php echo content_url() ."/uploads/"; ?>320x180.png" alt=""  class="vimeo-thumbnail-img js-vimeo-thumbnail-img" />
                                </form>
                               <?php /* <div class="js-vimeo-thumbnail-title"><?php echo $row["input_title"]; ?></div>
                                <div class="js-vimeo-thumbnail-release"><?php echo $row["input_release_date"]; ?></div>*/ ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
     </div>
    <?php }else{ ?>


        <table class="video_list_table">
            <?php foreach ($open_array as $row) {?>
                <?php if($row["input_category"] == $_POST['category_id'] && $row["input_disp"] == 1){?>
               
                <tr>
                    <td>
                        <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post" name="cl_list<?php echo $row["input_id"];?>">
                            <div class="vimeo-thumbnail-item">
                                <input type="hidden" name="id" value="<?php echo $row["input_id"];?>">
                                <input type="hidden" name="viewing" value="viewing">
                                <input type="hidden" name="category_id" value="<?php echo  $_POST['category_id']; ?>">
                                <input type="hidden" name="vimeo-id" value="<?php echo $row["input_flame"];?>" class="js-vimeo-thumbnail-id">
                                <img src="<?php echo content_url() ."/uploads/"; ?>320x180.png"  class="vimeo-thumbnail-list-img js-vimeo-thumbnail-img" >
                                <a href="javascript:document.cl_list<?php echo $row["input_id"];?>.submit()" class ="calendar-a"><?php echo $row["input_title"]; ?> <?php if($set_category_id != 20){ echo "（".$row["input_release_date"]."）";} ?></a>
                                
                            </div>
                        </form>
                    </td>
                </tr>
                
                <?php } ?>
            <?php } ?>
        </table>
    <?php } ?>
    <p>
        <a class="index_button" href="<?php $id = 134; echo get_page_link( $id );?>">カテゴリ選択に戻る</a>
    </p>
<?php 
    } else{
        

 ?>

    <table class="user-table-two">
        <colgroup span="3"></colgroup>
        <tr>
            <th>ボタン</th>
            <th>カテゴリー名</th>
            <th>本数</th>
        </tr>
       <?php foreach ($input_data->view_category_row as $row) { ?>
            <?php if($input_data->view_category_number[$row->ID] > 0){?>
                <tr>
                    <td>
                        <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
                            <input type="hidden" name="category_id" value="<?php echo  $row->ID;?>">
                            <input type="hidden" name="viewing_category" value="viewing_category">
                            <input type="submit" value="一覧">
                        </form>
                    </td>
                    <td><?php echo $row->post_category_name; ?></td>
                    <td><?php echo $input_data->view_category_number[$row->ID]; ?>本</td>
                </tr>
            <?php } ?>
        <?php } ?>
    </table>

     <p>
        <a class="index_button" href="<?php $id = 134; echo get_page_link( $id );?>">カテゴリ選択に戻る</a>
    </p>

 <?php 
    }
 ?>
<?php 
}else{ ?>
    <div class="index_center">
        <p>データが存在しません</p>
<?php 
} 
?>