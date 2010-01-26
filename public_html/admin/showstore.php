<HTML>
<HEAD><TITLE>ShowMysqlTable</TITLE></HEAD>
<BODY>
<H2>登録者一覧</H2>
<hr>

<H3>登録を削除するIDを入力</H3>
<FORM method="post" action="">
  ID: <INPUT type="text" name="num"><BR>
  <INPUT type="submit">
</FORM>

<?php
echo "$_POST[num]";
$conn = mysql_connect("jkproject.localhost", "jkp", "jkproject");
mysql_query("SET NAMES 'utf-8'", $conn);
mysql_select_db("store_db", $conn);
$sql = "SELECT * FROM store_info";
$rs = mysql_query($sql, $conn);
echo "<TABLE border=1>";



while ($row = mysql_fetch_assoc($rs)) 
{
    echo "<TR>";
    foreach  ($row as $key => $val)
    {
        echo "<TD>$key=<b>$val</b></TD>";
    }
    echo "</TR>";
}
echo "</TABLE>";


$del = "DELETE FROM store_info WHERE id=$_POST[num]";
$act = mysql_query($del, $conn);

if($_POST[num] == "jkproject")
{
$alldel = "DELETE FROM store_info";
$action = mysql_query($alldel, $conn);
}

mysql_close($conn);
if($act || $action)
{
echo '<meta http-equiv="Refresh" content="0; URL=/./account/showstore.php">';
}
?>
</BODY>
</HTML>
