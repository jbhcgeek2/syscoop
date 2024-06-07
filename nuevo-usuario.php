<!DOCTYPE html>
<html lang="en" class="colorFondo">
  <?php
    session_start();
    include('includes/head2.php');
   ?>
<body>
<main> 
  <?php
    include('includes/_con.php');
    include('includes/navBar.php');
    // include('includes/operations/encrp.php');

    if(!empty($_SESSION['usNamePlataform'])){
    //verificamos la existencia del proveedor
    // if(!empty($_GET['provId'])){
    //   $idProveedor = $_GET['provId'];

    //   $sqlProv = "SELECT *,(SELECT x.nombre_usuario FROM USUARIOS x WHERE x.id_usuario = a.usuario_registro) AS usuario_reg,
    //   (SELECT y.nombre_usuario FROM USUARIOS y WHERE y.id_usuario = a.usuario_actualizo) AS usuario_act FROM PROVEEDORES a WHERE a.id_proveedor = '$idProveedor'";
    //   $queryProv = mysqli_query($conexion, $sqlProv);
    //   if($queryProv && mysqli_num_rows($queryProv) == 1){
    //     $fetchProv = mysqli_fetch_assoc($queryProv);
        
    //   }else{
    //     header('location: control.php');
    //   }
    // }else{
    //   header('location: control.php');
    // }

    if($permiso->ver_controles != 1){
      header('location: control.php');
    }
    

  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Registrar Usuarios</div>
          <div class="card-content">
            <div class="row">

              <form name="formNewUser" id="formNewUser">
                <div class="input-field col s12 m4 l3">
                  <select name="empleadoUser" id="empleadoUser">
                    <option value="" selected disabled>Seleccione</option>
                    <option value="RegNewEmp">Nuevo Empleado</option>
                    <?php 
                    $sqlEmp = "SELECT * FROM empleados WHERE activo = 1 AND id_empleado <> $idEmpleado";
                    $queryEmp = mysqli_query($conexion, $sqlEmp);
                    if($queryEmp){
                      while($fetchEmp = mysqli_fetch_assoc($queryEmp)){
                        $idEmpleado = $fetchEmp['id_empleado'];
                        $nombreEmpleado = $fetchEmp['nombre']." ".$fetchEmp['paterno']." ".$fetchEmp['materno'];
                        echo "<option value='$idEmpleado'>$nombreEmpleado</option>";
                      }
                    }else{
                      echo "<option value='' disabled>ERROR DE CONSULTA</option>";
                    }
                    ?>
                  </select>
                  <label for="empleadoUser">Empleado</label>
                </div>

                <div class="input-field col s12 m4 l3">
                  <input type="text" id="nombreUsuario" name="nombreUsuario">
                  <label for="nombreUsuario">Nombre Usuario</label>
                </div>

                <div class="input-field col s6 m4 l3">
                  <input type="password" id="pass1" name="pass1">
                  <label for="pass1">Contraseña</label>
                </div>
                <div class="input-field col s6 m4 l3">
                  <input type="password" id="pass2" name="pass2">
                  <label for="pass2">Verifica Contraseña</label>
                </div>


                <div id="dataNewEmp" class="hide">
                  <div class="input-field col s12 m4 l2">
                    <input type="text" id="nameEmpleado" name="nameEmpleado">
                    <label for="nameEmpleado">Nombres</label>
                  </div>
                  <div class="input-field col s6 m4 l2">
                    <input type="text" id="patEmpleado" name="patEmpleado">
                    <label for="patEmpleado">Ap. Paterno</label>
                  </div>
                  <div class="input-field col s6 m4 l2">
                    <input type="text" id="matEmpleado" name="matEmpleado">
                    <label for="matEmpleado">Ap. Materno</label>
                  </div>
                  <div class="input-field col s12 m4 l4">
                    <input type="text" id="mailEmpleado" name="mailEmpleado">
                    <label for="mailEmpleado">Email</label>
                  </div>
                  <div class="input-field col s6 m4 l2">
                    <input type="text" id="celEmpleado" name="celEmpleado">
                    <label for="celEmpleado">Celular</label>
                  </div>
                  <div class="input-field col s12 m4 l3">
                    <select name="depEmpleado" id="depEmpleado">
                      <option value="" selected disabled>Seleccione..</option>
                      <?php 
                        $sqlDep = "SELECT * FROM departamentos ORDER BY nombre_departamento ASC";
                        $queryDep = mysqli_query($conexion, $sqlDep);
                        if($queryDep){
                          while($fetchDep = mysqli_fetch_assoc($queryDep)){
                            $idDep = $fetchDep['id_departamento'];
                            $nombreDep = $fetchDep['nombre_departamento'];
                            echo "<option value='$idDep'>$nombreDep</option>";
                          }//fin del while
                        }else{
                          echo "<option value=''>ERROR</option>";
                        }
                      ?>
                    </select>
                    <label for="depEmpleado">Departamento</label>
                  </div>
                  <div class="input-field col s12 m4 l3">
                    <select name="empleadoActivo" id="empleadoActivo">
                      <option value="" selected disabled>Seleccione..</option>
                      <option value="1">Activo</option>
                      <option value="0">Baja</option>
                    </select>
                    <label for="empleadoActivo">Estatus</label>
                  </div>
                </div><!--DIV nuevo empleado-->

                <div class="col s12">
                  <h5 class="center-align">Permisos de Usuario</h5>
                  <div class="row">
                    <div class="col s12 center">
                      <ul class="tabs">
                        <li class="tab"><a href="#catsPerms">Catalogos</a></li>
                        <li class="tab"><a href="#controlPerms">Controles</a></li>
                        <li class="tab"><a href="#controlOper">Operaciones</a></li>
                      </ul>
                    </div>
                    <div class=" blue-grey lighten-5">

                    </div>
                    <div class="col s12 blue-grey lighten-5 contPermisos" id="catsPerms">
                      <?php

                        $permisosCatalogos = ["verInventario","agregarInventario","editarInventario",
                        "verProveedsores","agregarProveedores","editarProveedores"];
                        $nombrePermiCat = ["Ver Inventario","Agregar Inventario","Editar Inventario",
                        "Ver Proveedores","Agregar Proveedores","Editar Proveedores"];

                        for ($iPer=0; $iPer < count($permisosCatalogos); $iPer++) {
                          echo "<div class='col s12 m4 l3'>
                            <p>
                              <label>
                                <input type='checkbox' name='".$permisosCatalogos[$iPer]."' id='".$permisosCatalogos[$iPer]."'>
                                <span>".$nombrePermiCat[$iPer]."</span>
                              </label>
                            </p>
                          </div>";
                        }//fin del for
                      ?>
                    </div>
                    <div class="col s12 blue-grey lighten-5 contPermisos" id="controlPerms">
                      <?php

                        $permisosControl = ["verControles","verMovimientos","verDepreciacion"];
                        $nombrePermCon = ["Ver Controles","Ver Movimientos","Ver Depreciacion"];

                        for ($iCon=0; $iCon < count($permisosControl); $iCon++) { 
                          echo "<div class='col s12 m4 l3'>
                            <p>
                              <label>
                                <input type='checkbox' name='".$permisosControl[$iCon]."' id='".$permisosControl[$iCon]."'>
                                <span>".$nombrePermCon[$iCon]."</span>
                              </label>
                            </p>
                          </div>";
                        }//fin del for
                      ?>
                    </div>
                    <div class="col s12 blue-grey lighten-5 contPermisos" id="controlOper">
                      <?php
                        $permisosOperativos = ["actualizaFoto"];
                        $nombrePerOper = ["Actualizar Fotos"];
                        for ($iOper=0; $iOper < count($permisosOperativos); $iOper++) { 
                          echo "<div class='col s12 m4 l3'>
                            <p>
                              <label>
                                <input type='checkbox' name='".$permisosOperativos[$iOper]."' id='".$permisosOperativos[$iOper]."'>
                                <span>".$nombrePerOper[$iOper]."</span>
                              </label>
                            </p>
                          </div>";
                        }//fin del for
                      ?>
                    </div>
                  </div>
                </div>
                
                <div class="row col s12">
                  <div class="col s6 center">
                    <a href="ver-usuarios.php" class="btn waves waves-effect red">Regresar</a>
                  </div>
                  <div class="col s6 center">
                    <a href="#!" class="btn waves waves-effect blue" id="saveNewUser">Registrar</a>
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
  <!-- <script src="js/start.js"></script> -->
  <script src="js/sweetAlert2.min.js"></script>
  <script src="js/altaUsuario.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <script>
    var elemSel = document.querySelectorAll('select');
    var instanceSelect = M.FormSelect.init(elemSel, options);

    var elemDate = document.querySelectorAll('.datepicker');
    var instanceDate = M.Datepicker.init(elemDate, options);
  </script>
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
