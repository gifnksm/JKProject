<?php
header('Content-type: text/plain; charset-utf-8');
session_start();

$login = ($_SESSION['n'] != "" || $_SESSION['p'] != "");
$data = array();

if ($login) {
  $personal1 = array();
  $personal1["name"] = "personal-0";
  $personal1["title"] = "一人で出かける";
  $personal1["values"] =
    array("width-check" => true,
          "width" => 80,
          "bump-check" => true,
          "bump" => 2);
  $data[] = $personal1;

  $personal2 = array();
  $personal2["name"] = "personal-1";
  $personal2["title"] = "付き添いの人と出かける";
  $personal2["values"] =
    array("width-check" => true,
          "width" => 80,
          "bump-check" => true,
          "bump" => 5);
  $data[] = $personal2;

  $personal3 = array();
  $personal3["name"] = "personal-2";
  $personal3["title"] = "車で出かける";
  $personal3["values"] =
    array("parking" => "for-normal");
  $data[] = $personal3;
}

echo json_encode($data);
?>