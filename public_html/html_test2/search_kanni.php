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

/*キーワード*/
$keyword = $_POST['searchTerm'];

/* 項目名＝＞数字 */
$list_parking = array( "over-350cm"  => 1,
                       "under-350cm" => 2 ,
                       "for-nomal"   => 3 ,
                       "non-parking" => 4  );

$list_toilet = array( "disabled-toilet-separate" => 1,
                      "disabled-toilet-unisexed" => 2,
                      "normal-toilet"  => 3,
                      "without-toilet" => 4 );

$list_elevator = array( "with-large-elevator"  => 1,
                        "with-normal-elevator" => 2,
                        "without-elevator"     => 3 );

/* 検索内容の抽出 */
$sc_parking =  $_POST['parking'];
$sc_toilet_type =  $_POST['toilet-type'];
$sc_elevator = $_POST['elevator'];

$sc_route_width =  $_POST['route-width'];
$sc_entrance_width =  $_POST['entrance-width'];
$sc_entrance_bump = $_POST['entrance-bump'];
$sc_road_to_entrancet_bump =  $_POST['road-to-entrancet-bump'];
$sc_parking_to_entrancet_bump =  $_POST['parking-to-entrancet-bump'];



/* MySQLに接続 : @mysql_connect()とすると表示されるPHPもエラーメッセージを非表示にできる */
$conn = mysql_connect("jkproject.localhost", "jkp", "jkproject");
mysql_query("SET NAMES 'utf-8'", $conn);
mysql_select_db("store_db", $conn);

/* 以下でクエリーの作成 */

function translationLeq($name, $value) {
  if(is_null($value)){
    return '';
  } else {
    return "`$name` <= $value";
  }
}

/*データベースと検索条件をつなぐ関数
  $data:カラム名　$search:検索変数
*/
function translationEqual($data){
  $search = $_POST[$data];
  if(is_null($search)){
    return '';
  } else {
    return "`$data` = '$search'";
  }
}

function nonnull($e) {
  return $e != '';
}


/*条件検索*/
$conditions =
  array_merge(array(translationLeq('parking',
                                   $list_parking[$sc_parking]),
                    translationLeq('toilet',
                                   $list_toilet[$sc_toilet]),
                    translationLeq('elevator',
                                   $list_elevator[$sc_elevator]),
                    translationLeq('entrance-width',
                                   $sc_entrance_width),
                    translationLeq('route-width',
                                   $sc_route_width),
                    translationLeq('entrance-bump',
                                   $sc_entrance_width),
                    translationLeq('road-to-entrance-bump',
                                   $sc_road_to_entrance_bump),
                    translationLeq('parking-to-entranve-bump',
                                   $sc_parking_to_entrance_bump)),
              array_map('translationEqual',
                        array("parking-carport",
                              "with-slope",
                              "slide-door",
                              "double-door",
                              "auto-door",
                              "road-to-entrance-with-block",
                              "with-barrier",
                              "slipper-floor",
                              "help",
                              "with-audio-assist",
                              "with-braille-assist",
                              "guidemap",
                              "toilet-with-ostomate",
                              "toilet-with-baby-bed",
                              "toilet-with-baby-omutsu",
                              "toilet-with-audio-assist",
                              "with-banister",
                              "with-baby-çomutsu-koukan",
                              "with-baby-hot-water",
                              "kurumaisu-room",
                              "kurumaisu-daiyokujo",
                              "kurumaisu-rastaurant",
                              "kurumaisu-kanranseki",
                              "kurumaisu-phone",
                              "nancho-phone",
                              "braille-menu",
                              "vending-machine-for-disabled",
                              "vending-machine-with-braille")));

$query2 = join(' || ', array_filter($conditions, 'nonnull'));
if ($query2 == '')
  $query2 = 'true';

$keyword = '店';
/* キーワード検索　＞＞　店名，住所，（カテゴリー）*/
/*店名・住所・カテゴリーでの検索（部分一致検索）*/
$query =
  "SELECT * FROM store_info WHERE" .
  "(tenmei LIKE \"%$keyword%\" OR address LIKE \"%$keyword%\" OR category LIKE \"%$keyword%\") AND ($query2)";

/*クエリーを実行し．結果セットを取得*/
$result = mysql_query($query, $conn);








/* 検索結果の格納場所 */
$store_data = array();

function biasGtColor($row, $names, $bias) {
  $cleared = true;
  foreach($names as $n) {
    if (!($_POST[$n] - $bias >= $row[$n])) {
      $cleared = false;
      break;
    }
  }
  if ($cleared)
    return "blue";
  $cleared = true;
  foreach($names as $n) {
    if (!($_POST[$n] > $row[$n])) {
      $cleared = false;
      break;
    }
  }
  if ($cleared)
    return "yellow";
  return "red";
}

function biasLtColor($row, $names, $bias) {
  $cleared = true;
  foreach($names as $n) {
    if (!($_POST[$n] + $bias <= $row[$n])) {
      $cleared = false;
      break;
    }
  }
  if ($cleared)
    return "blue";
  $cleared = true;
  foreach($names as $n) {
    if (!($_POST[$n] < $row[$n])) {
      $cleared = false;
      break;
    }
  }
  if ($cleared)
    return "yellow";
  return "red";
}

function equalCount($row, $names) {
  $c = 0;
  foreach ($names as $n)
    if ($row[$n] == $_POST[$n])
      $c++;
  return $c;
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
  $blue_cnt = 0;

  /* 幅 */
  $wid_color = biasGtColor($row, array('route-width', 'entrance-width'), 10);
  if($wid_color == 'blue')
    $blue_cnt ++;
  $detail['width'] = $wid_color;
  $score += color_score($wid_color);

  /* 段差 */
  $bump_color = biasLtColor($row, array('entrance-bump', 'road-to-entrance-bump'), 3);
  if($bump_color == 'blue')
    $blue_cnt ++;
  $detail['bump'] = $bump_color;
  $score += color_score($bump_color);

  /* 出入口 */
  $_POST['auto-door'] = 'true';
  if (trueCount($row, array('auto-door', 'slide-door', 'double-door')) > 1)
    $door_color = 'blue';
  else
    $door_color = 'red';
  if($door_color == 'blue') $blue_cnt ++;
  $detail['entrance'] = $door_color;
  $score += $door_score($door_color);

  /* エレベータ */
  $_POST['elevator'] = $list_elevator[sc_elevator];
  $elv_color = biasLtColor($row, array('elevator'), 0);
  if($elv_color == 'blue') $blue_cnt ++;
  $detail['elevator'] = $elv_color;
  $score += $elv_score($elv_color);


  /* 階段 */
  if ($_POST['with-banister'] == 'true' &&
      $row['with-banister'] == 'true')
    $stair_color = 'blue';
  else
    $stair_color = 'red';
  if ($stair_color == 'blue') $blue_cnt ++;
  $stair_score = color_score($stair_color);
  $detail['stair'] = $stair_color;
  $score += $stair_score($stair_color);


  /* トイレ */
  $_POST['toilet'] = $list_toilet[sc_toilet];
  $toilet_color = biasLtColor($row, array('toilet'), 0);
  if($toilet_color == 'blue') $blue_cnt ++;
  $detail['toilet'] = $toilet_color;
  $score += color_score($toilet_color);


  /* ベビー */
  $baby_count =
    trueCount($row, array('toilet-with-ostomate',
                          'toilet-with-audio-assist',
                          'omutsu-koukan',
                          'hot-water'));
  if ($baby_count >= 3)
    $baby_color = 'blue';
  elseif ($baby_count >= 1)
    $baby_color = 'yellow';
  else
    $baby_color = 'red';
  if($baby_color == 'blue') $blue_cnt ++;
  $detail['for-baby'] = $baby_color;
  $score += $baby_score($baby_color);

  /* 駐車場 */
  $parking_color = biasLtColor($row, array('parking', 'parking-to-entrancet-bump'), 0);
  if ($parking_color == 'blue') $blue_cnt ++;
  $detail['parking'] = $parking_color;
  $score += $parking_score($parking_color);

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
          /*
            'infoImage' =>
            'image'     =>
          */
          'score'    => $store_score,
          'bluemark' => $blue_cnt);
}


/* ソートのルーチン */
function building_cmp($a, $b) {
  $av = $a['score']['value'];
  $bv = $b['score']['value'];
  if ($av == $bv)
    return 0;
  return $av < $bv ? 1 : -1;
}

/* スコア順にソート */
usort($store_data, "building_cmp");

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
function color_score($a){
  switch($a){
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
  if( $a >= 15){
    return 'blue';
  }elseif( $a >= 8){
    return 'yellow';
  }else {
    return 'red';
  }
}

/* response.php?dump でアクセスした場合 */
if (array_key_exists('dump', $_GET))
  var_export($result_data);
else
  echo json_encode($result_data);

?>