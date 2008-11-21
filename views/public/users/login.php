<?php head(); ?>

<div id="primary">
<div id="myomeka-login-box">
<h2>Login</h2>

<form id="myomeka-login-form" method="post" accept-charset="utf-8">
	<fieldset>
	<ol>
	<li><label for="username">Username</label> </li>
	<li><input type="text" name="username" class="textinput" id="username" /></li>

	<li><label for="password">Password</label> </li>
	<li><input type="password" name="password" class="textinput" id="password" /></li>
	</ol>
	<input type="submit" class="login" value="Login" />
	</fieldset>
</form>

<p id="myomeka-forgotpassword"><a href="<?php echo uri('users/forgot-password'); ?>">Lost your password?</a></p>

<?php 
// Possible spot for a hook that other plugins could use to append data 
// (like a link to register for an account with MyOmeka). ?>

</div>
</div>
<?php foot(); ?>