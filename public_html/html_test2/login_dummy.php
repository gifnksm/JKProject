<?
if (rand(0, 1) == 0) {
  echo <<< END
{
  "login": true,
  "name": "namae"
}
END;
} else {
  echo <<< END
{
  "login": false
}
END;
}
?>