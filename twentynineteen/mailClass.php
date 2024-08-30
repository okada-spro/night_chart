<?php 

class MailClass
{
    //メールの送信(  $set_mail_to:宛先)
    public function getMailID($send_orner_name,$send_orner_mail,$mail_title,$mail_text,$student_mail,$student_name)
    {
        
        
        mb_language("ja");
        mb_internal_encoding("UTF-8");
       
        $mail_to = $send_orner_mail;
        $mail_subject = $mail_title;


        $mail_contents = $mail_text;

        //送信元
        $mail_from = $send_orner_name . " <" .$send_orner_mail .">";

        // 送信元メールアドレス
        $from_mail = $student_mail;

        // 送信者名
        $from_name = $student_name;

        /*
        // 送信者情報の設定
        $headers = '';
        $headers .= "Content-Type: text/plain \r\n";
        $headers .= "Return-Path: " . $from_mail . " \r\n";
        $headers .= "From: " . $mail_from ." \r\n";
        $headers .= "Sender: " . $mail_from ." \r\n";
        $headers .= "Reply-To: " . $from_mail . " \r\n";
        $headers .= "Organization: " . $from_name . " \r\n";
        $headers .= "X-Sender: " . $from_mail . " \r\n";
        $headers .= "X-Priority: 3 \r\n";

        $res = wp_mail( $mail_to, $mail_subject, $mail_contents,$headers );
        */


        mb_language("Japanese"); 
        mb_internal_encoding("UTF-8");
 
        $email = $mail_to;
        $subject = $mail_title; // 題名 
        $body = $mail_text; // 本文
        $to = $student_mail;

        $header = "From: $mail_from\n";

        /*
        echo $email ."<br>";
         echo $subject ."<br>";
          echo $body ."<br>";
           echo $header ."<br>";
           */
 
        mb_send_mail($to, $subject, $body, $header);

    }


     //メールの送信 BCCにセット(  $set_mail_to:宛先)
    public function getMailIDALL($send_orner_name,$send_orner_mail,$mail_title,$mail_text,$student_array)
    {
        
        
        mb_language("ja");
        mb_internal_encoding("UTF-8");
       
        $mail_to = $send_orner_mail;
        $mail_subject = $mail_title;


        $mail_contents = $mail_text;

        //送信元
        $mail_from = $send_orner_name . " <" .$send_orner_mail .">";

        // 送信元メールアドレス
        $from_mail = $student_mail;

       
        /*
        // 送信者情報の設定
        $headers = '';
        $headers .= "Content-Type: text/plain \r\n";
        $headers .= "Return-Path: " . $from_mail . " \r\n";
        $headers .= "From: " . $mail_from ." \r\n";
        $headers .= "Sender: " . $mail_from ." \r\n";
        $headers .= "Reply-To: " . $from_mail . " \r\n";
        $headers .= "Organization: " . $from_name . " \r\n";
        $headers .= "X-Sender: " . $from_mail . " \r\n";
        $headers .= "X-Priority: 3 \r\n";

        $res = wp_mail( $mail_to, $mail_subject, $mail_contents,$headers );
        */


        mb_language("Japanese"); 
        mb_internal_encoding("UTF-8");
 
        $email = $mail_to;
        $subject = $mail_title; // 題名 
        $body = $mail_text; // 本文
        $to = $student_mail;

        $header="From: ".$mail_from;
        $header.="\n";
        $header.="Cc: info@nightchart.jp";
        $header.="\n";
        $header.="Bcc: ";
        
        foreach ($student_array as $key=>$value){
        
            $header.= get_the_author_meta('user_email',$key);

            $header.=",";
        }

        

        //echo $header;
 
        mb_send_mail($to, $subject, $body, $header);

    }


    /****************************************************
    **  送信メールの履歴作成
    ******************************************************/
    public function setMailList( $send_orner_id,$send_orner_name,$send_orner_mail,$mail_title,$mail_text,$student_mail,$student_name ,$student_id)
    {
        //$title = 

        $now_time = date("Y-m-d H:i:s");


        //タイトルを作成
        $title = $now_time ."-FROM:" .$send_orner_name ."-TO" . $student_name ." " .$mail_title;

       // echo $title."<br>";


        
        $my_post = array(
                'post_title' => $title,
                'post_type' => 'owner_send_mail_list',// 投稿タイプ（カスタム投稿タイプも指定できるよ）
                'post_status' => 'publish',
                'post_author' => $send_orner_id,
        );

        $client_id = wp_insert_post($my_post);

        update_field( 'sendmail_list_owner_name', $send_orner_name, $client_id); //送信元
        update_field( 'sendmail_list_owner_mail', $send_orner_mail, $client_id); //送信元メールアドレス
        update_field( 'sendmail_list_user_name', $student_name, $client_id);//送信先名前
        update_field( 'sendmail_list_user_id', $student_id, $client_id);//送信先ID
        update_field( 'sendmail_list_user_mail', $student_mail, $client_id);//送信先メール

        update_field( 'sendmail_list_title', $mail_title, $client_id);//件名
        update_field( 'sendmail_list_textarea', $mail_text, $client_id);//内容
        update_field( 'sendmail_list_send_day', $now_time, $client_id);//送信日
      
        
        return $client_id;
        

    }

    /****************************************************
    **  送信メールの履歴作成
    ******************************************************/
    public function setMailListAll( $send_orner_id,$send_orner_name,$send_orner_mail,$mail_title,$mail_text,$student_array)
    {
        //$title = 

        $now_time = date("Y-m-d H:i:s");


        //タイトルを作成
        $title = $now_time ."-FROM:" .$send_orner_name ."-TO" . $student_name ." " .$mail_title;

       // echo $title."<br>";


        
        $my_post = array(
                'post_title' => $title,
                'post_type' => 'owner_send_mail_list',// 投稿タイプ（カスタム投稿タイプも指定できるよ）
                'post_status' => 'publish',
                'post_author' => $send_orner_id,
        );

        $client_id = wp_insert_post($my_post);

        update_field( 'sendmail_list_owner_name', $send_orner_name, $client_id); //送信元
        update_field( 'sendmail_list_owner_mail', $send_orner_mail, $client_id); //送信元メールアドレス


        $student_name = "";
        $student_id= "";
        $student_mail = "";

        foreach ($student_array as $key=>$value){

            $student_name .= get_the_author_meta('last_name',$key) .get_the_author_meta('first_name',$key)  .",";
            $student_id .= $key .",";
            $student_mail .= get_the_author_meta('user_email',$key) .",";
        }


        update_field( 'sendmail_list_user_name', $student_name, $client_id);//送信先名前
        update_field( 'sendmail_list_user_id_text', $student_id, $client_id);//送信先ID
        update_field( 'sendmail_list_user_mail', $student_mail, $client_id);//送信先メール

        update_field( 'sendmail_list_title', $mail_title, $client_id);//件名
        update_field( 'sendmail_list_textarea', $mail_text, $client_id);//内容
        update_field( 'sendmail_list_send_day', $now_time, $client_id);//送信日
      
        
        return $client_id;
        

    }


    /****************************************************
    **   送信メールの履歴の取得
    ******************************************************/
    public function getMailList( )
    {
         $wp_query = new WP_Query();
        
         $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'owner_send_mail_list', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        $mail_array = array();

        if($wp_query->have_posts()): while($wp_query->have_posts()) : $wp_query->the_post();

            array_push($mail_array,get_the_ID());

        endwhile; endif;

        return $mail_array;
    }



    /****************************************************
    **   送信メールの検索
    ******************************************************/
    public function searchSendMail( $searchWord , $maillist)
    {
        //ワードの検索
        $no_space_word =  str_replace(' ', '', $searchWord);
        $no_space_word =  str_replace('　', '', $no_space_word);

        if($no_space_word == "")
        {
            return $maillist;
        }


        //ワードの分割
        $change_word = str_replace('　', ' ', $searchWord);
        $change_word = str_replace('    ', ' ', $change_word);


        $result = explode(',', $change_word);
       // var_dump($result);
       // echo $change_word;



        $search_array = array();

        foreach ($maillist as $key=>$value){


            $send_user = get_field( 'sendmail_list_user_name',$value);//送信先
            //$send_owner = get_field( 'sendmail_list_owner_name',$value);//送信者
            $send_title = get_field( 'sendmail_list_title',$value);//タイトル
            $send_text = get_field( 'sendmail_list_textarea',$value);//内容

            $send_text = str_replace('　', ' ', $send_text);
            $send_text = str_replace('    ', ' ', $send_text);

            foreach ($result as $row){

                if ( strpos( $send_user, $row ) !== false  || strpos( $send_title, $row ) !== false  || strpos( $send_text, $row ) !== false ) {

                    $search_array[$key] = $value;
                    break;
                }

            }

        }

         return $search_array;
    }
}
?>