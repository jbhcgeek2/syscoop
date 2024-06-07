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
    if(!empty($_GET['docInfo'])){
      //verificamos que exista el documento
      $idDocumento = $_GET['docInfo'];
      

      $sqlDoc = "SELECT * FROM manuales_formatos a INNER JOIN departamentos b ON
      a.departamento_doc_id = b.id_departamento INNER JOIN puestos c ON 
      a.puesto_id= c.id_puesto WHERE a.id_man_form = '$idDocumento'";
      try{
        $queryDoc = mysqli_query($conexion, $sqlDoc);
        
        if(mysqli_num_rows($queryDoc) >= 0){
          $fetchDoc = mysqli_fetch_assoc($queryDoc);
          $nombreDocumento = $fetchDoc['nombre_man_form'];
          $version = $fetchDoc['edicion'];
          $fechaUltMod = $fetchDoc['fecha_ultima_mod'];
          $fechaRegistro = $fetchDoc['fecha_registro'];
          $fechaPublicacion = $fetchDoc['fecha_publicacion'];
          $usuarioRegistro = getUserById($fetchDoc['usuario_registro']);
          $departamento = $fetchDoc['nombre_departamento'];
          $puestoEncargado = $fetchDoc['nombre_puesto'];
          $docLectura = $fetchDoc['version_lectura'];
          $docEditable = $fetchDoc['version_editable'];
          $codificacion = $fetchDoc['codificacion'];
          $tipoDoc = $fetchDoc['tipo_doc'];
          //los usuarios que sean tengan el puesto encargado del documento podran 
          //realizar modificaciones, o tambien aquellos con permisos de ver manuales

          if($fetchDoc['puesto_id'] == $puestoIdUsuario || $permisoAgregarManuales == 1){
            //tiene permisos para actulizar el manual
            $permActualizaManu = 1;
          }else{
            //no tiene permisos
            $permActualizaManu = 0;
          }
          
          
          $sqlDocLectura = "SELECT * FROM documentos WHERE id_documento = '$docLectura'";
          $queryLectura = mysqli_query($conexion, $sqlDocLectura);
          $fetchLectura = mysqli_fetch_assoc($queryLectura);
          $nombreDocumentoLec = $fetchLectura['nombre_documento'];
          $rutaLectura = $fetchLectura['ruta_documento'];
          $fechaUpLec = $fetchLectura['fecha_doc'];
  
          $sqlDocEdit = "SELECT * FROM documentos WHERE id_documento = '$docEditable'";
          $queryDocEdit = mysqli_query($conexion, $sqlDocEdit);
          $fetchDocEdit = mysqli_fetch_assoc($queryDocEdit);
          $nombreDocumentoEdit = $fetchDocEdit['nombre_documento'];
          $rutaEditable = $fetchDocEdit['ruta_documento'];
          $fechaUpEdit = $fetchDocEdit['fecha_doc'];
          $estatusInterno = "";

          //consultamos el acta de autorizacion
          $idActa = $fetchDoc['acta_id'];
          if($tipoDoc == "Manual" && $idActa == 0){
            //es un manual con acta pendiente de autorizacion
            $sqlVerAnt2 = "SELECT * FROM his_mov_docs a INNER JOIN actas b ON a.acta_ant = b.id_acta 
            WHERE a.doc_id = '$idDocumento' ORDER BY fecha_mov DESC LIMIT 1";
            $queryVerAnt2 = mysqli_query($conexion, $sqlVerAnt2);
            if(mysqli_num_rows($queryVerAnt2) > 0){
              $fetchVerAnt2 = mysqli_fetch_assoc($queryVerAnt2);
              //como el documento no esta autorizado, tomamos en cuenta los datos del documento anterior
              $version = $fetchVerAnt2['ver_ant'];
              $acta = $fetchVerAnt2['tipo_acta']." ".$fetchVerAnt2['acta_num']." ".$fetchVerAnt2['numeral'];
              $fechaActa = $fetchVerAnt2['fecha_acta'];
              $estatusInterno = "pendienteAutori";
              $idLecAnt = $fetchVerAnt2['lectura_ant'];
              $idEdit = $fetchVerAnt2['mod_ant'];

              $sqlDocLectura = "SELECT * FROM documentos WHERE id_documento = '$idLecAnt'";
              $queryLectura = mysqli_query($conexion, $sqlDocLectura);
              $fetchLectura = mysqli_fetch_assoc($queryLectura);
              $nombreDocumentoLec = $fetchLectura['nombre_documento'];
              $rutaLectura = $fetchLectura['ruta_documento'];
              $fechaUpLec = $fetchLectura['fecha_doc'];
      
              $sqlDocEdit = "SELECT * FROM documentos WHERE id_documento = '$idEdit'";
              $queryDocEdit = mysqli_query($conexion, $sqlDocEdit);
              $fetchDocEdit = mysqli_fetch_assoc($queryDocEdit);
              $nombreDocumentoEdit = $fetchDocEdit['nombre_documento'];
              $rutaEditable = $fetchDocEdit['ruta_documento'];
              $fechaUpEdit = $fetchDocEdit['fecha_doc'];
              
            }else{
              $acta = "Sin Autorizacion";
              $estatusInterno = "pendienteAutori";
            }
            
          }else{
            if(!empty($idActa) || $idActa > 0){
              $sqlAct = "SELECT * FROM actas WHERE id_acta = '$idActa'";
              $queryAct = mysqli_query($conexion, $sqlAct);
              if(mysqli_num_rows($queryAct) > 0){
                $fetchAct = mysqli_fetch_assoc($queryAct);

                $acta = $fetchAct['tipo_acta']." ".$fetchAct['acta_num'].$fetchAct['numeral'];
                $fechaActa = $fetchAct['fecha_acta'];
              }else{
                //no se encontro elacta
                $acta = "No localizada";
                $fechaActa = "";
              }
            }else{
              //sin acta de autorizacion
              $acta = "Sin Autorizacion";
              $fechaActa = "";
            }
          }
          
          

        }else{
          header('location: ver-manuales.php');
        }
        
        

        
      }catch(Throwable $e){
        //error al consultar el dato
        header('location: ver-manuales.php');
      }
    }
  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Informacion de Documento</div>
          <div class="card-content">
            <div class="row">
          
              <div class="input-field col s12 m6 l5">
                <input type="hidden" id="statInter" value="<?php echo $estatusInterno; ?>">
                <input type="text" id="nombreDocumento" value="<?php echo $nombreDocumento; ?>" readonly>
                <label for="nombreDocumento">Nombre</label>
              </div>
              
              <div class="input-field col s12 m3 l3">
                <select id="tipoDoc">
                  <option value="" disabled>Seleccione</option>
                  <?php 
                    $tipoDocs =['Manual','Anexo','Formato','Informe','Presentacion'];
                    for($x=0; $x<count($tipoDocs); $x++){
                      if($tipoDocs[$x] == $tipoDoc){
                        echo "<option value='".$tipoDocs[$x]."' selected disabled>".$tipoDocs[$x]."</option>";
                      }else{
                        echo "<option value='".$tipoDocs[$x]."'>".$tipoDocs[$x]."</option>";
                      }
                    }
                  ?>
                </select>
                <label for="">Tipo Documento</label>
              </div>
              <div class="input-field col s12 m3 l2">
                <input type="text" id="versionDoc" value="<?php echo $codificacion; ?>" readonly>
                <label for="versionDoc">Codificacion</label>
              </div>
              <div class="input-field col s12 m3 l2">
                <input type="text" id="versionDoc" value="<?php echo $version; ?>" readonly>
                <label for="versionDoc">Version</label>
              </div>
              <div class="input-field col s12 m4 l2">
                <input type="date" id="fechaPubli" value="<?php echo $fechaPublicacion; ?>" readonly>
                <label for="fechaPubli">Fecha Publicacion</label>
              </div>
              <div class="input-field col s12 m4 l2">
                <input type="date" id="fechaUltMod" value="<?php echo $fechaUltMod; ?>" readonly>
                <label for="fechaUltMod">Ult. Modificacion</label>
              </div>
              <div class="input-field col s12 m4 l3">
                <input type="text" id="encargadoDoc" value="<?php echo $puestoEncargado; ?>" readonly>
                <label for="encargadoDoc">Responsable</label>
              </div>
              <div class="input-field col s12 m4 l2">
                <input type="text" id="usuarioRegistro" value="<?php echo $usuarioRegistro; ?>" readonly>
                <label for="usuarioRegistro">Usuario Registro</label>
              </div>
              <div class="input-field col s12 m4 l3">
                <input type="text" id="departamentoDoc" value="<?php echo $departamento; ?>" readonly>
                <label for="departamentoDoc">Departamento Aplicable</label>
              </div>

              <div class="input-field col s12 m4 l3">
                <input type="text" id="actaAutori" name="actaAutori" value="<?php echo $acta; ?>">
                <label for="">Acta de Autorizacion</label>
              </div>
              <div class="input-field col s12 m4 l3">
                <input type="date" id="fechaActaAutori" name="fechaActaAutori" value="<?php echo $fechaActa; ?>">
                <label for="fechaActaAutori">Fecha de Autoriazcion</label>
              </div>
              
              <?php
                if($permActualizaManu == 1){
                  ?>
                  <div class="file-field input-field col s12 m8">
                    <a href="<?php echo $rutaEditable ?>" class="btn btnBlueNormal" target="_blank">
                      <span>Ver WORD</span>
                    </a>
                    <div class="file-path-wrapper">
                      <input type="text" value="<?php echo $nombreDocumentoEdit;?>">
                    </div>
                  </div>
                  <div class="input-field col s12 m4">
                    <input type="date" id="fechaSubidoEdit" value="<?php echo $fechaUpEdit; ?>">
                    <label for="fechaSubidoEdit">Fecha Subida</label>
                  </div>
                  <?php
                } 
              ?>
              


              <div class="file-field input-field col s12 m8">
                <a href="<?php echo $rutaLectura; ?>" class="btn btnBlueNormal" target="_blank">
                  <span>Ver PDF</span>
                </a>
                <div class="file-path-wrapper">
                  <input type="text" value="<?php echo $nombreDocumentoLec; ?>">
                </div>
              </div>
              <div class="input-field col s12 m4">
                <input type="date" id="fechaSubidoLec" value="<?php echo $fechaUpLec; ?>">
                <label for="fechaSubidoLec">Fecha Subida</label>
              </div>
              
              <?php
                if($permActualizaManu == 1){
                  ?>
                  <div class="col s12 m6 center-align">
                    <a href="#modalModDatos" class="btn btnAmberNormal modal-trigger">
                      <i class="medium material-icons">history_edu</i>
                      Editar Datos
                    </a>
                  </div>
                  <?php
                }
              ?>
              
              
              <?php 
              if($tipoDoc == "Cotizacion" || $tipoDoc == "Autorizacion Credito"){

              }else{
                if($permActualizaManu == 1){
                  ?>
                  <div class="col s12 m6 center-align">
                    <a href="#modalNewVersion" class="btn btnBlueNormal modal-trigger">
                      <i class="medium material-icons">add_to_photos</i>
                      Agregar Version
                    </a>
                  </div>
                  <?php
                }
                
              }
              ?>
              
            </div><!--FIN row principal del card-->

            

            
          </div><!--FIN Cardcontent principal-->
        </div><!--FIN cardStyleContent card principal-->


        <div class="card cardStyleContent">
          <div class="titulo">Anexos del documento</div>
          <div class="card-content">
            <?php 
            $auxConsultaAnexo = "";
              $sqlAnexo = "SELECT a.*,(SELECT b.ruta_documento FROM documentos b WHERE 
              b.id_documento = a.version_editable AND b.tipo_documento = 'Anexo') AS rutaLecturaAnexo,
              (SELECT b.ruta_documento FROM documentos b WHERE b.id_documento = a.version_lectura 
              AND b.tipo_documento = 'Anexo') AS rutaEditable
              FROM manuales_formatos a WHERE a.tipo_doc = 'Anexo' AND a.departamento_doc_id = '$idDocumento'";
              $queryAnexo = mysqli_query($conexion, $sqlAnexo);
              if(mysqli_num_rows($queryAnexo) > 0){
                while($fetchAnexo = mysqli_fetch_assoc($queryAnexo)){
                  $nombreAnexo = $fetchAnexo['nombre_man_form'];
                  $codAnexo = $fetchAnexo['codificacion'];
                  $versAnexo = $fetchAnexo['edicion'];
                  $fechaPubAnexo = $fetchAnexo['fecha_publicacion'];
                  $fechaActAnexo = $fetchAnexo['fecha_ultima_mod'];
                  $editAnexo = $fetchAnexo['rutaEditable'];
                  $lectAnexo = $fetchAnexo['rutaLecturaAnexo'];
                  $idAnexo = $fetchAnexo['id_man_form'];
                  $auxConsultaAnexo .= " OR referencia_id = '$idAnexo'";
                  ?>
                  <div class="row">
                    <div class="card card-content deep-orange lighten-5">
                      <div class="row">
                        <div class="input-field col s12 m8 l8">
                          <input type="text" id="nombreAnexo" name="nombreAnexo" value="<?php echo $nombreAnexo; ?>">
                          <label for="nombreAnexo">Nombre Anexo</label>
                        </div>
                        <div class="input-field col s12 m2 l2">
                          <input type="text" id="codiAnexo" name="codiAnexo" value="<?php echo $codAnexo; ?>">
                          <label for="codiAnexo">Codificacion</label>
                        </div>
                        <div class="input-field col s12 m2 l2">
                          <input type="text" id="versAnexo" name="versAnexo" value="<?php echo $versAnexo; ?>">
                          <label for="versAnexo">Version</label>
                        </div>
                        <div class="input-field col s12 m4 l2">
                          <input type="date" id="fechaPubAnexo" name="fechaPubAnexo" value="<?php echo $fechaPubAnexo; ?>" readonly>
                          <label for="fechaPubAnexo">Fecha Publicacion</label>
                        </div>
                        <div class="input-field col s12 m4 l2">
                          <input type="date" id="UltModAnexo" name="UltModAnexo" value="<?php echo $fechaActAnexo; ?>" readonly>
                          <label for="UltModAnexo">Ult. Modificacion</label>
                        </div>

                        <div class="file-field input-field col s12">
                          <a href="<?php echo $lectAnexo; ?>" target="_blank" class="btn btnBlueNormal">
                            Ver PDF
                          </a>
                          <div class="file-path-wrapper">
                            <input type="text" value="<?php echo $nombreAnexo.".pdf"; ?>">
                          </div>
                        </div>

                        <?php
                          if($permActualizaManu == 1){
                            ?>
                            <div class="file-field input-field col s12">
                              <a href="<?php echo $editAnexo; ?>" target="_blank" class="btn btnBlueNormal">
                                Ver WORD
                              </a>
                              <div class="file-path-wrapper">
                                <input type="text" value="<?php echo $nombreAnexo; ?>">
                              </div>
                            </div>
                            <?php
                          } 
                        ?>
                        
                        
                        <?php
                          if($permActualizaManu == 1){
                            ?>
                            <div class="col s12">
                              <div class="col s12 m6 center">
                                <a href="#modalEdit<?php echo $idAnexo; ?>" class="btn btnAmberNormal modal-trigger">
                                  <i class="material-icons">edit</i>
                                  Modificar Informacion
                                </a>
                              </div>
                              
                              <div class="col s12 m6 center">
                                <a href="#modalNewVerAnex<?php echo $idAnexo; ?>" class="btn btnBlueNormal modal-trigger">
                                  <i class="material-icons">add_to_photos</i>
                                  Agregar Version
                                </a>
                              </div>

                              <div class="modal" id="modalEdit<?php echo $idAnexo; ?>">
                                <div class="modal-content">
                                  <div class="row">
                                    <h4 class="center-align">Modificar Datos Anexo</h4>
                                    <input type="hidden" name="idAnexo|<?php echo $idAnexo; ?>">
                                    <div class="input-field col s12 m8">
                                      <input type="text" id="nombreAnexo|<?php echo $idAnexo; ?>" value="<?php echo $nombreAnexo; ?>">
                                      <label for="nombreAnexo|<?php echo $idAnexo; ?>">Nombre Anexo</label>
                                    </div>
                                    <div class="input-field col s12 m4">
                                      <input type="text" id="codAnexo|<?php echo $idAnexo; ?>" value="<?php echo $codAnexo; ?>">
                                      <label for="codAnexo|<?php echo $idAnexo; ?>">Codificacion</label>
                                    </div>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <a href="#!" class="btn-flat waves modal-close">Cancelar</a>
                                  <a href="#!" class="btn waves btnBlueNormal" onclick="updateAnexo(this.id)" id="<?php echo $idAnexo; ?>">Modificar</a>
                                </div>
                              </div>

                              <div class="modal" id="modalNewVerAnex<?php echo $idAnexo; ?>">
                                <div class="modal-content">
                                  <div class="row">
                                    <h4 class="center-align">Agregar Version de Anexo</h4>
                                    <input type="hidden" name="addIdAnexo<?php echo $idAnexo; ?>">
                                    <div class="input-field col s12 m4">
                                      <select id="tipoVerAnex<?php echo $idAnexo ?>" onchange="getVersionesAnexos(this.id)">
                                        <option value="">Seleccione</option>
                                        <option value="NuevaVer">Nueva Version</option>
                                        <option value="OtraVer">Version Anterior</option>
                                      </select>
                                      <label for="tipoVerAnex<?php echo $idAnexo ?>">Tipo Actualizacion</label>
                                    </div>
                                    <div class="input-field col s12 m8">
                                      <input type="text" id="nameAnexVer<?php echo $idAnexo; ?>" value="<?php echo $nombreAnexo; ?>">
                                      <label for="nameAnexVer<?php echo $idAnexo; ?>">Nombre Anexo</label>
                                    </div>
                                    <div class="input-field col s12 m3">
                                      <input type="text" id="verActuAnex<?php echo $idAnexo; ?>" value="<?php echo $versAnexo; ?>" readonly>
                                      <label for="verActuAnex<?php echo $idAnexo; ?>">Version Actual</label>
                                    </div>
                                    <div class="input-field col s12 m3">
                                      <input type="text" id="verNewnex<?php echo $idAnexo; ?>">
                                      <label for="verNewnex<?php echo $idAnexo; ?>">Version a Agregar</label>
                                    </div>
                                    <div class="input-field col s12 m3">
                                      <input type="text" id="codNewAnex<?php echo $idAnexo; ?>">
                                      <label for="codNewAnex<?php echo $idAnexo; ?>">Codificacion Anexo</label>
                                    </div>
                                    <div class="input-field col s12 m3">
                                      <input type="date" id="fechPubliNewAnex<?php echo $idAnexo; ?>">
                                      <label for="fechPubliNewAnex<?php echo $idAnexo; ?>">Fecha Modificacion</label>
                                    </div>
                                    <div id="resManAnt<?php echo $idAnexo; ?>"></div>
                                    <div class="input-field file-field col s12">
                                      <div class="btn btnAmberNormal">
                                        <span>Subir Word</span>
                                        <input type="file" id="wordNewAnex<?php echo $idAnexo; ?>">
                                      </div>
                                      <div class="file-path-wrapper">
                                        <input type="text" class="file-path validate">
                                      </div>
                                    </div>
                                    <div class="input-field file-field col s12">
                                      <div class="btn btnAmberNormal">
                                        <span>Subir PDF</span>
                                        <input type="file" id="pdfNewAnex<?php echo $idAnexo; ?>">
                                      </div>
                                      <div class="file-path-wrapper">
                                        <input type="text" class="file-path validate">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <a href="#!" class="btn-flat waves-effect modal-close">Cancelar</a>
                                  <a href="#!" class="btn waves-effect btnBlueNormal" 
                                  onclick="newVerAnexo(<?php echo $idAnexo; ?>)">Actualizar</a>
                                </div>
                              </div>

                            </div>
                            <?php
                          }
                        ?>
                        
                      </div>
                    </div>
                  </div>
                  <?php
                }//fin del while
              }else{
                echo "<div class='row'>
                  <div class='center-align'>
                    <h5><strong>Sin registros</strong></h5>
                    <img src='img/carpeta-vacia2.png' width='100'>
                  </div>
                </div>";
              }
            ?>
          </div>
        </div>



        <div class="card cardStyleContent">
          <div class="titulo">Versiones Anteriores</div>
          <div class="card-content">
            <?php 
              $sqlVerAnt = "SELECT a.id_mov_doc,a.nombre_ant,a.ver_ant,
              (SELECT b.ruta_documento FROM documentos b WHERE b.id_documento = a.lectura_ant) AS lectura,
              (SELECT b.ruta_documento FROM documentos b WHERE b.id_documento = a.mod_ant) AS editable
              FROM his_mov_docs a WHERE a.doc_id = '$idDocumento'";
              $queryVerAnt = mysqli_query($conexion, $sqlVerAnt);
              if(mysqli_num_rows($queryVerAnt) > 0){
                ?>
                <table class="centered">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>Version</th>
                      <th>V. Lectura</th>
                      <?php
                        if($permActualizaManu == 1){
                          echo "<th>V. Editable</th><th>Ver Mas</th>";
                        } 
                      ?>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    while($fetchVerAnt = mysqli_fetch_assoc($queryVerAnt)){
                      $nombre = $fetchVerAnt['nombre_ant'];
                      $ver = $fetchVerAnt['ver_ant'];
                      $lectura = $fetchVerAnt['lectura'];
                      $editable = $fetchVerAnt['editable'];

                      if($permActualizaManu == 1){
                        echo "<tr>
                        <td>$nombre</td>
                        <td>$ver</td>
                        <td>
                          <a href='$lectura' target='_blank'>
                            <i class='material-icons amber-text darken-4'>import_contacts</i>
                          </a>
                        </td>
                        <td>
                          <a href='$editable' target='_blank'>
                            <i class='material-icons amber-text'>cloud_download</i>
                          </a>
                        </td>
                        <td>
                          <a hrec='#!' class='waves-effect btn blue'>
                            <i class='material-icons'>add</i>
                          </a>
                        </td>
                      </tr>";
                      }else{
                        echo "<tr>
                        <td>$nombre</td>
                        <td>$ver</td>
                        <td>
                          <a href='$lectura' target='_blank'>
                            <i class='material-icons amber-text darken-4'>import_contacts</i>
                          </a>
                        </td>
                      </tr>";
                      }

                      
                    }//fin del while
                    ?>
                  </tbody>
                </table>
                <?php
              }else{
                echo "<div class='row'>
                  <div class='center-align'>
                    <h5><strong>Sin registros</strong></h5>
                    <img src='img/carpeta-vacia2.png' width='100'>
                  </div>
                </div>";
              }
            ?>
          </div>
        </div>

        <?php
          if($permiso->ver_movimientos == 1){
            ?>
            <div class="card cardStyleContent">
              <div class="titulo">Movimientos</div>
              <div class="card-content">
                <div class="row" style="height:500px;overflow-y:scroll;">
                  <?php 
                      $sqlMov = "SELECT * FROM movimientos WHERE (referencia_id = '$idDocumento' $auxConsultaAnexo) 
                      AND tipo_movimiento = 'Documentos' ORDER BY fecha_movimiento ASC";
                      $queryMov = mysqli_query($conexion, $sqlMov);
                      while($fetchMov = mysqli_fetch_assoc($queryMov)){
                        $usuarioMov = $fetchMov['usuario_movimiento'];
                        $fechaMov = $fetchMov['fecha_movimiento'];
                        $desMov = $fetchMov['descripcion_movimiento'];

                        echo "
                        <div class='row'>
                          <div class='col s4 m3 l2'>
                            $fechaMov
                          </div>
                          <div class='col s8 m6 l8'>
                            $desMov
                          </div>
                          <div class='col l2 hide-on-med-and-down'>
                            $usuarioMov
                          </div>
                        </div>
                        <div class='row'><div class='divider'></div></div>
                        ";
                      }//fin de while
                    ?>
                </div>
              </div>
            </div>
            <?php
          }
        ?>


      </div><!--FIN col principal 12-->
  </div>


    <div class="modal modal-fixed-footer" id="modalNewVersion">
      <div class="modal-content">
        <div class="row">
          <h4 class="center-align"><strong>Agregar version</strong></h4>
          <form id="updateVersion" name="updateVersion" enctype="multipart/form-data">
          <input type="hidden" id="manualUpdate" name="manualUpdate" value="<?php echo $idDocumento; ?>">
          <div class="input-field col s12 m4 l4">
            <select name="tipoActualizacion" id="tipoActualizacion">
              <option value="">Seleccione...</option>
              <option value="NuevaVer">Nueva Version</option>
              <option value="OtraVer">Version Anterior</option>
            </select>
            <label for="">Tipo de actualizacion</label>
          </div>
          <div class="input-field col s12 m8 l8">
            <input type="text" name ="nombreDocumentoNewVer" id="nombreDocumentoNewVer"  value="<?php echo $nombreDocumento; ?>">
            <label for="nombreDocumentoNewVer">Nombre Documento</label>
          </div>
          <div class="input-field col s6 m4 l3">
            <input type="text" name="versionActual" id="versionActual" value="<?php echo $version; ?>" readonly>
            <label for="versionActual">Version Actual</label>
          </div>
          <div class="input-field col s6 m4 l3">
            <input type="text" name="nuevaVersion" id="nuevaVersion">
            <label for="nuevaVersion">Version a Agregar</label>
          </div>
          <div class="input-field col s12 m4 l2">
            <input type="text" name="nuevaCodificacion" id="nuevaCodificacion" value="<?php echo $codificacion; ?>">
            <label for="nuevaCodificacion">Codificacion</label>
          </div>
          <div class="input-field col s12 m6 l5">
            <select name="consejoEncargado" id="consejoEncargado">
              <option value="" selected disabled>Seleccione</option>
              <?php 
                $sqlDir = "SELECT * FROM consejos";
                
                try {
                  $queryDir = mysqli_query($conexion, $sqlDir);
                  while($fetchDir = mysqli_fetch_assoc($queryDir)){
                    echo "<option value='".$fetchDir['id_consejo']."'>".$fetchDir['nombre_consejo']."</option>";
                  }
                } catch (Throwable $th) {
                  echo "<option value='' selected disabled>ERROR DE BASES</option>";
                }
              ?>
            </select>
            <label for="consejoEncargado">Consejo que Autoriza</label>
          </div>
          <div class="input-field col s12 m6 l5">
            <select name="actaAutorizacion" id="actaAutorizacion">
              <option value="">Seleccione...</option>
            </select>
            <label for="actaAutorizacion">Acta Autorizacion</label>
          </div>

          <div class="input-field file-field col s12">
            <div class="btn">
              <span>Archivo Lectura</span>
              <input type="file" id="docNewLectura" name="docNewLectura">
            </div>
            <div class="file-path-wrapper">
              <input type="text" class="file-path validate">
            </div>
          </div>
          <div class="input-field file-field col s12">
            <div class="btn">
              <span>Archivo Editable</span>
              <input type="file" id="docNewEditable" name="docNewEditable">
            </div>
            <div class="file-path-wrapper">
              <input type="text" class="file-path validate">
            </div>
          </div>
          </form>

          
        </div>
      </div>
      <div class="modal-footer">
        <a href="#!" class="btn-flat waves-effect modal-close">Cancelar</a>
        <a href="#!" class="btn waves-effect btnBlueNormal" id="saveNewVer">Guardar</a>
      </div>
    </div>

    <div class="modal modal-fixed-footer" id="modalModDatos">
      <div class="modal-content">
        <div class="row">
          <h4 class="center-align">Editar Informacion</h4>
          <form name="updateDataDoc2" id="updateDataDoc2" enctype="multipart/form-data">
          <input type="hidden" id="manualUpdateData" name="manualUpdateData" value="<?php echo $idDocumento; ?>">
          <div class="input-field col s12">
            <input type="text" id="nuevoNombreUpdate" name="nuevoNombreUpdate" value="<?php echo $nombreDocumento; ?>">
            <label for="nuevoNombreUpdate">Nombre del documento</label>
          </div>
          <div class="input-field col s12 m4 l3">
            <input type="text" id="nuevoCodificacion" name="nuevoCodificacion" value="<?php echo $codificacion; ?>">
            <label for="nuevoCodificacion">Nueva Codificacion</label>
          </div>
          <div class="input-field col s12 m8">
            <select name="responsableDocumento" id="responsableDocumento">
              <option value="">Seleccione</option>
              <?php 
                $sqlPues = "SELECT * FROM puestos";
                $queryPues = mysqli_query($conexion, $sqlPues);
                while($fetchPues = mysqli_fetch_assoc($queryPues)){
                  $puesto = $fetchPues['nombre_puesto'];
                  $idPues = $fetchPues['id_puesto'];
                  if($fetchDoc['puesto_id'] == $idPues){
                    echo "<option value='$idPues' selected>$puesto</option>";
                  }else{
                    echo "<option value='$idPues'>$puesto</option>";
                  }
                }//fin del while
              ?>
            </select>
            <label for="responsableDocumento">Responsable</label>
          </div>
          <div class="input-field col s6 m8">
            <select name="depDocumento" id="depDocumento">
              <option value="">Seleccione</option>
              <?php 
                $sqlDep = "SELECT * FROM departamentos";
                $queryDep = mysqli_query($conexion, $sqlDep);
                while($fetchDep = mysqli_fetch_assoc($queryDep)){
                  $depa = $fetchDep['nombre_departamento'];
                  $idDep = $fetchDep['id_departamento'];
                  if($fetchDoc['departamento_doc_id'] == $idDep){
                    echo "<option value='$idDep' selected>$depa</option>";
                  }else{
                    echo "<option value='$idDep'>$depa</option>";
                  }
                }//fin del while
              ?>
            </select>
            <label for="depDocumento">Departamento Aplicable</label>
          </div>
          <div class="input-field col s6 m4">
            <input type="date" id="fechaNewPublicacion" name="fechaNewPublicacion" value="<?php echo $fechaPublicacion; ?>">
            <label for="fechaNewPublicacion">Fecha Publicacion</label>
          </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#!" class="btn-flat waves modal-close">Cancelar</a>
        <a href="#!" class="btn waves btnBlueNormal" id="saveModData">Guardar cambios</a>
      </div>
    </div>


    </main>
  <?php
    //require_once('includes/footer.php'); 
  ?>

  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <!-- <script src="js/start.js"></script> -->
  <script src="js/sweetAlert2.min.js"></script>
  <script src="js/infoDocs.js"></script>
  
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
