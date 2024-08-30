<?php 

    require_once("videoviewClass.php");
    require_once("userClass.php");

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

    //追加データ
    $add_vide_data = $input_data->getAddVideo();


    //現在のページ番号
    $page_number = 0;

    if(isset($_POST["page_number"]))
    {
        $page_number = $_POST["page_number"];
    }


    //お知らせを更新する
    if(isset($_POST["video_news"]))
    {

        $input_data->setNewVideo( $_POST["id"] ,  $_POST["video_title"] , $_POST["video_category"] );

        $add_vide_data = $input_data->getAddVideo();


        /*
        //データがない
        if(!isset($add_vide_data[ $_POST["id"] ]))
        {
            $input_data->setAddVideo( $_POST["id"] , $_POST["video_title"] );

            //再取得
            $add_vide_data = $input_data->getAddVideo();
        }

        //回数をあげる
        $input_data->plusAddVideoNew( $add_vide_data[ $_POST["id"] ] , $_POST["video_title"] );

        //全ユーザーに新規動画を保存
        $users = get_users( array('orderby'=>'ID','order'=>'ASC') );




        //動画データを取得
        $input_data->getCategoryDataRow();


      //  echo $input_data->view_category_array[ $_POST["video_category"]]["type"];

        foreach ($users as $row)
        {


            //ユーザーの視聴完了リスト
            $complete_array = $input_data->getVideoComplete( $row->ID );

            //すでに見ている場合は飛ばす
            if( isset($complete_array[$_POST["video_category"]][$_POST["id"]]))
            {
                continue;
            }


            //echo $row->ID;

            $member_level = get_the_author_meta('member_level',$row->ID);//メンバーレベルを取得
            $member_type = get_the_author_meta('member_type',$row->ID);

            if($input_data->view_category_array[ $_POST["video_category"]]["type"] == 0) //なし
            {
                update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $_POST["id"] ]); //なしの場合はほぼ全員見れる
                update_user_meta($row->ID,'user_video_upload_category', $_POST["category_id"] ); //なしの場合はほぼ全員見れる
            }
            else if($input_data->view_category_array[ $_POST["video_category"]]["type"] == 1) //株
            {
                if($member_level ==  UserClass::MONKASEI) //門下生は全部見れる
                {
                    update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $_POST["id"] ]);
                    update_user_meta($row->ID,'user_video_upload_category', $_POST["category_id"] ); //なしの場合はほぼ全員見れる
                }
                else if($member_level ==  UserClass::KUNRENSEI && $member_type == 1) //訓練生では株は見れる
                {
                    update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $_POST["id"] ]);
                    update_user_meta($row->ID,'user_video_upload_category', $_POST["category_id"] ); //なしの場合はほぼ全員見れる
                }
                else{

                }

            }
            else if($input_data->view_category_array[ $_POST["video_category"]]["type"] == 2) //FX
            {
                 if($member_level ==  UserClass::MONKASEI) //門下生は全部見れる
                {
                    update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $_POST["id"] ]);
                    update_user_meta($row->ID,'user_video_upload_category', $_POST["category_id"] ); //なしの場合はほぼ全員見れる
                }
                else if($member_level ==  UserClass::KUNRENSEI && $member_type == 2) //訓練生ではFXは見れる
                {
                    update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $_POST["id"] ]);
                    update_user_meta($row->ID,'user_video_upload_category', $_POST["category_id"] ); //なしの場合はほぼ全員見れる
                }
                else{

                }
            }
            else if($input_data->view_category_array[ $_POST["video_category"]]["type"] == 3) //ダイジェスト
            {
                update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $_POST["id"] ]);
                update_user_meta($row->ID,'user_video_upload_category', $_POST["category_id"] ); //なしの場合はほぼ全員見れる
            }
             else if($input_data->view_category_array[ $_POST["video_category"]]["type"] == 4) //動画会員
            {
                update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $_POST["id"] ]);
                update_user_meta($row->ID,'user_video_upload_category', $_POST["category_id"] ); //なしの場合はほぼ全員見れる
            }
            else
            {
                update_user_meta($row->ID,'user_video_upload_id',"");
            }

           // update_user_meta($row->ID,'user_video_upload_id',"");

           //update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $_POST["id"] ]);
        }

        */
    }



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
        $base_table_array = $input_data->make_vimeo_admin_list($open_array,$input_disp_table);

        $disp_view_num = 50;//最大値


        $disp_count = 0;
        $array_count = 0;

        $table_array = array();

        foreach ($base_table_array as $key =>$row) { 

            if(!isset($table_array[$array_count]))
            {
                $table_array[$array_count] = array();
            }

            $table_array[$array_count][$disp_count] = $row;

            $disp_count++;

            if($disp_count >= $disp_view_num)
            {
                $disp_count = 0;
                $array_count++;
            }
        }
        

?>
<script>
$(document).ready(function() {

    $('table.user-table')
        .tablesorter({})
        .tablesorterPager({
            container: $(".pager"),
            size: 30,
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
       
        <div class="">
            <form action="<?php  $id = 54; echo get_page_link( $id );?>" method="post">
                <select name="disp_table_category"  class="same-user-select">
                    <?php foreach ($disp_view_category_array as $key => $value) {?>
                        <option value="<?php echo $key;?>" <?php if($input_disp_table == $key){ echo "selected";}?>><?php echo $value["name"];?></option>
                    <?php } ?>
                </select>
                <input type="submit" value="表示変更"   class="same-user-select">
            </form>
        </div>


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
                        <th><font size="5px">お知らせ</font></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ( $table_array[ $page_number ] as $key =>$row) { ?>
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
                        <td class="fixed_th_2">


                            <?php 
                                $news_num = 0;

                                if(isset($add_vide_data[ $row["input_id"] ]))
                                {
                                    $new_num_base = get_field( 'item_afc_video_new_num',$add_vide_data[ $row["input_id"] ]);

                                    if($new_num_base != "")
                                    {
                                        $news_num = $new_num_base;
                                    }
                                }
                    
                            ?>

                            <form action="<?php  $id = 54; echo get_page_link( $id );?>" method="post">
                                <input type="hidden" name="id" value="<?php echo $row["input_id"];?>">
                                <input type="hidden" name="category_id" value="<?php echo $row["input_category"];?>">
                                <input type="hidden" name="video_news" value="video_news">
                                <input type="hidden" name="video_title" value="<?php echo $row["input_title"];?>">
                                <input type="hidden" name="video_category" value="<?php echo $row["input_category"];?>">
                                <input type="hidden" name="page_number" value="<?php echo $page_number;?>">

                                <?php if($news_num == 0){ ?>
                                    <input type="submit" value="報告">
                                <?php }else{ ?>
                                    <input type="submit" value="再送"  style="background-color: rebeccapurple;">
                                <?php } ?>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

           

            <div class="pager_area">


                <?php if($page_number > 0){ ?>

                     <form action="<?php  $id = 54; echo get_page_link( $id );?>" method="post" class="pager_button_box">
                        <input type="hidden" name="page_number" value="0">
                        <input type="submit" value="<<"  style="color:white;background-color: #0073aa;">
                    </form>

                    <form action="<?php  $id = 54; echo get_page_link( $id );?>" method="post"  class="pager_button_box">
                        <input type="hidden" name="page_number" value="<?php echo $page_number-1;?>">
                        <input type="submit" value="<"  style="olor:white;background-color: #0073aa;">
                    </form>

                <?php }else{ ?>

                    <form action="" method="post" class="pager_button_box">
                        <input type="hidden" name="page_number" value="0"  >
                        <input type="submit" value="<<"  style="color:white;background-color: #767676;">
                    </form>

                    <form action="" method="post" class="pager_button_box">
                        <input type="hidden" name="page_number" value="0" >
                        <input type="submit" value="<"  style="olor:white;background-color: #767676;">
                    </form>

                <?php } ?>

                <div class="pager_number_area">

                     <?php if($page_number == 0){ ?>

                        <b>1</b>・・・・・<?php echo $array_count - 1;?>

                     <?php }else if($page_number == $array_count - 1){ ?>

                        1・・・・・<b><?php echo $array_count - 1;?></b>

                     <?php }else{ ?>

                          1・・・<b><?php echo $page_number + 1;?></b>・・・<?php echo $array_count - 1;?>

                       <?php } ?>
                 </div>


                <?php if($page_number < $array_count){ ?>

                    <form action="<?php  $id = 54; echo get_page_link( $id );?>" method="post"  class="pager_button_box">
                        <input type="hidden" name="page_number" value="<?php echo $page_number+1;?>">
                        <input type="submit" value=">"  style="olor:white;background-color: #0073aa;">
                    </form>

                     <form action="<?php  $id = 54; echo get_page_link( $id );?>" method="post"  class="pager_button_box">
                        <input type="hidden" name="page_number" value="<?php echo $array_count - 1;?>">
                        <input type="submit" value=">>"  style="color:white;background-color: #0073aa;">
                    </form>

                <?php }else{ ?>

                    <form action="" method="post" class="pager_button_box">
                        <input type="hidden" name="page_number" value="<?php  echo $array_count - 1;?>" >
                        <input type="submit" value=">"  style="olor:white;background-color: #767676;">
                    </form>

                     <form action="" method="post" class="pager_button_box">
                        <input type="hidden" name="page_number" value="<?php  echo $array_count - 1;?>">
                        <input type="submit" value=">>"  style="color:white;background-color: #767676;">
                    </form>

                <?php } ?>

             </div>

        <?php }else{ ?>
            <div class="index_center">
                <p>データが存在しません</p>
            </div>
        <?php } ?>

        <div>
            <a class="index_button" href="<?php $id = 129; echo get_page_link( $id );?>">動画登録へ</a>
        </div>
    </div>

<?php }else{ ?>
    <div class="index_center">
        <p>データが存在しません</p>
        <p>
            <a class="index_button" href="<?php $id = 129; echo get_page_link( $id );?>">動画登録へ</a>
        </p>
    </div>
<?php } ?>
