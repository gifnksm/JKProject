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
    <FORM method="post" action="auth_test2.php">
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
              'users' => array(
                             'hoge'=>'hogepass',
                         )
        );

$auth = new Auth("Array", $params, "loginFormHtml");
$auth->start();
if ($auth->checkAuth()) 
{
    echo <<<LOGGEDIN
<HTML>
<HEAD><TITLE>ログイン成功!</TITLE></HEAD>
<BODY>

<H3>ログイン成功</H3>
<BR>

</BODY>
</HTML>
LOGGEDIN;
}

?>
