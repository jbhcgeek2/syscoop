<?php 
session_start();
if(!empty($_SESSION['usNamePlataform'])){ 
  include('../_con.php');
  include('functionsComents.php');
  if(!empty($_POST['userNameNew'])){
    
    //antes que nada verificamos si el usuario ingresao ya existe o no
    $usuarioNuevo = $_POST['userNameNew'];
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$usuarioNuevo'";
    $query = mysqli_query($conexion, $sql);
    if(mysqli_num_rows($query) == 0){
      //no se encontro el usuario, capturamos los datos del empleado para registrarlo
      $contraUsuario = $_POST['passNewUser'];
      $nombrePersona = $_POST['nombreNewEmp'];
      $paternoPersona = $_POST['paternoNewEmp'];
      $maternoPersona = $_POST['maternoNewEmp'];
      $mailPersona = $_POST['mailNewEmp'];
      $celPersona = $_POST['celNewEmp'];
      $depPersona = $_POST['depNewEmp'];
      $activo = $_POST['activoNewEmp'];
      $fechaActual = date('Y-m-d');

      //verificamos los permisos del usuario en el array general de todos los permisos
      $permisos = ["verInventario","agregarInventario","editarInventario",
      "verProveedsores","agregarProveedores","editarProveedores","verControles",
      "verMovimientos","actualizaFoto","verDepreciacion"];
      //primero insertamos el empleado
      $sql2 = "INSERT INTO empleados (nombre,paterno,materno,correo,celular,
      departamento_id,activo,fecha_creacion) VALUES ('$nombrePersona','$paternoPersona',
      '$maternoPersona','$mailPersona','$celPersona',$depPersona,$activo,'$fechaActual')";
      $query2 = mysqli_query($conexion, $sql2);
      if($query2){
        $idEmpleado = mysqli_insert_id($conexion);
        //ahora insertamos los permisos del usuario
        $verInventario = $_POST[$permisos[0]]; 
        $addInventario = $_POST[$permisos[1]]; 
        $editInventario = $_POST[$permisos[2]]; 
        $verProveedores = $_POST[$permisos[3]];
        $addProveedores = $_POST[$permisos[4]]; 
        $editProveedores = $_POST[$permisos[5]]; 
        $verControles = $_POST[$permisos[6]];
        $verMovimientos = $_POST[$permisos[7]]; 
        $actualizarFoto = $_POST[$permisos[8]];
        $verDepreciacion = $_POST[$permisos[9]];
        $sql3 = "INSERT INTO permisos (ver_inventario,agregar_inventario,editar_inventario,
        ver_controles,ver_proveedores,agregar_proveedores,editar_proveedores,actualizar_foto,
        ver_movimientos,ver_depreciacion) VALUES ($verInventario,$addInventario,$editInventario,
        $verControles,$verProveedores,$addProveedores,$editProveedores,$actualizarFoto,
        $verMovimientos,$verDepreciacion)";
        $query3 = mysqli_query($conexion, $sql3);
        if($query3){
          $permisosUsuario = mysqli_insert_id($conexion);
          // ahora si insertamos el usuario
          $sql4 = "INSERT INTO usuarios (nombre_usuario,contra_usuario,fecha_creacion,
          empleado_id,permisos_id,usuario_activo) VALUES ('$usuarioNuevo','$contraUsuario',
          '$fechaActual',$idEmpleado,$permisosUsuario,$activo)";
          $query4 = mysqli_query($conexion, $sql4);
          if($query4){
            echo "operationSucess";
          }else{
            echo "DataError|Ocurrio un error en la ultima etapa del proceso, contacte a sistemas";
          }
        }else{
          echo "DataError|Ocurrio un error al asignar los permisos del usuario";
        }
      }else{
        echo "DataError|Error al guardar los datos del empledo";
      }
    }else{
      //el usuario ya existe
      echo "DataError|El usuario ingresado ya existe, favor de verificar";
    }
  }elseif(!empty($_POST['idEmpleadoUsuario'])){
    //seccion para agregar un usuario de un empleado existente
    $nuevoUsuario = $_POST['userName'];
    $idEmpleado = $_POST['idEmpleadoUsuario'];
    $contraUsuario = $_POST['passNew'];
    $fechaActual = date('Y-m-d');
    //verificamos si el usuario ya existe

    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$nuevoUsuario'";
    $query = mysqli_query($conexion, $sql);
    if($query){
      if(mysqli_num_rows($query) == 0){
        $permisos = ["verInventario","agregarInventario","editarInventario",
        "verProveedsores","agregarProveedores","editarProveedores","verControles",
        "verMovimientos","actualizaFoto","verDepreciacion"];

        $verInventario = $_POST[$permisos[0]]; 
        $addInventario = $_POST[$permisos[1]]; 
        $editInventario = $_POST[$permisos[2]]; 
        $verProveedores = $_POST[$permisos[3]];
        $addProveedores = $_POST[$permisos[4]]; 
        $editProveedores = $_POST[$permisos[5]]; 
        $verControles = $_POST[$permisos[6]];
        $verMovimientos = $_POST[$permisos[7]]; 
        $actualizarFoto = $_POST[$permisos[8]];
        $verDepreciacion = $_POST[$permisos[9]];

        $sql2 = "INSERT INTO permisos (ver_inventario,agregar_inventario,editar_inventario,
        ver_controles,ver_proveedores,agregar_proveedores,editar_proveedores,actualizar_foto,
        ver_movimientos,ver_depreciacion) VALUES ($verInventario,$addInventario,$editInventario,
        $verControles,$verProveedores,$addProveedores,$editProveedores,$actualizarFoto,
        $verMovimientos,$verDepreciacion)";

        $query2 = mysqli_query($conexion, $sql2);
        if($query2){
          $idPermisos = mysqli_insert_id($conexion);
          //insertamos el nuevo usuario
          $sql3 = "INSERT INTO usuarios (nombre_usuario,contra_usuario,fecha_creacion,
          empleado_id,permisos_id,usuario_activo) VALUES ('$nuevoUsuario','$contraUsuario',
          '$fechaActual',$idEmpleado,$idPermisos,1)";
          $query3 = mysqli_query($conexion, $sql3);
          if($query3){
            echo "operationSucess";
          }else{
            echo "DataError|Ocurrio un error en la ultima etapa del proceso, contacte a sistemas";
          }
        }else{
          echo "DataError|Ocurrio un error al asignar los permisos del usuario";
        }
      }else{
        //el usuario ya existe
        echo "DataError|El usuario ingresado ya existe, favor de verificar";
      }
    }
  }elseif(!empty($_POST['usurioMod'])){
    //seccion para modificar permisos del usuario
    $tipopermiso = $_POST['tipoPer'];
    $usuarioMod = $_POST['usuarioMod'];
    $idPermiso = $_POST['permiso'];
    $permisoCampo = $_POST['namePer'];
    $usuario = $_SESSION['usNamePlataform'];
    $sql ="";
    if($tipopermiso == "dar"){
      $sql = "UPDATE permisos SET $permisoCampo = '1' WHERE id_permiso = '$idPermiso'";
    }else{
      //quitamos el permiso
      $sql = "UPDATE permisos SET $permisoCampo = '0' WHERE id_permiso = '$idPermiso'";
    }

    try {
      $query = mysqli_query($conexion, $sql);
      //se realiza la modificacion correctamente, podemos insertar el comentario
      $coment = "Se modifica el permiso ".$permisoCampo.".";
      $comentario = setComent($usuario,$coment,$usuarioMod,"Usuarios");
      if($comentario == "OperationSuccess"){
        echo "OperationSuccess";
      }else{
        //se completo con errores
        echo "DataError|La operacion se completo con errores.";
      }
    } catch (\Throwable $th) {
      //error al realizar la modificacion
      echo "DataError|Error al realizar la actualizacion de permisos, ".$th;
    }
  }elseif(!empty($_POST['usuarioMod'])){
    //seccion par modificar los datos del usuario
    $idUsuario = $_POST['usuarioMod'];
    $campo = $_POST['campoMod'];
    $valorMod = $_POST['valorMod'];
    $usuario = $_SESSION['usNamePlataform'];
    //consultamos el empleado
    $sqlE = "SELECT * FROM usuarios a INNER JOIN empleados b 
    ON a.empleado_id = b.id_empleado WHERE a.id_usuario = '$idUsuario'";
    $queryE = mysqli_query($conexion, $sqlE);
    $fetchE = mysqli_fetch_assoc($queryE);
    $idEmpleado = $fetchE['id_empleado'];

    if($campo == "departamento"){
      $campo = "departamento_id";
    }elseif($campo == "puesto"){
      $campo = "cargo_id";
    }elseif($campo == "celUser"){
      $campo = "celular";
    }

    $sql = "UPDATE empleados SET $campo = '$valorMod' WHERE id_empleado = '$idEmpleado'";
    try {
      $query = mysqli_query($conexion, $sql);
      $comen = "Modificacion de datos de usuario";
      $comentario = setComent($usuario,$comen,$idUsuario,"Usuarios");
      if($campo == "activo"){
        //si se da de baja al empleado, tambien damos de baja el o los usuario que tenga
        $sql2 = "UPDATE usuarios SET usuario_activo = '0' WHERE empleado_id = '$idEmpleado'";
        $query2 = mysqli_query($conexion, $sql2);
      }
      if($comentario == "OperationSuccess"){
        echo "OperationSuccess";
      }else{
        //se completo con errores
        echo "DataError|La operacion se completo con errores.";
      }
    } catch (\Throwable $th) {
      //error al actualizar
    }
  }elseif($_POST['dataUsuarioPicture']){
    //seccion para definir una foto de perfil
    $idUsaurio = $_POST['dataUsuarioPicture'];
    //verificamos si existe la foto de perfil
    if($_FILES['fileNewFoto']['name']){
      $sql = "SELECT * FROM usuarios a INNER JOIN empleados b ON a.empleado_id = b.id_empleado 
      WHERE a.id_usuario = '$idUsaurio' LIMIT 1";
      try {
        $query = mysqli_query($conexion, $sql);
        $fetch = mysqli_fetch_assoc($query);
        $nombreFile = "imagenPerfil_".$idUsaurio;
        $rutaDestino = "../../img/userProfile/";
        $idEmpleado = $fetch['id_empleado'];

        $extenciones = ["image/png","image/jpeg","image/jpe"];
        $ext = $_FILES['fileNewFoto']['type'];
        if(in_array($ext,$extenciones)){
          //es del tipo adecuado
          $nameAux = $_FILES['fileNewFoto']['name'];
          $extAux = explode(".",$nameAux);
          $extFile = count($extAux)-1;
          $extencion = $extAux[$extFile];
          $nombreFile = $nombreFile.".".$extencion;
          $rutaFinal = $rutaDestino.$nombreFile;
          $tmpName = $_FILES['fileNewFoto']['tmp_name'];
          try {
            if(file_exists($rutaDestino) && move_uploaded_file($tmpName,$rutaFinal)){
              //una vez arriba del servidor, actualizamos el registro de empleado 
              $sql2 = "UPDATE empleados SET imgPerfil = '$rutaFinal' WHERE id_empleado = '$idEmpleado'";
              try {
                $query2 = mysqli_query($conexion,$sql2);
                echo "OperationSuccess";
              } catch (\Throwable $th) {
                //throw $th;
                //error al asignar la foto de perfil
                echo "DataError|Ocurrio un error al guardar la imagen.";
              }
            }else{
              //no se movio
              echo "DataError|Ocurrio un error al subir la imagen.";
            }
            
          } catch (\Throwable $th) {
            //error: no se moio la foto
            echo "DataError|Ocurrio un error al subir la imagen 2.";
          }
        }else{
          //error: tipo de archivo incorrecto
          echo "DataError|Por favor, elija un tipo de archivo correcto.";
        }
      } catch (\Throwable $th) {
        //error al consultar los datos
      }
      //verificamos que la imagen sea jpn o png
      
    }else{
      echo "no trae foto";
    }
  }
}
?>