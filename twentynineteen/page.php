<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php

				$custom_page_array = array(
					"0"=>  array("input_history",true,false),
					"1"=>  array("history_list",true,false),
					"2"=>  array("history_list_admin",true,true),
					"3"=>  array("zoom_list",true,true),
					"4"=>  array("videoviewing_input",true,true),
					"5"=>  array("videoviewing_list",true,true),
					"6"=>  array("videoviewing",true,false),
					"7"=>  array("videoviewing_make_category",true,true),
					"8"=>  array("zoom_input",true,true),
					"9"=>  array("zoom_registration",true,false),
					"10"=>  array("reportsubmission",true,false),
					"11"=>  array("report_make",true,true),
					"12"=>  array("report_list",true,true),
					"13"=>  array("news_list",true,false),
					"14"=>  array("news_make",true,false),
					"15"=>  array("tool-upload",true,true),
					"16"=>  array("tool-download",true,false),
					"17"=>  array("user_edit",true,false),
					"18"=>  array("user_list_comment",true,true),
					"19"=>  array("user_edit_password",true,false),
					"20"=>  array("contact",true,false),
					"21"=>  array("mail_make",true,true),
					"22"=>  array("mail_menu",true,true),
					"23"=>  array("mail_list",true,true),
					"24"=>  array("trainee_list",true,true),
					"25"=>  array("video_member_list",true,true),
					"26"=>  array("test_page",true,false),
					"27"=>  array("mail_make_all",true,false),
					"28"=>  array("contact_address",true,false),
					"29"=>  array("updata_list",true,true),
					"30"=>  array("mail_send_list",true,true),
					"31"=>  array("videosort",true,true),

					
				);

			// Start the Loop.
			while ( have_posts() ) :

				the_post();


				if(!checkMakePage( $custom_page_array ))
				{
					get_template_part( 'template-parts/content/content', 'page' );
				}
				
			endwhile; // End the loop.

			?>

			<?php
				

			?>

			<!-- ▼▼▼履歴入力▼▼▼ -->
				<?php if (is_page('user')) : ?>

					<?php 
						if(current_user_can('administrator')){ 
							include("user_list.php");
						}
					?>
				<?php endif; ?>
			<!-- ▲▲▲履歴入力▲▲▲ -->

			<?php

				foreach (  $custom_page_array as  $page_key => $page_value)
				{		
					if($page_value[1]) //ログインが必要なページ
					{
						if (  is_user_logged_in() )
						{
							//ログイン後
							$user = wp_get_current_user();

							$login_ban = get_the_author_meta('login_ban',$user->ID);

							if($login_ban == ""){
								$login_ban = false;
							}


							require_once("pageFunction.php");//ページ用

							//期限切れ
							$updata_page_base = getUserUpdataDayPage($user->ID);

							//echo $login;

							if($login_ban && !is_page("contact") )
							{
								include("logn-ban.php");
								
							}
							else if(strtotime($updata_page_base) <= strtotime( date('Y-m-d H:i:s') ) && !is_page("contact"))
							{
								include("logn-limit.php");
								break;
							}
							else
							{
								if (is_page($page_value[0]))
								{
									$page_hit = true;

									if ($page_value[2])
									{
										if(current_user_can('administrator'))
										{
											include($page_value[0] .".php");
										}
									}else{
										include($page_value[0] .".php");
									}
								}
							}
						}
					}
					else{
						if (is_page($page_value[0])){
							$page_hit = true;
							include($page_value[0] .".php");
						}
					}
				}
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();


//現在のページこちらで作っているページかどうか
function checkMakePage( $my_custom_page )
{
	foreach (  $my_custom_page as  $page_key => $page_value)
	{
		if(is_page( $page_value[0] ))
		{
			return true;
		}
	}

	if (is_page('user')) 
	{
		return true;
	}

	return false;
}

/*
  function getUserUpdataDayPage(  $user_id  )
    {
        $updata_day = get_the_author_meta('user_updata_day',$user_id);

        if($updata_day == "")
        {
            $str_day = strtotime(get_the_author_meta('user_registered',$user_id));

            $year=date('Y', $str_day);
            $month=date('n', $str_day);

            $updata_num = 0;

            //2021年はすでに１度終えていると判定する
            if($year == 2021)
            {
                $updata_num += 1;
            }
            //2022年5月までは１度終えていると判定
            else if($year == 2022 && $month <= 5 )
            {
                $updata_num += 1;
            }

            $year_num += (int)$year +  $updata_num + 1;


            $updata_day =  date('Y-m-d H:i:s', strtotime($year_num .'-' .$month .'-01 00:00:00'));

        }

        //更新無し
        if(get_the_author_meta('user_is_updata',$user_id) == 1)
        {
            return  date("Y-m-d H:i:s",strtotime("+1 year"));
        }



        return $updata_day;
    }
	*/