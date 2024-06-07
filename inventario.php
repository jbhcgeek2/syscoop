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
          <div class="titulo">Control de Inventario</div>
          <div class="card-content">
            <div class="row">

              <div class="row">
                  <div class="input-field col s6 m3">
                    <select name="clasificacion" id="clasificacion">
                      <option value="">Seleccione...</option>
                      <option value="Mobiliario">Mobiliario</option>
                      <option value="Equipo">Equipo</option>
                      <option value="Transporte">Transporte</option>
                    </select>
                    <label for="">Clasificacion</label>
                  </div>
                  <div class="input-field col s6 m3">
                    <select name="sucursal" id="sucursal">
                      <option value="">Seleccione...</option>
                      <?php 
                      $sqlSuc = "SELECT * FROM sucursales WHERE sucursal_activa = '1' ORDER BY nombre_sucursal ASC";
                      $querySuc = mysqli_query($conexion, $sqlSuc);
                      while($fetchSuc = mysqli_fetch_assoc($querySuc)){
                        $nombreSuc = $fetchSuc['nombre_sucursal'];
                        echo "<option value='$nombreSuc'>$nombreSuc</option>";
                      }//fin del while
                      ?>
                    </select>
                    <label for="">Sucursal</label>
                  </div>
                <div class="input-field col s12 m6">
                  <input type="text" id="buscarObjeto">
                  <label for="buscadorObjeto">Buscar objeto</label>
                </div>
              </div>

              <?php 
              if($permisos->agregar_inventario == 1){
                ?> 
                  <div class="col s12 right-align">
                    <a href="nuevo-objeto.php" class="btn blue waves waves-effect">
                      Registrar Objeto
                    </a>
                  </div>
                </div>
                <?php
              }
              ?>

            <div class="row" style="overflow-y:scroll;height:500px;">
              <div class="col s12" >
                <table class="centered black-text striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Clasificacion</th>
                      <th>Sucursal</th>
                      <th>Ver Mas</th>
                    </tr>
                  </thead>
                  <tbody id="resultBusquedas">
                    <?php 
                      //consultaremos los objetos del inventario
                      //consultamos el total de registros
                      $sqlUxT = "SELECT COUNT(*) AS totFacs FROM factura_inventario";
                      $queryUxT = mysqli_query($conexion, $sqlUxT);
                      $fetchUxT = mysqli_fetch_assoc($queryUxT);
                      $totalRows = $fetchUxT['totFacs'];


                      $sqlInv = "SELECT *,(SELECT b.nombre_objeto  FROM inventario b WHERE 
                      b.factura_objeto = a.id_factura LIMIT 1) AS nombre_objeto_aux,
                      (SELECT b.clasificacion FROM inventario b WHERE 
                      b.factura_objeto = a.id_factura LIMIT 1) AS clasificacion,
                      (SELECT b.sucursal_resguardo FROM inventario b WHERE 
                      b.factura_objeto = a.id_factura LIMIT 1) AS sucursal_resguardo FROM 
                      factura_inventario a ORDER BY a.id_factura DESC";
                      $queryInv = mysqli_query($conexion,$sqlInv);
                      if($queryInv){
                        if(mysqli_num_rows($queryInv) > 0){
                          while($fetchInv = mysqli_fetch_assoc($queryInv)){
                            
                            $id = $fetchInv['id_factura'];
                            $nombreObj = strtoupper($fetchInv['nombre_objeto_general']);
                            $clasificacion = strtoupper($fetchInv['clasificacion']);
                            $sucursal = strtoupper($fetchInv['sucursal_resguardo']);

                            echo "<tr>
                              <td>$id</td>
                              <td>$nombreObj</td>
                              <td>$clasificacion</td>
                              <td>$sucursal</td>
                              <td>
                                <a href='ver-objeto.php?objId=$id'>
                                  <i class='material-icons'>screen_share</i>
                                </a>
                              </td>

                            </tr>";
                          }//fin del while
                        }else{
                          //no se encontraron resultados
                          echo "<tr><td colspan='5'>SIN RESULTADOS</td></tr>";
                        }
                      }else{
                        //error al realizar la consulta
                      }
                    ?>
                  </tbody>
                </table>
                <div class="col s12 center-align hide">
                  
                  <ul class="pagination" id="contentPaginator">
                    <li class="waves-effect" id="backPage" onclick="getPage(this.id)"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
                    
                    <?php 
                      $numeros = round($totalRows / $maxItemPage);
                      if($numeros > $maxNumPages){
                        $numerosR = $numeros;
                        $numeros = $maxNumPages;
                      }else{
                        $numerosR = $maxNumPages;
                      }
                      for($x = 1; $x<=$numeros; $x++){
                        if($x == 1){
                          echo "<li class='active' id='page|$x' onclick='getPage(this.id)'><a href='#!'>$x</a></li>";
                        }else{
                          echo "<li class='waves-effect' id='page|$x' onclick='getPage(this.id)'><a href='#!'>$x</a></li>";
                        }
                        
                      }//fin del for

                    ?>
                    <li class="waves-effect" id="nextPage" onclick="getPage(this.id)"><a href="#!"><i class="material-icons">chevron_right</i></a></li>
                  </ul>
                  <input type="hidden" id="maxPages" value="<?php echo $numeros; ?>">
                  <input type="hidden" id="realNumPages" value="<?php echo $numerosR; ?>">
                  <input type="hidden" id="maxRows" value="<?php echo $totalRows; ?>">
                </div>
              </div>
            </div>

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
  <script src="js/inventario.js"></script>
  <script src="js/sweetAlert2.min.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
