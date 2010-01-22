<?php
header('Content-type: text/plain; charset-utf-8');
session_start();

$login = ($_SESSION['n'] != "" || $_SESSION['p'] != "");
$data = array();

if ($login) {
  $p0 = array();
  $p0["name"] = "personal-0";
  $p0["title"] = "一人で出かける";
  $p0["values"] =
    array("width-check" => "true",
          "width" => 80,
          "bump-check" => "true",
          "bump" => 2);
  $data[] = $p0;

  $p1 = array();
  $p1["name"] = "personal-1";
  $p1["title"] = "付き添いの人と出かける";
  $p1["values"] =
    array("width-check" => "true",
          "width" => 80,
          "bump-check" => "true",
          "bump" => 5);
  $data[] = $p1;

  /* personal1の駐車場データだけ変更したものにする */
  $p3 = array();
  $p3["name"] = "personal-2";
  $p3["title"] = "車で出かける";
  $p3["values"] = $p0["values"];
  $p3["values"]["parking"] = "for-normal";
  $data[] = $p3;
}

echo json_encode($data);
?>