<?php
require_once 'lib/Google/config.php';

if (isset($_SESSION['gdaccess_token'])) {
    $Client->setAccessToken($_SESSION['gdaccess_token']);
} elseif (isset($_GET['code'])) {
    $token = $Client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['gdaccess_token'] = $token;
} else {
    header('Location: login.php');
    exit();
}
header('Location: index.php');
