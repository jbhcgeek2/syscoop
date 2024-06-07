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

  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Registrar Proveedor</div>
          <div class="card-content">
            <div class="row">
              
            <form id="formNewProv" name="formNewProv" enctype="multipart/form-data">
              <div class="col s12">
                <div class="input-field col s12 m4 l3">
                  <input type="text" id="nombreProveedor" name="nombreProveedor">
                  <label for="nombreProveedor">Nombre Proveedor</label>
                </div>
                <div class="input-field col s12 m4 l2">
                  <input type="tel" name="telProveedor" id="telProveedor">
                  <label for="telProveedor">Telefono</label>
                </div>
                <div class="input-field col s12 m4 l2">
                  <input type="text" name="rfcProveedor" id="rfcProveedor">
                  <label for="rfcProveedor">RFC</label>
                </div>
                <div class="input-field col s12 m4 l5">
                  <input type="text" name="direccion" id="direccion">
                  <label for="direccion">Direccion</label>
                </div>
                

              </div>
              <div class="col s6 m2 offset-m8 center-align">
                <a href="proveedores.php" class="btn waves waves-effect red darken-4">Regresar</a>
              </div>
              <div class="col s6 m2 center-align">
              <a href="#!" id="saveProv" class="btn waves waves-effect blue">Guardar</a>
              </div>
            </form>

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
  <script src="js/altaProveedor.js"></script>
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
