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

    <div class="settlement-area">
        <div class="order-flow-content">
            <div class="mode-input">入力</div>
            <div class="mode-line"></div>
            <div class="mode-conf">確認</div>
            <div class="mode-line"></div>
            <div class="mode-comp">完了</div>
        </div>
        
        <div class="settlement-title">お支払い情報の入力（レジ）</div>
        

        <div class="cart-main">
            <?php 
                if(!$size_flg){
                    $cart_left_font_height = count($cart_item) * 70 + 62 + 200;
                }else{
                    $cart_left_font_height = 70;
                }
                
                ?>
            <div class="cart-left-font" style="height:<?=$cart_left_font_height ?>px">注文内容</div>
            <?php if(!$size_flg):?>
            <table>
                <tbody>
                    <tr>
                        <th width="550px">商品名</th>
                        <th width="150px">単価</th>
                        <th width="150px">数量</th>
                        <th width="150px">小計</th>
                    </tr>
                    <tr>
                    <?php 
                    $amount_money = 0;
                    $item_disp_no = 0;
                    if($cart_item != NULL){
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
                            <?php echo $_COOKIE[$page_data->post_title]?>
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
                    </tr>
                    <?php 
                            $item_disp_no++;
                        }
                    }
                    ?>
                    <tr><td colspan='5' class="money_disp settlement-total_money">ご注文合計金額　　￥<?=$amount_money ?>　</td></tr>
                </tbody>
            </table>
            <!-- tab -->
            <?php else:?>
                <?php 
                    if($cart_item != NULL){
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
                        </div>

                        <div class="tanka-area">
                            <div class="left-tag">単価</div>
                            <div class="item-money" id="item-money<?=$item_disp_no ?>"><?php echo $custom_field_data['金額'][0]?></div>
                        </div>
                        
                        <div class="amount-area">
                            <div class="left-tag">数量</div>
                            <div class="item-count-no"><?php echo $_COOKIE[$page_data->post_title]?></div>
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
                    ?>
                        <div class="money-area">
                            <div class="money_disp settlement-total_money">ご注文合計金額　　</div>
                            <div class="money-font settlement-total_money">￥<?=$amount_money ?>　</div>
                        </div>
                    <?php
                    }else{
                        echo"カートは空";
                    }
                    
                ?>
            <?php endif;?>

            <div class="have-account">
                <!-- ペイパル -->
                <div class="simple_settlement">
                    <p>お持ちのアカウントでカンタン決済</p>
                    <div id="smart-button-container">
                        <div style="text-align: center;">
                            <div id="paypal-button-container"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="no-account">
                <div class="comment-area">
                <?php if(!$size_flg):?>
                        <p>
                        ―――――――――――――――――――――――――　アカウントをお持ちでない方はこちらから　―――――――――――――――――――――――――
                        </p>
                <?php else:?>
                    <p>――――――　アカウントをお持ちでない方はこちらから　――――――</p>
                <?php endif;?>
                </div>

                
                <!-- コンタクトフォーム呼び出し -->
                <?php echo do_shortcode('[contact-form-7 id="73" title="支払い方法"]'); ?>

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id=sb&enable-funding=venmo&currency=JPY" data-sdk-integration-source="button-factory"></script>
<script>
var 	Cnt 		= jQuery('#ContactInput'), //入力部分
        Cfm 		= jQuery('#ContactConfirm'), //確認部分
        BtCfm 		= jQuery('#BtConfirm'), //確認ボタン
        BtSnd 		= jQuery('#BtSend input'),//送信ボタン
        BtCsl 		= jQuery('#BtCancel'),//キャンセルボタン
        CntItem 	= jQuery('#ContactInput .CntItem1'),//入力の包容タグ　メール
        TargetForm = "",
        CntVal = "",
        CfmItem 	= jQuery('#ContactConfirm .CfmItem div');//確認の包容タグ


/* 確認ボタン
------------------------------------------------------------------*/
    BtCfm.click(function(){//確認ボタンを押したとき、
        CfmItem.text(' '); //確認の中身をリセット
        $Error = ''; //エラーのリセット
        CntItem.ConfirmInput(); //.CntItemを対象として確認の関数を実行
    });

/* キャンセルボタン
------------------------------------------------------------------*/
    BtCsl.click(function(){ //キャンセルボタンを押したとき、
        Cfm.fadeOut(function(){Cnt.fadeIn();}); //入力部分と確認部分を切り替え
        $('.contact-title').text("お申し込みフォーム");
    });

/* 確認画面へ反映させる関数
------------------------------------------------------------------*/
    jQuery.fn.ConfirmInput = function(){
        var input_ok = true;
        $('.CntItem1').each(function(i, elem) { //.CntItemを一つずつ実行
            
            /*入力されたものを変数「CntVal」に代入
            ------------------------------------------------------------*/

            // メールアドレスの欄の場合(.CntMailの場合)、形式をチェック
            //------------------------------------------------------------*/
            if(elem.classList.contains('CntMail1')){
                CntVal = elem.value;
                if(elem.classList.contains('CntMail1') && !CntVal.match(/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/)){
                    alert('メールアドレスの形式が正しくありません。');
                    $Error = 'error'; //$Errorに中身を入れる
                    input_ok = false;
                    return false;
                }
            }
            //チェックボックス
            else if(elem.classList.contains('CntCheck1')){
                if(elem.value == 'on'){
                    CntVal = "銀行振込";
                }
            }
            //<input type="text">の場合
            else if(elem.classList.contains('CntInput1')){
                CntVal = elem.value;
            }
            // 住所
            else if(elem.classList.contains('Prefectures')){
                CntVal = elem.value;
            }
            // 日時
            else if(elem.classList.contains('CntDate')){
                TargetForm = $(this).find('option:selected');
                CntVal = TargetForm.val();
            }

            CntNum = $(this).attr('id').replace(/Cnt/g,''); //IDを取得後「Cnt」をカット
            $('#Cfm'+CntNum).text('　' + CntVal);
        
        });

        if(input_ok){
            $('.input-area').css('display','none')
            $('.output-area').css('display','block')
            $('.mode-input').css('background-color','#CECECE')
            $('.mode-conf').css('background-color','#9A0811')
            if($Error != 'error') { //エラーがない場合
                Cnt.fadeOut(function(){Cfm.fadeIn();}); //入力部分と確認部分を切り替え
    
                $('.contact-title').text("入力内容確認");
                $('.contact-massage').html("以下の内容で送信してよろしいですか？<br>送信後、決済画面に移動します。");
            }

        }
    };


/* ペイパルページ移動
------------------------------------------------------------------*/
    // ペイパルスクリプト
    function initPayPalButton() {
        paypal.Buttons({
            style: {
            shape: 'rect',
            color: 'blue',
            layout: 'horizontal',
            label: 'paypal',
            
            },

            createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{"amount":{"currency_code":"JPY","value":<?php echo $amount_money?>}}]
            });
            },

            onApprove: function(data, actions) {
            return actions.order.capture().then(function(orderData) {
                
                // Full available details
                console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));

                // Show a success message within this page, e.g.
                const element = document.getElementById('paypal-button-container');
                element.innerHTML = '';
                element.innerHTML = '<h3>Thank you for your payment!</h3>';
                // ペイパル決済後処理
                location = '<?php echo home_url('order-thanks'); ?>';
                // Or go to another URL:  actions.redirect('thank_you.html');
                
            });
            },

            onError: function(err) {
            console.log(err);
            }
        }).render('#paypal-button-container');
    }
    initPayPalButton();
        
</script>
        </div>

    </div>