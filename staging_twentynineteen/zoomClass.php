<?php 

class ZoomClass
{
    public $input_data ;//インプット用の配列

    public $zoom_row;//データ呼び出し

    public $zoom_last_row;//データ呼び出し(最新)

    public $zoom_month_row;//データ呼び出し(今月)
    public $zoom_set_month_row;//データ呼び出し(指定月)


     //ZOOM会議の種類
     public  $zoom_catagory_array_data = array(
            0=>"カテゴリーなし",
            1=>"ザラ場指導",
            2=>"株 講義",
            3=>"FX 講義",
     );

     
     //ZOOM会議の参加不可
     public  $zoom_join_array_data = array(
            0=>"全員参加",
            1=>"訓練生は参加不可",
            2=>"門下生は参加不可",
            3=>"訓練生と動画会員は参加不可",
            4=>"動画会員は参加不可",
     );

     //定義(会議の種類)
     public const ZOOM_NO_CATEGORY = 0;
     public const ZOOM_ZARABA_CATEGORY = 1;
     public const ZOOM_KOUGI_CATEGORY = 2;
     public const ZOOM_FX_KOUGI_CATEGORY = 3;


     public const ZOOM_ALL_CATEGORY = 99;

     
     //定義(参加の種類)
     public const ZOOM_JOIN_ALL = 0;
     public const ZOOM_JOIN_NO_KUNRENSEI = 1;
     public const ZOOM_JOIN_NO_MONKASEI = 2;
     public const ZOOM_JOIN_NO_KUNRENSEI_DOUGA = 3;
     public const ZOOM_JOIN_NO_DOUGA = 4;


    public function __construct()
    {
        $this->input_data["input_id"] = 0;
        $this->input_data["input_zoom_day"] = $this->changeSetData(date('Y-M-d 08:00', strtotime('+9hour')));
        $this->input_data["input_zoom_start_day"] = $this->changeSetData(date('Y-M-d 08:00', strtotime('+9hour')));
        $this->input_data["input_zoom_deadline"] = $this->changeSetData(date('Y-M-d 10:00', strtotime('+9hour')));
        $this->input_data["input_zoom_url"] = "";
        $this->input_data["input_zoom_title"] = "";
        $this->input_data["input_plans"] = "";
        $this->input_data["post_author"] = 0;
        $this->input_data["input_zoom_category"] = ZoomClass::ZOOM_ZARABA_CATEGORY;
        $this->input_data["input_zoom_joinType"]= ZoomClass::ZOOM_JOIN_NO_DOUGA;
    }

    //初期化
    public  function init()
    {
        $this->input_data["input_id"] = 0;
        $this->input_data["input_zoom_day"] = $this->changeSetData(date('Y-M-d 08:00', strtotime('+9hour')));
        $this->input_data["input_zoom_start_day"] = $this->changeSetData(date('Y-M-d 08:00', strtotime('+9hour')));
        $this->input_data["input_zoom_deadline"] =$this->changeSetData(date('Y-M-d 10:00', strtotime('+9hour')));
        $this->input_data["input_zoom_url"] = "";
        $this->input_data["input_zoom_title"] = "";
        $this->input_data["input_plans"] = "";
        $this->input_data["post_author"] = 0;
        $this->input_data["input_zoom_category"] = ZoomClass::ZOOM_ZARABA_CATEGORY;
        $this->input_data["input_zoom_joinType"]= ZoomClass::ZOOM_JOIN_NO_DOUGA;
    }


    //初期化(ザラ場)
    public  function init_zaraba()
    {
        $this->input_data["input_id"] = 0;
        $this->input_data["input_zoom_day"] = $this->changeSetData(date('Y-M-d 08:00', strtotime('+9hour')));
        $this->input_data["input_zoom_start_day"] = $this->changeSetData(date('Y-M-d 08:00', strtotime('+9hour')));
        $this->input_data["input_zoom_deadline"] =$this->changeSetData(date('Y-M-d 10:00', strtotime('+9hour')));
        $this->input_data["input_zoom_url"] = "";
        $this->input_data["input_zoom_title"] = date('Y/m/d', strtotime('+9hour')) ." ザラ場";
        $this->input_data["input_plans"] = "";
        $this->input_data["post_author"] = 0;
        $this->input_data["input_zoom_category"] = ZoomClass::ZOOM_ZARABA_CATEGORY;
        $this->input_data["input_zoom_joinType"]= ZoomClass::ZOOM_JOIN_NO_DOUGA;
    }

    //初期化(株講義)
    public  function init_kabu_kougi()
    {
        $this->input_data["input_id"] = 0;
        $this->input_data["input_zoom_day"] = $this->changeSetData(date('Y-M-d 21:00', strtotime('+9hour')));
        $this->input_data["input_zoom_start_day"] = $this->changeSetData(date('Y-M-d 22:00', strtotime('+9hour')));
        $this->input_data["input_zoom_deadline"] =$this->changeSetData(date('Y-M-d 23:59', strtotime('+9hour')));
        $this->input_data["input_zoom_url"] = "";
        $this->input_data["input_zoom_title"] =  date('Y/m/d', strtotime('+9hour')) ." 株講義";
        $this->input_data["input_plans"] = "";
        $this->input_data["post_author"] = 0;
        $this->input_data["input_zoom_category"] = ZoomClass::ZOOM_KOUGI_CATEGORY;
        $this->input_data["input_zoom_joinType"]= ZoomClass::ZOOM_JOIN_NO_DOUGA;
    }

      //初期化(FX講義)
    public  function init_fx_kougi()
    {
        $this->input_data["input_id"] = 0;
        $this->input_data["input_zoom_day"] = $this->changeSetData(date('Y-M-d 21:00', strtotime('+9hour')));
        $this->input_data["input_zoom_start_day"] = $this->changeSetData(date('Y-M-d 22:00', strtotime('+9hour')));
        $this->input_data["input_zoom_deadline"] =$this->changeSetData(date('Y-M-d 23:59', strtotime('+9hour')));
        $this->input_data["input_zoom_url"] = "";
        $this->input_data["input_zoom_title"] =  date('Y/m/d', strtotime('+9hour')) ." FX講義";
        $this->input_data["input_plans"] = "";
        $this->input_data["post_author"] = 0;
        $this->input_data["input_zoom_category"] = ZoomClass::ZOOM_FX_KOUGI_CATEGORY;
        $this->input_data["input_zoom_joinType"]= ZoomClass::ZOOM_JOIN_NO_DOUGA;
    }


    //DBデータの入力用の情報を取得(全データ取得でOK)
    public function getInputZoomRow($user_id)
    {
         // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM wp_zoom_data";//$wpdb->prepare("SELECT * FROM wp_zoom_data");
        $row = $wpdb->get_results($sql);

        if($row)
        {
            $this->zoom_row = $row;
        }
        else {
            $this->zoom_row = "";
        }

    }


    //DBデータのリスト用の情報を取得(全データ取得でOK)
    public function getListZoomRow($user_id)
    {
         // wpdbオブジェクト
        global $wpdb;


        $sql = "SELECT * FROM wp_zoom_data ORDER BY post_zoom_start_day DESC";//$wpdb->prepare("SELECT * FROM wp_zoom_data ORDER BY post_zoom_day DESC");
        $row = $wpdb->get_results($sql);

        if($row)
        {
            $this->zoom_row = $row;
        }
        else {
            $this->zoom_row = "";
        }



    }

     //DBデータの入力用の情報を取得(最後だけ取得)
    public function getInputZoomLastRow()
    {
         // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM wp_zoom_data ORDER BY ID DESC LIMIT 1";//$wpdb->prepare("SELECT * FROM wp_zoom_data ORDER BY ID DESC LIMIT 1");
        $row = $wpdb->get_results($sql);

        if($row)
        {
            $this->zoom_last_row = $row;
        }
        else {
            $this->zoom_last_row = "";
        }
    }


     //DBデータの入力用の情報を取得(ID指定)
    public function getInputZoomForID($input_id)
    {
         // wpdbオブジェクト
        global $wpdb;

         $query = "SELECT * FROM wp_zoom_data WHERE ID = %d";//$wpdb->prepare("SELECT * FROM wp_zoom_data ORDER BY ID DESC LIMIT 1");

        $sql = $wpdb->prepare($query,$input_id);
        $row = $wpdb->get_results($sql);

        if($row)
        {
            $this->zoom_row = $row;
        }
        else {
            $this->zoom_row = "";
        }
    }


    //現在の時間よりも最新のデーターを取得
    public function getZoomNowDayAfterRow()
    {
         // wpdbオブジェクト
        global $wpdb;

        date_default_timezone_set('Asia/Tokyo');

        $set_data = date("Y-m-d H:i:s");


        $query = "SELECT * FROM wp_zoom_data WHERE post_zoom_start_day > %s  ORDER BY post_zoom_start_day";//$wpdb->prepare("SELECT * FROM wp_zoom_data ORDER BY ID DESC LIMIT 1");

        $sql = $wpdb->prepare($query,$set_data);
        $row = $wpdb->get_results($sql);

        //var_dump($row);

        if($row)
        {
            $this->zoom_last_row = $row;
        }
        else {
            $this->zoom_last_row = "";
        }

       //var_dump($this->zoom_last_row);
    }


    //一番最新の参加しているデータを取得
    public function getPlanNew($user_id,$category_type)
    {
        if(!$this->zoom_last_row)
        {
            return "";//予定なし
        }

       
         $zoom_plan_data   = array();

        foreach ($this->zoom_last_row as $row)
        {
            $plan_data   = "";

            if($row->post_zoom_participant_plans)
            {
                $plan_data =explode(",",$row->post_zoom_participant_plans);
            }
            else{
                $plan_data  = "";
            }


            //var_dump($plan_data);

            if($plan_data)
            {
                for($i=0;$i<count($plan_data);$i++)
                {
                    if(($user_id == $plan_data[$i]) && ($category_type ==  $row->post_zoom_category || $category_type == ZoomClass::ZOOM_ALL_CATEGORY ))
                    {
                        
                        $zoom_plan_data["input_id"] =$row->ID;
                        $zoom_plan_data["input_zoom_day"] = $row->post_zoom_day;
                        $zoom_plan_data["input_zoom_start_day"] = $row->post_zoom_start_day;
                        $zoom_plan_data["input_zoom_deadline"] = $row->post_zoom_deadline;
                        $zoom_plan_data["input_zoom_url"] = $row->post_zoom_url;
                        $zoom_plan_data["input_zoom_title"] = $row->post_zoom_title;
                        $zoom_plan_data["input_zoom_category"] = $row->post_zoom_category;
                        $zoom_plan_data["input_zoom_joinType"] = $row->post_zoom_jointype;

                        

                         $zoom_plan_data["input_plans"]  ="";
                
                        if(isset($row->post_zoom_participant_plans))
                        {
                           
                             $zoom_plan_data["input_plans"] = array();

                            $participant_array = explode(",",$row->post_zoom_participant_plans);

                            foreach ($participant_array as $participant_num)
                            {
                                 $joins_info = get_userdata( $participant_num );

                                 if($joins_info){

                                    if(!$zoom_plan_data["input_plans"])
                                    {
                                        $zoom_plan_data["input_plans"] = array();
                                    }

                                    array_push( $zoom_plan_data["input_plans"],$participant_num);
                                 }
                            }
                        }
                        

                        return $zoom_plan_data;
               
                     }
                }
            }
        }
        
        return "";
    }



    
     //最新IDをデータ配列にセット
    public function setNewID()
    {
        foreach ($this->zoom_last_row as $row) 
        {
            $this->input_data["input_id"] =$row->ID;
        }
    }


     //データの取得
    public function getZoomData($input_str)
    {
        return $this->input_data[$input_str];
    }

     //データのセット
    public function setZoomData($input_str,$set_data)
    {
       $this->input_data[$input_str] = $set_data;
    }


    //入力画面等編集データのセット
    public function setEditData($postData)
    {
       // var_dump($postData);

        //echo $postData['id'];

        foreach ($this->zoom_row as $row) 
        {
            if($row->ID == $postData['id'])
            {
                $this->input_data["input_id"] =$postData['id'];
                $this->input_data["input_zoom_day"] = $row->post_zoom_day;
                $this->input_data["input_zoom_start_day"] = $row->post_zoom_start_day;
                $this->input_data["input_zoom_deadline"] = $row->post_zoom_deadline;
                $this->input_data["input_zoom_url"] = $row->post_zoom_url;
                $this->input_data["input_zoom_title"] = $row->post_zoom_title;
                $this->input_data["input_zoom_category"] = $row->post_zoom_category;
                $this->input_data["input_zoom_joinType"] = $row->post_zoom_jointype;

                $this->input_data["input_plans"]  = "";


                
                if($row->post_zoom_participant_plans)
                {

                    $participant_array = explode(",",$row->post_zoom_participant_plans);

                    foreach ($participant_array as $participant_num)
                    {
                        $joins_info = get_userdata( $participant_num );

                        if($joins_info){

                            if(!$this->input_data["input_plans"]){
                                $this->input_data["input_plans"] = array();
                            }

                            array_push( $this->input_data["input_plans"],$participant_num);
                        }
                    }

                }
                else{
                   
                }
               
                break;
            }
        }
    }

    //出席者の人数を取得
    public function getParticNum($zoom_participant)
    {
        $participant  = "";

        if(isset($zoom_participant))
        {
            $participant =explode(",",$zoom_participant);
        }

        return count($participant) - 1;
    }

    //参加者の人数を取得
    public function getPlanNum($zoom_plan)
    {
        $plans  = array();

        if(isset($zoom_plan))
        {
            $plans =explode(",",$zoom_plan);
        }

        return count($plans) - 1;
    }

     //表示用に日付データを戻す
    public function changeSetData($setTimes)
    {
        $year_m =  date('Y-m-d',  strtotime($setTimes));
        $hour_m =  date('H:i:s',  strtotime($setTimes));

        $set_h = $year_m ."T" .$hour_m;

        return $set_h;
    }

    //POSTデータの設置
    public  function setPostData($postData)
    {
        //var_dump($postData);

        if(isset($postData['input_id']) )
        {
            $this->input_data["input_id"] = $postData['input_id'];
        }

        $this->input_data["input_zoom_day"] = $postData["input_zoom_day"];
        $this->input_data["input_zoom_start_day"] = $postData["input_zoom_start_day"];
        $this->input_data["input_zoom_deadline"] = $postData["input_zoom_deadline"];
        $this->input_data["input_zoom_url"] = $postData["input_zoom_url"];
        $this->input_data["input_zoom_title"] = $postData["input_zoom_title"];
        $this->input_data["input_zoom_category"] =  $postData["input_zoom_category"];
        $this->input_data["input_zoom_joinType"] = $postData["input_zoom_joinType"];
       
        if(isset($postData['input_plans']))
        {
            $this->input_data["input_plans"] = $postData["input_plans"];
        }

        //var_dump($this->input_data["input_join"]);
    }




     //ZOOMデータを新規登録
    public function insertTradeData($user_id)
    {
        // wpdbオブジェクト
        global $wpdb;


        $zoom_join_str = "";

        

        //$wpdb->show_errors(); 

         $insert = array(
            'post_author' => $user_id,
            'post_date' => date("Y-m-d H:i:s"),
            'post_zoom_day'  =>   date('Y-m-d H:i:s',  strtotime($this->input_data["input_zoom_day"])),
            'post_zoom_start_day'  =>   date('Y-m-d H:i:s',  strtotime($this->input_data["input_zoom_start_day"])),
            'post_zoom_deadline'  =>   date('Y-m-d H:i:s',  strtotime($this->input_data["input_zoom_deadline"])),
            'post_zoom_url' => $this->input_data["input_zoom_url"], 
            'post_zoom_title' => $this->input_data["input_zoom_title"],
            'post_zoom_category' => $this->input_data["input_zoom_category"],
            'post_zoom_jointype' => $this->input_data["input_zoom_joinType"],

            'post_zoom_participant_plans' => $zoom_join_str
         );

         $dataFormat = array('%d','%s','%s','%s','%s','%s','%s','%d','%d','%s');


         $sql_rsl = $wpdb->insert("wp_zoom_data", $insert, $dataFormat); 


         if ( $sql_rsl == false ) {
            //登録失敗
            return false;
         }
         else {
            //登録成功
            return true;
         }
    }

    
    //ズームデータ更新
    public function upDataTradeData($user_id)
    {
        // wpdbオブジェクト
        global $wpdb;

        //ここは更新
        $updata = array(
            'post_date' => date("Y-m-d H:i:s"),
            'post_zoom_day'  =>   date('Y-m-d H:i:s',  strtotime($this->input_data["input_zoom_day"])),
            'post_zoom_start_day'  =>   date('Y-m-d H:i:s',  strtotime($this->input_data["input_zoom_start_day"])),
            'post_zoom_deadline'  =>   date('Y-m-d H:i:s',  strtotime($this->input_data["input_zoom_deadline"])),
            'post_zoom_url' => $this->input_data["input_zoom_url"], 
            'post_zoom_title' => $this->input_data["input_zoom_title"],
            'post_zoom_category' => $this->input_data["input_zoom_category"],
            'post_zoom_jointype' => $this->input_data["input_zoom_joinType"],
         );

         //更新したい行の条件
          $condition = array(
              'ID' => $this->input_data["input_id"],
           );

          // var_dump($updata);

           $dataFormat = array('%s','%s','%s','%s','%s','%s','%d','%d');
           $conditionsFormat = array('%d');
           $sql_rsl = $wpdb->update("wp_zoom_data", $updata, $condition,$dataFormat,$conditionsFormat); 

            
           if ( $sql_rsl == false ) {
	            //更新失敗
                return false;
            } else {
	            //更新成功
                return true;
           }
    }

      //ZOOMデータ削除
    public function deleteZOOMData($post_id)
    {
        // wpdbオブジェクト
        global $wpdb;

         //更新したい行の条件
          $condition = array(
              'ID' => $post_id,
           );

           $conditionsFormat = array('%d');
           $sql_rsl = $wpdb->delete("wp_zoom_data", $condition,$conditionsFormat); 

            
           if ( $sql_rsl == false ) {
	            //削除失敗
                return false;
            } else {
	            //削除成功
                return true;
           }
     }



 

    //ズーム参加者予定データ更新
    public function upDataZoomPlansData($set_id)
    {
        // wpdbオブジェクト
        global $wpdb;

        $zoom_plans_str = "";

        if(count($this->input_data["input_plans"]) > 0)
        {
            foreach($this->input_data["input_plans"] as $value){

                $user_info = get_userdata( $value );

                if($user_info)
                {
                    $zoom_plans_str = $zoom_plans_str .strval($value).",";
                }
            }
        }

        //ここは更新
        $updata = array(
            'post_date' => date("Y-m-d H:i:s"),
            'post_zoom_participant_plans' => $zoom_plans_str
         );

         //更新したい行の条件
          $condition = array(
              'ID' => $set_id,
           );

           //var_dump($updata);

           $dataFormat = array('%s','%s');
           $conditionsFormat = array('%d');
           $sql_rsl = $wpdb->update("wp_zoom_data", $updata, $condition,$dataFormat,$conditionsFormat); 

            
           if ( $sql_rsl == false ) {
	            //更新失敗
                return false;
            } else {
	            //更新成功
                return true;
           }
    }



    //ズーム参加者予定データの追加と削除
    public function upDataZoomPlansDataForID($set_id,$user_id)
    {
        // wpdbオブジェクト
        global $wpdb;

        $zoom_plans_array = array();


        //まず変更データを取得
        foreach ($this->zoom_row as $row) 
        {
            if($set_id == $row->ID)
            {
                if(isset($row->post_zoom_participant_plans))
                {
                    $zoom_plans_array = explode(",",$row->post_zoom_participant_plans);
                }
            }
        }

        //var_dump($zoom_plans_array );


        //無かった場合は追加
        $is_num = false;

        for($i=0;$i<count($zoom_plans_array);$i++)
        {
            if($zoom_plans_array[$i] == $user_id)
            {
                //unset($zoom_plans_array[$i]);
                $is_num = true;
                break;
            }
        }

        if(!$is_num)
        {
            array_push($zoom_plans_array,$user_id);
        }


        
         $zoom_join_str = "";
         $zoom_plans_str = "";

        if(count($zoom_plans_array) > 1)
        {
            foreach($zoom_plans_array as $value){
                if($value != "")
                {
                    $zoom_plans_str = $zoom_plans_str .strval($value).",";
                }
            }
        }

        //ここは更新
        $updata = array(
            'post_date' => date("Y-m-d H:i:s"),
            'post_zoom_participant_plans' => $zoom_plans_str
         );

         //更新したい行の条件
          $condition = array(
              'ID' => $set_id,
           );

           //var_dump($updata);

           $dataFormat = array('%s','%s');
           $conditionsFormat = array('%d');
           $sql_rsl = $wpdb->update("wp_zoom_data", $updata, $condition,$dataFormat,$conditionsFormat); 

            
           if ( $sql_rsl == false ) {
	            //更新失敗
                return false;
            } else {
	            //更新成功
                return true;
           }
    }


    
    //ZOOM情報を取得して、今月分だけ並べなおす
    public function getZoomMonthRow()
    {
        // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM wp_zoom_data";//$wpdb->prepare("SELECT * FROM ".$wpdb->zoom_data);
        $rows = $wpdb->get_results($sql);

        date_default_timezone_set('Asia/Tokyo');


        $now_yaear = date('Y');
        $now_month = date('m');

        $this->zoom_month_row = array();
       

        if($rows)
        {
            foreach ($rows as $row) 
            {
                 $year_m =  date('Y',  strtotime($row->post_zoom_day));
                 $hour_m =  date('m',  strtotime($row->post_zoom_day));

                 if($now_yaear == $year_m && $hour_m == $now_month)
                 {
                    $zoom_set_array = array();

                    $zoom_set_array["input_id"] = $row->ID;
                    $zoom_set_array["input_zoom_day"] = $row->post_zoom_day;
                    $zoom_set_array["input_zoom_start_day"] = $row->post_zoom_start_day;
                    $zoom_set_array["input_zoom_deadline"] = $row->post_zoom_deadline;
                    $zoom_set_array["input_zoom_url"] = $row->post_zoom_url;
                    $zoom_set_array["input_zoom_title"] = $row->post_zoom_title;
                    $zoom_set_array["input_zoom_category"] = $row->post_zoom_category;
                    $zoom_set_array["input_zoom_joinType"] = $row->post_zoom_jointype;

                    $zoom_set_array["input_plans"] = "";

                    
                   
                    if(isset($row->post_zoom_participant_plans))
                    {

                        $participant_array = explode(",",$row->post_zoom_participant_plans);

                        foreach ($participant_array as $participant_num)
                        {
                            $joins_info = get_userdata( $participant_num );

                            if($joins_info){

                                if(!$zoom_set_array["input_plans"])
                                {
                                    $zoom_set_array["input_plans"] = array();
                                }

                                array_push( $zoom_set_array["input_plans"],$participant_num);
                            }
                        }

                    }
                 
                    

                    array_push($this->zoom_month_row,$zoom_set_array);
                 }
            }
        }
        
    }

     //ZOOM情報を取得して、指定付き分だけ並べなおす
    public function getZoomSetMonthRow( $set_year , $set_month)
    {
        // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM wp_zoom_data";//$wpdb->prepare("SELECT * FROM ".$wpdb->zoom_data);
        $rows = $wpdb->get_results($sql);

        date_default_timezone_set('Asia/Tokyo');

        $this->zoom_set_month_row = array();
       

        if($rows)
        {
            foreach ($rows as $row) 
            {
                 $year_m =  date('Y',  strtotime($row->post_zoom_day));
                 $hour_m =  date('m',  strtotime($row->post_zoom_day));

                 if($set_year == $year_m && $hour_m == $set_month)
                 {
                    $zoom_set_array = array();

                    $zoom_set_array["input_id"] = $row->ID;
                    $zoom_set_array["input_zoom_day"] = $row->post_zoom_day;
                    $zoom_set_array["input_zoom_start_day"] = $row->post_zoom_start_day;
                    $zoom_set_array["input_zoom_deadline"] = $row->post_zoom_deadline;
                    $zoom_set_array["input_zoom_url"] = $row->post_zoom_url;
                    $zoom_set_array["input_zoom_title"] = $row->post_zoom_title;
                    $zoom_set_array["input_zoom_category"] = $row->post_zoom_category;
                    $zoom_set_array["input_zoom_joinType"] = $row->post_zoom_jointype;

                    $zoom_set_array["input_plans"] = "";

                    
                   
                    if(isset($row->post_zoom_participant_plans))
                    {

                        $participant_array = explode(",",$row->post_zoom_participant_plans);

                        foreach ($participant_array as $participant_num)
                        {
                            $joins_info = get_userdata( $participant_num );

                            if($joins_info){

                                if(!$zoom_set_array["input_plans"])
                                {
                                    $zoom_set_array["input_plans"] = array();
                                }

                                array_push( $zoom_set_array["input_plans"],$participant_num);
                            }
                        }

                    }
                 
                    

                    array_push($this->zoom_set_month_row,$zoom_set_array);
                 }
            }
        }
        
    }



    //その人が今月のZOOMに出席予定か
    public function checkUserZoomPlans($user_id)
    {
       // var_dump($this->zoom_row);

        $zoom_num = 0;

        if(!$this->zoom_month_row)
        {
            return 0;//今月はない
        }

        //echo $user_id;

       
            foreach ($this->zoom_month_row as $row)
            {
                if($row["input_plans"])
                {
                   

                    if(isset( $row["input_plans"] ))
                    {
                        if(count($row["input_plans"]) > 0 && $row["input_zoom_category"] ==  ZoomClass::ZOOM_ZARABA_CATEGORY)
                        {
                           

                            for($i=0;$i<count($row["input_plans"]);$i++)
                            {
                                if($user_id == $row["input_plans"][$i]){
                                    $zoom_num++;
                                 break;
                                }
                            }
                        }
                    }
                }
            }
        

        return $zoom_num;
    }

    //その人が指定があった月のZOOMに出席予定か
    public function checkUserZoomSetPlans($user_id)
    {
       // var_dump($this->zoom_row);

        $zoom_num = 0;

        if(!$this->zoom_set_month_row)
        {
            return 0;//今月はない
        }

        //echo $user_id;

       
            foreach ($this->zoom_set_month_row as $row)
            {
                if($row["input_plans"])
                {
                   

                    if(isset( $row["input_plans"] ))
                    {
                        if(count($row["input_plans"]) > 0 && $row["input_zoom_category"] ==  ZoomClass::ZOOM_ZARABA_CATEGORY)
                        {
                           

                            for($i=0;$i<count($row["input_plans"]);$i++)
                            {
                                if($user_id == $row["input_plans"][$i]){
                                    $zoom_num++;
                                 break;
                                }
                            }
                        }
                    }
                }
            }
        

        return $zoom_num;
    }

  
      //その人が今月の講義に出席予定か
    public function checkUserKougiPlans($user_id)
    {
       // var_dump($this->zoom_row);

        $zoom_num = 0;

        if(!$this->zoom_month_row)
        {
            return 0;//今月はない
        }

        //echo $user_id;

       
            foreach ($this->zoom_month_row as $row)
            {
                if($row["input_plans"])
                {
                   

                    if(isset( $row["input_plans"] ))
                    {
                        if( (count($row["input_plans"])  > 0 ) && ( ($row["input_zoom_category"] ==  ZoomClass::ZOOM_KOUGI_CATEGORY ) || ($row["input_zoom_category"] ==  ZoomClass::ZOOM_FX_KOUGI_CATEGORY ) ) )
                        {
                           

                            for($i=0;$i<count($row["input_plans"]);$i++)
                            {
                                if($user_id == $row["input_plans"][$i]){
                                    $zoom_num++;
                                 break;
                                }
                            }
                        }
                    }
                }
            }
        

        return $zoom_num;
    }


    //その人が指定された月の講義に出席予定か
    public function checkUserSetKougiPlans($user_id)
    {
       // var_dump($this->zoom_row);

        $zoom_num = 0;

        if(!$this->zoom_set_month_row)
        {
            return 0;//今月はない
        }

        //echo $user_id;

       
            foreach ($this->zoom_set_month_row as $row)
            {
                if($row["input_plans"])
                {
                   

                    if(isset( $row["input_plans"] ))
                    {
                        if( (count($row["input_plans"])  > 0 ) && ( ($row["input_zoom_category"] ==  ZoomClass::ZOOM_KOUGI_CATEGORY ) || ($row["input_zoom_category"] ==  ZoomClass::ZOOM_FX_KOUGI_CATEGORY ) ) )
                        {
                           

                            for($i=0;$i<count($row["input_plans"]);$i++)
                            {
                                if($user_id == $row["input_plans"][$i]){
                                    $zoom_num++;
                                 break;
                                }
                            }
                        }
                    }
                }
            }
        

        return $zoom_num;
    }



      //その人が今月の講義に出席予定か
    public function checkUserFxKougiPlans($user_id)
    {
       // var_dump($this->zoom_row);

        $zoom_num = 0;

        if(!$this->zoom_month_row)
        {
            return 0;//今月はない
        }

        //echo $user_id;

       
            foreach ($this->zoom_month_row as $row)
            {
                if($row["input_plans"])
                {
                   

                    if(isset( $row["input_plans"] ))
                    {
                        if(count($row["input_plans"]) > 0 && $row["input_zoom_category"] ==  ZoomClass::ZOOM_FX_KOUGI_CATEGORY)
                        {
                           

                            for($i=0;$i<count($row["input_plans"]);$i++)
                            {
                                if($user_id == $row["input_plans"][$i]){
                                    $zoom_num++;
                                 break;
                                }
                            }
                        }
                    }
                }
            }
        

        return $zoom_num;
    }


    //過去のテーブルを表示用に再編成
    public function make_past_zoom_datatable($page_max,$rows)
    {
        $count_data = 1;
        $table_gyo = 0;

        $zoom_set_array = array();

        foreach ($rows as $row) 
        {
            
             $zoom_set_array[$count_data][$table_gyo]["input_id"] = $row->ID;
             $zoom_set_array[$count_data][$table_gyo]["input_zoom_day"] = $row->post_zoom_day;
             $zoom_set_array[$count_data][$table_gyo]["input_zoom_start_day"] = $row->post_zoom_start_day;
             $zoom_set_array[$count_data][$table_gyo]["input_zoom_deadline"] = $row->post_zoom_deadline;
             $zoom_set_array[$count_data][$table_gyo]["input_zoom_url"] = $row->post_zoom_url;
             $zoom_set_array[$count_data][$table_gyo]["input_zoom_title"] = $row->post_zoom_title;

             $zoom_set_array[$count_data][$table_gyo]["input_plans"]  = "";


             if(isset($row->post_zoom_participant_plans))
             {

                $participant_array = explode(",",$row->post_zoom_participant_plans);

                foreach ($participant_array as $participant_num)
                {
                    $joins_info = get_userdata( $participant_num );

                    if($joins_info){

                        if(! $zoom_set_array[$count_data][$table_gyo]["input_plans"])
                        {
                            $zoom_set_array[$count_data][$table_gyo]["input_plans"] = array();
                        }

                        array_push( $zoom_set_array[$count_data][$table_gyo]["input_plans"],$participant_num);
                    }
                }
             }
             

             if($table_gyo +1 == $page_max)
             {
                $count_data++;
                $table_gyo = 0;
             }
             else {
	            $table_gyo++;
            }
        }

        return $zoom_set_array;

    }

}
?>