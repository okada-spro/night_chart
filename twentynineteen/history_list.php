<?php

    require_once("historyClass.php");
    require_once("zoomClass.php");

    $user = wp_get_current_user();

    //クラス作成
    $input_data= new HistoryClass();
    $zoom_data= new ZoomClass();

     //ZOOMデータの作成
    $zoom_data= new ZoomClass();

    //初期化
    $input_data->init();


     //文字用の変数
    $delete_str = "";

    if(isset($_POST['is_delete']) )
    {
        //削除
        $delete_sql =  $input_data->deleteTradeData($user->ID,$_POST['id']);

        if ( $delete_sql == false ) {
            $delete_str =  '削除に失敗しました';
        } else {
	        $delete_str = '削除しました';
        }
    }

    
    // データ取得クエリ実行
    $user_id = $user->ID;

    if(isset($_POST['is_list']) )
    {
        $user_id = $_POST['id'];
    }

    //echo $user_id;

    $user_info = get_userdata( $user_id );

    $input_data->getHistoryListRow($user_id);

    // 配列を計算して出しなおす
    $base_rows = $input_data->make_new_array($input_data->history_row);


    

if($input_data->history_row){

    if($user_id != $user->ID ){
        $rows = array_reverse($base_rows,true);
    }
    else{
        $rows = $base_rows;
    }


?>


<script>
$(document).ready(function() {

    $('table.lecture-table')
        .tablesorter({})
        .tablesorterPager({
            container: $(".pager"),
            size: 500,
    });
});
</script>

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


<?php if($delete_str != ""){ ?>
     <div class="index_updata_center">
    <p><?php echo $delete_str;?></p>
    </div>
<?php }?>


    <?php if(current_user_can('administrator')){ ?>

        <div class="history_list_name_area">
            <div class="history_list_name_str"><?php echo $user_info->last_name ;?>　<?php echo $user_info->first_name;?> 様</div>
        </div>
    <?php } ?>

    <?php 
        

        //表示年を設定
        $years = date("Y");

        //年テーブルを作成
        $years_table = array();

        $years_table[0] = "直近10ヶ月分";

        $disp_max = 10;//10ヶ月

        //$years_table[] = 2020;

        for($i=$years;$i>=2020;$i--)
        {
            $years_table[$i] = $i."年度";
        }

        if(isset($_POST['year_table']) )
        {
            $now_years = $_POST['year_table'];
        }
        else {
            $now_years = 0;
        }

        $disp_count = 0;//直近用

       

    ?>

       
   
       
        <?php if($user_id == $user->ID ){ ?>
            <div class="page_div_box"><p>
                <form action="<?php  $id = 37; echo get_page_link( $id );?>" method="post">
                    <select name="year_table"  class="same-user-select">
                        <?php foreach ($years_table as $key => $value) {?>
                            <option value="<?php echo $key;?>" <?php if($now_years == $key){ echo "selected";}?>><?php echo $value;?></option>
                        <?php } ?>
                    </select>
                    <input type="submit" value="表示変更"   class="same-user-select">
                </form>
            </p></div>
        <?php }else{ ?>
            <div style="text-align: center;"><p>
            <?php if($_POST['years'] == "all"){ ?>
                <form action="<?php  $id = 37; echo get_page_link( $id );?>" method="post">
                    <input type="hidden" name="years" value="<?php echo $years;?>">
                    <input type="hidden" name="id" value="<?php echo $_POST['id'];?>">
                    <input type="hidden" name="is_list" value="checklist">
                    <input type="submit" value="<?php echo $years;?>年表示">
                </form>

            <?php }else{ ?>
                <form action="<?php  $id = 37; echo get_page_link( $id );?>" method="post">
                    <input type="hidden" name="years" value="all">
                    <input type="hidden" name="id" value="<?php echo $_POST['id'];?>">
                    <input type="hidden" name="is_list" value="checklist">

                    <input type="submit" value="全期間表示">
                </form>
            <?php } ?>
            </p></div>
        <?php } ?>

    
     <table class="user-table">
        <colgroup span="11"></colgroup>
        <thead>
        <tr>
            <?php if($user_id == $user->ID){?>
                <th>編集</th>
                <th>削除</th>
            <?php } ?>
            <th>登録月</th>
            <th>月分益</th>
            <th>月分損</th>
            <th>資金増減</th>
            <th>月利</th>
            <th>月末残高</th>
            <th>トレード回数</th>
            <th>勝率</th>
            <th>入金</th>
            <th>出金</th>
            <?php if(current_user_can('administrator')){ ?>
                <th>ザラ場/講義</th>
            <?php } ?>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row) {?>
            <?php $is_years = true;?>
                <?php 
                    if( isset($_POST['years'])){

                        if($_POST['years'] == "all")
                        {

                        }
                        else if($_POST['years'] != $row["post_trade_year"])
                        {
                            $is_years = false;
                        }
                    }
                    else{
                        //通常モード
                        if($now_years > 0 )
                        {
                            if($now_years != $row["post_trade_year"])
                            {
                                $is_years = false;
                            }
                        }
                        else {

                            if($disp_count >= $disp_max)
                            {
                                 $is_years = false;
                            }else{
                                $disp_count++;
                            }
                        }
                    }

                     //月のズームデータを取得
                     $zoom_data->getZoomSetMonthRow( $row["post_trade_year"] , $row["post_trade_month"]);
                   
                     $zoom_plans_num =  $zoom_data->checkUserZoomSetPlans( $user_id );
                     $kougi_plans_num =  $zoom_data->checkUserSetKougiPlans( $user_id );

                 ?>
                 <?php if($is_years){?>
                    <tr>
                        <?php if($user_id == $user->ID){?>
                        <td>
                            <form action="<?php  $id = 35; echo get_page_link( $id );?>" method="post">
                                <input type="hidden" name="id" value="<?php echo $row["ID"];?>">
                                <input type="hidden" name="set_years" value="<?php echo $row["post_trade_year"];?>">
                                <input type="hidden" name="set_month" value="<?php echo $row["post_trade_month"];?>">
                                 
                                <input type="hidden" name="is_edit" value="edit">
                                <input type="submit" value="編集">
                            </form>
                        </td>
                        <td>
                            <form action="<?php  $id = 37; echo get_page_link( $id );?>" method="post"  onSubmit="return delete_check()">
                                <input type="hidden" name="id" value="<?php echo $row["ID"];?>">
                                <input type="hidden" name="is_delete" value="delete">
                                <input type="hidden" name="year_table" value="<?php echo $now_years;?>">
                                <input type="submit" value="削除">
                            </form>
                        </td>
                        <?php } ?>
                        <td><?php echo $row["post_trade_year"];?>年<?php echo $row["post_trade_month"];?>月 </td>
                        <td><?php echo number_format($row["post_profit"]);?> 円 </td>

                        <td>
                            <?php if($row["post_loss"] != 0){?>
                                <font color="red">-<?php echo number_format($row["post_loss"]);?> </font>円
                            <?php }else{ ?>
                                 0円
                            <?php } ?>
                        </td>
                        <td>
                            <?php if($row["increase"] < 0){?>
                                <font color="red"><?php echo number_format($row["increase"]);?> </font>円
                            <?php }else{ ?>
                                <?php echo number_format($row["increase"]);?> 円 
                            <?php } ?>
                        </td>
                        <td>
                            <?php if($row["interest"] < 0){?>
                                <font color="red"><?php echo number_format($row["interest"]);?> </font>％
                            <?php }else{ ?>
                                 <?php echo number_format($row["interest"]);?> ％ 
                            <?php } ?>
                        </td>
                        <td><?php echo number_format($row["balance"]); ?> 円 </td>
                        <td><?php echo $row["post_trade_number"];?> (<?php echo $row["post_trade_win_number"];?> /<?php echo $row["post_trade_lose_number"];?> )回 </td>
                        <td><?php echo $row["post_win_rate"];?> % </td>
                        <td><?php echo number_format($row["post_payment"]);?> 円  </td>
                        <td><?php echo number_format($row["post_withdrawal"]);?> 円  </td>
                        <?php if(current_user_can('administrator')){ ?>
                            <td><?php echo $zoom_plans_num;?>/<?php echo $kougi_plans_num;?> </td>
                        <?php } ?>
                    </tr>
            <?php } ?>
        <?php } ?>

    </table>

    
<?php if( current_user_can('administrator') && isset($_POST['is_list']) && isset($_POST['years']) ){ ?>

    <?php if($_POST['years'] != "all"){ ?>

        
    <?php 
     
        //最終ログイン
        $last_login = get_the_author_meta('last_login',$user_id);
                        
        if($last_login)
        {
            $the_login_date =  date('Y年m月d日 H時i分', $last_login);
        }
        else{
            $the_login_date = "";
        }
     
     ?>


        <table class="history-lsit-kougi-table">
            <tr>
                <th colspan="12">ザラ場/講義　参加回数</th>
            </tr>
            <tr>
                <?php for($i=1;$i<=12;$i++){ ?>
                    <th><?php echo $i;?>月</th>
                <?php } ?>
            </tr>
            <tr>
                <?php for($i=1;$i<=12;$i++){ ?>
                    <?php 
                         $zoom_data->getZoomSetMonthRow( $_POST['years'] , $i);
                   
                         $zoom_plans_num =  $zoom_data->checkUserZoomSetPlans( $user_id );
                        $kougi_plans_num =  $zoom_data->checkUserSetKougiPlans( $user_id );
                    ?>

                   <td><?php echo $zoom_plans_num;?>/<?php echo $kougi_plans_num;?> </td>
                <?php } ?>
            </tr>
            <tr>
                <th colspan="6">最終ログイン</th>
                <td colspan="6"><?php echo $the_login_date;?></td>
             </tr>

        </table>
    <?php } ?>
<?php } ?>





     <?php if($user_id != $user->ID || current_user_can('administrator')){?>
       
        <?php if(isset($_POST['years'])){?>
         <p>
          <?php 
          
            $set_years = $_POST["years"];
          
            if( $set_years == "all"){
                $set_years = $years;
            }
          
          ?>
          <a class="index_button" href="<?php $id = 43; echo get_page_link( $id )."?years=" .$set_years;?>"><?php echo $set_years;?>年成績一覧（管理者）へ</a>
        </p>
        <p>
            <a class="index_button" href="<?php $id = 43; echo get_page_link( $id );?>">成績一覧（管理者）</a>
        </p>
        <?php } ?>
    <?php } ?>
<?php
    }else {
?>
	    <p>
            <div class="page_div_box">成績がありません</div>
        </p>

        
<?php if( current_user_can('administrator') && isset($_POST['is_list']) && isset($_POST['years']) ){ ?>


     <?php 
     
        //最終ログイン
        $last_login = get_the_author_meta('last_login',$user_id);
                        
        if($last_login)
        {
            $the_login_date =  date('Y年m月d日 H時i分', $last_login);
        }
        else{
            $the_login_date = "";
        }
     
     ?>


      <table class="history-lsit-kougi-table">
        <tr>
            <th colspan="12">ザラ場/講義　参加回数</th>
        </tr>
        <tr>
            <?php for($i=1;$i<=12;$i++){ ?>
                  <th><?php echo $i;?>月</th>
            <?php } ?>
        </tr>
        <tr>
            <?php for($i=1;$i<=12;$i++){ ?>

                <?php 
                     $zoom_data->getZoomSetMonthRow( $_POST['years'] , $i);
                   
                     $zoom_plans_num =  $zoom_data->checkUserZoomSetPlans( $user_id );
                     $kougi_plans_num =  $zoom_data->checkUserSetKougiPlans( $user_id );
                ?>

                   <td><?php echo $zoom_plans_num;?>/<?php echo $kougi_plans_num;?> </td>
            <?php } ?>
        </tr>

        <tr>
                <th colspan="6">最終ログイン</th>
                <td colspan="6"><?php echo $the_login_date;?></td>
         </tr>

      </table>
<?php } ?>

        <a class="index_button" href="<?php $id = 35; echo get_page_link( $id );?>">成績入力</a>
<?php   
    }

?>






