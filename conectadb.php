<?php

function my_query($query) { 
  //mysql_connect('10.70.249.35', 'root', '12') OR die(fail('no hay conexion.')); 
mysql_connect('localhost', 'root') OR die(fail('no hay conexion.')); 
  mysql_select_db ('soloplancho'); return mysql_query($query); 
}



?>
