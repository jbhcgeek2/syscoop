let campoSel = document.getElementById("empleadoUser");

campoSel.addEventListener("change",function(){
  let campo = document.getElementById("empleadoUser").value;
  if(campo == "RegNewEmp"){
    //mostramos loscampos del nuevo empleado
    document.getElementById("dataNewEmp").classList.remove("hide");
  }else{
    document.getElementById("dataNewEmp").classList.add("hide");
    let nombreEmp = document.getElementById("nameEmpleado").value = "";
    let paternoEmp = document.getElementById("patEmpleado").value = "";
    let maternoEmp = document.getElementById("matEmpleado").value = "";
    let mailEmp = document.getElementById("mailEmpleado").value = "";
    let celEmp = document.getElementById("celEmpleado").value = "";
    let depEmp = document.getElementById("depEmpleado").value = "";
    let activoEmp = document.getElementById("empleadoActivo").value = "";
  }
  
})

let btnEnviarEmpleado = document.getElementById("saveNewUser");

btnEnviarEmpleado.addEventListener("click", function(){
  //verificamos que contebnga los campos
  let empleado = document.getElementById("empleadoUser").value;
  let pass1 = document.getElementById("pass1").value;
  let pass2 = document.getElementById("pass2").value;
  let userName = document.getElementById("nombreUsuario").value;
  let valida = 0;
  
  if(pass1 == pass2){
    console.log(pass1.length);
    if(pass1.length >= 6 && pass2.length >= 6){
      if(userName.length >= 3){
        //comenzamos con la validacion de empleado
        if(empleado == "RegNewEmp"){
          //se registrara un empleado
          let nombreEmp = document.getElementById("nameEmpleado").value;
          let paternoEmp = document.getElementById("patEmpleado").value;
          let maternoEmp = document.getElementById("matEmpleado").value;
          let mailEmp = document.getElementById("mailEmpleado").value;
          let celEmp = document.getElementById("celEmpleado").value;
          let depEmp = document.getElementById("depEmpleado").value;
          let activoEmp = document.getElementById("empleadoActivo").value;

          if(nombreEmp != "" && mailEmp != "" && celEmp != "" && depEmp != "" && activoEmp != ""){
            //hacemos el envio del formulario
            let datos = new FormData();
            datos.append("nombreNewEmp",nombreEmp);
            datos.append("paternoNewEmp",paternoEmp);
            datos.append("maternoNewEmp",maternoEmp);
            datos.append("mailNewEmp",mailEmp);
            datos.append("celNewEmp",celEmp);
            datos.append("depNewEmp", depEmp);
            datos.append("activoNewEmp",activoEmp);
            datos.append("userNameNew",userName);
            datos.append("passNewUser",pass1);

            let permisosCat = ["verInventario","agregarInventario","editarInventario",
            "verProveedsores","agregarProveedores","editarProveedores"];
            let permisosCont = ["verControles","verMovimientos","verDepreciacion"];
            let permisosOper = ["actualizaFoto"];

            for(let perCat = 0; perCat < permisosCat.length; perCat++){
              let idAuxCat = permisosCat[perCat];
              let campoAuxFor1 = document.getElementById(idAuxCat).checked;
              if(campoAuxFor1){
                datos.append(idAuxCat,"1");
              }else{
                datos.append(idAuxCat,"0");
              }
            }//fi del forcatalogo

            //Permisos de la seccion de controles
            for(let perCon = 0; perCon < permisosCont.length; perCon++){
              let idAuxCon = permisosCont[perCon];
              let campoAuxFor2 = document.getElementById(idAuxCon).checked;
              if(campoAuxFor2){
                datos.append(idAuxCon,"1");
              }else{
                datos.append(idAuxCon,"0");
              }
            }//fi del perCon

            //permisos de la seccion de operaciones
            for(let perOper = 0; perOper < permisosOper.length; perOper++){
              let idAuxOp = permisosOper[perOper];
              let campoAuxFor3 = document.getElementById(idAuxOp).checked;
              if(campoAuxFor3){
                datos.append(idAuxOp,"1");
              }else{
                datos.append(idAuxOp,"0");
              }
            }//fin del perOper


            let envio = new XMLHttpRequest();
            envio.open("POST","includes/operations/altaUsuarios.php",false);
            envio.send(datos);
            if(envio.status == 200){
              let respuesta = envio.responseText;
              if(respuesta == "operationSucess"){
                Swal.fire(
                  'Operacion Realizada',
                  'Se inserto correctamente el usuario',
                  'success'
                ).then(function(){
                  location.reload();
                })
              }else{
                let error = respuesta.split("DataError|");
                error = error[1];
                Swal.fire(
                  'Error',
                  'Ocurrio un error al realizar la operacion: '+error,
                  'error'
                )
              }
            }else{
              Swal.fire(
                'Cuidado',
                'Verifica tu conexion a internet',
                'warning'
              )
            }
          }else{
            Swal.fire(
              'Error',
              'Verifica que los campos del empleado esten capturados.',
              'error'
            )
          }
        }else if(empleado != ""){
          //se indico un empleado existente
          let datos = new FormData();
          let permisosCat = ["verInventario","agregarInventario","editarInventario",
            "verProveedsores","agregarProveedores","editarProveedores"];
            let permisosCont = ["verControles","verMovimientos","verDepreciacion"];
            let permisosOper = ["actualizaFoto"];
            let userName = document.getElementById('nombreUsuario').value;
            

            for(let perCat = 0; perCat < permisosCat.length; perCat++){
              let idAuxCat = permisosCat[perCat];
              let campoAuxFor1 = document.getElementById(idAuxCat).checked;
              if(campoAuxFor1){
                datos.append(idAuxCat,"1");
              }else{
                datos.append(idAuxCat,"0");
              }
            }//fi del forcatalogo

            //Permisos de la seccion de controles
            for(let perCon = 0; perCon < permisosCont.length; perCon++){
              let idAuxCon = permisosCont[perCon];
              let campoAuxFor2 = document.getElementById(idAuxCon).checked;
              if(campoAuxFor2){
                datos.append(idAuxCon,"1");
              }else{
                datos.append(idAuxCon,"0");
              }
            }//fi del perCon

            //permisos de la seccion de operaciones
            for(let perOper = 0; perOper < permisosOper.length; perOper++){
              let idAuxOp = permisosOper[perOper];
              let campoAuxFor3 = document.getElementById(idAuxOp).checked;
              if(campoAuxFor3){
                datos.append(idAuxOp,"1");
              }else{
                datos.append(idAuxOp,"0");
              }
            }//fin del perOper

            datos.append("idEmpleadoUsuario",empleado);
            datos.append("userName",userName);
            datos.append("passNew",pass1);
            let envio = new XMLHttpRequest();
            envio.open("POST","includes/operations/altaUsuarios.php",false);
            envio.send(datos);

            if(envio.status == 200){
              let respuesta = envio.responseText;
              if(respuesta == "operationSucess"){
                Swal.fire(
                  'Operacion Realizada',
                  'Se inserto correctamente el usuario',
                  'success'
                ).then(function(){
                  location.reload();
                })
              }else{
                let error = respuesta.split("DataError|");
                error = error[1];
                Swal.fire(
                  'Error',
                  'Ocurrio un error al realizar la operacion: '+error,
                  'error'
                )
              }
            }else{
              Swal.fire(
                'Error',
                'Verifica tu conexion a internet',
              )
            }


        }else{
          //sin ocion
          Swal.fire(
            'Error',
            'Debes indicar un empleado para el usuario',
            'error'
          )
        }
      }else{
        Swal.fire(
          'Error',
          'El nombre de usuario debe ser minimo de 3 caracteres.',
          'error'
        )
      }
    }else{
      Swal.fire(
        'Error',
        'La contraseña debe ser minimo de 6 caracteres.',
        'error'
      )
    }
  }else{
    Swal.fire(
      'Error',
      'Verifica que las contraseñas coincidan.',
      'error'
    )
  }




});