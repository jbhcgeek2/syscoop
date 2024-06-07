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
    //verificamos la existencia del ticket
    $idTipo = $_GET['tipo'];
    $sqlT = "SELECT * FROM tipoTickets WHERE idTipoTicket = '$idTipo'";
    $queryT = mysqli_query($conexion, $sqlT);
    if(mysqli_num_rows($queryT) != 1){
      //bay bay
      header('Location: index.php');
      ?>
      <script>
        window.location = 'index.php';
      </script>
      <?php
    }else{
      //aqui definimos los datos del ticket
      $fetchT = mysqli_fetch_assoc($queryT);
      $idDepResponsable = $fetchT['responsableTicket'];
      $idDepSolicita = $fetchT['solicitaTicket'];
      $nombreTicket = $fetchT['nombreTicket'];
      $diasAtencion = $fetchT['diasAtencion'];
      $estatus = $fetchT['ticketActivo'];
      $camposAdd = $fetchT['camposTicket'];
    }
  ?>
  <div class="row">

  </div>

  <div class="row">
      <div class="col s12">

        <div class="card cardStyleContent">
          <div class="titulo">Informacion de Ticket</div>
          <div class="card-content">
            <div class="row">
              <input type="hidden" name="valorTicket" id="valorTicket" value="<?php echo $idTipo; ?>">

            <div class="input-field col s12 m4 l3">
              <input type="text" id="nombreTicket" name="nombreTicket" value="<?php echo $nombreTicket; ?>" onchange="updateCampoN(this.id)">
              <label for="nombreTicket">nombre Ticket</label>
            </div>
            <div class="input-field col s12 m4">
              <select name="solicitaTicket" id="solicitaTicket" onchange="updateCampoN(this.id)">
                <?php
                  //consultamos todos los departamentos responsables
                  $sqlDepRes = "SELECT * FROM departamentos WHERE id_departamento > 1";
                  $queryDepRes = mysqli_query($conexion, $sqlDepRes);
                  while($fetchDepRes = mysqli_fetch_assoc($queryDepRes)){
                    $nombreDepRes2 = $fetchDepRes['nombre_departamento'];
                    $idDepSol = $fetchDepRes['id_departamento'];
                    if($idDepSolicita == $idDepSol){
                      echo "<option value='$idDepSol' selected>$nombreDepRes2</option>";
                    }else{
                      echo "<option value='$idDepSol'>$nombreDepRes2</option>";
                    }
                    
                  }//fin del while

                ?>
              </select>
              <label for="solicitaTicket">Departamento Solicitante</label>
            </div>
            <div class="input-field col s12 m4">
              <select name="responsableTicket" id="responsableTicket" onchange="updateCampoN(this.id)">
                <?php
                  //consultamos todos los departamentos responsables
                  $sqlDepRes = "SELECT * FROM departamentos WHERE id_departamento > 1";
                  $queryDepRes = mysqli_query($conexion, $sqlDepRes);
                  while($fetchDepRes = mysqli_fetch_assoc($queryDepRes)){
                    $nombreDepRes = $fetchDepRes['nombre_departamento'];
                    $idDepRes = $fetchDepRes['id_departamento'];
                    if($idDepResponsable == $idDepRes){
                      echo "<option value='$idDepRes' selected>$nombreDepRes</option>";
                    }else{
                      echo "<option value='$idDepRes'>$nombreDepRes</option>";
                    }
                    
                  }//fin del while

                ?>
              </select>
              <label for="responsableTicket">Departamento Responsable</label>
            </div>

            <div class="input-field col s12 m3 l2">
              <input type="number" id="diasAtencion" name="diasAtencion" value="<?php echo $diasAtencion; ?>" onchange="updateCampoN(this.id)">
              <label for="diasAtencion">Dias de Atencion</label>
            </div>
            <div class="input-field col s12 m4 l3">
              <select name="ticketActivo" id="ticketActivo">
                <?php
                if($estatus == '1'){
                  echo "<option value='1' selected>Activo</option><option value='0'>Inactivo</option>";
                }else{
                  echo "<option value='1'>Activo</option><option value='0' selected>Inactivo</option>";
                }
                ?>
              </select>
              <label for="ticketActivo">Estatus Ticket</label>
            </div>

            <div class="col s12 center-align">
              <a href="#!" class="btn waves waves-effect btnGrenNormal" id="btnAddCampo">Agregar Campo</a>
              <a href="#!" class="btn waves waves-effect btnRedNormal" id="btnDelCampo">Eliminar Campo</a>
            </div>

            <div class="col s12">
              <div class="row" id="resCamposAdd">
              <?php
                //en esta seccion mostraremos los campos
                $nCampos = explode("|",$camposAdd);
                $countCampos = count($nCampos);
                echo "<input type='hidden' id='camposAdd' name='camposAdd' value='$countCampos'>";
                $k = 1;
                for($x = 0; $x < $countCampos; $x++){
                  $valor = $nCampos[$x];
                  echo "<div class='input-field col s12 m4' id='addCampo$k'>
                    <input type='text' id='inputAdd$k' name='inputAdd$k' value='$valor' onchange='updateCamposAdd()'>
                    <label for='inputAdd$k'>Campo $k</label>
                  </div>";
                  $k++;
                }//fin del for
              ?>
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
  <!-- <script src="js/start.js"></script> -->
  <script src="js/sweetAlert2.min.js"></script>
  <script src="js/verInfoTipoT.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
