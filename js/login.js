if(localStorage.getItem('name')){
    console.log('Sesion detectada');
}else{
    console.log('no definida');
}


function senData(){
    let usName = document.querySelector('#userName').value;
    let usPass = document.querySelector('#contra').value;
    let usRem = "";
    if(document.getElementById("remem").checked){
        usRem = "Chekeado";
    }else{
        usRem = "NoChekeado";
    }

    if(usName.length > 2 && usPass.length > 2){
        //continuamos con el proceso de envio de datos
        usName = usName.trim();
        usPass = usPass.trim();

        let invalid = ['~','*','@','#','%','$','&',':',';','/','+','=','-','{','}','.',',','[',']','^',
        'ü','é','á','í','ó','ú','ñ','Á','É','Í','Ó','Ú','Ü','Ñ','¿','!'];

        let nInvalid = 0;
        let tieneMal;
        let tieneMal2;

        for(let i =  0; i < invalid.length; i++){
            let tieneMal = usName.indexOf(invalid[i]);
            if(tieneMal > -1){
                nInvalid = 1;
                break;
            }
        }//fin del for user

        for(let x = 0; x < invalid.length; x++){
            let tieneMal2 = usPass.indexOf(invalid[x]);
            if(tieneMal2 > -1){
                nInvalid = 1;
                break;
            }
        }//fin del for password

        if(nInvalid == 0){
            //podemos hacer envio del XMLHTTP
            

            let datos = new FormData();
            datos.append('name',usName);
            datos.append('pw',usPass);
            datos.append('rem',usRem);


            let envio = new XMLHttpRequest(); 
            envio.open('POST','../includes/login.php',false);
            envio.send(datos);
            console.log(envio.status);
            if(envio.status == 200){
                let res = envio.responseText;

                if(res == "loginSuccess"){
                    //inicio de sesion correcto
                    window.location = "../control.php";
                }else{
                    let res2 = res.split("|");
                    if(res2[0] == "loginSuccess"){
                        let dato1 = res2[1];
                        let dato2 = res[2];
                        localStorage.setItem('name',dato1);
                        localStorage.setItem('name2',dato2);
                        
                        window.location = "../control.php";
                    }else{
                        //datos incorrectos
                        let res2 = res.split("|");
                        let error = res2[1];
                        
                        swal.fire(
                            'Error de Inicio',
                            'Verificar: '+error,
                            'error'
                        )
                    }
                }
            }else{
                swal.fire(
                    'Servidor Inalcanzable',
                    'No fue posible establecer comunicación con el servidor',
                    'error'
                )
                
            }


        }else{
            swal.fire(
                'Datos Incorrectos',
                'La información capturada contiene caracteres inválidos',
                'warning'
            )
        }
    }else{
        //ocurrio un error al ingresar el usuario o password
        swal.fire(
            'Datos Incorrectos',
            'Verifica los datos ingresados',
            'warning'
        )
    }
}


let envia1 = document.querySelector('#btnSend');
envia1.addEventListener('click', function(){
    senData();
})

let envia2 = document.querySelector('#contra');
envia2.addEventListener('keyup',function(e){
    if(e.key == 'Enter'){
        //enviamos el formulario
        senData();
    }
})