<?php 
error_reporting(0);
session_start();

if(!empty($_SESSION['usNamePlataform'])){
  include('../operations/usuarios.php');
  include('../_con.php');

  if(!empty($_POST['userNameNew'])){
    //registro deun nuevo usuario
    //verificamos si se registrara empleado o ya esta registrado
    $fechaActual = date('Y-m-d');
    if(!empty($_POST['nombreNewEmp'])){
      //registro de nuevo empleado
      $nombreEmp = $_POST['nombreNewEmp'];
      $paternoEmp = $_POST['paternoNewEmp'];
      $maternoEmp = $_POST['maternoNewEmp'];
      $mailEmp = $_POST['mailNewEmp'];
      $celEmp = $_POST['celNewEmp'];
      $depEmp = $_POST['depNewEmp'];
      $activEmp = $_POST['activoNewEmp'];
      $userName = $_POST['userNameNew'];
      $pass = $_POST['passNewUser'];
      //antes de continuar verificamos que el empleado no este registrado
      $sqlBusEmp = "SELECT * FROM EMPLEADOS WHERE celular = '$celEmp'";
      $queryBusEmp = mysqli_query($conexion, $sqlBusEmp);
      if(mysqli_num_rows($queryBusEmp) == 0){
        //verificamos la existencia del usuario
        $sqlBusUs = "SELECT * FROM USUARIOS WHERE nombre_usuario = '$userName'";
        $queryBusUs = mysqli_query($conexion, $sqlBusUs);
        if(mysqli_num_rows($queryBusUs) == 0){
          $sqlNewEmp = "INSERT INTO EMPLEADOS (nombre,paterno,materno,correo,
          celular,departamento_id,activo,fecha_creacion) VALUES ('$nombreEmp','$paternoEmp','$maternoEmp',
          '$mailEmp','$celEmp','$depEmp','$activEmp','$fechaActual')";
          $queryNewEmp = mysqli_query($conexion, $sqlNewEmp);
          if($queryNewEmp){
            //insertamos los permisos del usuario
            $idEmpleado = mysqli_insert_id($conexion);
            $sqlNewUser = "INSERT INTO USUARIOS (nombre_usuario,contra_usuario,empleado_id)";
          }else{
            echo "DataError|Ocurrio un error al guardar el empleado";
          }
        }else{
          //se detectaron usuarios existentes
          echo "DataError|El usuario ingresado ya existe, verificalo";
        }
      }else{
        //se encontraron resultados
        echo "DataError|Los datos del empleado coinciden con alguien mas, verificalo";
      }
    }else{
      //ya esta registrado el empleado
    }
  }elseif(!empty($_POST['usuBuscado'])){
    $usuario = $_POST['usuBuscado'];

    $sql = "SELECT * FROM usuarios a INNER JOIN empleados b ON 
    a.empleado_id = b.id_empleado INNER JOIN departamentos c ON 
    b.departamento_id = c.id_departamento WHERE a.nombre_usuario LIKE '%$usuario%' ORDER BY 
    a.nombre_usuario ASC";
    $query = mysqli_query($conexion,$sql);
    if($query){
      if(mysqli_num_rows($query) > 0){
        $i = 0;
        $data = [];
        while($fetch = mysqli_fetch_assoc($query)){
          $data[$i] = $fetch;
          $i++;
        }
        echo json_encode($data);
      }else{
        echo "NoResult";
      }
    }else{
      echo "DataError|No fue posible consultar la base de datos.";
    }
  }
}
?>