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
          <div class="titulo">Empleados</div>
          <div class="card-content">
            <div class="row">

            <div class="col s12">
                <div class="input-field col s12 m6 offset-m3 l4 offset-l4">
                    <input type="text" id="buscarEmpleado">
                    <label for="buscarEmpleado">Buscar Empleado</label>
                </div>
            </div>


            <div class="col s12 l10 offset-l1">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Cargo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $sqlEmp = "SELECT *,(SELECT b.nombre_puesto FROM puestos b WHERE a.cargo_id = b.id_puesto) 
                            AS nombreCargo FROM empleados a WHERE a.id_empleado > 1 ORDER BY a.paterno ASC";
                            $queryEmp = mysqli_query($conexion,$sqlEmp)or die("Error al consultar los empleados");
                            while($fetchEmp = mysqli_fetch_assoc($queryEmp)){
                              $nombreEmpleado = $fetchEmp['paterno']." ".$fetchEmp['materno']." ".$fetchEmp['nombre'];
                              $correoEmpleado = $fetchEmp['correo'];

                              echo "<tr>
                                <td>$nombreEmpleado</td>
                                <td>$correoEmpleado</td>
                                <td></td>
                              </tr>";
                            }//fin del while
                        ?>
                    </tbody>
                </table>
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
  <!-- <script src="js/start.js"></script> -->
  <script src="js/sweetAlert2.min.js"></script>
  <script src="js/verControles.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
