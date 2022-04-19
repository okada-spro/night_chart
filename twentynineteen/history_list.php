<?php

    require_once("historyClass.php");

    $user = wp_get_current_user();

    //クラス作成
    $input_data= new HistoryClass();

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

    $input_data->getHistoryListRow($user_id);

    // 配列を計算して出しなおす
    $rows = $input_data->make_new_array($input_data->history_row);



    if($input_data->history_row){




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


<?php if($delete_str != ""){ ?>
     <div class="index_updata_center">
    <p><?php echo $delete_str;?></p>
    </div>
<?php }?>


   
   
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
        <?php } ?>
       
        
    
     <table class="user-table history_list">
        <colgroup span="11"></colgroup>
        <tr>
            <?php //ユーザー以外でもボタン見えるように変更?>
            <?php //if($user_id == $user->ID){?>
                <th class="fixed_th_1">編集</th>
                <th class="fixed_th_2">削除</th>
            <?php //} ?>
            <th class="fixed_th_3">登録月</th>
            <th>月分益</th>
            <th>月分損</th>
            <th>資金増減</th>
            <th>月利</th>
            <th>月末残高</th>
            <th>トレード回数</th>
            <th>勝率</th>
            <th>入金</th>
            <th>出金</th>
        </tr>
        <?php foreach ($rows as $row) {?>
            <?php $is_years = true;?>
                <?php 
                    if( isset($_POST['years'])){
                        if($_POST['years'] != $row["post_trade_year"])
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
                 ?>
                 <?php if($is_years){?>
                    <tr>
                        <?php if($user_id == $user->ID){?>
                        <td class="fixed_th_1">
                            <form action="<?php  $id = 35; echo get_page_link( $id );?>" method="post">
                                <input type="hidden" name="id" value="<?php echo $row["ID"];?>">
                                <input type="hidden" name="set_years" value="<?php echo $row["post_trade_year"];?>">
                                <input type="hidden" name="set_month" value="<?php echo $row["post_trade_month"];?>">
                                 
                                <input type="hidden" name="is_edit" value="edit">
                                <input type="submit" value="編集">
                            </form>
                        </td>
                        <td class="fixed_th_2">
                            <form action="<?php  $id = 37; echo get_page_link( $id );?>" method="post"  onSubmit="return delete_check()">
                                <input type="hidden" name="id" value="<?php echo $row["ID"];?>">
                                <input type="hidden" name="is_delete" value="delete">
                                <input type="hidden" name="year_table" value="<?php echo $now_years;?>">
                                <input type="submit" value="削除">
                            </form>
                        </td>
                        <?php }else{ ?>
                            <td class="fixed_th_1">
                                <input type="button" value="編集" class="no_push" disabled="disabled" >
                            </td>
                            <td class="fixed_th_2">
                            <input type="button" value="削除" class="no_push">
                            </td>
                        <?php } ?>
                        <td class="fixed_th_3"><?php echo $row["post_trade_year"];?>年<?php echo $row["post_trade_month"];?>月 </td>
                        <td><?php echo $row["post_profit"];?> 円 </td>

                        <td>
                            <?php if($row["post_loss"] != 0){?>
                                <font color="red">-<?php echo $row["post_loss"];?> </font>円
                            <?php }else{ ?>
                                 0円
                            <?php } ?>
                        </td>
                        <td>
                            <?php if($row["increase"] < 0){?>
                                <font color="red"><?php echo $row["increase"];?> </font>円
                            <?php }else{ ?>
                                <?php echo $row["increase"];?> 円 
                            <?php } ?>
                        </td>
                        <td>
                            <?php if($row["interest"] < 0){?>
                                <font color="red"><?php echo $row["interest"];?> </font>％
                            <?php }else{ ?>
                                 <?php echo $row["interest"];?> ％ 
                            <?php } ?>
                        </td>
                        <td><?php echo $row["balance"]; ?> 円 </td>
                        <td><?php echo $row["post_trade_number"];?> (<?php echo $row["post_trade_win_number"];?> /<?php echo $row["post_trade_lose_number"];?> )回 </td>
                        <td><?php echo $row["post_win_rate"];?> % </td>
                        <td><?php echo $row["post_payment"];?> 円  </td>
                        <td><?php echo $row["post_withdrawal"];?> 円  </td>
                    </tr>
            <?php } ?>
        <?php } ?>
    </table>

     <?php if($user_id != $user->ID || current_user_can('administrator')){?>
       
        <?php if(isset($_POST['years'])){?>
         <p>
           
          <a class="index_button" href="<?php $id = 43; echo get_page_link( $id )."?years=" .$_POST["years"];?>"><?php echo $_POST['years'];?>年成績一覧（管理者）へ</a>
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
        <a class="index_button" href="<?php $id = 35; echo get_page_link( $id );?>">成績入力</a>
<?php   
    }
?>


