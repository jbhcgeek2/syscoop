<!DOCTYPE html>
<html lang="en" class="colorFondo">
  <?php
    //session_start();
    include('includes/head2.php');
   ?>
<body>
<main> 
  <?php
    include('includes/_con.php');
    include('includes/navBar.php');
    //include('phpqrcode/qrlib.php');
    include('phpqrcode/phpqrcode.php');
    //include('includes/operations/encrp.php');
    if(!empty($_SESSION['usNamePlataform'])){
    //verificamos la existencia del objeto
    if(!empty($_GET['objId'])){
      $idPoliza = $_GET['objId'];
      //include('php-barcode-master/barcode.php');
      //verificamos la existencia del objeto
      /*$sqlObj = "SELECT *,
      (SELECT x.ruta_documento FROM DOCUMENTOS x WHERE x.id_documento = a.factura_objeto ) AS rutaFactura,
      (SELECT h.ruta_documento FROM DOCUMENTOS h WHERE h.id_documento = a.poliza_registro) AS rutaPoliza 
      FROM INVENTARIO a INNER JOIN PROVEEDORES b ON
      a.proveedor_id = b.id_proveedor INNER JOIN EMPLEADOS c ON 
      a.resguardo_empleado = c.id_empleado INNER JOIN USUARIOS d ON 
      a.usuario_actualizo = d.id_usuario  WHERE a.id_inventario = '$idFactura'";*/

    }else{
      //no seencontro el objeto
      header('location: control.php');
    }
    
  ?>

  <div class="row">
    <div class="col s12">
      <?php 
        $sqlFact = "SELECT *,
        (SELECT b.ruta_documento FROM documentos b WHERE a.factura_id = 
        b.id_documento AND b.tipo_documento = 'Factura') AS doc_factura,
        (SELECT c.ruta_documento FROM documentos c WHERE a.poliza_id = 
        c.id_documento AND c.tipo_documento = 'Poliza') AS doc_poliza,
        (SELECT c.ruta_documento FROM documentos c WHERE a.imagen_id = 
        c.id_documento AND c.tipo_documento = 'Imagen') AS doc_imagen
          FROM factura_inventario a INNER JOIN proveedores b ON 
          a.proveedor_id = b.id_proveedor WHERE a.id_factura  = '$idPoliza'";
  
        $queryObj = mysqli_query($conexion, $sqlFact);
        if(mysqli_num_rows($queryObj) > 0){

          while($fetchObj = mysqli_fetch_assoc($queryObj)){
            $poliza = $fetchObj['poliza_registro'];
            $idFactura = $fetchObj['id_factura'];
            $nombreUsuarioUpdate = getUserById($fetchObj['usuario_actualiza']);
            $nombreProveedor = $fetchObj['nombre_proveedor'];
            ?>
            <input type="hidden" id="polizaOriginal" value="<?php echo $idFactura; ?>">
            <input type="hidden" id="facturaIdMod" name="facturaIdMod" value="<?php echo $idPoliza; ?>">

            <div class="card cardStyleContent">
            <div class="titulo">Informacion de Objeto</div>
            <div class="card-content">
            <div class="row">
              <div class="col s12 m3 center-align">
                <img class="materialboxed" src="<?php echo $fetchObj['doc_imagen']; ?>" width="90%" alt="">
                <a href="#modalChangeImg" class="btn modal-trigger waves-effect btnBlueNormal" id="changeImgObj">Cambiar Imagen</a>

                <div class="row">
                  <div class="col s12 center-align">
                    <a href="#!" class="btn redButon" id="btnAddObjeto">Agregar Objeto</a>
                  </div>
                </div>
                <div class="row">
                  <div class="col s12 center-align">
                    <a href="#!" class="btn redButon" id="bajaObjetoTotal">Suspender</a>
                  </div>
                </div>
              </div>
              <div class="col s12 m9">
                <div class="input-field col s12 m4 l2">
                  <input type="text" value="<?php echo $poliza; ?>" id="poliza_registro" name="poliza_registro" onchange="updateCampo(this.id,this.value)">
                  <label for="poliza_registro">Poliza Registro</label>
                </div>
                <div class="input-field col s6 m4 l4">
                  <input type="text" value="<?php echo $fetchObj['nombre_objeto_general']; ?>" id="nombre_objeto_general" name="nombre_objeto_general" onchange="updateCampo(this.id,this.value)">
                  <label for="nombre_objeto_general">Nombre</label>
                </div>
                <div class="input-field col s6 m4 l2">
                  <input type="text" value="$<?php echo number_format($fetchObj['valor_moi']); ?>" id="valor_moi" name="valor_moi" readonly>
                  <label for="valorMOI">Valor MOI</label>
                </div>
                <div class="input-field col s12 m2 l2">
                  <input type="text" value="<?php echo $fetchObj['cantidad']; ?>" id="cantidad" name="cantidad" readonly>
                  <label for="cantidad">Cantidad</label>
                </div>
                <div class="input-field col s12 m3 l2">
                  <input type="text" value="<?php echo $fetchObj['fecha_compra']; ?>" id="cantidad" name="cantidad" readonly>
                  <label for="cantidad">Fecha Compra</label>
                </div>
                <div class="input-field col s12 m3 l3">
                  <select name="no_deprecia" id="no_deprecia" onchange="updateCampo(this.id,this.value)">
                    <?php 
                      if($fetchObj['no_deprecia'] == "0"){
                        echo "<option value='1'>Si</option><option value='0' selected>No</option>";
                      }else{
                        echo "<option value='1' selected>Si</option><option value='0'>No</option>";
                      }
                    ?>
                  </select>
                  <label for="sinodeprecia">¿El objeto deprecia?</label>
                </div>
                <div class="input-field col s12 m4 l4">
                  <input type="text" id="modelo" name="modelo" value="<?php echo $fetchObj['modelo']; ?>" onchange="updateCampo(this.id,this.value)">
                  <label>Modelo</label>
                </div>
                <div class="input-field col s12 m6 l4">
                  <input type="text" id="marca" name="marca" value="<?php echo $fetchObj['marca']; ?>" onchange="updateCampo(this.id,this.value)">
                  <label for="marca">Marca</label>
                </div>
                <div class="input-field col s12 m6 l4">
                  <select name="clasificacion" id="clasificacion">
                    <option value="" disabled>Seleccione..</option>
                    <?php 
                      $sqlClasi = "SELECT * FROM clasificacion WHERE mostrar = '1'";
                      $porceMOI = "0";
                      $queryClasi = mysqli_query($conexion, $sqlClasi);
                      while($fetchClasi = mysqli_fetch_assoc($queryClasi)){
                        $nombreClasi = $fetchClasi['nombre_clasificacion'];
                        if($nombreClasi == $fetchObj['clasificacion']){
                          echo "<option value='$nombreClasi' selected disabled>$nombreClasi</option>";
                          $porceMOI = $fetchClasi['porce_depreciacion'];
                        }else{
                          echo "<option value='$nombreClasi'>$nombreClasi</option>";
                        }
                        
                      }

                      if($fetchObj['valor_moi'] <= 1){
                        $moiMensual = 0;
                        $mesesPendientes = 0;
                        $saldoPendiente = 0;
                        $mesesMoi = 0;
                      }else{
                        $porcentaje = $porceMOI/100;
                        $moiAnual = $fetchObj['valor_moi']*$porcentaje;
                        $moiMensual = $moiAnual/12;
                        $mesesMoi = $fetchObj['valor_moi']/$moiMensual;
                        $fechaInicial = new DateTime($fetchObj['fecha_compra']);
                        $fechaActual = new DateTime(date('Y-m-d'));
                        $fechaInicialCalculo = $fetchObj['fecha_compra'];

                        $dif = $fechaInicial->diff($fechaActual);
                        $meses = ($dif->y*12)+$dif->m;
                        $saldoPendiente = number_format($fetchObj['valor_moi'] - ($moiMensual * $meses));
                        $mesesPendientes = $mesesMoi - $meses;
                      }
                      

                      
                    ?>
                  </select>
                  <label>Clasificacion</label>
                </div>
                <div class="input-field col s12 m6 l6">
                  <input type="text" id="proveedor" readonly value="<?php echo $nombreProveedor; ?>">
                  <label for="proveedor">Proveedor</label>
                </div>
                <div class="input-field col s12 m6 l3">
                  <input type="text" id="color" name="color" value="<?php echo $fetchObj['color']; ?>" onchange="updateCampo(this.id,this.value)">
                  <label for="color">Color</label>
                </div>
                <div class="input-field col s12 m3 l3">
                  <input type="text" id="fechaUpdateFactura" name="fechaUpdateFactura" value="<?php echo $fetchObj['fecha_actualizacion']; ?>" readonly>
                  <label for="color">Fecha Actualizacion</label>
                </div>
                <div class="input-field col s12 m3 l4">
                  <input type="text" id="fechaUpdateFactura" name="fechaUpdateFactura" value="<?php echo $nombreUsuarioUpdate; ?>" readonly>
                  <label for="color">Usuario Actualizo</label>
                </div>
                <div class="input-field col s12 m3 l2">
                  <input type="text" id="moi_actual" value="$<?php echo number_format($moiMensual,2); ?>" readonly>
                  <label for="moi_actual">MOI Mensual</label>
                </div>
                <div class="input-field col s12 m3 l3">
                  <input type="text" id="mesesDep" value="<?php echo $mesesMoi; ?>" readonly>
                  <label for="mesesDep">Meses a depreciar</label>
                </div>
                <div class="input-field col s12 m3 l3">
                  <input type="text" id="saldoXDep" value="$<?php echo $saldoPendiente; ?>" readonly>
                  <label for="saldoXDep">Saldo Por Depreciar</label>
                </div>
                <div class="input-field col s12 m3 l3">
                  <input type="text" id="mesesXDep" value="<?php echo $mesesPendientes; ?>" readonly>
                  <label for="mesesXDep">Meses Por Depreciar</label>
                </div>
                

                <div class="input-field col s12 m12 l9">
                  <input type="text" value="<?php echo $fetchObj['observaciones_general']; ?>" id="observaciones_general" name="observaciones_general" onchange="updateCampo(this.id,this.value)">
                  <label for="observacionGeneral">Observaciones</label>
                </div>
              </div>
              
            </div>
          
            <div class="row">
              <div class="col s12 m6 center-align">
                <a href="<?php echo $fetchObj['doc_factura'];  ?>" 
                target="_blank" class="btn waves waves-effect btnGrenNormal">Ver factura</a>
              </div>

              <div class="col s12 m6 center-align">
                <a href="<?php echo $fetchObj['doc_poliza']; ?>"
                target="_blank" class="btn waves waves-effect btnGrenNormal">Ver Poliza</a>
              </div>
            </div>
            
        </div><!--FIN Cardcontent principal-->
      </div><!--FIN cardStyleContent card principal-->

      <div class="modal" id="modalChangeImg">
        <div class="modal-content">
          <div class="row">
            <h4 class="center-align">Actualizar Imagen</h4>
            <form id="updateImgObj" name="updateImgObj" enctype="multipart/form-data">
              <input type="hidden" name="idFactObjUpdateImg" name="idFactObjUpdateImg" value="<?php echo $idPoliza; ?>">
              <div class="input-field file-field col s12">
                <div class="btn waves-effect btnBlueNormal">
                  <span>Nueva Imagen</span>
                  <input type="file" id="newImgObjUpdate" name="newImgObjUpdate">
                </div>
                <div class="file-path-wrapper">
                  <input type="text" class="file-path validate">
                </div>
              </div>
            </form>
          </div><!--fin row-->
        </div>
        <div class="modal-footer">
          <a href="#!" class="btn-flat modal-close">Cancelar</a>
          <a href="#!"class="btn waves-effect green" id="SendUpateImg">Actualizar</a>
        </div>
      </div>


      <div class="card cardStyleContent">
        <div class="titulo">Tabla de Depreciacion</div>
        <table>
          <thead>
            <tr>
              <th class='col m1 s1 center-align'>No.</th>
              <th class='col m3 s2 center-align'>Mes</th>
              <th class='col m2 s3 center-align'>Monto a Depreciar</th>
              <th class='col m2 s3 center-align'>Monto Restante</th>
              <th class='col m3 s2 center-align'>Depreciacion acumulada</th>
            </tr>
          </thead>
        </table>
        <div class="card-content" style="overflow-y:scroll; height: 350px;">
          <table>
            
            <tbody>
              <?php
              if($fetchObj['valor_moi'] <= 1 || $fetchObj['no_deprecia'] == '0'){
                echo "<tr>
                  <td colspan='5' class='center-align'>
                  <img src='img/carpeta-vacia.png' width='100px'>
                  <h5>Depreciacion no dosponible en este articulo</h5>
                  <p>Esto se debe a que la depreciacion no esta activa en el objeto o a que el costo de este
                  es menor o igual a $1.00 MN</p>
                  </td>
                </tr>";
              }else{
                $mesArray = ["01"=>"Enero","02"=>"Febrero","03"=>"Marzo","04"=>"Abril",
                "05"=>"Mayo","06"=>"Junio","07"=>"Julio","08"=>"Agosto","09"=>"Septiembre",
                "10"=>"Octubre","11"=>"Noviembre","12"=>"Diciembre"];
  
                //consultamos si se presentan algunas bajas del producto
                $fechasBajas = [];
                $numeroBajas = 0;
                $sqlBaja = "SELECT * FROM inventario WHERE articulo_activo = '2' AND 
                factura_objeto = '$idFactura'";
                $queryBajas = mysqli_query($conexion, $sqlBaja);
                if(mysqli_num_rows($queryBajas) > 0){
                  $numeroBajas = mysqli_num_rows($queryBajas);
                  $iB = 0;
                  while($fetchBaja = mysqli_fetch_assoc($queryBajas)){
                    $fechasBajas[$iB] = $fetchBaja['fecha_baja'];
                    $iB++;
                  }//fin del while bajas
                }
                
                $fechaMal = explode("-",$fechaInicialCalculo);
                $auxMes = $fechaMal[1];
                $auxAnio = $fechaMal[0];
                $nuevaFecha = $auxAnio."-".$auxMes."-01";
                $articulosTotales = $fetchObj['cantidad'];
                $valorTotalesMoi = $fetchObj['valor_moi'];
                $valorTotalesMoi2 = $fetchObj['valor_moi']/$articulosTotales;
                $valorUaxMoi = 0;
                $valorRestante = $fetchObj['valor_moi'];
                $nuevoMoiMensual = $moiMensual/$articulosTotales;
                $uaxMoiMen = $nuevoMoiMensual;
                $acumulado = 0;
                
                //print_r($fechasBajas);
                  for($iMes = 1; $iMes <= $mesesMoi; $iMes++){
                    $fechaVa = date("Y-m-d",strtotime($nuevaFecha."+ ".$iMes." month"));
                    $fec = explode("-",$fechaVa);
                    $fechaDepre = $fec[0]." - ".$mesArray[$fec[1]];
                    $classTr = "";
                    if($numeroBajas > 0){
                      for($iBaj = 0; $iBaj < count($fechasBajas); $iBaj++){
                        $fechaBajaAux = $fechasBajas[$iBaj];
                        $fechaBajaAux2 = explode("-",$fechaBajaAux);
                        $fechaBajaAux3 = $fechaBajaAux2[0]." - ".$mesArray[$fechaBajaAux2[1]];
                        if($fechaBajaAux3 == $fechaDepre){
                          $articulosTotales = $articulosTotales -1;
                          $nuevoMoiMensual = $nuevoMoiMensual/$articulosTotales;
                          $restanMeses = ($mesesMoi - $iMes)+1;
  
                          $valorRestante = $valorRestante-($uaxMoiMen*$restanMeses);
                          $acumulado = $acumulado+( $uaxMoiMen*$restanMeses);
                          $classTr = "pink darken-1 white-text";
                        }else{}
                      }//fin for bajas
                    }
                    $valorUaxMoi = number_format($uaxMoiMen * $articulosTotales,2);
                    $valorRestante = $valorRestante-$valorUaxMoi;
                    $mostrarValor = number_format($valorRestante,2);
                    if($mostrarValor < 1){
                      $mostrarValor = "0.00";
                    }
                    $acumulado = $acumulado+$valorUaxMoi;
                    $auxAcumulado = number_format($acumulado,2);
                    if($acumulado >= $valorTotalesMoi){
                      $auxAcumulado = number_format($valorTotalesMoi,2);
                    }else{
                      $restan =  $valorTotalesMoi-$acumulado;
                      if($restan <= 1){
                        $auxAcumulado = number_format($valorTotalesMoi,2);
                      }
                    }
                    
  
                    echo "<tr class='$classTr'>
                      <td class='col s1 m1 l1 center-align'>$iMes</td>
                      <td class='col s2 m3 l2'>$fechaDepre</td>
                      <td class='col s3 m2 l3 center-align'>$ $valorUaxMoi</td>
                      <td class='col s3 m2 l3 center-align'>$ $mostrarValor</td>
                      <td class='col s2 m3 l2 center-align'>$ $auxAcumulado</td>
                    </tr>";
                  }
                  
              }
              
              ?>
            </tbody>
          </table>
        </div>
      </div>
      
      <?php 
        //realizamos la consulta de todos los productos por separado
        $sqlObjeto = "SELECT * FROM inventario a INNER JOIN usuarios b ON 
        a.usuario_actualizo = b.id_usuario WHERE a.factura_objeto = '$idFactura'";
        $queryObjeto = mysqli_query($conexion, $sqlObjeto);
        if($queryObjeto){
          while($fetchObjeto = mysqli_fetch_assoc($queryObjeto)){
            $idObjeto = $fetchObjeto['id_inventario'];
            $nameObjet = "nombre_objeto|".$idObjeto;
            $idEmpleado = $fetchObjeto['resguardo_empleado'];
            $lugarObjeto = "lugar_resguardo|".$idObjeto;
            $observObjeto = "observaciones_objeto|".$idObjeto;
            $sucurObjeto = "sucursal_resguardo|".$idObjeto;
            $empleadoObjeto = "resguardo_empleado|".$idObjeto;
            $fechaActualiza = "fechaUpdate|".$idObjeto;
            $usuarioActualiza = "usuarioUpdate|".$idObjeto;
            $resguardoObjeto = "file_resguardo|".$idObjeto;
            $idDocResguardo = $fetchObjeto['documento_resguardo'];
            $estatusObjeto = "articulo_activo|".$idObjeto;
            $fechaBaja = "fecha_baja|".$idObjeto;
            $valFechaBaja = $fetchObjeto['fecha_baja'];
            $codigo = $fetchObjeto['codigo'];

            $usuarioActualiza = getUsuarioId($usuario);

            $sqlDocRes = "SELECT * FROM documentos WHERE id_documento = '$idDocResguardo' 
            ORDER BY id_documento DESC LIMIT 1";
            $queryDocRes = mysqli_query($conexion, $sqlDocRes);
            $fecthDocRes = mysqli_fetch_assoc($queryDocRes);
            $rutaDocumento = $fecthDocRes['ruta_documento'];
            $nombreDocumento = $fecthDocRes['nombre_documento'];
            if($fetchObjeto['articulo_activo'] == 2){
              $color = "pink lighten-5";
            }else{
              $color = "";
            }
            
            ?>
              <div class="card cardStyleContent <?php echo $color; ?>">
                <div class="card-content">

                  <div class="row">
                    <div class="input-field col s12 m2 l2">
                      <input type="text" id="idObjeto" value="<?php echo $idObjeto; ?>" readonly>
                      <label for="idObjeto">Objeto ID</label>
                    </div>
                    <div class="input-field col s12 m4 l4">
                      <input type="text" id="<?php echo $nameObjet; ?>" name="<?php echo $nameObjet; ?>" value="<?php echo $fetchObjeto['nombre_objeto']; ?>" onchange="updateCampo(this.id,this.value)">
                      <label for="<?php echo $nameObjet; ?>">Nombre</label>
                    </div>
                    <div class="input-field col m3 l4">
                      <input type="text" id="<?php echo $lugarObjeto; ?>" name="" value="<?php echo $fetchObjeto['lugar_resguardo']; ?>" onchange="updateCampo(this.id,this.value)">
                      <label for="<?php echo $lugarObjeto; ?>">Lugar Resguardo</label>
                    </div>
                    <div class="input-field col m3 l2">
                      <select name="<?php echo $sucurObjeto; ?>" id="<?php echo $sucurObjeto; ?>" onchange="updateCampo(this.id,this.value)">
                      <?php 
                        $sqlSuc = "SELECT * FROM sucursales WHERE sucursal_activa = '1'";
                        $querySuc = mysqli_query($conexion, $sqlSuc);
                        while($fetchSuc = mysqli_fetch_assoc($querySuc)){
                          $sucDB = $fetchSuc['nombre_sucursal'];
                          if($sucDB == $fetchObjeto['sucursal_resguardo']){
                            echo "<option value='$sucDB' selected disabled>".$sucDB."</option>";
                          }else{
                            echo "<option value='$sucDB'>".$sucDB."</option>";
                          }
                        }//fin del while sucursales
                      ?>
                      </select>
                      <label for="<?php echo $sucurObjeto; ?>">Sucursal Resguardo</label>
                    </div>
                    <div class="input-field col m6 l5">
                      <select name="<?php echo $empleadoObjeto; ?>" id="<?php echo $empleadoObjeto; ?>" onchange="updateCampo(this.id,this.value)">
                      <?php 
                        //consultamos los empleados
                        $sqlEmpleados = "SELECT * FROM empleados";
                        $queryEmpleados = mysqli_query($conexion, $sqlEmpleados);
                        while($fetchEmpleados = mysqli_fetch_assoc($queryEmpleados)){
                          $nombreEmpleado = $fetchEmpleados['paterno']." ".$fetchEmpleados['materno']." ".$fetchEmpleados['nombre'];
                          $idEmpleadoDb = $fetchEmpleados['id_empleado'];
                          if($idEmpleadoDb == $idEmpleado){
                            echo "<option value='$idEmpleadoDb' selected disabled>".$nombreEmpleado."</option>";
                          }else{
                            echo "<option value='$idEmpleadoDb'>".$nombreEmpleado."</option>";
                          }
                        }//fin del while empleados
                      ?>
                      </select>
                      <label for="<?php echo $empleadoObjeto; ?>">Empleado asignado</label>
                    </div>
                    <div class="input-field col s12 m2 l3">
                      <input type="text" id="<?php echo $fechaActualiza; ?>" name="<?php echo $fechaActualiza; ?>"
                      value="<?php echo $fetchObjeto['fecha_ultima_actualizacion']; ?>" readonly>
                      <label for="<?php echo $fechaActualiza; ?>">Fecha Actualizacion</label>
                    </div>
                    <div class="input-field col s12 m2 l2">
                      <input type="text" id="<?php echo $usuarioActualiza; ?>" name="<?php echo $usuarioActualiza; ?>"
                      value="<?php echo $fetchObjeto['nombre_usuario'] ?>" readonly>
                      <label for="<?php echo $usuarioActualiza; ?>">Usuario Actualizo</label>
                    </div>
                    <div class="input-field col s12 m2 l2">
                      <select name="<?php echo $estatusObjeto; ?>" id="<?php echo $estatusObjeto; ?>" onchange="updateCampo(this.id,this.value)">
                        <option value="" disabled>Seleccione</option>
                        <?php 
                        if($fetchObjeto['articulo_activo'] == "1"){
                          echo "<option value='1' selected disabled>Activo</option>
                          <option value='2'>Baja</option>";
                        }else{
                          echo "<option value='1'>Activo</option>
                          <option value='2' selected disabled>Baja</option>";
                        }
                        ?>
                      </select>
                      <label for="<?php echo $usuarioActualiza; ?>">Estatus</label>
                    </div>
                    <?php 
                      if($fetchObjeto['articulo_activo'] == 2){
                        ?>
                        <div class="input-field col s12 m3 l6">
                          <input type="date" id="<?php echo $fechaBaja ?>" name="<?php echo $fechaBaja; ?>" value="<?php echo $valFechaBaja; ?>" onchange="updateCampo(this.id,this.value)">
                          <label for="">Fecha Baja</label>
                        </div>
                        <?php
                        $l = "l12";
                      }else{
                        $l = "l12";
                      }
                    ?>
                    <div class="input-field col s12 m12 <?php echo $l; ?>">
                      <input type="text" id="<?php echo $observObjeto ?>" name="<?php echo $observObjeto ?>" 
                      value="<?php echo $fetchObjeto['observaciones_objeto']; ?>" onchange="updateCampo(this.id,this.value)">
                      <label for="">Observacion</label>
                    </div>
                    <div class="file-field input-field col s12 m8">
                      <div class="btn waves-effect btnGrenNormal">
                        <span> Subir Resguardo</span>
                        <input type="file" id="<?php echo $resguardoObjeto; ?>" name="<?php echo $resguardoObjeto; ?>" onchange="updateCampo(this.id,this.value)">
                      </div>
                      <div class="file-path-wrapper validate">
                        <input type="text" class="file-path validate" value="<?php echo $nombreDocumento; ?>">
                      </div>
                    </div>
                    
                    <?php 
                      if(empty($rutaDocumento)){
                        $liga = "<a href='#!' class='btn waves-effect btnAmberNormal'>Sin Resguardo</a>";
                      }else{
                        $liga = "<a href='$rutaDocumento' tarjet='_blank' class='btn waves-effect btnGrenNormal'>Ver Resguardo</a>";
                      }
                    ?>
                    <div class="col s12 m4 center-align">
                      <?php echo $liga; ?>
                    </div>

                    <div class="col s12 center">
                      <?php 
                      $rutaBarcode = "php-barcode-master/barcode.php?text=".$codigo."&size=50&orientation=horizontal&codetype=Code39&print=true&sizefactor=2";

                      $rutaDirQr = "docs/qrImg/";
                      $fileQr = $rutaDirQr."temp_inv_".$idObjeto.".png";
                      $tamanioMatriz = 5;
                      $errorCorectionLevel = 'L';
                      $content = "https://".$serverId."/ver-obj-ind.php?obj=".$idObjeto;
                      //$fileName = $PNG_TEMP_DIR.'test'.md5($)
                      QRcode::png($content,$fileQr,"L",$tamanioMatriz,4);

                      $img = imagecreatefrompng($fileQr);

                      $txt = $codigo;
                      $fontFile = "includes/arial.ttf";
                      $fontSize = 10;
                      $fontColor = imagecolorallocate($img, 0, 0, 0);
                      $posX = 60;
                      $posY = 198;
                      $angle = 0;
                      $iWidth = imagesx($img);
                      $tSize = imagettfbbox($fontSize, $angle, $fontFile, $txt);
                      $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
                      $tHeight = max([$tSize[5], $tSize[7]]) - min([$tSize[1], $tSize[3]]);
                      $centerX = ceil(($iWidth - $tWidth) / 2);
                      $centerX = $centerX<0 ? 0 : $centerX;
                      imagettftext($img, $fontSize, $angle, $centerX, $posY, $fontColor, $fontFile, $txt);
                      $quality = 100;
                      $des2 = $rutaDirQr."temp_inv_".$codigo."_.jpg";
                      imagejpeg($img, $des2, $quality);
                      echo "<img src='".$des2."'>";
                      ?>
                    </div>

                  </div><!--fin row principal-->
                </div>
              </div>
            <?php
          }//fin del while
        }
        ?>
        <div class="card cardStyleContent">
          <div class="titulo">Historial de Movimientos</div>
          <div class="card-content" style="overflow-y:scroll; height: 400px;">
            <div class="row" >
              <?php 
              //mostramos los movimientos realizados al objeto
              $sqlMovs = "SELECT * FROM movimientos WHERE referencia_id = '$idPoliza' AND 
              tipo_movimiento ='INVENTARIO' ORDER BY id_movimiento DESC";
              $queryMovs = mysqli_query($conexion, $sqlMovs);
              if(mysqli_num_rows($queryMovs) > 0){
                while($fetchMovs = mysqli_fetch_assoc($queryMovs)){
                  $usuarioMov = $fetchMovs['usuario_movimiento'];
                  $fechaMov = $fetchMovs['fecha_movimiento'];
                  $desMov = $fetchMovs['descripcion_movimiento'];
                  $horaMov = $fetchMovs['hora_movimiento'];
                  //trabajamos el comentario para indicar el objeto modificado
                  $auxComent = explode("|",$desMov);
                  if(count($auxComent) > 1){
                    $comentario = "Objeto ID: <strong>".$auxComent[1]."</strong> ".$auxComent[2]." hora de aplicacion ".$horaMov;
                  }else{
                    $comentario = $desMov." hora de aplicacion ".$horaMov;
                  }

                  echo "
                    <div class='row'>
                      <div class='col s4 m3 l2'>
                        $fechaMov
                      </div>
                      <div class='col s8 m6 l8'>
                        $comentario
                      </div>
                      <div class='col l2 hide-on-med-and-down'>
                        $usuarioMov
                      </div>
                    </div>
                    <div class='row'><div class='divider'></div></div>
                    ";
                }//fin del whiel movs
              }else{
                ?>
                <div class="row center-align">
                  <img src='img/carpeta-vacia.png' width='100px'>
                  <h5>Sin Movimientos registrados</h5>
                </div>
                <?php
              }
              ?>
            </div>
          </div>
        </div>
        <?php
      
          }
          
        }else{
          //sin resultado
          header('location: control.php');
        }
      ?>

      

    </div><!--FIN col principal 12-->
  </div>


    </main>
  <?php
    //require_once('includes/footer.php'); 
  ?>

  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <script src="js/verObjeto.js"></script>
  <script src="js/sweetAlert2.min.js"></script>
  <script>
    var elemSel = document.querySelectorAll('select');
    var instanceSelect = M.FormSelect.init(elemSel, options);

    var elemDate = document.querySelectorAll('.datepicker');
    var instanceDate = M.Datepicker.init(elemDate, options);
  </script>
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>

