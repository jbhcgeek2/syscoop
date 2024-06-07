<?php 
session_start();

if(!empty($_SESSION['usNamePlataform'])){
  include('../_con.php');
  include('../conSql.php');

  if(!empty($_POST['tipoFiltro'])){
    $tipoFiltro = $_POST['tipoFiltro'];
    $valorFiltro = $_POST['valorFiltro'];
    $desde = $_POST['fechaDesFiltro'];
    $hasta = $_POST['fechaHasFiltro'];

    $mesArray = ["01"=>"Enero","02"=>"Febrero","03"=>"Marzo","04"=>"Abril",
    "05"=>"Mayo","06"=>"Junio","07"=>"Julio","08"=>"Agosto","09"=>"Septiembre",
    "10"=>"Octubre","11"=>"Noviembre","12"=>"Diciembre"];

    //hacemos el while de las fechas solicitadas
    if(!empty($desde) && !empty($hasta)){
      //validamosque desde no sea mayor a hasta
      if($desde < $hasta){
        if($desde != $hasta){
          //podemos continuar con el proceso
          $dateAux = explode("-",$desde);
          $nuevaFecha = $dateAux[0]."-".$dateAux[1]."-01";
          $fechaInicial = new DateTime($nuevaFecha);
          $fechaFinal = new DateTime($hasta);
          $diferencia = $fechaInicial->diff($fechaFinal);
          $meses = ($diferencia->y*12)+$diferencia->m;
          $contenidoTabla = "";
          $auxMes = 1;
          
          //consultamos las clasificaciones
          $sql1 = "SELECT nombre_clasificacion,porce_depreciacion FROM CLASIFICACION";
          $query1 = mysqli_query($conexion, $sql1);
          $datos = [];
          $iDa = 0;
          $headTable = "";
          while($fetch1 = mysqli_fetch_assoc($query1)){
            $clasi = $fetch1['nombre_clasificacion'];
            $headTable .= "<th>$clasi</th>";

            $clasificaciones[] = $clasi;
            $valorClasi[$clasi] = "0";

            $sql2 = "SELECT fecha_adquisicion,valor_moi,articulo_activo,fecha_baja FROM INVENTARIO WHERE clasificacion = '$clasi'";
            $query2 = mysqli_query($conexion, $sql2);
            $datos[$iDa] = $fetch1;
            while($fetch2 = mysqli_fetch_assoc($query2)){
              $valorMoiObjeto = $fetch2['valor_moi'];
              $porceDepre = $fetch1['porce_depreciacion'];
              $porce = $porceDepre/100;
              $moiAnualObjeto = $valorMoiObjeto*$porce;
              $moiMensualObjeto = $moiAnualObjeto/12;

              $datosIndiv = ["fecha_compra"=>$fetch2['fecha_adquisicion'],
              "valor_moi_Origi"=>$fetch2['valor_moi'],"moi_mensual"=>$moiMensualObjeto,
              "activo"=>$fetch2['articulo_activo'],"fecha_baja"=>$fetch2['fecha_baja']];

              $datos[$iDa]['DATOS'][] = $datosIndiv;

            }
            $iDa++;
          }//findel while
          print_r($datos);
          //echo "<br>".count($datos[0]['DATOS'])."<br>";
          $moisAuxiliar = "";
          for($iM = 0; $iM <= $meses; $iM++){
            $fechaVa2 = date("Y-m-d",strtotime($nuevaFecha."+1 ".$auxMes." month"));
            $fech = explode("-",$fechaVa2);
            $fechaTabla = $fech[0]." - ".$mesArray[$fech[1]];
            //echo $datos[0]['nombre_clasificacion'];
            $auxTabla = "";
            echo "<br><br><br>Aqui<br>";
            print_r($valorClasi)."<br>Valor <br>";
            print_r($clasificaciones);
            
            for($iCla = 0; $iCla < count($clasificaciones); $iCla++){
              echo count($datos[$iCla]['DATOS'])."---<br>";
              for($iDat = 0; $iDat <= count($datos[$iCla]); $iDat++){
                echo $datos[$iCla]['DATOS'][$iDat]['moi_mensual']."<br>";
                $valorClasi[$clasificaciones[$iCla]] = $valorClasi[$clasificaciones[$iCla]]+$datos[$iCla]['DATOS'][$iDat]['moi_mensual'];
                print_r($valorClasi)."<br>";
              }
              
              $valortabla =  $valorClasi[$clasificaciones[$iCla]];
              
              $auxTabla .=  "<td>$valortabla</td>";
            }
            

            $contenidoTabla .= "<tr>
            <td>$auxMes</td>
            <td>$fechaTabla</td>
            $auxTabla
            </tr>";
            $auxMes++;
          }

          echo "<table>
          <thead>
            <tr>
            <th>No.</th>
            <th>Mes</th>
            $headTable
            </tr>
          </thead>
          <tbody>
          $contenidoTabla
          </tbody>
          </table>";
        }else{
          echo "DataError|Las fechas no deben ser las mismas";
        }
      }else{
        echo "DataError|La fecha final no debe ser mayor a la de inicio";
      }
    }else{
      //datos vacios
      echo "DataError|Las fechas no deben estar vacias";
    }
  }else{

  }
}else{
  header('location:../index.php');
}
?>
