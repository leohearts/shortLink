<?php
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('shortLink.db');
    }
}
$db = new MyDB();

if (!$db) {
    die($db->lastErrorMsg());
}
$sql = <<<EOF
CREATE TABLE IF NOT EXISTS "shortlink" (
	"s"	TEXT NOT NULL UNIQUE,
	"full"	TEXT NOT NULL,
    "password"	TEXT NOT NULL,
	PRIMARY KEY("s")
)
EOF;
$ret = $db->exec($sql);
if (!$ret) {
    die($db->lastErrorMsg());
}

function returnLink($fullLink) {
    header("location: " . $fullLink);
    $fullLink = str_replace("'", "\\'", $fullLink);
    echo "<html><body>";
    echo "<a href='" . $fullLink . "'>Redirecting to " . SQLite3::escapeString($fullLink) . "</a>";
    echo "<script>document.querySelector('a').click()</script>";
    echo "</body></html>";
}

function getFull($sLink) {
    global $db;
    $sql = "SELECT full from shortlink where s='" . SQLite3::escapeString($sLink) . "'";
    $row = $db->querySingle($sql);
    return $row;
}

function getRandomString($len){
    $rand_pool = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $ret = "";
    for ($i=0;$i<$len;$i++){
        $ret = $ret . $rand_pool[random_int(0,62)];
    }
    return $ret;
}

function addLink ($link) {
    global $db;
    $sLink = getRandomString(5);
    $password = getRandomString(8);
    $sql = "INSERT INTO shortlink VALUES ('" . SQLite3::escapeString($sLink) . "', '" . SQLite3::escapeString($link) . "', '" . SQLite3::escapeString($password) . "')";
    $ret = $db->exec($sql);
    if (!$ret) {
        return ["status" => -1, "sLink" => $db->lastErrorMsg()];
    }
    return ["status" => 0, "sLink" => $sLink, "password" => $password];
}

function changeLink ($data) {
    global $db;
    $password = $data->password;
    $sLink = $data->sLink;
    $newLink = $data->newLink;
    $sql = "UPDATE shortlink SET full = '" . SQLite3::escapeString($newLink) . "' where s = '" . SQLite3::escapeString($sLink) . "' and password = '" . SQLite3::escapeString($password) . "'";
    $ret = $db->exec($sql);

    if (!$ret) {
        return ["status" => -1, "sLink" => $db->lastErrorMsg()];
    }
    return ["status" => 0, "db" => $sql, "ret" => $ret];
}
