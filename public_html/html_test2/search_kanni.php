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
$keyword = $_POST['searchTerm']

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
$sc_toilet-type =  $_POST['toilet-type'];
$sc_elevator = $_POST['elevator'];

$sc_route-width =  $_POST['route-width'];
$sc_entrance-width =  $_POST['entrance-width'];
$sc_entrance-bump = $_POST['entrance-bump'];
$sc_road-to-entrancet-bump =  $_POST['road-to-entrancet-bump'];
$sc_parking-to-entrancet-bump =  $_POST['parking-to-entrancet-bump'];

/*関数利用しない（１）
$sc_parking-carport =  $_POST['parking-carport'] ;
$sc_with-slope =  $_POST['with-slope'];
$sc_slide-door =  $_POST['slide-door'];
$sc_double-door =  $_POST['double-door'];
$sc_auto-door =  $_POST['auto-door'];
$sc_road-to-entrance-with-block =  $_POST['road-to-entrance-with-block'];
$sc_with-barrier =  $_POST['with-barrier'];
$sc_slipper-floor =  $_POST['slipper-floor'];
$sc_help =  $_POST['help'];
$sc_with-audio-assist =  $_POST['with-audio-assist'];
$sc_with-braille-assist =  $_POST['with-braille-assist'];
$sc_guidemap =  $_POST['guidemap'];
$sc_toilet-with-ostomate =  $_POST['toilet-with-ostomate'];
$sc_toilet-with-audio-assist =  $_POST['toilet-with-audio-assist'];
$sc_stair-with-banister =  $_POST['stair-with-banister'];
$sc_omutsu-koukan =  $_POST['omutsu-koukan'];
$sc_hot-water =  $_POST['hot-water'];
$sc_kurumaisu-room =  $_POST['kurumaisu-room'];
$sc_kurumaisu-daiyokujo =  $_POST['kurumaisu-daiyokujo'];
$sc_kurumaisu-restaurant =  $_POST['kurumaisu-restaurant'];
$sc_kurumaisu-kanranseki =  $_POST['kurumaisu-kanranseki'];
$sc_kurumaisu-phone =  $_POST['kurumaisu-phone'];
$sc_nancho-phone =  $_POST['nancho-phone'];
$sc_braille-menu =  $_POST['braille-menu'];
$sc_vending-machine-for-disabled =  $_POST['vending-machine-for-disabled'];
$sc_vebding-machine-with-braille =  $_POST['vebding-machine-with-braille'];
*/



/* Edit Suda 15:40  mysql設定はまだ！！！！！！！！！！！！！！！ */

/* MySQLに接続 : @mysql_connect()とすると表示されるPHPもエラーメッセージを非表示にできる */
$link = mysql_connect("localhost", DB_USER, DB_PASS)
      or die("MySQLとの接続に失敗しました．");

/* 接続データベースを選択 */
mysql_select_db(DB_NAME)
	or die("データベースとの接続に失敗しました．");

/* クライアントのキャラクタセットをutf8に変更 */
mysql_query("SET NAME utf8")
	or die("クエリーとの接続に失敗しました．");


/* 以下でクエリーの作成 */

/* キーワード検索　＞＞　店名，住所，（カテゴリー）*/
   /*店名・住所・カテゴリーでの検索（部分一致検索）*/
	$query1 =    " tenmei LIKE '%" . $keyword .
	    	"%' AND adress LIKE '%" . $keyword .
	    	"%' AND category LIKE '%" . $keyword . "%'";

   /*クエリーを実行し．結果セットを取得*/
	$result1 = mysql_query("SELECT * FROM list_t WHERE" . $query1)
	or die("クエリーを実行できませんでした．");

//var_dump($list_parking($sc_parking))



/*データベースと検索条件をつなぐ関数 
	$data:カラム名　$search:検索変数
*/
	function translation($data){ 
		$search = $_POST['$data'];
		if($search == null ){
			return null;
		}else{
			return "　.'||$data  = ' .$search    "
		}
	}



/*条件検索*/
    $query2 = "parking  	<= " .$list_parking($sc_parking)
	."|| toilet 		<= " .$list_toilet(sc_toilet)
	."|| elevator		<= " .$list_elevator(sc_elevator) 
	."|| entrance-width 	<= " .$sc_entrance-width
	."|| route-width 	<= " .$sc_route-width
	."|| entrance-bump 	<= " .$sc_entrance-bump
	."|| road-to-entrance-bump 	<= " .$sc_road-to-entrance-bump
     	."|| parking-to-entrance-bump	<= " .$sc_parking-to-entrance-bump
/* translation関数利用 */
	translation( "parking-carport" );     
	translation( "with-slope" );     
	translation( "slide-door" );     
	translation( "double-door" );     
	translation( "auto-door" );     
	translation( "road-to-entrance-with-block" );     
	translation( "with-barrier" );     
	translation( "slipper-floor" );     
	translation( "help" );     
	translation( "with-audio-assist" );     
	translation( "with-braille-assist" );     
	translation( "guidemap" );     
	translation( "toilet-with-ostomate" );     
	translation( "toilet-with-baby-bed" );     
	translation( "toilet-with-baby-omutsu" );     
	translation( "toilet-with-audio-assist" );     
	translation( "with-banister" );     
	translation( "with-baby-çomutsu-koukan" );     
	translation( "with-baby-hot-water" );     
	translation( "kurumaisu-room" );     
	translation( "kurumaisu-daiyokujo" );     
	translation( "kurumaisu-rastaurant" );     
	translation( "kurumaisu-kanranseki" );     
	translation( "kurumaisu-phone" );     
	translation( "nancho-phone" );     
	translation( "braille-menu" );     
	translation( "vending-machine-for-disabled" );     
	translation( "vending-machine-with-braille" );     

/*関数利用しない（２）
	."|| parking-carport 	= " .$sc_parking-carport
	."|| with-slope 	= " .$sc_with-slope     	
    	."|| slide-door 	= " .$sc_slide-door
     	."|| double-door 	= " .$sc_double-door
     	."|| auto-door 		= " .$sc_auto-door
        ."|| road-to-entrance-with-block = " .$sc_road-to-entrance-with-block
     	."|| with-barrier 	= " .$sc_with-barrier 
	."|| slipper-floor	= " .$sc_slipper-floor
	."|| help		= " .$sc_help
	."|| with-audio-assist	= " .$sc_with-audio-assist
	."|| with-braille-assist= " .$sc_with-braille-assist
	."|| guidemap		= " .$sc_guidemap
	."|| toilet-with-ostomate	= " .$sc_toilet-with-ostomate
	."|| toilet-with-baby-bed	= " .$sc_toilet-with-baby-bed
	."|| toilet-with-baby-omutsu	= " .$sc_toilet-with-baby-omutsu
	."|| toilet-with-audio-assist	= " .$sc_toilet-with-audio-assist
	."|| with-banister	= " .$sc_with-banister
	."|| with-baby-çomutsu-koukan	= " .$sc_with-baby-çomutsu-koukan
	."|| with-baby-hot-water	= " .$sc_with-baby-hot-water
	."|| kurumaisu-room		= " .$sc_kurumaisu-room
	."|| kurumaisu-daiyokujo	= " .$sc_kurumaisu-daiyokujo
	."|| kurumaisu-rastaurant	= " .$sc_kurumaisu-rastaurant
	."|| kurumaisu-kanranseki	= " .$sc_kurumaisu-kanranseki
	."|| kurumaisu-phone	= " .$sc_kurumaisu-phone
	."|| nancho-phone	= " .$sc_nancho-phone
	."|| braille-menu	= " .$sc_braille-menu
	."|| vending-machine-for-disabled	= " .$sc_vending-machine-for-disabled
	."|| vending-machine-with-braille	= " .$sc_vending-machine-with-braille
 	."|| = " .$sc_
*/
    	";


/*クエリーを実行し．結果セットを取得*/
$result = mysql_query("SELECT * FROM result1 WHERE" . $query2)
	or die("クエリーを実行できませんでした．");

/*結果セットの行数を取得*/
$rows = mysql_num_rows($result);


/* 検索結果の格納場所 */
$store_data = array();

/* ループカウンター */
$r = 0;

/* 出力データ編集 */
while( $row = mysql_fetch_array($result) ){
	$detail = array();
	$score = 0;
	$blue_cnt =0;

	/* 幅 */
	$wid_color = icon_width($sc_route-width , $row['route-width'] , $sc_entrance-width , $row['entrance-width']);
	if($wid_color == 'blue') $blue_cnt ++;
	$wid_score = color_score($wid_color);

	$detail['width'] = array('color' => $wid_color,
				 'value' => $wid_score,
				 'message' => '判定の詳細');
	$score += $wid_score;

	/* 段差 */
	$bump_color = icon_bump(　$sc_entrance-bump , $row['entrance-bump'] ,
			　　　　　 $sc_road-to-entrance-bump , $row['road-to-entrance-bump']);
	if($bump_color == 'blue') $blue_cnt ++;
	$bump_score = color_score($bump_color);

	$detail['bump'] = array('color' => $bump_color,
				 'value' => $bump_score,
				 'message' => '判定の詳細');
	$score += $bump_score;

	/* 出入口 */
	$door_color = icon_entrance($_POST['slide-door'] , $_POST['double-door'] , $_POST['auto-door'],
				      $row['slide-door'] ,   $row['double-door'] ,   $row['auto-door']);
	if($door_color == 'blue') $blue_cnt ++;
	$door_score = color_score($door_color);

	$detail['entrance'] = array('color' => $door_color,
				 'value' => $door_score,
				 'message' => '判定の詳細');
	$score += $door_score;

	/* エレベータ */
	$elv_color = icon_elevator($list_elevator(sc_elevator) ,$row['elevator']);
	if($elv_color == 'blue') $blue_cnt ++;
	$elv_score = color_score($door_color);

	$detail['elevator'] = array('color' => $elv_color,
				 'value' => $elv_score,
				 'message' => '判定の詳細');
	$score += $elv_score;


	/* 階段 */
	$stair_color = icon_stair($_POST['with-banister'] , $row['with-banister']);
	if($stair_score == 'blue') $blue_cnt ++;
	$stair_score = color_score($stair_color);

	$detail['stair'] = array('color' => $stair_color,
				 'value' => $stair_score,
				 'message' => '判定の詳細');
	$score += $stair_score;


	/* トイレ */
	$toilet_color = icon_width($list_toilet(sc_toilet) , $row['toilet']);
	if($toilet_color == 'blue') $blue_cnt ++;
	$toilet_score = color_score($toilet_color);

	$detail['toilet'] = array('color'  => $toilet_color,
			      	  'value'  => $toilet_score,
				 'message' => '判定の詳細');
	$score += $toilet_score;


	/* ベビー */
	$baby_color = icon_baby($_POST['toilet-with-ostomate'] , $_POST['toilet-with-audio-assist'] ,
			　　　	$_POST['omutsu-koukan']	       , $_POST['hot-water'] ,
				$row['toilet-with-ostomate']   , $row['toilet-with-audio-assist'] ,
			        $row['omutsu-koukan'] 	       , $row['hot-water'] );
	if($baby_color == 'blue') $blue_cnt ++;
	$baby_score = color_score($baby_color);

	$detail['for-baby'] = array('color' => $baby_color,
				 'value' => $baby_score,
				 'message' => '判定の詳細');
	$score += $baby_score;


	/* 駐車場 */
	$parking_color = icon_width($_POST['parking'] , $row['parking'] ,
				    $_POST['parking-to-entrancet-bump'] , $row['parking-to-entrancet-bump']);
	if($parking_color == 'blue') $blue_cnt ++;
	$parking_score = color_score($parking_color);

	$detail['parking'] = array('color' => $parking_color,
				   'value' => $parking_score,
				 'message' => '判定の詳細' ); 
	$score += $parking_score;

	$store_score = array('color' => store_color($score) , 'value' => $score)


	/*店のカラー，スコア，青マークの数，ディテールを格納*/
	$store_data[$r] = array(
				'id'	=> $row['id'],
				'name'	=> $row['tenmei'],
				'lat'	=> $row['id_ns'],
				'lng'	=> $row['gps_ew'],
				'addr'	=> $row['address'],
				'tel'	=> $row['tel'],
/*
				'infoImage'	=>
				'image'	=>
*/				
				'score'    => $store_score,
				'bluemark' => $blue_cnt,
				'detail'   => $detail );
	
	/* カウンターを進める */
	$r ++;
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
		if( $a >= 18){
			return 'blue';
		}elseif( $a >= 10){
			return 'yellow';
		}else
			return 'red';	
		}
	}



/* %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	アイコン色づけのための評価関数郡
   %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */
    
/*
	１．入口と店内通路の幅から'幅'の判定
	$a:項目Aの検索条件 vs $b:項目Aのデータ , $c:項目Bの検索条件 vs $d:項目Bのデータ ,$e：バイアス
*/
	function icon_width($a,$b,$c,$d,$e){
		if( $a+$e >= $b && $c+$e >= $d)
		{
			return "blue";
		}elseif( $a <= $b && $c <= $d)
		{
			return "red";
		}else{
			return "yellow";
		} 
	}

/*
	２．入口と敷居の段差から'段差'の判定
	$a:項目Aの検索条件 vs $b:項目Aのデータ , $c:項目Bの検索条件 vs $d:項目Bのデータ ,$e：バイアス
*/
	function icon_bump($a,$b,$c,$d,$e){
		if( $a+$e <= $b && $c+$e <= $d)
		{
			return "blue";
		}elseif( $a >= $b && $c >= $d)
		{
			return "red";
		}else{
			return "yellow";
		} 
	}


/*
	３．出入口の形状
	$a,$b,$c:検索条件 vs $d,$e,$f:データ（同順）
*/
	function icon_entrance($a,$b,$c,$d,$e,$f){
		if( $a != $d &&  $b != $e && $c != $f){
			return "red";
		}else{
			return "blue";
		}
	}

/*
	４．６．エレベータの種類，障害用トイレも兼用
	$a:検索条件 vs $b:データ
*/
	function icon_elevator($a,$b){
		if( $a <= $b){
			return "blue";
		}else{
			return "red";
		}
	}

/*
	５．階段が対応しているか
	$a:検索条件 vs $b:データ
*/
	function icon_stair($a,$b){
		if( $a == $b){
			return "blue";
		}else{
			return "red";
		}
	}

/*
	７．ベビー対応施設
	$a,$b,$c,$d:検索条件　vs　$e,$f,$g,$h:データ（同順）
*/
	function icon_baby($a,$b,$c,$d,$e,$f,$g,$h){
		$count = 0;
		if( $a == $e ) $count++;
		if( $b == $f ) $count++;
		if( $c == $g ) $count++;
		if( $d == $h ) $count++;
		if(count >= 3 ){
			return "blue";
		}elseif(count >= 1 ){
			return "yellow";
		}else{
			return "red";
		}
	}

/*
	８．駐車場の大きさと店までの段差
	$a,$b:検索条件 vs $c,$d:データ（同順） a,c:駐車場の大きさ，b,d:段差
*/
	function icon_parking($a,$b,$c,$d){
		if( $a > $c && $b > $d){
			return "blue";
		}elseif( $a < $b || $c < $d){
			return "red";
		}else{
			return "yellow";
		}
	}


/* response.php?dump でアクセスした場合 */
if (array_key_exists('dump', $_GET))
  var_export($result_data);
else
  echo json_encode($result_data);

?>