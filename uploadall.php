<?php
require_once "lib/Google/config.php";

if (isset($_REQUEST['val'])) {
    if (isset($_SESSION['gdaccess_token'])) {
        $Client->setAccessToken($_SESSION['gdaccess_token']);
        $service = new Google_Service_Drive($Client);

        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => "Facebook_".$_SESSION['userData']['first_name']."_albums",
            'mimeType' => 'application/vnd.google-apps.folder'));
        $file = $service->files->create($fileMetadata, array(
            'fields' => 'id'));
        $mainfolderId = $file->id;

        function uploadimage($i, $folderId, $service)
        {
            $count = count($_SESSION['userData']['albums'][$i]['photos']);
            for ($j=0; $j<$count; $j++) {
                $fileMetadata = new Google_Service_Drive_DriveFile(array(
                'name' => 'photo'.$j.'.jpg',
                'parents' => array($folderId)
                ));
                $content = file_get_contents($_SESSION['userData']['albums'][$i]['photos'][$j]['images'][0]['source']);
                $file = $service->files->create($fileMetadata, array(
                'data' => $content,
                'mimeType' => 'image/jpeg',
                'uploadType' => 'multipart',
                'fields' => 'id'));
            }
        }

        switch ($_REQUEST['val']) {
            case 'all':
                $totalalbum = count($_SESSION['userData']['albums']);
                ini_set('max_execution_time', 300);
                for ($i=0; $i<$totalalbum; $i++) {
                    $fileMetadata = new Google_Service_Drive_DriveFile(array(
                    'name' => $_SESSION['userData']['albums'][$i]['name'],
                    'parents' => array($mainfolderId),
                    'mimeType' => 'application/vnd.google-apps.folder'
                    ));
                    $file = $service->files->create($fileMetadata, array(
                        'fields' => 'id'));
                    $folderId = $file->id;
                    uploadimage($i, $folderId, $service);
                }
                header('Location: index.php');
                break;
            case 'single':
                $totalalbum = count($_SESSION['userData']['albums']);
                ini_set('max_execution_time', 300);
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
                        uploadimage($i, $folderId, $service);
                    }
                }
                header('Location: index.php');
                break;
            case 'multiple':
                  ini_set('max_execution_time', 300);
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
                            uploadimage($i, $folderId, $service);
                        }
                    }
                }
                header('Location: index.php');
                break;
            default:
                header('Location: index.php');
                break;
        }
        exit();
    } else {
        $_SESSION["url"] = "uploadall.php?".$_SERVER['QUERY_STRING'];
        $loginURL = $Client->createAuthUrl();
        header('Location: '.$loginURL);
    }
} else {
    header('Location: index.php');
}
