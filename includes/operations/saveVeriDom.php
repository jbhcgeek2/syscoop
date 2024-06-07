<?php 
session_start();

//verificamos la existenbcia de la sesion

if(ISSET($_SESSION['usNamePlataform'])){
    include('../_con.php');
    header('Content-Type: text/html; charset=UTF-8');

    if(!empty($_POST['nombrePer'])){
        $nombre = $_POST['nombrePer'];
        $paterno = $_POST['apPatPer'];
        $materno = $_POST['apMatPer'];
        $calle = $_POST['callePer'];
        $noExt = $_POST['noCallePer'];
        $noint = $_POST['noCalleIntPer'];
        $colonia = $_POST['colPer'];
        $cp = $_POST['cpPer'];
        $celular = $_POST['celper'];
        $socio = $_POST['idenPer'];
        $horario = $_POST['horarioPref'];
        $referencias = $_POST['refDomPer'];
        $lat = $_POST['latDomPer'];
        $long = $_POST['lngDomPer'];
        $veriAval = $_POST['avalVeriPer'];
        $usuario = $_SESSION['usNamePlataform'];

        $estado = $_POST['estadoPer'];
        $municipio = $_POST['municipioPer'];
        $localidad = $_POST['localidadPer'];

        $nAvales = $_POST['avalNumbers'];

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');

        //verificamos si contienen avales o no

        if($veriAval == 1){
            //se indicaron avales
            $tieneAvales = "si";
        }else{
            //no se indicaron avales
            $tieneAvales = "no";
        }

        //insertamos la verirficacion
        $sql = "INSERT INTO VERIFICACION (socio_num,nombres,ap_paterno,ap_materno,
        celular,horario_visita,estatus,usuario_registra,verifica_aval,fecha_registro,
        hora_registro) VALUES ('$socio','$nombre','$paterno','$materno','$celular',
        '$horario','PENDIENTE','$usuario','$tieneAvales','$fecha','$hora')";
        $query = mysqli_query($conexion, $sql);
        $error = mysqli_error($conexion);
        if($query){
            $idVerifica = mysqli_insert_id($conexion);
            //insertamos el domicilio de la verificacion
            $sql2 = "INSERT INTO DOMICILIOS (estado,municipio,localidad,colonia,
            cp,calle,entre_calles,referencias,numero,interior,latitud,longitud,
            tipo_domicilio,FId_verifica) VALUES ('$estado','$municipio','$localidad',
            '$colonia','$cp','$calle','$entreCalles','$referencias','$noExt','$noint',
            '$lat','$long','SOCIO','$idVerifica')";
            $query2 = mysqli_query($conexion, $sql2);
            $error = mysqli_error($conexion);
            if($query2){
                //verificamos si tiene avales
                if($veriAval == 1){
                    $verificaAvalesInsert = 0;
                    for($i = 1; $i <= $nAvales; $i++){
                        $nombreAval = $_POST['nameAval'.$i];
                        $paternoAval = $_POST['patAval'.$i];
                        $maternoAval = $_POST['matAval'.$i];
                        $celAval = $_POST['celAval'.$i];

                        $estadoAval = $_POST['estadoAval'.$i];
                        $municipioAval = $_POST['municipioAval'.$i];
                        $localidadAval = $_POST['localidadAval'.$i];
                        $calleAval = $_POST['calleAval'.$i];
                        $numExtAval = $_POST['numExtAval'.$i];
                        $numIntAval = $_POST['numIntAval'.$i];
                        $entreCalleAval = $_POST['entreCalleAval'.$i];
                        $colAval = $_POST['colAval'.$i];
                        $cpAval = $_POST['cpAval'.$i];
                        $latitud = $_POST['latDomAval'.$i];
                        $longitud = $_POST['lngDomAval'.$i];
                        $referenciAval = $_POST['refDomAval'.$i];
                        $tipoDom = "AVAL".$i;

                        $sqlAval = "INSERT INTO AVALES (nombre_aval,paterno_aval,materno_aval,cel_aval,FId_verificacion)
                        VALUES ('$nombreAval','$paternoAval','$maternoAval','$celAval','$idVerifica')";
                        $queryAval = mysqli_query($conexion, $sqlAval);
                        $error = mysqli_error($conexion);
                        if($queryAval){
                            //ahora insertamos el domicilio del aval
                            $idAval = mysqli_insert_id($conexion);

                            $sqlDomAval = "INSERT INTO DOMICILIOS (estado,municipio,localidad,colonia,
                            cp,calle,entre_calles,referencias,numero,interior,latitud,longitud,
                            tipo_domicilio,FId_verifica) VALUES ('$estadoAval','$municipioAval',
                            '$localidadAval','$colAval','$cpAval','$calleAval','$entreCalleAval','$referenciAval',
                            '$numExtAval','$numIntAval','$latitud','$longitud','$tipoDom','$idAval')";
                            $queryDomAval = mysqli_query($conexion, $sqlDomAval);
                            $error = mysqli_error($conexion);
                            if($queryDomAval){
                                $verificaAvalesInsert++;
                            }else{
                                //no se inserto el domicilio del aval
                            }
                        }

                    }//fin del for para insertar
                    if($nAvales == $verificaAvalesInsert){
                        echo "operationComplete";
                    }else{
                        echo "dataError|Error al insertar los avales, verificar con sistemas: ".$error;
                    }
                }else{
                    //podemos dar por terminado el registro de verificacion
                    echo "operationComplete";
                }
            }else{
                //error al guardar el domicilio de la verificacion
            }

        }else{
            //error al insertar la verificacion
        }

    }elseif(!empty($_POST['estadoCheck'])){
        //sin datos de formulario
        $estado = $_POST['estadoCheck'];

        $sql = "SELECT * FROM MUNICIPIOS WHERE estado = '$estado'";
        $query = mysqli_query($conexion, $sql);
        $error = mysqli_error($conexion);
        if($query){
            $data = [];
            $i=0;
            while($fetch = mysqli_fetch_assoc($query)){
                $data[$i] = $fetch;
                $i++;
            }//fin del while
    
            echo json_encode($data);
        }else{
            //error al consultar los municipios
            echo "dataError|".$error;
        }
    }elseif(!empty($_POST['municipioCheck'])){
        $municipio = $_POST['municipioCheck'];

        $sql = "SELECT nombre FROM COLONIAS WHERE municipio = '$municipio'";
        $query = mysqli_query($conexion, $sql);
        $error = mysqli_error($conexion);
        if($query){
            $data = [];
            $i =0;
            while($fetch = mysqli_fetch_assoc($query)){
                $data[$i] = $fetch;
                $i++;
            }//fin del while

            echo json_encode($data);
        }else{
            //ocurrio un error en la comunicacion
            echo "dataError|".$error;
        }
    }else{
        //sin operacion asignada
    }
}else{

}

?>