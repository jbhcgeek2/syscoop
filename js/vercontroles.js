if(document.getElementById("verUsuarios")){
  let btnVerUsuarios = document.getElementById("verUsuarios");
  btnVerUsuarios.addEventListener("click", function(){
    window.location = "ver-usuarios.php";
  })
}

if(document.getElementById("verSucursales")){
  let btnVerSucursales = document.getElementById("verSucursales");
  btnVerSucursales.addEventListener("click", function(){
    window.location = "ver-sucursales.php";
  })
}

if(document.getElementById("newSuc")){
  let newSuc = document.getElementById("newSuc");
  newSuc.addEventListener("click", function(){
    var elemSel = document.querySelectorAll('select');
    var options = "";
    var instanceSel = M.FormSelect.init(elemSel, options);
  })
}

if(document.getElementById("verEmpleados")){
  let newSuc = document.getElementById("verEmpleados");
  newSuc.addEventListener("click", function(){
    window.location = "ver-empleados.php";
  })
}
if(document.getElementById("verConsejos")){
  let newSuc = document.getElementById("verConsejos");
  newSuc.addEventListener("click", function(){
    window.location = "ver-consejos.php";
  })
}

if(document.getElementById("verTickets")){
  let ticke = document.getElementById("verTickets");
  ticke.addEventListener("click", function(){
    window.location = "control-tickets.php";
  })
}







if(document.getElementById("regSuc")){
  let registrarSuc = document.getElementById("regSuc");
  regSuc.addEventListener("click", function(){
    let nameSuc = document.getElementById("nameSuc").value;
    let estatus = document.getElementById("sucActiva").value;

    if(nameSuc != "" && estatus != ""){
      datos = new FormData();
      datos.append('nombreSuc',nameSuc);
      datos.append('estatusSuc',estatus);

      let enviar = new XMLHttpRequest();
      enviar.open("POST","includes/operations/sucursales.php",false);

      enviar.send(datos);
      if(enviar.status == 200){
        if(enviar.responseText == "operationSuccess"){
          Swal.fire(
            'Registro Completo',
            'La sucursal se registro correctamente',
            'success'
          ).then(function(){
            location.reload();
          })
        }else{
          let error = enviar.responseText.split("DataError|");
          Swal.fire(
            'Error',
            'Ocurrio un error inesperado: '+error,
            'error'
          )
        }
      }else{
        Swal.fire(
          'Servidor inalcansable',
          'Verifica tu conexion a internet',
          'warning'
        )
      }
    }else{
      Swal.fire(
        'Campos incompletos',
        'favor de indicar la informacion',
        'error'
      )
    }
    
  })
}