<?php 
    $item_list = [
        "観葉植物パキラ（白）",
        "卓上観葉植物",
        "plate",
        "カトラリーGold",
        "カトラリーステンレス",
        "ケトル",
        "コーヒーカップ",
        "スキンケアセット",
        "鉄瓶",
        "保湿石鹸",
        "和食器（黒）"
    ];

    var_dump($_COOKIE);

    // カート内商品取得
    foreach($_COOKIE as $key => $value){
        foreach($item_list as $item_detail){
            if($item_detail==$key){
                $cart_item[$key] = $value; 
            }
        }
    }

    //商品データ取得
    $args = array("post_type" => "post", "posts_per_page" => 15);
    $myposts = get_posts($args); 	//pick up 取得

    // 画面サイズ設定
	$size_flg = wp_is_mobile();	//false:PC
?>
    <div class="cart-main">
        <?php 
        
            $amount_money = 0;
            $item_disp_no = 0;
            if($cart_item == NULL){
                echo "<p>カートは空</p>";
            }else{
                if(!$size_flg){
                    $cart_left_font_height = count($cart_item) * 72 + 60 + 200;
                    echo '<div class="cart-left-font" style="height:'.$cart_left_font_height.'px">カート内一覧</div>';
                }else{
                    echo '<div class="cart-left-font" >カート内一覧</div>';
                }
                
                
                if(!$size_flg){
        ?>
        <table>
            <tbody>
                <tr>
                    <th width="550px">商品名</th>
                    <th width="150px">単価</th>
                    <th width="150px">数量</th>
                    <th width="150px">小計</th>
                    <th width="120px">削除</th>
                </tr>
                <tr>
                <?php 
                    foreach($myposts as $page_data){
                        // キーがあるか探す
                        if(!array_key_exists($page_data->post_title,$cart_item)) continue;
                        // カスタムフィールド取得
                        $custom_field_data = get_post_meta(  $page_data->ID );
                        $item_count = $_COOKIE[$page_data->post_title];
                ?>
                    <td class="item-name">
                        <img src="http://localhost/wordpress/wp-content/themes/twentytwenty/assets/images/<?php echo $page_data->post_title?>1.png" alt=""><?php echo $page_data->post_title?>
                    </td>
                    <td class="item-money" id="item-money<?=$item_disp_no ?>"><?php echo $custom_field_data['金額'][0]?></td>
                    <td class="item-count-no">
                        <input class="item-count-sub" type="button" value="-" onclick="CountMinus(<?=$item_disp_no ?>)">
                        <input class="item-count-disp" type="text" value="<?php echo $_COOKIE[$page_data->post_title]?>">
						<input class="item-count-add" type="button" value="+" onclick="CountPlus(<?=$item_disp_no ?>)">
                    </td>
                    <td class="money_disp" id="money_disp<?=$item_disp_no ?>">
                        <?php 
                            $subtotal = str_replace('¥','',$custom_field_data['金額'][0]);
                            $subtotal = str_replace(',','',$subtotal);
                            $subtotal_money = intval($subtotal)*$item_count;
                            $amount_money = $amount_money + $subtotal_money;
                            echo '¥'.$subtotal_money;
                        ?>
                    </td>
                    <td>
                        <input type="button" value="×" class="cart-delete-btn" onclick="ItemDelete('<?php echo $page_data->post_title ?>')">
                    </td>
                </tr>
                <?php 
                        $item_disp_no++;
                    }   //foreach($myposts as $page_data)
                ?>
                <tr><td colspan='5' class="money_disp total_money">ご注文合計金額　　￥<?=$amount_money ?>　</td></tr>
            </tbody>
        </table>
        <!-- tab -->
        <?php   }else{ ?>
            <?php 
                if($cart_item == NULL){
                    echo "<p>カートは空</p>";
                }else{
                    foreach($myposts as $page_data){
    
                        // キーがあるか探す
                        if(!array_key_exists($page_data->post_title,$cart_item)) continue;
                        // カスタムフィールド取得
                        $custom_field_data = get_post_meta(  $page_data->ID );
                        $item_count = $_COOKIE[$page_data->post_title];
                ?>
                    <div class="item-picture">
                        <img src="http://localhost/wordpress/wp-content/themes/twentytwenty/assets/images/<?php echo $page_data->post_title?>1.png" alt="">
                        <div class="item-name">
                            <?= $page_data->post_title?>
                        </div>
                        <input type="button" value="×" class="cart-delete-btn" onclick="ItemDelete('<?php echo $page_data->post_title ?>')">
                    </div>

                    <div class="tanka-area">
                        <div class="left-tag">単価</div>
                        <div class="item-money" id="item-money<?=$item_disp_no ?>"><?php echo $custom_field_data['金額'][0]?></div>
                    </div>
                    
                    <div class="amount-area">
                        <div class="left-tag">数量</div>
                        <div class="item-count-no">
                            <input class="item-count-sub" type="button" value="-" onclick="CountMinus(<?=$item_disp_no ?>)">
                            <input class="item-count-disp" type="text" value="<?php echo $_COOKIE[$page_data->post_title]?>">
                            <input class="item-count-add" type="button" value="+" onclick="CountPlus(<?=$item_disp_no ?>)">
                        </div>
                    </div>

                    <div class="small-area">
                        <div class="left-tag">小計</div>
                        <div class="money_disp" id="money_disp<?=$item_disp_no ?>">
                            <?php 
                                $subtotal = str_replace('¥','',$custom_field_data['金額'][0]);
                                $subtotal = str_replace(',','',$subtotal);
                                $subtotal_money = intval($subtotal)*$item_count;
                                $amount_money = $amount_money + $subtotal_money;
                                echo '¥'.$subtotal_money;
                            ?>
                        </div>
                    </div>
                    
                <?php 
                        $item_disp_no++;
                    }
                }
                ?>
                    <div class="money-area">
                        <div class="total_money">ご注文合計金額　　</div>
                        <div class="money-font total_money_sp">¥<?=$amount_money ?>　</div>
                    </div>
                <?php
                }
                
            ?>

        <?php } ?>
        <div class="btn-area">
            <?php if(!$size_flg): ?>
                <a href="<?php echo home_url()?>">
                    <input type="button" value="買い物を続ける" class="keep_shopping_btn">
                </a>
                <a href="<?php echo home_url('settlement')?>">
                    <input type="button" value="レジに進む" class="go_regi_btn">
                </a>
            <?php else:?>
                <a href="<?php echo home_url('settlement')?>">
                    <input type="button" value="レジに進む" class="go_regi_btn">
                </a>
                <a href="<?php echo home_url()?>">
                    <input type="button" value="買い物を続ける" class="keep_shopping_btn">
                </a>
            <?php endif;?>
        </div>
    </div>
    <script>

        // mode判定
        var php_size_flg = <?php echo var_export( $size_flg, true ); ?>;

        // 金額表示修正
        var elements = document.getElementsByClassName("money_disp");
        
        // 合計sp
        document.getElementsByClassName("total_money_sp")[0].innerText = document.getElementsByClassName("total_money_sp")[0].innerText.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");;
        for(var i = 0;i<elements.length;i++){
            elements[i].textContent = elements[i].textContent.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        }

        function ItemDelete(data){
            document.cookie = data + '=;max-age=0;path=/wordpress/';
            window.location.reload();
        }

        		// マイナス処理
		function CountMinus(no){
			var now = document.getElementsByClassName('item-count-disp')[no].value;

			if(now > 1){
				document.getElementsByClassName('item-count-disp')[no].value = Number(now) - 1; 


                //小計再計算
                var js_item_money = document.getElementById("item-money" + no).innerText.toString().replace(/[^0-9]/g, '');
                var now_value = js_item_money * document.getElementsByClassName('item-count-disp')[no].value;
                document.getElementById("money_disp" + no).innerText = "¥" + now_value.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

                // 合計再計算
                if(php_size_flg == false){
                    var js_amount_money = document.getElementsByClassName("total_money")[0].innerText.toString().replace(/[^0-9]/g, '');
                    var recalculation_money = Number(js_amount_money) - Number(js_item_money);
                    document.getElementsByClassName("total_money")[0].innerText =  "ご注文合計金額　　¥" + recalculation_money.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + "　";
                }else if(php_size_flg == true){
                    var js_amount_money = document.getElementsByClassName("total_money_sp")[0].innerText.toString().replace(/[^0-9]/g, '');
                    var recalculation_money = Number(js_amount_money) - Number(js_item_money);
                    document.getElementsByClassName("total_money_sp")[0].innerText =  "¥" + recalculation_money.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + "　";
                }
            }
            //キャッシュに個数保存
		}

		// プラス処理
		function CountPlus(no){
			var now = document.getElementsByClassName('item-count-disp')[no].value;
			document.getElementsByClassName('item-count-disp')[no].value = Number(now) + 1;

            //小計再計算
            var js_item_money = document.getElementById("item-money" + no).innerText.toString().replace(/[^0-9]/g, '');
            var now_value = js_item_money * document.getElementsByClassName('item-count-disp')[no].value;
            document.getElementById("money_disp" + no).innerText = "¥" + now_value.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

            // 合計再計算
            if(php_size_flg == false){
                var js_amount_money = document.getElementsByClassName("total_money")[0].innerText.toString().replace(/[^0-9]/g, '');
                var recalculation_money = Number(js_amount_money) + Number(js_item_money);
                document.getElementsByClassName("total_money")[0].innerText =  "ご注文合計金額　　¥" + recalculation_money.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + "　";
            }else if(php_size_flg == true){
                var js_amount_money = document.getElementsByClassName("total_money_sp")[0].innerText.toString().replace(/[^0-9]/g, '');
                var recalculation_money = Number(js_amount_money) + Number(js_item_money);
                document.getElementsByClassName("total_money_sp")[0].innerText = "¥" + recalculation_money.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + "　";
            }
        }

    </script>