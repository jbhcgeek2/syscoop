<?php
error_reporting(0);
session_start();

if(!empty($_SESSION['usNamePlataform'])){
  include('../operations/usuarios.php');
  include('../operations/functionsComents.php');
  include('../_con.php');

  if(!empty($_POST['cons'])){
    //consultamos las actas
    $consejo = $_POST['cons'];
    $tipo = $_POST['tipo'];
    $fecha = $_POST['fechAc'];
    $numeral = $_POST['numeral'];
    // $fechaAux = new DateTime($fecha);
    $fechaAux = strtotime($fecha);
    // echo $fechaAux;

    $mes = date("n",$fechaAux);
    // echo $mes;

    $sql1 = "SELECT * FROM actas WHERE consejo_id = $consejo AND tipo_acta = '$tipo' 
    AND acta_num = '$mes' ORDER BY fecha_acta ASC";

    $query1 = mysqli_query($conexion, $sql1);
    $combo = "";
    while($fetch1 = mysqli_fetch_assoc($query1)){
      $numeral = $fetch1['numeral'];
      $combo .= "<option value = '$numeral' selected>$numeral</option>";
    }

    echo $combo;
  }elseif(!empty($_POST['actaConsejo'])){
    $consejoActa = $_POST['actaConsejo'];
    $tipoActa = $_POST['tipoActa'];
    $fechaActa = $_POST['actaFecha'];
    $numeroActa = $_POST['numeroActa'];
    $numeralNuevo = $_POST['numeralActa'];
    $numeroPuntos = $_POST['numControl'];
    $puntos = "";
    $fech = explode("-",$fechaActa);
    $nuevaFechaAux =  $fech[0]."-".$fech[1]."-01";
    $fechaActual = date("Y-m-d");
    $usuario = $_SESSION['usNamePlataform'];
    $idUsuario = getUsuarioId($usuario);
    $manualesAutori = $_POST['manualesAutori'];
    
    //verificamos si no existe el numero ingresado
    $sql1 = "SELECT * FROM actas WHERE (consejo_id = '$consejoActa' AND tipo_acta = '$tipoActa' 
    AND acta_num = '$numeroActa' AND numeral = '$numeralNuevo') AND fecha_acta >= '$nuevaFechaAux'";
    $query1 = mysqli_query($conexion, $sql1);
    $sql3 = "SELECT * FROM consejos WHERE id_consejo = '$consejoActa'";
    $query3 = mysqli_query($conexion, $sql3);
    $fetch3 = mysqli_fetch_assoc($query3);
    $nombreConsejo = $fetch3['nombre_consejo'];


    if(mysqli_num_rows($query1) == 0){
      for($x = 1; $x <= $numeroPuntos; $x++){
        $campo = $_POST['puntoActa'.$x];
        if($x == 1){
          $puntos = $campo;
        }else{
          $puntos .= "_|_".$campo;
        }
      }//fin for de puntos
      //verificamos si existen manuales a autorizar
      $fechasManuales = [];
      if($manualesAutori != ""){
        $auxManu = explode("|",$manualesAutori);
        for($manu = 0; $manu < count($auxManu); $manu++){
          $idManu = $auxManu[$manu];
          $sqlMan = "SELECT * FROM manuales_formatos WHERE id_man_form = '$idManu'";
          $queryMan = mysqli_query($conexion, $sqlMan);
          $fetchMan = mysqli_fetch_assoc($queryMan);
          $nombreManu = $fetchMan['nombre_man_form'];
          $tipoDoc = $fetchMan['tipo_doc'];
          $fechasManuales[$manu] = $fetchMan['fecha_publicacion'];

          $texto = "Se autoriza el ".$tipoDoc." con nombre ".$nombreManu." version ".$fetchMan['edicion'];
          $puntos .= "_|_".$texto;
        }//fin del for
      }
      //insertamos el acta
      try {
        $sql2 = "INSERT INTO actas (consejo_id,tipo_acta,acta_num,numeral,fecha_acta,puntos_actas,
        fecha_registro_acta,usuario_registro_acta) VALUES ('$consejoActa','$tipoActa','$numeroActa',
        '$numeralNuevo','$fechaActa','$puntos','$fechaActual','$idUsuario')";

        $horaActual = date('H:i:s');
        $query2 = mysqli_query($conexion, $sql2);
        $idActa = mysqli_insert_id($conexion);
        //actualizamos todos los documentos que se autorizaron
        if($manualesAutori != ""){
          $auxManu2 = explode("|",$manualesAutori);
          for($manu2 = 0; $manu2 < count($auxManu2); $manu2++){
            $idManu = $auxManu2[$manu2];
            if($fechasManuales[$manu2] == "0000-00-00" || $fechasManuales[$manu2] == "1900-01-01"){
              //en caso de que la fecha de publicacion sea invalida, la actualizaremos
              $sqlupdate = "UPDATE manuales_formatos SET acta_id = '$idActa', 
              fecha_actualizacion = '$fechaActa',fecha_publicacion = '$fechaActa' WHERE id_man_form = '$idManu'";
            }else{
              $sqlupdate = "UPDATE manuales_formatos SET acta_id = '$idActa', 
              fecha_actualizacion = '$fechaActa' WHERE id_man_form = '$idManu'";
            }
            
            $queryUpdate = mysqli_query($conexion, $sqlupdate);

            $comentario = "Se autoriza el manual dentro del acta ".$tipoActa." del ".$nombreConsejo." numeral ".$numeroActa.$numeralNuevo;

            $sqlCom = "INSERT INTO movimientos (fecha_movimiento,hora_movimiento,usuario_movimiento,
            tipo_movimiento,descripcion_movimiento,referencia_id) VALUES ('$fechaActual','$horaActual','$usuario',
            'Documentos','$comentario','$idManu')";
            $queryCom = mysqli_query($conexion, $sqlCom);
          }
          echo "DataSucess";
        }else{
          echo "DataSucess";
        }
      } catch (Throwable $th) {
        echo "DataError|Ocurrio un error al insertar el acta, intente de nuevo.".$th;
      }
      
      
    }else{
      echo "DataError|El numeral de acta ya existe, favor de verificarlo";
    }
    
    

    
  }elseif(!empty($_POST['verActasConsejo'])){
    $consejo = $_POST['verActasConsejo'];
    $anio = date('Y');
    $anio = $anio-1;
    $mes = date('m');
    $fechaBuscar = $anio."-".$mes."-01";
    $sql1 = "SELECT * FROM actas WHERE consejo_id = '$consejo' AND 
    fecha_acta > '$fechaBuscar' ORDER BY fecha_acta ASC";
    $query1 = mysqli_query($conexion, $sql1);
    $comboNuevo = "<option value=''>Seleccione...</option><option value='_'>Acta Pendiente</option>";
    $datos = [];
    if(mysqli_num_rows($query1) > 0){
      $i = 0;
      while($fetch1 = mysqli_fetch_assoc($query1)){
        $idActa = $fetch1['id_acta'];
        $actaNum = $fetch1['tipo_acta']." - ".$fetch1['acta_num'].$fetch1['numeral']." - ".$fetch1['fecha_acta'];
        $fecha = $fetch1['fecha_acta'];
        $datos[$i] = [$idActa,$actaNum,$fecha];
        $comboNuevo .= "<option value='$idActa'>$actaNum</option>";
        $i++;
      }//fin while1
    }else{

    }

    echo json_encode($datos);
  }elseif(!empty($_POST['updateDocActa'])){
    $docActa = $_FILES['docEnvio']['tmp_name'];
    $tipoDoc = $_POST['tipoDocActa'];
    $acta = $_POST['updateDocActa'];
    $usuario = $_SESSION['usNamePlataform'];
    $usuarioAct = getUsuarioId($_SESSION['usNamePlataform']);
    $campoDb = "";
    $texto = "";
    $extencion = "";

    if(!empty($_FILES['docEnvio']['tmp_name'])){
      $valida = 0;
      if($tipoDoc == "actaLectura" && $_FILES['docEnvio']['type'] == "application/pdf"){
        $valida = 1;
        $campoDb = "acta_lectura";
        $texto = "Se actualiza el documento de lectura";
        $extencion = ".pdf";
      }elseif($tipoDoc == "actaEditable"){
        $valida = 1;
        $campoDb = "acta_editable";
        $texto = "Se actualiza el documento editable";
        $aux = $_FILES['docEnvio']['name'];
        $aux = explode(".",$aux);
        $n = count($aux);
        $n = $n-1;
        $extencion = ".".$aux[$n];
      }

      if($valida == 1){
        //las actas debran guardarse dentro de la carpeta /docs/Actas/anio/mes
        //por lo que primero verificaremos la existencia del directorio, en caso
        //que no se encuentre, lo crearemos
        $sql = "SELECT * FROM actas WHERE id_acta = '$acta'";
        $query = mysqli_query($conexion, $sql);
        if(mysqli_num_rows($query) > 0){
          $fetch = mysqli_fetch_assoc($query);
          $numActa = $fetch['acta_num'];
          $numeral = "";
          if($fetch['numeral'] != ""){
            $numeral = $fetch['numeral'];
          }
          $fechaActa = explode("-",$fetch['fecha_acta'])[0];
          $raizActa = "../../docs/Actas/".$fechaActa;
          $rutas = 0;
          if(!file_exists($raizActa)){
            if(!mkdir($raizActa, 0777)){
              $rutas = 1;
            }
          }
          $rutaNumeral = $raizActa."/".$numActa;
          if(!file_exists($rutaNumeral)){
            if(!mkdir($rutaNumeral, 0777)){
              $rutas = $rutas+1;
            }
          }

          if($rutas == 0){
            $nombreArchivo = "Acta_".$fetch['tipo_acta']."-".$numActa.$numeral.$extencion;
            $rutaFinal = $rutaNumeral."/".$nombreArchivo;
            if(move_uploaded_file($_FILES['docEnvio']['tmp_name'],$rutaFinal)){
              //insertamos el documento en la base de datos
              $fecha = date('Y-m-d');
              $sql2 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento,
              fecha_doc,usuario_reg_doc,referencia_id) VALUES ('$nombreArchivo',
              '$rutaFinal','Acta','$fecha','$usuarioAct','$acta')";
              try {
                $query2 = mysqli_query($conexion, $sql2);
                $idDoc = mysqli_insert_id($conexion);
                $sql3 = "UPDATE actas SET $campoDb = '$idDoc' WHERE id_acta = '$acta'";
                try {
                  $query3 = mysqli_query($conexion, $sql3);
                  //insertamos un comentario de actualizacion
                  $comentario = setComent($usuario,$texto,$acta,'Acta');
                  if($comentario == "OperationSuccess"){
                    echo $comentario;
                  }else{
                    echo "DataError|Ocurrio un error al finalizar la operacion, contacte a sistemas.";
                  }
                } catch (Throwable $th) {
                  echo "DataError|No fue posible actualizar la informacion del acta, contacte a sistemas.";
                }
              } catch (Throwable $th) {
                echo "DataError|No fue posible guardar el acta en la base de datos";
              }
              
            }else{
              echo "DataError|No fue posible subir el documento.";
            }
          }else{
            echo "DataError|No fue posible crear los directorios destino.";
          }
        }else{
          echo "DataError|No fue posible localizar el acta.";
        }
      }else{
        echo "DataError|El tipo de documento indicado no es valido.";
      }
    }else{
      echo "DataError|No se ha indicado documentacion valida.";
    }
  }elseif(!empty($_POST['actaUpdateAcu'])){
    $idActa = $_POST['actaUpdateAcu'];
    $acuerdos = $_POST['acuUpdates'];

    $sql = "UPDATE actas SET puntos_actas = '$acuerdos' WHERE id_acta = '$idActa'";
    try {
      $query = mysqli_query($conexion, $sql);
      echo "OperationSuccess";
    } catch (Throwable $th) {
      echo "DataError|Error al actualizar la base de datos";
    }

  }elseif(!empty($_POST['buscarActa'])){
    $datoActa = $_POST['buscarActa'];

    $sql = "SELECT * FROM  actas a INNER JOIN consejos b ON a.consejo_id = b.id_consejo 
    WHERE a.puntos_actas LIKE '%$datoActa%' ORDER BY a.fecha_registro_acta DESC";
    
    try {
      $query = mysqli_query($conexion, $sql);
      if(mysqli_num_rows($query) > 0){
        $x = 0;
        $datos = [];
        while($fetch = mysqli_fetch_assoc($query)){
          $datos[$x] = $fetch;
          $x++;
        }//fin del while de datos
        echo json_encode($datos);
      }else{
        echo "NoDataResult";
      }
    } catch (Throwable $th) {
      echo "DataError|Ocurrion un error al realizar la busqueda";
    }
  }
}

?>