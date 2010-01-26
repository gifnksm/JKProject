<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>ログイン</TITLE>
<SCRIPT type="text/JavaScript">
var pic = new Array();
pic[0] = new Image();
pic[1] = new Image();
pic[0].src = "./image/login_2.png";
pic[1].src = "./image/login_1.png";
function setRollOverImg(index){
  document.getElementById('cat').src = pic[index].src;
}
</SCRIPT>
</HEAD>
<BODY>
  <table width="100%" height="100%"><tr>
      <td width="370" valign="top">
        <a Href="/../html_test2/index.html" Target="_blank">
          <img src="/resource/image/logo_small.png" Border="0" with="200" height="50" alt="バリアフリーマップ検索" />
        </a>
      </td>
      <td valign="middle" align="center">
        <h1><img src="./image/login_3.png" alt="ログイン" /></h1>
        <FORM method="post" action="login2.php">
          ユーザ名: <INPUT type="text" name="username"><BR>
          パスワード: <INPUT type="password" name="password"><BR>
          <INPUT type="submit" value="送信">
        </FORM>
        <p style="margin-top: 5em;"><a href="account.php">アカウント新規登録</a></p>
        <p><a href="/../html_test2/index.html">ホーム</a></p>
      </td>
      <td width="370" valign="bottom" align="right">
        <img src="./image/login_1.png" id="cat"
             onMouseOver="setRollOverImg(0)" onMouseOut="setRollOverImg(1)">
        <img src="./image/car_1.png" with="300" height="225" alt="車いす"
             onMouseOver="setRollOverImg(0)" onMouseOut="setRollOverImg(1)" />
      </td>
</BODY>
</HTML>
