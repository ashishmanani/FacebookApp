<?php
/*
  Name:- Ashish P. Manani
  Last Edit:- 20-08-2018
  Purpose:- It can used to get data of perticular user of facebook.
*/
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
//get user imformation.
$response = $FB->get("/me?fields=id, first_name, last_name, email, picture.type(large),albums{id,name,count}", $accessToken);
$userData = $response->getGraphNode()->asArray();
$_SESSION['userData'] = $userData;
$_SESSION['fbaccess_token'] = (string) $accessToken;
$userData = null;
$imgarr;
$totalalbum = count($_SESSION['userData']['albums']);
//Get user Album, images And Iterate all images and store into session. 
for ($i=0; $i<$totalalbum; $i++) {
	$albumid = $_SESSION['userData']['albums'][$i]['id'];
	$photo_count = $_SESSION['userData']['albums'][$i]['count'];
	
	$response = $FB->get("/$albumid/photos?fields=images&limit=100", $accessToken)->getgraphEdge();
	$userData = $response->asArray();
	if($photo_count>100)
	{
		$index = 0;
		$tmp = 99;
		for($j=0;$j<$photo_count;$j++)
		{
			$imgarr[$j] = $userData[$index]['images'][2]['source'];
			$index ++;
			if($j == $tmp)
			{
				$response = $FB->next($response);
				$userData = $response->asArray();
				$index = 0;
				$tmp = $tmp + 100;
			}
		}	
	}else{
		for($j=0;$j<$photo_count;$j++)
		{
			$imgarr[$j] = $userData[$j]['images'][2]['source'];
		}
	}
	$_SESSION['userData'][$albumid]=$imgarr;
}
header('Location: index.php');
exit();
