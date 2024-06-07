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
          <div class="titulo">Agregar Tickets</div>
          <div class="card-content">
            <form id="registraTipoTicket" name="registraTipoTicket">

            
            <div class="row">

              <div class="input-field col s12 m4 l3">
                <input type="text" id="nombreTicket" name="nombreTicket">
                <label for="nombreTicket">Nombre Ticket</label>
              </div>
              <!-- <div class="input-field col s12 m4 l3">
                <select name="tipoSolicitud" id="tipoSolicitud">
                  <option value="" selected disabled>Seleccione</option>
                  <option value="">Observacion Auditoria</option>
                  <option value="">Traslado de documentacion</option>
                  <option value=""></option>
                </select>
                <label for="tipoSolicitud">Tipo de Ticket</label>
              </div> -->
              
              <div class="input-field col s12 m4 l3">
                <select name="solicitaTicket" id="solicitaTicket">
                  <option value="" selected disabled>Seleccione...</option>
                  <?php
                    //consultamos los departamentos existentes,
                    $sqlDep = "SELECT * FROM departamentos WHERE id_departamento > 1";
                    try {
                      $queryDep = mysqli_query($conexion, $sqlDep);
                      while($fetchDep = mysqli_fetch_assoc($queryDep)){
                        $depa = $fetchDep['nombre_departamento'];
                        $idDep = $fetchDep['id_departamento'];
                        echo "<option value='$idDep'>$depa</option>";
                      }//fin del while
                    } catch (\Throwable $th) {
                      //error al consultar la informacion
                      echo "<option value='' disabled>Error de BD</option>";
                    }

                  ?>
                </select>
                <label for="solicitaTicket">Solicitantes</label>
              </div>

              <div class="input-field col s12 m4 l3">
                <select name="resuelveTicket" id="resuelveTicket">
                  <option value="" selected disabled>Seleccione...</option>
                  <?php
                    //consultamos los departamentos existentes,
                    $sqlDep2 = "SELECT * FROM departamentos WHERE id_departamento > 1";
                    try {
                      $queryDep2 = mysqli_query($conexion, $sqlDep2);
                      while($fetchDep2 = mysqli_fetch_assoc($queryDep2)){
                        $depa2 = $fetchDep2['nombre_departamento'];
                        $idDep2 = $fetchDep2['id_departamento'];
                        echo "<option value='$idDep2'>$depa2</option>";
                      }//fin del while
                    } catch (\Throwable $th) {
                      //error al consultar la informacion
                      echo "<option value='' disabled>Error de BD</option>";
                    }

                  ?>
                </select>
                <label for="resuelveTicket">Departamento Responsable</label>
              </div>

              <div class="input-field col s12 m2 l3">
                <input type="number" id="diasAtencion" name="diasAtencion">
                <label for="diasAtencion">Dias de Atencion</label>
                <!-- <select name="prioridad" id="prioridad">
                  <option value="" selected disabled>Seleccione...</option>
                  <option value="Baja">Baja</option>
                  <option value="Media">Media</option>
                  <option value="Alta">Alta</option>
                </select>
                <label for="diasAtencion">Prioridad</label> -->
              </div>

              <div class="col s12 center-align">
                <p style="text-align:center;">Podras registrar los campos que consideres necesarios para configurar tus solicitudes</p>
                <br>
                <a href="#!" id="addCampTicket" class="btn waves waves-effect btnGrenNormal">Agregar campo</a>
                <a href="#!" id="delCampTicket" class="btn waves waves-effect btnRedNormal">Eliminar Campo</a>
                
              </div>
              
              <div class="row">
                <input type="hidden" id="camposAdd" name="camposAdd" value="0">
                <div id="addCamps">
                  
                </div>
              </div>
              

              <div class="row center">
                <a href="#!" id="registrarTipo" class="btn waves waves-effect btnBlueNormal">Registrar</a>
              </div>
            </form>
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
  <script src="js/nuevoTipoTicket.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
