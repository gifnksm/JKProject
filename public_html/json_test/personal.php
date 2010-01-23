<?php
require 'DB.php';

session_start();
$name_ = $_SESSION['n'];
$pass_ = $_SESSION['p'];
$id_ = $_SESSION['i'];

$db = DB::connect("mysql://jkp:jkproject@jkproject.localhost/login_db");
$db->query("SET NAMES 'utf-8'");


if (DB::isError($db))
{
    echo "データベースに接続できません\n<BR>";
}
else
{

if($_SESSION['n'] != "" || $_SESSION['p'] != "")
{

$rs = $db->query("SELECT * FROM credentials WHERE id='$id_'");

$row = $rs->fetchRow();
//echo json_encode($row);
//***********************************************
//ここから個人情報受け渡し
//
echo <<<JSON
[{"name": "login_user",
  "title": "個人設定(一人で)",
  "values": {
    "width-check": "true",
    "width": $row[18],
    "bump-check": "true",
    "bump": $row[8],
    "slide-door": $row[15],
    "double-door": $row[16],
    "auto-door" : $row[17],
    "elevator": $row[10],
    "stair": $row[11],
    "toilet-type" : $row[12]
  }
 },
{"name": "login_user(2)",
  "title": "個人設定(付き添いあり)",
  "values": {
    "width-check": "true",
    "width": $row[18],
    "bump-check": "true",
    "bump": $row[9],
    "slide-door": $row[15],
    "double-door": $row[16],
    "auto-door" : $row[17],
    "elevator": $row[10],
    "stair": $row[11],
    "toilet-type" : $row[12]
  }
 },


 {"name": "personal-0",
  "title": "一人で出かける",
  "values": {
    "width-check": "true",
    "width": 80,
    "bump-check": "true",
    "bump": 2
  }
 },
 {"name": "personal-1",
  "title": "付き添いの人と出かける",
  "values": {
    "width-check": "true",
    "width": 80,
    "bump-check": "true",
    "bump": 5
  }
 },
 {"name": "personal-2",
  "title": "車で出かける",
  "values": {
    "parking": "for-normal"
  }
 }
]
JSON;
}
}
?>
