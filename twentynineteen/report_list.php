<?php 

   
    require_once("reportClass.php");
    require_once("userClass.php");

    $user = wp_get_current_user();

    //クラス作成
    $report_class= new ReportClass();
    $users_data= new UserClass();

    //初期化
    $report_class->init();

    //削除
    if( isset( $_POST["is_delete"] ) )
    {
        $report_class->deleteReportData( $_POST["delete_id"] );
    }



//確認画面じゃない
if(!isset($_POST["confirmation"]))
{

    //全データ取得
    $report_class->getIDRow();


    //表示配列を作ってしまう
    $table_array = $report_class->make_reportarray_datatable($report_class->report_row,0,ReportClass::DISP_ADMIN_SUBMITTION,99,1);

 

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
            size: 500,
    });
});
</script>
<script>
    // 表示折り畳み
    window.onload = function(){
        $("[class^='disp_close']").css("display","none");
    }

    // sp用テーブルスライド
$(function(){
    $(".disp_open").click(function(){
        var id = $(this).data('id');
        var t_text = $(this).find("th").eq(0).text();   //マーク取得

        console.log(id);
        console.log(t_text);
        
        if($(".disp_close" + id).css("display") == "none"){
            t_text = t_text.replace("▽","△");
            $(".disp_close" + id).css("display","table-row");
        }else{
            t_text = t_text.replace("△","▽");
            $(".disp_close" + id).css("display","none");
        }
        $(this).find("th").eq(0).text(t_text)
    });
});
</script>

<div class="page_div_box report_list">

     <table class="user-table-two mode-pc">
        <thead>
        <tr>
            <th class="fixed_th_1">編集</th>
            <th class="fixed_th_2">削除</th>
            <th class="fixed_th_3">提出</th>
            <th>ID</th>
            <th>講義日</th>
            <th>講義名</th>
            <th>タイプ</th>
            <th>提出人数</th>
        </tr>
        </thead>
        <?php foreach ($table_array as $key =>$value) { ?>
        <tr>
            <td class="fixed_th_1">
                <form action="<?php  $id = 482; echo get_page_link( $id );?>" method="post">
                    <input type="hidden" name="input_id" value="<?php echo $value["input_id"];?>">
                    <input type="hidden" name="edit" value="edit">
                    <input type="submit" value="編集">
                </form>
            </td>
            <td class="fixed_th_2">
                <form action="<?php  $id = 487; echo get_page_link( $id );?>" method="post"  onSubmit="return delete_check()">
                    <input type="hidden" name="delete_id" value="<?php echo $value["input_id"];?>">
                    <input type="hidden" name="is_delete" value="delete">
                    <input type="submit" value="削除">
                </form>
            </td>
             <td class="fixed_th_3">
                <?php if( $value["input_is_submission_num"] > 0) {?>
                    <form action="<?php  $id = 487; echo get_page_link( $id );?>" method="post">
                        <input type="hidden" name="input_id" value="<?php echo $value["input_id"];?>">
                        <input type="hidden" name="confirmation" value="confirmation">
                        <input type="submit" value="確認">
                    </form>
                <?php } ?>
            </td>
            <td><?php echo $value["input_id"]; ?></td>
            <td><?php echo $value["input_eventdate"]; ?></td>
            <td><?php echo $value["input_title"]; ?></td>
             <td><?php echo $users_data->member_type_array[$value["input_type"]]; ?></td>
             <td><?php echo $value["input_is_submission_num"]; ?>人</td>
         </tr>
        <?php } ?>
    </table>

    <table class="user-table-two mode-sp">
        <?php foreach ($table_array as $key =>$value) { ?>
            <tr class="disp_open"  data-id=<?php echo $value["input_id"];?>>
                <th>ID▽</th>
                <th colspan="2">講義名</th>
            </tr>
            <tr class="disp_open"  data-id=<?php echo $value["input_id"];?>>
                <td><?php echo $value["input_id"]; ?></td>
                <td colspan="2"><?php echo $value["input_title"]; ?></td>
            </tr>
            <tr class="disp_close<?php echo $value["input_id"];?>">
                <td>タイプ</td>
                <td>講義日</td>
                <td>提出人数</td>
            </tr>
            <tr class="disp_close<?php echo $value["input_id"];?>">
                <td><?php echo $users_data->member_type_array[$value["input_type"]]; ?></td>
                <td><?php echo $value["input_eventdate"]; ?></td>
                <td><?php echo $value["input_is_submission_num"]; ?>人</td>
            </tr>
            <tr class="disp_close<?php echo $value["input_id"];?>">
                <td>編集</td>
                <td>削除</td>
                <td>提出</td>
            </tr>
            <tr class="disp_close<?php echo $value["input_id"];?>">
                <td>
                    <form action="<?php  $id = 482; echo get_page_link( $id );?>" method="post">
                        <input type="hidden" name="input_id" value="<?php echo $value["input_id"];?>">
                        <input type="hidden" name="edit" value="edit">
                        <input type="submit" value="編集">
                    </form>
                </td>
                <td>
                    <form action="<?php  $id = 487; echo get_page_link( $id );?>" method="post"  onSubmit="return delete_check()">
                        <input type="hidden" name="delete_id" value="<?php echo $value["input_id"];?>">
                        <input type="hidden" name="is_delete" value="delete">
                        <input type="submit" value="削除">
                    </form>
                </td>
                <td>
                    <?php if( $value["input_is_submission_num"] > 0) {?>
                        <form action="<?php  $id = 487; echo get_page_link( $id );?>" method="post">
                            <input type="hidden" name="input_id" value="<?php echo $value["input_id"];?>">
                            <input type="hidden" name="confirmation" value="confirmation">
                            <input type="submit" value="確認">
                        </form>
                    <?php } ?>
                </td>
            </tr>
        <tr> 
            <td style="border:none;height:10px"></td>
        </tr>
        <?php 
        /*    

        
                     */
        ?>
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
</div>

<?php }else{ ?>

<?php
    
    $report_class->getInputReportForID($_POST["input_id"]);
    $report_class->setInputDataForPost();

    $report_membar = array();

    foreach ($report_class->report_row as $rows) {
       
       $report_membar =  $report_class->get_report_submissiton($rows->ID);
    }

    

     //$directory_path =  get_template_directory() ."/../../uploads/report/".$folder_ID;

    //var_dump($report_membar);

?>
   

    <?php if(empty($report_membar)){ ?>

        <p>
            <a class="index_button" href="<?php $id = 487; echo get_page_link( $id );?>">一覧に戻る</a>
        </p>

    <?php }else{ ?>
        
        <p>
            <div class="index_center">
                <font size="6"><?php echo $report_class->getReportData("input_eventdate"); ?>　<?php echo $report_class->getReportData("input_title"); ?></font>（<a href="<?php $id = 487; echo get_page_link( $id );?>">一覧に戻る</a>）
            </div>
        </p>

        <table class="user-table-two report_list">
            <colgroup span="3"></colgroup>
            
            <tr>
                <th class="fixed_th_1">ID</th>
                <th>名前</th>
                <th>OPEN</th>
                <th>DL</th>
            </tr>
            <?php foreach ($report_membar as $row){?>
                <tr>
                <td class="fixed_th_1"><?php echo $row["user_id"]; ?></td>
                <td><?php echo get_the_author_meta('last_name',$row["user_id"]);?>　<?php echo get_the_author_meta('first_name',$row["user_id"]);?>(<?php echo get_the_author_meta('user_login',$row["user_id"]);?>)</td>
                <td><a href="<?php echo $row["file_url"]; ?>" target="_blank" ?>開く</a></td>
                <td><a href="<?php echo $row["file_url"]; ?>" download="<?php echo $row["file_name"]; ?>">ダウンロード</a></td>
                </tr>
            <?php }?>
        </table>
     <?php }?>




<?php } ?>