<?php

//require_once "Auth.php";

$namae = $_POST['username'];
$pass = $_POST['password'];

echo "$namae";
echo "$pass";

//********************************************
//ログインできない時
//********************************************
if($namae == null || $pass == null ) {
echo <<<EOT
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;CHARSET=utf-8">
</head>
<body>
<DIV align="center">
ユーザー名もしくはパスワードが正しくありません。
<p><a href="/../html_test2/index.html">HOME</a></p>
<A Href="javascript:history.go(-1)">1つ前に戻る</A>
</DIV>
EOT;
exit;
}

//********************************************
//ログインできた時
//********************************************

require_once "Auth.php";


function loginFormHtml($username = null, $status = null)
{
    echo<<<LOGINFORM
<HTML>
<HEAD><TITLE>ログイン</TITLE></HEAD>
<BODY>
<DIV align="center">
<table><tr><td style="width: 12em;">
               <h1><img src="/resource/image/logo_small.png" with="200" height="50"
                        alt="バリアフリーマップ検索" /></h1>
                </td><td style="text-align: right;">
<H2>ここから先はログインが必要です</H2>
    <FORM method="post" action="login2.php">
        ユーザ名: <INPUT type="text" name="username"><BR>
        パスワード: <INPUT type="password" name="password"><BR>
        <INPUT type="submit">
    </FORM>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<p><a href="account.php">new account</a></p>
<p><a href="/../html_test2/index.html">HOME</a></p>
<A Href="javascript:history.go(-1)">1つ前に戻る</A>
</DIV>
</BODY>
</HTML>
LOGINFORM;
}




/*
session_start();

$db = DB::connect("mysql://jkp:jkproject@jkproject.localhost/login_db");
mysql_query("SET NAMES 'utf-8'", $conn);
mysql_select_db("login_db", $conn);
$sql = "SELECT * FROM credentials";
$rs = mysql_query($sql, $conn);
echo "<TABLE border=1>";
*/


$params = array(
        'cryptType'=>'none',
        'dsn'=>'mysql://jkp:jkproject@jkproject.localhost/login_db',
        'table'=>'credentials',
        'usernamecol'=>'username',
        'passwordcol'=>'password'
    );

$auth = new Auth("DB", $params, "loginFormHtml");
$auth->start();

if ($auth->checkAuth())
{
    echo <<<LOGGEDIN
<HTML>
<HEAD>
<TITLE>確認</TITLE>
</HEAD>
<BODY>

<H3>ログイン成功</H3>
<BR>

<p><a href="/../html_test2/index.html">HOME</a></p>
<A Href="javascript:history.go(-1)">1つ前に戻る</A>

</BODY>
</HTML>
LOGGEDIN;
}



/*
$sql = "select * from myuser where uid='$fLoginID'";
$r = pg_exec($SessDBConn, $sql);
if( pg_result($r, 0, 0) != 0 ) {
  session_destroy();
  print "そのログインIDはすでに使用されています。";
  exit;
}

$sql = "insert into myuser (uid, password, color) ";
$sql.= " values ('$fLoginID','$fPassword','$fBGColor')";
$r = pg_exec($SessDBConn, $sql);
// 下記をセッション変数に登録
session_register("sLoginID");
session_register("sPassword");
// セッション変数の値を更新する。
$sLoginID = $fLoginID;
$sPassword = $fPassword;
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html;CHARSET=EUC-JP">
</head>
<body>
登録しました。<br><br>
<a href="contents.php">コンテンツへ</a>
<p><a href="/../html_test2/index.html">HOME</a></p>
<A Href="javascript:history.go(-1)">1つ前に戻る</A>
</body></html>
*/

?>