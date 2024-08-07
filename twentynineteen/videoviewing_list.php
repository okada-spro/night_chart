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
    $input_data->getReleaseDateViewRow();

     // カテゴリ取得クエリ実行
    $input_data->getCategoryDataRow();

    if($input_data->view_row)
    {

        $open_array = $input_data->setOpenData(true);


        //表示セレクトを作成
        if(isset($_POST["disp_table_category"]))
        {
            $input_disp_table = $_POST["disp_table_category"];
        }
        else{
             $input_disp_table = 99;
        }


        $disp_view_category_array = $input_data->view_category_array;

        //全て表示を作成
        $disp_view_category_array[99]["name"] = "全て表示";


        //表示配列を作ってしまう
        $table_array = $input_data->make_vimeo_admin_list($open_array,$input_disp_table);


?>
<script>
$(document).ready(function() {

    $('table.user-table')
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
            $(".disp_close" + id).slideDown(100);
        }else{
            t_text = t_text.replace("△","▽");
            $(".disp_close" + id).css("display","none");
        }
        $(this).find("th").eq(0).text(t_text)
    });
});
</script>


    <div class="page_div_box videoviewing_list">
        <p>
            <form action="<?php  $id = 54; echo get_page_link( $id );?>" method="post">
                <select name="disp_table_category"  class="same-user-select">
                    <?php foreach ($disp_view_category_array as $key => $value) {?>
                        <option value="<?php echo $key;?>" <?php if($input_disp_table == $key){ echo "selected";}?>><?php echo $value["name"];?></option>
                    <?php } ?>
                </select>
                <input type="submit" value="表示変更"   class="same-user-select">
            </form>
        </p>


    <?php if($table_array){ ?>

        <table class="user-table mode-pc">
             <thead>
            <tr>
                <th class="fixed_th_1">編集</th>
                <th class="fixed_th_2">視聴</th>
                <th>タイトル</th>
                <th>カテゴリ</th>
                <th>埋め込み番号</th>
                <th>公開日</th>
                <th>公開</th>
            </tr>
             </thead>
             <tbody>
            <?php foreach ($table_array as $key =>$row) { ?>
                <?php 
                    $table_color = "";
                        if($row["input_disp"] == 0){
                            $table_color = 'bgcolor="darkgray"';
                    }
                ?>

            <tr <?php echo $table_color;?>>
                <td class="fixed_th_1">
                    <form action="<?php  $id = 129; echo get_page_link( $id );?>" method="post">
                        <input type="hidden" name="id" value="<?php echo $row["input_id"];?>">
                        <input type="hidden" name="viewing_edit" value="viewing_edit">
                        <input type="submit" value="編集">
                    </form>
                </td>
                <td class="fixed_th_2">
                    <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
                        <input type="hidden" name="id" value="<?php echo $row["input_id"];?>">
                        <input type="hidden" name="category_id" value="<?php echo $row["input_category"];?>">
                        <input type="hidden" name="viewing" value="viewing">
                        <input type="submit" value="視聴">
                    </form>
                </td>
                <td><?php echo $row["input_title"]; ?></td>
                <td><?php echo  $input_data->getCategoryNameData($row["input_category"]); ?></td>

                <td><?php echo $row["input_flame"]; ?></td>
                <td><?php echo $row["input_release_date"]; ?></td>
                <td><?php if($row["input_disp"] == 0){echo "非公開";}else{echo "公開";} ?></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>

        <table class="user-table mode-sp">
             <tbody>
            <?php foreach ($table_array as $key =>$row) { ?>
                <?php 
                    $table_color = "";
                        if($row["input_disp"] == 0){
                            $table_color = 'bgcolor="darkgray"';
                    }
                ?>
            <tr  class="disp_open"  data-id=<?php echo $row["input_id"];?>>
                <th colspan="4">タイトル▽</th>
            </tr>
            <tr <?php echo $table_color;?>  class="disp_open"  data-id=<?php echo $row["input_id"];?>>
                <td colspan="4"><?php echo $row["input_title"]; ?></td>
            </tr>
            <tr <?php echo $table_color;?> class="disp_close<?php echo $row["input_id"];?>">
                <td>編集</td>
                <td>視聴</td>
                <td>公開</td>
            </tr>
            <tr <?php echo $table_color;?> class="disp_close<?php echo $row["input_id"];?>">
                <td>
                    <form action="<?php  $id = 129; echo get_page_link( $id );?>" method="post">
                        <input type="hidden" name="id" value="<?php echo $row["input_id"];?>">
                        <input type="hidden" name="viewing_edit" value="viewing_edit">
                        <input type="submit" value="編集">
                    </form>
                </td>
                <td>
                    <form action="<?php  $id = 134; echo get_page_link( $id );?>" method="post">
                        <input type="hidden" name="id" value="<?php echo $row["input_id"];?>">
                        <input type="hidden" name="category_id" value="<?php echo $row["input_category"];?>">
                        <input type="hidden" name="viewing" value="viewing">
                        <input type="submit" value="視聴">
                    </form>
                </td>
                <td><?php if($row["input_disp"] == 0){echo "非公開";}else{echo "公開";} ?></td>
            </tr>
            <tr <?php echo $table_color;?> class="disp_close<?php echo $row["input_id"];?>">
                <td colspan="2">カテゴリ</td>
                <td colspan="2">埋め込み番号</td>
            </tr>
            <tr <?php echo $table_color;?> class="disp_close<?php echo $row["input_id"];?>">
                <td colspan="2"><?php echo $input_data->getCategoryNameData($row["input_category"]); ?></td>
                <td colspan="2"><?php echo $row["input_flame"]; ?></td>
            </tr>
            <tr <?php echo $table_color;?> class="disp_close<?php echo $row["input_id"];?>">
                <td colspan="4">公開日</td>
            </tr>
            <tr <?php echo $table_color;?> class="disp_close<?php echo $row["input_id"];?>">
                <td colspan="4"><?php echo $row["input_release_date"]; ?></td>
            </tr>
            <tr> 
                <td style="border:none;height:10px"></td>
            </tr>
            <?php 
            /*
            

            <tr>
            </tr>
            <tr >
                
                

                
                
            </tr>
                    */
                    ?>
            <?php } ?>
            </tbody>
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

    <?php }else{ ?>
        <div class="index_center">
            <p>データが存在しません</p>
        </div>
    <?php } ?>

        <p>
            <a class="index_button" href="<?php $id = 129; echo get_page_link( $id );?>">動画登録へ</a>
        </p>
    </div>

<?php }else{ ?>
    <div class="index_center">
        <p>データが存在しません</p>
        <p>
            <a class="index_button" href="<?php $id = 129; echo get_page_link( $id );?>">動画登録へ</a>
        </p>
    </div>
<?php } ?>
