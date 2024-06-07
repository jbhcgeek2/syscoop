<?php
session_start();
// error_reporting(E_ALL);
if(!empty($_SESSION['usNamePlataform'])){
  include('../operations/usuarios.php');
  include('../operations/functionsComents.php');
  include('../_con.php');
  include('../../enviarCorreo.php');
  if(!empty($_POST['nombreTicket'])){
    //seccion para dar de alta un tipo de ticket

    $nombreTicket = $_POST['nombreTicket'];
    $solicita = $_POST['solicitaTicket'];
    $responsable = $_POST['resuelveTicket'];
    $diasAtencion = $_POST['diasAtencion'];

    $nCampos = $_POST['camposAdd'];
    //concatenamos los campos a uno solo
    $camposAdd = "";
    $n = 1;
    for($x = 0; $x < $nCampos; $x++){
      if(!empty($_POST['inputAdd'.$n])){
        if($x == 0){
          $camposAdd = $_POST['inputAdd'.$n];
        }else{
          $camposAdd .= "|".$_POST['inputAdd'.$n];
        }
      }
      
      $n++;
    }//fin del for

    $sql = "INSERT INTO tipoTickets (nombreTicket,responsableTicket,diasAtencion,
    ticketActivo,camposTicket,solicitaTicket) VALUES ('$nombreTicket','$responsable',
    '$diasAtencion','1','$camposAdd','$solicita')";
    try {
      $query = mysqli_query($conexion, $sql);
      //agregamos el comentario de ticket
      $idTipoTicket = mysqli_insert_id($conexion);
      $usuario = $_SESSION['usNamePlataform'];
      $coment = "Se inserta el tipo de ticket: ".$nombreTicket;
      $comentario = setComent($usuario,$coment,$idTipoTicket,"Ticket");
      if($comentario == "OperationSuccess"){
        echo "OperationSuccess";
      }else{
        echo "DataError|La tarea finalizo con errores, reportar al area de sistemas.";
      }
    } catch (\Throwable $th) {
      //error al realizar el insert
      echo "DataError|Ocurrio un error al insertar el tipo de ticket: ".$th;
    }
  }elseif(!empty($_POST['tipoTUpdate'])){
    $idTipoT = $_POST['tipoTUpdate'];
    $valor = $_POST['valorTipoTUpdate'];
    $campo = $_POST['campoTipoTUpdate'];

    //los valores deben estar capturados
    if(!empty($idTipoT) && !empty($valor) && !empty($campo)){
      $sql = "UPDATE tipoTickets SET $campo = '$valor' WHERE idTipoTicket = '$idTipoT'";
      try {
        $query = mysqli_query($conexion, $sql);
        //insertamos un comentario de modificacion
        $comentario = "Se actualizo el campo: ".$campo." ahora contiene ".$valor;
        $setCom = setComent($usuario,$comentario,$idTipoT,"Ticket");
        if($setCom == "OperationSuccess"){
          echo "OperationSuccess";
        }else{
          echo "DataError|La tarea finalizo con errores, reportar al area de sistemas.";
        }
      } catch (Throwable $th) {
        echo "DataError|Ocurrio un error al realizar la actualizacion: ".$th;
      }
    }else{
      //no se tienen capturados todos los camois
      echo "No pasa";
    }
  }elseif(!empty($_POST['verInfoTipoT'])){
    //seccion para verificar la informacion del ticket y los empleados
    //que pueden dar respuesta a estos
    $idTipo = $_POST['verInfoTipoT'];
    $sql = "SELECT * FROM tipoTickets WHERE idTipoTicket = '$idTipo'";
    try {
    $query = mysqli_query($conexion, $sql);
    $fetch = mysqli_fetch_assoc($query);
    $responsable = $fetch['responsableTicket'];
    if($responsable == 2){
      $sql2 = "SELECT * FROM empleados a INNER JOIN usuarios b ON a.id_empleado = b.empleado_id  WHERE a.activo = '1'";
    }else{
      $sql2 = "SELECT * FROM empleados a INNER JOIN usuarios b ON a.id_empleado = b.empleado_id WHERE a.activo = '1' AND a.departamento_id = '$responsable'";
    }
    try {
      $query2 = mysqli_query($conexion, $sql2);
      //ahora si volcamos los datos
      $data = [];
      $i = 0;
      while($fetch2 = mysqli_fetch_assoc($query2)){
        $data[$i] = $fetch2;
        $i++;
      }//fin del while
      $datos = ["responden"=>$data,"camposT"=>$fetch];
      echo json_encode($datos);
    } catch (\Throwable $th) {
      //error al consultar la informacion de los empleados
      echo "DataError|Ocurrio un error al consultar la informacion adicional del ticket";
    }

    } catch (\Throwable $th) {
      //error al consultar el tipo de ticket
      echo "DataError|Ocurrio un error al consultar la informacion del ticket";
    }
  }elseif(!empty($_POST['prioridad'])){
    //seccion para capturar la solicitud de un nuevo ticket
    
    $tipoTicket = $_POST['tipoSolicitud'];
    $prioridad = $_POST['prioridad'];
    $asignado = $_POST['asignado'];
    $descripcionTicket = $_POST['descripcionTicket'];
    $numCampoAdd = $_POST['nuCamposAdd'];
    //verificamos que los campos esten capturados
    $camposAdd = "";
    $mal = 0;
    $campos = ['tipoSolicitud','prioridad','asignado','descripcionTicket','nuCamposAdd'];

    for($n = 0; $n < count($campos); $n++){
      //verificamos si los campos estan contienen datos
      $camp = $campos[$n];
      if (empty($_POST[$camp]) || !isset($_POST[$camp])) {
        $mal = $mal +1;
      }
    }//fin del for

    //verificamos si los campos campoAdd agregados estan capturados
    for($x = 0; $x < $numCampoAdd; $x++){
      $campoAdd = "campoAdd".$x;
      if($x == 0){
        $camposAdd = $_POST[$campoAdd];
      }else{
        $camposAdd = $camposAdd."|".$_POST[$campoAdd];
      }
    }//fin del for campos Add

    //una vez que tenemos la informacion capturada procedemos a insertar el ticket
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    $usuario = $_SESSION['usNamePlataform'];
    $idUsuario = getUsuarioId($usuario);

    //consultamos el area del ticket para insertarlo
    $sqlV = "SELECT * FROM tipoTickets WHERE idTipoTicket = '$tipoTicket'";
    $queryV = mysqli_query($conexion, $sqlV);
    $fetchV = mysqli_fetch_assoc($queryV);
    $idDepa = $fetchV['responsableTicket'];
    $sql2 = "";

    if($asignado == "unassigned"){
      //el ticket no se indico un responsable, por lo que mandaremos correo a todos
      //los usuario activos del area correspondiente
      $sql = "INSERT INTO tickets (fecha_registro_ticket,estatus_ticket,
      usuario_registra_ticket_id,descripcion_ticket,prioridad_ticket,
      tipo_ticket_id,hora_registro_ticket,campos_tipo_ticket,area_seguimiento_ticket_id)
       VALUES ('$fecha','Abierto','$idUsuario','$descripcionTicket','$prioridad',
      '$tipoTicket','$hora','$camposAdd','$idDepa')";

      $sqlUMail = "SELECT * FROM empleados WHERE departamento_id = '$idDepa' AND activo = '1'";
    }else{
      //se indico un responsable, nomas mandamos correo a este empleado
      $sql = "INSERT INTO tickets (fecha_registro_ticket,estatus_ticket,
      usuario_registra_ticket_id,descripcion_ticket,prioridad_ticket,
      tipo_ticket_id,hora_registro_ticket,campos_tipo_ticket,area_seguimiento_ticket_id,
      usuario_seguimiento_ticket_id) VALUES ('$fecha','Abierto','$idUsuario',
      '$descripcionTicket','$prioridad','$tipoTicket','$hora','$camposAdd',
      '$idDepa','$asignado')";

      $sqlUMail = "SELECT * FROM empleados a INNER JOIN usuarios b ON
      a.id_empleado = b.empleado_id WHERE b.id_usuario = '$asignado'";
    }

    
    try {
      $query = mysqli_query($conexion, $sql);
      //insertamos el comentario
      $idTicket = mysqli_insert_id($conexion);
      $coment = "Nueva solicitud de ticket registrada por ".$usuario;
      $comentario = setComent($usuario,$coment,$idTicket,"Comentario Ticket");
      if($comentario == "OperationSuccess"){
        //hacemos la consulta 2 para enviar los correos, si falla no pasara nada
        $queryUMail = mysqli_query($conexion, $sqlUMail);
        $correosMandar = "";
        
        while($fetchUMail = mysqli_fetch_assoc($queryUMail)){
          // $nombreCompletox = $fetchUMail['nombre']." ".$fetchUMail['paterno']." ".$fetchUMail['materno'];
          if($correosMandar == ""){
            $correosMandar = $fetchUMail['correo'];
          }else{
            $correosMandar = "|".$fetchUMail['correo'];
          }
          
          
        }
        $cuerpoMail = "Estimado usuario, <br>
        Nos dirigimos a ti para informarte que se ha registrado un nuevo ticket para tu departamento.<br> 
        Te invitamos cordialmente a acceder a la plataforma Syscoop para obtener información 
        detallada al respecto.<br>
        no dudes en ponerte en contacto si presentas problemas para visualizarlo.<br>";
        $asun = "Nuevo Ticket Registrado";
        $subAsun = "Atencion Usuario";
        $enviado = mailSend($correosMandar,$asun,$cuerpoMail,$subAsun,"OperationSuccess","");
        
        echo "OperationSuccess";
        
      }else{
        echo "DataError|La tarea finalizo con errores, reportar al area de sistemas.";
      }
    } catch (\Throwable $th) {
      //error al insertar la consulta
      echo "DataError|Ha ocurrido un error al insertar la solicitud: ".$th;
    }
  }elseif(!empty($_POST['coment'])){
    //seccion para insertar comentarios al ticket
    $ticket = $_POST['ticket'];
    $coment = $_POST['coment'];
    $usuario = $_SESSION['usNamePlataform'];

    $comentario = setComent($usuario,$coment,$ticket,"Comentario Ticket");
    if($comentario == "OperationSuccess"){
      echo "OperationSuccess";
    }else{
      echo "DataError|La tarea finalizo con errores, reportar al area de sistemas.";
    }
  }elseif(!empty($_POST['tomarTicket'])){
    //seccion para tomar un tiocket como tecnico

    $idTicket = $_POST['tomarTicket'];
    $usuario = $_SESSION['usNamePlataform'];
    $idUsuario = getUsuarioId($usuario);
    //hacemos la actualizacion
    $sql = "UPDATE tickets SET  usuario_seguimiento_ticket_id = '$idUsuario' WHERE id_ticket = '$idTicket'";
    try {
      $query = mysqli_query($conexion, $sql);
      //insertamos un comentario de ticket asignado
      $coment = "Ticket tomado por usuario: ".$usuario;
      $comentario = setComent($usuario,$coment,$idTicket,"Comentario Ticket");
    if($comentario == "OperationSuccess"){
      //ahora cambiamos el estatus a "En Proceso"
      $sql2 = "UPDATE tickets SET estatus_ticket = 'En Proceso' WHERE id_ticket = '$idTicket'";
      try {
        $query2 = mysqli_query($conexion, $sql2);
        echo "OperationSuccess";
      } catch (\Throwable $th) {
        echo "DataError|La tarea finalizo con errores 2, reportar al area de sistemas.";
      }
      
    }else{
      echo "DataError|La tarea finalizo con errores, reportar al area de sistemas.";
    }
    } catch (\Throwable $th) {
      //throw $th;
    }
    


  }elseif(!empty($_POST['AnexoTicket'])){
    $idTicket = $_POST['AnexoTicket'];
    $docTicket = $_FILES['docAnexo']['name'];

    //veriricamos que el archivo este en formato pdf
    //o formato documento de texto
    $extencionA = explode(".",$_FILES['docAnexo']['name']);
    $extencion = $extencionA[count($extencionA)-1];
    
    $tipoDoc = $_FILES['docAnexo']['type'];

    $tipos = ['application/pdf','application/msword','application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

    if(in_array($tipoDoc, $tipos)){
      //si es valido
      //ahora verificamos que no supere el limite de peso limitado en 
      if($_FILES['docAnexo']['size'] <= 25000000){
        //procedemos con la subida
        $carpetaDestino = "../../docs/tickets/".$idTicket;
        $nombreArchivo = "A_".$extencionA[0]."_".date('ymds').".".$extencion;
        $rutaFinal = $carpetaDestino."/".$nombreArchivo;
        $usuario = $_SESSION['usNamePlataform'];
        $usuarioID = getUsuarioId($usuario);
        $fecha = date('Y-m-d');
        if(!file_exists($carpetaDestino)){
          try {
            mkdir($carpetaDestino,0777,true);
          } catch (\Throwable $th) {
            echo $th;
          }
          
        }
        
        if(move_uploaded_file($_FILES['docAnexo']['tmp_name'],$rutaFinal)){
          //si se sube, lo insertamos a la base de datos
          $sqlUp = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento,
          fecha_doc,usuario_reg_doc,referencia_id) VALUES ('$nombreArchivo','$rutaFinal',
          'Anexo Ticket','$fecha','$usuarioID','$idTicket')";
          try {
            $queryUp = mysqli_query($conexion, $sqlUp);
            //indicamos un comentario de documento insertado
            $coment = "Se inserta nuevo documento anexo: ".$nombreArchivo;
            $comentario = setComent($usuario,$coment,$idTicket,"Comentario Ticket");
            if($comentario == "OperationSuccess"){
              echo "OperationSuccess";
            }else{
              echo "DataError|Ocurrio un error al finalizar el proceso.";
            }
          } catch (\Throwable $th) {
            //throw $th;
            echo "DataError|Ocurrio un error al guardar el documento".$th;
          }
        }else{
          //no se movio el documento
          echo "DataError|Ocurrio un error al procesar el documento. ".$_FILES['docAnexo']['error'];
        }
         
      }else{
        //supewra el maximo de peso
        echo "DataError|Debe indicar un pezo maximo de 25 Mb.";
      }
    }else{
      //tipo de documento no valido
      echo "DataError|Se indico un tipo de documento invalido.";
    }
    
  
  }elseif(!empty($_POST['statusUpdate'])){
    //seccion para camabia el estatus de un ticket
    $idTicket = $_POST['ticketChange'];
    $nuevoStatus = $_POST['statusUpdate'];
    $usuario = $_SESSION['usNamePlataform'];
    $fechaUpdate = date("Y-m-d");
    $horaUpdate = date("H:m:i");
    //verificamos si el estatus del ticket es rechazado por la entrega
    if($nuevoStatus == "Reabierto"){
      //si entra a esta seccion se toma en cuenta que el ticket se rechazo
      //pero se regresara al estatus de "En Proceso"
      $nuevoStatus = "En Proceso";
      $coment = "Se rechaza la solucion al ticket por el usuario: ".$usuario;
    }else{
      $coment = "Se actualiza el estatus del ticket a: {$nuevoStatus} por el usuario: ".$usuario;
    }

    //antes de procesar la informacion consultamos la informacion del solicitante
    $sqlMail = "SELECT * FROM tickets a INNER JOIN usuarios b 
    ON a.usuario_registra_ticket_id = b.id_usuario INNER JOIN empleados c ON 
    b.empleado_id = c.id_empleado WHERE a.id_ticket = '$idTicket'";

    try {
      $queryMail = mysqli_query($conexion, $sqlMail);
      $fetchMail = mysqli_fetch_assoc($queryMail);
      $nombreUsuario = $fetchMail['nombre']." ".$fetchMail['paterno']." ".$fetchMail['materno'];
      $correoUsuario = $fetchMail['correo'];
      
      $sqlUp = "UPDATE tickets SET estatus_ticket = '$nuevoStatus',
      fecha_actualiza_ticket = '$fechaUpdate', hora_actualiza_ticket = '$horaUpdate' 
      WHERE id_ticket = '$idTicket'";
      try {
        $queryUp = mysqli_query($conexion, $sqlUp);
        //insertamos un comentario de estatus actualizado
        // $coment = "Se actualiza el estatus del ticket a: {$nuevoStatus} por el usaurio: ".$usuario;
        $comentario = setComent($usuario,$coment,$idTicket,"Comentario Ticket");
        if($comentario == "OperationSuccess"){
          $cuerpoMail = "Estimad@ <strong>".$nombreUsuario."</strong>, <br>
          Queremos informarte que ha habido un cambio en el estatus de tu solicitud No. ".$idTicket.",<br><br>
          Fecha y Hora del cambio: ".$fechaUpdate." - ".$horaUpdate.".<br><br>
          Ingresa a la plataforma Syscoop para ver el cambio afectado, <br>
          Por favor, no dudes en ponerte en contacto con nosotros si <br>
          necesitas m&#225;s informaci&#243;n o si tienes alguna pregunta adicional sobre tu solicitud.";
          $asun = "Actualizacion del Ticket";
          $subAsun = "Nueva Modificacion";
          echo mailSend($correoUsuario,$asun,$cuerpoMail,$subAsun,"OperationSuccess","");
          
          // echo "OperationSuccess";
        }else{
          //para este punto la tarea se completo, con errores
          echo "DataError|La tarea se ha completado con errores, reporte al area de sistemas";
        }
      } catch (Throwable $th) {
        //fallo la actualizacion
        echo "DataError|Ha ocurrido un error al actualizar el estatus: ".$th;
      }
    } catch (Throwable $th) {
      //error al consultar los datos del usuario
      echo "DataError|Ha ocurrido un error al consultar informacion del usuario: ".$th;
    }

    

  }elseif(!empty($_POST['infoTicketClose'])){
    //seccion para dar por cerrado un ticket
    $idTicket = $_POST['infoTicketClose'];
    $usuario = $_SESSION['usNamePlataform'];
    $fechaCierre = date('Y-m-d');
    $horaCierre = date('H:m:i');

    $sqlMail = "SELECT * FROM tickets a INNER JOIN usuarios b 
    ON a.usuario_seguimiento_ticket_id = b.id_usuario INNER JOIN empleados c 
    ON b.empleado_id = c.id_empleado WHERE a.id_ticket = '$idTicket'";
    try {
      $queryMail = mysqli_query($conexion, $sqlMail);
      $fetchMail = mysqli_fetch_assoc($queryMail);
      $nombreUsuario = $fetchMail['nombre']." ".$fetchMail['paterno']." ".$fetchMail['materno'];
      $correoUsuario = $fetchMail['correo'];

      $sqlClose = "UPDATE tickets SET estatus_ticket = 'Cerrado',
      fecha_termino_ticket = '$fechaCierre', hora_termino_ticket = '$horaCierre' WHERE 
      id_ticket = '$idTicket'";
      try {
        $queryClose = mysqli_query($conexion, $sqlClose);
        //insertamos el comentario de que se cerro el ticket
        $coment = "Se da por cerrado el ticket por el usuario: ".$usuario;
        $comentario = setComent($usuario,$coment,$idTicket,"Comentario Ticket");
        if($comentario == "OperationSuccess"){

          $cuerpoMail = "Estimad@ <strong>".$nombreUsuario."</strong>, <br>
          Nos complace informarte que el ticket No. ".$idTicket." ha sido declarado resuelto,<br>
          Fecha y Hora del cambio: ".$fechaCierre." - ".$horaCierre.".<br><br>
          Ya puedes ingresar a la plataforma Syscoop para ver resumen de este.<br>";
          $asun = "Ticket Solucionado";
          $subAsun = "Ticket Cerrado";
          $enviado = mailSend($correoUsuario,$asun,$cuerpoMail,$subAsun,"OperationSuccess","");
          echo "OperationSuccess";
        }else{
          //para este punto la tarea se completo, con errores
          echo "DataError|La tarea se ha completado con errores, reporte al area de sistemas";
        }
      } catch (\Throwable $th) {
        echo "DataError|Ha ocurrido un error al dar por terminado el ticket: ".$th;
      }
    } catch (\Throwable $th) {
      //no se pudo consultar la informacion del usuario responsable
      echo "DataError|Ha ocurrido un error al consultar la informacion del usuario: ".$th;
    }
  }elseif(!empty($_POST['filtroTickets'])){
    //seccion para realizar un filtro de tickets
    $estatus = $_POST['filtroStatus'];
    $tipo = $_POST['filtroTipo'];
    $usuario = $_POST['filtroUsuario'];
    $uu = $_SESSION['usNamePlataform'];
    $permUser = getPermisos($uu);
    $permiso = json_decode($permUser);
    $filtro = "";

    if(!empty($estatus)){
      if($filtro == ""){
        $filtro = "a.estatus_ticket = '$estatus'";
      }
    }

    if(!empty($tipo)){
      if($filtro == ""){
        $filtro = "a.tipo_ticket_id = '$tipo'";
      }else{
        $filtro = $filtro." AND a.tipo_ticket_id = '$tipo'";
      }
    }
    if(!empty($usuario)){
      if($filtro == ""){
        $filtro = "a.usuario_registra_ticket_id = '$usuario'";
      }else{
        $filtro = $filtro." AND a.usuario_registra_ticket_id = '$usuario'";
      }
    }


    $sql = "SELECT * FROM tickets a INNER JOIN tipoTickets b ON 
    a.tipo_ticket_id = b.idTipoTicket INNER JOIN estatus_ticket c
    ON a.estatus_ticket = c.nombreEstatusTicket WHERE $filtro ORDER BY a.fecha_registro_ticket DESC";

    
    // $sql = "SELECT * FROM tickets WHERE $filtro";
    try {
      $query = mysqli_query($conexion, $sql);
      $data = [];
      $i =0;
      while($fetch = mysqli_fetch_assoc($query)){
        //consultamos los datos del usuario solicitante
        $usuarioReg = getUserById($fetch['usuario_registra_ticket_id']);
        if($fetch['usuario_seguimiento_ticket_id'] == NULL){
          $usuarioRes = "Sin Asignar";
        }else{
          $usuarioRes = getUserById($fetch['usuario_seguimiento_ticket_id']);
        }

        $fechaRegistro = $fetch['fecha_registro_ticket'];
        $diasAtencion = $fetch['diasAtencion'];
        //hacemos la suma de los dias

        $fechaInicial = new DateTime($fechaRegistro); // Fecha inicial
        $diasHabiles = $diasAtencion; // Número de días hábiles a sumar
        $contadorDiasHabiles = 0; // Contador de días hábiles

        // Crear un bucle para sumar los días hábiles
        while ($contadorDiasHabiles < $diasHabiles) {
            $fechaInicial->modify('+1 day');
            // Verificar si es un día hábil
            if ($fechaInicial->format('w') != 0 && $fechaInicial->format('w') != 6) {
                $contadorDiasHabiles++; // Incrementar el contador solo si es un día hábil
            }
        }
        // Obtener la fecha final como cadena
        $fechaFinalString = $fechaInicial->format('Y-m-d');

        if($permiso->ver_controles){
          //aqui le habilitaremos la opcion de ver
        }elseif($uu == $usuarioReg || $uu == $usuarioRes){
          //si es el responsable o el propietario tambien vera
        }else{
          //si no pos no podra ver nada
        }

        $datos = ["registra"=>$usuarioReg,"responsable"=>$usuarioRes,
        "fechaTermino"=>$fechaFinalString,"datos"=>$fetch];
        $data[$i] = $datos;
        $i++;
      }//fin del while

      echo json_encode($data);
    } catch (\Throwable $th) {
      echo "DataError|Ha ocurrido un error al aplicar el filtro ".$th;
    }
  }elseif(!empty($_POST['infoTicketSolucionado'])){
    //seccion para cambiar el estatus a solucionado
    $idTicket = $_POST['infoTicketSolucionado'];
    $usuario = $_SESSION['usNamePlataform'];
    $fechaCierre = date('Y-m-d');
    $horaCierre = date('H:m:i');

    $sqlMail = "SELECT * FROM tickets a INNER JOIN usuarios b 
    ON a.usuario_seguimiento_ticket_id = b.id_usuario INNER JOIN empleados c 
    ON b.empleado_id = c.id_empleado WHERE a.id_ticket = '$idTicket'";
    try {
      $queryMail = mysqli_query($conexion, $sqlMail);
      $fetchMail = mysqli_fetch_assoc($queryMail);
      $nombreUsuario = $fetchMail['nombre']." ".$fetchMail['paterno']." ".$fetchMail['materno'];
      $correoUsuario = $fetchMail['correo'];

      $sqlClose = "UPDATE tickets SET estatus_ticket = 'Resuelto',
      fecha_termino_ticket = '$fechaCierre', hora_termino_ticket = '$horaCierre' WHERE 
      id_ticket = '$idTicket'";
      try {
        $queryClose = mysqli_query($conexion, $sqlClose);
        //insertamos el comentario de que se cerro el ticket
        $coment = "Se da por solucionado el ticket por el usuario: ".$usuario;
        $comentario = setComent($usuario,$coment,$idTicket,"Comentario Ticket");
        if($comentario == "OperationSuccess"){

          $cuerpoMail = "Estimad@ <strong>".$nombreUsuario."</strong>, <br>
          Nos complace informarte que el ticket No. ".$idTicket." ha sido declarado resuelto,<br>
          Fecha y Hora del cambio: ".$fechaCierre." - ".$horaCierre.".<br><br>
          Ya puedes ingresar a la plataforma Syscoop para ver resumen de este.<br>";
          $asun = "Ticket Solucionado";
          $subAsun = "Ticket Solucionado";
          $enviado = mailSend($correoUsuario,$asun,$cuerpoMail,$subAsun,"OperationSuccess","");
          echo "OperationSuccess";
        }else{
          //para este punto la tarea se completo, con errores
          echo "DataError|La tarea se ha completado con errores, reporte al area de sistemas";
        }
      } catch (\Throwable $th) {
        echo "DataError|Ha ocurrido un error al dar por terminado el ticket: ".$th;
      }
    } catch (\Throwable $th) {
      //no se pudo consultar la informacion del usuario responsable
      echo "DataError|Ha ocurrido un error al consultar la informacion del usuario: ".$th;
    }
  }
}


//Fecha Registro
//Fecha Termino
//Estatus = Redirigido
//Estatus2 = En Espera
//No
//Usuario Registra
//Descripcion
//comentario
//prioridad
//Fecha Actualizacion

//En Espera
//Aceptado
//Redirigido
//En Proceso
//Cancelado
//Finalizado
?>