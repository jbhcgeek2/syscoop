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
    //verificamos el departamento del usuario para mostrarle sus manuales correspondientes
    $ticket = $_GET['info']; 

    $sqlT = "SELECT * FROM tickets a INNER JOIN tipoTickets b 
    ON a.tipo_ticket_id = b.idTipoTicket INNER JOIN departamentos c 
    ON a.area_seguimiento_ticket_id = c.id_departamento INNER JOIN 
    estatus_ticket d ON a.estatus_ticket = d.nombreEstatusTicket 
    WHERE a.id_ticket = '$ticket'";
    try {
      $queryT = mysqli_query($conexion, $sqlT);
      if(mysqli_num_rows($queryT) > 0){
        //si existe la informacion
        $fetchT = mysqli_fetch_assoc($queryT);
        $estatusT1 = $fetchT['estatus_ticket'];
        $nombreT = $fetchT['nombreTicket'];
        $fechaReg = $fetchT['fecha_registro_ticket'];
        $prioridad = $fetchT['prioridad_ticket'];
        $solicitanteId = $fetchT['usuario_registra_ticket_id'];
        $areaAsignada = $fetchT['nombre_departamento'];
        $idAreaAsignada = $fetchT['id_departamento'];
        $descripcionT = $fetchT['descripcion_ticket'];
        $diasAtencion = $fetchT['diasAtencion'];
        $usuarioIdAsignado = $fetchT['usuario_seguimiento_ticket_id'];
        $estatusActual = $fetchT['estatus_ticket'];
        $descripcionEstatus = $fetchT['descripcionEstatusTicket'];
        $usuarioAsignado = "";

        $empleado = getUserById($solicitanteId);
        $usuarioEmpleado = $empleado;//guarda el USERNAME EJ: JOEL
        $empleado = getDataUser($empleado);
        $empleado = json_decode($empleado);
        $idDepaUsuario = getDepaIdByUser($_SESSION['usNamePlataform']);//departamento de la sesion
        $nombreEmpleado = $empleado->nombre." ".$empleado->paterno." ".$empleado->materno;
        $usuarioEmpleadoAsignado = getUserById($solicitanteId);
        

        if($usuarioIdAsignado != NULL || $usuarioIdAsignado != ""){
          $usuarioAsignado = getUserById($usuarioIdAsignado);
          
        }
        

        if($usuarioEmpleado != $_SESSION['usNamePlataform']){
          //si el usuario es diferente lo sacamos a la lista de tickets
          //pero si el usuario pertenece al departamento asignado por el ticket
          //lo dejamos continuar
          //verificamos si el departamento es "TODOS"
          if($idAreaAsignada != 2){
            if($idDepaUsuario != $idAreaAsignada){
              header('location:ver-tickets.php');
              ?>
                <script>
                  window.location = 'ver-tickets.php';
                </script>
              <?php
            }
          }
          
          
        }
        //Para


        $fechaInicial = new DateTime($fechaReg); // Fecha inicial
        $diasHabiles = $diasAtencion; // Número de días hábiles a sumar
        $contadorDiasHabiles = 0; // Contador de días hábiles

        // Crear un bucle para sumar los días hábiles
        while ($contadorDiasHabiles < $diasHabiles) {
            $fechaInicial->modify('+1 day');
            // Verificar si es un día hábil
            if ($fechaInicial->format('w') != 0 && $fechaInicial->format('w') != 6) {
                $contadorDiasHabiles++; // Incrementar el contador solo si es un día hábil
            }
        }


        // Obtener la fecha final como cadena
        $fechaFinalString = $fechaInicial->format('Y-m-d');
      }else{
        //el ticket no existe, lo redirigimos a los tickets
        ?> 
        <script src="js/sweetAlert2.min.js"></script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
        <script>
          Swal.fire({
            icon: 'error',
            title: 'Error de Ticket',
            text: 'Puede que no tengas los permisos suficientes para ver este ticket.',
            confirmButtonText: 'Regresar'
          }).then(function(){
            window.location = 'ver-tickets.php';
          });
        </script>
        <?php
      } 
    } catch (Throwable $th) {
      //throw $th;
    }
    
  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Informacion de solicitud</div>
          <div class="card-content">

          <div class="row">
            <div class="col s12 center-align">
              <?php
                //seccion de botones de accion para usuarios
                //si la sesion pertenece al usuario que solicito, no mostraremos
                //el boton de tomar ticket
                //si, no tiene usuario asignado, mostraremos el boton

                //el usuario del ticket no podra hacer cambios en sus estatus si aun no tiene
                //empleado asignado

                //Solo cuando el ticket este Resulto, el usuario del ticket podra
                //cerrarlo, Reabrirlo
                if($usuarioIdAsignado == NULL){
                  //el ticket aun no esta tomado, pondremos en disabled el select
                  $classSelect = "disabled";
                }else{ 
                  if($usuarioEmpleado != $_SESSION['usNamePlataform']){
                    $classSelect = "";
                  }else{
                    $classSelect = "disabled";
                  }
                }

                //seccion para Asignar el estatus en el progress bar
                $imagenes = [];
                $clase = [];
                $actual = 0;
                $estatusAux = ['Abierto','En Proceso','Resuelto','Cerrado'];
                for($xl = 0; $xl < count($estatusAux); $xl++){
                  if($estatusAux[$xl] == $estatusActual){
                    $actual = $xl;
                  }
                }//fin del for

                for($xs = 0; $xs < count($estatusAux); $xs++){

                  if($actual >= $xs){
                    $imagenes[$xs] = "../img/comprobado.png";
                    $clase[$xs] = "labelTicket";
                  }else{
                    $imagenes[$xs] = "../img/pendiente.png";
                    $clase[$xs] = "labelTicketPend";
                  }

                }//fin del for


                if($usuarioEmpleado != $_SESSION['usNamePlataform'] && $usuarioIdAsignado == NULL ){
                  //mostramos el boton de tomar ticket
                  $idBtnTomar = "btnTomarTicket";
                  $classTomar = "pulse";
                }else{
                  if($usuarioAsignado == $_SESSION['usNamePlataform'] && $estatusActual == "Abierto"){
                    $idBtnTomar = "btnTomarTicket";  
                    $classTomar = "pulse";
                  }else{
                    $idBtnTomar = "btnTomTick";
                    $classTomar = "";
                  }
                  
                }

                if($usuarioAsignado == $_SESSION['usNamePlataform'] && $estatusActual == "En Proceso"){
                  //habilitamos el metodo para terminar el tickets
                  $terminarTick = "btnTerminar";
                }else{
                  //no se muestra el metodo para terminar el ticket
                  $terminarTick = "btnTer";
                }
                
                //si el ticket esta resulto solo el usuario que aperturo podra cerrarlo
                if($_SESSION['usNamePlataform'] == $usuarioEmpleadoAsignado && $estatusActual == "Resuelto"){
                  //si se cumple la condicion mostraremos el boton de cerrar ticket
                  $classSelect = "";
                  $classClose = "btnCloseTicket";
                  ?>
                    <!-- <script src="js/sweetAlert2.min.js"></script> -->
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                      Swal.fire({
                        title: 'Ticket Resuelto',
                        text: 'Verifica si el ticket ha sido solucionado y si es correcto, lo cierres.',
                        icon: 'info',
                      })
                    </script>
                  <?php
                }else{
                  //aqui estaba un boton de generar formato
                  $classClose = "btnticketRojo";
                }


                if($idBtnTomar == "btnTomarTicket"){
                  ?> 
                  <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
                  <script src="js/sweetAlert2.min.js"></script>
                    <script>
                      Swal.fire(
                        'Ticket Abierto',
                        'Para poder atender este ticket, da click en el boton "Tomar Ticket"',
                        'warning'
                      )
                    </script>
                  <?php
                }
              ?>
              
              <!-- Boton Generar Formato -->
              <div class="col s12 m4">
                <a href="#!" class="btn btnGrenNormal <?php echo $classTomar; ?>" id="<?php echo $idBtnTomar; ?>">Tomar Ticket</a>
              </div>
              <div class="col s12 m4">
                <a href="#!" class="btn btnBlueNormal" id="<?php echo $terminarTick; ?>">Dar Por Solucionado</a>
              </div>
              <div class="col s12 m4">
                <a href="#!" class="btn btnRedNormal" id="<?php echo $classClose; ?>">Cerrar Ticket</a>
              </div>
              
            </div>
          </div>

          <div class="row" style="margin-bottom:40px !important;"><!--ROW LOADER-->
            <div class="col m10 offset-m1 center-align">
              <div class="col m3">
                <img src="<?php echo $imagenes[0]; ?>"><br>
                <span class="<?php echo $clase[0]; ?>">Abierto</span>
              </div>
              <div class="col m3">
                <img src="<?php echo $imagenes[1]; ?>" ><br>
                <span class="<?php echo $clase[1]; ?>">En Proceso</span>
              </div>
              <div class="col m3">
                <img src="<?php echo $imagenes[2]; ?>" ><br>
                <span class="<?php echo $clase[2]; ?>">Resuelto</span>
              </div>
              <div class="col m3">
                <img src="<?php echo $imagenes[3]; ?>" ><br>
                <span class="<?php echo $clase[3]; ?>">Cerrado</span>
              </div>
            </div>
            <div class="col s12 center-align">
              <?php 
                //indicamos el significado de este estatus
                echo "<br><br><span><strong>".$descripcionEstatus."</strong></span>";
              ?>
            </div>
          </div><!--FIN ROW LOADER-->
            
          <div class="row">


            <ul class="collapsible">
              <li>
                <div class="collapsible-header green lighten-5">
                  <i class="material-icons">chrome_reader_mode</i>
                  <strong><?php echo $nombreT; ?></strong> - <?php echo $nombreEmpleado; ?>
                </div>
                <div class="collapsible-body teal lighten-5">
                  <div class="row">
                    <!-- INICIO INFORMACION DEL TICKET -->
                    <input type="hidden" id="infoTicket" value="<?php echo $ticket; ?>">
                    <div class="input-field col s12 m4 l4">
                      <input type="text" id="nombreTicket" name="nombreTicket" 
                      value="<?php echo $nombreT; ?>" readonly>
                      <label for="nombreTicket">Nombre Ticket</label>
                    </div>
                    <div class="input-field col s12 m3 l3">
                      <select name="estatusTicket" id="estatusTicket" <?php echo $classSelect; ?>>
                        <option value="" selected>Seleccione</option>
                        <?php
                          //unicamente el encargado del ticket podra hacer modificaciones al estatus
                          //siempre y cuando este tomado o asignado directamente
                          $sqlEst = "SELECT * FROM estatus_ticket WHERE estatusTicketActivo = '1'";
                          try {
                            //copnsultamos los estatus del ticket
                            $queryEst = mysqli_query($conexion, $sqlEst);
                            while($fetchEst = mysqli_fetch_assoc($queryEst)){
                              $nombreEst = $fetchEst['nombreEstatusTicket'];
                              $nombreMostrar = "";
                              if($nombreEst == "Reabierto"){
                                $nombreMostrar = "Rechazar Entrega";
                                // $nombreEst = "En Proceso";
                              }else{
                                $nombreMostrar = $nombreEst;
                              }
                              if($nombreEst == $estatusT1){
                                echo "<option value='$nombreEst' selected disabled>$nombreMostrar</option>";
                                
                              }else{
                                echo "<option value='$nombreEst'>$nombreMostrar</option>";
                              }
                            }//fin del while
                          } catch (Throwable $th) {
                            echo "<option value=''>Error en la base de datos</option>";
                          }
                        ?>
                      </select>
                      <label for="estatusTicket">Estatus</label>
                    </div>
                    <div class="input-field col s12 m3 l2">
                      <input type="date" id="fechaTicket" value="<?php echo $fechaReg; ?>" readonly>
                      <label for="fechaTicket">Fecha Registro</label>
                    </div>

                    <div class="input-field col s12 m2 l3">
                      <input type="text" id="prioridad" value="<?php echo $prioridad; ?>" readonly>
                      <label for="prioridad">Prioridad</label>
                    </div> 

                    <div class="input-field col s12 m6 l4">
                      <input type="text" id="usuarioSolicita" value="<?php echo $nombreEmpleado; ?>" readonly>
                      <label for="usuarioSolicita">Solicitante</label>
                    </div>
                    <div class="input-field col s12 m3 l4">
                      <input type="text" id="areaAsignada" value="<?php echo $areaAsignada; ?>" readonly>
                      <label for="areaAsignada">Area Asignada</label>
                    </div>
                    <div class="input-field col s12 m3 l4">
                      <input type="date" id="fechaLimite" value="<?php echo $fechaFinalString; ?>" readonly>
                      <label for="fechaLimite">Fecha Limite</label>
                    </div>

                    <div class="input-field col s12 m12 l6 offset-l3">
                      <input type="text" id="usuarioAsignado" value="<?php echo $usuarioAsignado; ?>" readonly>
                      <label for="usuarioAsignado">Empleado Asignado</label>
                    </div>

                    <!-- FIN SECCION INFORMACION DEL TICKET -->
                  </div><!--FIN ROW collapsible-->
                </div>
              </li>
            </ul>
            


            <div class="row">
              <div class="col s12">
                <h5 class="center-align">Informacion Clave</h5>
                <?php 
                  //mostramos los  campos que requiere el ticket
                  $camposT = $fetchT['camposTicket'];
                  $camposTicket = $fetchT['campos_tipo_ticket'];
                  $auxC = explode("|",$camposT);
                  $auxD = explode("|",$camposTicket);

                  for($t = 0; $t < count($auxC); $t++){
                    echo "<div class='input-field col s12 m4'>
                      <input type='text' id='campoAdd$t' name='campoAdd$t' value='".$auxD[$t]."' readonly>
                      <label for='campoAdd$t'>".$auxC[$t]."</label>
                    </div>";
                  }//fin del for
                ?>

                <div class="input-field col s12 m12">
                  <textarea name="descripcion" class="materialize-textarea" 
                  id="descripcion" cols="30" rows="10" readonly><?php echo $descripcionT; ?></textarea>
                  <label for="descripcion">Descripcion</label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col s12 center-align"><h5>Documentacion Anexada</h5></div>
              
              <div class="col s12 m9 file-field input-field">
                <div class="btn red">
                  <span>Anexar Documento</span>
                  <input type="file" id="nuevoAnexoTicket">
                </div>
                <div class="file-path-wrapper">
                  <input type="text" class="file-path validate" placeholder="Sube tu documento">
                </div>
              </div>

              <div class="col s12 m3">
                <a href="#!" class="btn btnBlueNormal" id="btnUploadAnexo">Subir documento</a>
              </div>

            </div>

            <div class="row">
              <table class="centered">
                <thead>
                  <tr>
                    <th>Nombre Documento</th>
                    <th>Fecha Subida</th>
                    <th>Propietario</th>
                    <th>Ver</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    //buscaremos la documentacion que se ha subido al ticket
                    $sqlDoc = "SELECT * FROM documentos a INNER JOIN usuarios b ON 
                    a.usuario_reg_doc = b.id_usuario WHERE a.tipo_documento = 'Anexo Ticket' 
                    AND a.referencia_id = '$ticket'";
                    try {
                      $queryDoc = mysqli_query($conexion, $sqlDoc);
                      if(mysqli_num_rows($queryDoc) > 0){
                        while($fetchDoc = mysqli_fetch_assoc($queryDoc)){
                          $nombreDoc = $fetchDoc['nombre_documento'];
                          $fechaSub = $fetchDoc['fecha_doc'];
                          $subio = $fetchDoc['nombre_usuario'];
                          $link = $fetchDoc['ruta_documento'];

                          echo "<tr>
                            <td>$nombreDoc</td>
                            <td>$fechaSub</td>
                            <td>$subio</td>
                            <td>
                              <a href='$link' target='_blank'>
                                <i class='material-icons'>info</i>
                              </a>
                            </td>
                          </tr>";
                        }

                      }else{
                        //no se tiene cargada documentacion
                        echo "<tr><td colspan='4'>Sin documentacion Cargada<td></tr>";
                        echo "<tr><td colspan='4'><img src='../img/carpeta-vacia.png' width='100'><td></tr>";
                      }
                    } catch (\Throwable $th) {
                      //error al consultar los documentos
                    }
                  ?>
                </tbody>
              </table>
            </div>

          </div>

        
          </div><!--FIN Cardcontent principal-->
        </div><!--FIN cardStyleContent card principal-->

        <div class="card cardStyleContent">
          <div class="titulo">Comentarios</div>
          <div class="card-content">
            <div class="row">

              <div class="input-field col s12 m10 offset-m1">
                <input type="text" id="newComent" name="newComent" >
                <label for="newComent">Nuevo Comentario</label>
              </div>

              <div class="col s12">
                <?php 

                  $sqlCom = "SELECT * FROM movimientos WHERE tipo_movimiento = 'Comentario Ticket' AND 
                  referencia_id = '$ticket' ORDER BY id_movimiento DESC";
                  try {
                    $queryCom = mysqli_query($conexion, $sqlCom);
                    if(mysqli_num_rows($queryCom) > 0){
                      ?>
                      <table class="centered striped">
                        <thead>
                          <tr>
                            <th>Usuario</th>
                            <th>Comentario</th>
                            <th>Fecha</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                            while($fetchCom = mysqli_fetch_assoc($queryCom)){
                              $usuarioMov = $fetchCom['usuario_movimiento'];
                              $coment = $fetchCom['descripcion_movimiento'];
                              $fechaMov = $fetchCom['fecha_movimiento'];
                              echo "<tr>
                                <td>$usuarioMov</td>
                                <td>$coment</td>
                                <td>$fechaMov</td>
                              </tr>";
                            }//fin del while
                          ?>
                        </tbody>
                      </table>
                      
                      <?php
                    }else{
                      //sin comentarios
                      echo "<div class='row center-align'>
                        <h5 class='center-align'>Sin Comentarios</h5>
                        <img src='img/carpeta-vacia.png' width='100px' style='text-align:center;'>
                      </div>";
                    }
                  } catch (Throwable $th) {
                    //error de comentarios
                    echo "<div class='row center-align'>
                        <h5 class='center-align'>Error de base de datos</h5>
                        <img src='img/carpeta-vacia.png' width='100px' style='text-align:center;'>
                      </div>";
                  }
                  
                ?>
              </div>

            </div>
          </div>
        </div>

        

      </div><!--FIN col principal 12-->
  </div>


    </main>
  <?php
    //require_once('includes/footer.php'); 
  ?>

  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <!-- <script src="js/start.js"></script> -->
  <!-- <script src="js/verManuales.js"></script> -->
  <script src="js/sweetAlert2.min.js"></script>
  <script src="js/verInfoTicket.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
