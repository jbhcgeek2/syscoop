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
    $totalTotal = 0;

    $mesArray = ["01"=>"Enero","02"=>"Febrero","03"=>"Marzo","04"=>"Abril",
    "05"=>"Mayo","06"=>"Junio","07"=>"Julio","08"=>"Agosto","09"=>"Septiembre",
    "10"=>"Octubre","11"=>"Noviembre","12"=>"Diciembre"];
    $mesAnumero =["01"=>"1","02"=>"2","03"=>"3","04"=>"4",
    "05"=>"5","06"=>"6","07"=>"7","08"=>"8","09"=>"9",
    "10"=>"10","11"=>"11","12"=>"12"];
    $coloresArray = ["indigo lighten-5","cyan lighten-5","teal lighten-5","light-blue lighten-5","blue lighten-5"];

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
            $colorFondoCla = $coloresArray[$iDa];
            $headTable .= "<th colspan='5' class='$colorFondoCla'>$clasi</th>";

            $clasificaciones[] = $clasi;
            //$valorClasi[$clasi] = "0";
            $valorClasi[$clasi]['Acumulado'] = "0";
            $valorClasi[$clasi]['Mensual'] = "0";
            $valorClasi[$clasi]['Restante'] = "0";
            $valorClasi[$clasi]['Global'] = "0";
            $valorClasi[$clasi]['Anual'] = "0";

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
          //print_r($datos);
          //echo "<br>".count($datos[0]['DATOS'])."<br>";
          $moisAuxiliar = "";
          $valortabla = "";
          for($iMes = 0; $iMes <= $meses; $iMes++){
            $fechaVa2 = date("Y-m-d",strtotime($nuevaFecha."+1 ".$auxMes." month"));
            $fech = explode("-",$fechaVa2);
            $fechaTabla = $fech[0]." - ".$mesArray[$fech[1]];
            $auxTabla = "";
            
            for($iCla = 0; $iCla < count($clasificaciones); $iCla++){
              //echo "<br>Va en el: ".$iCla.$clasificaciones[$iCla]."<br>";
              $claseTD = "";
              $toolTiped = "";
              if(isset($datos[$iCla]['DATOS'])){
                //echo "Tiene ".count($datos[$iCla]['DATOS'])." datos<br>";
                $valorClasi[$clasificaciones[$iCla]]['Mensual'] = 0;
                for($iDat = 0; $iDat < count($datos[$iCla]['DATOS']); $iDat++){
                  //echo "Moi Mensual: ".$datos[$iCla]['DATOS'][$iDat]['moi_mensual']."<br>";
                  //calculamos el tiempo que tiene de vida el producto
                  $moiMensual = $datos[$iCla]['DATOS'][$iDat]['moi_mensual'];
                  //Fecha de compra del producto
                  $fecIni = $datos[$iCla]['DATOS'][$iDat]['fecha_compra'];
                  $estatusProd = $datos[$iCla]['DATOS'][$iDat]['activo'];
                  $fecBaja = $datos[$iCla]['DATOS'][$iDat]['fecha_baja'];
                  $montoMoiOriginal = $datos[$iCla]['DATOS'][$iDat]['valor_moi_Origi'];
                  if(!empty($fecBaja)){
                    $fecBaj2 = explode("-",$fecBaja);
                    $fecBaja2 = $fecBaj2[0]."-".$fecBaj2[1]."-01";
                  }
                  $mesesDeVida = $montoMoiOriginal/$moiMensual;
                  $auxFecIni = explode("-",$fecIni);
                  $fecIni = $auxFecIni[0]."-".$auxFecIni[1]."-01";
                  $fecAct = $fech[0]."-".$fech[1]."-01";
                  $fecAct2 = $fech[0]."-01-01";
                  $mesAuxNum = $mesAnumero[$fech[1]];

                  
                  $fechaInicialCompra = new DateTime($fecIni);
                  $fechaCiclo = new DateTime($fecAct);
                  $fechaCiclo2 = new DateTime($fecAct2);
                  $difMes2 = $fechaInicialCompra->diff($fechaCiclo);
                  $diferenciaMeses = ($difMes2->y*12)+$difMes2->m;
                  $difMes3 = $fechaCiclo->diff($fechaCiclo2);
                  $diferecniaMeses2 = (($difMes3->y*12)+$difMes3->m);
                  
                  //echo $diferecniaMeses2."<br>";
                  //verificamos si la fecha de compra ya se cumplio para realizar la suma
                  if(strtotime($fecIni) < strtotime($fecAct)){
                    //ya paso la fecha, podemos sumar el moi
                    if($mesAuxNum == 1 && $iDat == 0){
                      $valorClasi[$clasificaciones[$iCla]]['Mensual'] = 0;
                    }
                    //antes de sumar el moi verificamos que el producto no este como baja
                    if($estatusProd == 2 ){
                      //verificamos si la fecha de baja es la actual o ya paso
                      if(strtotime($fecAct) < strtotime($fecBaja2)){
                        //el producto esta de baja, pero aun asi la sumamos por que aun no
                        //se cumplesun periodo
                        $valorClasi[$clasificaciones[$iCla]]['Mensual'] = $valorClasi[$clasificaciones[$iCla]]['Mensual'] + $moiMensual;
                        if($iMes == 0){
                          $moiSumar = $moiMensual * $diferenciaMeses;
                          $moiAnualReset = $moiMensual * ($diferecniaMeses2+1);

                          $valorClasi[$clasificaciones[$iCla]]['Acumulado'] = $valorClasi[$clasificaciones[$iCla]]['Acumulado'] + $moiSumar;
                          $valorClasi[$clasificaciones[$iCla]]['Restante'] = $valorClasi[$clasificaciones[$iCla]]['Restante'] + $montoMoiOriginal;
                          $valorClasi[$clasificaciones[$iCla]]['Restante'] =  $valorClasi[$clasificaciones[$iCla]]['Restante'] - $moiSumar;
                          $valorClasi[$clasificaciones[$iCla]]['Global'] = $valorClasi[$clasificaciones[$iCla]]['Global'] + $montoMoiOriginal;
                          $valorClasi[$clasificaciones[$iCla]]['Anual'] = $valorClasi[$clasificaciones[$iCla]]['Anual'] + $moiAnualReset;
                        }else{
                          $valorClasi[$clasificaciones[$iCla]]['Acumulado'] = $valorClasi[$clasificaciones[$iCla]]['Acumulado'] + $moiMensual;
                          $valorClasi[$clasificaciones[$iCla]]['Anual'] = $valorClasi[$clasificaciones[$iCla]]['Anual'] + $moiMensual;
                          
                          if($diferenciaMeses == 1){
                            $moiSumar = $moiMensual * $diferenciaMeses;
                            $valorClasi[$clasificaciones[$iCla]]['Restante'] = $valorClasi[$clasificaciones[$iCla]]['Restante'] + $montoMoiOriginal;
                            $valorClasi[$clasificaciones[$iCla]]['Restante'] =  $valorClasi[$clasificaciones[$iCla]]['Restante'] - $moiSumar;
                            $valorClasi[$clasificaciones[$iCla]]['Global'] = $valorClasi[$clasificaciones[$iCla]]['Global'] + $montoMoiOriginal;
                          }else{
                            $valorClasi[$clasificaciones[$iCla]]['Restante'] = $valorClasi[$clasificaciones[$iCla]]['Restante'] - $moiMensual;
                            
                          }
                        }
                      }elseif(strtotime($fecAct) == strtotime($fecBaja2)){
                        //podemos hacer el calculo para sumar lo restante
                        $auxMontoSuma = $moiMensual * $diferenciaMeses;
                        $auxMontoResta = $montoMoiOriginal - $auxMontoSuma;
                        $restarMoi = $moiMensual  * ($diferenciaMeses-1);
                        //$valorClasi[$clasificaciones[$iCla]]['Acumulado'] = $valorClasi[$clasificaciones[$iCla]]['Acumulado'] + $auxMontoResta+$moiMensual;
                        //$valorClasi[$clasificaciones[$iCla]]['Acumulado'] = $valorClasi[$clasificaciones[$iCla]]['Acumulado'] + $moiMensual;
                        $valorClasi[$clasificaciones[$iCla]]['Acumulado'] = $valorClasi[$clasificaciones[$iCla]]['Acumulado'] - $restarMoi;
                        $valorClasi[$clasificaciones[$iCla]]['Restante'] = $valorClasi[$clasificaciones[$iCla]]['Restante'] - ($auxMontoResta+$moiMensual);
                        $valorClasi[$clasificaciones[$iCla]]['Global'] = $valorClasi[$clasificaciones[$iCla]]['Global'] - $montoMoiOriginal;
                        $claseTD = "pink darken-1 white-text tooltipped";
                        $toolTiped = "data-position='bottom' data-tooltip='Baja de Producto'";
                      }elseif(strtotime($fecAct) > strtotime($fecBaja2)){
                        if($iMes == 0){
                          $moiSumar = $moiMensual * $diferenciaMeses;
                          //$valorClasi[$clasificaciones[$iCla]]['Acumulado'] = $valorClasi[$clasificaciones[$iCla]]['Acumulado'] + $montoMoiOriginal;
                          //$valorClasi[$clasificaciones[$iCla]]['Acumulado'] = $valorClasi[$clasificaciones[$iCla]]['Acumulado'] + $moiSumar;
                        }
                      }else{
                        //ya no aplica hacer nada
                      }
                    }else{
                      //no esta de baja
                      //calculamos la diferencia de meses para ver si ya paso su tiempo de vida
                      //para dejarlo de sumar y tomar en cuenta
                      if($diferenciaMeses <= $mesesDeVida){
                        $valorClasi[$clasificaciones[$iCla]]['Mensual'] = $valorClasi[$clasificaciones[$iCla]]['Mensual'] + $moiMensual;
                        if($iMes == 0){
                          $moiSumar = $moiMensual * $diferenciaMeses;
                          $moiAnualReset = $moiMensual * ($diferecniaMeses2+1);

                          $valorClasi[$clasificaciones[$iCla]]['Acumulado'] = $valorClasi[$clasificaciones[$iCla]]['Acumulado'] + $moiSumar;
                          $valorClasi[$clasificaciones[$iCla]]['Restante'] = $valorClasi[$clasificaciones[$iCla]]['Restante'] + $montoMoiOriginal;
                          $valorClasi[$clasificaciones[$iCla]]['Restante'] =  $valorClasi[$clasificaciones[$iCla]]['Restante'] - $moiSumar;
                          $valorClasi[$clasificaciones[$iCla]]['Global'] = $valorClasi[$clasificaciones[$iCla]]['Global'] + $montoMoiOriginal;
                          $valorClasi[$clasificaciones[$iCla]]['Anual'] = $valorClasi[$clasificaciones[$iCla]]['Anual'] + $moiAnualReset;
                        }else{
                          $valorClasi[$clasificaciones[$iCla]]['Acumulado'] = $valorClasi[$clasificaciones[$iCla]]['Acumulado'] + $moiMensual;
                          $valorClasi[$clasificaciones[$iCla]]['Anual'] = $valorClasi[$clasificaciones[$iCla]]['Anual'] + $moiMensual;
                          $moiAnualReset = $moiMensual * $diferecniaMeses2;
                          
                          

                          if($diferenciaMeses == 1){
                            $moiSumar = $moiMensual * $diferenciaMeses;
                            $valorClasi[$clasificaciones[$iCla]]['Restante'] = $valorClasi[$clasificaciones[$iCla]]['Restante'] + $montoMoiOriginal;
                            $valorClasi[$clasificaciones[$iCla]]['Restante'] =  $valorClasi[$clasificaciones[$iCla]]['Restante'] - $moiSumar;
                            $valorClasi[$clasificaciones[$iCla]]['Global'] = $valorClasi[$clasificaciones[$iCla]]['Global'] + $montoMoiOriginal;
                            
                          }else{
                            $valorClasi[$clasificaciones[$iCla]]['Restante'] = $valorClasi[$clasificaciones[$iCla]]['Restante'] - $moiMensual;
                            
                          }
                        }
                      }
                      
                    }


                    
                    //$moiMensualAnual = $moiMensual*$mesAuxNum;
                    
                    
                  
                    
                    //$valorClasi[$clasificaciones[$iCla]] =  $moiSumar;
                  }else{
                    //aun no podemos sumar el moi
                  }
                  //echo "--<br>";
                  
                }//fin for $iDat

                if($iMes == 0){
                  //echo "<br>".$valorClasi[$clasificaciones[$iCla]]['Mensual']." = ".$valorClasi[$clasificaciones[$iCla]]['Mensual']." * ".$mesAuxNum."<br>";
                  $valorClasi[$clasificaciones[$iCla]]['Mensual'] = $valorClasi[$clasificaciones[$iCla]]['Mensual'];
                }

              }else{
                //echo "No tiene Tiene Datos<br>";
              }
              $moiRestante = number_format($valorClasi[$clasificaciones[$iCla]]['Restante'],2);
              $moiAnual = number_format($valorClasi[$clasificaciones[$iCla]]['Anual'],2);
              $moiMensualAnual = number_format($valorClasi[$clasificaciones[$iCla]]['Mensual'],2);
              $valortabla =  number_format($valorClasi[$clasificaciones[$iCla]]['Acumulado'],2);
              $valorMoiGlobal = number_format($valorClasi[$clasificaciones[$iCla]]['Global'],2);
              $colorFondoClasi = $coloresArray[$iCla];
              $totalTotal = $totalTotal + $valorClasi[$clasificaciones[$iCla]]['Global'];
              $tooltipClasi = "";
              $auxTabla .=  "
              <td class='$colorFondoClasi $claseTD' $toolTiped><strong>$valorMoiGlobal</strong></td>
              <td class='$colorFondoClasi $claseTD' $toolTiped><strong>$moiAnual</strong></td>
              <td class='$colorFondoClasi $claseTD' $toolTiped>$moiMensualAnual</td>
              <td class='$colorFondoClasi $claseTD' $toolTiped>$moiRestante</td>
              <td class='$colorFondoClasi $claseTD' $toolTiped>$valortabla</td>";
              if($fech[1] == "12"){
                $valorClasi[$clasificaciones[$iCla]]['Anual'] = 0;
              }
            }

            $totalTotal = 0;
            for($iT = 0; $iT < count($valorClasi); $iT++){
              $totalTotal = $totalTotal + $valorClasi[$clasificaciones[$iT]]['Mensual'];
            }
            $totalTotal2 = number_format($totalTotal,2);
            $contenidoTabla .= "<tr>
            <td>$auxMes</td>
            <td>$fechaTabla</td>
            <td>$totalTotal2</td>
            $auxTabla
            </tr>";
            $auxMes++;
          }
          
          $auxiHeadTable = "<th>No.</th><th>Mes</th><th>Total Global <br>Mensual</th>";
          for($iHead = 0; $iHead < count($clasificaciones); $iHead++){
            $colorFondoClasiFi = $coloresArray[$iHead];
            $nombreClasi = $clasificaciones[$iHead];
            $auxiHeadTable .= "
            <th class='$colorFondoClasiFi tooltipped' data-position='top' data-tooltip='$nombreClasi'>MOI <br>global</th>
            <th class='$colorFondoClasiFi tooltipped' data-position='top' data-tooltip='$nombreClasi'>MOI <br>Acu. Men.</th>
            <th class='$colorFondoClasiFi tooltipped' data-position='top' data-tooltip='$nombreClasi'>Monto a <br>depreciar Mens</th>
            <th class='$colorFondoClasiFi tooltipped' data-position='top' data-tooltip='$nombreClasi'>Monto <br>Restante</th>
            <th class='$colorFondoClasiFi tooltipped' data-position='top' data-tooltip='$nombreClasi'>Depreciacion <br>acumulada</th>";
          }
          
          echo "
          <div class='card-content' style='overflow-y:scroll; height: 450px;white-space: nowrap;'>
            <table class='centered'>
            <thead>
              <tr>
              <tr>
              <th></th>
              <th></th>
              <th></th>
              $headTable
              </tr>
              <tr>$auxiHeadTable</tr>
            </thead>
            <tbody>
            $contenidoTabla
            </tbody>
            </table>
          </div>";

          
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
