<html>
<head>
<title>ログインページ</title>
</head>
<body>
<form method="POST" action="<?php print($SERVER['PHP_SELF']) ?>">
<table border="0">
       <tr>
	<th align="right">ユーザID:</th>
	<td><input type="text" name="username" size="15" maxlength="20" /></td>
       </tr>
       <tr>
        <th align="right">パスワード:</th>
	<td><input type="password" name="password" size="15" maxlengh="20" /></td>
       </tr>
       <tr>
	<td colspan="2">
	    <input type="submit" value"ログイン" />
	</td>
       </tr>
</table>
<font color="Red"><?php print($err); ?></font>
</form>
</body>
</html>