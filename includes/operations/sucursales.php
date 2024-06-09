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
  }elseif(!empty($_POST["newNameSuc"])){
    //seccion para actualizar las sucursales
    $nombreSuc = $_POST['newNameSuc'];
    $estatusSuc = $_POST['newStatusSuc'];
    $idSuc = $_POST['dataSuc'];

    $sql = "UPDATE sucursales SET nombre_sucursal = '$nombreSuc', sucursal_activa = $estatusSuc WHERE id_sucursal = $idSuc";
    try {
      $query = mysqli_query($conexion, $sql)or die("DataError|Error al actualizar la sucursal");
      $res = ['status'=>'ok','mensaje'=>'operationComplete'];
      echo json_encode($res);
    } catch (\Throwable $th) {
      //error al actualizar
      $res =['status'=>'error','mensaje'=>'Ocurrio un error al actualizar la sucursal.'];
      echo json_encode($res);
    }
    
  }
}
?>