document.addEventListener('DOMContentLoaded', function() {
  var elems = document.querySelectorAll('.modal');
  var instances = M.Modal.init(elems, options);
});



function updatePermiso(permiso){
  //obtenemos elid del check
  let idChek = document.getElementById(permiso);
  let usuario = document.getElementById("usuarioDato").value;
  let permisoData = document.getElementById("permisoData").value;
  //verificamos si esta marcado
  let tipoPermiso = "";
  if(idChek.checked){
    //en esta seccion se le brindara el permiso nuevo
    tipoPermiso = "dar";
  }else{
    //en esta seccion se le quitara el permiso
    tipoPermiso = "quitar";
  }

  //preguntamos siesta de acuerdo con modificar el campo
  Swal.fire({
    title: "Modificar Permiso?",
    text: "Se modificara el permiso seleccionado",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Modificar",
  }).then((result)=>{
    if(result.value == true){
      let datos = new FormData();
      datos.append("tipoPer",tipoPermiso);
      datos.append("usurioMod",usuario);
      datos.append("namePer",permiso);
      datos.append("permiso", permisoData);

      let enviar = new XMLHttpRequest();
      enviar.open("POST","../includes/operations/altaUsuarios.php",false);
      enviar.send(datos);

      if(enviar.status == 200){
        let res = enviar.responseText;
        if(res == "OperationSuccess"){
          Swal.fire(
            'Permiso Actualizado'
          )
        }else{
          //error
          let err = res.split("DataError|")[1];
          Swal.fire(
            'Error en la actualizacion',
            'Verificar: '+err,
            'error'
          )
        }
        console.log(enviar.responseText);
      }else{  
        //servidor inalcansable
        Swal.fire(
          'Servidor Inalcansable',
          'Verifica tu conexion a internet',
          'warning'
        )
      }
    }else{
      //cncelo el proceso
      location.reload();
    }
    
  })
}

let btnShowP = document.getElementById("btnShowPass");
btnShowP.addEventListener("click", function(){
  //simplemente habilitamos la vista
  console.log(btnShowP);
  let campo = document.getElementById("contentCon");
  if(campo.classList.contains("hide")){
    //la quitamos
    campo.classList.remove("hide");
    document.getElementById("btnShowPass").innerHTML = "Ocultar Contrasena";
  }else{
    campo.classList.add("hide");
    document.getElementById("btnShowPass").innerHTML = "Ver Contraseña";
  }
  // document.getElementById("contentCon").classList.remove("hide");
})

function updateCampo(campo){
  //verificamos el campo
  //preguntamos si esta seguroi de actualizar el campo
  Swal.fire({
    title: 'Modificar Campo?',
    text: 'Estas seguro de modificar el campo?',
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Modificar",
  }).then((result)=>{
    if(result.value == true){
      let usuario = document.getElementById("usuarioDato").value;
      let valorMod = document.getElementById(campo).value;
      let datos = new FormData();
      datos.append("campoMod",campo);
      datos.append("usuarioMod",usuario);
      datos.append("valorMod",valorMod);
    
    
      let envio = new XMLHttpRequest();
      envio.open("POST","../includes/operations/altaUsuarios.php",false);
      envio.send(datos);
    
      if(envio.status == 200){
        let res = envio.responseText;
        if(res == "OperationSuccess"){
          Swal.fire(
            'Operacion Realizada',
            'Se modifico correctamente la informacion',
            'success'
          ).then(function(){
            location.reload();
          })
        }else{
          //error de operacion
          let err = res.split("|")[1];
          Swal.fire(
            'Operacion Realizada',
            'Verificar: '+err,
            'error'
          )
        }
      }else{
        //error
        Swal.fire(
          'Servidor Inalcansable',
          'Verifica tu conexion a internet',
          'error'
        )
      }
    }else{
      //se cancela
    }
  })

  
}


if(document.getElementById('updateNewpicture')){
  //seccion para actualizar la foto de perfil

  let btnPerfil = document.getElementById('updateNewpicture');
  btnPerfil.addEventListener('click', function(){
    let datos = new FormData(document.getElementById('dataPictureUpdate'));
    let envio = new XMLHttpRequest();
    envio.open('POST','../includes/operations/altaUsuarios.php',false);
    envio.send(datos);
  
    if(envio.status == 200){
      console.log(envio.responseText);
      let res = envio.responseText;
      if(res == "OperationSuccess"){
        Swal.fire(
          'Foto Actualizada',
          'Se subio correctamente la imagen',
          'success'
        ).then(function(){
          location.reload();
        })
      }else{
        //error al subir la imagen
        let err = res.split("DataError|");
        Swal.fire(
          'Ha ocurrido un error',
          'Verificar: '+err,
          'error'
        )
      }
    }else{
      //error de comunicacion
      Swal.fire(
        'Servidor Inalcansable',
        'Verifica tu conexion a internet',
        'error'
      )
    }
  })

  

}
