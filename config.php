<!DOCTYPE html>
<html lang="en" class="colorFondo">
  <?php
    session_start();
    include('includes/head2.php');
   ?>
<body>
<main> 
  <?php
    include('includes/con.php'); 
    include('includes/navBar.php');
    include('includes/operations/encrp.php');
    if(!empty($_SESSION['usNamePlataform'])){

  ?>
  <div class="row">

  </div>
  <div class="row">
      <div class="col s12">

      <div class="card cardStyleContent">
        <div class="titulo">Configuraciones</div>
        <div class="card-content">
          <div class="row">

            <div class="col s12 m4 l3"> 
              <div class="card tealGradient cardControl" id="getUsuarios">
                <div class="iconCard">
                  <img src="../img/usuario_.png" alt="" width="100%">
                </div>
                <div class="cardName">
                  Ver Usuarios
                </div>
              </div>
            </div>

            <div class="col s12 m4 l3">
              <div class="card cardControl redGradient" id="setUsers">
                <div class="iconCard">
                  <img src="../img/configuracion.png" alt="" width="100%">
                </div>
                <div class="cardName">
                  Registrar Usuarios
                </div>
              </div>
            </div>

            

          </div><!--Fin row del div card-->
        </div>

        
      </div>

        

        

      </div>
  </div>


    </main>
  <?php
    //require_once('includes/footer.php'); 
  ?>


  <!--  Scripts-->
  <!-- <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script> -->
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <script src="js/config.js"></script>
  <script src="js/sweetAlert2.min.js"></script>
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
