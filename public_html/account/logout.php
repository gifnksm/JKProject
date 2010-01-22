<?
$_SESSION = array();

if (isSet($_COOKIE[session_name()]))
{
	setCookie(session_name(), '',time() - 3600, '/');
}

session_start();
$destroyed = session_destroy();
?>

<HTML>
<HEAD><TITLE>ログアウト</TITLE></HEAD>
<BODY>
<DIV align="center">
<H3>ログアウト</H3>

<?php
if($destroyed)
{
	echo "ログアウトしました。<BR>";
}
else
{
	echo "ログアウトできませんでした。<BR>";
}
?>
<p><a href="/../html_test2/index.html">HOME</a></p>
</DIV>
</BODY>
</HTML>