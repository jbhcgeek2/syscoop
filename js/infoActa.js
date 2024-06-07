function updateDocActa(tipo){
  let tipoDoc = tipo;

  let valorInput = document.getElementById(tipoDoc).files[0];

  let form = new FormData();
  let acta = document.getElementById('actaInfo').value;

  form.append('docEnvio',valorInput);
  form.append('updateDocActa',acta);
  form.append('tipoDocActa',tipoDoc);

  let enviar = new XMLHttpRequest();

  enviar.open('POST','../includes/operations/consejos.php',false);
  enviar.send(form);

  if(enviar.status == 200){
    let res = enviar.responseText;
    if(res == "OperationSuccess"){
      Swal.fire(
        'Operacion completa',
        'Se actualizo correctamente la documentacion',
        'success'
      ).then(function(){
        location.reload();
      })
    }else{
      let error = res.split("DataError|")[1];
      Swal.fire(
        'Cuidado',
        'Verificar: '+error,
        'error'
      )
    }
  }else{
    console.log('error');
  }


}

let btnUpdateAcu = document.getElementById('updateAcuerdos');
btnUpdateAcu.addEventListener('click', function(){
  let acta = document.getElementById("actaInfo").value;
  let nAcu = document.getElementById("nAcuEdit").value;
  let acuerdosFina = "";
  let acuDom2 = "acuerdoEdit";

  for(let x = 1; x < nAcu; x++){
    let dom = acuDom2+x;
    if(document.getElementById(dom)){
      let acu = document.getElementById(dom).value;
      if(acuerdosFina == ""){
        acuerdosFina = acu;
      }else{
        acuerdosFina += "_|_"+acu;
      }
    }
  }

  //indicams si relamente desea actualizar la informacion
  Swal.fire({
    title: 'Estas seguro de actualizar la informacion?',
    showDenyButton: true,
    icon: 'question',
    iconHtml: '؟',
    iconColor: 'red',
    showCancelButton: true,
    confirmButtonText: 'Guardar',
    denyButtonText: 'Cancelar',
  }).then((result) => {
    if(result.value){
      //hacemos el envio de informacion
      let data = new FormData();
      data.append('actaUpdateAcu',acta);
      data.append('acuUpdates',acuerdosFina);

      let enviar = new XMLHttpRequest();
      enviar.open("POST","../includes/operations/consejos.php", false);
      enviar.send(data);

      if(enviar.status == 200){
        let res = enviar.responseText;
        if(res == "OperationSuccess"){
          Swal.fire(
            'Actualizacion completa',
            'Se actualizaron los acuerdos correctamente',
            'success'
          ).then(function(){
            location.reload();
          })
        }else{
          let err = res.split("DataError|")[1];
          Swal.fire(
            'Ha ocurrido un error',
            'Verificar: '+err,
            'error'
          )
        }
      }else{
        Swal.fire(
          'Servidor inalcansable',
          'Verifica tu conexion a internet',
          'error'
        )
      }
    }else{
      location.reload();
    }
  })
})

let btnAgregaAcu = document.getElementById("AddAcu");
btnAgregaAcu.addEventListener('click', function(){
  //verificamos el numero de acuerdos actuales
  let nAcuerdos = document.getElementById('nAcuEdit').value;
  // let newDomValue = document.getElementById("resNewAcue");
  let newDomValue = `<div id='acuerdoContent${nAcuerdos}'><div class='input-field col s10'>
    <input type='text' name='acuerdoEdit${nAcuerdos}' id='acuerdoEdit${nAcuerdos}' value=''>
    <label for='acuerdoEdit${nAcuerdos}'>Acuerdo</label>
  </div>
  <div class='col s2'>
    <a href='#!' onclick='deleteAcuerdo(${nAcuerdos})'>
      <i class='material-icons red-text'>delete</i>
    </a>
  </div></div>`;

  

  document.getElementById("resNewAcue").insertAdjacentHTML('beforeend',newDomValue);
  document.getElementById('nAcuEdit').value = Number(nAcuerdos)+1;
})



function noDoc(){
  Swal.fire(
    'Documentacion Faltante',
    'El documento al cual quieres acceder aun no se registra, favor de notificarlo al area encargada.',
    'warning'
  )
}

function deleteAcuerdo(a){
  let acuDom = 'acuerdoContent'+a;
  let nAcuer = document.getElementById('nAcuEdit').value;
  document.getElementById(acuDom).innerHTML = '';
}

