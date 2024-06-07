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
    //$serverName = "SERVER2018\sqlexpress";;
    //$conectionInfo = array("Database"=>"tepicCierre","UID"=>"inficaja","PWD"=>"Infiwin1");
    //$conn = sqlsrv_connect($serverName, $conectionInfo);
    //if($conn){
      //echo "Si, conexion";
    //}else{
    //  echo "No Conexion";
    //  die( print_r( sqlsrv_errors(), true));
    //}
  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Actualizar Fotos</div>
          <div class="card-content">

            <div class="row">

              <div class="input-field col s5 m2">
                <input type="number" id="noSocio">
                <label for="noSocio">No. Socio</label>
              </div>
              <div class="input-field col s7 m10">
                <input type="text" id="nombreSocio" readonly>
                <label for="nombreSocio" id="labelNombreSoc">Nombre Completo</label>
              </div>
              <div class="col s12 center hide" id="botonConfirma">
                <div class="col s6">
                  <a href="#!" class="btn waves waves-effect green darken-4" id="btnConfirmaSocio">Tomar Foto</a>
                </div>
                <div class="col s6">
                  <a href="#!" class="btn waves waves-effect green darken-4" id="btnConfirmaFirma">Tomar Firma</a>
                </div>
              </div>

              <div class="row">
              <div class="col s12 center hide camView" id="camView">
                <div class="liveVideoCam">
                  <canvas id="canvaFoto" class="hide center-aling" width="500" height="500"></canvas>
                  <canvas id="canvaFotoFirma" class="hide center-aling" width="500" height="150"></canvas>
                  <img src="img/foto-marco.png" alt="" class="marcoVid hide" id="marcoVid">
                  <video id="liveVideoCamFoto" class="" playsinline autoplay></video>
                </div>
                <div class="col s12 controller">
                  <div class="col s4">
                    <a href="#!" id="reintentarFoto" class="btn waves waves-effect green darken-4 hide">Reintentar</a>  
                  </div>
                  <div class="col s4 center">
                    <a href="#!" id="snap" class="hide btn waves waves-effect green darken-4">Tomar Foto</a>  
                    <a href="#!" id="snapFirma" class=" hide btn waves waves-effect green darken-4">Tomar Foto F.</a>  
                  </div>
                  <div class="col s4">
                    <a href="#!" id="saveFoto" class="btn waves waves-effect green darken-4 hide">Guardar Foto</a>
                    <a href="#!" id="saveFirma" class="btn waves waves-effect green darken-4 hide">Guardar Firma</a>
                  </div>
                  
                </div>
                
                </div>
              </div>
<div id="errorMsg"></div>
              

              

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
  <script src="js/updatePicture.js"></script>
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
