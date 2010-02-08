<?php
$namae = $_POST['username'];
$pass = $_POST['password'];

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
<p><a href="/index.html">ホーム</a></p>
<A Href="javascript:history.go(-1)">1つ前に戻る</A>
</DIV>
EOT;
exit;
}

//********************************************
//ログインできた時
//********************************************
/* require_once "Auth.php"; */
require_once "DB.php";

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
    <FORM method="post" action="">
        ユーザ名: <INPUT type="text" name="username"><BR>
        パスワード: <INPUT type="password" name="password"><BR>
        <INPUT type="submit">
    </FORM>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<p><a href="account.php">new account</a></p>
<p><a href="/index.html">HOME</a></p>
<A Href="javascript:history.go(-1)">1つ前に戻る</A>
</DIV>
</BODY>
</HTML>
LOGINFORM;
}

$params = array(
        'cryptType'=>'none',
        'dsn'=>'mysql://jkp:jkproject@jkproject.localhost/login_db',
        'table'=>'credentials',
        'usernamecol'=>'username',
        'passwordcol'=>'password'
    );

$db = DB::connect("mysql://jkp:jkproject@jkproject.localhost/login_db");
$db->query("SET NAMES 'utf-8'");

if (DB::isError($db)) {
  $row = array();
} else {
  $st =$db->prepare("SELECT * FROM credentials where username=? and password=?");
  $rs = $db->execute($st, array($namae, $pass));
  $row = $rs->fetchRow(DB_FETCHMODE_ASSOC);
}

/* ログイン成功 */
if (count($row) > 1)
{
  session_start();
  $_SESSION['n'] = $namae;
  $_SESSION['p'] = $pass;
  $_SESSION['i'] = $row["id"];
  echo <<<LOGGEDIN
<HTML>
<HEAD>
<TITLE>確認</TITLE>
<meta http-equiv="Refresh" content="2; URL=/index.html">
<style type="text/css">
#anime {
  position: absolute;
  bottom: 0; right: 0;
  text-indent: 200px;
  overflow: hidden;
  width: 200px;
}
</style>
<script>
  var div = null;
  var i = 0;
  var speed = 20;
  var l = 200 / speed;
  function animate() {
    if (i <= l) {
      div.style.textIndent = (l - i) * speed + 'px';
    } else {
      div.style.right = (i - l) * speed + 'px';
    }
    i++;
  }
  function startAnimation() {
    div = document.getElementById('anime');
    setInterval(animate, 10);
  }
  setTimeout(startAnimation, 1400);
</script>
</HEAD>
<BODY>
<DIV align="center"> 
<H3>ログインしました。</H3>
<H2>自動的にトップページに戻ります</H2>
<p><a href="/index.html">ホーム</a></p>
</DIV>
<div id="anime"><img src="image/car_anime.gif" width="200" height="200"/></div>
</BODY>
</HTML>
LOGGEDIN;
}
else {
  echo "ユーザーが登録されていないか、入力情報に不正があります。";
}
?>