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
    //verificamos la existencia del proveedor
    
    if($permiso->ver_controles != 1){
      header('location: control.php');
    }
    

  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Catalogo de Puestos</div>
          <div class="card-content">
            <div class="row">
              <table>
                <thead>
                  <tr>
                    <th>Puesto</th>
                    <th>Titular</th>
                    <th>Ver</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  //consultamos los puestos
                  $sqlP = "SELECT * FROM puestos";
                  try {
                    $queryP = mysqli_query($conexion, $sqlP);
                    //hacemos el while de datos
                    while($fetchP = mysqli_fetch_assoc($queryP)){
                      $nombrePuesto = $fetchP['nombre_puesto'];

                      echo `<tr>
                        <td>$nombrePuesto</td>
                        <td></td>
                        <td></td>
                      </tr>`;
                    }//fin del while
                  } catch (\Throwable $th) {
                    //error al consultar la informacion
                    echo "Error: ".$th;
                  }

                  ?>
                </tbody>
              </table>
              

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
