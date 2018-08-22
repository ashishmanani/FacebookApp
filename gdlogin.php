<?php
require_once "lib/Google/config.php";

if (isset($_SESSION['gaccess_token'])) {
    header('Location: index.php');
    exit();
} else {
    $loginURL = $Client->createAuthUrl();
    header('Location: '.$loginURL);
}
