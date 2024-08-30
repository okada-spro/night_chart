<?php 

class NewstClass
{
    public $input_data ;//インプット用の配列

    public $news_row;//データ呼び出し

    public function __construct()
    {
          $this->input_data["input_id"] = 0;
          $this->input_data["input_text"] = "";
          $this->input_data["input_title"] ="";
          $this->input_data["input_disp"] = 0;
    }

    //初期化
    public  function init()
    {
          $this->input_data["input_id"] = 0;
          $this->input_data["input_text"] = "";
          $this->input_data["input_title"] ="";
          $this->input_data["input_disp"] = 0;
        
    }

   
    //会員レベルに合わせてお知らせを取得する
    function getNewsPost($user_id)
    {
        $disp_post_array = array();

        $member_level = get_the_author_meta('member_level',$user_id);
        $member_type = get_the_author_meta('member_type',$user_id);

        //記事を取得していく
        query_posts("&posts_per_page=30&paged=$paged");
        if (have_posts()) :
        while ( have_posts() ) : the_post();


            //会員
            $news_repeater = get_field( 'news_repeater', get_the_ID());

            //タイプ
            $news_repeater_type = get_field( 'news_repeater_type', get_the_ID());



            $is_in_disp = true;

            //会員チェック
            if($news_repeater != null)
            {
                if(count($news_repeater) > 0)
                {
                    if( !in_array($member_level, $news_repeater , true))
                    {
                        $is_in_disp = false;
                    }
                }
            }

            //タイプ
            if($news_repeater_type != null)
            {
                if(count($news_repeater_type) > 0)
                {
                    if( !in_array( $member_type , $news_repeater_type , true))
                    {
                        $is_in_disp = false;
                    }
                }
            }


            if($is_in_disp)
            {
                $set_array = array();

                $set_array["day"] = get_post_time( 'Y年n月j日', false, null, true );
                $set_array["permalink"] = get_the_permalink();
                $set_array["title"] = get_the_title();
                $set_array["ID"] = get_the_ID();

                array_push($disp_post_array,$set_array);
      
            }

        endwhile; endif;

        wp_reset_query();

        return $disp_post_array;


    }
}
?>