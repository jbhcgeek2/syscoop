<!DOCTYPE html>
<html lang="en" class="colorFondo">
  <?php
    //error_reporting(E_ALL);
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
          <div class="titulo">Inicio</div>
          <div class="card-content">
            <div class="row">
             
            <a href="#!" class="btn" id="btnSolicita">Ver cosas</a>
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
  <script src="js/redeco.js"></script>
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
