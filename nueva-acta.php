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
          <div class="titulo">Registrar Acta</div>
          <div class="card-content">
            <div class="row">
              <form id="formNewAct" name="formNewAct" enctype="multipart/form-data">
              
              <div class="input-field col s12 m4">
                <select name="actaConsejo" id="actaConsejo" onchange="getNumerales()">
                  <option value="">Seleccione</option>
                  <?php 
                  $sql1 = "SELECT * FROM consejos";
                  $query1 = mysqli_query($conexion, $sql1);
                  while($fetch1 = mysqli_fetch_assoc($query1)){
                    $nombre = $fetch1['nombre_consejo'];
                    $idCon = $fetch1['id_consejo'];
                    
                    echo "<option value='$idCon'>$nombre</option>";
                  }
                  ?>
                </select>
                <label for="actaConsejo">Consejo</label>
              </div>

              <div class="input-field col s12 m3 l3">
                <select name="tipoActa" id="tipoActa" onchange="getNumerales()">
                  <option value="">Seleccione</option>
                  <option value="Ordinaria">Ordinaria</option>
                  <option value="Extraordinaria">Extraordinaria</option>
                </select>
                <label for="tipoActa">Tipo Acta</label>
              </div>

              <div class="input-field col s12 m4">
                <input type="date" id="actaFecha" name="actaFecha" onchange="getNumerales()">
                <label for="actaFecha">Fecha de acta</label>
              </div>
              <div class="input-field col s6 m2 l1">
                <input type="text" id="numeroActa" name="numeroActa">
                <label for="numeroActa" id="numeroActaLabel">Numero</label>
              </div>
              <div class="input-field col s6 m2" id="comboNumExiste">
                <select name="numeralExistente" id="numeralExistente" readonly>
                  <option value="">Ver...</option>
                </select>
                <label for="numeralExistente">Numerales</label>
              </div>
              <div class="input-field col s6 m2">
                <input type="text" id="numeralActa" name="numeralActa" style="text-transform:uppercase;">
                <label for="numeralActa">Nuevo Numeral</label>
              </div>

              <div class="input-field col s12">
                <input type="text" id="puntoActa1" name="puntoActa1">
                <label for="puntoActa1">Punto a tratar 1</label>
              </div>
              <input type="hidden" name="numControl" id="numControl" value="1">
              <input type="hidden" name="manuAutorizados" id="manuAutorizados" value="|">
              <div id="contenidoPuntos"><div id="contenedor2"></div></div>

              <div class="col s12">
                <div class="col m4 center">
                  <a href="#!" class="btn waves-effect red" id="addDot">Agregar Punto</a>
                </div>
                <div class="col m4"></div>
                <div class="col m4 center">
                  <a href="#!" class="btn waves-effect red" id="delDot">Eliminar Punto</a>
                </div>
              </div>
                

              </form>
            </div><!--FIN row principal del card-->
            <div class="row center-align">
              <a href="#!" class="btn waves waves-effect blue" id="enviaForm">Guardar</a>
            </div>
          </div><!--FIN Cardcontent principal-->
        </div><!--FIN cardStyleContent card principal-->

        <div class="card cardStyleContent">
          <div class="titulo">Documentos pendientes de autorizacion</div>
          <div class="card-content">
            <div class="row">
              <?php 
                $sqlDoc = "SELECT * FROM manuales_formatos WHERE (tipo_doc = 'Manual' OR tipo_doc = 'Cotizacion'
                OR tipo_doc = 'Autorizacion Credito') AND acta_id = '0' ORDER BY
                nombre_man_form ASC";
                
                try {
                  $queryDoc = mysqli_query($conexion, $sqlDoc);  
                  if(mysqli_num_rows($queryDoc) > 0){
                    ?>
                    <table>
                      <thead>
                        <tr>
                          <th>Autorizar</th>
                          <th>Tipo</th>
                          <th>Documento</th>
                          <th>Version</th>
                          <th>Fecha Registro</th>
                          <th>Ver mas</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                      while($fetchDoc = mysqli_fetch_assoc($queryDoc)){
                        $nombreDoc = $fetchDoc['nombre_man_form'];
                        $fechaReg = $fetchDoc['fecha_registro'];
                        $idDoc = $fetchDoc['id_man_form'];
                        $version = $fetchDoc['edicion'];
                        $tipoDoc = $fetchDoc['tipo_doc'];
                        echo "<tr>
                        <td class='center-align'>
                          <p>
                          <label>
                            <input type='checkbox' id='autori|$idDoc' class='filled-in' onchange='updateAutori(this.id);'/>
                            <span></span>
                          </label>
                          </p>
                        </td>
                        <input type='hidden' id='nombreDoc|$idDoc' value='$nombreDoc'>
                        <td>$tipoDoc</td>
                        <td>$nombreDoc</td>
                        <td>$version</td>
                        <td>$fechaReg</td>
                        <td><a href='verInfoDoc.php?docInfo=$idDoc' class='btn waves-effect blue'>Ver</a></td>
                        </tr>";
                      }//fin del while de datos
                      ?>
                      </tbody>
                    </table>
                    <?php
                  }else{
                    //sin pendientes de autorizacion
                  }
                } catch (Throwable $th) {
                  //error al consultar los manuales pendientes
                }
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
  <script src="js/altActa.js"></script>
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
