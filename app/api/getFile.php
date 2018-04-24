<?php

header('Content-type: application/json');

$url = isset($_POST['url']) ? $_POST['url'] : null;

if(is_null($url) || $url == "") {
    $arr = array();
    $arr["status"] = "KO";
    $arr["message"] = "URL is incorrect";
    echo json_encode($arr);
    exit;
}

require __DIR__ . '/vendor/autoload.php';

use YoutubeDl\YoutubeDl;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\NotFoundException;
use YoutubeDl\Exception\PrivateVideoException;

$dl = new YoutubeDl([
    'extract-audio' => true,
    'audio-format' => 'mp3',
    'audio-quality' => 0, // best
    'output' => '%(title)s.%(ext)s'
]);
// For more options go to https://github.com/rg3/youtube-dl#user-content-options

$dl->setBinPath('/usr/local/bin/youtube-dl');
$dl->setDownloadPath(__DIR__);

/*$dl->onProgress(function ($progress) {
    $percentage = $progress['percentage'];
    $size = $progress['size'];
    $speed = $progress['speed'] ?? null;
    $eta = $progress['eta'] ?? null;
    
    //echo "Percentage: $percentage; Size: $size";
    $texto = "Percentage: $percentage; Size: $size";
    if ($speed) {
        //echo "; Speed: $speed";
        $texto .= "; Speed: $speed";
    }
    if ($eta) {
        //echo "; ETA: $eta";
        $texto .= "; ETA: $eta";
    }
    //echo "<br>";

    $fp = fopen(__DIR__ . '/N8U0Pb2JLbY.txt', 'a');
    fwrite($fp, $texto . "\n");
    fclose($fp);

    // Will print: Percentage: 21.3%; Size: 4.69MiB; Speed: 4.47MiB/s; ETA: 00:01
});*/

// Enable debugging
/*$dl->debug(function ($type, $buffer) {
    if (\Symfony\Component\Process\Process::ERR === $type) {
        echo 'ERR > ' . $buffer;
    } else {
        echo 'OUT > ' . $buffer;
    }
});*/
try {
    $video = $dl->download($url);

    $arr = array();
    $arr["status"] = "OK";
    $arr["url"] = $video->getFilename();
    echo json_encode($arr);
    exit;
    // $video->getFile(); // \SplFileInfo instance of downloaded file
} catch (NotFoundException $e) {
    // Video not found
    $arr = array();
    $arr["status"] = "KO";
    $arr["message"] = "Video not found";
    echo json_encode($arr);
    exit;
} catch (PrivateVideoException $e) {
    // Video is private
    $arr = array();
    $arr["status"] = "KO";
    $arr["message"] = "Video not found";
    echo json_encode($arr);
    exit;
} catch (CopyrightException $e) {
    // The YouTube account associated with this video has been terminated due to multiple third-party notifications of copyright infringement
    $arr = array();
    $arr["status"] = "KO";
    $arr["message"] = "Video not found";
    echo json_encode($arr);
    exit;
} catch (\Exception $e) {
    // Failed to download
    $arr = array();
    $arr["status"] = "KO";
    $arr["message"] = "Video not found";
    echo json_encode($arr);
    exit;
}
