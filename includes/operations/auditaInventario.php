<?php 
error_reporting(0);
session_start();



if(!empty($_SESSION['usNamePlataform'])){
  include('../operations/usuarios.php');
  include('../operations/functionsComents.php');
  include('../_con.php');
  //antes que nada verificamos que se tengan poermisos de
  //auditoria de inventario
  $usuario = $_SESSION['usNamePlataform'];
  $permiso = getPermisos($usuario);
  $permiso = json_decode($permiso);
  $userId = getUsuarioId($usuario);

  if($permiso->auditar_inventario == 1){
    if(!empty($_POST['tipoCreacion'])){
      $tipo = $_POST['tipoCreacion'];
      if($tipo == "valida"){
        $tipo = "Conciliacion";
        $fechaAux = date('Y-m-d');
        //es una conciliacion, en esta parte cualquier persona con permiso puede iniciarla
        //pero, el auditor interneo es el unico que no puede iniciarla
        $departamentoUsuario = getDepaByUser($usuario);
        if($departamentoUsuario != "NoData"){
          if($departamentoUsuario != "Auditoria"){
            
            $sql1 = "INSERT INTO auditoria_inventario (tipo_auditoria,fecha_inicio,usuario_inicia) VALUES 
            ('$tipo','$fechaAux','$userId')";
            try {
              $query1 = mysqli_query($conexion, $sql1);
              //recuperamos el id de la revision
              $idRev = mysqli_insert_id($conexion);
              echo "operacionComplete|".$idRev;
            } catch (Throwable $th) {
              //throw $th;
              echo "DataError|Ocurrio un error al registrar la conciliacion";
            }
            
          }else{
            //se trata de auditoria
            echo "DataError|El departamento de auditoria no puede generar esta tarea.";
          }
        }else{
          //usuario no autorizado
          echo "DataError|No se encontro informacion del usuario, contacte a sistemas.";
        }
      }else{
        //se inicia la auditoria, en este paso tenemos que verificar que
        //la persona que este realizando esta accion, sea, auditoria o sistemas.
        
      }
    }elseif(!empty($_POST['buscarByCod'])){
      //seccion para buscar por codigo en la auditoria
      $codigo = $_POST['buscarByCod'];
      
      $sql = "SELECT * FROM inventario WHERE codigo LIKE '%$codigo%' ORDER BY sucursal_resguardo,lugar_resguardo";
      $query = mysqli_query($conexion, $sql);
      if(mysqli_num_rows($query) > 0){
        $datos = [];
        $i=0;
        while($fetch = mysqli_fetch_assoc($query)){
          $datos[$i] = $fetch;
          $i++;
        }//fin del while
        echo json_encode($datos);
      }else{
        //sin resultados
        echo "NoDataResult";
      }
      
    }elseif(!empty($_POST['idVeri'])){
      //registraremos la validacio de un objeto
      $idAuditoria = $_POST['idVeri'];
      $observacion = $_POST['obserValida'];
      $idObjeto = $_POST['idObjetoInventa'];
      $estadoObj = $_POST['estadoObj'];
      $fechaAux = date('Y-m-d');
      $lugarResgRevi = $_POST['lugarOriginal'];
      $sucurResgRevi = $_POST['sucurOriginal'];
      $horaInv = date('H:i:s');

      


      $sql = "INSERT INTO auditoria_objeto (auditoria_id,inventario_id,verificado,
      observaciones,estado_objeto,fecha_inventario,hora_inventario,usuario_inventariado,
      lugar_resguardo_inv,sucur_resguardo_inv) VALUES ('$idAuditoria','$idObjeto',
      '1','$observacion','$estadoObj','$fechaAux','$horaInv','$userId','$lugarResgRevi','$sucurResgRevi')";
      try {
        $query = mysqli_query($conexion, $sql);
        //una vez que insertamos la validacion insertamos un comentario
        echo "operationSuccess";
      } catch (Throwable $th) {
        //no fue posible insertar el objeto
        echo "DataError|No fue posible registrar la validacion del objeto";
      }
      
    }elseif(!empty($_POST['prodByCombos'])){
      $clasificacion = $_POST['clasiSel'];
      $sucursal = $_POST['sucurSel'];
      $lugaResguardo = $_POST['lugSel'];

      $sqlClasi = "";
      $sqlSuc = "";
      $sqlLug = "";
      
      //creamos las sentencias 
      if($clasificacion != ""){
        $sqlClasi = " AND clasificacion = '$clasificacion'";
      }
      if($sucursal != ""){
        $sqlSuc = " AND sucursal_resguardo = '$sucursal'";
      }
      if($lugaResguardo != ""){
        $sqlLug = " AND lugar_resguardo = '$lugaResguardo'";
      }

      $sql = "SELECT * FROM inventario WHERE articulo_activo = '1' "." ".$sqlClasi." ".$sqlSuc." ".$sqlLug;
      echo $sql;
      try {
        $query = mysqli_query($conexion, $sql);
        if(mysqli_num_rows($query) > 0){
          $datos = [];
          $i = 0;
          while($fetch = mysqli_fetch_assoc($query)){
            $datos[$i] = $fetch;
            $i++;
          }//fin del while
          echo json_encode($datos);
        }else{
          echo "NoData";
        }
      } catch (Throwable $th) {
        echo "DataError|No fue posible realizar la consulta a la base de datos";
      }
      
    }elseif(!empty($_POST['finalizarRev'])){
      $idRevi = $_POST['finalizarRev'];
      $fechaAux = date('Y-m-d');

      $sql = "UPDATE auditoria_inventario SET fecha_fin = '$fechaAux', usuario_finaliza = '$userId' 
      WHERE id_auditoria = '$idRevi'";
      try {
        $query = mysqli_query($conexion, $sql);
        echo "OperationComplete";
      } catch (Throwable $th) {
        echo "DataError|Ocurrio un error al actualizar la informacion";
      }
    }else{
      //sin accion
    }
  }else{
    echo "DataError|No se cuentan con permisos para realizar esta accion.";
  }
}
?>