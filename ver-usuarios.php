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
          <div class="titulo">Usuarios Registrados</div>
          <div class="card-content">
            <div class="row">
              <div class="col s12">
                <div class="input-field col s12 m6 offset-m3 xl4 offset-xl4">
                  <input type="text" id="buscaUsuario" onkeyup="buscaUsuario(this.value)">
                  <label for="buscaUsuario">Buscar Usuario</label>
                </div>
              </div>

              <div class="col s12 m12 l12 xl10 offset-xl1" style="overflow-y: scroll;height: 500px;">
                <table>
                  <thead>
                    <tr>
                      <th>Usuario</th>
                      <th>Empleado</th>
                      <th>Departamento</th>
                      <th>Ver</th>
                    </tr>
                  </thead>
                  <tbody id="resultUser">
                    <?php 
                    $sqlUs = "SELECT * FROM usuarios a INNER JOIN empleados b ON 
                    a.empleado_id = b.id_empleado INNER JOIN departamentos c ON 
                    b.departamento_id = c.id_departamento WHERE a.usuario_activo = '1' ORDER BY 
                    a.nombre_usuario ASC";
                    $queryUs = mysqli_query($conexion, $sqlUs);
                    if($queryUs){
                      while($fetchUs = mysqli_fetch_assoc($queryUs)){
                        $nombreUs = $fetchUs['nombre_usuario'];
                        $empleado = $fetchUs['nombre']." ".$fetchUs['paterno']." ".$fetchUs['materno'];
                        $departamento = $fetchUs['nombre_departamento'];
                        $idUsuario = $fetchUs['id_usuario'];

                        echo "<tr>
                          <td>$nombreUs</td>
                          <td>$empleado</td>
                          <td>$departamento</td>
                          <td>
                            <a href='verInfoUsuario.php?dataSet=$idUsuario'>
                              <i class='material-icons red-text'>manage_accounts</i>
                            </a>
                          </td>
                        </tr>";
                      }
                    }else{

                    }
                    ?>
                  </tbody>
                </table>
              </div>

            </div><!--FIN row principal del card-->

            <div class="row">
              <div class="col s12 center-align">
                <a href="nuevo-usuario.php" class="btn waves waves-effect blue">Nuevo Usuario</a>
              </div>
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
  <script src="js/verUsuarios.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
