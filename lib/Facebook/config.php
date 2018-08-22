<?php
  session_start();
  require_once "lib/Facebook/autoload.php";

  $FB = new \Facebook\Facebook([
    'app_id' => '299039227524192',
    'app_secret' => '7ae34f4efe43a20c7d6efd80646d283b',
    'default_graph_version' => 'v2.10'
  ]);

  $helper = $FB->getRedirectLoginHelper();
