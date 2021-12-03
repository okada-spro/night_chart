<?php 

   
    require_once("newsClass.php");

    $user = wp_get_current_user();

    //クラス作成
    $input_data= new NewstClass();

    //初期化
    $input_data->init();
 

?>

ニュース作成
