<?php 
error_reporting(0);

function setComent($user,$com,$refId,$tMov){
  include('includes/_con.php');
  if(!$conexion){
    include('_con.php');
    if(!$conexion){
      include('../_con.php');
    }
  }
  
  $fechaActual = date('Y-m-d');
  $hora = date('H:i:s');

  $sql = "INSERT INTO movimientos (fecha_movimiento,hora_movimiento,usuario_movimiento,
  tipo_movimiento,descripcion_movimiento,referencia_id) VALUES ('$fechaActual','$hora','$user',
  '$tMov','$com','$refId')";

  try {
    $query = mysqli_query($conexion, $sql);
    return "OperationSuccess";
  } catch (Throwable $th) {
    return "Error";
  }
  

}

?>