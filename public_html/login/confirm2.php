<!--
***********************************************
**  DBに書き込む画面
**
**                                  by ohno
**                                  2010/01/20
***********************************************
-->
<HTML>
<HEAD>
<TITLE>DBにかきこむ</TITLE>
</HEAD>
<BODY>

<?php
require 'DB.php';


/*
$sql = <<<SQL
    SELECT c.id,c.name,c.age,g.name,c.prefecture,c.telephone,c.company 
    FROM main_customer AS c,gender AS g 
    WHERE c.gender=g.id 
        AND c.age BETWEEN ? AND ?
SQL;

$ageLow = $_POST['ageLow'];
$ageHigh = $_POST['ageHigh'];
*/


$db = DB::connect("mysql://jkp:jkproject@jkproject.localhost/login_db");
$db->query("SET NAMES 'utf-8'");

/*
if (DB::isError($db))
{
    echo "error to open\n<BR>";
}
else
{
    $stmt = $db->prepare($sql);

    $rs = $db->execute($stmt, array($ageLow, $ageHigh));
    while ($row = $rs->fetchRow())
    {
        echo    'id: ' . $row[0] . 
                ' name: ' . $row[1] . 
                ' age: ' . $row[2] . 
                ' gender: ' . $row[3] . 
                ' pref: ' . $row[4] . 
                ' tel: ' . $row[5] . 
                ' company: ' . $row[6] . 
                "<BR/>\n";
    }
}
*/

/*
***************************************************
** confirm.phpから値を読み取り変数に代入
***************************************************
*/

$username_ = $_POST['username'];
$password_ = $_POST['password'];
echo $password;
$email_ = $_POST['email'];
$mobilemail_ = $_POST['mobilemail'];
$gender_ = $_POST['gender[0]'];
$birthdate_ = $_POST['date[Y]'];
$prefecture_ = $_POST['prefecture'];
//$city_ = $_POST['city'];
$width_ = $_POST['width'];
$rank1_ = $_POST['rank1'];
$rank2_ = $_POST['rank2'];
$slide_door = $_POST['door[0]'];
$double_door = $_POST['door[1]'];
$auto_door = $_POST['door[2]'];
$elevator_ = $_POST['elevator[0]'];
$step_ = $_POST['step[0]'];
$toilet_ = $_POST['toilet[0]'];
$baby_ = $_POST['baby[0]'];


/*
***************************************************
** MySQL に書き込む
***************************************************
*/

$sql = <<<SQL
    INSERT INTO credentials
	    (username,password,email,mobilemail,gender,birthdate,prefecture,width,rank1,rank2,slidedoor,doubledoor,autodoor,elevator,step,toilet,baby)
       VALUES ('$username_','$password_','$email_','$mobilemail_','$gender_','$birthdate_','$prefecture_','$width_','$rank1_','$rank2_','$slide_door','$double_door','$auto_door','$elevator_','$step_','$toilet_','$baby_');
SQL;

$result = mysql_query($sql);

if($result){
echo "ok";
}
else{
echo "no";
}
//$db->execute($sql);


?>

</BODY>
</HTML>
