<?php 

  

    //ファイルダウンロード
    if (!empty($_POST["pdf"]))
    {
     
    }


    require_once("reportClass.php");

    $user = wp_get_current_user();

    //クラス作成
    $input_data= new ReportClass();

    //初期化
    $input_data->init();


    //全データ取得
    $input_data->getPostEventdateRow();


    $file_up_str ="";


    $user_id = $user->ID;


    //自分のメンバーレベルとメンバータイプを取得
    $member_level = get_the_author_meta('member_level',$user_id);
    $member_type = get_the_author_meta('member_type',$user_id);


    //これがあるという事は管理者が見ている
    if(isset($_POST['user_id'])  && current_user_can('administrator') )
    {
        $user_id = $_POST['user_id'];
    }
    else if(isset($_POST['user_reply_id'])  && current_user_can('administrator') )
    {
        $user_id = $_POST['user_reply_id'];
    }
    else{
        $user_id = $user->ID;
    }

    $user_info = get_userdata( $user_id );

    $comment_array = array();

    //コメントデータを取得
    $args = array(
        'post_type' => array('report'), // 取得する投稿タイプのスラッグ
        'posts_per_page' => -1, // 表示件数を指定
        'meta_key' => 'report_user', //カスタムフィールドのキー
        'meta_value' => $user_id, //カスタムフィールドの値
        'meta_compare' => 'LIKE' //'meta_value'のテスト演算子
     );

    $my_query = new WP_Query($args);

    if ($my_query->have_posts()) : while ($my_query->have_posts()) : $my_query->the_post();
  
        $report_id = get_field( 'report_id');

        $comment_array[ $report_id ] = get_the_ID();

     endwhile; endif;

     wp_reset_postdata();
  
     
      //返信をアップロード
    if(isset( $_POST["input_reply"] ) )
    {
       
    // var_dump($_POST);
        
        //コメントが作られている
        if( isset(  $comment_array[ $_POST["input_reply_id"] ]  ) )
        {
             update_post_meta(  $comment_array[ $_POST["input_reply_id"] ] , "report_reply", $_POST["input_reply_text"]);
        }
        else{
            //まだない
              $insert_post = array(
                'post_title' =>  $_POST["input_reply_id"] ."-".$_POST["user_reply_id"] ."-report"  ,
                'post_type' => 'report',// 投稿タイプ（カスタム投稿タイプも指定できるよ）
                'post_status' => 'publish',
                'post_author' => $user->ID,
             );

             $report_post_id = wp_insert_post($insert_post);

             update_field( 'report_id', $_POST["input_reply_id"], $report_post_id);
             update_field( 'report_user', $_POST["user_reply_id"], $report_post_id);
             update_field( 'report_reply', $_POST["input_reply_text"], $report_post_id);
             update_field( 'report_user_reply', "", $report_post_id);
        }
      
        $file_up_str = "返信しました";
        
    }
    
    
    // $custom_fields = get_post_custom($post_id);

    //ファイルアップロード
    if(isset($_POST["fileup"]) && $_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $filetype = mime_content_type($_FILES['userfile']['tmp_name']);

        //echo $filetype;

        //ファイル拡張子
        if(!strpos($filetype, 'pdf')  )
        {
             $file_up_str = 'この拡張子ではアップロードできません';
        }


        //エラーを確認
        else if ($_FILES['userfile']['error'] === 2) {
            $file_up_str = '送信したファイルサイズを小さくしてください';
        // ③サイズが0だった場合（ファイルが空）
        }elseif($_FILES['userfile']['size'] === 0) {

            $file_up_str = 'ファイルを選択してください';

        } elseif ($_FILES['userfile']['error'] === 0) {

            $input_data->upload_report_submissiton($_POST["input_id"],$user->ID,$_FILES);

            $file_up_str = "【" . $_POST["title"] ."】のアップロードが完了しました";
        }
        else{
            $file_up_str = 'ファイルのアップロードに失敗しています';
        }
    }


    //表示変更
    $input_disp_table = 2;

     //直近表示
     if(isset($_POST['disp_table']) )
     {
        $input_disp_table = $_POST['disp_table'];
     } 
    

    // var_dump($input_data->report_row);

    //表示配列を作ってしまう
    $table_array = $input_data->make_reportarray_datatable($input_data->report_row,$user_id,$input_disp_table,$member_level,$member_type);

?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

<script type="text/javascript"> 
<!-- 

 function replySubmit(input_id,user_id){

  //  document.replyForm.input_id.value = input_id;
  //  document.replyForm.user_id.value = user_id;

 //   const reply_text = document.getElementsByName("input_reply_text_" + input_id);

  //  document.replyForm.input_reply_text.value = reply_text[0].value ;

   // document.replyForm.submit();

   // 入力ダイアログを表示 ＋ 入力内容を user に代入
  var user = window.prompt("返信を入力してください", "");

}

function replyDisp(input_id,user_id){

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


   
      //入力
      $(".reply_button").on('click',function(){

        var data_obj  =  $(this).data('value'); 
        
       Swal.fire({
        title: "返信内容を入力してください",
        text: "",
        input: "textarea",
        showCancelButton: true,
        confirmButtonText: '送信',
         cancelButtonText: 'キャンセル',
        allowOutsideClick: false
        }).then(function(result){

           

            if (result.value) {

                $('#input_reply_id').val(data_obj.input_id);
                $('#user_reply_id').val(data_obj.user_id);
                $('#input_reply_text').val(result.value);
                $('#replyForm').submit();


                /*
                Swal.fire({
                    type: 'success',
                    title: '送信は成功しました',
                    html: ' 送信内容：' + data_obj.input_id
                });
                */
                
            }
        });
    });

    //表示のみ
    $(".reply_text_button").click(function(){


        var data_id =  $(this).data('id'); 

       Swal.fire({
            title: "",
            html :'<div style=" text-align: left;">' + data_id + '</div>',
        });
    });
    
});

// inputファイル名表示
$(function(){
    // $('.file_upload input[type="file"]').on('change', function () {
    $('.fileinput').on('change', function () {
        var file = $(this).prop('files')[0];
        var no = $('.fileinput').index(this);
        $("td.file_upload form p").eq(no).text(file.name);
    });
});

// sp用テーブルスライド
$(function(){
    $(".disp_open").click(function(){
        var id = $(this).data('id');
        var t_text = $(this).find("td").eq(0).text();   //マーク取得

        if($(".disp_close" + id).css("display") == "none"){
            t_text = t_text.replace("▼","▲");
            $(".disp_close" + id).slideDown(100);
        }else{
            t_text = t_text.replace("▲","▼");
            $(".disp_close" + id).css("display","none");
        }
        $(this).find("td").eq(0).text(t_text);
    });
});
</script>

<?php if($file_up_str != ""){ ?>
    <div class="index_updata_center">
        <p><?php echo $file_up_str;?></p>
    </div>
<?php }?>

<?php if(current_user_can('administrator')){ ?>
    <div class="history_list_name_area">
        <div class="history_list_name_str"><?php echo $user_info->last_name ;?>　<?php echo $user_info->first_name;?> 様</div>
    </div>
<?php } ?>

<div class="page_div_box">
   
    <p>
        <form action="<?php  $id = 463; echo get_page_link( $id );?>" method="post">
            <select name="disp_table"  class="same-user-select">
                <?php foreach ($input_data->disp_submission_array as $key => $value) {?>
                   <option value="<?php echo $key;?>" <?php if($input_disp_table == $key){ echo "selected";}?>><?php echo $value;?></option>
                <?php } ?>
            </select>
            <?php if(isset($_POST['report_admin_list']) && current_user_can('administrator')){?>
                <input type="hidden" name="report_admin_list" value="report_admin_list">
                <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
            <?php } ?>
            <input type="submit" value="表示変更"   class="same-user-select">
        </form>
    </p>


    <?php if(current_user_can('administrator')){ ?>
     
    
        
    <?php }else{ ?>
        <p><font color="red">
        *提出可能なファイルの拡張子は .pdfとなります<br>
        *ファイル名が変更されますので、レポートの提出・未提出は表を参考してください
        </font></p>
    <?php } ?>

     
     <table class="user-table-two mode-pc">
        <thead>
        <tr>
           

            <th class="fixed_th_1">ID</th>
            <th class="fixed_th_2">提出</th>
            <th  class="fixed_th_3">講義日</th>
            <th>講義名</th>
            <?php if(!isset($_POST['report_admin_list'])){?>
                <th>アップロード</th>
            <?php }elseif(current_user_can('administrator')){ ?>
                <th>OPEN</th>
            <?php } ?>
             <th>返信</th>
        </tr>
        </thead>
        <?php foreach ($table_array as $key =>$value) { ?>

        <?php 
           
            $table_color = "";
            $style ="";

            if($value["input_is_submission"])
            {
                $table_color = "bgcolor='#fffacd'";
                $style = "style='background-color:#fffacd;'";
            }
        
        ?>


        <tr <?php echo $table_color;?>>
           
            <td <?php echo $table_color;?> <?php echo $style;?>class="fixed_th_1"><?php echo $value["input_id"]; ?></td>
            <td <?php echo $table_color;?> <?php echo $style;?>class="fixed_th_2"><?php if($value["input_is_submission"]){ echo "<b>提出済</b>";}else{ echo "<font color='red'>未提出</font>";}?></td>
            <td <?php echo $table_color;?> <?php echo $style;?>class="fixed_th_3"><?php echo $value["input_eventdate"]; ?></td>
            <td><?php echo $value["input_title"]; ?></td>
            
            <?php if(!isset($_POST['report_admin_list'])){?>
                <td>
                    <form enctype="multipart/form-data"  action="<?php  $id = 463; echo get_page_link( $id );?>" method="POST">
                        <input type="hidden" name="input_id" value="<?php echo $value["input_id"]; ?>" />
                        <input type="hidden" name="fileup" value="fileup" />
                        <input type="hidden" name="disp_table" value="<?php echo $input_disp_table; ?>" />
                        <input type="hidden" name="title" value="<?php echo $value["input_title"]; ?>" />
                        <input name="userfile" type="file" accept=".pdf"/>
                        <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                        <input type="submit" value="送信" />
                    </form>
                </td>
                <td>
                    <?php if($value["input_is_submission"]){ ?>
                        <?php if(isset( $comment_array[ $value["input_id"] ] ) ){ ?>
                              <button  type="button"   class="reply_text_button" id="reply_text_btn" data-id='<?php echo nl2br(get_field( 'report_reply',$comment_array[ $value["input_id"] ])); ?>' />返信済</button>
                         <?php } ?>
                    <?php }else{ ?>
                        
                    <?php } ?>
                </td>
             <?php }elseif(current_user_can('administrator')){ ?>
                <?php if($value["input_is_submission"]){ ?>
                    <td><a href="<?php echo $value["file_url"]; ?>" target="_blank" ?>開く</a></td>
                    <td>
                        <button  type="button"  class="reply_button"   id="reply_btn"  data-value='{"input_id":"<?php echo $value["input_id"]; ?>","user_id":"<?php echo $user_id; ?>"}'  />入力</button>

                         <?php if(isset( $comment_array[ $value["input_id"] ] ) ){ ?>
                              <button  type="button"   class="reply_text_button" id="reply_text_btn" data-id='<?php echo nl2br(get_field( 'report_reply',$comment_array[ $value["input_id"] ])); ?>' />返信済</button>
                         <?php } ?>
                       
                    </td>
                <?php }else{ ?>
                    <td>未提出</td>
                    <td>  </td>
                <?php } ?>

             <?php } ?>
         </tr>
        <?php } ?>
    </table>

    <table class=" mode-sp user-table-two">
        <thead>
        <tr>
            <th>ID</th>
            <th>提出</th>
            <th style="width:50%">講義日</th>
        </tr>
        <?php
            foreach ($table_array as $key =>$value) {

                $table_color = "";

                if($value["input_is_submission"])
                {
                    $table_color = "bgcolor='#fffacd'";
                }
        
        ?>


        <tr <?php echo $table_color;?> class="disp_open"  data-id=<?php echo $value["input_id"];?>>
            <td>▼ <?php echo $value["input_id"]; ?></td>
            <td><?php if($value["input_is_submission"]){ echo "<b>提出済</b>";}else{ echo "<font color='red'>未提出</font>";}?></td>
            <td><?php echo $value["input_eventdate"]; ?></td>
        </tr>
        <tr class="disp_close<?php echo $value["input_id"];?>">
            <th colspan="3">講義名</th>
        </tr>
        <tr <?php echo $table_color;?> class="disp_close<?php echo $value["input_id"];?>">
        
            <td colspan="3"><?php echo $value["input_title"]; ?></td>
        </tr>
        <tr class="disp_close<?php echo $value["input_id"];?>">
        
            <?php if(!isset($_POST['report_admin_list'])){?>
                <th colspan="3">アップロード</th>
            <?php }elseif(current_user_can('administrator')){ ?>
                <th>OPEN</th>
            <?php } ?>
        </tr>
        <tr <?php echo $table_color;?> class="disp_close<?php echo $value["input_id"];?>">
        
            
            <?php if(!isset($_POST['report_admin_list'])){?>

                <td class="file_upload" colspan="3">
                    <form enctype="multipart/form-data"  action="<?php  $id = 463; echo get_page_link( $id );?>" method="POST">
                        <input type="hidden" name="input_id" value="<?php echo $value["input_id"]; ?>" />
                        <input type="hidden" name="fileup" value="fileup" />
                        <input type="hidden" name="disp_table" value="<?php echo $input_disp_table; ?>" />
                        <input type="hidden" name="title" value="<?php echo $value["input_title"]; ?>" />
                        <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                        <label>ファイルを選択
                            <input class="fileinput" name="userfile" type="file" accept=".pdf"/>
                        </label>
                        <p>選択されていません</p>
                        <input type="submit" value="送信" />
                    </form>
                </td>
        </tr>
        <?php if($value["input_is_submission"]){ ?>
            <tr class="disp_close<?php echo $value["input_id"];?>">
            
                <td colspan="3">
                    <?php if(isset( $comment_array[ $value["input_id"] ] ) ){ ?>
                        <button  type="button"   class="reply_text_button" id="reply_text_btn" data-id='<?php echo nl2br(get_field( 'report_reply',$comment_array[ $value["input_id"] ])); ?>' />返信済</button>
                    <?php } ?>
                <?php }else{ ?>
                    
                </td>
            </tr>
        <?php } ?>
             <?php }elseif(current_user_can('administrator')){ ?>
                <?php if($value["input_is_submission"]){ ?>
                    <td><a href="<?php echo $value["file_url"]; ?>" target="_blank" ?>開く</a></td>
                    <td>
                        <button  type="button"  class="reply_button"   id="reply_btn"  data-value='{"input_id":"<?php echo $value["input_id"]; ?>","user_id":"<?php echo $user_id; ?>"}'  />入力</button>

                         <?php if(isset( $comment_array[ $value["input_id"] ] ) ){ ?>
                              <button  type="button"   class="reply_text_button" id="reply_text_btn" data-id='<?php echo nl2br(get_field( 'report_reply',$comment_array[ $value["input_id"] ])); ?>' />返信済</button>
                         <?php } ?>
                       
                    </td>
                <?php }else{ ?>
                    <td>未提出</td>
                    <td>  </td>
                <?php } ?>

            <?php } ?>
        </tr>

        
        <tr class="disp_close<?php echo $value["input_id"];?>">
        
            <th colspan="3">返信</th>
        </tr>
        <tr class="disp_close<?php echo $value["input_id"];?>">
        
            <?php if(!isset($_POST['report_admin_list'])){?>
                <td colspan="3">
                    <?php if($value["input_is_submission"]){ ?>
                        <?php if(isset( $comment_array[ $value["input_id"] ] ) ){ ?>
                            <button  type="button"   class="reply_text_button" id="reply_text_btn" data-id='<?php echo nl2br(get_field( 'report_reply',$comment_array[ $value["input_id"] ])); ?>' />返信済</button>
                        <?php } ?>
                    <?php }else{ ?>
                        
                    <?php } ?>
                </td>
            <?php }elseif(current_user_can('administrator')){ ?>
            <?php } ?>
        </tr>
        <tr class="disp_close<?php echo $value["input_id"];?>"> 
            <td  colspan="3" style="border:none;height:30px"></td>
        </tr>

        <?php } ?>
        </thead>



        
    </table>

    <?php if(current_user_can('administrator')){ ?>
      
        <form action="<?php  $id = 463; echo get_page_link( $id );?>" method="POST" id="replyForm" >
            <input type="hidden" name="input_reply"  id="input_reply"  value="input_reply">
            <input type="hidden" name="input_reply_id" id="input_reply_id" value="" />
            <input type="hidden" name="user_reply_id"  id="user_reply_id"  value="">
            <input type="hidden" name="report_admin_list"  id="report_admin_list"  value="report_admin_list">
            <input type="hidden"   name="input_reply_text" id="input_reply_text"  value='' >
        </form>
     <?php } ?>

   <?php /*?>
      <div class="pager">
            <button type='button' class='first'>&lt;&lt;</button>
            <button type='button' class='prev'>&lt;</button>

            <span class="pagedisplay" ></span>
          

            <button type='button' class='next'>&gt;</button>
            <button type='button' class='last'>&gt;&gt;</button>

            <select class="pagesize">
                <option value="50">50</option>
                <option selected="selected" value="100">100</option>
                <option value="200">200</option>
            </select>
        </div>
         */?>

</div>

<script>
    $("[class^='disp_close']").css("display","none");
</script>