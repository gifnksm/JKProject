<!--
/*
*******************************************************
**	ログイン画面＋新規アカウント登録画面
**
**					by ohno
**					2010/01/20
*******************************************************
-->
<HTML>
<HEAD><TITLE>ログイン</TITLE></HEAD>
<BODY>
<table><tr><td style="width: 12em;">
               <A Href="/../html_test2/index.html" Target="_blank">   
	       <h1><img src="/resource/image/logo_small.png" Border="0" with="200" height="50"
	       		alt="バリアフリーマップ検索" /></h1>
		</td><td style="text-align: right;">
</td>
</tr>
</table>
<DIV align="center">
<H3>ログイン</H3>
    <FORM method="post" action="login2.php">
        ユーザ名: <INPUT type="text" name="username"><BR>
        パスワード: <INPUT type="password" name="password"><BR>
        <INPUT type="submit">
    </FORM>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<p><a href="account.php">アカウント新規登録</a></p>
<p><a href="/../html_test2/index.html">ホーム</a></p>
<A Href="javascript:history.go(-1)">1つ前に戻る</A> 

</DIV>
<DIV align="right">
<table><tr><td style="width: 12em;">
               <h1><img src="./image/car_1.png" with="300" height="225"
                        alt="車いす" /></h1>
                </td><td style="text-align: right;">
</DIV>

<SCRIPT type="text/JavaScript">
<!--
var pic = new Array();
pic[0] = new Image();
pic[1] = new Image();
pic[0].src = "./image/login_2.png";
pic[1].src = "./image/login_1.png";
function setRollOverImg(index, obj){
obj.src = pic[index].src;
}
//-->
</SCRIPT>
<IMG src="./image/login_1.png" onMouseOver="setRollOverImg(0, this)" onMouseOut="setRollOverImg(1, this)">





</BODY>
</HTML>
