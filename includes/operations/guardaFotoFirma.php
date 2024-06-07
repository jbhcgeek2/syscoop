<?php 
session_start();

if(!empty($_SESSION['usNamePlataform'])){
  include_once "../conSql.php";
  include_once "../_con.php";
  
  //$imagen = file_get_contents("php://input");
  $imagen = $_POST['imgBase64'];
  $imagen = str_replace('data:image/png;base64,','',$imagen);
  $imagen = str_replace(' ', '+',$imagen);
  $data = base64_decode($imagen);
  $socio = $_POST['numSoc'];
  
  if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/fotoSocios")) {
    mkdir($_SERVER['DOCUMENT_ROOT'] . "/fotoSocios", 0777, true);
  }

  $tipoFoto = $_POST['tipoPic'];

  $foto = $_SERVER['DOCUMENT_ROOT']."/fotoSocios/".$tipoFoto."_".$socio.".png";
  $fotoSql = "\\\\192.168.1.129/htdocs/fotoSocios/$tipoFoto"."_".$socio.".png";
//  $fotoSql = "http://192.168.1.129/fotoSocios/".$tipoFoto."_".$socio.".png";
  
  //$guardar = file_put_contents($foto, $data);
  if(file_put_contents($foto, $data)){
    //actualizamos la base de datos 
    if($tipoFoto == "Foto"){
      $campoUpdate = "PICTURE";
    }else{
      $campoUpdate = "FIRMA";
    }
    $sql = "UPDATE $dbSQLName.dbo.SOCIOS SET $campoUpdate = ? WHERE SOCIO = ?";
    $query = $con->prepare($sql);
    $update = $query->execute([$fotoSql, $socio]);
    if($update){
      $fecha = date("Y-m-d");
      $hora = date("H:i:s");
      $usuario = $_SESSION['usNamePlataform'];
      $descripcionMov = "Actualizacion de la ".$tipoFoto." del socio No.".$socio;
      //se realizo el guardado correctamente ahora insertamos el movimiento
      $sql2 = "INSERT INTO movimientos (fecha_movimiento,hora_movimiento,usuario_movimiento,
      tipo_movimiento,descripcion_movimiento) VALUE ('$fecha','$hora','$usuario',
      'Actualizacion Socio','$descripcionMov')";
      $query2 = mysqli_query($conexion, $sql2);
      if($query2){
        echo "updatePicture";
      }else{
        echo "Ocurrio un error en la base de datos interna";
      }
      
    }else{
      echo "error al actualizar";
    }



  }else{
    echo "No se guardo";
  }
  //print $guardar ? $foto.' saved.' : 'Unable to save the file.';
}else{

}
?>