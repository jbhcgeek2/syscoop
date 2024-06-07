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
          <div class="titulo">Panel de Control</div>
          <div class="card-content">
            <div class="row">

            <div class="col s12 m4 l3">
              <div class="card blueGradient cardControl" id="verUsuarios">
                <!-- <div class="iconCard">
                  <img src="../img/inventario.png" alt="" width="100%">
                </div> -->
                <div class="cardName">
                  Usuarios
                </div>
              </div>
            </div>

            <div class="col s12 m4 l3">
              <div class="card tealGradient cardControl" id="verSucursales">
                <!-- <div class="iconCard">
                  <img src="../img/ubicaciones.png" alt="" width="100%">
                </div> -->
                <div class="cardName">
                  Sucursales
                </div>
              </div>
            </div>

            <div class="col s12 m4 l3">
              <div class="card orange cardControl" id="verEmpleados">
                <!-- <div class="iconCard">
                  <img src="../img/empleados.png" alt="" width="100%">
                </div> -->
                <div class="cardName">
                  Empleados
                </div>
              </div>
            </div>

            <div class="col s12 m4 l3">
              <div class="card pink cardControl" id="verPuestos">
                <!-- <div class="iconCard">
                  <img src="../img/empleados.png" alt="" width="100%">
                </div> -->
                <div class="cardName">
                  Puestos
                </div>
              </div>
            </div>

            <div class="col s12 m4 l3">
              <div class="card amber cardControl" id="verConsejos">
                <!-- <div class="iconCard">
                  <img src="../img/mesa.png" alt="" width="100%">
                </div> -->
                <div class="cardName">
                  Consejos
                </div>
              </div>
            </div>

            <div class="col s12 m4 l3">
              <div class="card orange cardControl" id="verTickets">
                <!-- <div class="iconCard">
                  <img src="../img/mesa.png" alt="" width="100%">
                </div> -->
                <div class="cardName">
                  Tickets
                </div>
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
  <!-- <script src="js/start.js"></script> -->
  <script src="js/sweetAlert2.min.js"></script>
  <script src="js/vercontroles.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
