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
      $data = $_GET['dataSet'];
      if(!empty($data) && !is_nan($data)){
        //verificamos la informacion del usuario
        // $sql = "SELECT * FROM usuarios a INNER JOIN empleados b 
        // ON a.empleado_id = b.id_empleado INNER JOIN permisos c ON
        // a.permisos_id = c.id_permiso WHERE a.id_usuario = '$data'";
        
        $sql = "SELECT * FROM empleados a INNER JOIN puestos b 
        ON a.cargo_id = b.id_puesto WHERE a.id_empleado = '$data'";
        try {
          $query = mysqli_query($conexion, $sql);
          $fetch = mysqli_fetch_assoc($query);
          $imgPerfil = "img/imgPerfilDefaut.jpg";
          $nombreCompleto = mb_strtoupper($fetch['nombre'])." ".mb_strtoupper($fetch['paterno'])." ".mb_strtoupper($fetch['materno']);
        //   $usuarioPer = $fetch['nombre_usuario'];
          $correoPer = $fetch['correo'];
          $celular = $fetch['celular'];
          $departamento = $fetch['departamento_id'];
          $puesto = $fetch['cargo_id'];
          $idEmpleadoUser = $fetch['id_empleado'];
          $activo = $fetch['activo'];
          
        } catch (Throwable $th) {
          //error de consulta de datos
          echo "Error al consultar la base de datos";
        }
      }else{
        //sin datos validos
        echo "<script>window.location='index.php'</script>";
      }
  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Datos del empleado</div>
          <div class="card-content">
            <div class="row">

              <input type="hidden" name="usuarioDato" id="usuarioDato" value="<?php echo $data; ?>">
              <div class="col s12">
                <div class="col s12 m4 l3 offset-s1 center">
                  <img src="<?php echo $imgPerfil; ?>" alt="FotodePerfil" width="100%" style="width: 180px;height: 180px;">
                </div>

                <div class="col s12 m8 l9">

                  <div class="input-field col s12 m8">
                    <input type="text" id="nombreEmpleado" value="<?php echo $nombreCompleto; ?>">
                    <label for="nombreEmpleado">Nombre Empleado</label>
                  </div>
                  <div class="input-field col s12 m4">
                    <select name="activo" id="activo" onchange="updateCampo(this.id)">
                      <option value="" disabled>Seleccione</option>
                      <?php 
                        if($activo == "1"){
                          echo '<option value="1" selected disabled>Activo</option>
                          <option value="0">Baja</option>';
                        }else{
                          echo '<option value="1">Activo</option>
                          <option value="0" selected disabled>Baja</option>';
                        }
                      ?>
                      
                    </select>
                    <label for="activo">Estatus</label>
                  </div>
                  
                  <div class="input-field col s12 m6">
                    <input type="text" id="mailUser" value="<?php echo $correoPer; ?>">
                    <label for="mailUser">Correo</label>
                  </div>
                  <div class="input-field col s12 m3">
                    <input type="text" id="celUser" value="<?php echo $celular; ?>" onchange="updateCampo(this.id)">
                    <label for="celUser">Celular</label>
                  </div>
                  <div class="input-field col s12 m6">
                    <select name="" id="departamento" onchange="updateCampo(this.id)">
                      <?php
                        //consultamos los departamentos dados de alta
                        $sqlDep = "SELECT * FROM departamentos WHERE id_departamento > 1 ORDER BY nombre_departamento ASC";
                        try {
                          $queryDep = mysqli_query($conexion, $sqlDep);
                          while($fetchDep = mysqli_fetch_assoc($queryDep)){
                            $nombreDep = $fetchDep['nombre_departamento'];
                            $idDepa = $fetchDep['id_departamento'];
                            if($idDepa == $departamento){
                              echo "<option value='$idDepa' selected disabled>$nombreDep</option>";
                            }else{
                              echo "<option value='$idDepa'>$nombreDep</option>";
                            }
                          }//fin del while
                        } catch (\Throwable $th) {
                          //error en la consulta de departamentos
                          echo "<option value = '' selected disabled>Error de consulta</option>";
                        }

                      ?>
                    </select>
                    <label for="departamento">Departamento</label>
                  </div>
                  <div class="input-field col s12 m6">
                    <select name="puesto" id="puesto"  onchange="updateCampo(this.id)">
                      <option value="" selected>Seleccione...</option>
                      <?php
                        //consultamos el puesto del vato 
                        $sqlCargo = "SELECT * FROM puestos WHERE puesto_activo = '1'";
                        try {
                          $queryCargo = mysqli_query($conexion, $sqlCargo);
                          while($fetchcargo = mysqli_fetch_assoc($queryCargo)){
                            $nombrePuesto = $fetchcargo['nombre_puesto'];
                            $idPuesto = $fetchcargo['id_puesto'];
                            if($idPuesto == $puesto){
                              echo "<option value='$idPuesto' selected disabled>$nombrePuesto</option>";
                            }else{
                              echo "<option value='$idPuesto'>$nombrePuesto</option>";
                            }
                          }//fin del while
                        } catch (Throwable $th) {
                          echo "<option value='' selected>Error de consulta</option>";
                        }
                      ?>
                    </select>
                    <label for="puesto">Cargo</label>
                  </div>
                  

                </div>


                
              </div><!--Fin primera seccion del usuario-->

              


            </div><!--FIN row principal del card-->

            
          </div><!--FIN Cardcontent principal-->
        </div><!--FIN cardStyleContent card principal-->

        <div class="card cardStyleContent">
          <div class="titulo">Usuarios Asignados</div>
          <div class="card-content">
            <div class="row">
                <div class="col s12 m12 l12">
                    <table class="striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Estatus</th>
                                <th>Fecha Creacion</th>
                                <th>Ver</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            //consultamos los usuarios que tenga activos
                            $sqlU = "SELECT * FROM usuarios WHERE empleado_id = '$idEmpleadoUser'";
                            $queryU = mysqli_query($conexion, $sqlU);
                            if(mysqli_num_rows($queryU) > 0){
                                while($fetchU = mysqli_fetch_assoc($queryU)){
                                    $nombreUsuario = $fetchU['nombre_usuario'];
                                    $fechaCreacion = $fetchU['fecha_creacion'];
                                    $estatusU = $fetchU['usuario_activo'];
                                    $idUserU = $fetchU['id_usuario'];

                                    if($estatusU == "1"){
                                        $estatusUser = "Activo";
                                    }else{
                                        $estatusUser = "Baja";
                                    }

                                    echo "<tr>
                                        <td>$nombreUsuario</td>
                                        <td>$estatusUser</td>
                                        <td>$fechaCreacion</td>
                                        <td>
                                            <a href='verInfoUsuario.php?dataSet=$idUserU' class='btn waves-effect btnGrenNormal'>Ver</a>
                                        </td>
                                    </tr>";
                                }//fion del while
                            }else{
                                //sin usuarios asignados
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
                
            </div>
          </div>
        </div>

        <div class="card cardStyleContent">
          <div class="titulo">Tickets Solicitados</div>
          <div class="card-content">
          <div class="row" style="overflow-x: hidden;overflow-y: scroll; max-height:370px;">
              <table class="centered">
                <thead>
                  <tr>
                    <th>Fecha</th>
                    <th>Nombre Ticket</th>
                    <th>Usuario Encargado</th>
                    <th>Estatus</th>
                    <th>Fecha Solucion</th>
                    <th>Ver Ticket</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $sqlTick = "SELECT *,(SELECT d.nombre_usuario FROM usuarios d WHERE
                    d.id_usuario = a.usuario_seguimiento_ticket_id) AS usarioResuelve FROM 
                    tickets a INNER JOIN tipoTickets b ON a.tipo_ticket_id = b.idTipoTicket 
                    INNER JOIN estatus_ticket c ON c.nombreEstatusTicket = a.estatus_ticket 
                    WHERE a.usuario_registra_ticket_id = '$data' OR 
                    a.usuario_seguimiento_ticket_id = '$data'";
                    try {
                      $queryTick = mysqli_query($conexion, $sqlTick);
                      if(mysqli_num_rows($queryTick) > 0){
                        while($fetchTick = mysqli_fetch_assoc($queryTick)){
                          $fechaTick = $fetchTick['fecha_registro_ticket'];
                          $estatus = $fetchTick['estatus_ticket'];
                          if(!empty($fetchTick['usarioResuelve'])){
                            $usuarioRes = $fetchTick['usarioResuelve'];  
                          }else{
                            $usuarioRes = "Pendiente";
                          }
                          $nombreTick = $fetchTick['nombreTicket'];
                          // $fechaTermino = $fetchTick['fecha_termino_ticket'];
                          if(!empty($fetchTick['fecha_termino_ticket'])){
                            $fechaTermino = $fetchTick['fecha_termino_ticket'];
                          }else{
                            $fechaTermino = "Pendiente";
                          }
                          $colorBarge = $fetchTick['colorStatus'];
                          $ticket = $fetchTick['id_ticket'];
                          
                          echo "<tr>
                            <td>$fechaTick</td>
                            <td>$nombreTick</td>
                            <td>$usuarioRes</td>
                            <td class='text-center'><span class='new badge $colorBarge' data-badge-caption='$estatus'></span></td>
                            <td>$fechaTermino</td>
                            <td>
                              <a href='verInfoTicket.php?info=$ticket'>
                                <i class='material-icons green-text'>developer_board</i>
                              </a>
                            </td>
                          </tr>";
                        }//fin del while tiuckets
                      }else{
                        //sin tickets registrados
                        echo "
                        <tr><td colspan='6'><h5>Sin Datos</h5></td></tr>
                        <tr>
                          <td colspan='6'><img src='../img/sinDatos.png' width='120'></td>
                        </tr>";
                      }
                    } catch (\Throwable $th) {
                      //error de consulta de tickets
                      echo "
                        <tr><td colspan='6'><h5>Error de Base de datos</h5></td></tr>
                        <tr>
                          <td colspan='6'><img src='../img/sinDatos.png' width='120'></td>
                        </tr>";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="card cardStyleContent">
          <div class="titulo">Mis Resguardos</div>
          <div class="card-content">
            <div class="row" style="overflow-x: hidden;overflow-y: scroll; max-height:370px;">
              <!-- Tabla de ultimos movimientos -->
              <table class="centered">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Area Reguardo</th>
                    <th>Clasificacion</th>
                    <th>Codigo</th>
                    <th>Ver</th>
                  </tr>
                </thead>
                <tbody >
                  <?php
                    $sqlInv = "SELECT * FROM inventario WHERE resguardo_empleado = '$idEmpleadoUser' 
                    AND articulo_activo = '2'"; 
                    try {
                      $queryInv = mysqli_query($conexion, $sqlInv);
                      if(mysqli_num_rows($queryInv) > 0){
                        while($fetchInv = mysqli_fetch_assoc($queryInv)){
                          $nombreObj = $fetchInv['nombre_objeto'];
                          $areaObj = $fetchInv['lugar_resguardo'];
                          $clasificacion = $fetchInv['clasificacion'];
                          $codigo = $fetchInv['codigo'];
                          $objId = $fetchInv['factura_objeto'];



                          echo "<tr>
                            <td>$nombreObj</td>
                            <td>$areaObj</td>
                            <td>$clasificacion</td>
                            <td>$codigo</td>
                            <td>
                              <a href='ver-objeto.php?objId=$objId'>
                                <i class='material-icons green-text'>assignment</i>
                              </a>
                            </td>
                          </tr>";

                        }//fin del while de movimientos
                      }else{
                        //sin movimientos del usuario
                        echo "
                        <tr><td colspan='4'><h5>Sin Datos</h5></td></tr>
                        <tr>
                          <td colspan='4'><img src='../img/sinDatos.png' width='120'></td>
                        </tr>";
                      }
                      
                    } catch (\Throwable $th) {
                      //throw $th;
                    }
                  ?>
                </tbody>
              </table>
              <?php 
              ?>

            </div>
          </div>
        </div>

        <div class="card cardStyleContent">
          <div class="titulo">Ultimas interacciones</div>
          <div class="card-content">
            <div class="row" style="overflow-x: hidden;overflow-y: scroll; max-height:370px;">
              <!-- Tabla de ultimos movimientos -->
              <table class="centered">
                <thead>
                  <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Movimiento</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $sqlMovs = "SELECT * FROM movimientos WHERE usuario_movimiento = '$usuarioPer'"; 
                    try {
                      $queryMovs = mysqli_query($conexion, $sqlMovs);
                      if(mysqli_num_rows($queryMovs) > 0){
                        while($fetchMovs = mysqli_fetch_assoc($queryMovs)){
                          $fechaMov = $fetchMovs['fecha_movimiento'];
                          $descripMov = $fetchMovs['descripcion_movimiento'];
                          $tipoMov = $fetchMovs['tipo_movimiento'];
                          echo "<tr>
                            <td>$fechaMov</td>
                            <td>$tipoMov</td>
                            <td>$descripMov</td>
                          </tr>";

                        }//fin del while de movimientos
                      }else{
                        //sin movimientos del usuario
                        echo "
                        <tr><td colspan='3'><h5>Sin Datos</h5></td></tr>
                        <tr>
                          <td colspan='3'><img src='../img/sinDatos.png' width='150'></td>
                        </tr>";
                      }
                      
                    } catch (\Throwable $th) {
                      //throw $th;
                    }
                  ?>
                </tbody>
              </table>
              <?php 
              ?>

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
  <script src="js/sweetAlert2.min.js"></script>
  <script src="js/verInfoUsuario.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
