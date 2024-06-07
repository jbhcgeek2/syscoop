<?php
error_reporting(0);
session_start();

if(!empty($_SESSION['usNamePlataform'])){
  include('../operations/usuarios.php');
  include('../_con.php');

  if(!empty($_POST['nombreProveedor'])){
    $campos = ['nombreProveedor','telProveedor','rfcProveedor','direccion'];
    $van = 0;
    $fechaActual = date('Y-m-d');

    for ($i=0; $i < count($campos); $i++) { 
      if(empty($_POST[$campos[$i]])){
        $van++;
      }
    }//fin del for

    if($van == 0){
      $nombreProveedor = $_POST['nombreProveedor'];
      $telProveedor = $_POST['telProveedor'];
      $rfcProveedor = $_POST['rfcProveedor'];
      $direccion = $_POST['direccion'];
      $usuario = $_SESSION['usNamePlataform'];
      $usuario = getUsuarioId($usuario);
      
      //verificamos si el RFC o el telefono ya existen
      $sqlbusProv = "SELECT * FROM PROVEEDORES WHERE telefono_proveedor = '$telProveedor' OR rfc_proveedor = '$rfcProveedor'";
      $queryBus = mysqli_query($conexion, $sqlbusProv);
      if($queryBus){
        if(mysqli_num_rows($queryBus) == 0){
          //insertamos la informacion
          $sqlProvNew = "INSERT INTO PROVEEDORES (nombre_proveedor,telefono_proveedor,rfc_proveedor,
          direccion_proveedor,usuario_registro,usuario_actualizo,fecha_registro_proveedor) VALUES 
          ('$nombreProveedor','$telProveedor','$rfcProveedor','$direccion','$usuario','$usuario','$fechaActual')";
          $queryProvNew = mysqli_query($conexion, $sqlProvNew);
          if($queryProvNew){
            echo "ProveedorGuardado";
          }else{
            //error alinsrertar el proveedor
            echo "DataError|Ocurrio un error al guardar el proveedor.";
          }
        }else{
          //se encontraron resultados
          echo "DataError|Al parecer ya existen proveedores con la informacion insertada";
        }
      }else{
        //error de consulta
        echo "DataError|Ocurrio un error al realizar verificaicones (1)";
      }
    }else{
      echo "DataError|Verifica que los campos esten capturados.";
    }
  }elseif(!empty($_POST['nombreProvEdit'])){
    //antes de realizar la accion verificamos que si cuente con los permisos
    $usuario = $_SESSION['usNamePlataform'];
    $permisos = getPermisos($usuario);
    $permisos = json_decode($permisos);

    if($permisos->editar_proveedores == 1){
      $nombreProv = $_POST['nombreProvEdit'];
      $rfcProv = $_POST['rfcProvEdit'];
      $telProv = $_POST['telProvEdit'];
      $direProv = $_POST['direcProvEdit'];
      $idProv = $_POST['provIdEdit'];

      if($nombreProv != "" && $rfcProv != "" && $telProv != "" && $direProv != ""){
        //INSERT INTO PROVEEDORES (nombre_proveedor,telefono_proveedor,rfc_proveedor,
        //direccion_proveedor,usuario_registro,usuario_actualizo,fecha_registro_proveedor)
        $fechaActual = date('Y-m-d');
        $idUsuario = getUsuarioId($usuario);

        $sqlEdit = "UPDATE PROVEEDORES SET nombre_proveedor = '$nombreProv', 
        telefono_proveedor = '$telProv', rfc_proveedor = '$rfcProv', 
        direccion_proveedor = '$direProv', fecha_actualiza_proveedor = '$fechaActual',
        usuario_actualizo = '$idUsuario' WHERE id_proveedor = '$idProv'";
        $queryEdit = mysqli_query($conexion, $sqlEdit);
        if($queryEdit){
          echo "updateProvComplete";
        }else{
          echo "DataError|No fue posible realizar laactualizacion, Error DB";
        }
      }else{
        echo "DataError|Verifica que los campos esten capturados de manera correcta";
      }
    }else{
      echo "DataError|No se cuentan con los permisos necesarios pararealizar esta accion";
    }
  }elseif(!empty($_POST['buscarProv'])){
    //seccion para buscar proveedores
    $usuario = $_SESSION['usNamePlataform'];
    $permisos = getPermisos($usuario);
    $permisos = json_decode($permisos);
    if($permisos->ver_proveedores == 1){
      ///tiene permisos para buscar proveedores
      $nombreProv = $_POST['buscarProv'];
      $sql = "SELECT * FROM proveedores WHERE nombre_proveedor LIKE '%$nombreProv%'";
      try {

        $query = mysqli_query($conexion, $sql);
        if(mysqli_num_rows($query) >= 1){
          $data = [];
          $i = 0;
          while($fetch = mysqli_fetch_assoc($query)){
            $data[$i] = $fetch;
            $i++;
          }//fin del while

          echo json_encode($data);
        }else{
          //sin resultados de busqueda
          echo "noResults";
        }
      } catch (Throwable $th) {
        echo "DataError|Ocurrio un error al consultar la BD de proveedores";
      }
      
      
    }
  }
}

?>