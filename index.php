<?php
session_start();

if(!empty($_SESSION['AppID']) && !empty($_SESSION['AppSecret'])){

	require_once __DIR__ . '/facebook-sdk-v5/autoload.php';

	$fb = new Facebook\Facebook([
		'app_id' 				=> $_SESSION['AppID'],
		'app_secret' 			=> $_SESSION['AppSecret'],
		'default_graph_version' => 'v2.12',
	]);

	$helper = $fb->getRedirectLoginHelper();
	$permissions = ['email']; // optional

	try{
		if(isset($_SESSION['fb_access_token'])){
			$accessToken = $_SESSION['fb_access_token'];
		}else{
  			$accessToken = $helper->getAccessToken();
		}
	}catch(Facebook\Exceptions\FacebookResponseException $e) {
 		// When Graph returns an error
 		$error_log = 'Graph returned an error: ' . $e->getMessage();
  		exit;
	}catch(Facebook\Exceptions\FacebookSDKException $e) {
 		// When validation fails or other local issues
		$error_log = 'Facebook SDK returned an error: ' . $e->getMessage();
  		exit;
  	}

	if(isset($accessToken)) {
		if(isset($_SESSION['fb_access_token'])) {
			$fb->setDefaultAccessToken($_SESSION['fb_access_token']);
		}else{
			// getting short-lived access token
			$_SESSION['fb_access_token'] = (string) $accessToken;

	  		// OAuth 2.0 client handler
			$oAuth2Client = $fb->getOAuth2Client();
		
			// Exchanges a short-lived access token for a long-lived one
			$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['fb_access_token']);
			// $_SESSION['fb_access_token'] = (string) $longLivedAccessToken;
			$_SESSION['facebook_longlived_token'] = (string) $longLivedAccessToken;

			// setting default access token to be used in script
			$fb->setDefaultAccessToken($_SESSION['fb_access_token']);
		}

		// redirect the user back to the same page if it has "code" GET variable
		if(isset($_GET['code'])){
			// header('Location: index.php?msg=error');
			$error_log = "isset(code) is Fail!";
		}

		// getting basic info about user
		try {
			$profile_request = $fb->get('/me?fields=id,email,name,first_name,last_name');
			$profile = $profile_request->getGraphNode()->asArray();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			$error_log = 'Graph returned an error: ' . $e->getMessage();
			session_destroy();
			// redirecting user back to app login page
			// header("Location: index.php");
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			$error_log = 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
	
		// printing $profile array on the screen which holds the basic info about user'
		echo'<pre>';
		print_r($profile_request);
		echo'</pre>';
  		// Now you can redirect to another page and use the access token from $_SESSION['fb_access_token']
	}else{
		// replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
		$loginUrl = $helper->getLoginUrl('http://'.$_SERVER['SERVER_NAME'].'/fb-callback.php',$permissions);
	}
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
			<p class="caption">Access Token : <?php echo $_SESSION['fb_access_token'];?></p>
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