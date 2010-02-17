<?php
header('Content-type: text/plain; charset-utf-8');

session_start();

$comment = $_POST['comment'];
$fid = intval($_POST['facility_id']);
$uid = intval($_SESSION['i']);
$ip = getenv('REMOTE_ADDR');
$host = getenv('REMOTE_HOST');

function error($message) {
  echo json_encode(array('result' => 'ng', 'message' => $message));
  exit;
}

if ($comment == '')
  error('コメントが空です');

$comment = htmlspecialchars($comment, ENT_QUOTES);
$comment = nl2br($comment);

$conn = mysql_connect("jkproject.localhost", "jkp", "jkproject");
mysql_query("SET NAMES 'utf-8'", $conn);
mysql_select_db("bbs_db", $conn);

$query =
  sprintf("INSERT INTO comments" .
          " (user_id, facility_id, date, ip, host, text)"
          . " VALUES (%d, %d, now(), INET_ATON('%s'), '%s', '%s')",
          $uid, $fid,
          $ip,
          mysql_real_escape_string($host),
          mysql_real_escape_string($comment));

if (!mysql_query($query, $conn)) {
  error(mysql_error($conn));
}

echo json_encode(array('result' => 'ok'));

?>