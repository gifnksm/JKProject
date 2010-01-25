<?php
header('Content-type: text/plain; charset-utf-8');

$data_file = './detail.json';
$handle = fopen($data_file, 'r');
$json_str = fread($handle, filesize($data_file));
fclose($handle);
echo $json_str;
?>