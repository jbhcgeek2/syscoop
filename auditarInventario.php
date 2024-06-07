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
    //verificamos que contemos con permisos de auditar inventario
    if($permiso->auditar_inventario == 0){
      ?>
      <script>
        window.location = 'index.php';
      </script>
      <?php
    }

  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Revisiones de inventario</div>
          <div class="card-content">
            <div class="row">
              <?php 
                $sqlAu = "SELECT * FROM auditoria_inventario ORDER BY id_auditoria DESC";
                $queryAu = mysqli_query($conexion, $sqlAu);
                if(mysqli_num_rows($queryAu) > 0){
                  ?>
                  <div class="col s12 m12 l10 offset-l1" style="overflow-y: scroll;height: 400px;">
                    <table class="centered">
                      <thead>
                        <tr>
                          <th>Tipo</th>
                          <th>Fecha Inicio</th>
                          <th>Fecha Fin</th>
                          <th>Usuario Encargado</th>
                          <th>Ver</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
                        while($fetchAu = mysqli_fetch_assoc($queryAu)){
                          $tipo = $fetchAu['tipo_auditoria'];
                          $inicio = $fetchAu['fecha_inicio'];
                          $user = $fetchAu['usuario_inicia'];
                          $usuarioIni = getUserById($user);
                          $id = $fetchAu['id_auditoria'];
                          $fechaFin = $fetchAu['fecha_fin'];
                          echo "<tr>
                            <td>$tipo</td>
                            <td>$inicio</td>
                            <td>$fechaFin</td>
                            <td>$usuarioIni</td>
                            <td>
                              <a href='ver-auditoria.php?info=$id'><i class='material-icons'>queue_play_next</i></a>
                            </td>
                          </tr>";
                        }//fin del while
                      ?>
                      </tbody>
                    </table>
                  </div>
                  <?php
                }else{
                  //sin resulytados
                  ?>
                  <div class="row center">
                    <h5 class="center-align">Sin Auditorias registradas</h5>
                    <img src="img/carpeta-vacia2.png" width="100px" class="center-align">
                  </div>
                  <?php
                }

              ?>
              

            </div><!--FIN row principal del card-->

            <div class="row">
              <div class="col s12 m6 center-align">
                <a href="#!" class="btn waves waves-effect btnGrenNormal" onclick="genNewAudi('valida');">Generar Conciliacion</a>
              </div>
              <div class="col s12 m6 center-align">
                <a href="#!" class="btn waves waves-effect btnGrenNormal" onclick="genNewAudi('Audito');">Generar Auditoria</a>
              </div>
            </div>
            
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
  <script src="js/verAuditorias.js"></script>
  
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
