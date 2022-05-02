    <div class="contact-title">CONTACT</div>
    <div class="page-content-line"></div>
    <div class="contact-content">
    TextTextTextTextTextTextTextTextTextTextTextTextTextTextTextTextTextTextTextTextTextTextTextTextTextText
    TextTextText
    </div>

    <div class="contact-form">
        <?php echo do_shortcode('[contact-form-7 id="62" title="問合せ"]'); ?>
    </div>

    <script>
/* サンクスページ移動
------------------------------------------------------------------*/
    var submit_exe = document.getElementsByClassName("wpcf7-submit")[0];
    submit_exe.addEventListener('click', function(){
        var wait_time = 1000;
        var wait_count = 0;

        while(wait_count <= wait_time){
            var output = document.getElementsByClassName("wpcf7-response-output")[0];
            if(output.textContent == "ありがとうございます。メッセージは送信されました。"){
                location = '<?php echo home_url('contact-thanks'); ?>';
                break;
            }
            console.log(output.textContent);
            sleep(10);
            wait_count++;
        }

    });
    </script>