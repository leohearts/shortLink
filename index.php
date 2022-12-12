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
if (isset($_POST['change'])) {
    $data = json_decode($_POST['change']);
    $value = changeLink($data);
    echo json_encode($value);
    exit();
}

if (isset($_POST['fullLink'])){
    $value = addLink($_POST['fullLink']);
    echo json_encode($value);
    exit();
}

echo file_get_contents("ui.html");
