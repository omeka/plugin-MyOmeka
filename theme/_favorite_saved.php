<img src="<?php echo img('favorite-on.gif'); ?>" />
<div id="saved-annotation">
<h3>Your annotation:</h3>

    <div class="field"><?php echo htmlentities($_GET['annotation']); ?></div>

<h3>Your tags</h3>
    <div class="field">
        <?php echo htmlentities($_GET['tags']); ?>
    </div>

    <a href="#" id="edit-annotation">Edit your annotation</a>

</div>