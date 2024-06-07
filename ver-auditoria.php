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
    //verificamos la existencia de la auditoria
    if(!empty($_GET['info'])){
      $idauditoria = $_GET['info'];
      if($permiso->auditar_inventario != 1){
        header('location: control.php');
        echo "<script>window.location='index.php'</script>";
      }

      $sqlAu = "SELECT * FROM auditoria_inventario WHERE id_auditoria = '$idauditoria'";
      $queryAu = mysqli_query($conexion, $sqlAu);
      if($queryAu && mysqli_num_rows($queryAu) == 1){
        $fetchAu = mysqli_fetch_assoc($queryAu);
        $tipoRev = $fetchAu['tipo_auditoria'];
        $fechaCierreRev = $fetchAu['fecha_fin'];

        //se se detecta que existe mostratemos la informacion general
        //numero de articulos disponibles
        $sqlI1 = "SELECT COUNT(*) AS numSuc FROM sucursales WHERE sucursal_activa = '1'";
        $queryI1 = mysqli_query($conexion, $sqlI1);
        $fetchI1 = mysqli_fetch_assoc($queryI1);
        $numSucs = $fetchI1['numSuc'];

        $sqlI2 = "SELECT COUNT(*) AS numArt FROM inventario WHERE articulo_activo = '1'";
        $queryI2 = mysqli_query($conexion, $sqlI2);
        $fetchI2 = mysqli_fetch_assoc($queryI2);
        $numArti = $fetchI2['numArt'];

        $sqlI3 = "SELECT COUNT(*) as numVerif FROM auditoria_objeto WHERE auditoria_id = '$idauditoria'";
        $queryI3 = mysqli_query($conexion, $sqlI3);
        $fetchI3 = mysqli_fetch_assoc($queryI3);
        $numVerif = $fetchI3['numVerif'];


      }else{
        header('location: control.php');
        echo "<script>window.location='index.php'</script>";
      }
    }else{
      header('location: control.php');
      echo "<script>window.location='index.php'</script>";
    }
  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Informacion de <?php echo $tipoRev; ?></div>
          <div class="card-content">
            <div class="row">
              
            <div class="col s12 m4">
              <div class="card redGradient cardControl" id="verControles">
                <div class="cardAudit center-align">
                  Sucursales <br> <?php echo $numSucs; ?>
                </div>
              </div>
            </div>

            <div class="col s12 m4">
              <div class="card redGradient cardControl" id="verControles">
                <div class="cardAudit center-align">
                  Total Articulos <br> <?php echo $numArti; ?>
                </div>
              </div>
            </div>

            <div class="col s12 m4">
              <div class="card redGradient cardControl" id="verControles">
                <div class="cardAudit center-align">
                  Revisados <br> <?php echo $numVerif; ?>
                </div>
              </div>
            </div>

            <div class="col s12">
              <div class="divider"></div>
            </div>

            <?php 
            if($fechaCierreRev == ""){
              ?>
              <div class="col s12 m4 l3">
                <div class="card blueGradient cardControl2 valign-wrapper">
                  <div class="cardAuditControl center-align" id="<?php echo $idauditoria; ?>" 
                    onclick="generaFormato(this.id);">
                    Generar Formato de <?php echo $tipoRev; ?>
                  </div>
                </div>
              </div>
              <div class="col s12 m4 l3">
                <div class="card redGradient cardControl2 valign-wrapper" id="finalizarRev">
                  <div class="cardAuditControl center-align">
                    Finalizar <?php echo $tipoRev; ?>
                  </div>
                </div>
              </div>
              <?php
            }else{
              ?>
              <div class="col s12 m4 l3">
                <div class="card blueGradient cardControl2 valign-wrapper">
                  <div class="cardAuditControl center-align" id="<?php echo $idauditoria; ?>" 
                    onclick="generaFormato(this.id);">
                    Generar Resumen de <?php echo $tipoRev; ?>
                  </div>
                </div>
              </div>
              <?php
            }
            ?>


            

            
            

            </div><!--FIN row principal del card-->
          </div><!--FIN Cardcontent principal-->
        </div><!--FIN cardStyleContent card principal-->

        <div class="card cardStyleContent">
          <?php 
          if($fechaCierreRev == ""){
            ?>
              <div class="titulo">Buscar Articulo</div>
              <div class="card-content">
              <div class="row">
                <div class="input-field col s12 m4">
                  <input type="hidden" id="revData" value="<?php echo $idauditoria; ?>">
                  <select id="clasificacionObjeto" onchange="getBySelects();">
                    <option value=""selected>Seleccione...</option>
                    <?php 
                      $sqlI7 = "SELECT DISTINCT(clasificacion) FROM inventario WHERE articulo_activo = '1' 
                      ORDER BY clasificacion ASC";
                      $queryI7 = mysqli_query($conexion, $sqlI7);
                      while($fetchI7 = mysqli_fetch_assoc($queryI7)){
                        $clasi = strtoupper($fetchI7['clasificacion']);
                        echo "<option value='$clasi'>$clasi</option>";
                      }//fin del while
                    ?>
                  </select>
                  <label for="clasificacionObjeto">Clasificacion</label>
                </div>
                <div class="input-field col s12 m4">
                  <select id="sucursalResguardo" onchange="getBySelects();">
                    <option value="" selected>Seleccione...</option>
                    <?php 
                      $sqlI5 = "SELECT DISTINCT(sucursal_resguardo) AS sucurRes FROM inventario WHERE 
                      articulo_activo = '1' ORDER BY sucursal_resguardo ASC";
                      $queryI5 = mysqli_query($conexion, $sqlI5);
                      while($fetchI5 = mysqli_fetch_assoc($queryI5)){
                        $sucu = strtoupper($fetchI5['sucurRes']);
                        echo "<option value='$sucu'>$sucu</option>";
                      }
                    ?>
                  </select>
                  <label for="sucursalResguardo">Sucursal Resguardo</label>
                </div>
                <div class="input-field col s12 m4">
                  <select id="lugarResguaro" onchange="getBySelects();">
                    <option value=""selected>Seleccione...</option>
                    <?php 
                      $sqlI6 = "SELECT DISTINCT(lugar_resguardo) as LugRes FROM inventario WHERE 
                      articulo_activo = '1' ORDER BY lugar_resguardo ASC";
                      $queryI6 = mysqli_query($conexion, $sqlI6);
                      while($fetchI6 = mysqli_fetch_assoc($queryI6)){
                        $lug = strtoupper($fetchI6['LugRes']);
                        echo "<option value='$lug'>$lug</option>";
                      }//fin del while
                    ?>
                  </select>
                  <label for="lugarResguaro">Lugar Resguardo</label>
                </div>

                <div class="input-field col s12 m6 offset-m3 l4 offset-l4">
                  <input type="text" id="busArtiByCod">
                  <label for="busArtiByCod">Buscar por codigo</label>
                </div>

                  <p class="col s12 center-align">A continuacion se muestran los 
                    articulos pendientes por validar
                  </p>

                <div id="resulTable" class="col s12" style="height:300px;overflow-y:scroll;">
                  <table>
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Lugar Resguardo</th>
                        <th>Sucursal</th>
                        <th>Ver</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                        $sqlI7 = "SELECT *,(SELECT id_audi_invent FROM auditoria_objeto b 
                        WHERE b.auditoria_id = '$idauditoria' AND b.inventario_id = a.id_inventario) AS 
                        existeAudi FROM inventario a ORDER BY a.sucursal_resguardo,a.lugar_resguardo";
                        $queryI7 = mysqli_query($conexion, $sqlI7);
                        while($fetchI7 = mysqli_fetch_assoc($queryI7)){
                          $existe = $fetchI7['existeAudi'];
                          if($existe ==  NULL){
                            $idCod = $fetchI7['id_inventario'];
                            $codigo = $fetchI7['codigo'];
                            $nombre = $fetchI7['nombre_objeto'];
                            $lugarRes = $fetchI7['lugar_resguardo'];
                            $sucurRes = $fetchI7['sucursal_resguardo'];

                            echo "<tr>
                            <td>$idCod</td>
                            <td>$codigo</td>
                            <td class='truncate'>$nombre</td>
                            <td>$lugarRes</td>
                            <td>$sucurRes</td>
                            <td>
                              <a href='ver-obj-ind.php?obj=$idCod' target='_blank'>
                                <i class='material-icons'>info</i>
                              </a>
                            </td>
                            </tr>";
                            
                          }
                        }//fin del wileI7
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <?php
          }
          ?>
          
          
        </div>


        <div class="card cardStyleContent">
          <div class="titulo">Articulos validados</div>
          <div class="card-content">
            <div class="row" style="height:300px;overflow-y:scroll;">
              <?php 
                $sqlI4 = "SELECT * FROM auditoria_objeto a INNER JOIN inventario b 
                ON a.inventario_id = b.id_inventario WHERE auditoria_id = '$idauditoria' ORDER BY
                a.lugar_resguardo_inv ASC";
                $queryI4 = mysqli_query($conexion, $sqlI4);
                if(mysqli_num_rows($queryI4) > 0){
                  ?>
                  <table>
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Lugar Resguardo</th>
                        <th>Sucursal</th>
                        <th>Fecha Validacion</th>
                        <th>Revisor</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        while($fetchI4 = mysqli_fetch_assoc($queryI4)){
                          $nombreObj = $fetchI4['nombre_objeto'];
                          $codInv = $fetchI4['codigo'];
                          $fechaRev = $fetchI4['fecha_inventario'];
                          $rev = $fetchI4['usuario_inventario'];
                          $idObj = $fetchI4['id_inventario'];
                          $lugar = $fetchI4['lugar_resguardo_inv'];
                          $sucur = $fetchI4['sucur_resguardo_inv'];
                          $usuarioInv = $fetchI4['usuario_inventariado'];
                          $usuarioInv = getUserById($usuarioInv);
                          echo "<tr>
                            <td>$idObj</td>
                            <td>$codInv</td>
                            <td>$nombreObj</td>
                            <td>$lugar</td>
                            <td>$sucur</td>
                            <td>$fechaRev</td>
                            <td>$usuarioInv</td>
                          </tr>";
                        }//fin del while
                      ?>
                    </tbody>
                  </table>
                  <?php
                }else{
                  //aun no se valida ningun objeto

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
  <script src="js/infoAuditoria.js"></script>
  <script src="js/infoAuditoria2.js"></script>
  <script src="js/sweetAlert2.min.js"></script>
  
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
