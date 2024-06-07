<?php
  $us = "root";
  $pw = "";
  $ht = "localhost";
  $db = "control_ct";

  $conexion = mysqli_connect($ht,$us,$pw)or die
  ("Ocurrio un error al comunicarse con la base de datos: ".mysqli_error($conexion));
  mysqli_select_db($conexion, $db)or die("No se establecio la conexion con la tabla: ".mysqli_error($conexion));

?>
