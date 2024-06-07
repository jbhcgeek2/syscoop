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
          <div class="titulo">Control de Manuales</div>
          <div class="card-content">
            <div class="row">
              
              <div class="col s12">
                <div class="input-field col s12 m4 l3">
                  <select name="" id="tipoDocFiltro">
                    <option value="">Seleccione...</option>
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
                  <label for="tipoDocFiltro">Tipo Documento</label>
                </div>
                <div class="input-field col s12 m8 l7">
                  <input type="text" id="buscaManual">
                  <label for="buscaManual">Buscar Manual / Formato</label>
                </div>
              </div>
             
              

              <div class="col s12 m12" style="overflow-y: scroll;height: 450px;">
                <?php 
                  //consultamos la documentacion
                  if($permiso->ver_manuales == 1){
                    //si tiene activado ver manuales podra ver todos los manuales de todas las areas
                    $sqlDoc = "SELECT * FROM manuales_formatos a INNER JOIN puestos b ON 
                    a.puesto_id = b.id_Puesto WHERE tipo_doc = 'Manual' ORDER BY a.nombre_man_form ASC";
                  }else{
                    $sqlDoc = "SELECT * FROM manuales_formatos a INNER JOIN puestos b ON 
                    a.puesto_id = b.id_Puesto WHERE tipo_doc = 'Manual' AND 
                    a.departamento_doc_id = '$idDep'  ORDER BY a.nombre_man_form ASC";
                  }
                  
                  
                  $queryDoc = mysqli_query($conexion, $sqlDoc);
                  if(mysqli_num_rows($queryDoc) > 0){
                    ?>
                      <table>
                        <thead>
                          <tr>
                            <th class="truncate">Nombre</th>
                            <th>Version</th>
                            <th class="center-align">Tipo Documento</th>
                            <th class="center-align">Fecha Actualizacion</th>
                            <th class="center-align">Responsable</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody id="resTablaDocs">
                          <?php 
                            while($fetchDoc = mysqli_fetch_assoc($queryDoc)){
                              $nombreDoc = $fetchDoc['nombre_man_form'];
                              $version = $fetchDoc['edicion'];
                              
                              $fechaMod = "";
                              if($fetchDoc['fecha_ultima_mod'] == "0000-00-00" || $fetchDoc['fecha_ultima_mod'] == "1900-01-01"){
                                $fechaMod = $fetchDoc['fecha_publicacion'];
                              }else{
                                $fechaMod = $fetchDoc['fecha_ultima_mod'];
                              }
                              $puestoEncargado = $fetchDoc['nombre_puesto'];
                              $tipoDoc = $fetchDoc['tipo_doc'];
                              
                              if($fetchDoc['tipo_doc'] == "Anexo"){
                                $idDoc = $fetchDoc['departamento_doc_id'];
                              }else{
                                $idDoc = $fetchDoc['id_man_form'];
                              }
                              // $nombreDoc = strtolower($nombreManu);
                              $nombreDoc = ucwords($nombreDoc);
                              
                              echo "<tr>
                                <td class='truncate'>$nombreDoc</td>
                                <td class='center-align'>$version</td>
                                <td class='center-align'>$tipoDoc</td>
                                <td class='center-align'>$fechaMod</td>
                                <td class='center-align'>$puestoEncargado</td>
                                <td>
                                  <a href='verInfoDoc.php?docInfo=$idDoc'>
                                    <i class='medium material-icons red-text'>find_in_page</i>
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
            <?php
              if($permiso->agregar_manuales == 1){
                ?>
                  <div class="row">
                    <div class="col s12 center-align">
                      <a href="nuevo-documento.php" class="btn waves waves-effect btnBlueNormal">Nuevo Documento</a>
                    </div>
                  </div>
                <?php
              }
            ?>
            
            
          </div><!--FIN Cardcontent principal-->
        </div><!--FIN cardStyleContent card principal-->

        <div class="card cardStyleContent">
          <div class="titulo">Manuales Proximos a actualizar</div>
          <div class="card-content">
            <?php 
              $dateActual = date('Y-m-d');
              //los manuales deben actualizarse al menos 1 vez al anio
              //por lo que buscaremos aquellos que tengan un margen de 3 meses
              //de vencimiento
              $fechaMargen = date('Y-m-d', strtotime($dateActual."- 9 month"));
              if($permiso->ver_manuales == 1){
                $sqlVenci = "SELECT * FROM manuales_formatos a INNER JOIN puestos b 
                ON a.puesto_id = b.id_puesto WHERE a.departamento_doc_id = '$idDep' 
                AND a.tipo_doc = 'Manual' ORDER BY fecha_ultima_mod DESC";
              }else{
                $sqlVenci = "SELECT * FROM manuales_formatos a INNER JOIN puestos b 
                ON a.puesto_id = b.id_puesto WHERE a.departamento_doc_id = '$idDep' 
                AND a.tipo_doc = 'Manual' AND a.fecha_ultima_mod <= '$fechaMargen' 
                ORDER BY fecha_ultima_mod DESC";
              }
              
              
              try {
                $queryVenci = mysqli_query($conexion, $sqlVenci);
                if(mysqli_num_rows($queryVenci) > 0){
                  echo "
                  <table><thead>
                    <tr>
                      <th>Nombre</th>
                      <th>Ultima Actualizacion</th>
                      <th>Meses Transcurridos</th>
                      <th>Responsable</th>
                      <th>Ver</th>
                    </tr>
                  </thead><tbody>
                  ";
                  while($fetchVenci = mysqli_fetch_assoc($queryVenci)){
                    $nombreManu = $fetchVenci['nombre_man_form'];
                    $ultimaActu = $fetchVenci['fecha_ultima_mod'];
                    $puestoEncar = $fetchVenci['nombre_puesto'];
                    $auxFec1 = new DateTime($dateActual);
                    $auxFec2 = new DateTime($ultimaActu);
                    $diff = $auxFec1->diff($auxFec2);
                    $meses = $diff->m;
                    $idDocVen = $fetchVenci['id_man_form'];
                    echo "<tr>
                      <td>$nombreManu</td>
                      <td>$ultimaActu</td>
                      <td>$meses Meses</td>
                      <td>$puestoEncar</td>
                      <td>
                        <a href='verInfoDoc.php?docInfo=$idDocVen'>
                          <i class='medium material-icons red-text'>find_in_page</i>
                        </a>
                      </td>
                    </tr>";
                  }//fin while vencimientos
                  echo "</tbody></table>";
                }else{
                  //todos los manuales actualizados
                  echo "<div class='row center-align'>
                    <h5 class='center-align'><strong>Sin vencimientos</strong></h5>
                    <p>Todos los manuales se encuentran actualizados.</p>
                    <img src='img/verificado.png' width='100px'>
                  </div>";
                }
              } catch (Throwable $th) {
                echo "<h5 class='center-align'>Error en la base de datos</h5>";
              }
              
            ?>
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
  <script src="js/verManuales.js"></script>
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
