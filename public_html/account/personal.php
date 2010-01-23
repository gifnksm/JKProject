<?php
require 'DB.php';

header('Content-type: text/plain; charset-utf-8');
session_start();

$name_ = $_SESSION['n'];
$pass_ = $_SESSION['p'];
$id_ = $_SESSION['i'];
$login = ($name_ != "" || $pass_ != "");

if (!$login) {
  echo "[]";
  exit();
}

$db = DB::connect("mysql://jkp:jkproject@jkproject.localhost/login_db");
$db->query("SET NAMES 'utf-8'");

if (DB::isError($db)) {
  echo "[]";
  exit();
}

$rs = $db->query("SELECT * FROM credentials WHERE id='$id_'");
$row = $rs->fetchRow(DB_FETCHMODE_ASSOC);

/* 値を取り出す */
$vals = array();
if (!is_null($row["width"])) {
  $vals["width-check"] = "true";
  $vals["width"] = $row["width"];
}
if ($row["slidedoor"])
  $vals["slide-door"] = "true";
if ($row["doubledoor"])
  $vals["double-door"] = "true";
if ($row["autodoor"])
  $vals["auto-door"] = "true";
if (!is_null($row["elevator"]))
  $vals["elevator"] = $row["elevator"];
if (!is_null($row["step"]))
  $vals["stair"] = $row["step"];
if (!is_null($row["baby"]))
  $vals["baby-type"] = $row["baby"];

/* 個人用設定をつくる */

$p0 = array();
$p0["name"] = "personal-0";
$p0["title"] = "一人で出かける";
$p0["values"] = $vals;
if (!is_null($row["rank1"])) {
  $p0["values"]["bump-check"] = "true";
  $p0["values"]["bump"] = $row["rank1"];
}

$p1 = array();
$p1["name"] = "personal-1";
$p1["title"] = "付き添いの人と出かける";
$p1["values"] = $vals;
if (!is_null($row["rank2"])) {
  $p1["values"]["bump-check"] = "true";
  $p1["values"]["bump"] = $row["rank2"];
}

/* personal1の駐車場データだけ変更したものにする */
$p2 = array();
$p2["name"] = "personal-2";
$p2["title"] = "車で出かける";
$p2["values"] = $p0["values"];
$p2["values"]["parking"] = "for-normal";

if (array_key_exists('dump', $_GET)) {
  echo "=============  sql  =============\n";
  var_dump($row);
  echo "============= result =============\n";
  var_dump(array($p0, $p1, $p2));
} else {
  echo json_encode(array($p0, $p1, $p2));
}
?>