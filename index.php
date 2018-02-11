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

<?php include'favicon.php';?>

<title>Facebook SDKs Tester.</title>

<!-- CSS -->
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/style.css"/>

</head>

<body>
<div class="container">
	<h1><a href="index.php">Facebook SDKs Tester.</a></h1>
	<h2>with Facebook PHP SDK v5</h2>

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
				<button type="submit" class="input-submit" >SETUP</button>
			</div>
		</form>
	</div>
	<?php }else{?>
	<div class="display">
		<?php if(isset($accessToken)){?>
		<div class="profile">
			<div class="thumbnail">
				<img src="https://graph.facebook.com/<?php echo $profile['id'];?>/picture?type=square" alt="">
			</div>
			<div class="detail">
				<p><?php echo $profile['name'];?></p>
				<p class="id"><?php echo $profile['email'];?></p>
			</div>
		</div>
		<?php }else{?>
		<a href="<?php echo $loginUrl;?>">
		<div class="login-btn">Login to Facebook</div>
		</a>
		<?php }?>
	</div>
	
	<div class="message">
		<div class="message-items">
			<p>Access Token</p>
			<textarea><?php echo $_SESSION['fb_access_token'];?></textarea>
		</div>
		<div class="message-items">
			<p class="caption">LongLived Access Token : <?php echo $_SESSION['facebook_longlived_token'];?></p>
		</div>
		<div class="message-items">
			<p class="caption">Error : <?php echo (empty($error_log)?'null':$error_log);?></p>
		</div>
		<div class="message-items">
			<p class="caption">App ID : <?php echo $_SESSION['AppID'];?></p>
		</div>
		<div class="message-items">
			<p class="caption">App Secret : <?php echo (empty($_SESSION['AppSecret'])?'null':'xxxxxxxxxxxxxxxxxxxxxxxxxx');?></p>
		</div>

		<a href="clear.php">Clear Config</a>
	</div>
	<?php }?>
</div>
</body>
</html>