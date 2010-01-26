<?php
header('Content-type: text/plain; charset-utf-8');

$conn = mysql_connect("jkproject.localhost", "jkp", "jkproject");
mysql_query("SET NAMES 'utf-8'", $conn);
mysql_select_db("bbs_db", $conn);

$facility_id = 2;
$query = 'select username, date, text from comments'
  . ' left join login_db.credentials on'
  . ' comments.user_id = login_db.credentials.id'
  . " where facility_id = $facility_id";
$result = mysql_query($query, $conn);

$comments = array();
while ($row = mysql_fetch_assoc($result)) {
  $comments[] = $row;
}

session_start();
$login = ($_SESSION['n'] != "" || $_SESSION['p'] != "");
$json_result = array('login' => $login,
                     'name' => $_SESSION['n'],
                     'facility_id' => $facility_id,
                     'comments' => $comments);

if (array_key_exists('dump', $_GET))
  var_dump($json_result);
else
  echo json_encode($json_result);
?>