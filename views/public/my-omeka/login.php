<?php
    $pageTitle = html_escape(get_option('my_omeka_page_title') . ': Dashboard');
    head(array('title'=>$pageTitle));
?>

<div id="MyOmeka">

<form  actio="<?php echo get_option('my_omeka_page_path').'login/'; ?>" method="post" accepted-charset="utf-8">
    <!--fieldset-->
        <lo>
            <li><label for="username">Username</label></li>
            <li><input type="text" name="username" class="textinput" id="username" /></li>
            
            <li><label for="password">Password</label></li>
            <li><input type="password" name="password" class="textinput" id="password" /></li>
        </lo>
        <input type="submit" class="login" value="Login" />
    <!--/fieldset-->
</form>
    <?php include 'register.php'; ?>

</div>

<?php foot(); ?>
