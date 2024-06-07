<?php
error_reporting(E_ALL);
include("_con.php"); 

if($conexion){
    session_start();
    if(!empty($_POST['name']) && !empty($_POST['pw'])){
        //escaparemos los datos
        include('operations/encrp.php');
        $user = htmlentities($_POST['name']);
        $pass = htmlentities($_POST['pw']); 
        $remPass = $_POST['rem'];
        
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$user'";
        
        try{
            $query = mysqli_query($conexion, $sql);

            if(mysqli_num_rows($query) == 1){
                //verificamos los datos del usuario
                $fetch = mysqli_fetch_assoc($query);

                $usDb = $fetch['nombre_usuario'];
                $pwDb = $fetch['contra_usuario'];
                $activo = $fetch['usuario_activo'];

                if($user == $usDb && $pass == $pwDb){
                    //los datos de usuario son correcto, verificamos si esta activo
                    if($activo == 1){
                        $_SESSION['usNamePlataform'] = $usDb;
                        if($remPass == "Chekeado"){
                            $newUs = setCodeKey($usDb);
                            $newPw = setCodeKey($pwDb);
                            echo "loginSuccess|".$newUs."|".$newPw;
                        }else{
                            echo "loginSuccess";
                        }
                    }else{
                        echo "DataError|Usuario inhabilitado";
                    }
                }else{
                    ///datos incorrectos
                    echo "DataError|Usuario o contrasena incorrectos";
                }
            }else{
                //sin resultados en la base de datos
                echo "DataError|Usuario inexistente";
            }
        }catch(Throwable $th){
            echo "DataError|Error de comunicacion con la base de datos: ".$th;
        }

    }else{
        //sin datos de conexion
        header('location:../index.php');
    }
}else{
    echo "dataError|Error de Conexión.";
}
?>