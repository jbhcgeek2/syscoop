
let comboConsejo = document.getElementById('consejoEncargado');

comboConsejo.addEventListener('change', function(){
  let valorCombo = comboConsejo.value;
  if(valorCombo != ""){
    let datos = new FormData();
    datos.append('verActasConsejo', valorCombo);

    let envio = new XMLHttpRequest();
    envio.open("POST","../includes/operations/consejos.php",false);
    envio.send(datos);
    if(envio.status == 200){
      
      let valores = JSON.parse(envio.responseText);
      let nuevoCombo = "<option value=''>Seleccione...</option><option value='P'>Acta Pendiente</option>";
      for(let x =0; x < valores.length; x++){
        let valor = valores[x][0];
        let texto = valores[x][1];
        let fecha = valores[x][2];
        nuevoCombo += "<option value='"+valor+"|"+fecha+"'>"+texto+"</option>";
      }//fin for
      document.getElementById("actaAutorizacion").innerHTML = nuevoCombo;
      var elemSel = document.querySelectorAll('select');
      var options = "";
      var instanceSel = M.FormSelect.init(elemSel, options);
    }else{

    }
  }
})



let btnUpdateVersion = document.getElementById("saveNewVer");
btnUpdateVersion.addEventListener("click", function(){
  let datosUpdate = new FormData(document.getElementById('updateVersion'));
  let valida = 0;
  let tipoMov = document.getElementById("tipoActualizacion").value;
  if(tipoMov != ""){

    for(let [name, value] of datosUpdate){
      if(value == ' ' || value == ''){
        document.getElementById(`${name}`).classList.add('campoMal');
        valida++;
      }
    }//fin del for validacion
  
    if(valida == 0){
      let envio = new XMLHttpRequest();
      envio.open("POST","../includes/operations/documentos.php",false);
      envio.send(datosUpdate);
  
      if(envio.status == 200){
        let res = envio.responseText;
        if(res == "OperationSuccess"){
          Swal.fire(
            'Manual actualizado',
            'Se registro correctamente la informacion.',
            'success'
          ).then(function(){
            location.reload();
          })
        }else{
          let error = res.split("DataError|");
          error = error[1];
          Swal.fire(
            'Error',
            'Ha ocurrido un error: '+error,
            'error'
          )
        }
      }else{
        Swal.fire(
          'Error de conexion',
          'Verifica tuconexion a internet',
          'warning'
        )
      }
    }else{
      Swal.fire(
        'Informacion incompleta',
        'Verifica que los campos se encuentren capturados correctamente',
        'warning'
      )
    }
  }else{
    Swal.fire(
      'Informacion Incompleta',
      'El campo tipo de actualizacion esta vacio',
      'error'
    )
  }

  
})

let btnUpdateData =  document.getElementById("saveModData");
btnUpdateData.addEventListener("click", function(){
  let datosUpdateDoc = new FormData(document.getElementById("updateDataDoc2"));
  let valida = 0;

    for(let [name, value] of datosUpdateDoc){
      if(value == ' ' || value == ''){
        document.getElementById(`${name}`).classList.add('campoMal');
        valida++;
      }
    }//fin del for validacion

    if(valida == 0){
      //hacemos el envio de informacion
      let envio = new XMLHttpRequest();
      envio.open("POST","../includes/operations/documentos.php",false);
      envio.send(datosUpdateDoc);

      if(envio.status == 200){
        if(envio.responseText == "OperationSuccess"){
          Swal.fire(
            'Operacion exitosa',
            'Se actualizo correctamente la informacion',
            'success'
          ).then(function(){
            location.reload();
          })
        }else{
          if(envio.responseText == "OperationSuccess2"){
            Swal.fire(
              'Cuidado',
              'Se completo la tarea con errores, contacte a sistemas.',
              'warning'
            )
          }else{
            let error = envio.responseText.split("DataError|")[1];
            Swal.fire(
              'Cuidado',
              'Se detecto lo siguiente: '+error,
              'warning'
            )
          }
        }
      }else{
        Swal.fire(
          'Error de conexion',
          'Verifica tuconexion a internet',
          'warning'
        )
      }
    }else{
      //campos incompletos
      Swal.fire(
        'Datos incompletos',
        'Verifica que la informacion se encuentre capturada.',
        'success'
      )
    }
})

let statInter = document.getElementById("statInter").value;

if(statInter == "pendienteAutori"){
  //el documento aun se encuentra con versiones pendientes de aut6orizar

  Swal.fire(
    'Autorizacion Pendiente',
    'El presente documento cuenta con una version pendiente de autorizar',
    'info'
  )
}


function updateAnexo(a){
  let newNomAnexo = document.getElementById("nombreAnexo|"+a).value;
  let newCodAnexo = document.getElementById("codAnexo|"+a).value;

  let datos = new FormData();
  datos.append("AnexoNombreEdit",newNomAnexo);
  datos.append("AnexoCodEdit",newCodAnexo);
  datos.append("AnexoEdit",a);

  let enviar = new XMLHttpRequest();
  enviar.open("POST","../includes/operations/documentos.php",false);
  enviar.send(datos);

  if(enviar.status == 200){
    let res = enviar.responseText;
    if(res == "OperationSuccess"){
      Swal.fire(
        'Operacion Exitosa',
        'Se actualizo correctamente la informacion del Anexo.',
        'success'
      )
    }else{
      let err = res.split("DataError|")[1];
      Swal.fire(
        'Error',
        err,
        'error'
      )
    }
  }else{
    Swal.fire(
      'Servidor Inalcansable',
      'Verifica tu conexion a internet.',
      'error'
    )
  }
}

function newVerAnexo(a){
  //seccion para indicar una nueva version de un anexo
  alert(a);

}


function getVersionesAnexos(a){
  //verificamos si se indica una version anterior para enlazar con el manual
  let aux = a.split("tipoVerAnex");
  let tipo = document.getElementById(a).value;
  let anexo = aux[1];

  if(tipo == "OtraVer"){
    let datos = new FormData();
    datos.append("getOldVersions",anexo);
    
    let envia = new XMLHttpRequest();
    envia.open("POST","../includes/operations/documentos.php",false);
    envia.send(datos);
    if(envia.status == 200){
      //console.log(envia.responseText);
      let res = envia.responseText;
      if(res.split("DataError|").length == 1){
        if(res != "NoVersions"){
          //insertamos las versiones
          let res2 = JSON.parse(res);
          //console.table(res2);
          let newSelec = `<div class='input-field col s12'>
            <select id='manAnexoAnt${anexo}'>
            <option value='' selected disabled>Seleccione...</option>`;
            for (let x = 0; x < res2.length; x++) {
              newSelec += `<option value=''>${res2[x].nombre_ant} V. ${res2[x].ver_ant}</option>`
            }//fin del for
            newSelec += `</select>
            <label for=''manAnexoAnt${anexo}'>Seleccione Manual Anterior</label>
            </div>`;
            document.getElementById("resManAnt"+anexo).innerHTML = newSelec;
            var elemSel = document.querySelectorAll('select');
            var instanceSel = M.FormSelect.init(elemSel, options);
        }else{
          Swal.fire(
            'Opcion no valida',
            'No se han detectado versiones anteriores del manual para enlazar el anexo.',
            'warning',
          ).then(function(){
            location.reload();
          })
        }
      }else{
        let err = res.split("DataError|")[1];
        Swal.fire(
          'Error',
          err,
          'error'
        )
      }
    }else{
      Swal.fire(
        'Servidor Inalcansable',
        'Verifica tu conexion a internet',
        'error'
      )
    }
  }else{
    //Esta indicando una nueva version, primero tendra que actualizar 
    //el manual, para poder registrar una nueva version

  }
}