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

    // consultamos si el usuario tiene tickets abiertos
    $sqlTi = "SELECT * FROM tickets WHERE (usuario_registra_ticket_id = '$idUsuario' OR  
    usuario_seguimiento_ticket_id = '$idUsuario') AND estatus_ticket IN ('Abierto','En Proceso','Resuelto')";
    $queryTi = mysqli_query($conexion, $sqlTi);
    $numTi = mysqli_num_rows($queryTi);
  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <!-- <div class="card cardStyleContent">
          <div class="titulo">Inicio</div>
          <div class="card-content"> -->
            <div class="row">
              <?php 
              if($permiso->ver_controles){
                ?>
                <!-- <div class="col s12 m4 l3">
                  <div class="card redGradient cardControl" id="verControles">
                    <div class="cardName">
                      Controles
                    </div>
                  </div>
                </div> -->
                <div class="col s12 m4 l3">
                  <div class="card cardControl5 card-2" id="verControles">
                    <div class="col s8">
                      <div class="cardName">
                        Controles
                      </div>  
                    </div>
                  </div>
                </div>
                <?php
              }
              if($permiso->ver_inventario){
                ?>

              <!-- <div class="col s12 m4 l3">
                <div class="card purpleGradient cardControl" id="verInvent">
                  <div class="cardName">
                    Inventario
                  </div>  
                </div>
              </div> -->

              <div class="col s12 m4 l3">
                <div class="card cardControl5 card-1" id="verInvent">
                  <div class="col s8">
                    <div class="cardName">
                      Inventario
                    </div>  
                  </div>
                </div>
              </div>
              <?php 
              }
              
              if($permiso->ver_proveedores == 1){
                ?>
                <!-- <div class="col s12 m4 l3">
                  <div class="card tealGradient cardControl" id="verProv">
                    <div class="cardName">
                      Proveedores
                    </div>  
                  </div>
                </div> -->

                <div class="col s12 m4 l3">
                  <div class="card cardControl5 card-3" id="verProv">
                    <div class="col s8">
                      <div class="cardName">
                        Proveedores
                      </div>  
                    </div>
                  </div>
                </div>
                <?php
              }
              if($permiso->actualizar_foto == 1){
                ?>
                <!-- <div class="col s12 m4 l3">
                  <div class="card tealGradient cardControl" id="updatePicture">
                    <div class="cardName">
                      Actualizar Fotos
                    </div>  
                  </div>
                </div> -->

                <!-- <div class="col s12 m4 l3">
                  <div class="card cardControl5 card-4" id="updatePicture">
                    <div class="col s8">
                      <div class="cardName">
                      Actualizar Fotos
                      </div>  
                    </div>
                  </div>
                </div> -->
                <?php
              }

              ?>
              <!-- <div class="col s12 m4 l3">
                <div class="card blueGradient cardControl" id="showTickets">
                  <div class="cardName">
                    Tickets
                  </div>  
                </div>
              </div> -->
              
              <div class="col s12 m4 l3">
                <div class="card cardControl5 card-5" id="showTickets">
                  <div class="col s8">
                    <div class="cardName">
                      Tickets
                    </div>  
                  </div>
                </div>
              </div>

              
              

             </div><!--FIN row principal del card -->
          <!-- </div>FIN Cardcontent principal -->
        <!-- </div>FIN cardStyleContent card principal -->

        <!-- Sin formato realizaremos unos cuadros para mostrar los pendientes -->

        <div class="row">
          <div class="col s12">


            <div class="col s12 m8">
              <div class="card cardStyleContent">
                  <div class="titulo">Mi Archivo</div>
                  <div class="card-content">
                    <div class="row" style="height:250px;overflow-y:scroll;">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Tipo</th>
                            <th>Documento</th>
                            <th>Version</th>
                            <th>Actualizacion</th>
                            <th>ver</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                            //consultamos los tickets que tenga abiertos el usuario
                            $numDocs = 0;
                            $sqlTi2 = "SELECT * FROM manuales_formatos a INNER JOIN documentos b 
                            ON a.version_lectura = b.id_documento WHERE a.departamento_doc_id = '$idDep' OR 
                            a.departamento_doc_id = '2' ORDER BY a.fecha_actualizacion DESC";
                            try {
                              $queryTi2 = mysqli_query($conexion, $sqlTi2);
                              if(mysqli_num_rows($queryTi2) > 0){
                                //mostramos los documentos
                                while($fetchTi2 = mysqli_fetch_assoc($queryTi2)){
                                  $nombreFile = $fetchTi2['nombre_man_form'];
                                  $tipoDoc = $fetchTi2['tipo_doc'];
                                  $versionFile = $fetchTi2['edicion'];
                                  $lectura = $fetchTi2['ruta_documento'];
                                  $acta = $fetchTi2['acta_id'];
                                  $idDoc = $fetchTi2['id_man_form'];
                                  $fechaAct = $fetchTi2['fecha_actualizacion'];
                                  if(strlen($nombreFile) > 25){
                                    $nombreFile = substr($nombreFile, 0, 25)."...";
                                  }
                                  //si el documento es manual, y no esta autorizado, no lo mostramos
                                  if($tipoDoc == "Manual" || $tipoDoc == "Anexo" || $tipoDoc == "Formato"){
                                    $numDocs = $numDocs + 1;

                                    echo "<tr>
                                    <td>$tipoDoc</td>
                                    <td>$nombreFile</td>
                                    <td>$versionFile</td>
                                    <td>$fechaAct</td>
                                    <td>
                                      <a href='verInfoDoc.php?docInfo=$idDoc' >
                                        <i class='material-icons green-text'>library_books</i>
                                      </a>
                                    </td>
                                    </tr>";
                                  }
                                  

                                  
                                }//fin del while
                              }else{
                                //nop tiene documentos asignados
                              }
                            } catch (\Throwable $th) {
                              //throw $th;
                            }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
              </div>
            </div>

            <div class="col s12 m4 l4" id="showCardTicket">
              <div class="cardTest" >
                <div class="col m4">
                  <img src="../img/apoyo-tecnico.png" alt="" class="imgcardTest">
                </div>
                <div class="col m8 cardTextos">
                  <span class="numeroCard"><?php echo $numTi; ?></span><br>
                  <span class="tituloCardX">Tickets Abiertos</span>
                </div>
              </div>
            </div>

            <div class="col s12 m4 l4" id="showManuales">
              <div class="cardTest2">
                <div class="col m4">
                  <img src="../img/carpeta.png" alt="" class="imgcardTest">
                </div>
                <div class="col m8 cardTextos">
                  <span class="numeroCard"><?php echo $numDocs; ?></span><br>
                  <span class="tituloCardX">Manuales Disponibles</span>
                </div>
              </div>
            </div>

          </div>

          
        </div>

        <?php
          //consultamos los tickets proximos a vencer
          if($permiso->ver_movimientos == 1){
          ?>
          <div class="card cardStyleContent">
            <div class="titulo">Ultimos Movimientos</div>
            <div class="card-content">
              
              <div class="row" style="height:300px;overflow-y:scroll;">
                <table>
                  <thead>
                    <tr>
                      <th>Tipo Movimiento</th>
                      <th>Fecha</th>
                      <th>Hora</th>
                      <th>Usuario</th>
                      <th>Descripcion</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $sqlMov = "SELECT * FROM movimientos ORDER BY id_movimiento DESC LIMIT 25";
                      $queryMov = mysqli_query($conexion, $sqlMov);
                      if($queryMov){
                        //mstramos los ultimos movimientos
                        while($fetchMov = mysqli_fetch_assoc($queryMov)){
                          $tipoMov = $fetchMov['tipo_movimiento'];
                          $fechaMov = $fetchMov['fecha_movimiento'];
                          $horaMov = $fetchMov['hora_movimiento'];
                          $usuarioMov = $fetchMov['usuario_movimiento'];
                          $descrMov = $fetchMov['descripcion_movimiento'];
                          echo "
                          <tr>
                            <td>$tipoMov</td>
                            <td>$fechaMov</td>
                            <td>$horaMov</td>
                            <td>$usuarioMov</td>
                            <td>$descrMov</td>
                          </tr>";
                        }
                      }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <?php
          }
        ?>

        

      </div><!--FIN col principal 12-->
  </div>


    </main>
  <?php
    //require_once('includes/footer.php'); 

    
  ?>

  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <script src="js/control.js"></script>
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
