
<?php 

    require_once("historyClass.php");

    // wpdbオブジェクト
    global $wpdb;

    $user = wp_get_current_user();

    //クラス作成
    $input_data= new HistoryClass();

    //初期化
    $input_data->init();

    // データ取得クエリ実行
    $input_data->getInputHistoryRow($user->ID);


    //文字用の変数
    $insert_str = "";
    $updata_str = "";
    $samedata_str = "";

   // var_dump($user);

   //echo $user->ID;

    //保存
    if(isset($_POST['is_save']) )
    {
        //postデータのセット
        $input_data->setPostData($_POST);

        //新規 or 更新
        $insert =  $input_data->checkIsUpdata();

        //必ず新規
        if($insert)
        {
            $insert_sql = $input_data->insertTradeData($user->ID);

            if ( $insert_sql == false ) {
	            $insert_str =   '登録に失敗しました';
            } else {
	             $insert_str =  '登録しました';
            }
        }
        else {
        
             //日付が被っているものがあれば、削除をさせる
            $is_date = false;//$input_data->checkRegistrationDate();

            if(!$is_date)
            {
                $updata_sql = $input_data->upDataTradeData($user->ID);

                if ( $updata_sql == false ) {
	                $updata_str =  '更新に失敗しました';
                } else {
	                $updata_str = '更新しました';
                }
                
            }
            else {
                 $samedata_str =  '日付が同じものがあるので更新できません。こちらのデータを削除するか、すでにある日付のものを編集してください';
            }
        }
    }
    else if(isset($_POST['is_edit']) )
    {
        //編集画面
        if(isset($_POST['id']))
        {
            $input_data->setEditData($_POST);
        }
        else {
	        $input_data->setEditNwwData($user->ID,$_POST['set_years'],$_POST['set_month']);
        }

    }

    if( isset($_POST['set_years'])  && isset($_POST['set_month']))
    {
        if(!isset($_POST['is_edit']) )
        {
            $set_input_month = $input_data->getHistroyData("input_date");
        }
        else {
	        $set_input_month = $_POST['set_years'].'-' .sprintf('%02d', $_POST['set_month']);
        }

        //echo $set_input_month;



?>
<script>
     $(function(){

        var $input_win = $('#input_trade_win_number');
        var $input_lose = $('#input_trade_lose_number');
        var $output = $('#input_trade_total_number');

    
        $input_win.on("keyup",function(){
            $output.text( parseInt($input_win.val()) + parseInt($input_lose.val()));
 
        });

        $input_lose.on("keyup",function(){
              $output.text( parseInt($input_win.val()) + parseInt($input_lose.val()));
 
        });

        var $input_win_profit = $('#input_profit');
        var $input_lose_profit = $('#input_loss');
        var $output_profit = $('#input_trade_total_profit');

    
        $input_win_profit.on("keyup",function(){
            $output_profit.text( parseInt($input_win_profit.val()) - parseInt($input_lose_profit.val()));
 
        });

        $input_lose_profit.on("keyup",function(){
              $output_profit.text( parseInt($input_win_profit.val()) - parseInt($input_lose_profit.val()));
 
        });


         
     });
</script>

<?php if($insert_str != ""){ ?>
    <div class="index_updata_center">
        <p><?php echo $insert_str;?></p>
    </div>
   
<?php }?>

<?php if($updata_str != ""){ ?>
    <div class="index_updata_center">
        <p><?php echo $updata_str;?></p>
    </div>
   
<?php }?>

<?php if($samedata_str != ""){ ?>
    <div class="index_updata_center">
        <p><?php echo $samedata_str;?></p>
    </div>
   
<?php }?>

<div style="text-align: center;">
<font color="red" size="4">*数値入力にはマイナスをつけないでください</font><br/>
<font color="red" size="4">*漢字やカンマ（,)等を入れないでください</font>

</div>
<form action="<?php  $id = 35; echo get_page_link( $id );?>" method="post">
    <input type="hidden" name="input_date" value="<?php echo $set_input_month;?>" >
    <div class="c-table-container">
        <table class="c-table">
        <tbody>
        <tr>
            <th>トレード月</th>
            <td><input type="month" name="disa_date" value="<?php echo $set_input_month;?>"  disabled></input></td>
        </tr>
   
        <tr>
            <th>利益</th>
            <td>
                <input type="number"  name="input_profit" id="input_profit" value="<?php echo $input_data->getHistroyData("input_profit");?>"  min="0">円</input>
            </td>
        </tr>

        <tr>
            <th>損失</th>
            <td>
                <div style=" display: flex;"><input type="number"   name="input_loss"  id="input_loss"  value="<?php echo $input_data->getHistroyData("input_loss");?>"  min="0">円</input></div>
            </td>
        </tr>
       
         <tr>
            <th>利益合計</th>
            <td>
                <span id="input_trade_total_profit"><?php echo  $input_data->getHistroyData("input_profit") - $input_data->getHistroyData("input_loss");?></span>円
            </td>
        </tr>
       
        <tr>
            <th>勝トレード数</th>
            <td>
                <input type="number"   name="input_trade_win_number" id="input_trade_win_number"  value="<?php echo $input_data->getHistroyData("input_trade_win_number");?>"  min="0">回</input>
            </td>
        </tr>
    
        <tr>
            <th>負トレード数</th>
            <td>
                <input type="number"   name="input_trade_lose_number" id="input_trade_lose_number"  value="<?php echo $input_data->getHistroyData("input_trade_lose_number");?>"  min="0">回</input>
            </td>
        </tr>
        
         <tr>
            <th>総トレード数</th>
            <td>
                 <span id="input_trade_total_number"><?php echo  $input_data->getHistroyData("input_trade_win_number") + $input_data->getHistroyData("input_trade_lose_number");?></span>回
            </td>
        </tr>
        

        <tr>
            <th>先月末残高</th>
            <td>
               <?php 
                    if(isset($input_data->input_data["last_month_balance"])){
                        echo $input_data->getHistroyData("last_month_balance")."円";
             ?>
                         <input type="hidden"   name="last_month_balance"  value="<?php echo $input_data->getHistroyData("last_month_balance");?>">
             <?php
                    }
                    else {
                        echo "--------------------";
                    }
               ?>
            </td>
        </tr>
        <tr>
            <th>月末残高</th>
            <td>
                <input type="number"   name="input_balance"  value="<?php echo $input_data->getHistroyData("input_balance");?>"  min="0">円</input>
            </td>
        </tr>

        <tr>
            <th>入金額</th>
            <td>
                <input type="number"   name="input_payment"  value="<?php echo $input_data->getHistroyData("input_payment");?>"  min="0">円</input>
            </td>
        </tr>
   
        <tr>
            <th>出金額</th>
            <td>
                <input type="number"   name="input_withdrawal"  value="<?php echo $input_data->getHistroyData("input_withdrawal");?>"  min="0">円</input>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="input_id" value="<?php echo $input_data->getHistroyData("input_id");?>">
                <input type="hidden" name="is_save" value="save">
                <input type="hidden" name="set_years" value="<?php echo $_POST['set_years'];?>">
                <input type="hidden" name="set_month" value="<?php echo $_POST['set_month'];?>">
                <input class="index_history_button" type="submit" value="送信">
                </form>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <form action="<?php  $id = 35; echo get_page_link( $id );?>" method="post">
                    <input type="hidden" name="year_table" value="<?php echo $_POST['set_years'];?>">
                    <input  class="index_history_button" type="submit" value="登録月一覧へ">
                </form>
            </td>
        </tr>
        </tbody>

        </table>
    </div>

 



<?php }else{ ?>
   <?php 
        $user = wp_get_current_user();

        $input_data->getHistoryListRow($user->ID);

        // 配列を計算して出しなおす
        $rows = $input_data->make_new_array($input_data->history_row);

        $years = date("Y");

        //年テーブルを作成
        $years_table = array();

        //$years_table[] = 2020;

        for($i=$years;$i>=2020;$i--)
        {
            $years_table[] = $i;
        }

        if(isset($_POST['year_table']) )
        {
            $now_years = $_POST['year_table'];
        }
        else {
            $now_years = $years;
        }
        //var_dump($rows);
   ?>

<div class="page_div_box">
    <p>
        <form action="<?php  $id = 35; echo get_page_link( $id );?>" method="post">
            <select name="year_table"  class="same-user-select">
            <?php foreach ($years_table as  $value) {?>
                <option value="<?php echo $value;?>" <?php if($now_years == $value){ echo "selected";}?>><?php echo $value;?>年</option>
            <?php } ?>
            </select>
            <input type="submit" value="年度変更"   class="same-user-select">
        </form>
    </p>


    <table class="user-table-two">
        <colgroup span="11"></colgroup>
        <tr>
            <th>登録月</th>
            <th>月末残高</th>
            <th>編集</th>
        </tr>
        <?php for($i=1;$i<=12;$i++){?>
            <tr>
                
                <td><?php echo $now_years;?>年<?php echo $i;?>月 </td>
                <?php 
                    $is_years = false;
                    $is_disp_years = false;
                 ?>

                <?php if($rows){?>

                    <?php foreach ($rows as $row) {?>
                        <?php 
                            if($now_years == $row["post_trade_year"] && $i == $row["post_trade_month"] && $user->ID ==$row["post_author"])
                            {
                                $is_years = true;
                            }
                        ?>
                    
                        <?php if($is_years){?>
                            <td><?php echo $row["balance"]; ?> 円</td>
                            <td>
                                <form action="<?php  $id = 35; echo get_page_link( $id );?>" method="post">
                                    <input type="hidden" name="id" value="<?php echo $row["ID"];?>">
                                    <input type="hidden" name="set_years" value="<?php echo $now_years;?>">
                                    <input type="hidden" name="set_month" value="<?php echo $i;?>">
                                    <input type="hidden" name="is_edit" value="edit">
                                    <input type="submit" value="編集">
                                </form>
                            </td>
                        
                            <?php 
                                $is_years = false;
                                $is_disp_years =true;
                            ?>
                        <?php }?>
                    <?php } ?>
                <?php }else{ ?>


                <?php } ?>
                    <?php if(!$is_disp_years){?>
                        <td>------------- 円 </td>
                        <td>
                            <form action="<?php  $id = 35; echo get_page_link( $id );?>" method="post">
                                <input type="hidden" name="set_years" value="<?php echo $now_years;?>">
                                <input type="hidden" name="set_month" value="<?php echo $i;?>">
                                <input type="hidden" name="is_edit" value="edit">
                                <input type="submit" value="編集">
                            </form>
                        </td>
                    <?php } ?>
                 


                 
            </tr>
        <?php } ?>
    </table>
</div>




<?php } ?>