

<div class="contact-main">
    <div class="contact-area">
        <div class="contact-title">お申し込みフォーム</div>
        <div class="contact-massage">ご希望の学校および必要情報をご入力ください。</div>
        <div class="contact-main-area">
            
            <!-- test -->
            <?php echo do_shortcode('[contact-form-7 id="73" title="支払い方法"]'); ?>

            <div class="post_check">
                <?php echo($_POST['your-name']);?>
            </div>
            <?php //フォームボタンにペイパルを紐づける?>
            <!-- <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top"> -->
            <!-- <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="226D5MNM2KZ8J">
                <img alt="" border="0" src="https://www.sandbox.paypal.com/ja_JP/i/scr/pixel.gif" width="1" height="1">
                <input type="submit" value="送信" class="paypal-move"/>
            </form>  -->


            <!-- sandbox -->
            <!-- <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top"> -->
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="VYNZDUE2S64S8">
                <!-- <input type="image" src="https://www.paypalobjects.com/ja_JP/JP/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - オンラインでより安全・簡単にお支払い"> -->
                <img alt="" border="0" src="https://www.paypalobjects.com/ja_JP/i/scr/pixel.gif" width="1" height="1">
                <input type="submit" value="送信" class="paypal-move"/>
            </form>


            <?php /*
            <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="226D5MNM2KZ8J">
                <!-- <input type="image" src="https://www.sandbox.paypal.com/ja_JP/JP/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - オンラインでより安全・簡単にお支払い"> -->
                <img alt="" border="0" src="https://www.sandbox.paypal.com/ja_JP/i/scr/pixel.gif" width="1" height="1">
                <input type="submit" value="送信" class="paypal-move"/>
            </form>

            */?>

        </div>
    </div>
</div>

<script>
var 	Cnt 		= jQuery('#ContactInput'), //入力部分
        Cfm 		= jQuery('#ContactConfirm'), //確認部分
        BtCfm 		= jQuery('#BtConfirm'), //確認ボタン
        BtSnd 		= jQuery('#BtSend input'),//送信ボタン
        BtCsl 		= jQuery('#BtCancel'),//キャンセルボタン
        CntItem 	= jQuery('#ContactInput .CntItem1'),//入力の包容タグ　メール
        CfmItem 	= jQuery('#ContactConfirm .CfmItem div');//確認の包容タグ

/* 確認ボタン
------------------------------------------------------------------*/
    BtCfm.click(function(){//確認ボタンを押したとき、
        CfmItem.text(' '); //確認の中身をリセット
        $Error = ''; //エラーのリセット
        CntItem.ConfirmInput(); //.CntItemを対象として確認の関数を実行
        if($Error != 'error') { //エラーがない場合
            Cnt.fadeOut(function(){Cfm.fadeIn();}); //入力部分と確認部分を切り替え

            $('.contact-title').text("入力内容確認");
            $('.contact-massage').html("以下の内容で送信してよろしいですか？<br>送信後、決済画面に移動します。");
        }
    });

/* キャンセルボタン
------------------------------------------------------------------*/
    BtCsl.click(function(){ //キャンセルボタンを押したとき、
        Cfm.fadeOut(function(){Cnt.fadeIn();}); //入力部分と確認部分を切り替え
        $('.contact-title').text("お申し込みフォーム");
        $('.contact-massage').text("ご希望の学校および必要情報をご入力ください。");
    });

/* 確認画面へ反映させる関数
------------------------------------------------------------------*/
    jQuery.fn.ConfirmInput = function(config){
        return this.each(function(i, elem) { //.CntItemを一つずつ実行
            /*
            入力されたものを変数「CntVal」に代入
            ------------------------------------------------------------*/

            // span 学校一覧
            if($(this).hasClass('CntSelectSc')){
                TargetForm = $(this).find('option:selected');
                CntVal = TargetForm.val();
            }

            else if($(this).hasClass('hope-school')){
                TargetForm = $(this).find('option:selected');
                CntVal = TargetForm.val();
            }

            // span 日時
            else if($(this).hasClass('CntDate')){
                TargetForm = $(this).find('option:selected');
                CntVal = TargetForm.val();
            }

            //<input type="text">の場合
            else if($(this).hasClass('CntInput1')){
                TargetForm = $(this).find('input');
                CntVal = TargetForm.val();

            //<input type="checkbox">の場合
            }else if($(this).hasClass('CntCheck1')){
                TargetForm = $(this).find('input:checked');
                CntCheck = '';
                for (var i=0; i<TargetForm.length; i++) {
                    if(i != 0){ CntCheck += '、'; }
                    CntCheck += '「'+TargetForm.eq(i).val()+'」';
                }
                CntVal = CntCheck;

            //<textarea>の場合
            }else if($(this).hasClass('CntText1')){
                TargetForm = $(this).find('textarea');
                CntVal = TargetForm.val();
            }

            // debug　入力した項目確認
            // console.log(CntVal);
            /*
            入力部分のIDから関連する確認部分のIDへ「CntVal」を挿入
            ------------------------------------------------------------*/
            CntNum = $(this).attr('id').replace(/Cnt/g,''); //IDを取得後「Cnt」をカット
            $('#Cfm'+CntNum).text('　' + CntVal);
            /*
            メールアドレスの欄の場合(.CntMailの場合)、形式をチェック
            ------------------------------------------------------------*/
            if($(this).hasClass('CntMail1') && !CntVal.match(/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/)){
                alert('メールアドレスの形式が正しくありません。');
                $Error = 'error'; //$Errorに中身を入れる
                return false;
            }
            /*
            必須(.Require)に中身が入っていない場合
            ------------------------------------------------------------*/
            else if($(this).hasClass('Require1') && CntVal==''){
                alert('必須項目が入力されていません。');
                $Error = 'error';
                return false;
            }
        });
    };
/* ペイパルページ移動
------------------------------------------------------------------*/
    //contact form 7 でメールの送信を確認したらペイパルへ移動
    $(document).ready(function(){

        if($('.post_check').text() != '\n                            ' && $('.wpcf7-response-output').text() != '入力内容に問題があります。確認して再度お試しください。'){
            $('.paypal-move').click();
        }
    });

</script>