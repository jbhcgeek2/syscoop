document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems, options);
  });
  
  

  
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
  
let btnUpdateData = document.getElementById('btnUpdateDataEmp');
btnUpdateData.addEventListener('click', function(){
  //seccion para actualizar la informacion del empleado
  //preguntamos si desea actualizar los datos
  Swal.fire({
    title: 'Actualizar Datos',
    text: 'Estas seguro de actualizar la informacion?',
    icon: 'warning',
    showDenyButton: 'Cancelar',
    confirmButtonText: 'Si, Actualizar',
    denyButtonText: `No, Cancelar`
  }).then((result)=>{
    if(result.isConfirmed){
      //validamos los datos
      let empleado = document.getElementById('usuarioDato').value;
      let nombres = document.getElementById('nombreEmpleado').value;
      let paterno = document.getElementById('paternoEmpleado').value;
      let materno = document.getElementById('maternoEmpleado').value;
      let mail = document.getElementById('mailUser').value;
      let celular = document.getElementById('celUser').value;
      let activo = document.getElementById('activo').value;
      let departamento = document.getElementById('departamento').value;
      let puesto = document.getElementById('puesto').value;

      let datos = new FormData();
      datos.append("empleadoUpdate",empleado);
      datos.append("nombresUpdate",nombres);
      datos.append("paternoUpdate",paterno);
      datos.append("maternoUpdate",materno);
      datos.append("mailUpdate",mail);
      datos.append("celularUpdate",celular);
      datos.append("activoUpdate",activo);
      datos.append("departamentoUpdate",departamento);
      datos.append("puestoUpdate",puesto);

      let envio = new XMLHttpRequest();
      envio.open("POST","../includes/operations/updateEmpleado.php",false);
      envio.send(datos);

      if(envio.status == 200){
        let res = JSON.parse(envio.responseText);
        if(res.status == "ok"){
          Swal.fire(
            'Operacion Realizada',
            'Se actualizo correctamente el empleado',
            'success'
          ).then(function(){
            location.reload();
          })
        }else{
          Swal.fire(
            'Ha ocurrido un error',
            res.mensaje,
            'error'
          )
        }
      }else{
        //error deservidor
        Swal.fire(
          'Servidor Inalcansable',
          'Verifica tu conexion a internet',
          'error'
        )
      }

    }
  })
})
  