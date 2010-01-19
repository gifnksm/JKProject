<?
header('Content-type: text/plain; charset-utf-8');

/* カテゴリ名の配列を生成 */
/* 文字列をスペースで分割して配列に */
$cat_ids =
  explode(' ', 'width bump entrance elevator stair toilet for-baby parking');
$cat_names =
  explode(' ', '幅 段差 出入り口 エレベーター 階段 トイレ ベビー 駐車場');

/* データファイルを文字列に読み込む */
$data_file = './db.json';
$handle = fopen($data_file, 'r');
$json_str = fread($handle, filesize($data_file));
fclose($handle);

/* 読み込んだ文字列をJSONとしてパースする */
/* 第2引数に true を指定して(連想)配列に */
$building_data = json_decode($json_str, true);

/* スコアリング関数 */
function get_score_name($score) {
  if ($score <= 5)
    return 'red';
  if ($score <= 8)
    return 'yellow';
  return 'blue';
}

/* score および scoreDetail を設定 */
foreach ($building_data as &$building) { /* &をつけてループ内で代入可能に */
  $detail = array();
  $score_base = rand(5, 10);
  $score_arr = array();
  foreach ($cat_ids as $id) {
    /* カテゴリのスコアを設定 */
    $score = rand(-5, 5) + $score_base; /* 値が偏りやすくなるようにbaseでバイアス */
    if ($score < 0) $score = 0;
    elseif($score > 10) $score = 10;

    $detail[$id] = array('color' => get_score_name($score),
                         'value' => $score,
                         'message' => 'これがだめ。あれがだめ。判定の詳細');
    $score_arr[] = $score;
  }
  /* 各カテゴリスコアの最悪値 (最小値) で施設のスコアを決定 */
  $score = min($score_arr);
  $building['score'] = array('color' => get_score_name($score),
                             'value' => $score,
                             'detail' => $detail);
}

/* スコア順にソート */
function building_cmp($a, $b) {
  $av = $a['score']['value'];
  $bv = $b['score']['value'];
  if ($av == $bv)
    return 0;
  return $av < $bv ? 1 : -1;
}
usort($building_data, "building_cmp");

$result_data = array('searchTerm' => $_POST['searchTerm'],
                     'category' =>
                     array('ids' => $cat_ids,
                           'names' => array_combine($cat_ids, $cat_names)),
                     'result' => $building_data);

/* response.php?dump でアクセスした場合 */
if (array_key_exists('dump', $_GET))
  var_export($result_data);
else
  echo json_encode($result_data);

?>