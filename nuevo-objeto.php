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
          <div class="titulo">Registrar Objeto</div>
          <div class="card-content">
            <div class="row">
              <form id="formNewObj" name="formNewObj" enctype="multipart/form-data">

                <div class="input-field col s12 m4 l3">
                  <input type="text" id="nombreObjeto" name="nombreObjeto">
                  <label for="nombreObjeto">Nombre Objeto</label>
                </div>
                <div class="input-field col s12 m4 l2">
                  <input type="date" id="fechaCompra" name="fechaCompra">
                  <label for="fechaCompra">Fecha Adquisicion</label>
                </div>
                <div class="input-field col s12 m4 l3">
                  <select name="proveedor" id="proveedor">
                    <option value="" selected disabled>Seleccione</option>
                    <?php 
                      $sqlProv = "SELECT id_proveedor,nombre_proveedor FROM proveedores ORDER BY nombre_proveedor ASC";
                      $queryProv = mysqli_query($conexion,$sqlProv);
                      if($queryProv){
                        if(mysqli_num_rows($queryProv) > 0){
                          while($fetchProv = mysqli_fetch_assoc($queryProv)){
                            $idProv = $fetchProv['id_proveedor'];
                            $nombreProv = $fetchProv['nombre_proveedor'];

                            echo "<option value='$idProv'>$nombreProv</option>";
                          }//fin del while
                        }else{
                          echo "<option value='' disabled>Sin resultados</option>";  
                        }
                      }else{
                        echo "<option value='' disabled>Error de consulta</option>";
                      }
                    ?>
                  </select>
                  <label for="proveedor">Proveedor</label>
                </div>
                <div class="input-field col s6 m4 l2">
                  <input type="text" name="modelo" id="modelo">
                  <label for="modelo">Modelo</label>
                </div>
                <div class="input-field col s6 m4 l2">
                  <input type="text" id="marca" name="marca">
                  <label for="marca">Marca</label>
                </div>
                <div class="input-field col s6 m4 l2">
                  <input type="text" name="color" id="color">
                  <label for="color">Color</label>
                </div>
                <div class="input-field col s6 m4 l3">
                  <input type="text" name="lugarResguardo" id="lugarResguardo">
                  <label for="lugarResguardo">Lugar Resguardo</label>
                </div>
                <div class="input-field col s12 m4 l3">
                  <select name="sucursalResguardo" id="sucursalResguardo">
                    <option value="" selected disabled>Seleccione</option>
                    <?php 
                    $sqlSuc = "SELECT * FROM sucursales WHERE sucursal_activa = '1' ORDER BY nombre_sucursal ASC";
                    $querySuc = mysqli_query($conexion, $sqlSuc);
                    while($fetchSuc = mysqli_fetch_assoc($querySuc)){
                      $nombreSuc = $fetchSuc['nombre_sucursal'];
                      echo "<option value='$nombreSuc'>$nombreSuc</option>";
                    }//fin del while
                    ?>
                  </select>
                  <label for="sucursalResguardo">Sucursal Resguardo</label>
                </div>
                <div class="input-field col s12 m4 l4">
                  <select name="empleadoResguardo" id="empleadoResguardo">
                    <option value="" selected disabled>Seleccione</option>
                    <?php
                      //consultamos los empleados registrados y activos
                      $sqlEmp = "SELECT id_empleado,nombre,paterno,materno FROM empleados WHERE activo = 1 ORDER BY nombre ASC";
                      $queryEmp = mysqli_query($conexion, $sqlEmp);
                      if($queryEmp){
                        if(mysqli_num_rows($queryEmp) > 0){
                          while($fetchEmp = mysqli_fetch_assoc($queryEmp)){
                            $idEmp = $fetchEmp['id_empleado'];
                            $nombreEmpleado = $fetchEmp['nombre']." ".$fetchEmp['paterno']." ".$fetchEmp['materno'];

                            echo "<option value='$idEmp'>$nombreEmpleado</option>";
                          }//fin del while
                        }else{
                          //no se encontraron resultados
                          echo "<option value='' disabled>Sin Registros</option>";
                        }
                      }else{
                        //no se consulto la informacion
                        echo "<option value='' disabled>Error de Consulta</option>";
                      }

                    ?>
                  </select>
                  <label for="empleadoResguardo">Empleado Asignado</label>
                </div>
                <div class="input-field col s6 m2 l2">
                  <input type="number" name="polizaRegistro" id="polizaRegistro">
                  <label for="polizaRegistro">Poliza Registro</label>
                </div>
                <div class="input-field col s6 m2 l2">
                  <input type="number" name="valorMoi" id="valorMoi">
                  <label for="valorMoi">Valor MOI</label>
                </div>
                <div class="input-field col s12 m8 l4">
                  <textarea name="observaciones" id="observaciones" class="materialize-textarea"></textarea>
                  <label for="observaciones">Observaciones</label>
                </div>
                <div class="input-field col s12 m4 l4">
                  <textarea name="accesorios" id="accesorios" class="materialize-textarea"></textarea>
                  <label for="accesorios">Accesorios</label>
                </div>
                
                <div class="input-field col s6 m2 l2">
                  <select name="clasificacion" id="clasificacion">
                    <option value="" selected disabled>Seleccione</option>
                    <?php 
                    $sqlClasi = "SELECT * FROM clasificacion WHERE mostrar = '1'";
                    $queryClasi = mysqli_query($conexion, $sqlClasi);
                    while($fetchClasi = mysqli_fetch_assoc($queryClasi)){
                      $nombreClasi = $fetchClasi['nombre_clasificacion'];
                      echo "<option value='$nombreClasi'>$nombreClasi</option>";
                    }
                    ?>
                  </select>
                  <label for="clasificacion">Clasificacion</label>
                </div>
                <div class="input-field col s6 m2 l2">
                  <input type="number" name="cantidadObjeto" id="cantidadObjeto">
                  <label for="cantidadObjeto">Cantidad</label>
                </div>
                <div class="input-field col s12 m4 l2">
                    <select name="sinodeprecia" id="sinodeprecia">
                      <option value=""selected disabled>Seleccione</option>
                      <option value="SI">Si</option>
                      <option value="NO">No</option>
                    </select>
                    <label for="sinodeprecia">¿El objeto deprecia?</label>
                </div>

                <div class="input-field file-field col s12 m12 l6">
                  <div class="btn red">
                    <span>Factura</span>
                    <input type="file" name="facturaObjeto" id="facturaObjeto">
                  </div>
                  <div class="file-path-wrapper">
                    <input type="text" class="file-path validate">
                  </div>
                </div>

                <div class="input-field file-field col s12 m12 l6">
                  <div class="btn red">
                    <span>Poliza</span>
                    <input type="file" name="polizaObjeto" id="polizaObjeto">
                  </div>
                  <div class="file-path-wrapper">
                    <input type="text" class="file-path validate">
                  </div>
                </div>
                
                <div class="input-field file-field col s12 m12 l6">
                  <div class="btn red">
                    <span>Imagen</span>
                    <input type="file" name="imgObjeto" id="imgObjeto">
                  </div>
                  <div class="file-path-wrapper validate">
                    <input type="text" class="file-path validate">
                  </div>

                </div>

              </form>
            </div><!--FIN row principal del card-->
            <div class="row center-align">
              <a href="#!" class="btn waves waves-effect blue" id="enviaForm">Guardar</a>
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
  <script src="js/altaInventario.js"></script>
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
