function updateCampoN(campo){

  //preguntamos si deseamos hacer la modificacion
  Swal.fire({
    title: 'Deseas modificar el campo?',
    showCancelButton: true,
    confirmButtonText: 'Modificar!',
    // showDenyButton: 'true',
    cancelButtonColor: '#d33',
    cancelButtonText: 'Cancalar',
    allowOutsideClick: false
  }).then((result) =>{
    // console.log(result);
    if(result.value){
      //actualizamos
      let valor = document.getElementById(campo).value;
      let tipoT = document.getElementById('valorTicket').value;
      
      let datos = new FormData();
      datos.append("campoTipoTUpdate",campo);
      datos.append("valorTipoTUpdate",valor);
      datos.append("tipoTUpdate",tipoT);

      let envia = new XMLHttpRequest();
      envia.open("POST","../includes/operations/tickets.php",false);
      envia.send(datos);

      if(envia.status == 200){
        let res = envia.responseText;
        if(res == "OperationSuccess"){
          Swal.fire(
            'Campo Actualizado',
            'success'
          )
        }else{
          //error
          let err = res.split("DataError|")[1];
          Swal.fire(
            'Ha ocurriod un error',
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
      //solo cerramos
    }
  })

  
}

let btnAddCampo = document.getElementById("btnAddCampo");

btnAddCampo.addEventListener('click', function(){
  //comenzamos verificando cuantos existen
  let nCampos = document.getElementById("camposAdd").value;
  // console.log(nCampos);
  nCampos = Number(nCampos);
  nCampos = nCampos+1;
  let nuevoCampo = `<div class='input-field col s12 m4' id='addCampo${nCampos}'>
    <input type="text" id="inputAdd${nCampos}" name="inputAdd${nCampos}" onchange="updateCamposAdd()">
    <label for="inputAdd${nCampos}">Campo ${nCampos}</label>
  </div>`;

  document.getElementById("resCamposAdd").insertAdjacentHTML("beforeend",nuevoCampo);
  document.getElementById("camposAdd").value = nCampos;
});


let btnDelCampo = document.getElementById('btnDelCampo');
btnDelCampo.addEventListener('click', function(){
  //comenzamos contando los campos
  let nCampos = document.getElementById("camposAdd").value;
  nCampos = Number(nCampos);
  //eliminamos el ultimo
  document.getElementById('addCampo'+nCampos).remove();
  nCampos = nCampos -1;
  document.getElementById("camposAdd").value = nCampos;

  updateCamposAdd();

});


function updateCamposAdd(){
  //verificamos el numero de campos que se actualizara
  let nCampos = document.getElementById('camposAdd').value;
  let ticket = document.getElementById('valorTicket').value;
  //creamos un for para formar los datos
  let n = 1;
  let campos = "";
  for (let x = 0; x < nCampos; x++) {
    let dato = document.getElementById('inputAdd'+n).value.trim();
    if(dato != ""){
      if(campos == ""){
        campos = dato;
      }else{
        campos = campos+"|"+dato;
      }
    }
    
    // console.log(dato);
    n = n+1;
  }
  
  //preguntamos si desea actualizar los campos
  Swal.fire({
    title: 'Actualizar campos?',
    text: 'Esto, modifica todos los campos del tipo de  ticket.',
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: "Si, Actualizar",
    cancelButtonText: 'No, cancelar'
  }).then((result)=>{
    if(result.value){
      //mandamos el form
      let datos = new FormData();
      datos.append("valorTipoTUpdate",campos);
      datos.append("tipoTUpdate",ticket);
      datos.append("campoTipoTUpdate","camposTicket");

      let envia = new XMLHttpRequest();
      envia.open("POST","../includes/operations/tickets.php",false);
      envia.send(datos);

      if(envia.status == 200){
        //se mando bien
        let res = envia.responseText;
        if(res == "OperationSuccess"){
          //se actualizaron los campos
          Swal.fire(
            'Campos Actualizados',
            'se actualizo correctamente la informacion',
            'success'
          )
        }else{
          //error en la actualizacion
          let err = res.split("DataError|")[1];
          Swal.fire(
            'Ha ocurrido un error',
            'Verificar: '+err,
            'error'
          )
        }
      }else{
        //error en la comunicacion
        Swal.fire(
          "Servidor Inalcansable",
          "Verifica tu conexion a internet",
          "error"
        )
      }
    }else{
      
    }
  })
}