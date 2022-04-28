<?php 

require_once("userClass.php");

class ReportClass
{
    public $input_data ;//インプット用の配列

    public $report_row;//データ呼び出し



    public function __construct()
    {
          $this->input_data["input_id"] = 0;
          $this->input_data["input_eventdate"] = date("Y-m-d H:i:s");
          $this->input_data["input_title"] ="";
          $this->input_data["input_type"] =0;
    }

    //初期化
    public  function init()
    {
          $this->input_data["input_id"] = 0;
          $this->input_data["input_eventdate"] = date("Y-m-d H:i:s");
          $this->input_data["input_title"] ="";
          $this->input_data["input_type"] =0;
    }


    //提出画面
    public  $disp_submission_array = array(
            0=>"未提出のみ表示",
            1=>"提出済みのみ表示",
            2=>"全て表示",
     );

    public const DISP_NOTSUBMITTED = 0;//未提出のみ表示
    public const DISP_SUBMITTION = 1;//提出のみ表示
    public const DISP_ALL_SUBMITTION = 2;//全て表示

    public const DISP_ADMIN_SUBMITTION = 99;//管理者が使用する

 
     //データの取得
    public function getReportData($input_str)
    {
        return $this->input_data[$input_str];
    }


    //DBデータの全取得（最新開催日より）
    public function getEvntDataRow()
    {
         // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM  wp_report_data ORDER BY post_eventdate DESC";//$wpdb->prepare("SELECT * FROM  wp_videoviewing_data ORDER BY post_release_date DESC");
        $row = $wpdb->get_results($sql);

        if($row)
        {
            $this->report_row = $row;
        }
        else {
            $this->report_row = array();
        }
    }

      //DBデータの全取得（IDより）
    public function getIDRow()
    {
         // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM  wp_report_data ORDER BY ID DESC";//$wpdb->prepare("SELECT * FROM  wp_videoviewing_data ORDER BY post_release_date DESC");
        $row = $wpdb->get_results($sql);

        if($row)
        {
            $this->report_row = $row;
        }
        else {
            $this->report_row = array();
        }
    }

     //DBデータの全取得（日より）
    public function getPostEventdateRow()
    {
         // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM  wp_report_data ORDER BY post_eventdate DESC";//$wpdb->prepare("SELECT * FROM  wp_videoviewing_data ORDER BY post_release_date DESC");
        $row = $wpdb->get_results($sql);

        if($row)
        {
            $this->report_row = $row;
        }
        else {
            $this->report_row = array();
        }
    }


    //DBデータの入力用の情報を取得(最後だけ取得)
    public function getInputReportLastRow ()
    {
         // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM wp_report_data ORDER BY ID DESC LIMIT 1";//$wpdb->prepare("SELECT * FROM wp_zoom_data ORDER BY ID DESC LIMIT 1");
        $row = $wpdb->get_results($sql);

        if($row)
        {
            $this->report_row = $row;
        }
        else {
            $this->report_row = array();
        }
    }


     //DBデータの入力用の情報を取得し、次のIDだけ設定する
    public function getInputReportLastReID ()
    {
         // wpdbオブジェクト
        global $wpdb;


        $sql = "SHOW TABLE STATUS LIKE 'wp_report_data'";
        
        $row = $wpdb->get_results($sql);

       // var_dump($row);
        

        if($row)
        {
            foreach($row as $rows)
            {
                $this->input_data["input_id"] = $rows->Auto_increment;
                return;
            }

            return;
        }
        else {
            $this->input_data["input_id"] = 1;

           return;

        }
    }

    
    //DBデータで指定したIDのものを取得する
    public function getInputReportForID($input_id)
    {
         // wpdbオブジェクト
        global $wpdb;

        $query = "SELECT * FROM wp_report_data WHERE ID = %d";
        $sql = $wpdb->prepare($query,$input_id);

        //echo $sql;
        $row = $wpdb->get_results($sql);

        if($row)
        {
            $this->report_row = $row;
        }
        else {
            $this->report_row = array();
        }
    }


     //DBデータで指定したIDのものを取得し、Boolを帰す
    public function getSerchReportForID($input_id)
    {
         // wpdbオブジェクト
        global $wpdb;

        $query = "SELECT * FROM wp_report_data WHERE ID = %d";
        $sql = $wpdb->prepare($query,$input_id);

        //echo $sql;
        $row = $wpdb->get_results($sql);

        // var_dump($row);


        if($row)
        {
            return true;
        }
        else {
            return false;
        }
    }


        
    //POSTデータの設置
    public  function setPostData($postData)
    {
        

        if(isset($postData['input_id']) )
        {
            $this->input_data["input_id"] = $postData['input_id'];
        }

        $this->input_data["input_eventdate"] =   $postData["input_eventdate"];
        $this->input_data["input_title"] = $postData["input_title"];
        $this->input_data["input_type"] = $postData["input_type"];
        
       // var_dump($this->input_data);
    }


     //*******************************************************
     //            レポート DB削除
     //*******************************************************
    public function deleteReportData($post_id)
    {
        // wpdbオブジェクト
        global $wpdb;

         //更新したい行の条件
          $condition = array(
              'ID' => $post_id,
           );

           $conditionsFormat = array('%d');
           $sql_rsl = $wpdb->delete('wp_report_data', $condition,$conditionsFormat); 

            
           if ( $sql_rsl == false ) {
	            //削除失敗
                return false;
            } else {
	            //削除成功
                return true;
           }
     }


     //指定されたデータをinputDataに入れる
    public  function setInputDataForPost()
    {
        foreach ($this->report_row as $rows) 
        {
            $this->input_data["input_id"] =$rows->ID;
            $this->input_data["input_eventdate"] =   date('Y-m-d',  strtotime($rows->post_eventdate));
            $this->input_data["input_title"] = $rows->post_title;
            $this->input_data["input_type"] = $rows->post_type;
            

        }

       // var_dump($this->input_data);
    }


    
    //レポート提出を新規登録
    public function insertRepotData()
    {
        // wpdbオブジェクト
        global $wpdb;
          
          $insert = array(
            'post_date' => date("Y-m-d H:i:s"),
            'post_eventdate'  =>  date('Y-m-d H:i:s',  strtotime($this->input_data["input_eventdate"])),
            'post_title' => $this->input_data["input_title"], 
            'post_type' => $this->input_data["input_type"], 
           
         );

        // var_dump($insert);

         $dataFormat = array('%s','%s','%s','%d');

         $sql_rsl = $wpdb->insert('wp_report_data', $insert, $dataFormat); 

         if ( $sql_rsl == false ) {
            //登録失敗
            return false;
         }
         else {
            //登録成功
            return true;
         }
    }



      //更新
    public function upDataRepotData()
    {
        // wpdbオブジェクト
        global $wpdb;

        //ここは更新
        $updata = array(
            'post_date' => date("Y-m-d H:i:s"),
            'post_eventdate'  =>  date('Y-m-d H:i:s',  strtotime($this->input_data["input_eventdate"])),
            'post_title' => $this->input_data["input_title"], 
            'post_type' => $this->input_data["input_type"],
         );

        //var_dump($updata);

         //更新したい行の条件
          $condition = array(
              'ID' => $this->input_data["input_id"],
           );

           $dataFormat = array('%s','%s','%s','%d');
           $conditionsFormat = array('%d');
           $sql_rsl = $wpdb->update('wp_report_data', $updata, $condition,$dataFormat,$conditionsFormat); 

            
           if ( $sql_rsl == false ) {
	            //更新失敗
                return false;
            } else {
	            //更新成功
                $this->make_reportfolder($this->input_data["input_id"]);
              


                return true;
           }
    }

    //レポートフォルダの作成
    public function make_reportfolder($make_ID)
    {
        //フォルダの有無を調べる
        $directory_path =  WP_CONTENT_DIR ."/uploads/report"; 

        //「$directory_path」で指定されたディレクトリが存在するか確認
        if(file_exists($directory_path)){
            //存在したときの処理
           
        }else{
            //存在しないときの処理
            mkdir($directory_path, 0777);
        }

        $this->make_report_kind_folder($make_ID);
    }


    //レポート別フォルダの作成
    public function make_report_kind_folder($make_ID)
    {
        //フォルダの有無を調べる
        $directory_path =  WP_CONTENT_DIR ."/uploads/report/".$make_ID; 

        //「$directory_path」で指定されたディレクトリが存在するか確認
        if(file_exists($directory_path)){
            //存在したときの処理
            
        }else{
            //存在しないときの処理
            mkdir($directory_path, 0777);
        }
    }


    //レポート提出確認
    public function make_report_submissiton($folder_ID,$user_id)
    {
        //フォルダの有無を調べる
        $directory_path =  WP_CONTENT_DIR ."/uploads/report/".$folder_ID . "/" .$user_id; 

        //「$directory_path」で指定されたディレクトリが存在するか確認
        if(file_exists($directory_path)){
            //存在したときの処理
            $files = glob($directory_path ."/*.*");

            if($files){
                return true;
            }
            else {
	            return false;
            }
            
        }else{
            //存在しないときの処理
           return false;
        }
    }


    //レポート提出人数を取得
    public function get_report_submissiton_num($folder_ID)
    {
        //フォルダの有無を調べる
        $directory_path =  WP_CONTENT_DIR ."/uploads/report/".$folder_ID; 

        //「$directory_path」で指定されたディレクトリが存在するか確認
        if(file_exists($directory_path)){
            //存在したときの処理
            $folers = glob($directory_path ."/*");

            if($folers){

                $count = 0;

                foreach ($folers as $row)
                {
                    $user_id = str_replace($directory_path . "/", "", $row);

                    $user_info = get_userdata( $user_id );

                    //フォルダの有無
                     $files = glob($row ."/*");

                    if($user_info && $files)
                    {
                        $count++;
                    }
                }
               
                return $count;
            }
            else {
	            return 0;
            }
            
        }else{
            //存在しないときの処理
           return 0;
        }
    }



     //レポート提出
    public function upload_report_submissiton($folder_ID,$user_id,$_file)
    {
        //フォルダの有無を調べる
        $directory_path =  WP_CONTENT_DIR ."/uploads/report/".$folder_ID . "/" .$user_id; 

        //「$directory_path」で指定されたディレクトリが存在するか確認
        if(file_exists($directory_path)){
            //存在したときの処理
           
        }else{
            //存在しないときの処理
           mkdir($directory_path, 0777);
        }

       //拡張子を取得
       $kaku = pathinfo(basename($_file['userfile']['name']));

       //ファイル名を作成
       $make_file = "lecture_id" .$folder_ID . "_user" .$user_id ."." . $kaku["extension"];

       $upload = $directory_path ."/" . $make_file;

       move_uploaded_file($_file['userfile']['tmp_name'], $upload);
        //echo $upload;

        //var_dump($kaku);
    }


    //講義内の全てのフォルダーを取得
    public function get_report_submissiton($folder_ID)
    {
        $report_member = [];

        //フォルダの有無を調べる
       // $directory_path =  get_template_directory() ."/../../uploads/report/".$folder_ID;
       
       $directory_path =  WP_CONTENT_DIR ."/uploads/report/".$folder_ID; 
      
        //「$directory_path」で指定されたディレクトリが存在するか確認
        if(file_exists($directory_path)){

            //存在したときの処理
             $report_member = glob($directory_path ."/*");

         
                $report_file_array = array();

                foreach ($report_member as $row)
                {
                    //この人のIDは?
                    $get_id = str_replace($directory_path . "/", '', $row);

                    //フォルダの中身は？
                    $report_name_array = glob($row ."/*.*");


                    if(empty($report_name_array)){
                        
                    }
                    else {

                         $user_info = get_userdata( $get_id );

                        if($user_info)
                        {

                            //ファイルURL
                            foreach ($report_name_array as $row_file){
                            
                                $report_file_array[$get_id]["file_name"] = str_replace($row . "/", '', $row_file);

                                $report_file_array[$get_id]["file_url"] = content_url() . "/uploads/report/" . $folder_ID . "/" .$get_id. "/" .$report_file_array[$get_id]["file_name"];
                            }

                            //ID
                            $report_file_array[$get_id]["user_id"] = $get_id;
                        }
                    }

                }
                //var_dump($report_file_array);
                return $report_file_array;
           

            //var_dump($report_member);

            return $report_member;
            
        }else{
            //存在しないときの処理
           return $report_member;
        }
    }



     //ページネーション用に再編成
    public function make_reportarray_datatable($rows,$user_id,$disp_types,$member_level,$member_type)
    {
        $count_data = 1;
        
      

        $report_set_array = array();
        $report_membar_array = array();

        foreach ($rows as $row) {
            $report_membar_array[$row->ID] =  $this->get_report_submissiton($row->ID);
        }
        //var_dump($report_membar_array);

       // 

        foreach ($rows as $row) 
        {
             //メンバータイプで調査 訓練生の時のみ調べる
             if($member_level == UserClass::KUNRENSEI){

                    if($member_type == 0){
                        //なしは見れない
                        continue;
                    }
                    else if($member_type != $row->post_type){
                        //タイプが違うなら見れない
                        continue;
                    }
             }
             else if($member_level == UserClass::DOGA){
                //動画会員は見れない
                continue;
             }



            $is_submission = true;//指定ユーザーが提出したかどうか

            //提出未提出選択時
            if($disp_types == ReportClass::DISP_NOTSUBMITTED ||  $disp_types == ReportClass::DISP_SUBMITTION)
            {
                $is_submission = $this->make_report_submissiton($row->ID,$user_id);
            }

            if(($disp_types ==  ReportClass::DISP_ADMIN_SUBMITTION) ||($disp_types == ReportClass::DISP_ALL_SUBMITTION) ||($disp_types == ReportClass::DISP_NOTSUBMITTED && !$is_submission) ||($disp_types == ReportClass::DISP_SUBMITTION && $is_submission))
            {
               
               



                $report_set_array[$count_data]["input_id"] = $row->ID;
                $report_set_array[$count_data]["input_eventdate"] = date('Y-m-d',  strtotime($row->post_eventdate));
                $report_set_array[$count_data]["input_title"] = $row->post_title;
                $report_set_array[$count_data]["input_type"] = $row->post_type;

                if(current_user_can('administrator')){
                    if( isset($report_membar_array[$row->ID][$user_id])){


                        $report_set_array[$count_data]["file_url"] = $report_membar_array[$row->ID][$user_id]["file_url"];
                        $report_set_array[$count_data]["file_name"] =$report_membar_array[$row->ID][$user_id]["file_name"];
                    }
                }
                


                if($disp_types ==  ReportClass::DISP_ADMIN_SUBMITTION)
                {
                    //管理者が必要ないので何人提出しているかを調べる
                    $report_set_array[$count_data]["input_is_submission_num"] = $this->get_report_submissiton_num($row->ID);
                }
                else{
                
                    //フォルダURL
                    $report_set_array[$count_data]["input_is_submission"] = $this->make_report_submissiton($row->ID,$user_id);

                    //コメント

             
                }

                $count_data++;
            }
        }

        return $report_set_array;

    }


     //自作日本語変換用basename
    public function mb_basename($str, $suffix=null){
        $tmp = preg_split('/[\/\\\\]/', $str);
        $res = end($tmp);
        if(strlen($suffix)){
            $suffix = preg_quote($suffix);
            $res = preg_replace("/({$suffix})$/u", "", $res);
        }
        return $res;
    }


}
?>