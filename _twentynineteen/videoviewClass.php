<?php 

require_once("userClass.php");

class VideoViewClass
{

	public $input_data ;//インプット用の配列

    public $view_row;//データ呼び出し

    public $view_category_row;//データ呼び出し

    public $view_category_array;//連想配列用

    public $view_category_number;//本数用の連想配列

     public $view_category_view_row;//カテゴリー指定で取得

     //定義
     public const ALL_CATEGORY_DISP = 99;//カテゴリー全表示用

     //動画の表示形式
     public  $disp_array_data = array(
            0=>"サムネイル形式で表示",
            1=>"リスト形式で表示",
     );

	public function __construct()
    {
        $this->input_data["input_id"] = 0;
        $this->input_data["input_release_date"] = date('YYYY-MM-DDThh:mm:ss');
        $this->input_data["input_flame"] = "";
        $this->input_data["input_url"] = "";
        $this->input_data["input_private"] = "";
        $this->input_data["input_flame_width"] = 640;
        $this->input_data["input_flame_height"] = 650;
        $this->input_data["input_title"] = "";
        $this->input_data["input_disp"] = 1;
        $this->input_data["input_category"] = 0;
        $this->input_data["input_category_name"] = "";
        
    }

      //初期化
    public  function init()
    {
        $this->input_data["input_id"] = 0;
        $this->input_data["input_release_date"] = date('YYYY-MM-DDThh:mm:ss');
        $this->input_data["input_flame"] = "";
        $this->input_data["input_url"] = "";
        $this->input_data["input_private"] = "";
        $this->input_data["input_flame_width"] = 640;
        $this->input_data["input_flame_height"] = 650;
        $this->input_data["input_title"] = "";
        $this->input_data["input_disp"] = 1;
        $this->input_data["input_category"] = 0;
        $this->input_data["input_category_name"] = "";
    }

    //DBデータの入力用の情報を取得(全データ取得でOK)
    public function getInputViewRow()
    {
         // wpdbオブジェクト
        global $wpdb;


        $sql = "SELECT * FROM  wp_videoviewing_data";//$wpdb->prepare("SELECT * FROM  wp_videoviewing_data");
        $row = $wpdb->get_results($sql);

        if(isset($row))
        {
            $this->view_row = $row;
        }
        else {
            $this->view_row = array();
        }
    }

    //DBデータの入力用の情報を取得(全データ取得でOK)
    public function getReleaseDateViewRow()
    {
         // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM  wp_videoviewing_data ORDER BY post_release_date DESC";//$wpdb->prepare("SELECT * FROM  wp_videoviewing_data ORDER BY post_release_date DESC");
        $row = $wpdb->get_results($sql);

        if(isset($row))
        {
            $this->view_row = $row;
        }
        else {
            $this->view_row = array();
        }
    }

     //DBデータの入力用の情報を取得(カテゴリデータ指定)
    public function getCategoryIDViewRow($category_num,$get_year,$set_month)
    {
         // wpdbオブジェクト
        global $wpdb;


        $query = "SELECT * FROM  wp_videoviewing_data WHERE  post_categorys = %d";
        $sql = $wpdb->prepare($query,$category_num);

        //echo $sql;
        $row = $wpdb->get_results($sql);

        if(isset($row))
        {
            

            $this->view_category_view_row = array();

            foreach ($row as $rows)
            {
                $year_m =  date('Y',  strtotime($rows->post_release_date));
                $month_m =  date('n',  strtotime($rows->post_release_date));

                if($get_year == $year_m && $set_month == $month_m){
                    
                    $day_m =  date('j',  strtotime($rows->post_release_date));

                    $this->view_category_view_row[$day_m] = $this->getMySQLChangeArray($rows);
                }

            }

        }
        else {
            $this->view_category_view_row = array();
        }

        //var_dump($this->view_category_view_row);
    }


     //カテゴリーデータ取得
    public function getCategoryDataRow()
    {
         // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM wp_videoviewing_category";//$wpdb->prepare("SELECT * FROM wp_videoviewing_category");

        //echo $sql;

        $row = $wpdb->get_results($sql);

        //var_dump($row);

        if(isset($row))
        {
            $this->view_category_row = $row;

            //連想配列に入れなす

             $this->view_category_array = array();

             foreach ($this->view_category_row as $rows)
             {
                 $this->view_category_array[$rows->ID]["name"] = $rows->post_category_name;
                 $this->view_category_array[$rows->ID]["type"] = $rows->post_type;
             }

            
        }
        else {
            $this->view_category_row = array();
             $this->view_category_array = array();
        }
    }




     //DBデータの入力用の情報を取得(全データ取得でOK)
    public function getNewViewRow()
    {
         // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM wp_videoviewing_data ORDER BY ID DESC LIMIT 1";//$wpdb->prepare("SELECT * FROM wp_videoviewing_data ORDER BY ID DESC LIMIT 1");
        $row = $wpdb->get_results($sql);

        //echo $sql;
        //var_dump($row);

        if(isset($row))
        {
            $this->view_row = $row;

            //最新データをセット
            foreach ($this->view_row as $rows) 
            {
            
                $this->input_data["input_id"] =$rows->ID;
                $this->input_data["input_release_date"] = $this->changeSetData($rows->post_release_date);
                $this->input_data["input_flame"] =$rows->post_flame;
                $this->input_data["input_url"] =$rows->post_url;
                $this->input_data["input_title"] = $rows->post_title;
                $this->input_data["input_flame_width"] = $rows->post_flame_width;
                $this->input_data["input_flame_height"] =$rows->post_flame_height;
                $this->input_data["input_disp"] = $rows->post_disp;
                $this->input_data["input_category"] = $rows->post_categorys;
            }
        }
        else {
            $this->view_row = array();
        }
    }

    
     //データの取得
    public function getViewData($input_str)
    {
        return $this->input_data[$input_str];
    }


     //データのセット
    public function setViewData($input_str,$set_data)
    {
       $this->input_data[$input_str] = $set_data;
    }


     //カテゴリネームの取得
    public function getCategoryNameData($set_num)
    {
        $num = $set_num;

        if($num == 0) $num = 1;

        if(isset($this->view_category_array[$num]["name"]))
        {
            return  $this->view_category_array[$num]["name"];
        }

       return  1;
    }


      
    //POSTデータの設置
    public  function setPostData($postData)
    {
        

        if(isset($postData['input_id']) )
        {
            $this->input_data["input_id"] = $postData['input_id'];
        }

        $this->input_data["input_release_date"] =   $postData["input_release_date"];

        $this->input_data["input_flame_width"] = $postData["input_flame_width"];
        $this->input_data["input_flame_height"] =$postData["input_flame_height"];
        $this->input_data["input_flame"] = $postData["input_flame"];
        $this->input_data["input_url"] = $postData["input_url"];
        $this->input_data["input_title"] = $postData["input_title"];
        $this->input_data["input_disp"] =$postData["input_disp"];
        $this->input_data["input_category"] =$postData["input_category"];

       // var_dump($this->input_data);
    }


    
    //同じタイトルがあるかどうかを確認
    public function checkTitle($postData)
    {
        if(!$this->view_row)
        {
            return false;
        }

       // var_dump($this->view_row);


        foreach ($this->view_row as $row) 
        {
            if($row->post_title == $postData['input_title'])
            {
                return true;
            }
        }

        return false;
    }
    
    
    //入力画面等編集データのセット
    public function setEditData($postData)
    {
        foreach ($this->view_row as $row) 
        {
            if($row->ID == $postData['id'])
            {
                $this->input_data["input_id"] =$row->ID;
                $this->input_data["input_release_date"] =  $this->changeSetData($row->post_release_date);
                //$this->input_data["input_release_date"] = $row->post_release_date;
                $this->input_data["input_flame"] =$row->post_flame;
                $this->input_data["input_url"] =$row->post_url;
                $this->input_data["input_title"] = $row->post_title;
                $this->input_data["input_flame_width"] = $row->post_flame_width;
                $this->input_data["input_flame_height"] =$row->post_flame_height;
                $this->input_data["input_disp"] = $row->post_disp;
                $this->input_data["input_category"] = $row->post_categorys;
                break;
            }
        }
    }


     //MSQLデータをこちらの配列にする
    public function getMySQLChangeArray($row)
    {
       $change_array = array();


       $change_array["input_id"] =$row->ID;
       $change_array["input_release_date"] =  $this->changeSetData($row->post_release_date);
       $change_array["input_flame"] =$row->post_flame;
       $this->input_data["input_url"] =$row->post_url;
       $change_array["input_title"] = $row->post_title;
       $change_array["input_flame_width"] = $row->post_flame_width;
       $change_array["input_flame_height"] =$row->post_flame_height;
       $change_array["input_disp"] = $row->post_disp;
       $change_array["input_category"] = $row->post_categorys;
        
        return $change_array;
    }


    //公開のものを取得する
    public function setOpenData($all_data)
    {
        $open_array = array();

        //var_dump($this->view_row);

        foreach ($this->view_row as $row) 
        {
            if( $row->post_disp == 1 || $all_data)
            {
                $data_array = array();

               

                //echo  date('Y-m-dTH:i:s',  $row->post_release_date)."<br >";
            
                $data_array["input_id"] =$row->ID;
                $data_array["input_release_date"] = $row->post_release_date;
                $data_array["input_flame"] =$row->post_flame;
                $data_array["input_url"] =$row->post_url;


                if($data_array["input_url"] != ""){
                    $data_array["input_private"] = str_replace("https://vimeo.com/".$data_array["input_flame"] ."/" , '', $data_array["input_url"]);;

                }

                $data_array["input_title"] = $row->post_title;
                $data_array["input_flame_width"] = $row->post_flame_width;
                $data_array["input_flame_height"] =$row->post_flame_height;
                $data_array["input_disp"] = $row->post_disp;
                $data_array["input_category"] = $row->post_categorys;

                //カテゴリがない場合をセットする
                 if(!isset($this->view_category_array[$data_array["input_category"]]["name"]))
                 {
                    $data_array["input_category"] = 1;
                 }
                

                 array_push($open_array,$data_array);
            }

            
        }

       // var_dump($open_array);

        return $open_array;
    }


    //表示用に日付データを戻す
    public function changeSetData($setTimes)
    {
        $year_m =  date('Y-m-d',  strtotime($setTimes));
        $hour_m =  date('H:i:s',  strtotime($setTimes));

        $set_h = $year_m ."T" .$hour_m;

        return $set_h;
    }


      //動画を新規登録
    public function insertViewData($user_id)
    {
        // wpdbオブジェクト
        global $wpdb;

         $release_date = str_replace('T', ' ', $this->input_data["input_release_date"]);

       
          $insert = array(
            'post_author'=>$user_id, 
            'post_date' => date("Y-m-d H:i:s"),
            'post_update_date'=> date("Y-m-d H:i:s"),
            'post_release_date'  =>  date('Y-m-d H:i:s',  strtotime($release_date)),

            'post_title' => $this->input_data["input_title"], 
            'post_flame' => $this->input_data["input_flame"], 
            'post_url' => $this->input_data["input_url"], 
            'post_flame_width'=> $this->input_data["input_flame_width"], 
            'post_flame_height'=> $this->input_data["input_flame_height"], 

            'post_disp' =>  $this->input_data["input_disp"], 
            'post_categorys' =>  $this->input_data["input_category"], 

         );

        // var_dump($insert);

         $dataFormat = array('%d','%s','%s','%s','%s','%d','%s','%d','%d','%d','%d');

         $sql_rsl = $wpdb->insert('wp_videoviewing_data', $insert, $dataFormat); 

         if ( $sql_rsl == false ) {
            //登録失敗
            return false;
         }
         else {
            //登録成功
            return true;
         }
    }

    //動画更新
    public function upDataViewData($user_id)
    {
        // wpdbオブジェクト
        global $wpdb;


         $release_date = str_replace('T', ' ', $this->input_data["input_release_date"]);

        //ここは更新
        $updata = array(
            'post_update_date'=> date("Y-m-d H:i:s"),

            'post_release_date'  =>  date('Y-m-d H:i:s',  strtotime($release_date)),

            'post_title' => $this->input_data["input_title"], 
            'post_flame' => $this->input_data["input_flame"], 
            'post_url' => $this->input_data["input_url"], 
            'post_flame_width'=> $this->input_data["input_flame_width"], 
            'post_flame_height'=> $this->input_data["input_flame_height"], 
            'post_disp' =>  $this->input_data["input_disp"], 
            'post_categorys' =>  $this->input_data["input_category"], 
         );

         //更新したい行の条件
          $condition = array(
              'ID' => $this->input_data["input_id"],
           );

           $dataFormat = array('%d','%s','%s','%s','%s','%d','%s','%d','%d','%d','%d');
           $conditionsFormat = array('%d');
           $sql_rsl = $wpdb->update('wp_videoviewing_data', $updata, $condition,$dataFormat,$conditionsFormat); 

            
           if ( $sql_rsl == false ) {
	            //更新失敗
                return false;
            } else {
	            //更新成功
                return true;
           }
    }

    //カテゴリーを新規登録
    public function insertCategoryData($user_id,$category_name,$category_type)
    {
        // wpdbオブジェクト
        global $wpdb;

          $insert = array(
            'post_author'=>$user_id, 
            'post_category_name' =>$category_name,
            'post_date' => date("Y-m-d H:i:s"),
            'post_type' =>$category_type,
         );

        // var_dump($insert);

         $dataFormat = array('%d','%s','%s','%d');

         $sql_rsl = $wpdb->insert('wp_videoviewing_category', $insert, $dataFormat); 

         if ( $sql_rsl == false ) {
            //登録失敗
            return false;
         }
         else {
            //登録成功
            return true;
         }
    }

      //カテゴリーを更新
    public function upDataCategoryData($user_id,$category_id,$category_name,$category_type)
    {
        // wpdbオブジェクト
        global $wpdb;


          //ここは更新
        $updata = array(
            'post_category_name' =>$category_name,
            'post_date' => date("Y-m-d H:i:s"),
            'post_type' =>$category_type,
         );

         //更新したい行の条件
          $condition = array(
              'ID' => $category_id,
           );

         $dataFormat = array('%s','%s','%d');
         $conditionsFormat = array('%d');
         $sql_rsl = $wpdb->update('wp_videoviewing_category', $updata, $condition,$dataFormat,$conditionsFormat); 

        if ( $sql_rsl == false ) {
	            //更新失敗
                return false;
        } else {
	            //更新成功
                return true;
       }
    }

    //カテゴリーデータ削除
    public function deleteCategoryData($post_id)
    {
        // wpdbオブジェクト
        global $wpdb;

         //更新したい行の条件
          $condition = array(
              'ID' => $post_id,
           );

           $conditionsFormat = array('%d');
           $sql_rsl = $wpdb->delete('wp_videoviewing_category', $condition,$conditionsFormat); 

            
           if ( $sql_rsl == false ) {
	            //削除失敗
                return false;
            } else {
	            //削除成功
                return true;
           }
     }


     //各動画の本数を調べる
     public function checkCategoryNum( $level,$member_type )
     {
        $this->view_category_number = array(); 

        //まず各カテゴリを初期化する
         foreach ($this->view_category_row as $rows)
         {
             $this->view_category_number[$rows->ID] = 0;
         }

         //全動画の本数をカテゴリなしに集める
         foreach ($this->view_row as $rows) 
         {
             $this->view_category_number[1]++;
         }
         
        

        //本数をカウント(1以外は削除する)
        foreach ($this->view_row as $rows) 
        {
            if($rows->post_categorys > 1 && isset( $this->view_category_number[$rows->post_categorys]))
            {
                 $is_disp = true;

                if( $level  == UserClass::KUNRENSEI){//訓練生

                    $type =  $this->view_category_array[$rows->post_categorys]["type"];

                    if($type != 0 && $member_type != $type)
                    {
                        $is_disp = false;
                    }

                }
                else if( $level  == UserClass::DOGA){//動画

                     $type =  $this->view_category_array[$rows->post_categorys]["type"];

                    if($type != 0 && $member_type != $type)
                    {
                        $is_disp = false;
                    }
                }


                //非表示は入らない
                if($rows->post_disp > 0 && $is_disp)
                {
                    $this->view_category_number[$rows->post_categorys]++;
                }
                $this->view_category_number[1]--;
            }
        }

         //var_dump($this->view_category_number);
     }


    //丸数字とリンクを取得
    public function getVideoLink($num)
    {
        if(isset($this->view_category_view_row[$num]))
        {
            //$n = 9311 + $num;
            //return '&#'.$n;

            
            return '<td class="maru">' .$num ."</td>";

            //return $num;
        }
        else {
            return '<td  class="calendar-td">' .$num ."</td>";
        }

    }

   


    //動画を並べなおす（管理者）
    public function make_vimeo_admin_list($rows,$disp_type)
    {
        $count_data = 1;

        $video_set_array = array();

        if($rows){

            foreach ($rows as $row) 
            {
                if( ( $disp_type == VideoViewClass::ALL_CATEGORY_DISP )  || ( $disp_type == $row["input_category"] ) )
                {
                    $video_set_array[$count_data] = $row;
           
                     $count_data++;
                }
            }
        }

        if($count_data == 0)
        {
            $video_set_array = "";
        }

        return $video_set_array;

    }

}

?>

