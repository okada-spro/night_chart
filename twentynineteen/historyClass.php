<?php




class HistoryClass
{
     // wpdbオブジェクト
   // global $wpdb;
//
    public $input_data ;//インプット用の配列

    public $history_row;//ヒストリーデータ呼び出し

    public function __construct()
    {
       $this->input_data["input_id"] = 0;
        $this->input_data["input_date"] = date('Y-m');
        $this->input_data["input_profit"] = 0;
        $this->input_data["input_loss"] = 0;
        $this->input_data["input_increase"] = 0;
        $this->input_data["input_balance"] = 0;
        $this->input_data["input_trade_win_number"] = 0;
        $this->input_data["input_trade_lose_number"] = 0;
        $this->input_data["input_trade_number"] = 0;
        $this->input_data["input_win_rate"] = 0;
        $this->input_data["input_withdrawal"] = 0;
        $this->input_data["input_payment"] = 0;
        $this->input_data["input_remarks"] = "";
        $this->input_data["unixdate"] = "";
    }


    //初期化
    public  function init()
    {
        $this->input_data["input_id"] = 0;
        $this->input_data["input_date"] = date('Y-m');
        $this->input_data["input_profit"] = 0;
        $this->input_data["input_loss"] = 0;
        $this->input_data["input_increase"] = 0;
        $this->input_data["input_balance"] = 0;
        $this->input_data["input_trade_win_number"] = 0;
        $this->input_data["input_trade_lose_number"] = 0;
        $this->input_data["input_trade_number"] = 0;
        $this->input_data["input_win_rate"] = 0;
        $this->input_data["input_withdrawal"] = 0;
        $this->input_data["input_payment"] = 0;
        $this->input_data["input_remarks"] = "";
        $this->input_data["unixdate"] = "";
    }



    public  $disp_array_data = array(
            0=>"直近４ヶ月を表示",
            1=>"直近６ヶ月を表示",
            2=>"１２ヶ月を表示",
     );

     public  $is_trade_array_data = array(
            0=>"期間内の提出者",
            1=>"期間内の未出者",
            2=>"全て表示",
     );


    //DBデータの入力用の情報を取得
    public function getInputHistoryRow($user_id)
    {
         // wpdbオブジェクト
        global $wpdb;

        $query ="SELECT * FROM wp_user_trade_data  WHERE post_author = %d";
        $sql = $wpdb->prepare($query,$user_id);
        //sql = $wpdb->prepare("SELECT * FROM wp_user_trade_data  WHERE post_author=".$user_id );
        $row = $wpdb->get_results($sql);

        if(isset($row))
        {
            $this->history_row = $row;
        }
        else {
            $this->history_row = array();
        }
    }

    //管理者用に全員のデータを取得
    public function getTradeHistoryRowAll()
    {
         // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM wp_user_trade_data";//$wpdb->prepare("SELECT * FROM wp_user_trade_data" );
        $this->history_row = $wpdb->get_results($sql);
    }

    //管理者用に全員のデータを年別に取得
    public function getTradeHistoryRowYear()
    {
         // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM wp_user_trade_data";//$wpdb->prepare("SELECT * FROM wp_user_trade_data");
        $this->history_row = $wpdb->get_results($sql);
    }

    //DBデータからトレードデータ一覧を情報を取得（ソート込み）
    public function getHistoryListRow($user_id)
    {
         // wpdbオブジェクト
        global $wpdb;


        $query ="SELECT * FROM wp_user_trade_data  WHERE post_author = %d ORDER BY post_trade_unixtime";
        //$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->user_trade_data."  WHERE post_author=".$user_id ." ORDER BY post_trade_unixtime DESC");
        $sql = $wpdb->prepare($query,$user_id);




        $this->history_row = $wpdb->get_results($sql);
    }


    //データの取得
    public function getHistroyData($input_str)
    {
        return $this->input_data[$input_str];
    }

     //データのセット
    public function setHistroyData($input_str,$set_data)
    {
       $this->input_data[$input_str] = $set_data;
    }


    //POSTデータの設置
    public  function setPostData($postData)
    {

        //var_dump($postData);

        if(isset($postData['input_id']) )
        {
            $this->input_data["input_id"] = $postData['input_id'];
        }

        $this->input_data["input_date"] = $postData["input_date"];

        $dates = explode("-",$this->input_data["input_date"]);

        $this->input_data["input_year"] = intval($dates[0]);
        $this->input_data["input_month"] = intval($dates[1]);

        $this->input_data["unixdate"] = new DateTime($this->input_data["input_year"].'-' .$this->input_data["input_month"] .'-01 00:00:00');

        // echo $_POST["input_date"]."<br/>";
        $this->input_data["input_balance"] = $postData["input_balance"];
        $this->input_data["input_trade_win_number"] = $postData["input_trade_win_number"];
        $this->input_data["input_trade_lose_number"] =$postData["input_trade_lose_number"];
        $this->input_data["input_profit"] = $postData["input_profit"];
        $this->input_data["input_loss"] = $postData["input_loss"];
        $this->input_data["input_increase"] = $this->input_data["input_profit"] - $this->input_data["input_loss"]  ;

        $this->input_data["input_trade_number"] = $this->input_data["input_trade_win_number"] + $this->input_data["input_trade_lose_number"];

        if($this->input_data["input_trade_win_number"] > 0)
        {
            $this->input_data["input_win_rate"] = floor (($this->input_data["input_trade_win_number"] / $this->input_data["input_trade_number"]) * 10000) / 100;
        }
        else {
             $this->input_data["input_win_rate"] =0;
        }

        if(isset($postData['last_month_balance']) ){
            $this->input_data["last_month_balance"] = $postData["last_month_balance"];
        }

        $this->input_data["input_withdrawal"] = $postData["input_withdrawal"];
        $this->input_data["input_payment"] = $postData["input_payment"];
        $this->input_data["input_remarks"] = "";//$_POST["input_remarks"];
    }

    //これは上書きする情報かどうか ture:新規 false；上書き
    public function checkIsUpdata()
    {
        if(!$this->history_row)
        {
            return true;
        }
        else
        {
            //被ってる場合は上書き
            $is_same = false; //この状態だと新規

            foreach ($this->history_row as $row)
            {
                if($row->post_trade_year == $this->input_data["input_year"] && $row->post_trade_month == $this->input_data["input_month"])
                {
                     $is_same = true;//更新
                     break;
                }
            }

            if(!$is_same)
            {
                //新規
                return true;
            }

            return false;//更新
        }
    }


    //入力画面等編集データのセット
    public function setEditData($postData)
    {
        foreach ($this->history_row as $row)
        {
            if($row->ID == $postData['id'])
            {
                $this->input_data["input_id"] =  $row->ID;
                $this->input_data["input_date"] = $row->post_trade_year .'-' .sprintf('%02d', $row->post_trade_month);
                $this->input_data["trade_year"] =  $row->post_trade_year;
                $this->input_data["trade_month"] =  $row->post_trade_month;
                $this->input_data["input_profit"] = $row->post_profit;
                $this->input_data["input_loss"] = $row->post_loss;
                $this->input_data["input_increase"] = $row->post_increase;
                $this->input_data["input_trade_number"] = $row->post_trade_number;
                $this->input_data["input_win_rate"]  = $row->post_win_rate;
                $this->input_data["input_withdrawal"] = $row->post_withdrawal;
                $this->input_data["input_payment"]= $row->post_payment;
                $this->input_data["input_balance"] = $row->post_balance;
                $this->input_data["input_trade_win_number"] = $row->post_trade_win_number;
                $this->input_data["input_trade_lose_number"] =$row->post_trade_lose_number;
                $this->input_data["post_author"] =$row->post_author;
                break;
            }
        }


        //前月残高を調べる
        $month = $this->input_data["trade_month"] - 1;
        $years =  $this->input_data["trade_year"];

        if($month <= 0){
            $years -= 1;
            $month = 12;
        }

        //echo $years;

        //前月のを入れる
        foreach ($this->history_row as $row)
        {
            if($this->input_data["post_author"] == $row->post_author && $month == $row->post_trade_month && $years == $row->post_trade_year)
            {
                $this->input_data["last_month_balance"] =  $row->post_balance;
                break;
            }
        }


    }


    //入力画面等編集データのs新規でセット
    public function setEditNwwData($user_id,$set_years,$set_month)
    {
        //前月残高を調べる
        $month = $set_month - 1;
        $years =  $set_years;

        if($month <= 0){
            $years -= 1;
            $month = 12;
        }

        //echo $years;

        //前月のを入れる
        foreach ($this->history_row as $row)
        {
            if($user_id == $row->post_author && $month == $row->post_trade_month && $years == $row->post_trade_year)
            {
                $this->input_data["last_month_balance"] =  $row->post_balance;
                break;
            }
        }
    }



    //トレードデータを新規登録
    public function insertTradeData($user_id)
    {
        // wpdbオブジェクト
        global $wpdb;



          $insert = array(
            'post_author'=>$user_id,
            'post_date' => date("Y-m-d H:i:s"),
            'post_trade_unixtime' => intval($this->input_data["unixdate"]->format('U')),
            'post_trade_year'  =>  $this->input_data["input_year"],
            'post_trade_month' => $this->input_data["input_month"],
            'post_profit' => intval($this->input_data["input_profit"]),
            'post_loss' =>  intval($this->input_data["input_loss"]),
            'post_increase' =>  intval($this->input_data["input_increase"]),
            'post_balance' =>  intval($this->input_data["input_balance"]),
            'post_trade_win_number' =>  intval($this->input_data["input_trade_win_number"]),
            'post_trade_lose_number' =>  intval($this->input_data["input_trade_lose_number"]),
            'post_trade_number' =>  intval($this->input_data["input_trade_number"]),
            'post_win_rate' => $this->input_data["input_win_rate"],
            'post_withdrawal' =>  intval($this->input_data["input_withdrawal"]),
            'post_payment' =>   intval($this->input_data["input_payment"]),
            'post_comment' => $this->input_data["input_remarks"]
         );

         //var_dump($insert);

         $dataFormat = array('%d','%s','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%f','%d','%d','%s');


         $sql_rsl = $wpdb->insert('wp_user_trade_data', $insert, $dataFormat);

         if ( $sql_rsl == false ) {
            //登録失敗
            return false;
         }
         else {
            //登録成功
            return true;
         }
    }

    //トレードデータ更新
    public function upDataTradeData($user_id)
    {
        // wpdbオブジェクト
        global $wpdb;


        //ここは更新
        $updata = array(
            'post_date' => date("Y-m-d H:i:s"),
            'post_trade_unixtime' => $this->input_data["unixdate"]->format('U'),
            'post_trade_year'  =>  $this->input_data["input_year"],
            'post_trade_month' => $this->input_data["input_month"],
            'post_profit' => $this->input_data["input_profit"],
            'post_loss' => $this->input_data["input_loss"],
            'post_increase' => $this->input_data["input_increase"],
            'post_balance' => $this->input_data["input_balance"],
            'post_trade_win_number' => $this->input_data["input_trade_win_number"],
            'post_trade_lose_number' => $this->input_data["input_trade_lose_number"],
            'post_trade_number' => $this->input_data["input_trade_number"],
            'post_win_rate' => $this->input_data["input_win_rate"],
            'post_withdrawal' => $this->input_data["input_withdrawal"],
            'post_payment' =>  $this->input_data["input_payment"],
            'post_comment' => $this->input_data["input_remarks"]
         );

         //更新したい行の条件
          $condition = array(
              'ID' => $this->input_data["input_id"],
           );

           $dataFormat = array('%s','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%f','%d','%d','%s');
           $conditionsFormat = array('%d');
           $sql_rsl = $wpdb->update('wp_user_trade_data', $updata, $condition,$dataFormat,$conditionsFormat);


           if ( $sql_rsl == false ) {
	            //更新失敗
                return false;
            } else {
	            //更新成功
                return true;
           }
    }

    //同じ日付が登録されているかどうか
    public function checkRegistrationDate()
    {
        foreach ($this->history_row as $row)
        {
            if($row->ID != $this->input_data["input_id"] &&  $row->post_trade_year == $this->input_data["input_year"] &&  $row->post_trade_month == $this->input_data["input_month"])
            {
                return true;//被っている
            }
        }

        return false;
    }


     //トレードデータ削除
    public function deleteTradeData($user_id,$post_id)
    {
        // wpdbオブジェクト
        global $wpdb;

         //更新したい行の条件
          $condition = array(
              'ID' => $post_id,
           );

           $conditionsFormat = array('%d');
           $sql_rsl = $wpdb->delete('wp_user_trade_data', $condition,$conditionsFormat);


           if ( $sql_rsl == false ) {
	            //削除失敗
                return false;
            } else {
	            //削除成功
                return true;
           }
     }


    //表示用に月利を入れなおす
    public function make_new_array($rows)
    {
        $is_data = false;//データがあったかどうか

        $new_array = array();

        //先月の末の資産
        $last_balance = 0;

        //今月末の資産
        $balance = 0;

        $row_count = 0;

        foreach ($rows as $row)
        {
            if($row)
            {
                $is_data = true;


                if($row_count == 0)
                {
                    //初回のみ入金額が資産になる
                    $last_balance = $row->post_payment;
                }
                else {
                    //前回のを入れる
	                $last_balance = $balance;
                }


                $row_count++;

                //月の増減
                $Increase =$row->post_increase;
                //今月残高
                $balance =  $row->post_balance;

                //計算用の今月残高
                $math_balance =  $row->post_balance - $row->post_payment + $row->post_withdrawal;

                //月利？（月残高の増減 / 先月残高) * 100

                if($math_balance > 0 && $last_balance > 0)
                {

                    $interest = floor( (( $math_balance  / $last_balance ) -1) * 10000) / 100;
                }
                else {
	                $interest = 0;
                }



                $set_array = array(
                "ID"=>$row->ID,
                "post_date"=>$row->post_date,
                "post_trade_unixtime"=>$row->post_trade_unixtime,
                "post_trade_year"=>$row->post_trade_year,
                "post_trade_month"=>$row->post_trade_month,
                "post_profit"=>$row->post_profit,
                "post_loss"=>$row->post_loss,
                "post_balance" => $row->post_balance,
                "post_trade_win_number" => $row->post_trade_win_number,
                "post_trade_lose_number" => $row->post_trade_lose_number,
                "post_trade_number"=>$row->post_trade_number,
                "post_win_rate"=>$row->post_win_rate,
                "post_withdrawal"=>$row->post_withdrawal,
                "post_payment"=>$row->post_payment,
                "post_comment"=>$row->post_comment,
                "post_author"=>$row->post_author,

                "increase"=>$Increase,
                "interest"=>$interest,
                "balance"=>$balance,
                );

                array_unshift($new_array,$set_array);
            }
        }

        //var_dump($new_array);

        if( !$is_data )//データがなかった
        {
            $new_array = "";
        }

        return $new_array;

    }


    //ユーザーの最大トレード年を取得する
    public function getIsYearsArray()
    {
         $years_array = array();

         // var_dump($this->history_row);

         foreach ($this->history_row as $row) {

            if(!in_array($row->post_trade_year, $years_array))
            {
                array_unshift($years_array,$row->post_trade_year);
            }
         }

         //ソート

        // var_dump($years_array);

         rsort($years_array);

         return $years_array;
    }

    //履歴をユーザー毎に並べなおす
    public function setUserTradeData($years)
    {
        //ユーザーの全データを取得
	    $users = get_users( array('orderby'=>'ID','order'=>'ASC') );

        //ユニックスデータを作成
        $unix_array = array();


        $now_year = date('Y');
        $est_unix = 0;

        for( $i=2020;$i<=$now_year;$i++)
        {
             for( $j=1;$j<=12;$j++)
             {
                $unixdate = new DateTime($i.'-' .$j .'-01 00:00:00');

                array_push($unix_array , $unixdate->format('U') );
             }

        }

        //全ユーザーの空データを作成
        $trade_user_base_array = array();
         $trade_user_array = array();

        foreach ($users as $row)
        {
             foreach ($unix_array as $unix_row)
             {
                $trade_array[ $row->ID ][ $unix_row ] = "";
             }
        }


       //  var_dump($unix_array);




       // var_dump($this->history_row);

        //まずユーザー毎に分割
        foreach ($this->history_row as $row)
        {
            $user_info = get_userdata( $row->post_author );

            if($user_info){
                $trade_array[$row->post_author][$row->post_trade_unixtime] = $row;
            }
        }




        //新規配列
        $new_array = array();

        //ユーザー毎にユニックスタイムのソート
        foreach ($trade_array  as $key => $value)
        {
            ksort($trade_array[$key]);

            $make_array = $this->make_new_array($trade_array[$key]);

            if($make_array)
            {
                //過去に１回でも入力があったもの

                $is_input = false;//入力が期間内にあったか？

                //作ったものを入れなおす
                foreach ($make_array as $row)
                {
                    if($years == $row["post_trade_year"])
                    {
                        $new_array[$key]["days"][$row["post_trade_month"]] = $row;

                        $is_input = true;
                    }
                }

                if($is_input)
                {
                     $new_array[$key]["disp"] = true;
                }
                else{
                     $new_array[$key]["disp"] = false;
                }

                if(!isset($new_array[$key]["days"])){
                   $new_array[$key]["days"][13] = "";
                }
             }
             else{
               $new_array[$key]["days"][13] = "";
                $new_array[$key]["disp"] = false;
             }
        }


        /* var_dump($new_array);

             echo "<br />";
             echo "<br />";
             echo "<br />";
             echo "<br />";
             echo "<br />";
             echo "<br />";
        */





         return $new_array;
    }
}









?>
