<?php
/*
  Name:- Ashish P. Manani
  Last Edit:- 07-09-2018
  Purpose:- For Provide Uploading Functionality this Script will be use.
*/
require_once "lib/Google/config.php";
require_once "init.php";
if (isset($_REQUEST['val'])) {
    if (isset($_SESSION['gdaccess_token'])) {
        if(isset($_REQUEST['val']))
		{
      //for validation purpose.
			if($_REQUEST['val'] == 'single' || $_REQUEST['val'] == 'multiple')
			{
				if(!isset($_REQUEST['q']))
				{
					header('Location: multiple.php?err=d/u');
				}
				if($_REQUEST['q'] == "")
				{
					header('Location: multiple.php?err=d/u');
			}
			}
			$Client->setAccessToken($_SESSION['gdaccess_token']);
			$service = new Google_Service_Drive($Client);
      //Create Root folder in google drive.
			$fileMetadata = new Google_Service_Drive_DriveFile(array(
				'name' => "Facebook_".$_SESSION['userData']['first_name']."_albums",
				'mimeType' => 'application/vnd.google-apps.folder'));
			$file = $service->files->create($fileMetadata, array(
				'fields' => 'id'));
			$mainfolderId = $file->id;

			switch ($_REQUEST['val']) {
				case 'all':
        //Iterate all album and create folder of perticular album and send request for uploading images. 
					$totalalbum = count($_SESSION['userData']['albums']);
          $folderidall = null;
          $remain = null;
          $albumindex = null;
					for ($i=0; $i<$totalalbum; $i++) {
						$fileMetadata = new Google_Service_Drive_DriveFile(array(
						'name' => $_SESSION['userData']['albums'][$i]['name'],
						'parents' => array($mainfolderId),
						'mimeType' => 'application/vnd.google-apps.folder'
						));
						$file = $service->files->create($fileMetadata, array(
							'fields' => 'id'));
						$folderId = $file->id;
            $count=$_SESSION['userData']['albums'][$i]['count'];
            if($folderidall == null && $remain == null && $albumindex == null){
              $folderidall = $folderId;
              $remain = $count;
              $albumindex = $i;
            }else{
              $folderidall = $folderidall.",".$folderId;
              $remain = $remain.",".$count;
              $albumindex = $albumindex.",".$i;
            }
					}
          if($folderidall != null && $remain != null && $albumindex != null){
						header("Location:uploadall.php?folderId=$folderidall&remain=$remain&albumindex=$albumindex");
					}
					break;
				case 'single':
        //create single album folder and send request for uploading images. 
					$totalalbum = count($_SESSION['userData']['albums']);
					for ($i=0; $i<$totalalbum; $i++) {
						if ($_SESSION['userData']['albums'][$i]['id'] == $_REQUEST['q']) {
							$fileMetadata = new Google_Service_Drive_DriveFile(array(
							'name' => $_SESSION['userData']['albums'][$i]['name'],
							'parents' => array($mainfolderId),
							'mimeType' => 'application/vnd.google-apps.folder'
							));
							$file = $service->files->create($fileMetadata, array(
							'fields' => 'id'));
							$folderId = $file->id;
							$count=$_SESSION['userData']['albums'][$i]['count'];
							header("Location:uploadall.php?folderId=$folderId&remain=$count&albumindex=$i");
						}
					}
					break;
				case 'multiple':
        //Iterate Multiple Album And send request for uploading images.
					$folderidall = null;
					$remain = null;
					$albumindex = null;
					foreach ($_REQUEST['q'] as $value) {
						$totalalbum = count($_SESSION['userData']['albums']);
						for ($i=0; $i<$totalalbum; $i++) {
							if ($_SESSION['userData']['albums'][$i]['id'] == $value) {
								$fileMetadata = new Google_Service_Drive_DriveFile(array(
								'name' => $_SESSION['userData']['albums'][$i]['name'],
								'parents' => array($mainfolderId),
								'mimeType' => 'application/vnd.google-apps.folder'
								));
								$file = $service->files->create($fileMetadata, array(
								'fields' => 'id'));
								$folderId = $file->id;
								$count=$_SESSION['userData']['albums'][$i]['count'];
                if($folderidall == null && $remain == null && $albumindex == null){
									$folderidall = $folderId;
									$remain = $count;
									$albumindex = $i;
								}else{
									$folderidall = $folderidall.",".$folderId;
									$remain = $remain.",".$count;
									$albumindex = $albumindex.",".$i;
								}
							}
						}
					}
					if($folderidall != null && $remain != null && $albumindex != null){
						header("Location:uploadall.php?folderId=$folderidall&remain=$remain&albumindex=$albumindex");
					}
					break;
				default:
					header('Location: index.php');
					break;
			}
			exit();
		}
    } else {
        //Get login url of google for authentication of user.And redirect to login page 
        $loginURL = $Client->createAuthUrl();
        header('Location: '.$loginURL);
    }
} else if(isset($_REQUEST['folderId']) && isset($_REQUEST['remain']) && isset($_REQUEST['albumindex'])){
//It can used to upload images of selected or all or sinigle albums.
	//create google client and set access token of developer account.
  $Client->setAccessToken($_SESSION['gdaccess_token']);
	$service = new Google_Service_Drive($Client);
  $allabumindex = explode(",",$_REQUEST['albumindex']);
	$albumindex = $allabumindex[0];
	$albumid = $_SESSION['userData']['albums'][$albumindex]['id'];
	$count = $_SESSION['userData']['albums'][$albumindex]['count'];
	$allremain = explode(",",$_REQUEST['remain']);
 	$offset = $count - $allremain[0];
  	$allfolderid = explode(",",$_REQUEST['folderId']);
	$folderId = $allfolderid[0];
	$loop = 100;
	if($allremain[0]<100)
	{
		$loop = $allremain[0];
    
	}
  //upload image
	for($i=0;$i<$loop;$i++)
	{
		$fileMetadata = new Google_Service_Drive_DriveFile(array(
		'name' => $_SESSION['userData']['albums'][$albumindex]['name']."_".$offset.'.jpg',
		'parents' => array($folderId)
		));
		$content = file_get_contents($_SESSION['userData'][$albumid][$offset]);
		$file = $service->files->create($fileMetadata, array(
		'data' => $content,
		'mimeType' => 'image/jpeg',
		'uploadType' => 'multipart',
		'fields' => 'id'));
		$offset++;
	}
	$allremain[0] = $allremain[0] - 100;
	if($allremain[0]>0){
    $tmpremain=implode(",",(array_filter($allremain)));
    $tmpfolderid=implode(",",(array_filter($allfolderid)));
    $tmpalbumindex = implode(",",(array_filter($allabumindex)));
		header("Location:uploadall.php?folderId=$tmpfolderid&remain=$tmpremain&albumindex=$tmpalbumindex");
	}else{
    unset($allremain[0]);
    unset($allfolderid[0]);
    unset($allabumindex[0]);
    if(!empty($allremain)){
      $tmpremain=implode(",",(array_filter($allremain)));
      $tmpfolderid=implode(",",(array_filter($allfolderid)));
      $tmpalbumindex = implode(",",(array_filter($allabumindex)));
      header("Location:uploadall.php?folderId=$tmpfolderid&remain=$tmpremain&albumindex=$tmpalbumindex");
    }else{
      echo "<h3>Albums Uploaded on Google Drive.</h3>";
    }
	}
	//echo "<h3>Uploding Successfull</h3>";
}else {
    //header('Location: index.php');
}
