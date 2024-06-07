let btnAddCampo = document.getElementById('addCampTicket');

btnAddCampo.addEventListener('click', function(){
  let nCampos = document.getElementById("camposAdd").value;
  nCampos = Number(nCampos);
  //sumamos 1 y agregamos el campo para que indique el nombre
  let num = nCampos + 1;
  let campoText = `<div id="campoAdd${num}" class="input-field col s12 m4">
    <input type="text" id="inputAdd${num}" name="inputAdd${num}">
    <label for="">Campo${num}</label>
  </div>`;

  //ahora sumamos el campo y ponemos el nuevo valor
  document.getElementById("camposAdd").value= num;
  document.getElementById("addCamps").insertAdjacentHTML('beforeend',campoText);
})

let btnDelCampo = document.getElementById("delCampTicket");

btnDelCampo.addEventListener('click', function(){
  let nCampos = document.getElementById("camposAdd").value;
  nCampos = Number(nCampos);

  if(nCampos == 0){
    //le adecimos que minimo debe existir un campo
    Swal.fire(
      'Sin Campos a Eliminar',
      'No Existen campos a eliminar.',
      'error'
    )
  }else{
    document.getElementById("campoAdd"+nCampos).remove();
    //restyamos uno al contyador
    nCampos = nCampos -1;
    document.getElementById("camposAdd").value = nCampos;
  }
  
})

let btnRegistra = document.getElementById('registrarTipo');
btnRegistra.addEventListener('click', function(){
  //verificamos que todos los dato contengan informacion
  let datos = new FormData(document.getElementById("registraTipoTicket"));
  let incorrecto = 0;

  for(let [name, value] of datos){
    // console.log("Campo contiene: "+name);  
    let valor = document.getElementById(name).value;
    if(valor == ""){
      //sumamos un incorrecto
      incorrecto = incorrecto+1;
    }
  }//fin del for

  if(incorrecto == 0){
    //mandamos el formualrio
    let envio = new XMLHttpRequest();
    envio.open("POST","../includes/operations/tickets.php",false);
    envio.send(datos);

    if(envio.status == 200){
      let res = envio.responseText;
      if(res == "OperationSuccess"){
        Swal.fire({
          title: 'Operacion Completa',
          text: 'Deseas insertar un nuevo ticket?',
          icon: "info",
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: "Registrar Otro",
          denyButtonText: 'No, finalizar proceso'
        }).then((result) => {
          if(result.isConfirmed){
            location.reload();
          }else{
            window.location = "control-tickets.php";
          }
        })
      }else{
        //ocurrio un error
        let err = res.split("DataError|")[1];
        Swal.fire(
          'Ocurrion un error',
          'Verificar: '+err,
          'error'
        )
      }
    }else{
      //error de comunicacion
    }

  }else{
    //tiene campos no completos
    Swal.fire(
      'Informacion Incompleta',
      'Por facor indique la informacion de todos los campos',
      'error'
    )
  }
})