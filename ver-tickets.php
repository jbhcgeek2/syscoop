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
    
    
  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Tickets Registrados</div>
          <div class="card-content">
            
          <div class="row">
            <p class="center-align">Filtrar tickets por:</p>
            <div class="input-field col m4">
              <select name="estatusTicket" id="estatusTicket" onchange="filtroTicket()">
                <option value="">Seleccione</option>
                <?php 
                  $sqlEst = "SELECT * FROM estatus_ticket WHERE estatusTicketActivo = '1'";
                  $queryExt = mysqli_query($conexion,$sqlEst);
                  while($fetchEst = mysqli_fetch_assoc($queryExt)){
                    $nombreEst = $fetchEst['nombreEstatusTicket'];
                    echo "<option value='$nombreEst'>$nombreEst</option>";
                  }
                ?>
              </select>
              <label for="estatusTicket">Estatus</label>
            </div>
            <div class="input-field col m4">
              <select name="tipoTickets" id="tipoTickets" onchange="filtroTicket()">
                <option value="" selected>Seleccione</option>
                <?php 
                  $sqlTi = "SELECT * FROM tipoTickets WHERE ticketActivo = '1'";
                  $queryTi = mysqli_query($conexion, $sqlTi);
                  while($fetchTi = mysqli_fetch_assoc($queryTi)){
                    $nombreTi = $fetchTi['nombreTicket'];
                    $idTi = $fetchTi['idTipoTicket'];
                    echo "<option value='$idTi'>$nombreTi</option>";
                  }//fin del while
                ?>
              </select>
              <label for="tipoTickets">Tipo Ticket</label>
            </div>
            <div class="input-field col m4">
              <select name="solicitaTicket" id="solicitaTicket" onchange="filtroTicket()">
                <option value="" selected>Solicitante</option>
                <?php 
                  $sqlUT = "SELECT * FROM usuarios a INNER JOIN empleados b ON 
                  a.empleado_id = b.id_empleado WHERE b.activo = '1' AND b.id_empleado > 1";
                  $queryUT = mysqli_query($conexion, $sqlUT);
                  while($fetchUT = mysqli_fetch_assoc($queryUT)){
                    $nombreCorto = $fetchUT['nombre']." ".$fetchUT['paterno']." ".$fetchUT['materno'];
                    // $nombreCorto = substr($nombreCorto,0,25)."...";
                    $idUsuarioX = $fetchUT['id_usuario'];
                    echo "<option value='$idUsuarioX'>$nombreCorto</option>";
                  }//fin del while
                ?>
              </select>
              <label for="solicitaTicket">Solicitante</label>
            </div>
          </div>


          <table>
            <thead>
              <tr>
                <th>Ticket</th>
                <th>Solicitante</th>
                <th>Responsable</th>
                <th>Prioridad</th>
                <th>Estatus</th>
                <th>Fecha Limite</th>
                <th>Ver</th>
              </tr>
            </thead>
            <tbody id="resultFiltro">
              <?php
                //consultamos los tickets
                // echo $idUsuario;
                //si el usuario tiene persmisos de control, mostraremos todos los 
                //tickets, de lo contrario solo mostraremos las solicitudes del ticket
                if($permiso->ver_controles == 1){
                  $sqlT = "SELECT * FROM tickets a INNER JOIN tipoTickets b ON 
                  a.tipo_ticket_id = b.idTipoTicket INNER JOIN estatus_ticket c
                  ON a.estatus_ticket = c.nombreEstatusTicket WHERE a.estatus_ticket 
                  IN ('Abierto','En Proceso','Resuelto') ORDER BY a.fecha_registro_ticket DESC";
                }else{
                  // $sqlT = "SELECT * FROM tickets a INNER JOIN tipoTickets b ON 
                  // a.tipo_ticket_id = b.idTipoTicket INNER JOIN estatus_ticket c
                  // ON a.estatus_ticket = c.nombreEstatusTicket WHERE b.solicitaTicket IN('$idDep','2') 
                  // AND a.estatus_ticket IN ('Abierto','En Proceso','Resuelto') 
                  // ORDER BY a.fecha_registro_ticket DESC";
                  $sqlT = "SELECT * FROM tickets a INNER JOIN tipoTickets b ON 
                  a.tipo_ticket_id = b.idTipoTicket INNER JOIN estatus_ticket c
                  ON a.estatus_ticket = c.nombreEstatusTicket WHERE (b.responsableTicket IN('$idDep','2') 
                  OR a.usuario_registra_ticket_id = '$idUsuario' OR a.usuario_seguimiento_ticket_id = '$idUsuario') AND a.estatus_ticket IN ('Abierto','En Proceso','Resuelto') 
                  ORDER BY a.fecha_registro_ticket DESC";
                }
                try {
                  $queryT = mysqli_query($conexion, $sqlT);
                  if(mysqli_num_rows($queryT) > 0){
                    while($fetchT = mysqli_fetch_assoc($queryT)){
                      $nombreTicket = $fetchT['nombreTicket'];
                      $prioridad = $fetchT['prioridad_ticket'];
                      $ticket = $fetchT['id_ticket'];
                      //consultamos el usuario 
                      $usuarioReg = getUserById($fetchT['usuario_registra_ticket_id']);
                      //verificamos la fecha de creacion para definir la fecha limite
                      $fechaRegistro = $fetchT['fecha_registro_ticket'];
                      $responsable = $fetchT['usuario_seguimiento_ticket_id'];
                      
                      if($responsable != ""){
                        $usuarioRes = getUserById($responsable);
                      }else{
                        $usuarioRes = "Sin Asignar";
                      }
                      // Codigo de colores para estatus
                      // Abierto = Azul - blue darken-1
                      // En Proceso = Amarillo - yellow
                      // En Espera = Naranja - amber darken-3
                      // En espera interna = Gris - blue-grey lighten-2
                      // Resulto = Verde - green darken-1
                      // Cerrado = Gris - grey darken-1
                      // Reabierto Rojo - deep-orange accent-4
                      $colorBarge = $fetchT['colorStatus'];
                      $nombreEsattus = $fetchT['estatus_ticket'];
                      
                      
                      $diasAtencion = $fetchT['diasAtencion'];
                      //hacemos la suma de los dias

                      $fechaInicial = new DateTime($fechaRegistro); // Fecha inicial
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
                      
                      //verificaremos si el usuario es el propietario del ticket para mostrarle
                      //la ocion de entrar a ver la informacion
                      //pero si tiene permisos de control, le habilitaremos 
                      //entrar a ver el ticket
                      if($permiso->ver_controles == 1){
                        $enlace = "<a href='verInfoTicket.php?info=$ticket'>
                          <i class='material-icons green-text'>developer_board</i>
                        </a>";
                      }else{
                        if($usuarioReg == $_SESSION['usNamePlataform']){
                          $enlace = "<a href='verInfoTicket.php?info=$ticket'>
                            <i class='material-icons green-text'>developer_board</i>
                          </a>";
                        }else{
                          if($usuarioRes == $_SESSION['usNamePlataform']){
                            $enlace = "<a href='verInfoTicket.php?info=$ticket'>
                              <i class='material-icons green-text'>developer_board</i>
                            </a>";
                          }else{
                            $enlace = "<a href='#!'>
                              <i class='material-icons red-text'>developer_board</i>
                            </a>";
                          }
                          
                        }
                      }
                      
                      echo "<tr>
                        <td>$nombreTicket</td>
                        <td>$usuarioReg</td>
                        <td>$usuarioRes</td>
                        <td>$prioridad</td>
                        <td class='text-center'><span class='new badge $colorBarge' data-badge-caption='$nombreEsattus'></span></td>
                        <td>$fechaFinalString</td>
                        <td>
                          $enlace
                        </td>
                      </tr>";

                    }//fin del while
                  }else{
                    //sin tickets registrados
                    echo "<tr><td colspan='7' class='center-align'><h5>Sin Tickets Registrados</h5></td></tr>";
                  }
                } catch (\Throwable $th) {
                  //error en la consulta
                  echo "<tr><td colspan='7' class='center-align'><h5>Error de base de datos $th</h5></td></tr>";

                }
              ?>
            </tbody>
          </table>
            
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
  <!-- <script src="js/verManuales.js"></script> -->
  <script src="js/sweetAlert2.min.js"></script>
  <script src="js/verTickets.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
