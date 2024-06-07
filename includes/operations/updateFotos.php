<?php 

session_start();
if(!empty($_SESSION['usNamePlataform'])){
  include('../_con.php');
  include('../conSql.php');

  if(!empty($_POST['socioUpdatePic'])){
    $socio = $_POST['socioUpdatePic'];
    //buscamos el nombre del socio
    $sql = "SELECT NOMBREC,PICTURE,FIRMA FROM $dbSQLName.dbo.SOCIOS WHERE SOCIO = '$socio'";
    $query = $con->query($sql);
    if($query){
      $fetch = $query->fetchAll(PDO::FETCH_OBJ);
      echo "DataSuccess|".trim($fetch[0]->NOMBREC);
    }
  }else{
    //sin datos
  }
}else{
  //sin acceso
}
?>