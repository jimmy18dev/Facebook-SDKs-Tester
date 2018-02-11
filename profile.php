<?php
session_start();
require_once __DIR__ . '/facebook-sdk-v5/autoload.php'; // change path as needed

$fb = new \Facebook\Facebook([
  'app_id'                	=> $_SESSION['AppID'],
  'app_secret'            	=> $_SESSION['AppSecret'],
  'default_graph_version' 	=> 'v2.12',
  'default_access_token' 	=> $_SESSION['fb_access_token'], // optional
]);

try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('/me?fields=id,email,name,first_name,last_name');
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$user = $response->getGraphUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Profile</title>
</head>
<body>
<a href="index.php">Return to Home</a>
<p>Access Token</p>
<textarea><?php echo $_SESSION['fb_access_token'];?></textarea>

<img src="https://graph.facebook.com/<?php echo $user['id'];?>/picture?type=square">
<p><strong>Facebook ID</strong> <?php echo $user['id'];?></p>
<p><strong>Email</strong> <?php echo $user['email'];?></p>
<p><strong>Name</strong> <?php echo $user['name'];?></p>
<p><strong>First Name</strong> <?php echo $user['first_name'];?></p>
<p><strong>Last Name</strong> <?php echo $user['last_name'];?></p>

<a href="clear.php">Clear Config</a>
</body>
</html>