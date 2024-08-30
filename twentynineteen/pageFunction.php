<?php 

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

?>