<?php
session_start();

if(!empty($_SESSION['AppID']) && !empty($_SESSION['AppSecret'])){

	require_once __DIR__ . '/facebook-sdk-v5/autoload.php';

	$fb = new Facebook\Facebook([
		'app_id' 				=> $_SESSION['AppID'],
		'app_secret' 			=> $_SESSION['AppSecret'],
		'default_graph_version' => 'v2.12',
	]);

	$helper 		= $fb->getRedirectLoginHelper();
	$permissions 	= ['email']; // optional
	$loginUrl 		= $helper->getLoginUrl('http://'.$_SERVER['SERVER_NAME'].'/fb-callback.php',$permissions);
}

?>
<!DOCTYPE html>
<html lang="th" itemscope itemtype="http://schema.org/Blog" prefix="og: http://ogp.me/ns#">
<head>

<!--[if lt IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<![endif]-->

<!-- Meta Tag -->
<meta charset="utf-8">

<!-- Viewport (Responsive) -->
<meta name="viewport" content="width=device-width">
<meta name="viewport" content="user-scalable=no">
<meta name="viewport" content="initial-scale=1,maximum-scale=1">	

<title>Facebook Login | PHP SDK v5 and API Version 2.12</title>

<!-- CSS -->
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/style.css"/>

</head>

<body>
<div class="container">
	<header class="head">
		<h1>Facebook Login</h1>
		<p>PHP SDK v5 and API Version 2.12</p>
	</header>

	<?php if(empty($_SESSION['AppID']) || empty($_SESSION['AppSecret'])){?>
	<div class="form">
		<form action="setup.php" method="post">
			<div class="form-items">
				<input type="text" class="input-text" placeholder="App ID" name="AppID" autofocus>
			</div>
			<div class="form-items">
				<input type="text" class="input-text" placeholder="App Secret" name="AppSecret">
			</div>
			<div class="form-items">
				<button type="submit" class="btn-submit">SAVE</button>
			</div>
		</form>
	</div>
	<?php }else{?>
	<div class="display">
		<a href="<?php echo $loginUrl;?>" class="btn-login">Login with Facebook</a>

		<label>Access Token</label>
		<textarea><?php echo $_SESSION['fb_access_token'];?></textarea>

		<label>LongLived Access Token:</label>
		<textarea><?php echo $_SESSION['facebook_longlived_token'];?></textarea>

		<p><strong>App ID</strong> <?php echo $_SESSION['AppID'];?></p>
		<p><strong>App Secret</strong> <?php echo (empty($_SESSION['AppSecret'])?'null':'xxxxxxxxxxxxxxxxxxxxxxxxxx');?></p>
		<p>Error : <?php echo (empty($error_log)?'null':$error_log);?></p>
		<a class="btn" href="clear.php">Clear Config</a>
	</div>
	<?php }?>
</div>
</body>
</html>