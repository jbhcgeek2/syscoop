<?php 
session_start();

if(!empty($_SESSION['usNamePlataform'])){
    include('../_con.php');
    header('Content-Type: text/html; charset=UTF-8');
    
    if(!empty($_POST['envioFormulario']) && $_POST['envioFormulario'] == "completeOperations"){
        //verificamos que accion vamos a realizar
        $estatus = $_POST['EstatusVerifica'];
        $id_verifica = $_POST['idenVeri'];
        $usuario = $_SESSION['usNamePlataform'];
        $usuarioVeri = $_POST['usuarioVerifica'];
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');

        switch ($estatus){
            case 'ACEPTADA':
                if(!empty($usuarioVeri)){
                    $sql = "UPDATE VERIFICACION SET estatus = 'ACEPTADA', USUARIO_ACTUALIZA = '$usuario', 
                    usuario_verifica = '$usuarioVeri',fecha_asigna = '$fecha',hora_asigna = '$hora' WHERE id_verifica = '$id_verifica'";
                    $query = mysqli_query($conexion, $sql);
                    if($query){
                        //insertamos un comentario
                        $msg = "Se acepta la verificacion y se asigna a ".$usuarioVeri;
                        $sql2 = "INSERT INTO COMENTARIO (comentario,usuario_nombre,
                        fecha_comentario,hora_comentario,verificacion_id) VALUES ('$msg','$usuario',
                        '$fecha','$hora','$id_verifica')";
                        $query2 = mysqli_query($conexion, $sql2);
                        if($query2){
                            echo "operationComplete";
                        }else{
                            echo "dataError|Se completo la tarea con errores, reportar a sistemas";   
                        }
                    }else{
                        //error al actualizart la base de datos
                        echo "dataError|No se actualizo la base de datos, reportar a sistemas";
                    }
                }else{
                    //verificador no asignado
                    echo "dataError|Verificador no seleccionado.";
                }
                break;
            case 'CANCELADA':
                $motivoCancela = $_POST['motivoCancela'];
                $sql = "UPDATE VERIFICACION SET estatus = 'CANCELADA',USUARIO_ACTUALIZA = '$usuario' 
                WHERE id_verifica = '$id_verifica'";
                $query = mysqli_query($conexion, $sql);
                if($query){
                    //insertamos el comentario
                    $sql2 = "INSERT INTO COMENTARIO (comentario,usuario_nombre,
                    fecha_comentario,hora_comentario,verificacion_id) VALUES ('$motivoCancela','$usuario',
                    '$fecha','$hora','$id_verifica')";
                    $query2 = mysqli_query($conexion, $sql2);
                    if($query2){
                        //se completo el proceso correctamente
                        echo "operationComplete";
                    }else{
                        echo "dataError|Se completo la tarea con errores, reportar a sistemas";
                    }
                }else{
                    //reportamos un error
                    echo "dataError|No se actualizo la base de datos, reportar a sistemas";
                }
                break;
            
            default:
                # code...
                break;
        }//fin del switch
    }elseif(!empty($_POST['newComent'])){
        $comentario = $_POST['newComent'];
        $idVerifica = $_POST['idenVeriComent'];
        $usuario = $_SESSION['usNamePlataform'];
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');

        $sql = "INSERT INTO COMENTARIO (comentario,usuario_nombre,
        fecha_comentario,hora_comentario,verificacion_id) VALUES ('$comentario','$usuario',
        '$fecha','$hora','$idVerifica')";
        $query = mysqli_query($conexion, $sql);
        if($query){
            //se completo el proceso correctamente
            echo "operationComplete";
        }else{
            echo "dataError|Error al guardar el comentario, si el problema persiste favor de reportar a sistemas";
        }
        

    }
}
?>