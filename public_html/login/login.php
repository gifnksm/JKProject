<?php
/*
*******************************************************
**	ログイン画面＋新規アカウント登録画面
**
**					by ohno
**					2010/01/20
*******************************************************
*/
require_once "Auth.php";
require_once 'HTML/QuickForm.php';

//$form = new HTML_QuickForm('myForm','POST','confirm.php');

function loginFormHtml($username = null, $status = null)
{
    echo <<<LOGINFORM
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
<p><a href="/../html_test2/index.html">HOME</a></p>

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


</BODY>
</HTML>
LOGGEDIN;
}
?>
