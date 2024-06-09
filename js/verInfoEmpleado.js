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
  
  