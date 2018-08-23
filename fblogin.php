<?php
require_once "lib/Facebook/config.php";

if (isset($_SESSION['fbaccess_token'])) {
    header('Location: index.php');
    exit();
}

$redirectURL = "http://localhost/FacebookApp/fbcallback.php";
$permissions = ['email','public_profile','user_photos'];
$loginURL = $helper->getLoginUrl($redirectURL, $permissions);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log In</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .body{
          background-image: url("images/HomeBackground.jpg");
          width: 100%;
          height: auto;
          background-position: center;
          background-attachment: fixed;
          background-repeat: no-repeat;
          background-size: cover;
        }
    </style>
</head>
<body class="body">
    <div class="container" style="margin-top: 100px">
        <div class="row justify-content-center">
            <div class="col-md-6 col-md-offset-3" align="center">
                <img src="images/logo.png" width="100%"/>
                <input type="button" onclick="window.location = '<?php echo $loginURL ?>';"
                value="Log In With Facebook" class="btn btn-warning" style="color:black;width:250px;height:40px;font-weight:bold;">
            </div>
        </div>
    </div>
</body>
</html>
