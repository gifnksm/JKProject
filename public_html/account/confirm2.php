<!--
***********************************************
**  DBに登録
**
**                                  by ohno
**                                  2010/01/20
***********************************************
-->
<HTML>
<HEAD>
<TITLE>DBに登録</TITLE>
</HEAD>
<BODY>
<DIV align="center">
<?php
require 'DB.php';


$db = DB::connect("mysql://jkp:jkproject@jkproject.localhost/login_db");
$db->query("SET NAMES 'utf-8'");


if (DB::isError($db))
{
    echo "データベースにアクセスできませんm(__)m";
}
else
{


/*
***************************************************
** confirm.phpから値を読み取り変数に代入
***************************************************
*/
$username_ = $_POST['username'];
$password_ = $_POST['password_x'];
$email_ = $_POST['email'];
$mobilemail_ = $_POST['mobilemail'];
$gender_ = $_POST["gender"][0];
$birthdate_y = $_POST["date"][Y];
$birthdate_m = $_POST["date"][M];
$birthdate_d = $_POST["date"][d];
$birthdate_ = "$birthdate_y"."/" ."$birthdate_m". "/"."$birthdate_d";
$prefecture_ = $_POST['prefecture'];
//$city_ = $_POST['city'];
$width_ = $_POST['width'];
$rank1_ = $_POST['rank1'];
$rank2_ = $_POST['rank2'];
$slide_door = $_POST["door"][0];
$double_door = $_POST["door"][1];
$auto_door = $_POST["door"][2];
$elevator_ = $_POST["elevator"][0];
$step_ = $_POST["step"][0];
$toilet_ = $_POST["toilet"][0];
$baby_ = $_POST["baby"][0];


/*
***************************************************
** MySQL に書き込む
***************************************************
*/

$sql = <<<SQL
    INSERT INTO credentials
	    (username,password,email,mobilemail,gender,birthdate,prefecture,width,rank1,rank2,slidedoor,doubledoor,autodoor,elevator,step,toilet,baby)
       VALUES ('$username_','$password_','$email_','$mobilemail_','$gender_','$birthdate_','$prefecture_','$width_','$rank1_','$rank2_','$slide_door','$double_door','$auto_door','$elevator_','$step_','$toilet_','$baby_');
SQL;

$result = mysql_query($sql);

if($result){
	    echo '<H2>ようこそ、' ."$username_". 'さん</H2>';
	   echo 'これからもバリアフリーマップ検索をお楽しみください!<BR>';
}
else{
	   echo '<H2>登録できませんでした</H2>';
}

}
?>
<p><a href="/../html_test2/index.html?no=1">HOME</a></p>
<A Href="javascript:history.go(-1)">1つ前に戻る</A>
</DIV>
</BODY>
</HTML>
