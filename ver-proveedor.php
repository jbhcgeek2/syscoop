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
    include('includes/operations/encrp.php');
    if(!empty($_SESSION['usNamePlataform'])){
    //verificamos la existencia del proveedor
    if(!empty($_GET['provId'])){
      $idProveedor = $_GET['provId'];

      $sqlProv = "SELECT *,(SELECT x.nombre_usuario FROM USUARIOS x WHERE x.id_usuario = a.usuario_registro) AS usuario_reg,
      (SELECT y.nombre_usuario FROM USUARIOS y WHERE y.id_usuario = a.usuario_actualizo) AS usuario_act FROM PROVEEDORES a WHERE a.id_proveedor = '$idProveedor'";
      $queryProv = mysqli_query($conexion, $sqlProv);
      if($queryProv && mysqli_num_rows($queryProv) == 1){
        $fetchProv = mysqli_fetch_assoc($queryProv);
        
      }else{
        header('location: control.php');
      }
    }else{
      header('location: control.php');
    }

    if($permiso->ver_proveedores != 1){
      header('location: control.php');
    }
    if($permiso->editar_proveedores == 1){
      $classInput = "editProv";
    }else{
      $classInput = "";
    }

  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Informacion de Proveedor</div>
          <div class="card-content">
            <div class="row">
              <div class="input-field col s12 m4 l1">
                <input type="text" id="idProv" value="<?php echo $fetchProv['id_proveedor']; ?>" readonly>
                <label for="idProv" class="active">ID</label>
              </div>
              <div class="input-field col s12 col m4 l3">
                <input type="text" id="nombreProv" class="<?php echo $classInput ?>" value="<?php echo $fetchProv['nombre_proveedor']; ?>">
                <label for="nombreProv" class="active">Nombre</label>
              </div>
              <div class="input-field col s12 m4 l2">
                <input type="text" id="telProv" class="<?php echo $classInput ?>" value="<?php echo $fetchProv['telefono_proveedor']; ?>">
                <label for="telProv" class="active">Telefono</label>
              </div>
              <div class="input-field col s12 m4 l2">
                <input type="text" id="rfcProv" class="<?php echo $classInput ?>" value="<?php echo $fetchProv['rfc_proveedor']; ?>">
                <label for="rfcProv" class="active">RFC</label>
              </div>
              <div class="input-field col col s12 m4 l4">
                <input type="text" id="direccionProv" class="<?php echo $classInput ?>" value="<?php echo $fetchProv['direccion_proveedor']; ?>">
                <label for="direccionProv">Direccion</label>
              </div>

              <div class="input-field col s12 m4 l2">
                <input type="text" id="fechaRegistro" value="<?php echo $fetchProv['fecha_registro_proveedor']; ?>" readonly>
                <label for="fechaRegistro" class="active">Fecha Registro</label>
              </div>
              <div class="input-field col s12 m4 l3">
                <input type="text" id="usuarioInserto" value="<?php echo $fetchProv['usuario_reg']; ?>" readonly>
                <label for="usuarioInserto" class="active">Usuario Registro</label>
              </div>
              <div class="input-field col s12 m4 l2">
                <input type="text" id="fechaActualizo" value="<?php echo $fetchProv['fecha_actualiza_proveedor']; ?>" readonly>
                <label for="fechaActualizo">Fecha Actualizo</label>
              </div>
              <div class="input-field col s12 m4 l3">
                <input type="text" id="usuarioActualizo" value="<?php echo $fetchProv['usuario_act']; ?>" readonly>
                <label for="usuarioActualizo">Usuario Actualizo</label>
              </div>

              <div class="row">
                <div class="col s6 center">
                  <a href="proveedores.php" class="btn waves waves-effect red">
                    Regresar
                  </a>
                </div>
                <?php 
                if($permiso->editar_proveedores == 1){
                  ?>
                  <div class="col s6 center">
                    <a href="#!" class="btn waves waves-effect blue" id="updateInfoProv">
                      Actualizar
                    </a>
                  </div>
                  <?php 
                }
                ?>
              </div>

            </div><!--FIN row principal del card-->
          </div><!--FIN Cardcontent principal-->
        </div><!--FIN cardStyleContent card principal-->

        <div class="card cardStyleContent">
          <div class="titulo">Objetos Inventariados</div>
          <div class="card-content">
              <?php 
                //verificamos si tienepermisospara ver los objetos
                if($permiso->ver_inventario == 1){
                  //consultamos los objetos del proveedor

                  $sqlObjProv = "SELECT * FROM inventario WHERE proveedor_id = '$idProveedor' ORDER BY 
                  nombre_objeto ASC";
                  $queryObjProv = mysqli_query($conexion, $sqlObjProv);
                  if($queryObjProv){
                    if(mysqli_num_rows($queryObjProv) > 0){
                      ?>
                      <table>
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Clasificacion</th>
                            <th>Sucursal</th>
                            <th>Ver</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                            while($fetchObjProv = mysqli_fetch_assoc($queryObjProv)){
                              $idObjprov = $fetchObjProv['id_inventario'];
                              $nombreObjInv = $fetchObjProv['nombre_objeto'];
                              $clasiObjInv = $fetchObjProv['clasificacion'];
                              $sucObjInv = $fetchObjProv['sucursal_resguardo'];
                              $qrCode = $fetchObjProv['codigo'];

                              echo "<tr>
                                <td>$idObjprov</td>
                                <td>$qrCode</td>
                                <td>$nombreObjInv</td>
                                <td>$clasiObjInv</td>
                                <td>$sucObjInv</td>
                                <td>
                                  <a href='ver-objeto.php?objId=$idObjprov'>
                                    <i class='material-icons'>screen_share</i>
                                  </a>
                                </td>
                              </tr>";
                            }//fin del while
                          ?>
                        </tbody>
                      </table>
                      <?php
                    }else{
                      //sin objetos registrados
                      ?>
                      <div class="row">
                        <div class="col s12 center">
                          <h5><b>Sin objetos</b></h5>
                          <img src="img/espera.png" alt="">
                          <p>No se han registrado objetos de este proveedor</p>
                        </div>
                      </div>
                      <?php
                    }
                  }else{
                    //ocurrio un error al consuotar la informacion
                    ?>
                    <div class="row">
                      <div class="col s12 center">
                        <h5><b>Error de consulta</b></h5>
                        <img src="img/sinDatos.png" alt="">
                        <p>ocurrio un error al consultar la informacion</p>
                      </div>
                    </div>
                    <?php
                  }
                }else{
                  ?>
                  <div class="row">
                    <div class="col s12 center">
                      <h5><b>Seccion no autorizada</b></h5>

                      <img src="img/no-autorizado.png" alt="" width="90px">
                      

                      <p>No cuentas con los permisos suficientes para ver esta seccion.</p>
                    </div>
                  </div>
                  <?php
                }
              ?>
          </div>
        </div>

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
  <script src="js/verProveedores.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
