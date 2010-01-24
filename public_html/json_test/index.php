<?php
require 'DB.php';

$sql = <<<SQL
    SELECT c.id,c.name,c.age,g.name,c.prefecture,c.telephone,c.company 
    FROM main_customer AS c,gender AS g 
    WHERE c.gender=g.id 
        AND c.age BETWEEN ? AND ?
SQL;

$name_j = $_SESSION['n'];
$pass_j = $_SESSION['p'];
$id_j = $_SESSION['i'];

$db = DB::connect("mysql://jkp:jkproject@jkproject.localhost/login_db");
$db->query("SET NAMES 'uft-8'");

if (DB::isError($db))
{
    echo "データベースに接続できませんでした";
}
else
{
    $stmt = $db->prepare($sql);

    $rs = $db->execute($stmt, array($name_x, $pass_x,id_x));
    while ($row = $rs->fetchRow())
    {
        echo    'id: ' . $row[0] . 
                ' name: ' . $row[1] . 
                ' age: ' . $row[2] . 
                ' gender: ' . $row[3] . 
                ' pref: ' . $row[4] . 
                ' tel: ' . $row[5] . 
                ' company: ' . $row[6] . 
                "<BR/>\n";
    }
}

?>





header('Content-type: text/plain; charset-utf-8');
session_start();
$login = ($_SESSION['n'] != "" || $_SESSION['p'] != "");
$data = array("login" => $login);

if($login )
{
 $data["name"] = $_SESSION["n"];
}

echo json_encode($data);





header("Content-type: text/plain; charset-utf-8");
$val = array(
             array(
                   "title" => "駐車場",
                   "icon" => "/resource/image/icon/parking.png",
                   "item" =>
                   array(
                         array("type" => "radio",
                               "name" => "parking",
                               "defaultValue" => "non-parking")
)
                   ),
             array(
                   "bbbb" => 3,
                   "vvvv" => 5
                   ));

$val = array(
             "searchTerm" => "hoge",
             "result" => array(
                               array("id" => 0,
                                     "name" => "四川屋台")
                               )
             );

$login = true;
if ($login) {
  $name = "hogehoge";
  $val = array("login" => true, "name" => $name);
} else {
  $val = array("login" => $login);
}

echo json_encode($val);


/* [
   {"title": "駐車場",
    "icon": "/resource/image/icon/parking.png",
    "items": [
      {"type": "radio",
       "name": "parking",
       "defaultValue": "non-parking",
       "selections": [
         {"title": "<em>幅350cm以上</em>の障がい者用駐車場がある",
          "value": "over-350cm"},
         {"title": "障がい者用駐車場がある",
          "value": "under-350cm"},
         {"title": "駐車場がある",
          "value": "for-normal"},
         {"title": "駐車場の有無にはこだわらない",
          "value": "non-parking"}
       ]
      }
    ]
   },
   {"title": "建物の主な出入り口",
    "icon": "/resource/image/icon/entrance.png",
    "items": [
      {"type": "radio",
       "name": "entrance-dansa",
       "defaultValue": "without-slope",
       "selections": [
         {"title": "段差<span class=\"weak\">（2cmを超える高低差）</span>がない",
          "value": "without-dansa"},
         {"title": "段差<span class=\"weak\">（2cmを超える高低差）</span>がない，またはスロープ<span class=\"weak\">（幅90cm以上）</span>がある",
          "value": "with-slope"},
         {"title": "段差・スロープの有無にはこだわらない",
          "value": "without-slope"}
       ]
      },
      {"type": "checkbox",
       "title": "出入口の幅が80cm以上である",
       "name": "entrance-width-over-80cm",
       "checked": false
      }
     ]
   },
   {"title": "建物の主な出入口までの通路",
    "icon": "/resource/image/icon/outside-to-entrance.png",
    "items": [
      {"type": "checkbox",
       "title": "<em>歩道</em>から施設の出入口まで段差<span class=\"weak\">（2cmを超える高低差）</span>がない",
       "name": "load-to-entrance-without-dansa",
       "checked": false},
      {"type": "checkbox",
       "title": "<em>障害者用駐車場</em>から施設の出入口まで段差<span class=\"weak\">（2cmを超える高低差）</span>がない",
       "name": "parking-to-entrance-without-dansa",
       "checked": false},
      {"type": "checkbox",
       "title": "<em>歩道</em>から施設の出入口まで視覚障害者誘導用ブロックがある",
       "name": "parking-to-entrance-with-block",
       "checked": false}
    ]
   },
   {"title": "建物内の案内",
    "icon": "/resource/image/icon/assist.png",
    "items": [
      {"type": "checkbox",
       "title": "音声案内がある",
       "name": "with-audio-assist",
       "checked": false},
      {"type": "checkbox",
       "title": "点字による案内表示がある",
       "name": "with-braille-assist",
       "checked": false}
    ]
   },
   {"title": "トイレ",
    "icon": "/resource/image/icon/toilet.png",
    "items": [
      {"type": "radio",
       "name": "toilet-type",
       "defaultValue": "normal-toilet",
       "selections": [
         {"title": "障害者用トイレ（男女別）がある",
          "value": "disabled-toilet-separate"},
         {"title": "障害者用トイレ（男女共用）がある",
          "value": "disabled-toilet-unisexed"},
         {"title": "トイレの種類にはこだわらない",
          "value": "normal-toilet"}
       ]
      },
      {"type": "checkbox",
       "title": "トイレ内がオストメイト対応になっている",
       "name": "toilet-with-ostomate",
       "checked": false
      },
      {"type": "checkbox",
       "title": "トイレ内にベビーベットがある",
       "name": "toilet-with-baby-bed",
       "checked": false
      },
      {"type": "checkbox",
       "title": "トイレ内におむつ交換シートがある",
       "name": "toilet-with-omutsu",
       "checked": false
      },
      {"type": "checkbox",
       "title": "トイレの場所を知らせる音声誘導装置がある",
       "name": "toilet-with-audio-assist",
       "checked": false
      }
    ]
   },
   {"title": "エレベーター",
    "icon": "/resource/image/icon/elevator.png",
    "items": [
      {"type": "radio",
       "name": "elevator",
       "defaultValue": "without-elevator",
       "selections": [
         {"title": "車いす対応エレベーターがある<br /><span class=\"weak\">（出入口幅80cm以上、制御装置高1メートル程度、定員11人乗り程度以上）</span>",
          "value": "with-large-elevator"},
         {"title": "エレベーターがある",
          "value": "with-normal-elevator"},
         {"title": "エレベーターの有無にはこだわらない",
          "value": "without-elevator"}
       ]
      }
    ]
   },
   {"title": "授乳及びおむつ交換場所",
    "icon": "/resource/image/icon/for-baby.png",
    "items": [
      {"type": "checkbox",
       "title": "授乳及びおむつ交換場所がある",
       "name": "omutsu-koukan",
       "checked": false
      },
      {"type": "checkbox",
       "title": "温水湯沸器又はポットが置いてある",
       "name": "hot-water",
       "checked": false
      }
    ]
   },
   {"title": "宿泊施設",
    "icon": "/resource/image/icon/accommodation.png",
    "items": [
      {"type": "checkbox",
       "title": "車いす対応客室<span class=\"weak\">（入口幅80cm以上、障害者用トイレ）</span>がある",
       "name": "kurumaisu-room",
       "checked": false
      },
      {"type": "checkbox",
       "title": "車いすで大浴場まで乗り入れ可能である",
       "name": "kurumaisu-daiyokujo",
       "checked": false
      },
      {"type": "checkbox",
       "title": "レストランまで車いすで行って食事ができる",
       "name": "kurumaisu-restaurant",
       "checked": false
      }
    ]
   },
   {"title": "興行施設",
    "icon": "/resource/image/icon/enterprise.png",
    "items": [
      {"type": "checkbox",
       "title": "車いす用観覧席がある",
       "name": "kurumaisu-kanranseki",
       "checked": false
      }
    ]
   },
   {"title": "通信設備",
    "icon": "/resource/image/icon/commu-equip.png",
    "items": [
      {"type": "checkbox",
       "title": "車いす用<span class=\"weak\">（高さ70cm程度）</span>の公衆電話がある",
       "name": "kurumaisu-phone",
       "checked": false
      },
      {"type": "checkbox",
       "title": "難聴者用公衆電話<span class=\"weak\"（音量調整装置のあるもの）</span>がある",
       "name": "nancho-phone",
       "checked": false
      }
    ]
   },
   {"title": "その他",
    "icon": "/resource/image/icon/others.png",
    "items": [
      {"type": "checkbox",
       "title": "飲食施設で点字のメニューがある",
       "name": "braille-menu",
       "checked": false
      },
      {"type": "checkbox",
       "title": "障害者向け自動販売機（ジュース等）がある",
       "name": "celler-machine-for-disabled",
       "checked": false
      },
      {"type": "checkbox",
       "title": "自動販売機（ジュース等）に点字の表示がある",
       "name": "celler-machine-with-braille",
       "checked": false
      }
    ]
   }
 ]
 */
?>