<?
header('Content-type: text/plain; charset-utf-8');
session_start();
$login = ($_SESSION['n'] != "" || $_SESSION['p'] != "");
$data = array("login" => $login);

if($login )
{
 $data["name"] = $_SESSION["n"];
}

echo json_encode($data);
?>
