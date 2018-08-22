<?php
session_start();
if (isset($_REQUEST['submit1'])) {
    session_destroy();
    header("Location:fblogin.php");
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Multiple Selection Option</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
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
            <li><a href="index.php">Home</a></li>
            <li><a href="uploadall.php?val=all">Upload All Albums To Drive</a></li>
            <li><a href="downloadall.php?val=all">Download All Albums</a></li>
            <li class="active"><a href="multiple.php">Multiple Selection Option</a></li>
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
      <body class="body">
          <div class="container" style="margin-top: 100px">
              <div class="row justify-content-center">
                  <div class="col-md-6 col-md-offset-3" align="center">
                  <form id="f1">
                    <table><tr><td><?php
                            if(isset($_REQUEST['err']))
                            {
                             	echo "<font color='red'>* You have to select Atleast one checkbox</font>";
                            }
                        ?></td></tr>
                    <?php
                    $totalalbum = count($_SESSION['userData']['albums']);
                    for ($i=0; $i<$totalalbum; $i++) { ?>
                      <tr><th style="font-size:20px; color:black;">
                        <input type="checkbox" name="q[]" value="<?php echo $_SESSION['userData']['albums'][$i]['id']?>" style="width:20px; height:20px;">
                        <?php echo $_SESSION['userData']['albums'][$i]['name']?> &nbsp;</input><span class="glyphicon glyphicon-folder-open"/></th></tr>
                        <?php
                    }
                    ?>
                    <tr>
                  <td><br><input type="hidden" value="multiple" name="val"></input>
                  <input type="button" value="Upload Albums" class="btn btn-danger" onclick="upload();"></input>
                  <input type="button" value="Download Albums" class="btn btn-danger" onclick="download();"></input>
                  </tr>
                </table>
                </form>
                </div>
              </div>
            </div>
    <script src="js/myjavascript.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </body>
</html>
