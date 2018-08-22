<?php
require_once "lib/Facebook/config.php";

try {
    $accessToken = $helper->getAccessToken();
} catch (\Facebook\Exceptions\FacebookResponseException $e) {
    echo "Response Exeption". $e->getMessage();
    exit();
} catch (\Facebook\Exceptions\FacebookSDKException $e) {
    echo "SDK Exeption". $e->getMessage();
    exit();
}

if (!$accessToken) {
    header("Location:index.php");
    exit();
}

$oAuth2Client = $FB->getoAuth2Client();
if (!$accessToken->isLongLived()) {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
}
$response = $FB->get("/me?fields=id, first_name, last_name, email, picture.type(large),albums{id,name,photos{images}}", $accessToken);
$userData = $response->getGraphNode()->asArray();
$_SESSION['userData'] = $userData;
$_SESSION['fbaccess_token'] = (string) $accessToken;
// echo "<pre>";
// var_dump($response);
header('Location: index.php');
exit();
