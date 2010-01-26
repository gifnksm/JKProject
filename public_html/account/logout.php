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
<?php
if($destroyed)
{
	echo "<H3>ログアウトしました。<H3>";
}
else
{
	echo "ログアウトできませんでした。<BR>";
}
?>
<H2>自動的にトップページに戻ります</H2>
<h1><img src="./image/neko3.png" alt="" /></h1>
<p><a href="/../html_test2/index.html">HOME</a></p>
<meta http-equiv="Refresh" content="2; URL=/../html_test2/index.html">

</DIV>
</BODY>
</HTML>