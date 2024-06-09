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
          <div class="titulo">Listado de Sucursales</div>
          <div class="card-content">
            <div class="row">

              <div class="col s12 m12 l10 offset-l1">
                <table>
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>Estatus</th>
                      <th>Editar</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                    $sqlSuc = "SELECT * FROM sucursales ORDER BY nombre_sucursal ASC";
                    $querySuc = mysqli_query($conexion, $sqlSuc);
                    if($querySuc){
                      while($fetchSuc = mysqli_fetch_assoc($querySuc)){
                        $nombreSuc = $fetchSuc['nombre_sucursal'];
                        $idSuc = $fetchSuc['id_sucursal'];
                        if($fetchSuc['sucursal_activa'] == 0){
                            $classCard = "Activa";
                        }else{
                            $classCard = "Baja";
                        }
                        $cadena = $idSuc."|".$nombreSuc."|".$classCard;
                        ?>
                        
                        <tr>
                          <td><?php echo $nombreSuc; ?></td>
                          <td><?php echo $classCard; ?></td>
                          <td>
                            <a href='#modalEditSuc' class="btn waves waves-effect btnGrenNormal modal-trigger"
                            id="editSuc|<?php echo $cadena; ?>" onClick="editSuc(this.id)">Editar</a>
                          </td>
                        </tr>
                        <?php
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
                <a href="#ModalNewSuc" class="btn modal-trigger waves waves-effect blue" id="newSuc">Nueva Sucursal</a>
              </div>
            </div>
            
          </div><!--FIN Cardcontent principal-->
        </div><!--FIN cardStyleContent card principal-->

      </div><!--FIN col principal 12-->
  </div>


  <div id="ModalNewSuc" class="modal">
    <div class="modal-content">
      <div class="row">
        <h4>Registrar Sucursal</h4>

        <div class="input-field col s12 m6">
            <input type="text" class="" id="nameSuc">
            <label for="nameSuc">Nombre</label>
        </div>

        <div class="input-field col s12 m6">
            <select name="estatusSuc" id="sucActiva">
                <option value="">Seleccione</option>
                <option value="1">Activa</option>
                <option value="0">Inactiva</option>
            </select>
            <label for="sucActiva">Estatus</label>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
      <a href="#!" class="btn blue waves-effect waves-blue" id="regSuc">Registrar</a>
    </div>
  </div>

  <div id="modalEditSuc" class="modal">
    <div class="modal-content">
      <div class="row">
        <h4>Registrar Sucursal</h4>

        <div class="input-field col s12 m6">
            <input type="text" class="" id="nameSucEdit">
            <label for="nameSucEdit">Nombre</label>
        </div>

        <div class="input-field col s12 m6">
            <select name="estatusSucEdit" id="estatusSucEdit">
                <option value="">Seleccione</option>
                <option value="1">Activa</option>
                <option value="0">Inactiva</option>
            </select>
            <label for="estatusSucEdit">Estatus</label>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
      <a href="#!" class="btn blue waves-effect waves-blue" id="regSuc">Registrar</a>
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
  <script src="js/vercontroles.js"></script>
  <script src="js/verSucursales.js"></script>
  <!-- <script src="js/login.js"></script> -->
  <?php
}else{

  header('location: index.php');
}
   ?>
  </body>
</html>
