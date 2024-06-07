<!DOCTYPE html>
<html lang="en">
  <?php
    require_once('includes/head.php');
  ?>
<body class="imgFondo">
  <?php
    if(empty($_SESSION['usNamePlataform'])){
    //require_once('includes/navBar.php');
  ?>
  <div class="row rowLogin">

    <!-- INICIO CARD LOGIN -->

    <div class="col s10 offset-s1 m8 offset-m2 l4 offset-l4">
      <div class="card cardLoginStyle">
        <div class="card-content background_1 cardLoginStyle">
          <div class="row">
            <div class="col s12 center">
              <!-- imagen -->
              <div class="row" class="">
                <img src="img/Syscoop-logo.png" alt="SysCoop" class="center imgLoginLog">
                
              </div>


              <div class="input-field col s12">
                <input type="text" name="userName" id="userName">
                <label for="userName">Usuario</label>
              </div>

              <div class="input-field col s12">
                <input type="password" name="contra" id="contra">
                <label for="password">Clave</label>
              </div>
              
              <div class="row">
                <div class="col s12">
                  <p>
                    <label>
                      <input type="checkbox" class="filled-in checkbox-color" id="remem" name="remem">
                      <span>Mantener mi sesion activa</span>
                    </label>
                  </p>
                </div>
              </div>

              <div class="row">
                <div class="col s12">
                  <a href="#!" class="btn waves-effect waves-light blueButon" id="btnSend">
                    Ingresar
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- FIN CARD LOGIN -->
  </div>



  <?php
    require_once('includes/footer.php');
  ?>


  <!--  Scripts-->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <!-- <script src="js/start.js"></script> -->
  <script src="js/sweetAlert2.min.js"></script>
  <script src="js/login.js"></script>
  <?php
}else{

  header('location: control.php');
}
   ?>
  </body>
</html>
