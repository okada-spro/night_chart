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
        $this->input_data["input_video_type"] = 0;
        $this->input_data["input_youtube_id"] = "";
        
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
        $this->input_data["input_video_type"] = 0;
        $this->input_data["input_youtube_id"] = "";
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
                $this->input_data["input_video_type"] = $rows->post_video_type;
                $this->input_data["input_youtube_id"] = $rows->post_youtube_id;
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
        $this->input_data["input_youtube_id"] =$postData["input_youtube_id"];
        $this->input_data["input_video_type"] =$postData["input_video_type"];

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
                $this->input_data["input_video_type"] = $row->post_video_type;
                $this->input_data["input_youtube_id"] = $row->post_youtube_id;
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
       $change_array["input_video_type"] = $row->post_video_type;
       $change_array["input_youtube_id"] = $row->post_youtube_id;
        
        return $change_array;
    }


    //公開のものを取得する
    public function setOpenData($all_data,$key_id = false)
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
                    $data_array["input_private"] = str_replace("https://vimeo.com/".$data_array["input_flame"] ."/" , '', $data_array["input_url"]);
                }

                $data_array["input_title"] = $row->post_title;
                $data_array["input_flame_width"] = $row->post_flame_width;
                $data_array["input_flame_height"] =$row->post_flame_height;
                $data_array["input_disp"] = $row->post_disp;
                $data_array["input_category"] = $row->post_categorys;
                $data_array["input_video_type"] = $row->post_video_type;
                $data_array["input_youtube_id"] = $row->post_youtube_id;

                //カテゴリがない場合をセットする
                 if(!isset($this->view_category_array[$data_array["input_category"]]["name"]))
                 {
                    $data_array["input_category"] = 1;
                 }
                

                 if($key_id == false)
                 {
                    array_push($open_array,$data_array);
                 }
                 else{
                     $open_array[$row->ID] = $data_array;
                 }
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

            'post_video_type' =>  $this->input_data["input_video_type"], 
            'post_youtube_id' =>  $this->input_data["input_youtube_id"], 

         );

        // var_dump($insert);

         $dataFormat = array('%d','%s','%s','%s','%s','%d','%s','%d','%d','%d','%d','%d','%s');

         $sql_rsl = $wpdb->insert('wp_videoviewing_data', $insert, $dataFormat); 

         if ( $sql_rsl == false ) {
            //登録失敗
            return false;
         }
         else {
            //登録成功
            return $wpdb->insert_id;
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
            'post_video_type' =>  $this->input_data["input_video_type"], 
            'post_youtube_id' =>  $this->input_data["input_youtube_id"], 
         );

         //更新したい行の条件
          $condition = array(
              'ID' => $this->input_data["input_id"],
           );

           $dataFormat = array('%s','%s','%s','%s','%s','%d','%d','%d','%d','%d','%s');
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


     //各動画の本数を調べる(必ず会員別チェックの項目を参照する事)
     public function checkCategoryNum( $level,$member_type ,$user_id)
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

         //ユーザー別動画追加情報を取得
         $users_data= new UserClass();

         $uder_vide_add_data = $users_data->GetUserVideoAdd(  $user_id   );

        

        //本数をカウント(1以外は削除する)
        foreach ($this->view_row as $rows) 
        {
            if($rows->post_categorys > 1 && isset( $this->view_category_number[$rows->post_categorys]))
            {
                 $is_disp = true;

                 // *マスターマインド会員は全部見れる


                 if( $level  == UserClass::MONKASEI){//門下生

                    $type =  $this->view_category_array[$rows->post_categorys]["type"];

                    //マスターマインド講義は見れない
                    if($rows->post_categorys == 26 )
                    {
                        $is_disp = false;
                    }

                }
                else if( $level  == UserClass::KUNRENSEI){//訓練生

                    $type =  $this->view_category_array[$rows->post_categorys]["type"];

                    if($type != 0 && $member_type != $type)
                    {
                        $is_disp = false;
                    }

                }
                else if( $level  == UserClass::DOGA){//動画

                     $type =  $this->view_category_array[$rows->post_categorys]["type"];

                    if($type != 0 && $member_type != $type && $rows->post_categorys != 24)
                    {
                        $is_disp = false;
                    }
                }
                else if( $level  == UserClass::NEW_DOGA){//新動画
                    //知らない方が幸せな話とピーなしすべらない話と質問会のみ
                    if($rows->post_categorys != 21 && $rows->post_categorys != 22 && $rows->post_categorys != 24 )
                    {
                        $is_disp = false;
                    }
                }

                else if( $level  == UserClass::SPECIAL_SEMINAR){//特別セミナー


                    if($rows->post_categorys != 21 && $rows->post_categorys != 22 )
                    {
                        $is_disp = false;
                    }
                }


                //ユーザー別の追加情報を取得
                if(isset($uder_vide_add_data[$rows->post_categorys]))
                {
                    $is_disp = true;
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


     //会員レベルとタイプから何が見れるかだけの配列を返す
     public function checkMemberEnableVideo( $level,$member_type )
     {

        $video_enable_array = array();

        //本数をカウント(1以外は削除する)
        foreach ($this->view_row as $rows) 
        {
            if($rows->post_categorys > 1)
            {
                 $is_disp = true;


                 if( $level  == UserClass::MONKASEI){//門下生

                    $type =  $this->view_category_array[$rows->post_categorys]["type"];

                    //マスターマインド講義は見れない
                    if($rows->post_categorys == 26 )
                    {
                        $is_disp = false;
                    }
                }
                else if( $level  == UserClass::KUNRENSEI){//訓練生

                    $type =  $this->view_category_array[$rows->post_categorys]["type"];

                    if($type != 0 && $member_type != $type)
                    {
                        $is_disp = false;
                    }

                }
                else if( $level  == UserClass::DOGA){//動画

                     $type =  $this->view_category_array[$rows->post_categorys]["type"];

                    if($type != 0 && $member_type != $type && $rows->post_categorys != 24) //直接指定している
                    {
                        $is_disp = false;
                    }
                }
                else if( $level  == UserClass::NEW_DOGA){//新動画
                    //知らない方が幸せな話とピーなしすべらない話と質問会のみ
                    if($rows->post_categorys != 21 && $rows->post_categorys != 22 && $rows->post_categorys != 24 )
                    {
                        $is_disp = false;
                    }
                }

                else if( $level  == UserClass::SPECIAL_SEMINAR){//特別セミナー


                    if($rows->post_categorys != 21 && $rows->post_categorys != 22 )
                    {
                        $is_disp = false;
                    }
                }

                //非表示は入らない
                if($rows->post_disp > 0 && $is_disp)
                {
                    $video_enable_array[$rows->post_categorys] = 1;
                }
            }
        }

        
        return $video_enable_array;
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


    /****************************************************
    **  動画の追加データを取得
    ******************************************************/
    public function getAddVideo( )
    {
        $wp_query = new WP_Query();
        
         $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_add_video_data', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

       
        //保存配列
        $video_data_array = "";

        //データを入れる
        if($wp_query->have_posts()): while($wp_query->have_posts()) : $wp_query->the_post();


            if($video_data_array == "")
            {
                $video_data_array = array();
            }


            $vide_id = get_field( 'item_afc_video_id');

            $video_data_array[  $vide_id ] = get_the_ID();

          
        endwhile; endif;


        return $video_data_array;

    }

    /***********************************************************************
    ** 動画の追加データを登録 
    ************************************************************************/
    public function setAddVideo( $video_id , $video_title )
    {
        
       $user = wp_get_current_user();


       $my_post = array(
            'post_title' => $video_title,
            'post_type' => 'cpt_add_video_data', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $program_id = wp_insert_post($my_post);

        if($program_id)
        {
            update_field( "item_afc_video_id", $video_id , $program_id);//内容
            update_field( "item_add_vdeo_title", $video_title , $program_id);//タイトル
            update_field( "item_afc_video_new_num", 0 , $program_id);//回数
        }


        return $program_id;

    }

    /***********************************************************************
    ** お知らせの回数とタイトルを変更する
    ************************************************************************/
    public function plusAddVideoNew( $video_add_id , $video_title )
    {
        
       $video_news_num =  get_field( 'item_afc_video_new_num',$video_add_id);

       if($video_news_num == "")
       {
           $video_news_num = 0;
       }

       $video_news_num++;

       update_field( "item_afc_video_new_num", $video_news_num , $video_add_id);//お知らせ更新

       //タイトル名更新
       update_field( "item_add_vdeo_title", $video_title , $video_add_id);//タイトル

    }


    /***********************************************************************
    ** ユーザーの動画完了をセット
    ************************************************************************/
    public function setVideoComplete( $user_id ,  $video_id , $video_categoy_id  )
    {
        
        if($video_id == "" || $video_categoy_id == "")
        {
            return;
        }

       $video_complete =  get_field( 'user_video_upload_check_array',$user_id);

       if($video_complete == "")
       {
           $video_complete = array();
       }
       else{
           $video_complete = json_decode($video_complete, true);
       }


       //カテゴリーを作成
       if(!isset($video_complete[$video_categoy_id]))
       {
           $video_complete[$video_categoy_id] = array();
       }

       //動画番号をセット
       if(!isset($video_complete[$video_categoy_id][ $video_id ]))
       {
           $video_complete[$video_categoy_id][ $video_id ] = 1;
       }


       $json_data = json_encode($video_complete, JSON_UNESCAPED_UNICODE);

       update_field( "user_video_upload_check_array", $json_data , $user_id);//保存


    }


    /***********************************************************************
    ** ユーザーの動画完了を取得
    ************************************************************************/
    public function getVideoComplete( $user_id )
    {
        
       $video_complete =  get_field( 'user_video_upload_check_array',$user_id);

       if($video_complete == "")
       {
           $video_complete = array();
       }
       else{
           $video_complete = json_decode($video_complete, true);
       }

       
       return $video_complete;

    }



    /***********************************************************************
    ** 新着情報を送信
    ************************************************************************/
    public function setNewVideo( $video_id , $video_title , $video_category )
    {
          //var_dump($_POST);
        $add_vide_data = $this->getAddVideo();

        //データがない
        if(!isset($add_vide_data[ $video_id ]))
        {
            $this->setAddVideo( $video_id , $video_title );

            //再取得
            $add_vide_data = $this->getAddVideo();
        }

        //回数をあげる
        $this->plusAddVideoNew($add_vide_data[ $video_id ]  , $video_title );

        //全ユーザーに新規動画を保存
        $users = get_users( array('orderby'=>'ID','order'=>'ASC') );


        //動画データを取得
        $this->getCategoryDataRow();


      //  echo $this->view_category_array[ $_POST["video_category"]]["type"];
     
        foreach ($users as $row)
        {


            //ユーザーの視聴完了リスト
            $complete_array = $this->getVideoComplete( $row->ID );

            //すでに見ている場合は飛ばす
            if( isset($complete_array[ $video_category ][$video_id ]))
            {
                continue;
            }

      
            $member_level = get_the_author_meta('member_level',$row->ID);//メンバーレベルを取得
            $member_type = get_the_author_meta('member_type',$row->ID);

            
            if($this->view_category_array[ $video_category ]["type"] == 0) //なし
            {

                if($member_level ==  UserClass::NEW_DOGA) //新動画会員はなしでも投資系は見れない
                {
                    //知らない方が幸せな話とピーなしすべらない話と質問会のみ
                    if($video_category == 21 || $video_category == 22 || $video_category == 24)
                    {
                        update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $video_id ]); //なしの場合はほぼ全員見れる
                        update_user_meta($row->ID,'user_video_upload_category', $video_category ); //なしの場合はほぼ全員見れる
                    }
                }
                else{
                    update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $video_id ]); //なしの場合はほぼ全員見れる
                    update_user_meta($row->ID,'user_video_upload_category', $video_category ); //なしの場合はほぼ全員見れる
                }
            }
            else if($this->view_category_array[ $video_category ]["type"] == 1) //株
            {
                if($member_level ==  UserClass::MONKASEI) //門下生は全部見れる
                {
                    update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $video_id ]);
                    update_user_meta($row->ID,'user_video_upload_category', $video_category ); //なしの場合はほぼ全員見れる
                }
                else if($member_level ==  UserClass::KUNRENSEI && $member_type == 1) //訓練生では株は見れる
                {
                    update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $video_id ]);
                    update_user_meta($row->ID,'user_video_upload_category', $video_category ); //なしの場合はほぼ全員見れる
                }
                /*else if($member_level ==  UserClass::DOGA && $member_type == 1) //動画会員では株は見れる
                {
                    update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $video_id ]);
                    update_user_meta($row->ID,'user_video_upload_category', $video_category ); //なしの場合はほぼ全員見れる
                }
                */
                else{

                }

            }
            else if($this->view_category_array[ $video_category ]["type"] == 2) //FX
            {
                 if($member_level ==  UserClass::MONKASEI) //門下生は全部見れる
                {
                    update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $video_id ]);
                    update_user_meta($row->ID,'user_video_upload_category', $video_category ); //なしの場合はほぼ全員見れる
                }
                else if($member_level ==  UserClass::KUNRENSEI && $member_type == 2) //訓練生ではFXは見れる
                {
                    update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $video_id ]);
                    update_user_meta($row->ID,'user_video_upload_category', $video_category ); //なしの場合はほぼ全員見れる
                }
                /*else if($member_level ==  UserClass::DOGA && $member_type == 2) //動画会員ではFXは見れる
                {
                    update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $video_id ]);
                    update_user_meta($row->ID,'user_video_upload_category', $video_category ); //なしの場合はほぼ全員見れる
                }
                */
                else{

                }
            }
            else if($this->view_category_array[ $video_category ]["type"] == 3) //ダイジェスト
            {
                update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $video_id ]);
                update_user_meta($row->ID,'user_video_upload_category',$video_category ); //なしの場合はほぼ全員見れる
            }
            else if($this->view_category_array[ $video_category ]["type"] == 4) //動画会員
            {
                update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $video_id ]);
                update_user_meta($row->ID,'user_video_upload_category', $video_category ); //なしの場合はほぼ全員見れる
            }
            else if($this->view_category_array[ $video_category ]["type"] == 5) //新動画会員
            {
                update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $video_id ]);
                update_user_meta($row->ID,'user_video_upload_category', $video_category ); //なしの場合はほぼ全員見れる
            }
            else if($this->view_category_array[ $video_category ]["type"] == 6) //マスターマインド
            {
                if($member_level ==  UserClass::MASTER_MIND) //マスターマインドのみ
                {
                    update_user_meta($row->ID,'user_video_upload_id',$add_vide_data[ $video_id ]);
                    update_user_meta($row->ID,'user_video_upload_category', $video_category ); //なしの場合はほぼ全員見れる
                }
            }
            else
            {
                update_user_meta($row->ID,'user_video_upload_id',"");
            }

            
        }


       



    }

    /***********************************************************************
    **  表示する動画を入れて、ソート順に並べなおす
    ************************************************************************/
    public function setSortVideoArray( $open_array , $vide_add_array )
    {
        
          $sort_array = array();

          //順番を確認する
          foreach ($open_array as $row) {

            if(isset( $vide_add_array[$row["input_id"]])){


                $sort_num = get_field( 'item_add_vdeo_sort_num',$vide_add_array[  $row["input_id"] ]);

                if( $sort_num != "")
                {
                    if( !isset($sort_array[ $sort_num ] ) )
                    {
                        $sort_array[ $sort_num ] = array();
                    }


                    $sort_array[ $sort_num ][  $row["input_id"] ] = $row;
                }
            }
          }
          ksort($sort_array);
          //var_dump($sort_array);



          //順番に上から入れていく
          $sort_open_array = array();

          foreach ($sort_array as $key => $row) {

            
             foreach ($row as  $id_key => $value ) {

                $sort_open_array[ $id_key ] = $value;

             }

          }

          foreach ($open_array as $key => $row) {


            if( !isset($sort_open_array[ $row["input_id"] ] ))
            {
                $sort_open_array[ $row["input_id"] ] = $row;
            }

          }


          return $sort_open_array;

    }
}

?>

