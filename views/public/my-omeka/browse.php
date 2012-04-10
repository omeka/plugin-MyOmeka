<?php $title = __(get_option('my_omeka_page_title').' Items');
    head(array('title' => $title, 'bodyid' => get_option('my_omeka_page_title'), 'bodyclass'=>'browse'));
?>

<div id="primary">
    <h1><?php echo $title; ?><?php echo __('(%s total)',$total_records); ?></h1>
</div>
<div id="primary">
    <ul class="navigation exhibit-tags" id="secondary-nav">
    <?php echo nav(array(
        __('Browse All') => uri(get_option('my_omeka_page_path').'browse'),
        __('Browse by Tag') => uri(get_option('my_omeka_page_path').'tags')
    ));?>  
    </ul>
    
    <?php echo tag_cloud($tags,uri(get_option('my_omeka_page_path').'browse')); ?>
</div>

<?php foot(); ?>
