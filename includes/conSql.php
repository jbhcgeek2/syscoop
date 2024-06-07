<?php
  $host = '192.168.1.128';
  $dbSQLName = 'tepic';
  $username = 'infiwin';
  $pass = 'Infiwin1';
  $puerto = 1433;

  try{
    $con = new PDO ("sqlsrv:Server=$host;Database=$dbSQLName",$username,$pass);
    $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
  }
  catch(Exception $exp){
    echo "No se logro conectar: ".$exp->getMessage();
  }
?>