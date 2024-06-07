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
    
    
  ?>

  <div class="row">
    <div class="col s12">
      <div class="card cardStyleContent">
        <div class="titulo">Reporte de depreciasion</div>
        <div class="card-content">
          <div class="row">
            <div class="input-field col s12 m4 l3">
              <select name="primFiltro" id="primFiltro">
                <option value="">Seleccione...</option>
                <option value="clasificacion">Por Clasificacion</option>
                <option value="activo">Por Activo...</option>
                <option value="sucursal">Por Sucursal...</option>
              </select>
              <label>Primer Filtro</label>
            </div>
            
            <div class="input-field col s12 m4 l3">
              <input type="date" name="fechaDesde" id="fechaDesde">
              <label for="fechaDesde">Desde</label>
            </div>
            <div class="input-field col s12 m4 l3">
              <input type="date" name="fechaHasta" id="fechaHasta">
              <label for="fechaHasta">Hasta</label>
            </div>
          </div>
          <div class="center">
            <a href="#!" class="btn waves waves-effect red darken-4" id="updateDep">Actualizar</a>
          </div>
        </div>
      </div>

      <div class="card cardStyleContent">
        <div class="card-content">
          <div class="row" id="resultReport"></div>
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
  <script src="js/repDepreciasion.js"></script>
  <script src="js/sweetAlert2.min.js"></script>
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

