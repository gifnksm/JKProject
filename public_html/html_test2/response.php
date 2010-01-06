<?
$data_dir = './data';

$names = array();

/* http://www.php.net/manual/ja/function.readdir.php */
if ($handle = opendir($data_dir)) {
  while (false !== ($file = readdir($handle))) {
    if (is_file("$data_dir/$file")) {
      $names[] = "$data_dir/$file";
    }
  }
  closedir($handle);
}

// ランダムにレスポンスを返す
$name = $names[rand(0, count($names)-1)];
$handle = fopen($name, 'r');
echo fread($handle, filesize($name));
fclose($handle);

?>