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
        //視聴完了を保存
        if(isset($_POST['complete']) )
        {
            $input_data->setVideoComplete( $user->ID ,  $_POST['id']  , $_POST['category_id']  );
        }

        //完了データを取得
        $complete_array = $input_data->getVideoComplete( $user->ID );


        //var_dump($_POST);

        //視聴(データ取得)
        $input_data->setEditData($_POST);

        $make_url = "https://player.vimeo.com/video/" .$input_data->input_data["input_flame"];
        $make_width = $input_data->input_data["input_flame_width"];
        $make_height = $input_data->input_data["input_flame_height"];
        $youtube_id = $input_data->input_data["input_youtube_id"];


        //閲覧禁止
        if(7 ==  $input_data->input_data["input_category"] && $user_member_type == 2 && $user_level != UserClass::MONKASEI)
        {

            echo "<div style='text-align: center;'>この動画は閲覧できません</div>";

            return;
        }

        if( isset($input_data->view_category_number[$input_data->input_data["input_category"]]) )
        {
            if($input_data->view_category_number[$input_data->input_data["input_category"]] == 0)
            {
                echo "<div style='text-align: center;'>この動画は閲覧できません</div>";

                return;
            }
        }
        


        if(($input_data->input_data["input_disp"] == 1 || current_user_can('administrator')) &&  $input_data->input_data["input_title"] != "")
        {
?>
    <div class="index_center">
        <?php echo  $input_data->input_data["input_title"]; ?>
    </div>
    <div class="viewing_div">

        <?php //if(current_user_can('administrator')){?>

            <?php if($input_data->input_data["input_video_type"] == 1  ){ ?>


                <div class="video-youtube-flame">

                    <iframe width="640" height="360" src="https://www.youtube.com/embed/<?php echo $youtube_id;?>?showinfo=0&modestbranding=1" title="<?php echo  $input_data->input_data["input_title"]; ?>" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
            
                </div>

            <?php }else{ ?>

                <iframe src=<?php echo $make_url;?> width="<?php echo $make_width;?>" height="<?php echo $make_height;?>" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>

            <?php } ?>
        <?php /* }else{ ?>
            <iframe src=<?php echo $make_url;?> width="<?php echo $make_width;?>" height="<?php echo $make_height;?>" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
        <?php }*/ ?>
    </div>


    <?php if($input_data->input_data["input_category"] != 7   &&  $input_data->input_data["input_title"] != "" ){ ?>


        <div class="video-youtube-complete-button-area">

            <?php if( !isset( $complete_array[ $_POST['category_id'] ] ) || !isset( $complete_array[ $_POST['category_id'] ][ $input_data->input_data["input_id"] ] ) ){ ?>


                 <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
                    <input type="hidden" name="id" value="<?php echo $input_data->input_data["input_id"];?>">
                    <input type="hidden" name="viewing" value="viewing">
                    <input type="hidden" name="complete" value="complete">
                    <input type="hidden" name="category_id" value="<?php echo  $_POST['category_id']; ?>">
                    <input type="submit" value="動画視聴完了する"   class="">
                 </form>

            <?php }else{ ?>

                <div class="video-youtube-complete-end-button">
                    <input type="submit" value="動画視聴完了" style="background-color: crimson;cursor: none;">
                 </div>

            <?php } ?>

        </div>


    <?php } ?>

<?php
        }else{
?>
            
<?php
        }
?>

    <?php 
    
        $test_info =  wp_get_current_user();


        $title_name = "";

        foreach( $input_data->view_category_row as $key => $value)
        {
            if(isset($_POST['video_month']))
            {
                
                if(7 ==  $_POST['category_id'])
                {
                    $title_name = $value->post_category_name;
                    break;
                }
            }
            else
            {
                if($value->ID ==  $_POST['category_id'])
                {
                    $title_name = $value->post_category_name;
                    break;
                }
            }
        }

        $categorys_id = 7;

        if(!isset($_POST['video_month']))
        {
            $categorys_id = $_POST['category_id'];
        }
       

        $open_array = $input_data->setOpenData(false);
             //動画全体から
        $video_array = array();

       

        $vimeo_key = "";

        $disp_count = 0;

        foreach( $open_array as $key => $value)
        {

            if( $value["input_category"] == $categorys_id &&  $value["input_disp"] == 1)
            {

                array_push($video_array , $value);

           //var_dump($value);
           
                if( $value["input_id"] == $input_data->input_data[ "input_id" ] )
                {
                    $vimeo_key =$disp_count;
                }

                $disp_count++;
            }
        }

        

        //$open_array = $input_data->setOpenData(false);

        //var_dump($video_array);

        if($user->ID == 19)
        {
            var_dump($_POST);
            echo $_POST['category_id'];
        }
        
?>


    <?php if($video_array  &&  $input_data->input_data["input_title"] != ""){?>


        <div class="video-next-button-area">

             <div class="video-next-button-area-constens">

                 <div class="video-next-button-left">

                    <?php if( isset( $video_array[ $vimeo_key + 1 ] ) ){?>

                        <?php $after_video = $video_array[ $vimeo_key + 1 ]; ?>

                        <?php if( $input_data->input_data["input_category"] != 26){ //このIDのものは表示しない?>
                            <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
                                <input type="hidden" name="id" value="<?php echo $after_video["input_id"];?>">
                                <input type="hidden" name="viewing" value="viewing">
                                <input type="hidden" name="category_id" value="<?php echo  $after_video['input_category']; ?>">
                                <input type="hidden" name="vimeo-id" value="<?php echo $after_video["input_flame"];?>" >
                                <input type="submit" value="前の動画へ"   class="">
                            </form>
                        <?php } ?>

                    <?php }else{ ?>

                        <input type="submit" value="前の動画へ"  style="background-color: darkgrey;">

                    <?php } ?>

                 </div>


                 <div class="video-next-button-center">

                 <?php if(isset($_POST['video_month']) || $input_data->input_data["input_category"] == 7 ){?>


                    <?php 
                    
                        $video_month = "";
                        

                        if(isset($_POST['video_month']))
                        {
                            $video_month = $_POST['video_month'];
                        }
                        else{

                            //ない場合はタイトルからとってくる
                            $tittle_seq =  explode('/', $input_data->input_data["input_title"]);


                            $video_month = $tittle_seq[0] . "-" .$tittle_seq[1];
                        }
                    
                    
                    ?>


                    <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
                        <input type="hidden" name="video_month" value="<?php echo $video_month;?>">
                        <input type="hidden" name="category_id" value="7">
                        <input type="hidden" name="viewing_category" value="viewing_category">
                        <input type="submit" value="ザラ場　動画一覧へ"   class="">
                    </form>

                 <?php }else{ ?>
                    <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
                        <input type="hidden" name="category_id" value="<?php echo $_POST['category_id'];?>">
                        <input type="hidden" name="viewing_category" value="viewing_category">
                        <input type="submit" value="<?php echo $title_name;?>　動画一覧へ"   class="">
                    </form>
                 <?php } ?>



                 </div>

                 <div class="video-next-button-right">

                    <?php if( $vimeo_key > 0 ){?>

                        <?php $next_video = $video_array[ $vimeo_key - 1 ]; ?>

                        <?php if( $input_data->input_data["input_category"] != 26){ //このIDのものは表示しない?>
                            <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
                                <input type="hidden" name="id" value="<?php echo $next_video["input_id"];?>">
                                <input type="hidden" name="viewing" value="viewing">
                                <input type="hidden" name="category_id" value="<?php echo  $next_video['input_category']; ?>">
                                <input type="hidden" name="vimeo-id" value="<?php echo $next_video["input_flame"];?>" >
                                <input type="submit" value="次の動画へ"   class="">
                            </form>
                        <?php } ?>

                    <?php }else{ ?>

                        <?php if( $input_data->input_data["input_category"] != 26){ //このIDのものは表示しない?>
                            <input type="submit" value="次の動画へ"   style="background-color: darkgrey;">
                         <?php } ?>
                    <?php } ?>

                 </div>

             </div>

         
        </div>

    <?php }else{ ?>


        <div style="text-align: center;margin-top: 50px;font-size: 32px;">
            こちらの動画は存在しません。

            <form action="https://nightchart.jp/" method="post">
                <input type="submit" value="TOPに戻る"   class="" style="margin-top: 20px;">
            </form>
        </div>


    <?php } ?>

<?php
          
        
    
    ?>


    <?php /*>

    <?php if(isset($_POST['video_month'])){?>

         <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
            <input type="hidden" name="video_month" value="<?php echo $_POST['video_month'];?>">
            <input type="hidden" name="category_id" value="7">
            <input type="hidden" name="viewing_category" value="viewing_category">
            <input type="submit" value="ザラ場　動画一覧へ"   class="index_updata_button">
        </form>

    <?php }else{ ?>
         <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
            <input type="hidden" name="category_id" value="<?php echo $_POST['category_id'];?>">
            <input type="hidden" name="viewing_category" value="viewing_category">
            <input type="submit" value="<?php echo $title_name;?>　動画一覧へ"   class="index_updata_button">
        </form>
    <?php } ?>

    */?>
     <?php if(current_user_can('administrator')){ ?>
     <p>
          <a class="index_video_updata_button" href="<?php $id = 54; echo get_page_link( $id );?>">動画管理一覧へ</a>
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

    <div class="vimeo-calendar-button-area">
        <a href="<?php $id = 134; echo get_page_link( $id );?>">カテゴリ選択に戻る</a>
    </div>

<?php
    }
    else if(isset($_POST['viewing_category']) &&  $_POST['category_id'] != 7 )
    {
        //カテゴリ表示
        $open_base_array = $input_data->setOpenData(false);
        $vide_add_array =  $input_data->getAddVideo( );//追加用のデータ

        //順番に上から入れていく
        $open_array = $input_data->setSortVideoArray( $open_base_array , $vide_add_array );

         //完了データを取得
        $complete_array = $input_data->getVideoComplete( $user->ID );


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

    <p>
        <a class="index_button" href="<?php $id = 134; echo get_page_link( $id );?>">カテゴリ選択に戻る</a>
    </p>

    <?php if($disp_type == 0){ //サムネイル形式 ?>

    <div class="vimeo-thumbnail-range">
        <div class="vimeo-thumbnail-container">
            <?php foreach ($open_array as $row) {?>
                <?php if($row["input_category"] == $_POST['category_id'] && $row["input_disp"] == 1){?>
                
                    <div class="vimeo-thumbnail-list">
                        <div class="ileUep"   <?php if($complete_array[  $_POST['category_id'] ][ $row["input_id"] ] == 1){?> style="background-color: blue;" <?php }?>>
                            <div class="vimeo-thumbnail-item">
                                <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
                                    <input type="hidden" name="id" value="<?php echo $row["input_id"];?>">
                                    <input type="hidden" name="viewing" value="viewing">
                                    <input type="hidden" name="category_id" value="<?php echo  $_POST['category_id']; ?>">
                                    <input type="hidden" name="vimeo-id" value="<?php echo $row["input_flame"];?>" class="js-vimeo-thumbnail-id">
                                    <?php if( $row["input_video_type"] == 1  ){ ?>
                                         <input type="image"  src="https://img.youtube.com/vi/<?php echo $row["input_youtube_id"]; ?>/mqdefault.jpg"  class="vimeo-thumbnail-img js-vimeo-thumbnail-img" >
                                    <?php }else{ ?>
                                        <input type="image"  src="<?php echo content_url() ."/uploads/"; ?>320x180.png" alt=""  class="vimeo-thumbnail-img js-vimeo-thumbnail-img" />
                                    <?php } ?>
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

                <?php if($row["input_category"] == $_POST['category_id'] && $row["input_disp"] == 1){ ?>

                
               
                <tr>
                    <td>

                        <div class="video_list_area_flrex">

                            <div class="video_list_area_link">

                                <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post" name="cl_list<?php echo $row["input_id"];?>">

                                    <?php 
                
                                        //echo $user->ID;


                                        /*if($user->ID == 1)
                                        {
                                            echo $row["input_youtube_id"];
                                        }
                                        */
                                    ?>

                                    <div class="vimeo-thumbnail-item">
                                        <input type="hidden" name="id" value="<?php echo $row["input_id"];?>">
                                        <input type="hidden" name="viewing" value="viewing">
                                        <input type="hidden" name="category_id" value="<?php echo  $_POST['category_id']; ?>">
                                        <input type="hidden" name="vimeo-id" value="<?php echo $row["input_flame"];?>" class="js-vimeo-thumbnail-id">
                                        <?php if( $row["input_video_type"] == 1  ){ ?>
                                            <img src="https://img.youtube.com/vi/<?php echo $row["input_youtube_id"]; ?>/mqdefault.jpg"  class="vimeo-thumbnail-list-img js-vimeo-thumbnail-img" >
                                        <?php }else{ ?>
                                            <img src="<?php echo content_url() ."/uploads/"; ?>320x180.png"  class="vimeo-thumbnail-list-img js-vimeo-thumbnail-img" >
                                        <?php } ?>
                                        <a href="javascript:document.cl_list<?php echo $row["input_id"];?>.submit()" class ="calendar-a"><?php echo $row["input_title"]; ?> <?php if($set_category_id != 20){ echo "（".$row["input_release_date"]."）";} ?></a>
                                    </div>
                                </form>

                             </div>


                             <?php if($complete_array[  $_POST['category_id'] ][ $row["input_id"] ] == 1){?>
                                 <div class="video_list_area_complete_button">

                                    <button class="video-youtube-complete-list-button" type="button">視聴完了</button>

                                 </div>

                             <?php } ?>

                        </div>


                    </td>
                </tr>
                
                <?php } ?>
            <?php } ?>
        </table>
    <?php } ?>


    
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
            <?php if($input_data->view_category_number[$row->ID] > 0 && $row->ID != 1 ){?>
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
    <?php /*
     <p>
        <a class="index_button" href="<?php $id = 134; echo get_page_link( $id );?>">カテゴリ選択に戻る</a>
    </p>
    */?>
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