<?php


  
class ToolClass
{
    public $input_data ;//インプット用の配列

    public $tool_row;//ヒストリーデータ呼び出し

    public function __construct()
    {
       $this->input_data["input_id"] = 0;
        $this->input_data["input_date"] = date('Y-m');
     
    }


    //初期化
    public  function init()
    {
        $this->input_data["input_id"] = 0;
       
    }


    //ツールのカテゴリ
    public  $disp_tool_category_data = array(
          "0"=>  array("カテゴリーなし","no_category","全て表示"),
          "1"=>  array("株","kab","株"),
          "2"=>  array("FX","fx","FX"),
     );



     //定義
     public const  CATEGORY_NO = 0;
     public const  CATEGORY_KABU = 1;
     public const  CATEGORY_FX = 2;

    //DBデータの全取得（IDより）
    public function getIDRow()
    {
         // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM  wp_tool_data ORDER BY ID DESC";//$wpdb->prepare("SELECT * FROM  wp_videoviewing_data ORDER BY post_release_date DESC");
        $row = $wpdb->get_results($sql);

        if($row)
        {
            $this->tool_row = $row;
        }
        else {
            $this->tool_row = array();
        }
    }


    //最新データのみを取得
    public function getInputToolLastRow ()
    {
         // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM wp_tool_data ORDER BY ID DESC LIMIT 1";//$wpdb->prepare("SELECT * FROM wp_zoom_data ORDER BY ID DESC LIMIT 1");
        $row = $wpdb->get_results($sql);

        if($row)
        {
            $this->tool_row = $row;
        }
        else {
            $this->tool_row = "";
        }
    }

    //最新データから次のID番号を取得
    public function getNextID ()
    {
        global $wpdb;

        $sql = "SHOW TABLE STATUS LIKE 'wp_tool_data'";
        
        $row = $wpdb->get_results($sql);

       // var_dump($row);
        

        if($row)
        {
            foreach($row as $rows)
            {
                return $rows->Auto_increment;
            }

            return 1;
        }
        else {
            return 1;

        }
    }

    //指定したIDがあるかどうかを調べる
    public function checkToolForID ($input_id)
    {
         // wpdbオブジェクト
        global $wpdb;

        $query = "SELECT * FROM wp_tool_data WHERE ID = %d";
        $sql = $wpdb->prepare($query,$input_id);

        //echo $sql;
        $row = $wpdb->get_results($sql);

     
        if($row)
        {
           return true;
        }
        else {
            return false;
        }
    }

    //指定したIDで取得
    public function getToolForID ($input_id)
    {
         // wpdbオブジェクト
        global $wpdb;

        $query = "SELECT * FROM wp_tool_data WHERE ID = %d";
        $sql = $wpdb->prepare($query,$input_id);

        //echo $sql;
        $row = $wpdb->get_results($sql);

        if($row)
        {
            $this->tool_row = $row;
        }
        else {
            $this->tool_row = "";
        }
    }


      //ダウンロードフォルダの作成
    public function make_download_folder()
    {
        //フォルダの有無を調べる
        $directory_path =  WP_CONTENT_DIR ."/uploads/download"; 

        //「$directory_path」で指定されたディレクトリが存在するか確認
        if(file_exists($directory_path)){
            //存在したときの処理
           
        }else{
            //存在しないときの処理
            mkdir($directory_path, 0777);
        }
    }

    //各ダウンロードフォルダの作成
    public function make_download_id_folder($make_ID)
    {
        //フォルダの有無を調べる
         $directory_path =  WP_CONTENT_DIR ."/uploads/download/".$make_ID; 

        //「$directory_path」で指定されたディレクトリが存在するか確認
        if(file_exists($directory_path)){
            //存在したときの処理
           
        }else{
            //存在しないときの処理
            mkdir($directory_path, 0777);
        }

    }

    //各ダウンロードフォルダの中身を削除
    public function make_download_deleat($make_ID)
    {
        //フォルダの有無を調べる
        $directory_path =  WP_CONTENT_DIR ."/uploads/download/".$make_ID; 

        $files = glob($directory_path ."/*"); // get all file names

        foreach($files as $file){ // iterate files
        
        if(is_file($file))
            unlink($file); // delete file
        }

    }

    
    //各ダウンロードフォルダの中身を削除
    public function check_download_file($make_ID)
    {
        //フォルダの有無を調べる
        $directory_path =  WP_CONTENT_DIR ."/uploads/download/".$make_ID; 

        $files = glob($directory_path ."/*"); // get all file names

        foreach($files as $file){ // iterate files
        
        if(is_file($file))
           return true;
        }

        return false;

    }

     //DLファイルアップ
    public function upload_dl_submissiton($make_ID,$_file)
    {
        //フォルダの有無を調べる
         $directory_path =  WP_CONTENT_DIR ."/uploads/download/".$make_ID; 

        //「$directory_path」で指定されたディレクトリが存在するか確認
        if(file_exists($directory_path)){
            //存在したときの処理
           
        }else{
            //存在しないときの処理
           mkdir($directory_path, 0777);
        }

        //setlocale(LC_CTYPE, 'ja_JP.UTF-8');
       //拡張子を取得
       $upload = $directory_path ."/" . $this->mb_basename($_file['userfile']['name']);

      // echo $_file['userfile']['name']."<br/>";
      //  echo  $this->mb_basename($_file['userfile']['name']);

       move_uploaded_file($_file['userfile']['tmp_name'], $upload);
        //echo $upload;

        //var_dump($kaku);
    }



    //新規ファイル登録(必ずファイル有)
    public function save_file_new($post_data,$_file)
    {
        //var_dump($post_data);

       // echo basename($_file['userfile']['name']);


        //新規の場合、すでにファイルがあるのはＮＧ
        if($this->check_download_file($post_data["input_id"]))
        {
            return false;
        }


        global $wpdb;
        
          $insert = array(
            'post_date' => date("Y-m-d H:i:s"),
            'post_category'  =>  $post_data["input_category"],
            'post_title' => $post_data["input_title"],
            'post_file_name' =>  $this->mb_basename($_file['userfile']['name']),
           
         );

        // var_dump($insert);

         $dataFormat = array('%s','%d','%s','%s');

         $sql_rsl = $wpdb->insert('wp_tool_data', $insert, $dataFormat); 

         if ( $sql_rsl == false ) {
            //登録失敗
            return false;
         }
         else {

            //ダウンロードフォルダ
            $this->make_download_folder();

            //各ダウンロードフォルダ
            $this->make_download_id_folder($post_data["input_id"]);

            //ファイルアップロード
            $this->upload_dl_submissiton($post_data["input_id"],$_file);

            //登録成功
            return true;
         }

    }

      //更新
    public function upload_file_data($post_data,$file_name)
    {
        // wpdbオブジェクト
        global $wpdb;

         //ここは更新
        $updata = array(
            'post_date' => date("Y-m-d H:i:s"),
            'post_category'  =>  $post_data["input_category"],
            'post_title' => $post_data["input_title"],
        );

        //ファイルネームがあるかどうかでわける
        if($file_name != ""){
            $updata['post_file_name'] = $file_name;
        }


         //更新したい行の条件
         $condition = array(
              'ID' => $post_data["input_id"],
         );

        $dataFormat = array('%s','%d','%s');

        if($file_name != ""){
            array_push($dataFormat,'%s');
        }
        $conditionsFormat = array('%d');
        $sql_rsl = $wpdb->update('wp_tool_data', $updata, $condition,$dataFormat,$conditionsFormat); 

        //echo $sql_rsl;

        if ( $sql_rsl === false ) {
	         //更新失敗
             return false;
         } else {
	         //更新成功
            
            return true;
         }
    }

    //ツールのみのアップロード更新
    public function tool_upload($post_data,$_file)
    {
        
        //ダウンロードフォルダ
        $this->make_download_folder();

        //各ダウンロードフォルダ
        $this->make_download_id_folder($post_data["input_id"]);

        //ファイルを削除
        $this->make_download_deleat($post_data["input_id"]);

        //ファイルアップロード
        $this->upload_dl_submissiton($post_data["input_id"],$_file);

    }


       //ZOOMデータ削除
    public function deleteToolData($post_id)
    {
        // wpdbオブジェクト
        global $wpdb;

         //更新したい行の条件
          $condition = array(
              'ID' => $post_id,
           );

           $conditionsFormat = array('%d');
           $sql_rsl = $wpdb->delete("wp_tool_data", $condition,$conditionsFormat); 

            
           if ( $sql_rsl === false ) {
	            //削除失敗
                return false;
            } else {
	            //削除成功
                 //ファイルを削除
                $this->make_download_deleat($post_id);

                return true;
           }
     }



    //ページネーション用に再編成
    public function make_tool_array_datatable($rows,$disp_types)
    {
        $table_gyo = 0;

        $tool_set_array = array();

       

        foreach ($rows as $row) 
        {
            $is_submission = true;//指定ユーザーが提出したかどうか

            if(($disp_types ==  ToolClass::CATEGORY_NO))
            {
                $tool_set_array[$table_gyo]["input_id"] = $row->ID;
                $tool_set_array[$table_gyo]["input_category"] = $row->post_category;
                $tool_set_array[$table_gyo]["input_title"] = $row->post_title;
                $tool_set_array[$table_gyo]["input_file_name"] = $row->post_file_name;
                
	            $table_gyo++;
            }
            else  if(($disp_types ==  ToolClass::CATEGORY_KABU &&  $row->post_category == ToolClass::CATEGORY_KABU))
            {
                $tool_set_array[$table_gyo]["input_id"] = $row->ID;
                $tool_set_array[$table_gyo]["input_category"] = $row->post_category;
                $tool_set_array[$table_gyo]["input_title"] = $row->post_title;
                $tool_set_array[$table_gyo]["input_file_name"] = $row->post_file_name;
                
	            $table_gyo++;
            }
              else  if(($disp_types ==  ToolClass::CATEGORY_FX &&  $row->post_category == ToolClass::CATEGORY_FX))
            {
                $tool_set_array[$table_gyo]["input_id"] = $row->ID;
                $tool_set_array[$table_gyo]["input_category"] = $row->post_category;
                $tool_set_array[$table_gyo]["input_title"] = $row->post_title;
                $tool_set_array[$table_gyo]["input_file_name"] = $row->post_file_name;
                
	            $table_gyo++;
            }
        }

        return $tool_set_array;

    }

    //カテゴリ名で分類した関数を返す
    public function get_category_array()
    {
        $category = array();

        //初期化
        foreach($this->disp_tool_category_data as $key => $value){

            $category[$key] = 0;

         }

         if($this->tool_row)
         {
            foreach($this->tool_row as $row){ 

                if(isset( $category[$row->post_category]))
                {
                     $category[$row->post_category]++;
                }
            } 
         }

         return $category;
    }


    //ダウンロードリンク付きの配列に組みなおす
    public function get_dl_array()
    {
        $category_dl;

        if($this->tool_row)
        {
            $category_dl = array();

            foreach($this->tool_row as $row){ 

                $dl_data = array();

                $dl_data["input_id"] = $row->ID;
                $dl_data["input_category"] = $row->post_category;
                $dl_data["input_update"] = date('Y-m-d',  strtotime($row->post_date));
                $dl_data["input_title"] = $row->post_title;
                $dl_data["input_file_name"] = $row->post_file_name;
                $dl_data["input_url"] =content_url() . "/uploads/download/" . $row->ID ."/" . $row->post_file_name;

                $category_dl[ $row->ID] = $dl_data;
            } 
        }
        else{
            $category_dl = "";
        }

        //var_dump($category_dl);

        return $category_dl;
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

