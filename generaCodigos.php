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
    if(!empty($_SESSION['usNamePlataform'])){
    //verificamos el tipo de usuario

  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Generacion de codigos</div>
          <div class="card-content">
            <div class="row">
              <div class="input-field col s12 m4">
                <select name="sucCodes" id="sucCodes">
                  <option value="" selected disabled>Seleccione</option>
                  <?php 
                    $sqlSuc = "SELECT DISTINCT(sucursal_resguardo) FROM inventario";
                    $querySuc = mysqli_query($conexion, $sqlSuc);
                    if(mysqli_num_rows($querySuc) > 0){
                      while($fetchSuc = mysqli_fetch_assoc($querySuc)){
                        echo "<option value ='".$fetchSuc['sucursal_resguardo']."'>".$fetchSuc['sucursal_resguardo']."</option>";
                      }
                    }else{
                      echo "<option value =''>sucursales con objetos</option>";
                    }
                    //include("generaQrPDF.php");
                    
                  ?>
                </select>
                <label for="sucCodes">Sucursal</label>
              </div>
              <div class="input-field col s12 m4">
                <select name="clasiBySuc" id="clasiBySuc">
                  <option value="" selected disabled>Seleccione Sucursal</option>
                </select>
                <label for="clasiBySuc">Clasificacion</label>
              </div>
              <div class="input-field col s12 m4">
                <select name="lugarByClasi" id="lugarByClasi">
                  <option value="" selected disabled>Seleccione...</option>
                </select>
                <label for="lugarByClasi">Lugar de Resguardo</label>
              </div>
            </div>

            <div class="row">
              <div class="col s12" id="resTab"></div>
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
  <script src="js/generaCodigos.js"></script>
  <script src="js/sweetAlert2.min.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
