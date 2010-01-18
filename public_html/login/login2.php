<?php

require_once "Auth.php";

function loginFormHtml($username = null, $status = null)
{
    echo <<<LOGINFORM
<HTML>
<HEAD><TITLE>ログインフォーム</TITLE></HEAD>
<BODY>

<H2>ログインしてください</H2>
<DIV align="center">
    <FORM method="post" action="">
        ユーザ名: <INPUT type="text" name="username"><BR>
        パスワード: <INPUT type="password" name="password"><BR>
        <INPUT type="submit">
    </FORM>
</DIV>

</BODY>
</HTML>
LOGINFORM;
}

$params = array(
        'cryptType'=>'none',
        'dsn'=>'mysql://php:password@jkproject.localhost/php_sample_db',
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
<TITLE>例</TITLE>
</HEAD>
<BODY>

<H3>ログイン成功</H3>
<BR>

</BODY>
</HTML>
LOGGEDIN;
}

?>
