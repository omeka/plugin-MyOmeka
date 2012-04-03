<?php $title = __('Browse Items');
    head(array('title' => $title, 'bodyid' => get_option('my_omeka_page_title'), 'bodyclass'=>'browse'));
?>

<div id="primary">
    <h1><?php echo $title; ?><?php echo __('(%s total)',$total_records); ?></h1>
</div>

<?php foot(); ?>
