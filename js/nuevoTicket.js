//seccion 

let tipoTicket = document.getElementById("tipoSolicitud");

tipoTicket.addEventListener("change", function(){
  let tipo = tipoTicket.value;
  //consultamos el ticjket y los empleados autorizados

  let datos = new FormData();
  datos.append("verInfoTipoT",tipo);

  let envia = new XMLHttpRequest();
  envia.open("POST","../includes/operations/tickets.php",false);
  envia.send(datos);

  if(envia.status == 200){
    let res = envia.responseText;
    if(res.split("DataError|").length == 1){
      let datos = JSON.parse(res);
      console.log(datos);
      console.log(datos.responden[0]['nombre']);

      let asignarA = `<option value='' selected disabled>Seleccione...</option>
      <option value='unassigned'>Sin Asignar</option>`;
      for(let x = 0; x < datos.responden.length; x++){
        let nombreC = datos.responden[x]['nombre']+" "+datos.responden[x]['paterno']+" "+datos.responden[x]['materno'];
        let empleado = datos.responden[x]['id_usuario'];
        asignarA = asignarA+"<option value='"+empleado+"'>"+nombreC+"</option>";
      }//fin del for

      // let camposAdd = datos.camposTicket
      console.log(datos.camposT['camposTicket']);
      let camposAdd = datos.camposT['camposTicket'].split("|");

      let campos = '<input type="hidden" name="nuCamposAdd" id="nuCamposAdd" value="'+camposAdd.length+'">';
      
      for(let z = 0; z < camposAdd.length; z ++){
        let nombreCampo = camposAdd[z];
        
        campos += `<div class="input-field col s12 m4">
          <input type="text" id="campoAdd${z}" name="campoAdd${z}" vaue="">
          <label for="campoAdd${z}">${nombreCampo}</label>
        </div>`;
      }//fin del for camposAdd

      document.getElementById("asignado").innerHTML = asignarA;
      document.getElementById("resCamposAdd").innerHTML = campos;
      var elemSel = document.querySelectorAll('select');
      var instanceSel = M.FormSelect.init(elemSel, options);

    }else{
      //error al consultar la info
    }
  }else{
    //error de comunicacion
    Swal.fire(
      'Servidor inalcansable',
      'Verifica tu conexion a internet',
      'error'
    )
  }
});

let btnSaveTicket = document.getElementById('saveTicket');

btnSaveTicket.addEventListener("click", function(){
  Swal.fire({
    title: 'Estas Seguro de continuar?',
    text: 'Se registrar en la base de datos tu solicitud.',
    // icon: 'info',
    confirmButtonText: 'Si, Registrar',
    showCancelButton: true,
    cancelButtonColor: '#d33',
    cancelButtonText: 'No, Cancelar'
  }).then((result)=>{
    console.log(result);
    if(result.value){
      //generamos el form data

      let datos = new FormData(document.getElementById('formDataTicket'));

      let envia = new XMLHttpRequest();
      envia.open('POST','../includes/operations/tickets.php',false);
      envia.send(datos);

      if(envia.status == 200){
        let res = envia.responseText;
        if(res == "OperationSuccess"){
          Swal.fire(
            'Ticket Registrado',
            'Se ha registrado correctamente el ticket',
            'success'
          ).then(function(){
            window.location = 'ver-tickets.php';
          })
        }else{
          //ha ocurrido un error al registrar el ticket
          let err = res.split("DataError|")[1];
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
      

    }else{
      // window.location = "index.php";
    }
  })
})