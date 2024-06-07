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
          <div class="titulo">Control de Tickets</div>
          <div class="card-content">
            <div class="row">

            <div class="col s12 m6 center-align">
              <a href="nuevo-tipo-ticket.php" class="btn waves-effect btnGrenNormal">Agregar Tipo Ticket</a>
            </div>
            <div class="col s12 m6 center-align">
              <a href="ver-tickets.php" class="btn waves-effect btnBlueNormal">Ver Tickets</a>
            </div>


            <table>
              <thead>
                <tr>
                  <th>Tipo Ticket</th>
                  <th>Solicitante</th>
                  <th>Area Responsable</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                  //consultamos los tipos de tickets
                  $sqlT = "SELECT *,(SELECT b.nombre_departamento FROM departamentos b WHERE 
                  a.responsableTicket = b.id_departamento) AS depaResponsable,
                  (SELECT c.nombre_departamento FROM departamentos c WHERE 
                  a.solicitaTicket = c.id_departamento) AS depaSolicita FROM tipoTickets a;";
                  try {
                    $queryT = mysqli_query($conexion, $sqlT);
                    if(mysqli_num_rows($queryT) > 0){
                      while($fetchT = mysqli_fetch_assoc($queryT)){
                        $nombreTicket = $fetchT['nombreTicket'];
                        $resuelve = $fetchT['depaResponsable'];
                        $solicita = $fetchT['depaSolicita'];
                        $tipo = $fetchT['idTipoTicket'];

                        echo "<tr>
                          <td>$nombreTicket</td>
                          <td>$solicita</td>
                          <td>$resuelve</td>
                          <td>
                            <a href='verInfoTipoT.php?tipo=$tipo'>
                              <i class='material-icons'>info</i>
                            </a>
                          </td>
                        </tr>";
                      }//fin del while
                    }else{
                      //sin resultados
                    }
                  } catch (\Throwable $th) {
                    //error en la consulta
                  }

                ?>
              </tbody>
            </table>
              

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
  <script src="js/verControles.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
