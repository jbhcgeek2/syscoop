<?php
session_start();
$urlServer = $_SERVER['REQUEST_URI'];
if($urlServer == "/" || $urlServer == "/index.php"){
  
}else{
?> 
<div class="navbar-fixed hide-on-large-only">

  <nav class="primary"><!--Color navBar-->
    <div class="nav-wrapper container">
      <?php
      ?>
      <a href="#!" class="brand-logo">
        <!--<img src="img/linealPin_red-8_2.png" alt="comfirmApp" class="brand-logo center hide-on-small-only" width="170px">-->
        
        
        
      </a>
      <a href="#!" data-target="mobile-nav" class="sidenav-trigger">
        <i class="material-icons">menu</i>
      </a>

    </div>
  </nav>


</div>


<ul class="sidenav sidenav-fixed navBarStyle" id="mobile-nav"> 
  <!-- <div class="divider"></div> -->
  <?php
  }
    if(!empty($_SESSION['usNamePlataform'])){
      include("operations/usuarios.php");
      // echo $_SERVER['REQUEST_URI'];
      
      $uu = $_SESSION['usNamePlataform'];
      $usuarioPlat = getDataUser($uu);
      $datosPerNav = json_decode($usuarioPlat);
      // print_r($datosPerNav);
      $idUsuario = $datosPerNav->id_usuario;
      $permUser = getPermisos($uu);
      $permiso = json_decode($permUser);

      $idEmpleado = getEmpleadoIdByUser($uu);
      $idDep = getDepaIdByUser($uu);

      if(!empty($datosPerNav->imgPerfil)){
        $imgPerfil = $datosPerNav->imgPerfil;
      }else{
        $imgPerfil = "img/imgPerfilDefaut.jpg";
      }

      //puesto_id
      $nombrePerAct = $datosPerNav->nombre." ".$datosPerNav->paterno;
      $emailPerAct = $datosPerNav->correo;
      $puestoIdUsuario = $datosPerNav->cargo_id;
      $permisoAgregarManuales = $permiso->agregar_manuales;
      ?>
        <!-- <li>
          <a href=../ class="center-align" style="font-size:large;">
            <img src="img/linealPin_red-8.png" alt="comfirmApp" class="center" width="170px" style="margin-top:10px !important;margin: 0 auto;">
          </a>
        </li> -->
        <div class="col s12 center">
          <!--<img src="img/linealPin_red-8_2.png" alt="comfirmApp" class="brand-logo center" width="170px">-->
        </div>

        <div class="row center rowNav">  
          <div class="col s4 offset-s4 center-align">
            <a href="miPerfil.php">
              <img src="<?php echo $imgPerfil; ?>" alt="" class="circle circleExtra" style="width: 100px;height: 100px;">
            </a>
          </div>
          <div class="col s12">
            <span class="spanNombreNav"><?php echo $nombrePerAct; ?></span>
          </div>
          <div class="col s12">
            <span class="spanMailNav"><?php echo $emailPerAct; ?></span>
          </div>
        </div>





        <!-- <li class="bold"><a href="about.html" class="waves-effect waves-teal">About</a></li>
        <li class="bold active"><a href="getting-started.html" class="waves-effect waves-teal">Getting Started</a></li> -->

        <li class="">
          <ul class="collapsible collapsible-accordion">
            <li class="bold">
              <a href="../control.php" class="waves-red collapsible-header waves-effect navItemText">
                <i class="material-icons  white-text">home</i>
                Inicio
              </a>
            </li>
            <li class="bold">
              <a href="../miPerfil.php" class="waves-red collapsible-header waves-effect navItemText">
                <i class="material-icons  white-text">account_circle</i>
                Mi Perfil
              </a>
            </li>
            <?php 
              if($permiso->ver_controles == 1){
                ?>
                <li class="">
                  <a class="collapsible-header waves-effect waves-red navItemText">
                    <i class="material-icons  white-text iconNavStyle">settings</i>
                    Configuraciones
                  </a>
                  <div class="collapsible-body subItemNav">
                    <ul>
                      <li><a href="verControles.php" class="white-text">Panel de Control</a></li>
                      <li><a href="ver-usuarios.php" class="white-text">Ver Usuarios</a></li>
                      <li><a href="nuevo-usuario.php" class="white-text">Nuevo Usuario</a></li>
                      <li><a href="control-tickets.php" class="white-text">Ver Tipo Tickets</a></li>
                      <li><a href="ver-consejos.php" class="white-text">Ver Consejos</a></li>
                    </ul>
                  </div>
                </li>
                <?php
              }
              if($permiso->ver_inventario == 1){
                ?>
                <li>
                  <a href="#!" class="collapsible-header waves-red navItemText">
                    <i class="material-icons white-text iconNavStyle">dvr</i>
                    Inventario
                  </a>
                  <div class="collapsible-body subItemNav">
                    <ul>
                      <li><a href="inventario.php" class="white-text">Ver Inventario</a></li>
                      <li><a href="repDepreciacion.php" class="white-text">Reporte Depreciasion</a></li>
                      <?php 
                      if($permiso->agregar_inventario == 1){
                        ?>
                        <li><a href="nuevo-objeto.php" class="white-text">Registrar Objetos</a></li>
                        <?php
                      }
                      if($permiso->auditar_inventario == 1){
                        ?>
                        <li><a href="auditarInventario.php" class="white-text">Auditar Inventario</a></li>
                        <?php
                      }
                      ?>
                      <li><a href="generaCodigos.php" class="white-text">Generar Codigos</a></li>
                    </ul>
                  </div>
                </li>
                <?php
              }// fin ver inventario
              if($permiso->ver_proveedores == 1){
                ?>
                <li>
                  <a href="#!" class="collapsible-header waves-red navItemText">
                    <i class="material-icons white-text iconNavStyle">local_shipping</i>
                    Proveedores
                  </a>
                  <div class="collapsible-body subItemNav">
                    <ul>
                      <li><a href="proveedores.php" class="white-text">Ver Proveedores</a></li>
                      <?php 
                      if($permiso->agregar_proveedores == 1){
                        ?>
                        <li><a href="nuevo-proveedor.php" class="white-text">Registrar Proveedores</a></li>
                        <?php
                      }
                      ?>
                    </ul>
                  </div>
                </li>
                <?php
              } 
            ?>
            <li>
              <a href="#!" class="collapsible-header waves-red navItemText">
                <i class="material-icons white-text iconNavStyle">local_library</i>
                Documentacion
              </a>
              <div class="collapsible-body subItemNav">
                <ul>
                  <li><a href="ver-manuales.php" class="white-text">Documentos y Manuales</a></li>
                <?php
                    if($permiso->agregar_manuales == 1){
                      // echo '<li><a href="ver-manuales.php" class="white-text">Documentos y Manuales</a></li>';
                      echo '<li><a href="nuevo-documento.php" class="white-text">Registrar Documento</a></li>';
                    } 
                    if($permiso->ver_actas == 1){
                      echo '<li><a href="ver-actas.php" class="white-text">Ver Actas</a></li>';
                    }
                    if($permiso->agregar_actas == 1){
                      echo '<li><a href="nueva-acta.php" class="white-text">Registrar Acta</a></li>';
                    }
                  ?>
                  
                
                </ul>
              </div>
            </li>
            <li>
              <a href="#!" class="collapsible-header waves-red navItemText">
                <i class="material-icons white-text iconNavStyle">policy</i>
                Solicitudes Internas
              </a>
              <div class="collapsible-body subItemNav">
                <ul>
                  <li><a href="ver-tickets.php" class="white-text">Ver Tickets</a></li>
                  <li><a href="nuevo-ticket.php" class="white-text">Registrar Ticket</a></li>
                  <?php
                    //verificamos si tiene permiso para controles
                    if($permiso->ver_controles){
                      echo '<li><a href="control-tickets.php" class="white-text">Catalogo de Tickets</a></li>';
                      echo '<li><a href="nuevo-tipo-ticket.php" class="white-text">Nuevo Tipo de Ticket</a></li>';
                    } 
                  ?>
                </ul>
              </div>
            </li>
            <!-- <li class="bold">
              <a href="#!" class="waves-red collapsible-header waves-effect navItemText">
                <i class="material-icons white-text">person_pin</i>
                HELP
              </a>
            </li> -->
            <li class="bold">
              <a href="../cerrar.php" class="waves-red collapsible-header waves-effect  navItemText">
                <i class="material-icons  red-text">power_settings_new</i>
                Cerrar Sesion
              </a>
            </li>
          </ul>
        </li>






      <?php
    }else{
      // echo '<li class="item"><a href="cerrar.php">Cerrar Sesion</a></li>';
    }
  ?>
  

</ul>
