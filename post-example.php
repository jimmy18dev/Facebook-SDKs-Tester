<?php
session_start();
echo'Facebook Post Example<br>';
echo'Token:'. $_SESSION['fb_access_token'];

require_once __DIR__ . '/facebook-sdk-v5/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => $_SESSION['AppID'], // Replace {app-id} with your app id
  'app_secret' => $_SESSION['AppSecret'],
  'default_graph_version' => 'v2.2',
  ]);

$helper = $fb->getRedirectLoginHelper();

$linkData = [
  'link' => 'http://www.example.com',
  'message' => 'User provided message',
  ];

try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->post('/me/feed', $linkData, $_SESSION['fb_access_token']);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$graphNode = $response->getGraphNode();

echo '<pre>';
print_r($graphNode);
echo '<pre>';

echo 'Posted with id: ' . $graphNode['id'];
?>