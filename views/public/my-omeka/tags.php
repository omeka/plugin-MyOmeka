<?php
    $pageTitle = html_escape(get_option('my_omeka_page_title') . ': Dashboard');
    head(array('title'=>$pageTitle));
?>


<?php foot(); ?>
