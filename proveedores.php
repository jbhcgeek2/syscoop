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
    //verificamos el tipo de usuario

    if($permiso->ver_proveedores != 1){
      header('location: control.php');
    }

  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Control de Proveedores</div>
          <div class="card-content">
            <div class="row">
              <div class="row">
                <div class="input-field col s12 m6 offset-m3">
                  <input type="text" id="buscarProov">
                  <label for="buscarProov">Buscar proveedor</label>
                </div>
              </div>

              <?php 
              if($permiso->agregar_proveedor == 1){
                ?> 
                <div class="row">
                  <div class="col s12 right-align">
                    <a href="nuevo-proveedor.php" class="btn blue waves waves-effect">
                      Registrar Proveedor
                    </a>
                  </div>
                </div>
                <?php
              }
              ?>

            <div class="row">
              <div class="col s12" style="height:400px;overflow-y: scroll;">
                <table class="centered black-text striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Telefono</th>
                      <th>RFC</th>
                      <th>Ver Mas</th>
                    </tr>
                  </thead>
                  <tbody id="resProvs">
                    <?php 
                      //consultaremos los objetos del inventario
                      $sqlInv = "SELECT * FROM PROVEEDORES";
                      $queryInv = mysqli_query($conexion,$sqlInv);
                      if($queryInv){
                        if(mysqli_num_rows($queryInv) > 0){
                          while($fetchInv = mysqli_fetch_assoc($queryInv)){
                            
                            $id = $fetchInv['id_proveedor'];
                            $nombre = $fetchInv['nombre_proveedor'];
                            $telefono = $fetchInv['telefono_proveedor'];
                            $rfc = $fetchInv['rfc_proveedor'];

                            echo "<tr>
                              <td>$id</td>
                              <td>$nombre</td>
                              <td>$telefono</td>
                              <td>$rfc</td>
                              <td>
                                <a href='ver-proveedor.php?provId=$id'>
                                  <i class='material-icons'>screen_share</i>
                                </a>
                              </td>

                            </tr>";
                          }//fin del while
                        }else{
                          //no se encontraron resultados
                          echo "<tr><td colspan='5'>SIN RESULTADOS</td></tr>";
                        }
                      }else{
                        //error al realizar la consulta
                      }
                    ?>
                  </tbody>
                </table>

              </div>

            </div>
            <div class="row">
              <div class="col s12 m4 offset-m4 center-align">
                <a href="nuevo-proveedor.php" class="btn waves waves-effect blue">
                  Nuevo Proveedor
                </a>
              </div>
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
  <script src="js/proovedores.js"></script>
  <!-- <script src="js/start.js"></script> -->
  <script src="js/sweetAlert2.min.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
