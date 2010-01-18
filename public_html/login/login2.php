<?php

require_once "Auth.php";

function loginFormHtml($username = null, $status = null)
{
    echo <<<LOGINFORM
<HTML>
<HEAD><TITLE>���O�C���t�H�[��</TITLE></HEAD>
<BODY>

<H2>���O�C�����Ă�������</H2>
<DIV align="center">
    <FORM method="post" action="">
        ���[�U��: <INPUT type="text" name="username"><BR>
        �p�X���[�h: <INPUT type="password" name="password"><BR>
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
<TITLE>��</TITLE>
</HEAD>
<BODY>

<H3>���O�C������</H3>
<BR>

</BODY>
</HTML>
LOGGEDIN;
}

?>
