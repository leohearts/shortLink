<?php
require_once('utils.php');
$sLink = basename($_SERVER['REDIRECT_URL']);
preg_match("/[a-zA-Z0-9-_=]*/", $sLink, $matches);
$sLink = $matches[0];

if ($sLink !== '' && $sLink !== 'index') {
    $fullLink = getFull($sLink);
    if ($fullLink == '') {
        die("404");
    }
    returnLink($fullLink);
    exit();
}

if (isset($_POST['fullLink'])){
    $response_json = [];
    $value = addLink($_POST['fullLink']);

    $response_json['status'] = $value[0];
    $response_json['sLink'] = $value[1];
    echo json_encode($response_json);
    exit();
}

echo file_get_contents("ui.html");
