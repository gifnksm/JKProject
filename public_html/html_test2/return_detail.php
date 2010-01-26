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
$query = sprintf("SELECT * FROM store_info WHERE id = %d", $_POST['id']);

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
$row = mysql_fetch_assoc($result);
$detail = array();
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
$baby_count = trueCount($row, array('toilet-with-baby-bed',
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


$scoreValue = 0;
$scoreMax = 0;
foreach ($detail as $value) {
  $scoreMax ++;
  if ($value == 'blue')
    $scoreValue ++;
}


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

/*######################################################################################*/

/* 店舗情報 */
$info_store = array( 'name'             => $row['tenmei'],
                     //      'access'        => $row[''],
                     'tel'           => $row['tel'],
                     'fax'           => $row['fax'],
                     'adress'        => $row['adress'],
                     'open'  => $row['open'],
                     'shop-holiday' => $row['shop-holiday'],
                     'comment'       => $row['comment'],
                     //      'url'           => $row['url'],
                     'date'          => $row['date']
                     );

/*  施設データを羅列　*/
$bfinfo = array();


/*############################################################
  各項目の詳細情報表示関数　（TrueFalse），（Int）型
  ##########################################################*/

/* item内小項目ごとの情報TF */
function itemTF($a){
  return $item_a = array( "name" => $a,
			  "value" => $row[$a],
			  "color" => item_colorTF($a)
			  );
}
/* item内,項目の色分け(True-False) */
function item_colorTF($a){
  if( $_POST['$a'] = null){
    return "black";
  }else{
    if( $row[$a] == $_POST[$a]){
      return "blue";
    }else{
      return "red";
    }
  }
}
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function itemLINT($a){
  return $itemLINT = array( "name"=>$a,
			    "value"=>$row['$a'],
			    "color"=>item_colorLINT($a)
			    );
}


/* INT Larger:データベース値が大きいと青*/
function item_colorLINT($a){
  if( $_POST[$a] = null){
    return "black";
  }else{
    if( $row[$a] >= $_POST[$a]){
      return "blue";
    }else{
      return "red";
    }
  }
}
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function itemSINT($a){
  return $itemSINT = array( "name"=>$a,
			    "value"=>$row[$a],
			    "color"=>item_colorSINT($a)
			    );
}


/* INT Smaller：データベース値が大きいと赤*/
function item_colorSINT($a){
  if( $_POST[$a] = null){
    return "black";
  }else{
    if( $row[$a] <= $_POST[$a]){
      return "blue";
    }else{
      return "red";
    }
  }
}

/*########################################################*/

//駐車場
$Inv_parking = array_flip($list_parking);

//parking_value
if( $row['parking'] != null){
  $parking_value = $Inv_parking[$_POST['parking']];
}else{
  $parking_value = null;
}

//parking_color
if( $_POST[$a] = null){
  $parking_color="black";
}else{
  if( $row[$a] <= $_POST[$a]){
    $parking_color="blue";
  }else{
    $parking_color="red";
  }
}


$bfinfo[ ] = array( "title"=>"駐車場",
		    "icon"=>"/resouce/image/icon/arking.png",
		    "items"=>
		    array(array( "name" =>"parking",
				 "value"=>$parking_value,
				 "color"=>$parking_color),
			  itemTF("parking-carport"))
		    );


//建物の主な出入り口
$bfinfo[ ] = array( "title"=>"建物の主な出入り口",
		    "icon"=>"/resouce/image/icon/entrance.png",
		    "items"=>array(itemSINT('entrance-bump'),
				   itemTF('slide-door'),
				   itemTF('double-door'),
				   itemTF('auto-door'),
				   itemSINT('road-to-entrance-bump'),
				   itemSINT('parking-to-entrance-bump'),
				   itemTF('road-to-entrance-with-block')));


//建物内の移動
$bfinfo[ ] = array( "title"=>"建物内の移動",
		    "icon"=>"/resouce/image/icon/mobility.png",
		    "items"=>array(itemLINT('route-width'),
				   itemTF('with-barrier'),
				   itemTF('slipper-floor')));

//建物の案内
$bfinfo[ ] = array( "title"=>"建物の主な出入り口",
		    "icon"=>"/resouce/image/icon/assist.png",
		    "items"=>array(itemTF('help'),
				   itemTF('with-audio-assist'),
				   itemTF('with-braille-assist'),
				   itemTF('guidemap')));


//トイレ
$Inv_toilet_type = array_flip($list_toilet);

//toilet_value
if( $row['toilet-type'] != null){
  $toilt_value = $Inv_toilet_type[$_POST['toilet-type']];
}else{
  $toilet_value = null;
}

//toilet_color
if( is_null($_POST['toilet-type'])){
  $toilet_color = "black";
} elseif ( $row['toilet-type'] >= $_POST['toilet-type']){
  $toilet_color = "blue";
} else {
  $toilet_color = "red";
}

$bfinfo[ ] = array( "title"=>"トイレ",
		    "icon"=>"/resouce/image/icon/toilet.png",
		    "items"=>array(array( "name" =>"toilet-type",
					  "value"=>$toilet_value,
					  "color"=>$toilet_color),
				   itemTF('toilet-with-ostomate'),
				   itemTF('toilet-with-baby-bed'),
				   itemTF('toilet-with-omutsu'),
				   itemTF('toilet-with-audio-assist')));


//階段
$bfinfo[ ] = array( "title"=>"階段",
		    "icon"=>"/resouce/image/icon/stair.png",
		    "items"=>array(itemTF('stair-with-banister')));


//エレベーター
$Inv_elevator = array_flip($list_elevator);

//elevator_value
if( $row['elevator'] != null){
  $elevator_value = $Inv_elevator[$_POST['elevator']];
}else{
  $elevator_value = null;
}

//elevator_color
if ( is_null($_POST['elevator'])){
  $elevator_color = "black";
} elseif ( $row['elevator'] >= $_POST['elevator']){
  $elevator_color = "blue";
} else {
  $elevator_color = "red";
}

$bfinfo[ ] = array( "title"=>"エレベーター",
                    "icon"=>"/resouce/image/icon/elevator.png",
                    "items"=>array(array( "name" =>"elevator",
					  "value"=>$elevator_value,
					  "color"=>$elevator_color)));


//授乳およびおむつ交換場所
$bfinfo[ ] = array( "title"=>"授乳およびおむつ交換場所",
		    "icon"=>"/resouce/image/icon/baby.png",
		    "items"=>array(itemTF('omutsu-koukan'),
				   itemTF('hot-water')));


//宿泊施設
$bfinfo[ ] = array( "title"=>"宿泊施設",
		    "icon"=>"/resouce/image/icon/accommodation.png",
		    "items"=>array(itemTF('kurumaisu-room'),
				   itemTF('kurumaisu-daiyokujo'),
				   itemTF('kurumaisu-restaurant')));

//興行施設
$bfinfo[ ] = array( "title"=>"興行施設",
		    "icon"=>"/resouce/image/icon/enterprise.png",
		    "items"=>array(itemTF('kurumaisu-kanranseki')));

//通信設備
$bfinfo[ ] = array( "title"=>"通信設備",
		    "icon"=>"/resouce/image/icon/eommu-equip.png",
		    "items"=>array(itemTF('kurumaisu-phone'),
				   itemTF('nancho-phone')));

//その他
$bfinfo[ ] = array( "title"=>"その他",
		    "icon"=>"/resouce/image/icon/others.png",
		    "items"=>array(itemTF('braille-menu'),
				   itemTF('vending-machine-for-disabled'),
				   itemTF('vending-machine-with-braille')));


/*%%%%%%%%%%%%%%%%%%%%%%%%
        結果表示
%%%%%%%%%%%%%%%%%%%%%%%*/
$result_data =
  array("category" => array("ids" => $cat_ids,
                            "names" => array_combine($cat_ids, $cat_names)),
        "info" => $info_store,
	"scoreValue" => $scoreValue,
	"scoreMax" => $scoreMax,
        "score"=>$detail,
	"bfinfo"=>$bfinfo
	);




/* response.php?dump でアクセスした場合 */
if (array_key_exists('dump', $_GET))
  var_export($result_data);
else
  echo json_encode($result_data);

?>