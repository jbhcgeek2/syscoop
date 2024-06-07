<?php 
session_start();

if(!empty($_SESSION['usNamePlataform'])){ 
  //verificamos si se acciono el boton de guardar
  include('../operations/usuarios.php');
  include('../operations/functionsComents.php');
  include('../_con.php');
  $usuarioNombre = $_SESSION['usNamePlataform'];
  $permUser = getPermisos($usuarioNombre);
  $permiso = json_decode($permUser);

  if(isset($_POST['nombreDoc'])){
    $nombreDoc = $_POST['nombreDoc']; 
    $tipoDoc = $_POST['tipoDoc'];
    $versionDoc = $_POST['versionDoc'];
    $fechaPublicacion = $_POST['fechaPublicacion'];
    $fechaUltimaActualizacion = $_POST['fechaActualizacion'];
    $fechaActual = date('Y-m-d');
    $docLectura = $_FILES['docLectura']['tmp_name'];
    $docEditable = $_FILES['docEditable']['tmp_name'];
    $usuarioAct = getUsuarioId($_SESSION['usNamePlataform']);
    //$actaAutorizacion = $_POST['actaNumAutoriza']; contiene el id del acta

    if($tipoDoc == "Manual"){

      $departamentoDoc = $_POST['departamentoDoc'];
      $puestoEncargado = $_POST['puestoEncargado'];
      $codificacion = $_POST['codiDoc'];
      $actaAutorizacion = $_POST['actaNumAutoriza'];
  
      if(!empty($_FILES['docLectura']['tmp_name']) && !empty($_FILES['docEditable']['tmp_name'])){
        //procedemos primero asubir la documentacion
        //verificamos que el documento de lectura este en formato PDF
        if($_FILES['docLectura']['type'] == "application/pdf"){
          // $auxLectura = explode(".",$_FILES['docEditable']['name']);
          // $extencion = $auxLectura[1];
          $auxExt = $_FILES['docEditable']['name'];
          $auxExt = explode(".",$auxExt);
          $n = count($auxExt);
          $n = $n-1;
          $extencion = ".".$auxExt[$n];

          $carpetaDestino = "../../docs/Formatos/".$codificacion;
          $rutaDocLectura = $carpetaDestino."/Lec_".$codificacion."_".date('Ymds').str_replace(".","-",$versionDoc).".pdf";
          $rutaDocEditable = $carpetaDestino."/Edit_".$codificacion."_".date('Ymds').str_replace(".","-",$versionDoc).".".$extencion;
          //antes de realizar el registro, creamos las careptas de estos manuales dentro de la codificacion
          try{
            mkdir($carpetaDestino,0777);
            //una vez creada procedemos a mover los documentos
            if(move_uploaded_file($_FILES['docLectura']['tmp_name'],$rutaDocLectura) && 
              move_uploaded_file($_FILES['docEditable']['tmp_name'],$rutaDocEditable)){
                try{
                  $sql1 = "INSERT INTO manuales_formatos (nombre_man_form,fecha_publicacion,
                  fecha_registro,fecha_ultima_mod,edicion,usuario_registro,version_editable,version_lectura,
                  departamento_doc_id,puesto_id,codificacion,acta_id,tipo_doc) VALUES ('$nombreDoc','$fechaPublicacion','$fechaActual',
                  '$fechaUltimaActualizacion','$versionDoc','$usuarioAct','1','1','$departamentoDoc','$puestoEncargado',
                  '$codificacion','$actaAutorizacion','$tipoDoc')";
                  $query1 = mysqli_query($conexion, $sql1);
                  //obtenemos el ID del documento, consultando el ultimo ID insertado
                  $sql2 = "SELECT id_man_form,edicion FROM manuales_formatos ORDER BY id_man_form DESC LIMIT 1";
                  $query2 = mysqli_query($conexion,$sql2);
                  $fetch2 = mysqli_fetch_assoc($query2);
                  $idManual = $fetch2['id_man_form'];
                  if($fetch2['edicion'] == $versionDoc){
                    //insertamos la documentacion de lectura
                    try{
                      $sql3 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento,fecha_doc,
                      referencia_id) VALUES ('$nombreDoc','$rutaDocLectura','$tipoDoc','$fechaActual','$idManual')";
                      $query3 = mysqli_query($conexion, $sql3);
                      $idDocLectura = mysqli_insert_id($conexion);
                      try{
                        $sql4 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento,fecha_doc,
                        referencia_id) VALUES ('$nombreDoc','$rutaDocEditable','$tipoDoc','$fechaActual','$idManual')";
                        $query4 = mysqli_query($conexion, $sql4);
                        $idDocEditable = mysqli_insert_id($conexion);
                        //por ultimo realiamos un cambio en el documento, para asignar la IP
                        try{
                          $sql5 = "UPDATE manuales_formatos SET version_editable = '$idDocEditable', version_lectura = '$idDocLectura' 
                          WHERE id_man_form = '$idManual'";
                          $query5 = mysqli_query($conexion, $sql5);
                          $comenManual = "Se inserta el  ".$nombreDoc." ".$nombreDoc;
                          $setComent = setComent($usuarioNombre,$comenManual,$idManual,"Documentos");
                          if($setComent == "OperationSuccess"){
                            if(!empty($actaAutorizacion)){
                              //se toma un acta para asignarlo
                              $textoAutori = "Se aprueba el manual con nombre ".$nombreDoc;
                              $sql6 = "SELECT * FROM actas WHERE id_acta = '$actaAutorizacion'";
                              $query6 = mysqli_query($conexion, $sql6);
                              $fetch6 = mysqli_fetch_assoc($query6);
                              $acuerdos = $fetch6['puntos_actas'];
                              if($acuerdos == "" || $acuerdos == " "){
                                $acuerdos = $textoAutori;
                              }else{
                                $acuerdos .= "_|_".$textoAutori;
                              }
                              $sql7 = "UPDATE actas SET puntos_actas = '$acuerdos' WHERE id_acta = '$actaAutorizacion'";
                              try {
                                $query7 = mysqli_query($conexion, $sql7);
                                echo "ProcessComplete";
                              } catch (Throwable $th) {
                                echo "ProcessComplete2";
                              }
                              
                            }else{
                              echo "ProcessComplete";
                            }
                          }else{
                            echo "ProcessComplete2";
                          }
                        }catch(Throwable $e){
                          echo "DataError|Ocurrio un error al relacionar la documentacion, contacte a sistemas: ".$e;
                        }
                      }catch(Throwable $e){
                        echo "DataError|Ocurrio un error al guardar la documentacion editable, contacte a sistemas.";
                      }
                    }catch(Throwable $e){
                      echo "DataError|Ocurrio un error al guardar la documentacion de lectura, contacte a sistemas.";
                    }
                  }else{
                    //en este punto no podemos hacer nada, por lo que diremos que se contacte a sistemas
                    echo "DataError|Ocurrio un error de base de datos, contacte a sistemas.";
                  }
                }catch(Throwable $e){
                  echo "DataError|Ocurrio un error al realizar el registro del documento";
                }
            }else{
              echo "DataError|No fue posible almacenar los documentos en el servidor.";
            }
          }catch(Throwable $e){
            echo "DataError|No fue posible crear la carpeta del documento";
          }
        }else{
          echo "DataError|El documento de lectura debe tener formato PDF.";
        }
      }else{
        echo "DataError|No se indicaro documentos";
      }





    }elseif($tipoDoc == "Anexo"){

      $codificacion = $_POST['codiDoc'];
      $idManualInsert = $_POST['manualAnexo'];

      if(!empty($_FILES['docLectura']['tmp_name']) && !empty($_FILES['docEditable']['tmp_name'])){
        //procedemos primero asubir la documentacion
        //verificamos que el documento de lectura este en formato PDF
        if($_FILES['docLectura']['type'] == "application/pdf"){
          $carpetaDestino = "../../docs/Formatos/".$codificacion;

          $auxExt = $_FILES['docEditable']['name'];
          $auxExt = explode(".",$auxExt);
          $n = count($auxExt);
          $n = $n-1;
          $extencion = ".".$auxExt[$n];

          $rutaDocLectura = $carpetaDestino."/Lec_".$codificacion."_".str_replace(".","-",$versionDoc).".pdf";
          $rutaDocEditable = $carpetaDestino."/Edit_".$codificacion."_".str_replace(".","-",$versionDoc).$extencion;
          //antes de realizar el registro, creamos las careptas de estos manuales dentro de la codificacion
          try{
            mkdir($carpetaDestino,0777);
            //una vez creada procedemos a mover los documentos
            if(move_uploaded_file($_FILES['docLectura']['tmp_name'],$rutaDocLectura) && 
              move_uploaded_file($_FILES['docEditable']['tmp_name'],$rutaDocEditable)){
                try{
                  //antes de insertar el anexo, consutamos los datos del manual
                  $sql01 = "SELECT * FROM manuales_formatos WHERE id_man_form = '$idManualInsert'";
                  $query01 = mysqli_query($conexion, $sql01);
                  $fetch01 = mysqli_fetch_assoc($query01);
                  $puestoEncargado = $fetch01['puesto_id'];
                  $actaAutorizacion = '0';
                  $departamentoDoc = $idManualInsert;

                  $sql1 = "INSERT INTO manuales_formatos (nombre_man_form,fecha_publicacion,
                  fecha_registro,fecha_ultima_mod,edicion,usuario_registro,version_editable,version_lectura,
                  departamento_doc_id,puesto_id,codificacion,acta_id,tipo_doc) VALUES ('$nombreDoc','$fechaPublicacion','$fechaActual',
                  '$fechaUltimaActualizacion','$versionDoc','$usuarioAct','1','1','$departamentoDoc','$puestoEncargado',
                  '$codificacion','$actaAutorizacion','$tipoDoc')";
                  $query1 = mysqli_query($conexion, $sql1);
                  //obtenemos el ID del documento, consultando el ultimo ID insertado
                  $sql2 = "SELECT id_man_form,edicion FROM manuales_formatos ORDER BY id_man_form DESC LIMIT 1";
                  $query2 = mysqli_query($conexion,$sql2);
                  $fetch2 = mysqli_fetch_assoc($query2);
                  $idManual = $fetch2['id_man_form'];
                  if($fetch2['edicion'] == $versionDoc){
                    //insertamos la documentacion de lectura
                    try{
                      $sql3 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento,fecha_doc,
                      referencia_id) VALUES ('$nombreDoc','$rutaDocLectura','$tipoDoc','$fechaActual','$idManual')";
                      $query3 = mysqli_query($conexion, $sql3);
                      $idDocLectura = mysqli_insert_id($conexion);
                      try{
                        $sql4 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento,fecha_doc,
                        referencia_id) VALUES ('$nombreDoc','$rutaDocEditable','$tipoDoc','$fechaActual','$idManual')";
                        $query4 = mysqli_query($conexion, $sql4);
                        $idDocEditable = mysqli_insert_id($conexion);
                        //por ultimo realiamos un cambio en el documento, para asignar la IP
                        try{
                          $sql5 = "UPDATE manuales_formatos SET version_editable = '$idDocEditable', version_lectura = '$idDocLectura' 
                          WHERE id_man_form = '$idManual'";
                          $query5 = mysqli_query($conexion, $sql5);
                          $comentario = "Se inserta el ".$tipoDoc." $nombreDoc.";

                          $setComent = setComent($usuarioNombre,$comentario,$idManual,"Documentos");
                          if($setComent == "OperationSuccess"){
                            echo "ProcessComplete";
                          }else{
                            echo "ProcessComplete2";
                          }
                        }catch(Throwable $e){
                          echo "DataError|Ocurrio un error al relacionar la documentacion, contacte a sistemas";
                        }
                      }catch(Throwable $e){
                        echo "DataError|Ocurrio un error al guardar la documentacion editable, contacte a sistemas.";
                      }
                    }catch(Throwable $e){
                      echo "DataError|Ocurrio un error al guardar la documentacion de lectura, contacte a sistemas.";
                    }
                  }else{
                    //en este punto no podemos hacer nada, por lo que diremos que se contacte a sistemas
                    echo "DataError|Ocurrio un error de base de datos, contacte a sistemas.";
                  }
                }catch(Throwable $e){
                  echo "DataError|Ocurrio un error al realizar el registro del documento";
                }
            }else{
              echo "DataError|No fue posible almacenar los documentos en el servidor.";
            }
          }catch(Throwable $e){
            echo "DataError|No fue posible crear la carpeta del documento";
          }
        }else{
          echo "DataError|El documento de lectura debe tener formato PDF.";
        }
      }else{
        echo "DataError|No se indicaro documentos";
      }




    }elseif($tipoDoc == "Formato"){




      $codificacion = "CT-FORMAT";
      //$idManualInsert = $_POST['manualAnexo'];
      $auxName = date("Ymd-His");
      $auxNombreDoc = str_replace(" ","",$nombreDoc);
      $auxName2 = substr($auxNombreDoc,0,10);
      $departamentoDoc = $_POST['departamentoDoc'];
      $puestoEncargado = $_POST['puestoEncargado'];

      if(!empty($_FILES['docLectura']['tmp_name']) && !empty($_FILES['docEditable']['tmp_name'])){
        //procedemos primero asubir la documentacion
        //verificamos que el documento de lectura este en formato PDF
        if($_FILES['docLectura']['type'] == "application/pdf"){

          $auxExt = $_FILES['docEditable']['name'];
          $auxExt = explode(".",$auxExt);
          $n = count($auxExt);
          $n = $n-1;
          $extencion = ".".$auxExt[$n];

          $carpetaDestino = "../../docs/Formatos/".$codificacion;
          $rutaDocLectura = $carpetaDestino."/Lec_".$auxName2."_".$auxName.".pdf";
          $rutaDocEditable = $carpetaDestino."/Edit_".$auxName2."_".$auxName.$extencion;
          //antes de realizar el registro, creamos las careptas de estos manuales dentro de la codificacion
          try{
            mkdir($carpetaDestino,0777);
            //una vez creada procedemos a mover los documentos
            if(move_uploaded_file($_FILES['docLectura']['tmp_name'],$rutaDocLectura) && 
              move_uploaded_file($_FILES['docEditable']['tmp_name'],$rutaDocEditable)){
                try{

                  $sql1 = "INSERT INTO manuales_formatos (nombre_man_form,fecha_publicacion,
                  fecha_registro,fecha_ultima_mod,edicion,usuario_registro,version_editable,version_lectura,
                  departamento_doc_id,puesto_id,codificacion,acta_id,tipo_doc) VALUES ('$nombreDoc','$fechaPublicacion','$fechaActual',
                  '$fechaUltimaActualizacion','$versionDoc','$usuarioAct','1','1','$departamentoDoc','$puestoEncargado',
                  '$codificacion','0','$tipoDoc')";
                  $query1 = mysqli_query($conexion, $sql1);
                  //obtenemos el ID del documento, consultando el ultimo ID insertado
                  $sql2 = "SELECT id_man_form,edicion FROM manuales_formatos ORDER BY id_man_form DESC LIMIT 1";
                  $query2 = mysqli_query($conexion,$sql2);
                  $fetch2 = mysqli_fetch_assoc($query2);
                  $idManual = $fetch2['id_man_form'];
                  if($fetch2['edicion'] == $versionDoc){
                    //insertamos la documentacion de lectura
                    try{
                      $sql3 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento,fecha_doc,
                      referencia_id) VALUES ('$nombreDoc','$rutaDocLectura','$tipoDoc','$fechaActual','$idManual')";
                      $query3 = mysqli_query($conexion, $sql3);
                      $idDocLectura = mysqli_insert_id($conexion);
                      try{
                        $sql4 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento,fecha_doc,
                        referencia_id) VALUES ('$nombreDoc','$rutaDocEditable','$tipoDoc','$fechaActual','$idManual')";
                        $query4 = mysqli_query($conexion, $sql4);
                        $idDocEditable = mysqli_insert_id($conexion);
                        //por ultimo realiamos un cambio en el documento, para asignar la IP
                        try{
                          $sql5 = "UPDATE manuales_formatos SET version_editable = '$idDocEditable', version_lectura = '$idDocLectura' 
                          WHERE id_man_form = '$idManual'";
                          $query5 = mysqli_query($conexion, $sql5);
                          $comentario = "Se inserta el ".$tipoDoc.$nombreDoc;

                          $setComent = setComent($usuarioNombre,$comentario,$idManual,"Documentos");
                          if($setComent == "OperationSuccess"){
                            echo "ProcessComplete";
                          }else{
                            echo "ProcessComplete2";
                          }
                        }catch(Throwable $e){
                          echo "DataError|Ocurrio un error al relacionar la documentacion, contacte a sistemas";
                        }
                      }catch(Throwable $e){
                        echo "DataError|Ocurrio un error al guardar la documentacion editable, contacte a sistemas.";
                      }
                    }catch(Throwable $e){
                      echo "DataError|Ocurrio un error al guardar la documentacion de lectura, contacte a sistemas.";
                    }
                  }else{
                    //en este punto no podemos hacer nada, por lo que diremos que se contacte a sistemas
                    echo "DataError|Ocurrio un error de base de datos, contacte a sistemas.";
                  }
                }catch(Throwable $e){
                  echo "DataError|Ocurrio un error al realizar el registro del documento";
                }
            }else{
              echo "DataError|No fue posible almacenar los documentos en el servidor.";
            }
          }catch(Throwable $e){
            echo "DataError|No fue posible crear la carpeta del documento";
          }
        }else{
          echo "DataError|El documento de lectura debe tener formato PDF.";
        }
      }else{
        echo "DataError|No se indicaro documentos";
      }







    }elseif($tipoDoc == "Informe" || $tipoDoc == "Presentacion"){

      if($tipoDoc == "Informe"){
        $codificacion = "CT-INF";
      }else{
        $codificacion = "CT-PRES";
      }




      //$idManualInsert = $_POST['manualAnexo'];
      $auxName = date("Ymd-His");
      $auxNombreDoc = str_replace(" ","",$nombreDoc);
      $auxName2 = substr($auxNombreDoc,0,10);
      $departamentoDoc = $_POST['departamentoDoc'];
      $puestoEncargado = $_POST['puestoEncargado'];
      $versionDoc = "N/A";

      if(!empty($_FILES['docLectura']['tmp_name']) && !empty($_FILES['docEditable']['tmp_name'])){
        //procedemos primero asubir la documentacion
        //verificamos que el documento de lectura este en formato PDF
        if($_FILES['docLectura']['type'] == "application/pdf"){
          $carpetaDestino = "../../docs/Formatos/".$codificacion;

          $auxExt = $_FILES['docEditable']['name'];
          $auxExt = explode(".",$auxExt);
          $n = count($auxExt);
          $n = $n-1;
          $extencion = ".".$auxExt[$n];

          $rutaDocLectura = $carpetaDestino."/Lec_".$auxName2."_".$auxName.".pdf";
          $rutaDocEditable = $carpetaDestino."/Edit_".$auxName2."_".$auxName.$extencion;
          //antes de realizar el registro, creamos las careptas de estos manuales dentro de la codificacion
          try{
            mkdir($carpetaDestino,0777);
            //una vez creada procedemos a mover los documentos
            if(move_uploaded_file($_FILES['docLectura']['tmp_name'],$rutaDocLectura) && 
              move_uploaded_file($_FILES['docEditable']['tmp_name'],$rutaDocEditable)){
                try{

                  $sql1 = "INSERT INTO manuales_formatos (nombre_man_form,fecha_publicacion,
                  fecha_registro,fecha_ultima_mod,edicion,usuario_registro,version_editable,version_lectura,
                  departamento_doc_id,puesto_id,codificacion,acta_id,tipo_doc) VALUES ('$nombreDoc','$fechaPublicacion','$fechaActual',
                  '$fechaUltimaActualizacion','$versionDoc','$usuarioAct','1','1','$departamentoDoc','$puestoEncargado',
                  '$codificacion','0','$tipoDoc')";
                  $query1 = mysqli_query($conexion, $sql1);
                  //obtenemos el ID del documento, consultando el ultimo ID insertado
                  $sql2 = "SELECT id_man_form,edicion FROM manuales_formatos ORDER BY id_man_form DESC LIMIT 1";
                  $query2 = mysqli_query($conexion,$sql2);
                  $fetch2 = mysqli_fetch_assoc($query2);
                  $idManual = $fetch2['id_man_form'];
                  if($fetch2['edicion'] == $versionDoc){
                    //insertamos la documentacion de lectura
                    try{
                      $sql3 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento,fecha_doc,
                      referencia_id) VALUES ('$nombreDoc','$rutaDocLectura','$tipoDoc','$fechaActual','$idManual')";
                      $query3 = mysqli_query($conexion, $sql3);
                      $idDocLectura = mysqli_insert_id($conexion);
                      try{
                        $sql4 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento,fecha_doc,
                        referencia_id) VALUES ('$nombreDoc','$rutaDocEditable','$tipoDoc','$fechaActual','$idManual')";
                        $query4 = mysqli_query($conexion, $sql4);
                        $idDocEditable = mysqli_insert_id($conexion);
                        //por ultimo realiamos un cambio en el documento, para asignar la IP
                        try{
                          $sql5 = "UPDATE manuales_formatos SET version_editable = '$idDocEditable', version_lectura = '$idDocLectura' 
                          WHERE id_man_form = '$idManual'";
                          $query5 = mysqli_query($conexion, $sql5);
                          $comentario = "Se inserta ".$tipoDoc." ".$nombreDoc;

                          $setComent = setComent($usuarioNombre,$comentario,$idManual,"Documentos");
                          if($setComent == "OperationSuccess"){
                            echo "ProcessComplete";
                          }else{
                            echo "ProcessComplete2";
                          }
                        }catch(Throwable $e){
                          echo "DataError|Ocurrio un error al relacionar la documentacion, contacte a sistemas";
                        }
                      }catch(Throwable $e){
                        echo "DataError|Ocurrio un error al guardar la documentacion editable, contacte a sistemas.";
                      }
                    }catch(Throwable $e){
                      echo "DataError|Ocurrio un error al guardar la documentacion de lectura, contacte a sistemas.";
                    }
                  }else{
                    //en este punto no podemos hacer nada, por lo que diremos que se contacte a sistemas
                    echo "DataError|Ocurrio un error de base de datos, contacte a sistemas.";
                  }
                }catch(Throwable $e){
                  echo "DataError|Ocurrio un error al realizar el registro del documento";
                }
            }else{
              echo "DataError|No fue posible almacenar los documentos en el servidor.";
            }
          }catch(Throwable $e){
            echo "DataError|No fue posible crear la carpeta del documento";
          }
        }else{
          echo "DataError|El documento de lectura debe tener formato PDF.";
        }
      }else{
        echo "DataError|No se indicaron documentos";
      }




    }elseif($tipoDoc == "Autorizacion Credito" || $tipoDoc == "Cotizacion" || $tipoDoc == "Anexo Acta"){
      //seccion para insertar la documentacion de una cotizacion o la autorizacion de credito
      if($tipoDoc == "Autorizacion Credito"){
        $codificacion = "CT-AUTCRE";
        $des = "Credito";
      }elseif($tipoDoc == "Cotizacion"){
        $codificacion = "CT-COT";
        $des = "Cotizacion";
      }else{
        $codificacion = "ANEX-ACT";
        $des = "Anexo";
      }
      
      $auxName = date("Ymd-His");
      $auxNombreDoc = str_replace(" ","",$nombreDoc);
      $auxName2 = substr($auxNombreDoc,0,10);
      $departamentoDoc = $_POST['departamentoDoc'];
      $puestoEncargado = $_POST['puestoEncargado'];
      $versionDoc = "N/A";
      $auxExt = $_FILES['docEditable']['name'];
      $auxExt = explode(".",$auxExt);
      $n = count($auxExt);
      $n = $n-1;
      $extEdit = ".".$auxExt[$n];

      //verificamos la existencia de la documentacion
      if(!empty($_FILES['docLectura']['tmp_name']) && !empty($_FILES['docEditable']['tmp_name'])){
        //verificamos que sea el tiupo de documentos adecuados
        if($_FILES['docLectura']['type'] == "application/pdf"){
          $carpetaDestino = "../../docs/".$des."/";
          if(!file_exists($carpetaDestino)){
            mkdir($carpetaDestino, 0777);
          }
          $rutaDocLectura = $carpetaDestino."Lec_".$auxName2."_".$auxName.".pdf";
          $rutaDocEditable = $carpetaDestino."Edit_".$auxName2."_".$auxName.$extEdit;

          try {
            move_uploaded_file($_FILES['docLectura']['tmp_name'],$rutaDocLectura);
            move_uploaded_file($_FILES['docEditable']['tmp_name'],$rutaDocEditable);
            //una vez almacenados guardamos la documentacion
            $sql1 = "INSERT INTO manuales_formatos (nombre_man_form,fecha_publicacion,
            fecha_registro,edicion,usuario_registro,version_editable,version_lectura,
            departamento_doc_id,puesto_id,codificacion,acta_id,tipo_doc) VALUES ('$nombreDoc',
            '$fechaPublicacion','$fechaActual','1','$usuarioAct','1','1','$departamentoDoc','$puestoEncargado',
            '$codificacion','0','$tipoDoc')";
            try {
              $query1 = mysqli_query($conexion, $sql1);
              $idFormato = mysqli_insert_id($conexion);
              //ahora insertamos el documento en la base de datos
              try {
                $sql2 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento,fecha_doc,
                referencia_id) VALUES ('$nombreDoc','$rutaDocLectura','$tipoDoc','$fechaActual','$idFormato')";
                $query2 = mysqli_query($conexion, $sql2);
                $idDocLectura = mysqli_insert_id($conexion);
                try {
                  $sql3 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento,fecha_doc,
                  referencia_id) VALUES ('$nombreDoc','$rutaDocEditable','$tipoDoc','$fechaActual','$idFormato')";
                  $query3 = mysqli_query($conexion, $sql3);
                  $idDocEditable = mysqli_insert_id($conexion);
                  try {
                    //actualizamos el documento con sus respectivos cambios
                    $actaId = $_POST['actaNumAutoriza'];
                    if(!empty($actaId)){
                      //se detecto que contiene acta asignada, la insertamos de una
                      $sql4 = "UPDATE manuales_formatos SET version_editable = '$idDocEditable', 
                      version_lectura = '$idDocLectura',acta_id = '$actaId' WHERE id_man_form = '$idFormato'";
                    }else{
                      $sql4 = "UPDATE manuales_formatos SET version_editable = '$idDocEditable', 
                      version_lectura = '$idDocLectura' WHERE id_man_form = '$idFormato'";
                    }
                    
                    $query4 = mysqli_query($conexion, $sql4);
                    $comentario = "Se inserta el documento ".$tipoDoc." con nombre ".$nombreDoc;
                    $setcoment = setComent($usuarioNombre,$comentario,$idFormato,"Documentos");
                    if($setcoment == "OperationSuccess"){
                      if(!empty($actaId)){
                      //se realizaron todas las tareas de guardado del documento, ahora tendremos que
                      //asignar el documento al acta
                      if($tipoDoc == "Autorizacion Credito"){
                        $textoAutori = $nombreDoc;
                      }elseif($tipoDoc == "Cotizacion"){
                        $textoAutori = "Se aprueba la cotizacion de nombre ".$nombreDoc;
                      }else{
                        $textoAutori = "Se trata los temas del anexo con nombre ".$nombreDoc;
                      }
                      
                      //consultamos las autorizaciones del acta
                      $sql5 = "SELECT * FROM actas WHERE id_acta = '$actaId'";
                      $query5 = mysqli_query($conexion, $sql5);
                      $fetch5 = mysqli_fetch_assoc($query5);
                      $acuerdos = $fetch5['puntos_actas'];
                      if($acuerdos == "" || $acuerdos == " "){
                        $acuerdos .= $textoAutori;
                      }else{
                        $acuerdos .= "_|_".$textoAutori;
                      }
                      
                      //actualizamos los puntos
                      $sql6 = "UPDATE actas SET puntos_actas = '$acuerdos' WHERE id_acta = '$actaId'";
                      try {
                        $query6 = mysqli_query($conexion, $sql6);
                        echo "ProcessComplete";
                      } catch (Throwable $th) {
                        echo "ProcessComplete2".$th;
                      }
                      }else{
                        echo "ProcessComplete";
                      }
                    }else{
                      echo "ProcessComplete2";
                    }
                  } catch (Throwable $th) {
                    //throw $th;
                  }
                } catch (Throwable $th) {
                  echo "DataError|No fue posible registrar el documento editable, verifique con sistemas.";
                }
              } catch (Throwable $th) {
                echo "DataError|No fue posible registrar el documento de lectura, verifique con sistemas.";
              }
            } catch (Throwable $th) {
              echo "DataError|No fue posible registrar el formato, contacte a sistemas.";
            }
          } catch (Throwable $th) {
            echo "DataError|No fue posible almacenar los documentos en el servidor";
          }
        }
      }else{
        //sin documentacion
        echo "DataError|No se indicaron documentos";
      }

    }





        
  }elseif(!empty($_POST['nuevaVersion'])){
    
    //verificamos que tipo de version se agregara
    //una anterior o una actual
    $tipoUpdate = $_POST['tipoActualizacion'];
    $nuevaVer = $_POST['nuevaVersion'];
    $manual = $_POST['manualUpdate'];
    $docLectura = $_FILES['docNewLectura']['tmp_name'];
    $docEditable = $_FILES['docNewEditable']['tmp_name'];
    $nuevaCodificacion = $_POST['nuevaCodificacion'];
    $nuevoNombreDoc = $_POST['nombreDocumentoNewVer'];
    $fechaActual = date("Y-m-d");
    $usuarioAct = getUsuarioId($_SESSION['usNamePlataform']);
    
    $sql1 = "SELECT * FROM manuales_formatos WHERE id_man_form = '$manual'";
    $query1 = mysqli_query($conexion, $sql1);
    if(mysqli_num_rows($query1) > 0){
      $fetch1 = mysqli_fetch_assoc($query1);
      //deberiamos considerar que al entrar aqui se cambiara de version, pero
      //si se ingresa una version igual indicaremos un error
      $tipoDoc = $fetch1['tipo_doc'];
      $edicion = $fetch1['edicion'];
      $codAnt = $fetch1['codificacion'];
      $tipoAnt = $tipoDoc;
      $lecturaAnt = $fetch1['version_lectura'];
      $editAnt = $fetch1['version_editable'];
      $actaAnt = $fetch1['acta_id'];
      $nombreAnt = $fetch1['nombre_man_form'];
      $ultimaModVer = $fetch1['fecha_ultima_mod'];

      //verificamos si se indico numero de acta
      if($_POST['actaAutorizacion'] == "P"){
        //se indico que el acta esta pendiente
        $fechaActa = "1900-01-01";
        $idActa = "0";
      }else{
        $auxAct = explode("|",$_POST['actaAutorizacion']);
        //verificamos los datos del acta
        $idActa = $auxAct[0];
        $fechaActa = $auxActa[1];
      }

      $filtroFechas = 0;
      if($tipoUpdate == "NuevaVer"){
        //si se indico una nueva version verificamos que la fecha indicada sea
        //mayor a la actual
        if($fechaActa <= $ultimaModVer){
          $filtroFechas = 1;
          if($_POST['actaAutorizacion'] == "P"){
            //modificacion, se indica que para las nuevas versiones podran subir sus documentos,
            //para que el encargadop de actas sepa cuando estan manuales pendientyes de autorizar
            //por lo que si se da el caso, podra continuar con el proceso
            //$errorFiltro = "DataError|Esta indicando una nueva version del documento, sin acta de autorizacion este movimiento no es permitido.";
            $filtroFechas = 0;
          }else{
            $errorFiltro = "DataError|No es posible indicar una fecha posterior a la ultima actualizacion.";
          }
        }else{

        }
        
      }else{
        //se indico una version anterior
        //la fecha de actualizacion no debe ser mayor a la actual
        if($fechaActa >= $ultimaModVer){
          $filtroFechas = 1;
          $errorFiltro = "DataError|No es posible indicar una fecha superior a la ultima modificacion";
        }
      }

      if($filtroFechas == 0){
        //[pdemos continuar con el proceso de alta]

        if(!empty($_FILES['docNewLectura']['tmp_name']) && !empty($_FILES['docNewEditable']['tmp_name'])){
          //verificamos que el documento de lectura se encuentre en pdf
          if($_FILES['docNewLectura']['type'] == "application/pdf"){
            //procedemos a guardar los documentos, indicando la ruta de estos
    
            $carpetaDestino = "../../docs/Formatos/".$nuevaCodificacion;
            $rutaDocLectura = $carpetaDestino."/Lec_".$nuevaCodificacion."_".str_replace(".","-",$nuevaVer).".pdf";
            $rutaDocEditable = $carpetaDestino."/Edit_".$nuevaCodificacion."_".str_replace(".","-",$nuevaVer);
            //deberia EXISTIR LA CARETA DE DESTINO, PERO POR SI NO ESTA creamos
            try {
          
              if(!file_exists($carpetaDestino)){
                mkdir($carpetaDestino);
              }
              //movemos los documentos
              if(move_uploaded_file($docLectura,$rutaDocLectura) && 
                move_uploaded_file($docEditable,$rutaDocEditable)){
                  $mods = "";
                if($fetch1['edicion'] != $nuevaVer){
                  //verificamos si la codificacion es la misma
                  $mods .= "Se modifica la version del documento de ".$fetch1['edicion']." a la version ".$nuevaVer.".";
                  if($fetch1['codificacion'] != $nuevaCodificacion){
                    //agregamos el hisotiroc de movimiento
                    $mods .= " Ademas se cambia la codificacion, de ".$fetch1['codificacion']." ahora es ".$nuevaCodificacion.".";
                  }
                  $mods .= " Movimiento registrado por: ".$_SESSION['usNamePlataform'];
                  //insertamos los documwntos de los manuales
                  $sql2 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento,
                  fecha_doc,usuario_reg_doc,version_doc,referencia_id) VALUES ('$nuevoNombreDoc','$rutaDocLectura',
                  '$tipoDoc','$fechaActual','$usuarioAct','$nuevaVer','$manual')";
                  $sql3 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento,
                  fecha_doc,usuario_reg_doc,version_doc,referencia_id) VALUES ('$nuevoNombreDoc','$rutaDocEditable',
                  '$tipoDoc','$fechaActual','$usuarioAct','$nuevaVer','$manual')";
                  try {
                    $query2 = mysqli_query($conexion, $sql2);
                    $idDocLectura = mysqli_insert_id($conexion);

                    $query3 = mysqli_query($conexion, $sql3);
                    $idDocEditable = mysqli_insert_id($conexion); 
                    //verificamos si se indico una version actual o una version anterior
                    if($tipoUpdate == "NuevaVer"){
                      //si es una nueva version, actualizaremos la informacion del documento
                      //actualizamos los nuevos datos del manual
                      if($_POST['actaAutorizacion'] == "P"){
                        //si el acta de autorizacion esta pendiente indicaremos el acta en 0 y 
                        //las fechas de actualizacion, se mantendran con la version anterior
                        $sql4 = "UPDATE manuales_formatos SET codificacion = '$nuevaCodificacion',
                        fecha_actualizacion = '$fechaActual',edicion = '$nuevaVer',
                        usuario_actualizo = '$usuarioAct', version_editable = '$idDocEditable',
                        version_lectura = '$idDocLectura',acta_id = '$idActa' WHERE id_man_form = '$manual'";
                      }else{
                        //si se indico un acta
                        $sql4 = "UPDATE manuales_formatos SET codificacion = '$nuevaCodificacion',
                        fecha_actualizacion = '$fechaActual',fecha_ultima_mod = '$fechaActa',
                        edicion = '$nuevaVer',usuario_actualizo = '$usuarioAct', version_editable = '$idDocEditable',
                        version_lectura = '$idDocLectura', acta_id = '$idActa' WHERE id_man_form = '$manual'";
                      }
                      
                      try {
                        $query4 = mysqli_query($conexion, $sql4);

                        //ahora insertamos la nueva version en el historico
                        $sql5 = "INSERT INTO his_mov_docs (usuario_id,nombre_ant,fecha_mov,ver_ant,cod_ant,tipo_ant,
                        lectura_ant,mod_ant,doc_id,acta_ant) VALUES ('$usuarioAct','$nombreAnt','$fechaActual','$edicion',
                        '$codAnt','$tipoAnt','$lecturaAnt','$editAnt','$manual','$actaAnt')";
                        try {
                          $query5 = mysqli_query($conexion, $sql5);
                          $setComent = setComent($usuarioNombre,$mods,$manual,"Documentos");

                          if($setComent == "OperationSuccess"){
                            echo "OperationSuccess";
                          }else{
                            echo "OperationSuccess2";
                          }
                        } catch (Throwable $th) {
                          echo "DataError|Ocurrio un error al generar el registro historico, contacte a sistemas.".$th;
                        }

                      } catch (Throwable $th) {
                        echo "DataError|Ocurrio un error inesperado, contacta a sistemas";
                      }
                    }else{
                      //se indico una version anterior, por lo que solo insertaremos
                      //al historico de versiones
                      $mods2 = "Se inserta la version ".$nuevaVer." del ".$tipoAnt." con nombre: ".$nuevoNombreDoc;
                      try {
                        $sql5 = "INSERT INTO his_mov_docs (usuario_id,nombre_ant,fecha_mov,ver_ant,cod_ant,tipo_ant,
                        lectura_ant,mod_ant,doc_id,acta_ant) VALUES ('$usuarioAct','$nuevoNombreDoc','$fechaActual','$nuevaVer',
                        '$nuevaCodificacion','$tipoAnt','$idDocLectura','$idDocEditable','$manual','$idActa')";
                        $query5 = mysqli_query($conexion, $sql5);

                        $setComent = setComent($usuarioNombre,$mods2,$manual,"Documentos");
                        if($setComent == "OperationSuccess"){
                          echo "OperationSuccess";
                        }else{
                          echo "OperationSuccess2";
                        }
                      } catch (Throwable $th) {
                        //throw $th;
                      }
                    }
                  } catch (Throwable $th) {
                    echo "DataError|Ocurrio un error al momento de almacenar la documentacion. ".$th;
                  }
                }else{
                  //las versiones son iguales indicamos un error 
                  echo "DataError|Debe indicar una version superior a la actual.";
                }
              }
            }catch (Throwable $th) {
              echo "DataError|No fue posible crear la carpeta destino. reporta a sistemas.";
            }
          }else{
            echo "DataError|El documento de lectura no se encuentra en PDF.";
          }
        }else{
          echo "DataError|No se indico documentacion valida.";
        }


      }else{
        echo $errorFiltro;
      }

    }else{
      //no se encontraron resultados del documento a actualizar
      echo "DataError|No se encontro informacion del documento a actualizar";
    }


    
  }elseif(!empty($_POST['getManualesAnexo'])){
    if($_POST['getManualesAnexo'] == "anexos"){
      $sql = "SELECT * FROM manuales_formatos WHERE tipo_doc = 'Manual' ORDER BY nombre_man_form";
      $query = mysqli_query($conexion, $sql);
      if(mysqli_num_rows($query) > 0){
        $datos = [];
        $i = 0;
        while($fetch = mysqli_fetch_assoc($query)){
          $nombre = $fetch['nombre_man_form'];
          $idMan = $fetch['id_man_form'];
          $datos[$i] = ["iden"=>$idMan,"nombre_man"=>$nombre];
          $i++;
        }
        echo json_encode($datos);
      }else{

      }
    }else{
      //no hacemos nada
    }
  }elseif(!empty($_POST['nuevoNombreUpdate'])){
    //seccion para actualizar la informacion del 
    $nombreNuevo = $_POST['nuevoNombreUpdate'];
    $codificacion = $_POST['nuevoCodificacion'];
    $responsable = $_POST['responsableDocumento'];
    $departamento = $_POST['depDocumento'];
    $fechaPub = $_POST['fechaNewPublicacion'];
    $idDoc = $_POST['manualUpdateData'];

    //antes de continuar verificamos la informacion del documento
    $sql = "SELECT * FROM manuales_formatos a INNER JOIN departamentos b ON
    a.departamento_doc_id = b.id_departamento INNER JOIN puestos c ON 
    a.puesto_id= c.id_puesto WHERE a.id_man_form = '$idDoc'";
    try {
      $query = mysqli_query($conexion, $sql);
      $fetch = mysqli_fetch_assoc($query);
      //verificamos si la informacion cambio
      $nombreActual = $fetch['nombre_man_form'];
      $codActual = $fetch['codificacion'];
      $responsableActual = $fetch['puesto_id'];
      $depaActual = $fetch['departamento_doc_id'];
      $fechaPubActual = $fetch['fecha_publicacion'];
      $responsableNombre = $fetch['nombre_puesto'];
      $nombreDepaActual = $fetch['nombre_departamento'];

      $mods = "";
      if($nombreNuevo != $nombreActual){
        $mods .= "Se modifica el nombre de (<strong>".$nombreActual."</strong>) ahora es (<strong>".$nombreNuevo."</strong>). ";
      }
      if($codificacion != $codActual){
        $mods .= "Se modifica la codificacion de (<strong>".$codActual."</strong>) ahora es (<strong>".$codificacion."</strong>). ";
      }
      if($responsable != $responsableActual){
        $sqlPues = "SELECT * FROM puestos WHERE id_puesto = '$responsable'";
        $queryPues = mysqli_query($conexion, $sqlPues);
        $fetchPues = mysqli_fetch_assoc($queryPues);
        $nombreNuevoPuesto = $fetchPues['nombre_puesto'];

        $mods .= "Se modifica el responsable de (<strong>".$responsableNombre."</strong>) ahora es (<strong>".$nombreNuevoPuesto."</strong>). ";
      }
      if($departamento != $depaActual){
        $sqlDep = "SELECT * FROM departamentos WHERE id_departamento = '$departamento'";
        $queryDep = mysqli_query($conexion, $sqlDep);
        $fetchDep = mysqli_fetch_assoc($queryDep);
        $nombreDep = $fetchDep['nombre_departamento'];

        $mods .= "Se modifica el departamento de (<strong>".$nombreDepaActual."</strong>) ahora es (<strong>".$nombreDep."</strong>)";
      }
      if($fechaPub != $fechaPubActual){
        $mods .= "Se modifica la fecha de publicacion de (".$fechaPubActual.") ahora es (".$fechaPub.")";
      }

      if($mods != ""){
        $sqlUpdate = "UPDATE manuales_formatos SET nombre_man_form = '$nombreNuevo', codificacion = '$codificacion',
        puesto_id = '$responsable', departamento_doc_id = '$departamento', fecha_publicacion = '$fechaPub' 
        WHERE id_man_form = '$idDoc'";
        try {
          $queryUpdate = mysqli_query($conexion, $sqlUpdate);
          //insertamos el comentario
          $setComent = setComent($usuarioNombre,$mods,$idDoc,"Documentos");
          if($setComent == "OperationSuccess"){
            echo "OperationSuccess";
          }else{
            echo "OperationSuccess2";
          }
        } catch (Throwable $th) {
          echo "DataError|Ocurrio un error al actualizar la informacion.";
        }
      }else{
        echo "DataError|No se detectaron cambios a realizar";
      }


    } catch (Throwable $th) {
      echo "DataError|No fue posible acceder a la informacion del documento.";
    }
    

  }elseif(!empty($_POST['busTipoDoc'])){
    $tipoDoc = $_POST['busTipoDoc'];
    $idDep = getDepaIdByUser($usuarioNombre);

    if($permiso->ver_manuales == 1){
      $sql = "SELECT * FROM manuales_formatos a INNER JOIN puestos b ON 
      a.puesto_id = b.id_Puesto WHERE a.tipo_doc = '$tipoDoc' 
      ORDER BY a.nombre_man_form ASC";
    }else{
      $sql = "SELECT * FROM manuales_formatos a INNER JOIN puestos b ON 
      a.puesto_id = b.id_Puesto WHERE a.tipo_doc = '$tipoDoc' AND 
      a.departamento_doc_id = '$idDep' ORDER BY a.nombre_man_form ASC";
    }

    
    try {
      $query = mysqli_query($conexion, $sql);
      if(mysqli_num_rows($query) > 0){
        $x = 0;
        $datos = [];
        while($fetch = mysqli_fetch_assoc($query)){
          $datos[$x] = $fetch;
          $x++;
        }
        echo json_encode($datos);
      }else{
        //sin datos
        echo "NoDataResult";
      }
    } catch (Throwable $th) {
      echo "DataError|ocurrio un error al consultar la informacion. ".$th;
    }
    
  }elseif(!empty($_POST['nombreBusca'])){
    $tipoDoc = $_POST['tipoDocBus'];
    $nombreDoc = $_POST['nombreBusca'];
    $idDep = getDepaIdByUser($usuarioNombre);

    if($permiso->ver_manuales == 1){
      if($tipoDoc == ""){
        $sql = "SELECT * FROM manuales_formatos a INNER JOIN puestos b ON 
        a.puesto_id = b.id_Puesto WHERE a.nombre_man_form LIKE '%$nombreDoc%' ORDER BY 
        a.nombre_man_form ASC";
      }else{
        $sql = "SELECT * FROM manuales_formatos a INNER JOIN puestos b ON 
        a.puesto_id = b.id_Puesto WHERE a.nombre_man_form LIKE '%$nombreDoc%' AND 
        a.tipo_doc = '$tipoDoc' ORDER BY a.nombre_man_form ASC";
      }
    }else{
      if($tipoDoc == ""){
        $sql = "SELECT * FROM manuales_formatos a INNER JOIN puestos b ON 
        a.puesto_id = b.id_Puesto WHERE a.departamento_doc_id = '$idDep' 
        AND a.nombre_man_form LIKE '%$nombreDoc%' ORDER BY 
        a.nombre_man_form ASC";
      }else{
        $sql = "SELECT * FROM manuales_formatos a INNER JOIN puestos b ON 
        a.puesto_id = b.id_Puesto WHERE a.nombre_man_form LIKE '%$nombreDoc%' AND 
        a.tipo_doc = '$tipoDoc' AND a.departamento_doc_id = '$idDep' ORDER BY a.nombre_man_form ASC";
      }
    }
    

    try {
      $query = mysqli_query($conexion, $sql);
      if(mysqli_num_rows($query) > 0){
        $x = 0;
        $datos = [];
        while($fetch = mysqli_fetch_assoc($query)){
          $datos[$x] = $fetch;
          $x++;
        }//fin del while
        echo json_encode($datos);
      }else{
        echo "NoDataResult";
      }
    } catch (\Throwable $th) {
      echo "DataError|Ocurrio un error al buscar la documentacion.";
    }
  }elseif(!empty($_POST['AnexoNombreEdit'])){
    $nombreAnexo = $_POST['AnexoNombreEdit'];
    $codAnexo = $_POST['AnexoCodEdit'];
    $anexo = $_POST['AnexoEdit'];

    //verificamos la existencia del anexo
    $sql = "SELECT * FROM manuales_formatos WHERE id_man_form = '$anexo' AND tipo_doc = 'Anexo'";
    try {
      $query = mysqli_query($conexion, $sql);
      $fetch = mysqli_fetch_assoc($query);
      $anexoOrigi = $fetch['nombre_man_form'];
      $codActual = $fetch['edicion'];
      if(mysqli_num_rows($query) > 0){
        $sql2 = "UPDATE manuales_formatos SET nombre_man_form = '$nombreAnexo',codificacion = '$codAnexo' WHERE
        id_man_form = '$anexo' AND tipo_doc = 'Anexo'";
        try {
          $query2 = mysqli_query($conexion,$sql2);
          $mods = "Se actualizar la informacion del anexo ".$anexoOrigi." ahora ".$nombreAnexo." codificacion 
          ".$codActual." ahora ".$codActual;
          $setComent = setComent($usuarioNombre,$mods,$anexo,"Documentos");
          if($setComent == "OperationSuccess"){
            echo "OperationSuccess";
          }else{
            echo "OperationSuccess";
          }
        } catch (Throwable $th) {
          echo "DataError|Ocurrio un error al actualizar la informacion del anexo.";
        }
      }else{
        echo "DataError|No fue posible localizar informacion del Anexoindicado. 2";  
      }
    } catch (Throwable $th) {
      echo "DataError|No fue posible localizar informacion del Anexoindicado.";
    }
    
  }elseif(!empty($_POST['getOldVersions'])){
    //seccion para mostrar las versiones anteriores de un manual
    $anexo = $_POST['getOldVersions'];

    $sql = "SELECT * FROM manuales_formatos WHERE id_man_form = '$anexo' AND tipo_doc = 'Anexo'";
    try {
      $query = mysqli_query($conexion, $sql);
      $fetch = mysqli_fetch_assoc($query);
      $idManual = $fetch['departamento_doc_id'];
      $sql2 = "SELECT * FROM his_mov_docs WHERE doc_id = '$idManual' AND tipo_ant = 'Manual'";
      try {
        $query2 = mysqli_query($conexion, $sql2);
        if(mysqli_num_rows($query2) > 0){
          $data = [];
          $i = 0;
          while($fetch2 = mysqli_fetch_assoc($query2)){
            $data[$i] = $fetch2;
          }//fin del while
          echo json_encode($data);
        }else{
          echo "NoVersions";
        }
      } catch (Throwable $th) {
        echo "DataError|Ocurrio un error al consultar las versiones anteriores del manual. ".$th;
      }
    } catch (Throwable $th) {
      echo "DataError|Ocurrio un error al consultar el manual del anexo ".$th;
    }
    
  }
}

?>