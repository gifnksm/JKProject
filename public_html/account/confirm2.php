<HTML>
<HEAD>
<TITLE>DBに登録</TITLE>
</HEAD>
<BODY>
<DIV align="center">
<DIV align="left">
               <h1><img src="/resource/image/logo_small.png" Border="0" with="200" height="50"
                        alt="バリアフリーマップ検索" /></h1>
                </td><td style="text-align: right;">
</DIV>

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
if($gender_ == ""){
$gender_ = "N";
}
$birthdate_y = $_POST["date"][Y];
$birthdate_m = $_POST["date"][m];
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


if($usernamae_ != "" || $password_ != "")
{

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
$id_=mysql_insert_id();

if($result){
session_start();
$_SESSION['n'] = $username_;
$_SESSION['p'] = $password_;
$_SESSION['i'] = $id_;
	    echo '<H2>ようこそ、' ."$username_". 'さん</H2>';
	   echo 'これからもバリアフリーマップ検索をお楽しみください!<BR>';
echo '<H2>自動的にトップページに移動します</H2>';
echo '<meta http-equiv="Refresh" content="3; URL=/index.html">';

}
else{
	   echo '<H2>登録できませんでした</H2>';
}
}
else{
echo '<H2>登録できませんでした</H2>';
echo '<H2>もう一度始めからやり直してください</H2>';
echo '<H2>自動的にログインページに移動します</H2>';
echo '<meta http-equiv="Refresh" content="3; URL=/account/login.php">';
}
}
?>
<p><a href="/index.html">ホーム</a></p>
<A Href="javascript:history.go(-1)">1つ前に戻る</A>
</DIV>
</BODY>
</HTML>
