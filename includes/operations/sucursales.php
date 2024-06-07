<?php 
session_start();
if(!empty($_SESSION['usNamePlataform'])){ 
  include('../_con.php');
  if(!empty($_POST['nombreSuc'])){
    $nombreSuc = $_POST['nombreSuc'];
    $estatus = $_POST['estatusSuc'];

    $sql = "INSERT INTO sucursales (nombre_sucursal,sucursal_activa)
    VALUES ('$nombreSuc',$estatus)";
    $query = mysqli_query($conexion, $sql)or die("DataError|Error al registrar la sucursal");
    echo "operationSuccess";
  }
}
?>