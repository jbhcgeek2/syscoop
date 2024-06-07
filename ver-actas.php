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
          <div class="titulo">Control de actas</div> 
          <div class="card-content">
            <div class="row">
              <div class="col s12">
                <div class="input-field col s12 m6 offset-m3">
                  <input type="text" id="buscaActa">
                  <label for="buscaActa">Buscar de Actas</label>
                </div>
              </div>

              <div class="col s12" style="overflow-y: scroll;height: 400px;">
                <?php 
                  //consultamos las actas 
                  $sqlDoc = "SELECT * FROM actas a INNER JOIN consejos b ON a.consejo_id = b.id_consejo  
                  ORDER BY a.fecha_registro_acta DESC";
                  $queryDoc = mysqli_query($conexion, $sqlDoc);
                  if(mysqli_num_rows($queryDoc) > 0){
                    ?>
                      <table>
                        <thead>
                          <tr>
                            <th>Consejo</th>
                            <th>Acta</th>
                            <th>Fecha Registro</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody id="resultBusqueda">
                          <?php 
                            while($fetchDoc = mysqli_fetch_assoc($queryDoc)){
                              $consejoNombre = $fetchDoc['nombre_consejo'];
                              $actaNum = $fetchDoc['acta_num']." ".$fetchDoc['numeral'];
                              $fechaActa = $fetchDoc['fecha_acta'];
                              $idActa = $fetchDoc['id_acta'];

                              
                              echo "<tr>
                                <td>$consejoNombre</td>
                                <td>$actaNum</td>
                                <td>$fechaActa</td>
                                <td>
                                  <a href='verInfoActa.php?actaNum=$idActa' class='btn waves-effect red'>
                                    <i class='material-icons'>edit</i>
                                  </a>
                                </td>
                              </tr>";
                            }
                          ?> 
                        </tbody>
                      </table>
                    <?php
                  }else{
                    //sin documentos registrados
                    echo "<div class='row'>
                      <div class='center-align'>
                        <h5><strong>Sin registros</strong></h5>
                        <img src='img/carpeta-vacia2.png' width='100'>
                      </div>
                    </div>";
                  }
                ?>
              </div>

            </div><!--FIN row principal del card-->

            <div class="row">
              <div class="col s12 center-align">
                <a href="nueva-acta.php" class="btn waves waves-effect btnBlueNormal">Nueva Acta</a>
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
  <script src="js/verActas.js"></script>
  <script src="js/sweetAlert2.min.js"></script>
  <script src="js/verUsuarios.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
