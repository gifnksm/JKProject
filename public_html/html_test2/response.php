<?
header('Content-type: text/plain; charset-utf-8');

/* カテゴリ名の配列を生成 */
/* 文字列をスペースで分割して配列に */
$cat_ids =
  explode(' ', 'width bump entrance elevator stair toilet for-baby parking');
$cat_names =
  explode(' ', '幅 段差 出入り口 エレベーター 階段 トイレ ベビー 駐車場');

/* 項目名＝＞数字 */
$list_parking = array( "over-350cm"  => 1,
                       "under-350cm" => 2 ,
                       "for-normal"  => 3 ,
                       "non-parking" => 4  );

$list_toilet = array( "disabled-toilet-separate" => 1,
                      "disabled-toilet-unisexed" => 2,
                      "normal-toilet"  => 3,
                      "without-toilet" => 4 );

$list_elevator = array( "with-large-ev"  => 1,
                        "with-normal-ev" => 2,
                        "without-ev"     => 3 );

/* 検索内容の抽出 */
$_POST['parking'] = $list_parking[$_POST['parking']];
$_POST['toilet'] = $list_toilet[$_POST['toilet']];
$_POST['elevator'] = $list_elevator[$_POST['elevator']];

$_POST['auto-door'] = 'true';

if ($_POST['route-width-check'] != 'true')
  $_POST['route-width-check'] = 0;
if ($_POST['entrance-width-check'] != 'true')
  $_POST['entrance-width'] = 0;
if ($_POST['entrance-bump-check'] != 'true')
  $_POST['entrance-bump'] = 100;
if ($_POST['road-to-entrance-bump-check'] != 'true')
  $_POST['road-to-entrance-bump'] =  100;
if ($_POST['parking-to-entrance-bump-check'] != 'true')
  $_POST['parking-to-entrancet-bump'] = 100;


/* MySQLに接続 : @mysql_connect()とすると表示されるPHPもエラーメッセージを非表示にできる */
$conn = mysql_connect("jkproject.localhost", "jkp", "jkproject");
mysql_query("SET NAMES 'utf-8'", $conn);
mysql_select_db("store_db", $conn);

/* 以下でクエリーの作成 */
function createQuery($term) {
  return "(tenmei LIKE \"%$term%\" OR address LIKE \"%$term%\" OR category LIKE \"%$term%\")";
}
$keyword = join(' AND ',
                array_map('createQuery',
                          preg_split('/[\s,　]+/', $_POST['searchTerm'])));


/* キーワード検索　＞＞　店名，住所，（カテゴリー）*/
/*店名・住所・カテゴリーでの検索（部分一致検索）*/
$query = "SELECT * FROM store_info WHERE $keyword";

/*クエリーを実行し．結果セットを取得*/
$result = mysql_query($query, $conn);

/* 検索結果の格納場所 */
$store_data = array();

function getBiasColor($row, $names, $bias) {
  if ($bias > 0) {
    // greater is better
    foreach ($names as $n) {
      if ($row[$n] < $_POST[$n])
        return "red";
    }
    foreach ($names as $n) {
      if ($row[$n] < $_POST[$n] + $bias)
        return "yellow";
    }
    return "blue";
  } elseif ($bias < 0) {
    // less is better
    foreach ($names as $n) {
      if ($row[$n] > $_POST[$n])
        return "red";
    }
    foreach ($names as $n) {
      if ($row[$n] > $_POST[$n] + $bias)
        return "yellow";
    }
    return "blue";
  }
}

function getColor($row, $names, $flag) {
  if ($flag) {
    // greater is better
    foreach ($names as $n) {
      if ($row[$n] < $_POST[$n])
        return "red";
    }
    return "blue";
  } else {
    // less is better
    foreach ($names as $n) {
      if ($row[$n] > $_POST[$n])
        return "red";
    }
    return "blue";
  }
}

function trueCount($row, $names) {
  $c = 0;
  foreach ($names as $n) {
    if ($_POST[$n] != 'true' || $row[$n] == 'true')
      $c++;
  }
  return $c;
}

/* 出力データ編集 */
while ($row = mysql_fetch_assoc($result)) {
  $detail = array();
  $score = 0;

  /* 幅 */
  $detail['width'] =
    getBiasColor($row, array('route-width', 'entrance-width'), 20);
  /* 段差 */
  $detail['bump'] =
    getBiasColor($row, array('entrance-bump', 'road-to-entrance-bump'), -3);
  /* 出入口 */
  if (trueCount($row, array('auto-door', 'slide-door', 'double-door')) >= 1)
    $detail['entrance'] = 'blue';
  else
    $detail['entrance'] = 'red';
  /* エレベータ */
  $detail['elevator'] = getColor($row, array('elevator'), false);
  /* 階段 */
  if ($_POST['stair-with-banister'] == 'true' &&
      $row['stair-with-banister'] == 'true')
    $detail['stair'] = 'blue';
  else
    $detail['stair'] = 'red';
  /* トイレ */
  $detail['toilet'] = getColor($row, array('toilet'), false);
  /* ベビー */
  $baby_count =
    trueCount($row, array('toilet-with-baby-bed',
                          'toilet-with-omutsu-koukan',
                          'omutsu-koukan',
                          'hot-water'));
  if ($baby_count >= 3)
    $detail['for-baby'] = 'blue';
  elseif ($baby_count >= 2)
    $detail['for-baby'] = 'yellow';
  else
    $detail['for-baby'] = 'red';
  /* 駐車場 */
  $detail['parking'] =
    getColor($row, array('parking', 'parking-to-entrancet-bump'), false);

  /* toilet-with-audio-assist, toilet-with-ostomate */
  $score += color_score($detail['width']);
  $score += color_score($detail['bump']);
  $score += color_score($detail['entrance']);
  $score += color_score($detail['elevator']);
  $score += color_score($detail['stair']);
  $score += color_score($detail['toilet']);
  $score += color_score($detail['for-baby']);
  $score += color_score($detail['parking']);

  $store_score = array('color' => store_color($score) ,
                       'value' => $score,
                       'detail' => $detail);

  /*店のカラー，スコア，青マークの数，ディテールを格納*/
  $store_data[] =
    array('id'  => $row['id'],
          'name' => $row['tenmei'],
          'lat' => $row['gps_ns'],
          'lng' => $row['gps_ew'],
          'addr' => $row['address'],
          'tel' => $row['tel'],
          'score' => $store_score);
}


/* ソートのルーチン */
function store_cmp($a, $b) {
  $av = $a['score']['value'];
  $bv = $b['score']['value'];
  if ($av == $bv)
    return 0;
  return $av < $bv ? 1 : -1;
}
/* スコア順にソート */
usort($store_data, "store_cmp");

$result_data = array('searchTerm' => $_POST['searchTerm'],
                     'category' =>
                     array('ids' => $cat_ids,
                           'names' => array_combine($cat_ids, $cat_names)),
                           'result' => $store_data);



/* ##########################################################
   関数の定義
   ########################################################## */
/*
  アイコン色とスコアを対応付ける関数（項目）
  $a:アイコン色
*/
function color_score($a) {
  switch($a) {
  case 'blue': return 3; break;
  case 'yellow': return 1; break;
  case 'red': return 0; break;
  }
}

/*
  スコアとアイコン色を対応付ける関数（施設）
  $a:スコア
*/
function store_color($a){
  if( $a >= 18) {
    return 'blue';
  } elseif( $a >= 15) {
    return 'yellow';
  } else {
    return 'red';
  }
}

/* response.php?dump でアクセスした場合 */
if (array_key_exists('dump', $_GET))
  var_export($result_data);
else
  echo json_encode($result_data);

?>