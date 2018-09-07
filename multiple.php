<?php
/*
  Name:- Ashish P. Manani
  Last Edit:- 07-09-2018
  Purpose:- For Provide Uploading and Downloading Functionality of multiple albums.
*/
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
                    <table>
                        <tr><td><?php $user_id = $_SESSION['userData']['id'];
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
                  <td><br>
                  <?php if (isset($_SESSION['gdaccess_token'])) {?>
                    <input type="button" value="Upload Selected Albums" class="btn btn-danger"
                    onclick="uploadimage('uploadall.php?val=multiple&','multiple')"></input>
                  <?php }else{?>
                  <a href="uploadall.php?val=login" class="btn btn-danger">Login To Drive</a>
                  <?php }?>
                    <input type="button" value="Download Selected Albums" class="btn btn-danger"
                    onclick="downloadpage('downloadall.php?val=multiple&','multiple')"></input>
                  </tr>
                </table>
                </div>
              </div>
              <!-- Modal -->
              <div class="modal fade" id="downloadmodal" role="dialog">
                <div class="modal-dialog">
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" id="modelclose" style="display:none;">&times;</button>
                    </div>
                    <div class="modal-body" align="center" id="modelbody">
                  <img src="images/loading.gif" id="loading"/>
                  <div style="display:none;" id="downloadbutton">
                    <h3>Click Download Button to Download Zip <?php echo $user_id;?>.zip.</h3><br>
                    <a href="zip/<?php echo $user_id;?>.zip" class="btn btn-success">Download</a>
                  </div>
                    </div>
                    <div class="modal-footer" id="modelclosebutton" style="display:none;">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                  
                </div>
              </div>
                <!-- Modal -->
              <div class="modal fade" id="uploadmodal" role="dialog">
                <div class="modal-dialog">
                
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" id="modelcloseupload" style="display:none;">&times;</button>
                    </div>
                    <div class="modal-body" align="center" id="modelbody">
                  <img src="images/loading.gif" id="loadingupload"/>
                  <div id="uploadtext">
                    
                  </div>
                    </div>
                    <div class="modal-footer" id="uploadmodelclosebutton" style="display:none;">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
            
    <script src="js/download_upload.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </body>
</html>
