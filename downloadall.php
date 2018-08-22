<?php
session_start();
$totalalbum = count($_SESSION['userData']['albums']);
if (isset($_REQUEST['val'])) {

    function putimage($i)
    {
        $count = count($_SESSION['userData']['albums'][$i]['photos']);
        for ($j=0; $j<$count; $j++) {
            if (!file_exists("images/".$_SESSION['userData']['id']."/".$_SESSION['userData']['albums'][$i]['name']."/photo$j.jpg")) {
                file_put_contents("images/".$_SESSION['userData']['id']."/".$_SESSION['userData']['albums'][$i]['name']."/photo$j.jpg", file_get_contents($_SESSION['userData']['albums'][$i]['photos'][$j]['images'][0]['source']));
            } else {
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    chown("images/".$_SESSION['userData']['id']."/".$_SESSION['userData']['albums'][$i]['name']."/photo$j.jpg");
                } else {
                    unlink("images/".$_SESSION['userData']['id']."/".$_SESSION['userData']['albums'][$i]['name']."/photo$j.jpg");
                }
                file_put_contents("images/".$_SESSION['userData']['id']."/".$_SESSION['userData']['albums'][$i]['name']."/photo$j.jpg", file_get_contents($_SESSION['userData']['albums'][$i]['photos'][$j]['images'][0]['source']));
            }
        }
    }

    function delete_files($target)
    {
        if (is_dir($target)) {
            $files = glob($target . '*', GLOB_MARK);
            foreach ($files as $file) {
                delete_files($file);
            }
            if ($GLOBALS['totalalbum']!=0) {
                rmdir($target);
                $GLOBALS['totalalbum']--;
            }
        } elseif (is_file($target)) {
            unlink($target);
        }
    }

    function createzip($originalpath, $zipfilepath)
    {
        if (file_exists($zipfilepath)) {
            unlink($zipfilepath);
        }
        if (is_dir($originalpath)) {
            $rootPath = realpath($originalpath);
            $zip = new ZipArchive();
            $zip->open($zipfilepath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }
    }

    function downloadzip($filename)
    {
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-length: " . filesize($filename));
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile("$filename");
    }

    switch ($_REQUEST['val']) {
        case 'all':
            if (!file_exists("images/".$_SESSION['userData']['id'])) {
                mkdir("images/".$_SESSION['userData']['id']);
            }
            ini_set('max_execution_time', 300);
            for ($i=0; $i<$GLOBALS['totalalbum']; $i++) {
                if (!file_exists("images/".$_SESSION['userData']['id']."/".$_SESSION['userData']['albums'][$i]['name'])) {
                    mkdir("images/".$_SESSION['userData']['id']."/".$_SESSION['userData']['albums'][$i]['name']);
                }
                putimage($i);
            }
            createzip("images/".$_SESSION['userData']['id'], "zip/".$_SESSION['userData']['id'].".zip");
            delete_files("images/".$_SESSION['userData']['id']);
            downloadzip("zip/".$_SESSION['userData']['id'].".zip");
            break;
        case 'single':
            if (!file_exists("images/".$_SESSION['userData']['id'])) {
                mkdir("images/".$_SESSION['userData']['id']);
            } else {
                delete_files("images/".$_SESSION['userData']['id']);
                mkdir("images/".$_SESSION['userData']['id']);
            }
            $GLOBALS['totalalbum'] = count($_SESSION['userData']['albums']);
            ini_set('max_execution_time', 300);
            for ($i=0; $i<$GLOBALS['totalalbum']; $i++) {
                if ($_SESSION['userData']['albums'][$i]['id'] == $_REQUEST['q']) {
                    if (!file_exists("images/".$_SESSION['userData']['id']."/".$_SESSION['userData']['albums'][$i]['name'])) {
                        mkdir("images/".$_SESSION['userData']['id']."/".$_SESSION['userData']['albums'][$i]['name']);
                    }
                    putimage($i);
                }
            }
            createzip("images/".$_SESSION['userData']['id'], "zip/".$_SESSION['userData']['id'].".zip");
            delete_files("images/".$_SESSION['userData']['id']);
            downloadzip("zip/".$_SESSION['userData']['id'].".zip");
            break;
        case 'multiple':
            if (!file_exists("images/".$_SESSION['userData']['id'])) {
                mkdir("images/".$_SESSION['userData']['id']);
            } else {
                delete_files("images/".$_SESSION['userData']['id']);
                mkdir("images/".$_SESSION['userData']['id']);
            }
             ini_set('max_execution_time', 300);
             $GLOBALS['totalalbum'] = count($_SESSION['userData']['albums']);
            foreach ($_REQUEST['q'] as $value) {
                for ($i=0; $i<$GLOBALS['totalalbum']; $i++) {
                    if ($_SESSION['userData']['albums'][$i]['id'] == $value) {
                        if (!file_exists("images/".$_SESSION['userData']['id']."/".$_SESSION['userData']['albums'][$i]['name'])) {
                            mkdir("images/".$_SESSION['userData']['id']."/".$_SESSION['userData']['albums'][$i]['name']);
                        }
                        putimage($i);
                    }
                }
            }
            createzip("images/".$_SESSION['userData']['id'], "zip/".$_SESSION['userData']['id'].".zip");
            delete_files("images/".$_SESSION['userData']['id']);
            downloadzip("zip/".$_SESSION['userData']['id'].".zip");
            break;
        default:
            header('Location: index.php');
            break;
    }
}
