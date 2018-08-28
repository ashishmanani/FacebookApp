<?php
session_start();
if (!isset($_SESSION['fbaccess_token'])) {
    header('Location: fblogin.php');
    exit();
}

if (isset($_REQUEST['submit1'])) {
    session_destroy();
    header("Location:fblogin.php");
}

if (isset($_SESSION['url'])) {
	$data = $_SESSION['url'];
	unset($_SESSION['url']);
        header('Location: '.$data);
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Facebook App</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/mycss.css"/>
    <style>
    .body{
      background-image: url("images/HomeBackground1.jpg");
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
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="#"><img src="images/logo.png" style="width:150px;"/></a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="uploadall.php?val=all">Upload All Albums To Drive</a></li>
            <li><a href="downloadall.php?val=all">Download All Albums</a></li>
            <li><a href="multiple.php">Multiple Selection Option</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color:orange;"><?php echo $_SESSION['userData']['first_name']." ".$_SESSION['userData']['last_name'];?>
              <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li align="center"><a href="#"><img src="<?php echo $_SESSION['userData']['picture']['url'];?>" class="img-circle img-thumbnail img-responsive" width="50%"/></a></li>
                <li><a href="#"><?php echo $_SESSION['userData']['email']?></a></li>
                <li><a href="?submit1=submit"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
      </nav>
    <div class="container">
      <form id="f1">
      <div class="row justify-content-center" style="margin-top:60px;">
        <h1 align="center" style="color:#0000FF;"><b>Your Albums</b></h1><br>
            <?php
            if (isset($_SESSION['userData']['albums'])) {
                $totalalbum = count($_SESSION['userData']['albums']);
                for ($i=0; $i<$totalalbum; $i++) {
		    $albumid = $_SESSION['userData']['albums'][$i]['id'];
                    $count = $_SESSION['userData']['albums'][$i]['count'];?>
                    <div class="slider" id="<?php echo $_SESSION['userData']['albums'][$i]['id'];?>">
                      <div class="wrap" style="background:black;">
<div id="arrow-left<?php echo $_SESSION['userData']['albums'][$i]['id'];?>" class="arrow-left"></div>
                    <?php
                    for ($j=0; $j<$count; $j++) {?>
<div id="s<?php echo $_SESSION['userData']['albums'][$i]['id'];?>" class="slide">
                      <img src="<?php echo $_SESSION['userData'][$albumid][$j];?>"
                      width="50%"/>
			<?php $tmp = $j+1; echo "<br>".$tmp;?>
                    </div>
                        <?php
                    }
                    ?>
                  <div id="arrow-right<?php echo $_SESSION['userData']['albums'][$i]['id'];?>"
                    class="arrow-right"></div>
                  <div id="close" class="close"
                  onclick="closediv(<?php echo $_SESSION['userData']['albums'][$i]['id'];?>);"></div>
                </div>
              </div>
                <div class="col-md-3 thumbnail">
                  <img src="<?php echo $_SESSION['userData'][$albumid][0]; ?>"
                  onclick="fun(<?php echo $_SESSION['userData']['albums'][$i]['id'];?>);" class='img-responsive image'
                  style='height:300px; border-style:solid; border-width:2px;'>
                  <div style="display:none;" class="progress centered"
                  id="p<?php echo $_SESSION['userData']['albums'][$i]['id'];?>">
                    <div class="progress-bar progress-bar-striped active" role="progressbar"
                      aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                      <p style="color: yellow; font-size:15px;">It will take while!! So, You have to Wait. :)</p>
                    </div>
                  </div>
                  <div style="height:60px;">
                  <h3><?php echo $_SESSION['userData']['albums'][$i]['name']?></h3>
				  <?php echo "Total Image ".$count;?>
                  </div>
              <div>
                <a onclick="singleProgress(<?php echo $_SESSION['userData']['albums'][$i]['id'];?>);"
                  href="uploadall.php?val=single&q=<?php echo $_SESSION['userData']['albums'][$i]['id']; ?>"
                  class="btn btn-danger">Upload Album</a>
                <a onclick="singleProgress(<?php echo $_SESSION['userData']['albums'][$i]['id'];?>);"
                  href="downloadall.php?val=single&q=<?php echo $_SESSION['userData']['albums'][$i]['id']; ?>"
                  class="btn btn-danger">Download Album</a>
              </div>
                <input type="checkbox" name="q[]" style="margin-top:10px;" value="<?php echo $_SESSION['userData']['albums'][$i]['id']?>">
                    For Upload/ Download.</input><br>
              </div>
                    <?php
                }
            }
            ?>
      </div>
      <div class="row justify-content-center">
      <div style="display:none;" class="progress" id="progress">
        <div class="progress-bar progress-bar-striped active" role="progressbar"
          aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:100%">
          It will take while.[:)]
        </div>
      </div>
        <div class="col-md-6">
          <input type="hidden" value="multiple" name="val"></input>
          <input type="button" value="Upload Selected Albums" class="btn btn-warning form-control"
          onclick="upload();" style="margin-bottom:10px;"></input>
        </div>
        <div class="col-md-6">
          <input type="button" value="Download Selected Albums" class="btn btn-warning form-control"
          onclick="download();" style="margin-bottom:10px;"></input>
        </div>
      </div>
    </form>
    </div>
    </div>
    <script src="js/myjavascript.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </body>
</html>
