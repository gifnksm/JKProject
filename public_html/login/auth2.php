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
    <FORM method="post" action="auth2.php">
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
              'users' => array(
                             'hoge'=>'hogepass',
                         )
          );


$auth = new Auth("Array", $params, "loginFormHtml");
$auth->start();//�F�؊J�n
if ($auth->getAuth()) 
{
    echo "����";
    exit();
}
echo "���s";
?>
