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

   

}
?>