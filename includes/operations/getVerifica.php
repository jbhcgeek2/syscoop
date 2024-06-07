<?php 
session_start();
if(!empty($_SESSION['usNamePlataform'])){
    include('../_con.php');
    header('Content-Type: text/html; charset=UTF-8');

    if(!empty($_POST['estatusVeri'])){
        $estatus = $_POST['estatusVeri'];

        $sql = "SELECT a.id_verifica,a.socio_num,a.ap_paterno,a.ap_materno,a.nombres,
        b.localidad,c.nombre FROM VERIFICACION a INNER JOIN DOMICILIOS b 
        ON b.FId_verifica = a.id_verifica INNER JOIN MUNICIPIOS c ON c.id = b.municipio 
        WHERE a.estatus = '$estatus' AND b.tipo_domicilio = 'SOCIO'";
        $query = mysqli_query($conexion, $sql);
        $error = mysqli_error($conexion);
        if($query){
            if(mysqli_num_rows($query) > 0){
                $datos = [];
                $i = 0;
                while($fetch = mysqli_fetch_assoc($query)){
                    $datos[$i] = $fetch;
                    $i++;
                }//fin del while

                echo json_encode($datos);
            }else{
                //no se obtubieron resultados
                echo "noData";
            }
        }else{
            //error al consultar las verificaciones
            echo "dataError|ocurrio un error al obtener los resultados: ".$error;
        }
    }else{
        //operacion no permitida

    }
}

?>