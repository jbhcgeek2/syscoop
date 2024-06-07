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
    //include('includes/operations/encrp.php');
    if(!empty($_GET['obj'])){
    //verificamos el tipo de usuario
    if(!empty($_GET['obj'])){
      //verificamos que exista el documento
      $idInventario = $_GET['obj'];

      $sqlInv = "SELECT *,(SELECT c.ruta_documento FROM documentos c WHERE 
      c.id_documento = b.imagen_id) AS ruta_imagen,
      (SELECT d.ruta_documento FROM documentos d WHERE d.id_documento = b.poliza_id) AS ruta_poliza,
      (SELECT e.ruta_documento FROM documentos e WHERE e.id_documento = b.factura_id) AS ruta_factura 
      FROM inventario a INNER JOIN factura_inventario b ON a.factura_objeto = b.id_factura 
      INNER JOIN usuarios f ON a.usuario_actualizo = f.id_usuario WHERE a.id_inventario = '$idInventario'";
      try{
        $queryInv = mysqli_query($conexion, $sqlInv);
        //verificamos la informacion del documento
        $fetchObjeto = mysqli_fetch_assoc($queryInv);

        if($fetchObjeto['documento_resguardo'] == "0" || empty($fetchObjeto['documento_resguardo'])){
          //objeto sin resguardo
          $clasResg = "btnAmberNormal";
          $rutaResg = "#!";
          $tar = "";
        }else{
          //si cuenta con resguardo
          $rutaResg = $fetchObjeto['documento_resguardo'];
          $clasResg = "btnGrenNormal";
          $tar = "_blank";
        }

        $usActu = $fetchObjeto['nombre_usuario'];
        $docFactu = $fetchObjeto['ruta_factura'];
        $docPoli = $fetchObjeto['ruta_poliza'];
        $idEmpleado = $fetchObjeto['empleado_id'];
        $fechaBaja = $fetchObjeto['fecha_baja'];
        
        if($fetchObjeto['articulo_activo'] == "2"){
          $classBaja = "pink lighten-5";
        }else{
          $classBaja = "";
        }
        


      }catch(Throwable $e){
        //error al consultar el dato
        echo "<script>
         window.location = 'inventario.php';
        </script>";
      }
    }else{
      echo "<script>
        window.location = 'index.php';
      </script>";
    }
  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent <?php echo $classBaja; ?>">
          <div class="titulo">Informacion de Objeto</div>
          <div class="card-content">
            <div class="row">
              <div class="col s12 m4 l4">
                <img src="<?php echo $fetchObjeto['ruta_imagen']; ?>"class="materialboxed" width="100%">
              </div>
              <div class="col s12 m8 l8" style="">


                <div class="input-field col s12 m6 l5">
                  <input type="text" id="nombreInv" value="<?php echo $fetchObjeto['nombre_objeto']; ?>" readonly>
                  <label for="nombreInv">Nombre</label>
                </div>
                    <div class="input-field col s12 m6 l4">
                      <input type="text" id="lugarRes" value="<?php echo $fetchObjeto['lugar_resguardo']; ?>" readonly>
                      <label for="lugarRes">Lugar Resguardo</label>
                    </div>
                    <div class="input-field col s6 m4 l3">
                      <input type="text" id="sucRes" value="<?php echo $fetchObjeto['sucursal_resguardo']; ?>">
                      <label for="sucRes>">Sucursal Resguardo</label>
                    </div>
                    <div class="input-field col s6 m4 l3">
                      <select id="estatusObj" readonly>
                        <option value="" disabled>Seleccione</option>
                        <?php 
                        if($fetchObjeto['articulo_activo'] == "1"){
                          echo "<option value='1' selected>Activo</option>
                          <option value='2'>Baja</option>";
                        }else{
                          echo "<option value='1'>Activo</option>
                          <option value='2' selected disabled>Baja</option>";
                        }
                        ?>
                      </select>
                      <label for="estatusObj">Estatus</label>
                    </div>
                    <div class="input-field col s12 m8 l6">
                      <select name="empAsig" id="<?php echo $empleadoObjeto; ?>" readonly>
                      <?php 
                        //consultamos los empleados
                        $sqlEmpleados = "SELECT * FROM EMPLEADOS";
                        $queryEmpleados = mysqli_query($conexion, $sqlEmpleados);
                        while($fetchEmpleados = mysqli_fetch_assoc($queryEmpleados)){
                          $nombreEmpleado = $fetchEmpleados['paterno']." ".$fetchEmpleados['materno']." ".$fetchEmpleados['nombre'];
                          $idEmpleadoDb = $fetchEmpleados['id_empleado'];
                          if($idEmpleadoDb == $idEmpleado){
                            echo "<option value='$idEmpleadoDb' selected>".$nombreEmpleado."</option>";
                          }else{
                            echo "<option value='$idEmpleadoDb' disabled>".$nombreEmpleado."</option>";
                          }
                        }//fin del while empleados
                      ?>
                      </select>
                      <label for="empAsig">Empleado asignado</label>
                    </div>
                    <div class="input-field col s6 m4 l3">
                      <input type="text" id="fechActu" value="<?php echo $fetchObjeto['fecha_ultima_actualizacion']; ?>" readonly>
                      <label for="fechActu">Fecha Actualizacion</label>
                    </div>
                    <div class="input-field col s6 m4 l3">
                      <input type="text" id="usActu" value="<?php echo $usActu; ?>" readonly>
                      <label for="usActu">Usuario Actualizo</label>
                    </div>
                    
                    <?php 
                      if($fetchObjeto['articulo_activo'] == 2){
                        ?>
                        <div class="input-field col s12 m3 l6">
                          <input type="date" id="fecBaja" value="<?php echo $fechaBaja; ?>">
                          <label for="fecBaja">Fecha Baja</label>
                        </div>
                        <?php
                        $l = "l12";
                      }else{
                        $l = "l9";
                      }
                    ?>
                    <div class="input-field col s12 m12 <?php echo $l; ?>">
                      <input type="text" id="observObj" value="<?php echo $fetchObjeto['observaciones_objeto']; ?>" readonly>
                      <label for="observObj">Observacion</label>
                    </div>
                    


              </div>
              <div class="row">
              <div class="col s6 m4 center-align">
                <a href="<?php echo $docFactu;  ?>" 
                target="_blank" class="btn waves waves-effect btnGrenNormal">Ver factura</a>
              </div>
              <div class="col s6 m4 center-align">
                <a href="<?php echo $docPoli; ?>"
                target="_blank" class="btn waves waves-effect btnGrenNormal">Ver Poliza</a>
              </div>
              <div class="col s12 m4 center-align">
                <a href="<?php echo $rutaResg; ?>"
                target="<?php echo $tar; ?>" id="btnResg" class="btn waves waves-effect <?php echo $clasResg; ?>">Ver Resguardo</a>
              </div>
            </div>

              <div class="row">
                <div class="col s12 center-align">
                  <a href="ver-objeto.php?objId=<?php echo $fetchObjeto['id_factura']; ?>" target="_blank" class="btn btnBlueNormal waves-effect">Ver Completo</a>
                </div>
              </div>

              <div class="row">
                <?php 
                  //verifricamos si se encuentra activa una validacion o auditoria de inventario
                  //y si el usuario tiene permiso para realizarla
                  if($permiso->auditar_inventario == 1){
                    $depaUsuario = getDepaByUser($_SESSION['usNamePlataform']);
                    $tipoRevi = "";
                    if($depaUsuario != "Auditoria"){
                      $tipoRevi = "Conciliacion";
                    }else{
                      $tipoRevi = "Auditoria";
                    }

                    $sqlEx = "SELECT * FROM auditoria_inventario WHERE fecha_fin IS NULL AND 
                    tipo_auditoria = '$tipoRevi'";
                    $queryEx = mysqli_query($conexion, $sqlEx);
                    if(mysqli_num_rows($queryEx) > 1){
                      //verificamos el tipo de usuario
                      
                      echo "<div class='col s12 center'>
                        <h5>Auditoria no disponible</h5>
                        <p>Ocurrio un error al validar la revision, verifique con soporte tecnico.</p>
                      </div>";

                    }elseif(mysqli_num_rows($queryEx) == 1){
                      //mostramos la informacion de la revision
                      //verificamos si el objeto ya fue revisado
                      $fetchAudi = mysqli_fetch_assoc($queryEx);
                      $idAudito = $fetchAudi['id_auditoria'];

                      $sqlEx2 = "SELECT * FROM auditoria_objeto WHERE auditoria_id = '$idAudito' 
                      AND inventario_id = '$idInventario'";
                      $queryEx2 = mysqli_query($conexion, $sqlEx2);
                      if(mysqli_num_rows($queryEx2) == 0){
                        $fechaInicio = $fetchAudi['fecha_inicio'];
                        echo "<div class='col s12'>
                        <div class='input-field col s12'>
                          <input type='date' readonly id='fechaInicio' value='$fechaInicio'>
                          <label for='fechaInicio'>Fecha de Inicio</label>
                        </div>
                        <div class='input-field col s12 m4'>
                          <select id='estadoObjeto'>
                            <option value='' selected disabled>Seleccione...</option>
                            <option value='Nuevo'>Nuevo</option>
                            <option value='Uso Regular'>Uso regular</option>
                            <option value='Viejo'>Viejo</option>
                            <option value='Dañado'>Dañado</option>
                          </select>
                          <label for='estadoObjeto'>Estado Objeto</label>
                        </div>
                        <div class='input-field col s12 m8'>
                          <input type='text' id='observacionesAudiObj'>
                          <label for='observaciones'>Observaciones</label>
                        </div>
                        <div class='row center'>
                          <div class='col s12 m4 offset-m4 l4 offset-l4'>
                            <a href='#!' onclick='validaObjetoInv($idAudito,$idInventario)' class='btn waves-effect btnBlueNormal'>Validar Objeto</a>
                          </div>
                        </div>
                      </div>";
                      }else{
                        $fetchAudi2 = mysqli_fetch_assoc($queryEx2);
                        $idUs2 = $fetchAudi2['usuario_inventariado'];
                        $usRev = getUserById($idUs2);
                        echo "<h5 class='center-align'>Objeto ya revisado por $usRev</h5>";
                      }
                      
                    }
                  }
                ?>
              </div>

            </div><!--FIN row principal del card-->
            
          </div><!--FIN Cardcontent principal-->
        </div><!--FIN cardStyleContent card principal-->

      </div><!--FIN col principal 12-->
  </div>

    </main>
  <?php
    //require_once('includes/footer.php'); 
  ?>

  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  
  <!-- <script src="js/start.js"></script> -->
  <script src="js/sweetAlert2.min.js"></script>
  <script src="js/infoAuditoria2.js"></script>
  <!--<script src="js/vercontroles.js"></script>
  <script src="js/login.js"></script> -->
  <script>
    let btnResg = document.getElementById("btnResg");
    btnResg.addEventListener("click", function(){
      Swal.fire(
        'Sin Resguardo',
        'El objeto no cuenta con resguardo digitalizado.',
        'warning'
      )
    })
  </script>
  <?php
}else{
  header('location: index.php');
}
   ?>
  </body>
</html>
