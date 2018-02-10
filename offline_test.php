<h3>Retrieve user profile (Offlile)</h3>
<?php
session_start();
require_once __DIR__ . '/facebook-sdk-v5/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => $_SESSION['AppID'], // Replace {app-id} with your app id
  'app_secret' => $_SESSION['AppSecret'],
  'default_graph_version' => 'v2.2',
]);

try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('/me?fields=id,name','CAAWhZClAiwiYBAEzv0URaqwkYhgZB1MsHgo7TchInGhCUMnCQ8MXt90zk3NZCcf1fkCZAxZCZCZA42fSk7aa6ulwbAEGMsr8H9ZA5XC0iuDmQIJdht3BVgrlmLc8Lh2g4QhknLvSuU1ZCuwXh8RRQcEVJH2XUUHt5Es3sJthwuSZAI2idcv5LoZAuaGi7UBQPYg2ZCoZD');
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$user = $response->getGraphUser();
echo'<pre>';
print_r($user);
echo'</pre>';
echo '<br>Name: ' . $user['name'];
?>