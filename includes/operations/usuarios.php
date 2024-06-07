<?php 
//error_reporting(1);
  //funciones para obtner datos de usuarios

  //funcion para consultar los permisos del usuario
  function getPermisos($usuario){
    include('includes/_con.php');
    if(!$conexion){
      include('_con.php');
      if(!$conexion){
        include('../_con.php');
      }
    }

    $sql = "SELECT * FROM usuarios a INNER JOIN permisos b ON a.permisos_id = b.id_permiso WHERE a.nombre_usuario = '$usuario'";
    $query = mysqli_query($conexion, $sql);
    $fetch = mysqli_fetch_assoc($query);
    $datos = json_encode($fetch);
    
    return $datos;
  }

  //OBTENER INFORMACION PRINCIPAL DEL USUARIO
  function getDataUser($usuario){
    include('includes/_con.php');
    if(!$conexion){
      include('_con.php');
    }
    $sql = "SELECT * FROM usuarios a INNER JOIN empleados b ON a.empleado_id = b.id_empleado WHERE a.nombre_usuario = '$usuario'";
    $query = mysqli_query($conexion, $sql);

    $error = mysqli_error($conexion);
    if(mysqli_num_rows($query) == 1){
      $fetch = mysqli_fetch_assoc($query);
      $datos = json_encode($fetch);
      return $datos;
    }
  }//fin funcion getDataUser

  function getUsuarioId($usuario){
    include('includes/_con.php');
    if(!$conexion){
      include('_con.php');
      if(!$conexion){
        include('../_con.php');
      }
    }
    $sql = "SELECT id_usuario FROM usuarios  WHERE nombre_usuario = '$usuario'";
    $query = mysqli_query($conexion, $sql);
    if(mysqli_num_rows($query) > 0){
      $fetch = mysqli_fetch_assoc($query);
      $idUsuario = $fetch['id_usuario'];
      return $idUsuario;
    }else{
      return "noDataPerson";
    }
  }//fin funcion getUserById

  function getEmpleadoIdByUser($usuario){
    include('includes/_con.php');
    if(!$conexion){
      include('_con.php');
      if(!$conexion){
        include('../_con.php');
      }
    }
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$usuario'";
    $query = mysqli_query($conexion, $sql);
    $fetch = mysqli_fetch_assoc($query);

    $idEmpleado = $fetch['empleado_id'];
    return $idEmpleado;
  }

  function getUserById($idUsuario){
    include('includes/_con.php');
    if(!$conexion){
      include('_con.php');
      if(!$conexion){
        include('../_con.php');
      }
    }
    $sql = "SELECT * FROM usuarios WHERE id_usuario = '$idUsuario'";
    $query = mysqli_query($conexion, $sql);
    $fetch = mysqli_fetch_assoc($query);
    $usuario = $fetch['nombre_usuario'];
    return $usuario;
  }

  function getEmpleado($idEmpleado){
    include('includes/_con.php');
    if(!$conexion){
      include('_con.php');
      if(!$conexion){
        include('../_con.php');
      }
    }

    $sql = "SELECT * FROM empleados WHERE id_empleado = '$idEmpleado'";
    $query = mysqli_query($conexion, $sql);
    if($query){
      $fetch = mysqli_fetch_assoc($query);
      $nombreEmpleado = $fetch['paterno']." ".$fetch['materno']." ".$fetch['nombre'];
      return $nombreEmpleado;
    }else{
      echo "DataError|No fue posible encontrar el usuario";
    }
  }

  function getUserByPerson($idPersona){
    include('includes/_con.php');
    if(!$conexion){
      include('_con.php');
    }
    $sql = "SELECT * FROM usuarios WHERE persona_id = '$idPersona'";
    $query = mysqli_query($conexion, $sql);
    if($query){
      if(mysqli_num_rows($query) > 0){
        $data = [];
        $i=0;
        while($fetch = mysqli_fetch_assoc($query)){
          $data[$i] = $fetch;
          $i++;
        }//fin del while

        return json_encode($data);
      }else{
        return "NoData";
      }
    }else{
      return "DataError|Error al consultar la base de datos";
    }
  }

  function getDepaByUser($usuario){
    include('includes/_con.php');
    if(!$conexion){
      include('_con.php');
      if(!$conexion){
        include('../_con.php');
      }
    }

    $sql = "SELECT * FROM usuarios a INNER JOIN  empleados b ON a.empleado_id = b.id_empleado
    INNER JOIN departamentos c ON b.departamento_id = c.id_departamento WHERE a.nombre_usuario = '$usuario' AND 
    usuario_activo = '1'";
    $query = mysqli_query($conexion, $sql);
    if($query){
      if(mysqli_num_rows($query) > 0){
        $fetch = mysqli_fetch_assoc($query);
        return $fetch['nombre_departamento'];
      }else{
        return "NoData";
      }
    }else{

    }
  }

  function getDepaIdByUser($usuario){
    include('includes/_con.php');
    if(!$conexion){
      include('_con.php');
      if(!$conexion){
        include('../_con.php');
      }
    }

    $sql = "SELECT * FROM usuarios a INNER JOIN  empleados b ON a.empleado_id = b.id_empleado
    INNER JOIN departamentos c ON b.departamento_id = c.id_departamento WHERE a.nombre_usuario = '$usuario' AND 
    usuario_activo = '1'";
    $query = mysqli_query($conexion, $sql);
    if($query){
      if(mysqli_num_rows($query) > 0){
        $fetch = mysqli_fetch_assoc($query);
        return $fetch['id_departamento'];
      }else{
        return "NoData";
      }
    }else{

    }
  }

?>