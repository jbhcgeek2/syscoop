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
          <div class="titulo">Registrar Documento</div>
          <div class="card-content">
            <div class="row">
              <form id="formNewDoc" name="formNewDoc" enctype="multipart/form-data">

                <div class="input-field col s12 m3 l3" id="ConttipoDoc">
                  <select name="tipoDoc" id="tipoDoc">
                    <option value="" selected disabled>Seleccione</option>
                    <option value="Manual">Manual</option>
                    <option value="Anexo">Anexo Manual</option>
                    <option value="Formato">Formato</option>
                    <option value="Informe">Informe</option>
                    <option value="Presentacion">Presentacion</option>
                    <option value="Autorizacion Credito">Autorizacion Credito</option>
                    <option value="Cotizacion">Cotizacion</option>
                    <option value="Anexo Acta">Anexo Acta</option>
                  </select>
                  <label for="tipoDoc">Tipo Documento</label>
                </div>
                <div class="input-field col s12 m6 l3" id="ContnombreDoc">
                  <input type="text" id="nombreDoc" name="nombreDoc">
                  <label for="nombreDoc">Nombre Documento</label>
                </div>
                <div class="input-field col s6 m3" id="ContversionDoc">
                  <input type="text" id="versionDoc" name="versionDoc">
                  <label for="versionDoc">Version</label>
                </div>
                <div class="input-field col s12 m4 l3" id="ContcodiDoc">
                  <input type="text" id="codiDoc" name="codiDoc">
                  <label for="codiDoc">Codificacion</label>
                </div>
                <div id="auxResult1"></div>
                <div class="input-field col s6 m4" id="ContdepartamentoDoc">
                  <select name="departamentoDoc" id="departamentoDoc">
                    <option value="" selected disabled>Seleccione</option>
                    <?php 
                      //consultamos los departamentos
                      $sqlDep = "SELECT * FROM departamentos ORDER BY nombre_departamento";
                      $queryDep = mysqli_query($conexion, $sqlDep)or die("Error al consultar los departamentod");
                      if(mysqli_num_rows($queryDep) > 0){
                        while($fetchDep = mysqli_fetch_assoc($queryDep)){
                          $nombreDep = strtolower($fetchDep['nombre_departamento']);
                          $nombreDep = ucwords($nombreDep);

                          $idDep = $fetchDep['id_departamento'];
                          echo "<option value='$idDep'>$nombreDep</option>";
                        }//fin del while
                      }else{
                        // sin departamentos
                        echo "<option value='' disabled>SIN REGISTROS</option>";
                      }

                    ?>
                  </select>
                  <label for="departamentoDoc">Departamento</label>
                </div>
                <div class="input-field col s6 m4" id="ContpuestoEncargado">
                  <select name="puestoEncargado" id="puestoEncargado">
                    <option value="" selected disabled>Seleccione</option>
                    <?php 
                      $sqlPuesto = "SELECT * FROM puestos WHERE puesto_activo = '1' ORDER BY nombre_puesto ASC";
                      $queryPuesto = mysqli_query($conexion, $sqlPuesto)or die("Error al consultar los puestos");
                      if(mysqli_num_rows($queryPuesto) > 0){
                        while($fetchPuesto = mysqli_fetch_assoc($queryPuesto)){
                          $nombrePuesto = strtolower($fetchPuesto['nombre_puesto']);
                          $nombrePuesto = ucwords($nombrePuesto);
                          $idPuesto = $fetchPuesto['id_puesto'];
                          echo "<option value='$idPuesto'>$nombrePuesto</option>";
                        }
                      }else{
                        echo "<option value='' disabled>SIN CARGOS</option>";
                      }
                    ?>
                  </select>
                  <label for="puestoEncargado">Puesto Encargado</label>
                </div>
                <div class="input-field col s12 m6 l4" id="ContautorizadoPor">
                  <select name="autorizadoPor" id="autorizadoPor">
                    <option value="">Seleccione...</option>
                    <?php 
                      $sqlConse = "SELECT * FROM consejos";
                      $queryConse = mysqli_query($conexion, $sqlConse);
                      while($fethConse = mysqli_fetch_assoc($queryConse)){
                        $idConse = $fethConse['id_consejo'];
                        $nombreConse = strtolower($fethConse['nombre_consejo']);
                        $nombreConse = ucwords($nombreConse);

                        echo "<option value='$idConse'>$nombreConse</option>";
                      }//fin while consejos
                    ?>
                  </select>
                  <label for="autorizadoPor">Autorizado por</label>
                </div>
                <div class="input-field col s12 m6 l4" id="ContactaNumAutoriza">
                  <select name="actaNumAutoriza" id="actaNumAutoriza">
                    <option value="">Seleccione</option>
                  </select>
                  <label for="actaNumAutoriza">Numero Acta</label>
                </div>
                <div class="input-field col s6 m6 l4" id="ContfechaPublicacion">
                  <input type="date" id="fechaPublicacion" name="fechaPublicacion">
                  <label for="fechaPublicacion">Fecha Publicacion</label>
                </div>
                <div class="input-field col s6 m6 l4" id="ContfechaActualizacion">
                  <input type="date" id="fechaActualizacion" name="fechaActualizacion">
                  <label for="fechaActualizacion">Fecha Actualizacion</label>
                </div>

                <div class="file-field input-field col s12" id="ContdocLectura">
                  <div class="btn red">
                    <span>Documento PDF</span>
                    <input type="file" name="docLectura" id="docLectura" accept=".pdf">
                  </div>
                  <div class="file-path-wrapper">
                    <input type="text" class="file-path validate" id="labelFileLectura">
                  </div>
                </div>
                <div class="file-field input-field col s12" id="ContdocEditable">
                  <div class="btn red">
                    <span>Documento WORD / TXT</span>
                    <input type="file" name="docEditable" id="docEditable" accept=".txt,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                  </div>
                  <div class="file-path-wrapper">
                    <input type="text" class="file-path validate" id="labelFileEditable">
                  </div>
                </div>

              </form>
            </div><!--FIN row principal del card-->
            <div class="row center-align">
              <a href="#!" class="btn waves waves-effect btnBlueNormal" id="enviaForm">Guardar Documento</a>
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
  <script src="js/altaDocumento.js"></script>
  <script>
    var elemSel = document.querySelectorAll('select');
    var instanceSelect = M.FormSelect.init(elemSel, options);

    var elemDate = document.querySelectorAll('.datepicker');
    var instanceDate = M.Datepicker.init(elemDate, {format:'yyyy-mm-dd'});
  </script>
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>

