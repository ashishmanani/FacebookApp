<?php
  session_start();
  require_once "vendor/autoload.php";

  $Client = new Google_Client();
  $Client->setClientId("316099422547-ujdjnesg37n1h891ip1p7b0ci8o360ap.apps.googleusercontent.com");
  $Client->setClientSecret("bnkrkeoA577yGK70JtWbRQ3C");

  $google_redirect_url = "http://localhost/FacebookApp/gdcallback.php";
  $Client = new Google_Client();
  $Client->setAuthConfigFile('lib/Google/credentials.json');
  $Client->setRedirectUri($google_redirect_url);
  $Client->addScope(Google_Service_Drive::DRIVE);
