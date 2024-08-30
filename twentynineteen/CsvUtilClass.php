<?php

//require_once("userClass.php");

class CsvUtilClass
{
	public static function createCsv($filename,$records)
	{
		
		require_once("userClass.php");

		$users_data= new UserClass();


		$filename_day = $filename ."_" . time();

		/*$createCsvFilePath = "/home/rkrkdiary/nightchart.jp/public_html/wp-content/themes/twentynineteen/csv_save/".$filename.".csv";

		if(!file_exists($createCsvFilePath)){
			touch($createCsvFilePath);
		}
		
		$createCsvFile = fopen($createCsvFilePath, "w");

		stream_filter_prepend($createCsvFile,'convert.iconv.utf-8/cp932'); //ストリームフィルタ指定

		foreach($records as $record){

			$user_info = get_userdata( $record );

			//会員
			$level = get_the_author_meta('member_level',$user_info->ID);

			$user_str_data = array();

			array_push($user_str_data,$user_info->ID);
			array_push($user_str_data,get_the_author_meta('last_name',$record) .get_the_author_meta('first_name',$record));
			//array_push($user_str_data,$users_data->level_array_data[ $level ]);
			array_push($user_str_data,$user_info->user_email);
			array_push($user_str_data,get_the_author_meta('billing_postcode',$record));
			array_push($user_str_data,get_the_author_meta('billing_address_1',$record) .get_the_author_meta('billing_address_2',$record));
			array_push($user_str_data,get_the_author_meta('billing_phone',$record));


			fputcsv($createCsvFile, $user_str_data);
		}

		fclose($createCsvFile);
		*/
	
		header('Content-Type: application/octet-stream');
		header("Content-Disposition: attachment; filename=$filename_day.csv");
	
		//ファイルを開く
		$stream = fopen('php://output', 'w');

		stream_filter_prepend($stream,'convert.iconv.utf-8/cp932'); //ストリームフィルタ指定
		
		//ヘッダーを書き込み
		//fputcsv($stream, $header);
		
		//レコードを書き込み
		foreach($records as $record){

			if(get_the_author_meta('billing_postcode',$record) == "")
			{
				continue;
			}

			$user_info = get_userdata( $record );

			$user_str_data = array();

			array_push($user_str_data,$user_info->ID);
			array_push($user_str_data,get_the_author_meta('last_name',$record) .get_the_author_meta('first_name',$record));
			//array_push($user_str_data,$users_data->level_array_data[ $level ]);
			array_push($user_str_data,$user_info->user_email);
			array_push($user_str_data,"'".get_the_author_meta('billing_postcode',$record));
			array_push($user_str_data,get_the_author_meta('billing_address_1',$record) .get_the_author_meta('billing_address_2',$record));
			array_push($user_str_data,"'".get_the_author_meta('billing_phone',$record));

			
			//登録日
			$member_level = get_the_author_meta('member_level',$record);
            $member_type = get_the_author_meta('member_type',$record);
			$member_ban = get_the_author_meta('login_ban',$record);

			$member_str = $users_data->checkLevelStr($member_level);

			array_push($user_str_data,$member_str);

			if( $member_level == 0 && $member_type > 0)
            {
					$member_str = $users_data->checkMemberTypeStr($member_type);

					array_push($user_str_data,$member_str);
			}
			else{
				array_push($user_str_data,"");
			}

			
      

			//BAN
			$member_ban = get_the_author_meta('login_ban',$record);

			if($member_ban == true){
				array_push($user_str_data,"B");
			}
			else{
				array_push($user_str_data,"");
			}

			
			$registerd = get_the_author_meta('user_registered',$record);
            $the_registerd_date =  date('Y年m月d日 H時i分', strtotime($registerd));


			array_push($user_str_data,$the_registerd_date);

			fputcsv($stream, $user_str_data);
		}
		exit();
		
	}
}

?>