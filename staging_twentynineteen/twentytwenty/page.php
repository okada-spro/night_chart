<?php

    $page_obj = get_page_by_path('about');
    $page = get_post( $page_obj );

    get_header();
?>
<?php 
    // include(STYLESHEETPATH . "/header.php");
?>

<div class="content-main">
    <?php include(STYLESHEETPATH . "/$page->post_title.php"); ?>
</div>

<?php 
    get_footer();
?>