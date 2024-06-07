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
    include('includes/operations/encrp.php');
    if(!empty($_SESSION['usNamePlataform'])){
    //verificamos el tipo de usuario

  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Generar Solicitud</div>
          <div class="card-content">
            <div class="row">
              <form action="" id="formDataTicket">
                <div class="input-field col s12 m4">
                  <select name="tipoSolicitud" id="tipoSolicitud">
                    <option value="" selected disabled>Seleccione...</option>
                    <?php
                      //consultamos los tipos de solicitudes que podemos hacer
                      //unicamente mostraremos aquellas que correspondan al perfil del usuario

                      $idDepartamento = $datosPerNav->departamento_id;

                      $sqlT = "SELECT * FROM tipoTickets WHERE solicitaTicket IN('$idDepartamento','2')";
                      try {
                        $queryT = mysqli_query($conexion, $sqlT);
                        //mostramos si tiene para solicitar
                        if(mysqli_num_rows($queryT) > 0){
                          while($fetchT = mysqli_fetch_assoc($queryT)){
                            //mostramos las solicitudes
                            $nombreSol = $fetchT['nombreTicket'];
                            $idSol = $fetchT['idTipoTicket'];

                            echo "<option value='$idSol'>$nombreSol</option>";
                          }//fin del while
                        }else{
                          //sin solicitudes habilitadas para realizar
                          echo "<option value='' disabled>Sin Solicitudes disponibles</option>";
                        }
                        
                      } catch (\Throwable $th) {
                        //error al consultar la informacion
                      }

                    ?>
                  </select>
                  <label for="tipoSolicitud">Tipo solicitud</label>
                </div>

                <div class="input-field col s12 m4">
                  <select name="prioridad" id="prioridad">
                    <option value="" selected disabled>Seleccione</option>
                    <option value="Alta">Alta</option>
                    <option value="Media">Media</option>
                    <option value="Baja">Baja</option>
                  </select>
                  <label for="">Nidel de atencion</label>
                </div>

                <div class="input-field col s12 m4">
                  <select name="asignado" id="asignado">
                    <option value="" selected disabled>Seleccione...</option>
                    <?php
                      ///se muestra cuando se indica un tipo de solicitud

                      // $sql = "SELECT * FROM empleados WHERE activo = '1'";
                      // try {
                      //   $query = mysqli_query($conexion, $sql);
                      //   while($fetch = mysqli_fetch_assoc($query)){
                      //     $nombre = $fetch['paterno']." ".$fetch['materno']." ".$fetch['nombre'];
                      //     $id = $fetch['id_empleado'];
                      //     echo "<option value='$id'>$nombre</option>";
                      //   }//fin del while
                      // } catch (Throwable $th) {
                      //   //error de consulta

                      // }

                    ?>
                  </select>
                  <label for="asignado">Asignar a</label>
                </div>
                
              

                <div class="row" id="resCamposAdd">

                </div>

                
                  <div class="input-field col s12">
                    <textarea name="descripcionTicket" id="descripcionTicket" class="materialize-textarea" cols="30" rows="10"></textarea>
                    <label for="descripcionTicket">Descripcion</label>
                  </div>
                

                <div class="row">
                  <div class="col s12 m6 center-align">
                    <a href="#!" class="btn waves waves-effect btnRedNormal">Salir</a>
                  </div>
                  <div class="col s12 m6 center-align">
                    <a href="#!" class="btn waves waves-effect btnBlueNormal" id="saveTicket">Guardar</a>
                  </div>
                  
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
  <script src="js/nuevoTicket.js"></script>
  <script src="js/sweetAlert2.min.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
