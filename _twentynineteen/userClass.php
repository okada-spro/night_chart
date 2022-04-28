<?php 
 
class UserClass
{
    public $input_data ;//インプット用の配列

    public $user_row;//データ呼び出し

    public $zoom_row;//ズームデータ




    public  $disp_array_data = array(
            0=>"生存者だけを表示",
            1=>"脱落者だけを表示",
            2=>"生存・脱落を表示",
     );

     public  $disp_level_array_data = array(
            0=>"訓練生だけを表示",
            1=>"門下生だけを表示",
            2=>"動画会員だけを表示",
            99=>"全てを表示",
     );


       public  $disp_only_level_array_data = array(
            0=>"訓練生だけを表示",
            1=>"株 訓練生だけを表示",
            2=>"FX 訓練生だけを表示",
            6=>"株だけを表示",
            7=>"FXだけを表示",
            3=>"門下生だけを表示",
            4=>"動画会員だけを表示",
            5=>"動画会員だけを非表示",
           99=>"全てを表示",
     );



    public  $level_array_data = array(
            0=>"動画会員",
            1=>"訓練生",
            2=>"門下生",
           
     );


     public $withdrawal_array = array(
            0=>"生存",
            1=>"脱落",
     );


  
      public $member_type_array = array(
            0=>"なし",
            1=>"株",
            2=>"FX",
            3=>"ダイジェスト",
     );


     /*定義*/
     public const KUNRENSEI = 1;//訓練生
     public const MONKASEI = 2;//門下生
     public const DOGA = 0;//動画会員


      /*定義*/
     public const KABU = 1;//株
     public const FX = 2;//FX

     public const ALL_DISP_CONST = 99;//全員

    public function __construct()
    {
      $this->zoom_row = array();
    }

 

    //レベルの文字チェック
    public function checkLevelStr($checklevel)
    {
        if($checklevel == "" || $checklevel == NULL)
        {
            return $this->level_array_data[0];
        }
       
        return $this->level_array_data[$checklevel];
    }

     //タイプの文字チェック
    public function checkMemberTypeStr($member_type)
    {
        if($member_type == "" || $member_type == NULL)
        {
            return $this->member_type_array[0];
        }
       
        return $this->member_type_array[$member_type];
    }




    //生存者の文字チェック
    public function checkWithdrawalStr($checklevel)
    {
        if($checklevel == "" || $checklevel == NULL)
        {
            return $this->withdrawal_array[0];
        }
       
        return $this->withdrawal_array[$checklevel];
    }



    //ZOOM情報を取得して、今月分だけ並べなおす
    public function getZoomRow()
    {
         // wpdbオブジェクト
        global $wpdb;

        $sql = "SELECT * FROM wp_zoom_data";//$wpdb->prepare("SELECT * FROM wp_zoom_data");
        $rows = $wpdb->get_results($sql);

        $now_yaear = date('Y');
        $now_month = date('m');
       

        

        if(isset($rows))
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
                    $zoom_set_array["input_zoom_url"] = $row->post_zoom_url;
                    $zoom_set_array["input_zoom_title"] = $row->post_zoom_title;

                 
                     if(isset($row->post_zoom_participant_plans))
                    {
                        $zoom_set_array["input_plams"] =explode(",",$row->post_zoom_participant_plans);
                    }
                    else{
                        $zoom_set_array["input_plams"]  = $array();
                    }

                    

                    array_push($this->zoom_row,$zoom_set_array);
                 }
            }
        }
        
    }


    //その人が今月のZOOMに出席予定か
    public function checkUserZoomPlans($user_id)
    {
        //var_dump($this->zoom_row);

        $zoom_num = 0;

        if(count($this->zoom_row) == 1)
        {
            return 0;//今月はない
        }

        //echo $user_id;

        foreach ($this->zoom_row as $row)
        {
            if( in_array($user_id,(array)$row["input_plams"]) )
            {
                $zoom_num++;
            }
        }

        return $zoom_num;
    }


     //////////////////////////////////////////////////
    //       ユーザーのメールアドレス変更
    ///////////////////////////////////////////////////
    public function changeUserMail($user_id,$mail_add)
    {
        wp_update_user([
			'ID' => $user_id,
			'user_email' => $mail_add,
]		);
    }


    //////////////////////////////////////////////////
    //       ユーザー情報の検索
    ///////////////////////////////////////////////////
    public function serchUserData($serch_word,$user_array)
    {
        //最新配列
        $new_array = array();

        //検索があったかどうか
        $check_data = false;

        $serch_word = str_replace(" ","",$serch_word);
        $serch_word = str_replace("　","",$serch_word);

        foreach ($user_array as $row){

            if(( strpos(  $row->ID , $serch_word) !== false ) || 
              ( strpos(  $row->user_login , $serch_word) !== false ) || 
              ( strpos(  $row->user_email , $serch_word) !== false ) || 
              ( strpos(  get_the_author_meta('last_name',$row->ID) , $serch_word) !== false ) || 
              ( strpos(  get_the_author_meta('first_name',$row->ID) , $serch_word) !== false ) || 
              ( strpos(  get_the_author_meta('billing_phone',$row->ID), $serch_word) !== false ) )
            {
                $check_data = true;

                array_push($new_array,$row);
            }
           
        }


        if( !$check_data )
        {
            return "";
        }
        else{
            return $new_array;
        }
    }



    //////////////////////////////////////////////////
    //      門下生と訓練生と株とFXで通るかどうか
    ///////////////////////////////////////////////////
    public function checkLevelTypeMatch($chackNum,$member_level,$member_type)
    {
        if($member_level == UserClass::MONKASEI)
        {
            //門下生は全部見れる
            return true;
        }
        else if($member_level == UserClass::KUNRENSEI)
        {
            //訓練生の場合はタイプと一致している必要がある
            if( $chackNum == $member_type){

                return true;

            }
            
        }


        return false;
    }


    //////////////////////////////////////////////////
    //      リスト関連で表示するかどうか
    ///////////////////////////////////////////////////
    public function checkDispStudents( $disp_level_table , $input_level_table , $member_level , $member_type)
    {
        $is_disp = false;

        //全て表示
        if(  $disp_level_table == 99 && $input_level_table == UserClass::ALL_DISP_CONST ){
            $is_disp = true;
        }
        else if( $input_level_table == $member_level && $input_level_table == UserClass::MONKASEI  ){ //門下生の時
            $is_disp = true;
        }
        else if( $disp_level_table == 0 && $input_level_table == UserClass::KUNRENSEI && $member_level == UserClass::KUNRENSEI ){ //訓練生の時の全表示
            $is_disp = true;
        }
        else if( $disp_level_table == 1 && $input_level_table == UserClass::KUNRENSEI && $member_level == UserClass::KUNRENSEI  && $member_type == 1){ //訓練生の時の株表示
            $is_disp = true;
        }
        else if( $disp_level_table == 2 && $input_level_table == UserClass::KUNRENSEI && $member_level == UserClass::KUNRENSEI  && $member_type == 2){ //訓練生の時のFX表示
            $is_disp = true;
        }
        else if( $disp_level_table == 5 && $member_level != UserClass::DOGA ){ //動画会員を非表示
            $is_disp = true;
        }
        else if( $disp_level_table == 6 && $member_level != UserClass::DOGA && $member_type != 2){ //株のみ表示
            $is_disp = true;
        }
        else if( $disp_level_table == 7 && $member_level != UserClass::DOGA && $member_type != 1){ //FXのみ表示
            $is_disp = true;
        }
        else if( $input_level_table == UserClass::DOGA && $member_level == UserClass::DOGA ){ //動画会員
            $is_disp = true;
        }


        return $is_disp;
    }




}

?>
   

