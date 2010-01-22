<HTML>
<HEAD><TITLE>ShowMysqlTable</TITLE></HEAD>
<BODY>

<?php

$conn = mysql_connect("jkproject.localhost", "jkp", "jkproject");
mysql_query("SET NAMES 'utf-8'", $conn);
mysql_select_db("login_db", $conn);
$sql = "SELECT * FROM credentials";
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

mysql_close($conn);

?>

</BODY>
</HTML>
