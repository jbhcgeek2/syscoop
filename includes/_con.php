<?php
  //$us = "cajatu4x_Prueba";
  //$pw = "#Benja.GeeK0";
  //$ht = "localhost";
  //$db = "cajatu4x_comfirmapp";
  $us = "u427759545_adminSyscoop";
  $pw = "#Benja.GeeK0";
  $ht = "localhost";
  $db = "u427759545_syscoop";

  $conexion = mysqli_connect($ht,$us,$pw)or die
  ("Ocurrio un error al comunicarse con la base de datos: ".mysqli_error($conexion));
  mysqli_select_db($conexion, $db)or die("No se establecio la conexion con la tabla: ".mysqli_error($conexion));
  mysqli_set_charset($conexion, "utf8");
  date_default_timezone_set('America/Mazatlan');



  $serverId = "syscoop.tecuanisoft.com";
  $maxItemPage = 2;//numero de registros a mosrtrar
  $maxNumPages = 3;//numero de paginas a mostrar

?>