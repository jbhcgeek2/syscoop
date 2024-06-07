<?php 
error_reporting(0);
session_start();



if(!empty($_SESSION['usNamePlataform'])){
  include('../operations/usuarios.php');
  include('../operations/functionsComents.php');
  include('../_con.php');
  if(!empty($_POST['nombreObjeto'])){
    //guardar nuevo objeto en el inventario
    $campos = ['nombreObjeto','fechaCompra','proveedor','modelo','marca',
    'color','lugarResguardo','sucursalResguardo','empleadoResguardo','polizaRegistro',
    'valorMoi','observaciones','accesorios','clasificacion','sinodeprecia'];
    $van = 0;
    $fechaAux = date('Y-m-d');
    $numAux = random_int(100, 9999);
    //el tratamiento de archivos lo manejaremos por separado
    for ($i=0; $i < count($campos); $i++) { 
      if(empty($_POST[$campos[$i]])){
        $van++;
      }
    }//fin delfor

    if($van == 0){
      //verificamos la existencia de la poliza registrop
      $poliza = $_POST['polizaRegistro'];
      $sqlPoli = "SELECT COUNT(*) AS poliInsert FROM factura_inventario WHERE poliza_registro = '$poliza'";
      $queryPoli = mysqli_query($conexion, $sqlPoli);
      $fetchPoli = mysqli_fetch_assoc($queryPoli);
      $totalPoli = $fetchPoli['poliInsert'];
      if($totalPoli == 0){
        //verificamos si existen archivos por subir
        //echo $_FILES['facturaObjeto']['tmp_name'];
        //echo $_FILES['polizaObjeto']['tmp_name'];
        $error_archivo = 0;
        $rutaArchvios = "";
        $rutaArchvios2 = "";
        
        
        if(!empty($_FILES['facturaObjeto']['tmp_name']) && ($_FILES['facturaObjeto']['type'] == "application/pdf")){
          $nombreArchivo = "Factura_".$_POST['proveedor']."_".$fechaAux."_".$numAux.".pdf";
          $rutaArchvios = "../../docs/".$nombreArchivo;
          if(move_uploaded_file($_FILES['facturaObjeto']['tmp_name'],$rutaArchvios)){
            //no hacemos otra cosa
          }else{
            $error_archivo = $error_archivo+1;
            $rutaArchvios = "";
          }
        }

        if(!empty($_FILES['polizaObjeto']['tmp_name']) && ($_FILES['polizaObjeto']['type'] == "application/pdf")){
          $nombreArchivo2 = "Poliza_".$_POST['proveedor']."_".$fechaAux."_".$numAux.".pdf";
          $rutaArchvios2 = "../../docs/".$nombreArchivo2;
          if(move_uploaded_file($_FILES['polizaObjeto']['tmp_name'],$rutaArchvios2)){
            //no hacemos nada
          }else{
            $error_archivo = $error_archivo+1;
            $rutaArchvios2 = "";
          }
        }
        if(!empty($_FILES['imgObjeto']['tmp_name'])){
          $extencion = "";
          if($_FILES['imgObjeto']['type'] == "jpg"){
            $extencion = ".jpg";
          }else if($_FILES['imgObjeto']['type'] == "jpeg"){
            $extencion = ".jpeg";
          }else if($_FILES['imgObjeto']['type'] == "png"){
            $extencion = ".png";
          }else{
            $extencion = ".jpg";
          }
          $auxDateSS = date("YMDHis");
          $nombreImagen = "Foto_".$modelo."_".$fechaAux."-".$auxDateSS.$extencion;
          $rutaArchvios3 = "../../docs/".$nombreImagen;
          if(move_uploaded_file($_FILES['imgObjeto']['tmp_name'],$rutaArchvios3)){
            //no hacemos nada
          }else{
            $error_archivo = $error_archivo+1;
            $rutaArchvios3 = "";
          }
        }

        //procedemos con el guardado
        if($error_archivo == 0){
          //insertamos primeramente la documentacion
          if($rutaArchvios != ""){
            $sqlAr1 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento) VALUES 
            ('$nombreArchivo','$rutaArchvios','Factura')";
            $queryAr1 = mysqli_query($conexion, $sqlAr1);
            $idFactura = mysqli_insert_id($conexion);
          }
          if($rutaArchvios2 != ""){
            $sqlAr2 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento) VALUES 
            ('$nombreArchivo2','$rutaArchvios2','Poliza')";
            $queryAr1 = mysqli_query($conexion, $sqlAr2);
            $idPoliza = mysqli_insert_id($conexion);
          }
          if($rutaArchvios3 != ""){
            $sqlAr3 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento) VALUES 
            ('$nombreArchivo2','$rutaArchvios3','Imagen')";
            $queryAr3 = mysqli_query($conexion, $sqlAr3);
            $idImagen = mysqli_insert_id($conexion);
          }


          $nombreObjeto = htmlentities($_POST['nombreObjeto']);
          $fechaCompra = htmlentities($_POST['fechaCompra']);
          $proveedor = htmlentities($_POST['proveedor']);
          $modelo = htmlentities($_POST['modelo']);
          $marca = htmlentities($_POST['marca']);
          $color = htmlentities($_POST['color']);
          $lugarResguardo = htmlentities($_POST['lugarResguardo']);
          $sucursalResguardo = htmlentities($_POST['sucursalResguardo']);
          $empleado = htmlentities($_POST['empleadoResguardo']);
          $moi = htmlentities($_POST['valorMoi']);
          $observaciones = htmlentities($_POST['observaciones']);
          $accesorios = htmlentities($_POST['accesorios']);
          $usuarioActual = getUsuarioId($_SESSION['usNamePlataform']);
          $clasificacion = htmlentities($_POST['clasificacion']);
          $polizaRegistro = htmlentities($_POST['polizaRegistro']);
          $cantidad = htmlentities($_POST['cantidadObjeto']);
          $noDeprecia;
          if($_POST['sinodeprecia'] == "NO"){
            $noDeprecia = "0";
          }else{
            $noDeprecia = "1";
          }
          $moiIndividual = $moi /$cantidad;

          $sqlauxCla = "SELECT * FROM clasificacion WHERE nombre_clasificacion = '$clasificacion'";
          $queryAuxCla = mysqli_query($conexion, $sqlauxCla);
          $fetchAuxCla = mysqli_fetch_assoc($queryAuxCla);
          $claveCorta = $fetchAuxCla['clave'];

          $sqlFact = "INSERT INTO factura_inventario (poliza_registro,nombre_objeto_general,
          observaciones_general,proveedor_id,no_deprecia,valor_moi,cantidad,modelo,marca,color,fecha_compra,factura_id,poliza_id,imagen_id,
          clasificacion) VALUES 
          ('$polizaRegistro','$nombreObjeto','$observaciones','$proveedor','$noDeprecia','$moi','$cantidad','$modelo','$marca','$color','$fechaCompra',
          '$idFactura','$idPoliza','$idImagen','$clasificacion')";
          $queryFact = mysqli_query($conexion, $sqlFact);
          if($queryFact){
            //se inserto correctamente la factura
            $id_Factura = mysqli_insert_id($conexion);
            // hacemos los insert dependiendo de las cantidadesingresadas
            $obInsert = 0;
            for($iCant = 0; $iCant < $cantidad; $iCant++){
              //antes de insertar el objeto generamos el codigo del mismo
              $sqlAuxNum = "SELECT id_inventario FROM inventario WHERE clasificacion = '$clasificacion'";
              $queryAuxNum = mysqli_query($conexion, $sqlAuxNum);
              $numeroAux = mysqli_num_rows($queryAuxNum);
              $auxNum = (int) $numeroAux;
              $nuevoNum = $auxNum+1;
              $nuevoNum = str_pad($nuevoNum,4,"0",STR_PAD_LEFT);
              $codigo = "CT-".$claveCorta.$nuevoNum;

              $sqlObj = "INSERT INTO inventario (nombre_objeto,fecha_adquisicion,resguardo_empleado,
              fecha_ultima_actualizacion,usuario_actualizo,modelo_objeto,marca_objeto,color_objeto,accesorios_objeto,
              observaciones_objeto,lugar_resguardo,sucursal_resguardo,proveedor_id,poliza_registro,factura_objeto,
              fecha_registro,valor_moi,clasificacion,articulo_activo,codigo) 
              VALUES ('$nombreObjeto','$fechaCompra','$empleado','$fechaAux','$usuarioActual','$modelo','$marca','$color',
              '$accesorios','$observaciones','$lugarResguardo','$sucursalResguardo','$proveedor','$idPoliza','$id_Factura',
              '$fechaAux','$moiIndividual','$clasificacion','1','$codigo')";
              $queryObj = mysqli_query($conexion, $sqlObj);
              $obInsert++;
            }//fin del for

            if($obInsert == $cantidad){
              //$descripcion = "Inventario|$idInven|Se modifica el campo ".$campoInven." de la tabla Facturas ahora contiene: ".$valor;
              $comentario = "Inventario|$id_Factura|Se inserta el objeto: ".$nombreObjeto." con una cantidad de ".$cantidad.".";
              $usuario = $_SESSION['usNamePlataform'];
              $setComent = setComent($usuario,$comentario,$id_Factura,"INVENTARIO");
              
              echo "operationSuccess";
            }else{
              //no se inserto el objeto, eliminamos la documentacion de la base y de la carpeta
              if($rutaArchvios != ""){
                unlink($rutaArchvios);
              }
              if($rutaArchvios2 != ""){
                unlink($rutaArchvios2);
              }
              if($idPoliza != ""){
                $sqlDel1 = "DELETE FROM documentos WHERE id_documento = '$idPoliza'";
                $queryDel1 = mysqli_query($conexion, $sqlDel1);
              }
              if($idFactura != ""){
                $sqlDel2 = "DELETE FROM documentos WHERE id_documento = '$idFactura'";
                $queryDel2 = mysqli_query($conexion, $sqlDel2);
              }
              echo "DataError|Ocurrio un error al insertar el objeto, si el error persiste, contacte a sistemas";
            }
          }//fin del  query factura

        }else{
          echo "DataError|Ocurrio un erroral cargar la documentacion";
        }
      }else{
        //ya existe la poliza
        echo "DataError|La poliza indicada ya existe, verifiquelo.";

      }
    }else{
      echo "DataError|No se indicaron todos los campos";
    }
  }elseif(!empty($_POST['buscarObjeto'])){
    if($_POST['buscarObjeto'] == "verdadero"){
      $clasi = $_POST['buscaClasi']; $suc = $_POST['buscaSuc'];
      $nombreObjeto = $_POST['buscaNombre'];
      if(empty($suc) && $clasi != ""){
        if(!empty($nombreObjeto)){
          $sqlBusqueda = "SELECT DISTINCT(a.factura_objeto) FROM inventario a 
          INNER JOIN factura_inventario b ON a.factura_objeto = b.id_factura 
          WHERE a.clasificacion = '$clasi' AND a.nombre_objeto LIKE '%$nombreObjeto%' ORDER BY b.id_factura DESC LIMIT $maxItemPage";
        }else{
          $sqlBusqueda = "SELECT DISTINCT(a.factura_objeto) FROM inventario a 
          INNER JOIN factura_inventario b ON a.factura_objeto = b.id_factura 
          WHERE a.clasificacion = '$clasi' ORDER BY b.id_factura DESC LIMIT $maxItemPage";
        }
        //$campo = "a.clasificacion = '$clasi'";
      }elseif(empty($clasi) && $suc != ""){
        if(!empty($nombreObjeto)){
          $sqlBusqueda = "SELECT DISTINCT(a.factura_objeto) FROM inventario a 
          INNER JOIN factura_inventario b ON a.factura_objeto = b.id_factura 
          WHERE a.sucursal_resguardo = '$suc' AND a.nombre_objeto LIKE '%$nombreObjeto%' ORDER BY a.id_factura DESC LIMIT $maxItemPage";
        }else{
          $sqlBusqueda = "SELECT DISTINCT(a.factura_objeto) FROM inventario a 
          INNER JOIN factura_inventario b ON a.factura_objeto = b.id_factura 
          WHERE a.sucursal_resguardo = '$suc' ORDER BY b.id_factura DESC LIMIT $maxItemPage";
        }
        //$campo = "a.sucursal = '$suc'";
      }elseif($clasi != "" && $suc != ""){
        if(!empty($nombreObjeto)){
          $sqlBusqueda = "SELECT DISTINCT(a.factura_objeto) FROM inventario a 
          INNER JOIN factura_inventario b ON a.factura_objeto = b.id_factura 
          WHERE (a.clasificacion = '$clasi' AND a.sucursal_resguardo = '$suc') 
          AND a.nombre_objeto LIKE '%$nombreObjeto%' ORDER BY b.id_factura DESC LIMIT $maxItemPage";
        }else{
          $sqlBusqueda = "SELECT DISTINCT(a.factura_objeto) FROM inventario a 
          INNER JOIN factura_inventario b ON a.factura_objeto = b.id_factura 
          WHERE a.clasificacion = '$clasi' AND a.sucursal_resguardo = '$suc' ORDER BY b.id_factura DESC LIMIT $maxItemPage";
        }
        //$campo = "a.clasificacion = '$clasi' AND a.sucursal = '$suc'";
      }elseif(!empty($nombreObjeto)){
        $sqlBusqueda = "SELECT DISTINCT(a.factura_objeto) FROM inventario a 
        INNER JOIN factura_inventario b ON a.factura_objeto = b.id_factura 
        WHERE a.nombre_objeto LIKE '%$nombreObjeto%' ORDER BY b.id_factura DESC LIMIT $maxItemPage";
      }
      //$sqlBusqueda = "SELECT DISTINCT(a.factura_objeto) FROM inventario a 
      //INNER JOIN factura_inventario b ON a.factura_objeto = b.id_factura 
      //WHERE $campo";

      $queryBusqueda = mysqli_query($conexion, $sqlBusqueda);
      if($queryBusqueda){
        $datos = [];
        $i1 = 0;
        $i2 = 0;
        while($fetchQueryBus = mysqli_fetch_assoc($queryBusqueda)){
          $idFactura = $fetchQueryBus['factura_objeto'];
          //realizamos una segunda consulta 
          $sqlBus2 = "SELECT *,(SELECT b.nombre_objeto  FROM inventario b WHERE 
          b.factura_objeto = a.id_factura LIMIT 1) AS nombre_objeto_aux,
          (SELECT b.clasificacion FROM inventario b WHERE 
          b.factura_objeto = a.id_factura LIMIT 1) AS clasificacion,
          (SELECT b.sucursal_resguardo FROM inventario b WHERE 
          b.factura_objeto = a.id_factura LIMIT 1) AS sucursal_resguardo FROM 
          factura_inventario a WHERE a.id_factura = '$idFactura' ORDER BY a.id_factura DESC LIMIT $maxItemPage";
          $queryBusqueda2 = mysqli_query($conexion, $sqlBus2);
          if($queryBusqueda2){

            while($fetchBus2 = mysqli_fetch_assoc($queryBusqueda2)){
              $datos[$i1] = $fetchBus2;
            }
          }//fin quewry2
          $i1++;
        }//fin del while

        if($i1 > 0){
          echo json_encode($datos);
        }else{
          echo "NoDataResult";
        }
      }
    }else{
      //falso
    }
  }elseif(!empty($_POST['polizaUpdate'])){
    $idFactura = $_POST['polizaUpdate'];
    $campo = $_POST['campoUpdate'];
    $valor = $_POST['valorCampo'];
    $fecha = date('Y-m-d');
    $hora = date("H:i:s");
    $usuario = $_SESSION['usNamePlataform'];
    $idUsuario = getUsuarioId($usuario);
    
    $auxDato = explode("|",$campo);
    if(count($auxDato) > 1){
      if($auxDato[0] == "file_resguardo"){
        $idInvenAux = $auxDato[1];
        //tratamos de mover el archivo
        $nombreResguardo = "Resguardo_".$idInvenAux."_".$fecha."pdf";
        $rutaResguardo = "../../docs/".$nombreResguardo;
        if(move_uploaded_file($_FILES["valorCampo"]['tmp_name'],$rutaResguardo)){
          $sqlResg = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento) 
          VALUES ('$nombreResguardo','$rutaResguardo','Resguardo')";
          $queryResg = mysqli_query($conexion, $sqlResg);
          $idDocumento = mysqli_insert_id($conexion);
          $descripcion = "Inventario|$idInvenAux|Se modifica el documento resguardo de la tabla Inventario";
          $sqlUpdate = "UPDATE inventario SET fecha_ultima_actualizacion = '$fecha', usuario_actualizo = '$idUsuario',
          documento_resguardo = '$idDocumento' WHERE id_inventario = '$idInvenAux'";
        }else{
          echo "DataError|No fue posible procesar el documento";
        }
      }else{
        $idInven = $auxDato[1];
        $campoInven = $auxDato[0];
        if($campoInven == "resguardo_empleado"){
          $nombreEmpleado = getEmpleado($valor);
          $descripcion = "Inventario|$idInven|Se modifica el campo ".$campoInven." de la tabla Facturas ahora contiene: ".$nombreEmpleado;
        }else{
          $descripcion = "Inventario|$idInven|Se modifica el campo ".$campoInven." de la tabla Facturas ahora contiene: ".$valor;
        }
        
        $sqlUpdate = "UPDATE inventario SET $campoInven = '$valor',fecha_ultima_actualizacion = '$fecha' WHERE id_inventario = '$idInven'";
      }
    }else{
      //verificamos si el campo a actualizar es la clasificacion
      $descripcion = "Factura|$idFactura|Se modifica el campo ".$campo." de la tabla Facturas ahora contiene: ".$valor;
      $sqlUpdate = "UPDATE factura_inventario SET $campo = '$valor',fecha_actualizacion = '$fecha',usuario_actualiza = '$idUsuario' WHERE id_factura = '$idFactura'";
      
      
    }
    

    $queryUpdate = mysqli_query($conexion, $sqlUpdate);
    if($queryUpdate){
      //se actualizo correctamente, insertamos el movcimiento
      $setComent = setComent($usuario,$descripcion,$idFactura,"INVENTARIO");
      if($setComent == "OperationSuccess"){
        echo "UpdateSuccess";
      }else{
        echo "UpdateMal";
      }
    }

  }elseif(!empty($_POST['idFactObjUpdateImg'])){
    $idFactura = $_POST['idFactObjUpdateImg'];
    if(!empty($_FILES['newImgObjUpdate']['tmp_name'])){
      //consultamos la ruta del documento para eliminarlo
      $extencion = "";
      if($_FILES['newImgObjUpdate']['type'] == "jpg"){
        $extencion = ".jpg";
      }else if($_FILES['newImgObjUpdate']['type'] == "jpeg"){
        $extencion = ".jpeg";
      }else if($_FILES['newImgObjUpdate']['type'] == "png"){
        $extencion = ".png";
      }else{
        $extencion = ".jpg";
      }
      $usuario = $_SESSION['usNamePlataform'];
      $idUsuario = getUsuarioId($usuario);
      $fechaAux = date('Y-m-d');

      $sql = "SELECT * FROM factura_inventario a INNER JOIN documentos b ON 
      b.id_documento = a.imagen_id WHERE a.id_factura = '$idFactura'";
      $query = mysqli_query($conexion,$sql);
      $fetch = mysqli_fetch_assoc($query);
      $no = array(" ","/",".","&");
      $modelo = str_replace($no,"",$fetch['modelo']);
      $fechaAux = date('Y-m-d');
      $numAux = random_int(100, 9999);
      $auxDateSS = date("YMDHis");
      $nombreImagen = "Foto_".$modelo."_".$fechaAux."_".$numAux."_".$auxDateSS.$extencion;
      $rutaImg = "../../docs/".$nombreImagen;
      if(move_uploaded_file($_FILES['newImgObjUpdate']['tmp_name'], $rutaImg)){
        //se guardo, ahora almacenamos en la base de datos
        $sql2 = "INSERT INTO documentos (nombre_documento,ruta_documento,tipo_documento,fecha_doc,
        usuario_reg_doc,version_doc,referencia_id) VALUES ('$nombreImagen','$rutaImg','Imagen','$fechaAux',
        '$idUsuario','','$idFactura')";
        try {
          $query2 = mysqli_query($conexion, $sql2);
          $idImgNew = mysqli_insert_id($conexion);
          //actualizamos la imagen
          $sql3 = "UPDATE factura_inventario SET imagen_id = '$idImgNew',
          fecha_actualizacion = '$fechaAux' WHERE id_factura = '$idFactura'";
          try {
            $query3 = mysqli_query($conexion, $sql3);
            $coment = "Se actualiza la imagen del producto.";
            $setComent = setComent($usuario,$coment,$idFactura,"INVENTARIO");
            if($setComent == "OperationSuccess"){
              echo "operationSuccess";
            }else{
              echo "DataError|La actualizacion se realizo con errores.";
            }
          } catch (Throwable $th) {
            echo "DataError|Ocurrio un error al asignar la nueva imagen, contacte a sistemas ";
          }
          
        } catch (Throwable $th) {
          echo "DataError|Ocurrio un error al momento de actualizar la imagen, contacte a sistemas ";
        }
      }else{
        echo "DataError|Ocurrio un error al almacenar la imagen, intente de nuevo.";
      }
    }else{
      echo "DataError|No se indico imagen a actualizar";
    }
  }elseif(!empty($_POST['showPage'])){
    $pagina = $_POST['showPage'];
    $maxRows = $_POST['maxRows'];
    $inicio = $pagina-1;
    $inicio = $maxItemPage*$inicio;
    //offset = campos fuera a la izquierda

    $sql = "SELECT *,(SELECT b.nombre_objeto  FROM inventario b WHERE 
    b.factura_objeto = a.id_factura LIMIT 1) AS nombre_objeto_aux,
    (SELECT b.clasificacion FROM inventario b WHERE 
    b.factura_objeto = a.id_factura LIMIT 1) AS clasificacion,
    (SELECT b.sucursal_resguardo FROM inventario b WHERE 
    b.factura_objeto = a.id_factura LIMIT 1) AS sucursal_resguardo FROM 
    factura_inventario a ORDER BY a.id_factura DESC LIMIT $inicio,$maxItemPage";
    try {
      $query = mysqli_query($conexion, $sql);
      $datos = [];
      $i = 0;
      while($fetch = mysqli_fetch_assoc($query)){
        $datos[$i] = $fetch;
        $i++;
      }//fin del while
      echo json_encode($datos);
    } catch (Throwable $th) {
      //throw $th;
    }
  }elseif(!empty($_POST['checkPoliza'])){
    $poliza = $_POST['checkPoliza'];

    $sql = "SELECT COUNT(*) AS poliInsert FROM factura_inventario WHERE poliza_registro = '$poliza'";
    $query = mysqli_query($conexion, $sql);
    $fetch = mysqli_fetch_assoc($query);
    $totalPoli = $fetch['poliInsert'];
    if($totalPoli == 0){
      echo  "noPoliInsert";
    }else{
      echo "poliExist";
    }
  }elseif(!empty($_POST['sucShow'])){
    //mostramos las sucursales
    $suc = $_POST['sucShow'];
    $sql = "SELECT DISTINCT(clasificacion) AS clasificacion FROM inventario WHERE sucursal_resguardo = '$suc'";
    try {
      $query = mysqli_query($conexion, $sql);
      $dato = [];
      $i = 0;
      while($fetch = mysqli_fetch_assoc($query)){
        $dato[$i] = $fetch;
        $i++;
      }
      echo json_encode($dato);
    } catch (Throwable $th) {
      echo "DataError|Error al obtener informacion de las sucursales";
    }
  }elseif(!empty($_POST['clasiShow'])){
    $clasi = $_POST['clasiShow'];
    $suc = $_POST['sucByClasi'];
    $sql = "SELECT DISTINCT(lugar_resguardo) AS lugar FROM inventario WHERE sucursal_resguardo = '$suc' AND 
    clasificacion = '$clasi'";
    try {
      $query = mysqli_query($conexion, $sql);
      $datos =[];
      $i = 0;
      while($fetch = mysqli_fetch_assoc($query)){
        $datos[$i] = $fetch;
        $i++;
      }//fin del while

      echo json_encode($datos);
    } catch (Throwable $th) {
      echo "DataError|Error al obtener informacion de las clasificaciones";
    }
    
  }elseif(!empty($_POST['getObjByAreaSuc'])){
    $sucursal = $_POST['getObjByAreaSuc'];
    $clasi = $_POST['getObjByAreaClas'];
    $area = $_POST['getObjByArea2'];

    $sql = "SELECT a.codigo,a.nombre_objeto,a.resguardo_empleado,a.codigo,b.nombre,b.paterno,b.materno 
    FROM inventario a INNER JOIN empleados b ON a.resguardo_empleado = b.id_empleado 
    WHERE sucursal_resguardo = '$sucursal' AND clasificacion = '$clasi' AND 
    lugar_resguardo = '$area'";
    try {
      $query = mysqli_query($conexion, $sql);
      $datos = [];
      $i = 0;
      while($fetch = mysqli_fetch_assoc($query)){
        $datos[$i] = $fetch;
        $i++;
      }//fin del while
      echo json_encode($datos);
    } catch (Throwable $th) {
      echo "DataError|Ocurrion un error al consultar el inventario indicado.";
    }
    
    
  }elseif(!empty($_POST['objetoPadreAnexar'])){
    $idPoliza = $_POST['objetoPadreAnexar'];
    $sql = "SELECT *,(SELECT COUNT(*) FROM inventario d WHERE 
    d.clasificacion = a.clasificacion ) AS clasiExiste,a.valor_moi AS moi_original FROM factura_inventario a 
    INNER JOIN inventario b ON a.id_factura = b.factura_objeto INNER JOIN 
    clasificacion c ON a.clasificacion = c.nombre_clasificacion WHERE 
    a.id_factura = '$idPoliza' ORDER BY id_factura DESC LIMIT 1";
    try {
      $query = mysqli_query($conexion, $sql);
      $fetch = mysqli_fetch_assoc($query);
      //obtenemos los datos del objeto padre para duplicarlos en un nuevo objeto
      $nombre = $fetch['nombre_objeto_general'];
      $fechaCompra = $fetch['fecha_compra'];
      $resguardoEmpleado = $fetch['resguardo_empleado'];
      $modelo = $fetch['modelo_objeto'];
      $marca = $fetch['marca_objeto'];
      $color = $fetch['color_objeto'];
      $accesorios = $fetch['accesorios_objeto'];
      $observaciones = $fetch['observaciones_objeto'];
      $lugarResguardo = $fetch['lugar_resguardo'];
      $sucursalResguardo = $fetch['sucursal_resguardo'];
      $proveedor = $fetch['proveedor_id'];
      $usuarioActualizo = $fetch['usuario_actualizo'];
      $polizaRegistro = $fetch['poliza_registro'];
      // $valorMoi = $fetch[''];
      $facturaObjeto = $fetch['factura_objeto'];
      $fechaRegistro = $fetch['fecha_registro'];
      $clasificacion = $fetch['clasificacion'];
      $claveClasi = $fetch['clave'];
      $continuaClasi = (int) $fetch['clasiExiste'];
      $nuevoNum = $continuaClasi+1;
      $nuevoNum = str_pad($nuevoNum,4,"0",STR_PAD_LEFT);
      $codigo = "CT-".$claveClasi.$nuevoNum;
      $moiOriginal = $fetch['moi_original'];
      $cantidadOriginal = $fetch['cantidad'];


      $activo = 1;
      
      
      $sql2 = "INSERT INTO inventario (nombre_objeto,fecha_adquisicion,resguardo_empleado,
      modelo_objeto,marca_objeto,color_objeto,
      accesorios_objeto,observaciones_objeto,lugar_resguardo,sucursal_resguardo,proveedor_id,
      poliza_registro,valor_moi,factura_objeto,fecha_registro,clasificacion,articulo_activo,
      codigo,usuario_actualizo) VALUES ('$nombre','$fechaCompra','$resguardoEmpleado','$modelo','$marca','$color',
      '$accesorios','$observaciones','$lugarResguardo','$sucursalResguardo','$proveedor',
      '$polizaRegistro','$valorMoi','$facturaObjeto','$fechaRegistro','$clasificacion','$activo',
      '$codigo','$usuarioActualizo')";
      try {
        //intentamos hacer el insert del nuevo objeto
        $query2 = mysqli_query($conexion, $sql2);
        //si todo esta bien, procedemos a realizar el update del valor moi individual
        $nuevoMoi = $moiOriginal / $cantidadOriginal+1;
        $sql3 = "UPDATE inventario SET valor_moi = '$nuevoMoi' WHERE factura_objeto = '$idPoliza'";
        try {
          //intentamos hcer la actualizacion del valor moi en el inventario
          $query3 = mysqli_query($conexion, $sql3);
          //ahora actualizamos la cantidad de articulos en el factura inventario
          $nuevaCantidad = $cantidadOriginal+1;
          $sql4 = "UPDATE factura_inventario SET cantidad = '$nuevaCantidad' WHERE id_factura = '$idPoliza'";
          try {
            //intentamos actualizar la nueva cantidad
            $query4 = mysqli_query($conexion, $sql4);
            //suponemos que si se pudo y quedo actualizado
            echo "operationSuccess";
          } catch (\Throwable $th) {
            echo "DataError|Error al actualizar la cantidad de objetos";
          }
        } catch (\Throwable $th) {
          echo "DataError|Error al actualizar el valor MOI";
        }
      } catch (\Throwable $th) {
        echo "DataError|Error al obtener informacion del objeto a actualizar";
      }

    } catch (\Throwable $th) {
      //error al consultar el inventario
    }
  }elseif(!empty($_POST['facturaBaja'])){
    //seccion para dar de baja un objeto desde la raiz
    $idPoliza = $_POST['facturaBaja'];
    $motivoBaja = $_POST['motivoBaja'];
    $usuario = $_SESSION['usNamePlataform'];
    $idUsuario = getUsuarioId($usuario);
    //para darlo de baja indicaremos que no deprecia y los registros
    //los pondremos como bajas
    $sql = "SELECT * FROM factura_inventario WHERE id_factura = '$idPoliza'";
    try {
      $query = mysqli_query($conexion, $sql);
      $fetch = mysqli_fetch_assoc($query);
      //actualizamos el campo observaciones_general e indicamos el motivo de la baja
      //al igual que sus objetos hijos tendrasn elk mismo motivo de baja
      $fecha = date('Y-m-d');
      $sql2 = "UPDATE inventario SET fecha_baja = '$fecha', articulo_activo = '2', 
      valor_moi = '1', observaciones_objeto = '$motivoBaja', usuario_actualizo = '$idUsuario'
      WHERE factura_objeto = '$idPoliza'";
      try {
        $query2 = mysqli_query($conexion, $sql2);
        //ahora indicamos la baja del objeto padre
        $sql3 = "UPDATE factura_inventario SET valor_moi = '1',usuario_actualiza = '$idUsuario',
        fecha_actualizacion = '$fecha',observaciones_general = '$motivoBaja',suspender = '1' 
        WHERE id_factura = '$idPoliza'";
        try {
          //se completo la baja del articulo, insertamos el comentario
          $query3 = mysqli_query($conexion, $sql3);
          $coment = "Inventario|".$idPoliza."|Se suspende el objeto por: ".$motivoBaja;
          $setComent = setComent($usuario,$coment,$idPoliza,"INVENTARIO");
          if($setComent == "OperationSuccess"){
            echo "operationSuccess";
          }else{
            echo "DataError|La suspencion se realizo con errores.";
          }
        }catch (\Throwable $th) {
          //error al actualizar la tabla factura_inventario

        }
      } catch (\Throwable $th) {
        //error al actualizar los regisotos del inventario
      }
    } catch (\Throwable $th) {
      //error en la consulta
      echo "DataError|Error al consultar la base de datos";
    }
    

  }
}
?>