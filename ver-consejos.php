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
          <div class="titulo">Gobierno Corporativo</div>
          <div class="card-content">
            <div class="row">
              <table>
                <thead>
                  <tr>
                    <th>Consejo</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  //consultamos los consejos
                  $sqlCorp = "SELECT * FROM consejos";
                  try{
                    $queryCorp = mysqli_query($conexion, $sqlCorp);
                    if(mysqli_num_rows($queryCorp) > 0){
                      while($fetchCorp = mysqli_fetch_assoc($queryCorp)){
                        $nombreConsejo = $fetchCorp[''];
                        ?>
                          
                        <?php
                      }
                    }else{

                    }
                  }catch(Throwable $e){

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
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
