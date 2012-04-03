<?php
  $title = __('Browse By tags');
  head(array('title' => $title, 'bodyid' => get_option('my_omeka_page_title'),'bodyclass'=> 'tags'));
  
?>
<div id="primary">
    <h1><?php echo $title; ?></h1>
    <ul class="navigation exhibit-tags" id="secondary-nav">
    <?php echo nav(array(
        __('Browse All') => uri(get_option('my_omeka_page_path')),
        __('Browse by Tag') => uri(get_option('my_omeka_page_path'))
    ));?>  
    </ul>
    
    <?php echo tag_cloud($tags,uri(get_option('my_omeka_page_path').'browse')); ?>
</div>

<?php foot(); ?>
