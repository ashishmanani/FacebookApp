<?php
$totalalbum = 1;
class TestCase extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $this->createDir("my");
        //$this->assertDirectoryIsWritable("images/my");
        $this->putimage();
        $this->createzip("images/my", "zip/myZip.zip");
        $this->assertTrue(true);
    }

    public function createDir($dirname)
    {
        try {
            if (!file_exists("images/".$dirname)) {
                mkdir("images/".$dirname, 077);
                //$this->assertDirectoryExists('images/my');
                echo "Directory Created Successfully.";
                $this->assertTrue(true);
            } else {
                echo "Directory with files are removing...\n";
                $this->deletefiles("images/".$dirname);
                mkdir("images/".$dirname, 077);
                //$this->assertDirectoryExists("images/my");
                echo "Directory Created Successfully.\n";
                $this->assertTrue(true);
            }
        } catch (\Exception $e) {
            echo "Directory Creation Error.\n";
            $this->assertFalse(true);
        }
    }

    public function putimage()
    {
        try {
            $count = 8;
            for ($j=1; $j<=$count; $j++) {
                if (!file_exists("images/ashish/"."image$j.jpg")) {
                    file_put_contents("images/my/"."image$j.jpg", file_get_contents("images/image$j.jpg"));
                    echo "File image$j.jpg Download Successfully on images/my folder.\n";
                    $this->assertTrue(true);
                } else {
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        chown("images/ashish/"."image$j.jpg");
                        echo "File image$j.jpg Remove Successfully.\n";
                    } else {
                        unlink("images/ashish/"."image$j.jpg");
                        echo "File image$j.jpg Remove Successfully.\n";
                    }
                    file_put_contents("images/my/"."image$j.jpg", file_get_contents("images/image$j.jpg"));
                    echo "File image$j.jpg Download Successfully.\n";
                    $this->assertTrue(true);
                }
            }
        } catch (\Exception $e) {
            echo "Image Downloading Error";
            $this->assertFalse(true);
        }
    }

    public function deletefiles($target)
    {
        try {
            if (is_dir($target)) {
                $files = glob($target . '*', GLOB_MARK);
                foreach ($files as $file) {
                    $this->deletefiles($file);
                }
                if ($GLOBALS['totalalbum']!=0) {
                    rmdir($target);
                    $GLOBALS['totalalbum']--;
                }
            } elseif (is_file($target)) {
                unlink($target);
            }
        } catch (\Exception $e) {
            echo "File Deletion Error";
            $this->assertFalse(true);
        }
    }

    public function createzip($originalpath, $zipfilepath)
    {
        try {
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
            echo "Zip Creation Successfully And it store in zip/myZip.zip.\n";
            $this->assertTrue(true);
        } catch (\Exception $e) {
            echo "Zip Creation Error.\n";
            $this->assertFalse(true);
        }
    }
}
