<!DOCTYPE html>
<html lang="en" class="colorFondo">
  <?php
    //session_start();
    include('includes/head2.php');
    include('includes/_con.php');
    include('includes/navBar.php');
    include('../includes/operations/usuarios.php');
    //include('includes/operations/encrp.php');
    if(!empty($_SESSION['usNamePlataform'])){
    //verificamos el tipo de usuario
    $idActa = $_GET['actaNum'];
    if($idActa != ""){
      //verificamos que exista el documento

      $sqlActa = "SELECT * FROM actas a INNER JOIN consejos b ON
      a.consejo_id = b.id_consejo WHERE a.id_acta = '$idActa'";
      $queryAct = mysqli_query($conexion, $sqlActa);
      
      if(mysqli_num_rows($queryAct) > 0){
        $fetchAct = mysqli_fetch_assoc($queryAct);
        $consejo = $fetchAct['nombre_consejo'];
        $acuerdos = $fetchAct['puntos_actas'];
        $tipoActa = $fetchAct['tipo_acta'];
        $actaNumeral = $fetchAct['acta_num'].strtoupper($fetchAct['numeral']);
        $fechaActa = $fetchAct['fecha_acta'];
        $fechaRegistro = $fetchAct['fecha_registro_acta'];
        $usuarioReg = $fetchAct['usuario_registro_acta'];
        $usuarioReg = getUserById($usuarioReg);

        

        $puntos = "";
        $puntos2 = "";
        $auxPuntos = explode("_|_",$acuerdos);
        $auxN = 1;
        for($ix = 0; $ix < count($auxPuntos); $ix++){
          $acuerdo = $auxPuntos[$ix];
          $puntos .= '<div class="input-field col s12">
            <textarea name="acuerdo'.$auxN.'" id="acuerdo'.$auxN.'" class="materialize-textarea" readonly>'.$acuerdo.'</textarea>
            <label for="acuerdo'.$auxN.'">Acuerdo '.$auxN.'</label>
          </div>';
          $puntos2 .= "<div id='acuerdoContent$auxN'><div class='input-field col s10'>
            <input type='text' name='acuerdoEdit$auxN' id='acuerdoEdit$auxN' value='$acuerdo'>
            <label for='acuerdoEdit$auxN'>Acuerdo</label>
          </div>
          <div class='col s2'>
            <a href='#!' onclick='deleteAcuerdo($auxN)'>
              <i class='material-icons red-text'>delete</i>
            </a>
          </div></div>";
          $auxN++;
        }//fin for acuerdos

      }else{
        header("Status: 301 Moved Permanently");
        header("Location:ver-actas.php");
        echo"<script language='javascript'>window.location='ver-actas.php'</script>;";
      exit();
      }
    }else{
      header("Status: 301 Moved Permanently");
      header("Location:ver-actas.php");
      echo"<script language='javascript'>window.location='ver-actas.php'</script>;";
      exit();
    }
   ?>
<body>
<main>  
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Informacion de Acta</div>
          <div class="card-content">
            <div class="row">
          
              <div class="input-field col s12 m6 l4">
                <input type="text" id="nombreConsejo" value="<?php echo $consejo; ?>" readonly>
                <label for="nombreConsejo">Consejo</label>
              </div>
              
              <input type="hidden" name="actaInfo" id="actaInfo" value="<?php echo $idActa; ?>">
              <div class="input-field col s12 m3 l2">
                <input type="text" id="tipoActa" name="tipoActa" value="<?php echo $tipoActa; ?>" readonly>
                <label for="tipoActa">Tipo de Acta</label>
              </div>

              <div class="input-field col s12 m4 l2">
                <input type="text" id="actaNum" name="actaNum" value="<?php echo $actaNumeral; ?>" readonly>
                <label for="actaNum">Acta Numero</label>
              </div>

              <div class="input-field col s12 m4 l4">
                <input type="date" id="fechaActa" name="fechaActa" value="<?php echo $fechaActa; ?>" readonly>
                <label for="fechaActa">Fecha de sesion</label>
              </div>

              <div class="input-field col s12 m4 l3">
                <input type="date" id="fechaRegistro" name="fechaRegistro" value="<?php echo $fechaRegistro; ?>" readonly>
                <label for="fechaRegistro">Fecha de alta</label>
              </div>

              <div class="input-field col s12 m4 l3">
                <input type="text" id="usuarioRegistro" name="usuarioRegistro" value="<?php echo $usuarioReg; ?>" readonly>
                <label for="usuarioRegistro">Usuario Registro</label>
              </div>

              <div class="col s12 m6 center">
                <?php 
                $nombreLectura = "";
                $nombreEditable = "";
                $nombreBtnWord = "Subir";
                $nombreBtnPDF = "Subir";
                if($fetchAct['acta_editable'] != ""){
                  $sqlLec = "SELECT * FROM documentos WHERE id_documento = '".$fetchAct['acta_editable']."'";
                  $queryLec = mysqli_query($conexion, $sqlLec);
                  $fetchLec = mysqli_fetch_assoc($queryLec);
                  $nombreBtnWord = "Actualizar";
                  $nombreEditable = $fetchLec['nombre_documento'];
                  
                  echo '<div class="col s6">
                  <a href="'.$fetchLec['ruta_documento'].'" target="_blank" class="btn waves-effect btnGrenNormal noDocument">
                  <i class="material-icons">text_snippet</i>
                  Descargar WORD</a>
                  </div>';
                }else{
                  echo '<div class="col s6">
                  <a href="#!" class="btn waves-effect btnAmberNormal" onclick="noDoc();">Descargar WORD</a>
                  </div>';
                }

                if($fetchAct['acta_lectura'] != ""){
                  $sqlEdit = "SELECT * FROM documentos WHERE id_documento = '".$fetchAct['acta_lectura']."'";
                  $queryEdit = mysqli_query($conexion, $sqlEdit);
                  $fetchEdit = mysqli_fetch_assoc($queryEdit);
                  $nombreBtnPDF = "Actualizar";
                  $nombreLectura = $fetchEdit['nombre_documento'];

                  echo '<div class="col s6">
                  <a href="'.$fetchEdit['ruta_documento'].'" target="_blank" class="btn waves-effect btnGrenNormal">
                  <i class="material-icons">picture_as_pdf</i>
                  Descargar PDF</a>
                  </div>';
                }else{
                  echo '<div class="col s6">
                  <a href="#!" class="btn waves-effect btnAmberNormal" onclick="noDoc();">Descargar PDF</a>
                  </div>';
                }
                ?>
                
              </div>

              <div class="col s12">
                <div class="file-field input-field col s12 m12 l6">
                  <div class="btn red">
                    <span><?php echo $nombreBtnWord; ?> Acta WORD</span>
                    <input type="file" id="actaEditable" name="actaEditable" onchange="updateDocActa(this.id)">
                  </div>
                  <div class="file-path-wrapper">
                    <input type="text" class="file-path validate" value="<?php echo $nombreEditable; ?>">
                  </div>
                </div>

                <div class="file-field input-field col s12 m12 l6">
                  <div class="btn red">
                    <span><?php echo $nombreBtnPDF; ?> Acta PDF</span>
                    <input type="file" id="actaLectura" name="actaLectura" onchange="updateDocActa(this.id)">
                  </div>
                  <div class="file-path-wrapper">
                    <input type="text" class="file-path validate" value="<?php echo $nombreLectura; ?>">
                  </div>
                </div>
              </div>

            </div><!--FIN row principal del card-->

          </div><!--FIN Cardcontent principal-->
        </div><!--FIN cardStyleContent card principal-->

        

        <div class="modal" id="modalAcuerdos">
          <div class="modal-content">
            <div class="row">
              <h5 class="center-align">Editar acuerdos</h5>

              <?php echo $puntos2; ?>
              <div id="resNewAcue"></div>
            </div>
          </div>
          <div class="modal-footer">
            <a href="#!" class="btn waves waves-effect modal-close btnRedNormal">Cancelar</a>
            <a href="#!" class="btn waves waves-effect btnBlueNormal" id="AddAcu">Agregar Acuerdo</a>
            <a href="#!" class="btn waves waves-effect btnGrenNormal" id="updateAcuerdos">Guardar</a>
          </div>
        </div>

        <div class="card cardStyleContent">
          <div class="titulo">Acuerdos de Sesion</div>

          <div class="card-content">
            <div class="row">
              <?php echo $puntos; ?>
              <input type='hidden' id='nAcuEdit' value='<?php echo $auxN; ?>'>
            </div>
            <div class="row">
              <div class="col s12 m6 offset-m3 center">
                <a href="#!" class="btn modal-trigger amberButon" data-target="modalAcuerdos">
                  Editar acuerdos
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="card cardStyleContent">
          <div class="titulo">Anexos del acta</div>

          <div class="card-content">
            <div class="row">
              <?php
                $sql2 = "SELECT * FROM manuales_formatos a INNER JOIN puestos b ON 
                a.puesto_id = b.id_puesto WHERE acta_id = '$idActa'";
                $query2 = mysqli_query($conexion, $sql2);
                if(mysqli_num_rows($query2) > 0){
                  echo "<table>
                  <thead><tr>
                    <th>Tipo documento</th>
                    <th>Nombre</th>
                    <th>Version</th>
                    <th>Ver</th>
                  </tr></thead><tbody>";
                  while($fetch2 = mysqli_fetch_assoc($query2)){
                    $nombre = $fetch2['nombre_man_form'];
                    $version = $fetch2['edicion'];
                    //$responsable = $fetch2['nombre_puesto'];
                    $idDoc = $fetch2['id_man_form'];
                    $tipoDoc = $fetch2['tipo_doc'];
                    echo "<tr>
                      <td>$tipoDoc</td>
                      <td>$nombre</td>
                      <td>$version</td>
                      <td>
                        <a href='verInfoDoc.php?docInfo=$idDoc' target='_blank'>
                          <i class='material-icons amber-text darken-4'>import_contacts</i>
                        </a>
                      </td>
                    </tr>";
                  }//fin del while

                  //consultamos los datos historicos de documentos
                  $sql3 = "SELECT * FROM his_mov_docs WHERE acta_ant = '$idActa'";
                  $query3 = mysqli_query($conexion, $sql3);
                  if(mysqli_num_rows($query3) > 0){
                    while($fetch3 = mysqli_fetch_assoc($query3)){
                      $nombre = $fetch3['nombre_ant'];
                      $tipoDoc = $fetch3['tipo_ant'];
                      $version = $fetch3['ver_ant'];
                      $idDoc = $fetch3['doc_id'];
                      echo "<tr>
                        <td>$tipoDoc</td>
                        <td>$nombre</td>
                        <td>$version</td>
                        <td>
                          <a href='verInfoDoc.php?docInfo=$idDoc' target='_blank'>
                            <i class='material-icons amber-text darken-4'>import_contacts</i>
                          </a>
                        </td>
                      </tr>";
                    }//fin while
                  }
                  echo "</tbody></table>";
                }else{
                  //sin documentacion
                  echo "<div class='col s12 center'>
                    <h5 class='center-align'>Sin documentos</h5>
                    <img src='img/carpeta-vacia2.png' width='100px' class='center-align'>
                  </div>";
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
  <script src="js/infoActa.js"></script>
  <script src="js/sweetAlert2.min.js"></script>
  <script src="js/vercontroles.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
