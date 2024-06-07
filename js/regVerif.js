//inicializacion de select
var options = ''; 
var elems = document.querySelectorAll('select'); 
var instances = M.FormSelect.init(elems, options);

var autoCompleteInp = document.getElementById('localidadPer');
var completeInstance = M.Autocomplete.init(autoCompleteInp, options);

const btnGuardar = document.querySelector('#btnSaveVeri');

 
function validarCampo(valor){
  

}//fin funcion validar campo

function valDato(a,b){
  let invalido = ['#','°','!','|','¬','"','$','%','/','=','?','¿','¡',
  '*','¨','{','}','[',']','^','<','>',"'",'~'];
  let idDom = b;
  if(a != ''){
    let letras = a.split("");
    let nLet = letras.length;
    for(let i=0; i < nLet; i++){
      let letra = letras[i];
      //buscamos la letras dentro de las no permitidas
      for(i2=0; i2< invalido.length; i2++){
        if(letra == invalido[i2]){
          //agregamos un color de invalido al campo
          document.getElementById(idDom).classList.add("campoMal");
          // console.log('mal');
        }
      }
    }//fin del for 

    document.getElementById(b).value = a.toUpperCase();

  }
}

function helperInput(input,isCorrect){
  let campoDom = document.getElementById('helper'+input);
  if(isCorrect == "no"){
    //el campo es incorrecto
    campoDom.classList.add("helper-text");
    campoDom.setAttribute("data-error","");
    campoDom.innerHTML = 'Dato incorrecto';
    campoDom.classList.add('campoMalHelper');
  }else{
    //el campo es correcto, verificamos si contine datos
    if(campoDom.classList.contains("helper-text")){
      campoDom.classList.remove("helper-text");
      campoDom.innerHTML = '';
    }
  }
}

btnGuardar.addEventListener("click", function(){
  //realizaremos la verificacion de los datos
  let campoPersona = {
    "campo": [
      {"nombre": "nombrePer","tipo": "texto","requerido": "si",},
      {"nombre": "apPatPer","tipo": "texto","requerido": "no",},
      {"nombre": "apMatPer","tipo": "texto","requerido": "no",},
      {"nombre": "estadoPer","tipo": "numero","requerido": "si",},
      {"nombre": "municipioPer","tipo": "numero","requerido": "si",},
      {"nombre": "localidadPer","tipo": "texto","requerido": "si",},
      {"nombre": "entreCalleaPer","tipo": "texto","requerido": "si",},
      {"nombre": "callePer","tipo": "texto","requerido": "si",},
      {"nombre": "noCallePer","tipo": "numero","requerido": "si",},
      {"nombre": "noCalleIntPer","tipo": "texto","requerido": "no",},
      {"nombre": "entreCalleaPer","tipo": "texto","requerido": "si",},
      {"nombre": "colPer","tipo": "texto","requerido": "si",},
      {"nombre": "cpPer","tipo": "numero","requerido": "si",},
      {"nombre": "celper","tipo": "numero","requerido": "si",},
      {"nombre": "idenPer","tipo": "numero","requerido": "si",},
      {"nombre": "horarioPref","tipo": "texto","requerido": "si",},
      {"nombre": "refDomPer","tipo": "texto","requerido": "si",}
    ]
  }

  //verificamos la informacion de la persona
  let campoPersonaOk = 0;

  for(let p = 0; p < campoPersona.campo.length; p++){
    let campoP = campoPersona.campo[p].nombre;
    let valueCampoDomP = document.getElementById(campoP).value;
    let tipoCampo = campoPersona.campo[p].tipo;
    let requerido = campoPersona.campo[p].requerido;

      //paso 1: validar si contiene dato
      if(valueCampoDomP != ""){
        //verificamos si el campo es de texto o numerico
        if(tipoCampo == "texto"){
          if(isNaN(valueCampoDomP)){
            //es campo de texto, esta correcto
            campoPersonaOk++;
            helperInput(campoP,'si');
          }else{
            //indicamos que el campo es incorrecto
            if(campoP == "noCalleIntPer"){
              //el campo de numero interior es valido numeros y letras
              campoPersonaOk++;
              helperInput(campoP,'si');
            }else{
              helperInput(campoP,'no');
            }
          }
        }else{
          //el campo debe ser numerico
          if(!isNaN(valueCampoDomP)){
            //el campo es correcto
            campoPersonaOk++;
            helperInput(campoP,'si');
          }else{
            //indicamos que el valor esta mal
            helperInput(campoP,'no');
          }
        }
      }else{
        //el valor es vacio, verificamos si es requerido
        if(requerido == 'si'){
          //el campo es requerido, lo marcamos como erroneo
          helperInput(campoP,'no');
        }else{
          //el campo puede estar vacio, no hacemos ninguna accion
          campoPersonaOk++;
          helperInput(campoP,'si');
        }
      }
    
  }//fin del for

  if(campoPersonaOk == campoPersona.campo.length){
    //ahora verificcamos que las cordenadas de la persona esten capturadas
    let latitud = document.getElementById('latDomPer').value;
    let longitud = document.getElementById('lngDomPer').value;
    if(latitud != "" && longitud != ""){
      //ahora continuamos con la verifiaccion de los avales
      let campoAval = {
        "campo": [
          {"nombre": "nameAval","tipo": "texto","requerido": "si",},
          {"nombre": "patAval","tipo": "texto","requerido": "no",},
          {"nombre": "matAval","tipo": "texto","requerido": "no",},
          {"nombre": "estadoAval","tipo": "numero","requerido": "si",},
          {"nombre": "municipioAval","tipo": "numero","requerido": "si",},
          {"nombre": "localidadAval","tipo": "texto","requerido": "si",},
          {"nombre": "celAval","tipo": "numero","requerido": "si",},
          {"nombre": "calleAval","tipo": "texto","requerido": "si",},
          {"nombre": "numExtAval","tipo": "numero","requerido": "si",},
          {"nombre": "numIntAval","tipo": "texto","requerido": "no",},
          {"nombre": "entreCalleAval","tipo": "texto","requerido": "si",},
          {"nombre": "colAval","tipo": "texto","requerido": "si",},
          {"nombre": "cpAval","tipo": "numero","requerido": "si",},
          {"nombre": "refDomAval","tipo": "texto","requerido": "si",}
        ]
      }

      //primero validaremos que se indique si cuenta con avales o no
      if(document.getElementById('avalNumbers') && document.getElementById('avalNumbers').value >= 0){
        let numeroAvales = document.getElementById('avalNumbers').value;
        let pasaAvales = false;
        let totalCampos = numeroAvales*campoAval.campo.length;
        console.log(totalCampos);
        let camposBien = 0;
        
        if(numeroAvales > 0){
          //se realizara verificacion de avales
          for(let a = 1; a <= numeroAvales; a++){
            //creamos otro for para recorres los diferentes campos
            
            for(num = 0; num < campoAval.campo.length; num++){
              let campo_aval = campoAval.campo[num].nombre+a;
              let campoAvalObjet = campoAval.campo[num].nombre;
              let requeridoAval = campoAval.campo[num].requerido;
              let tipoCampoAval = campoAval.campo[num].tipo;
              let valorCampoAval = document.getElementById(campo_aval).value;
              //let domCampo = document.getElementById('helper'+campo_aval);
              //pasos 1 avales: validar si contiene dato
              if(valorCampoAval != ""){
                //el dato contiene valor, verificamos si corresponde al valor marcado
                if(tipoCampoAval == "texto"){
                  if(isNaN(valorCampoAval)){
                    //el campo no es numero, lo marcamos como correcto
                    helperInput(campo_aval,'si');
                    camposBien++;
                  }else{
                    //el campo es numerico, lo marcamos como incorrecto,
                    //a menos que se trate del numero inmterior
                    if(campoAvalObjet == "numIntAval"){
                      //lo marcamos como valido
                      helperInput(campo_aval,'si');
                      camposBien++;
                    }else{
                      //debe ser numerico
                      helperInput(campo_aval,'no');
                    }
                  }
                }else{
                  //el campo es numerico
                  if(!isNaN(valorCampoAval)){
                    helperInput(campo_aval,'si');
                    camposBien++;
                  }else{
                    helperInput(campo_aval,'no');
                  }
                }
              }else{
                //el dato no contiene valor, verificamos si es requerido
                if(requeridoAval == "si"){
                  //marcamos el campo como invalido
                  helperInput(campo_aval,'no');
                  camposBien++;
                }else{
                  //el campo puede estar vacio, no lo marcamos como incorrectos
                  helperInput(campo_aval,'si');
                  camposBien++;
                }
              }
            }//fin for campos avales (interno)
          }//fin del for verifica avales (externo)
          if(camposBien == totalCampos){
            //verificamos si los avales contienen las coordenadas capturadas
            let ubicacionesAvales = 0;
            for(let ubi = 1; ubi <= numeroAvales; ubi++){
              let latitudAval = document.getElementById("latDomAval"+ubi).value;
              let longitudAval = document.getElementById("lngDomAval"+ubi).value;
              if(latitudAval == "" || longitudAval == ""){
                ubicacionesAvales++;
              }
            }//fin del for para verificar ubicacion de avales
            if(ubicacionesAvales == 0){
              //todas las ubicaciones estan definidas
              //todos los campos correctos, procedemos a agregar los datos del aval al formData
              let formulario = new FormData(document.getElementById('newVeryData'));
              let envio = new XMLHttpRequest();

              for(let avalN = 1; avalN <= numeroAvales; avalN++){
                let camposInsert =   campoAval.campo.length;
                for(let campo = 0; campo < camposInsert; campo++){
                  //let campoForm = campoAval.campo[campo].nombre;
                  let campo2 = campoAval.campo[campo].nombre+avalN;
                  let valorAval = document.getElementById(campo2).value;

                  formulario.append(campo2,valorAval);
                }//fin del for campos
                let latAuxAv = "latDomAval"+avalN;
                let lngAuxAv = "lngDomAval"+avalN;
                let latDataAv = document.getElementById("latDomAval"+avalN).value
                let lngDataAv = document.getElementById("lngDomAval"+avalN).value
                formulario.append(latAuxAv,latDataAv);
                formulario.append(lngAuxAv,lngDataAv);
              }//fin del for avales
              formulario.append('avalNumbers',document.getElementById('avalNumbers').value);

              envio.open('POST','../includes/operations/saveVeriDom.php',false);
              envio.send(formulario);

              if(envio.status == 200){
                //verificamos la respuesta
                let res = envio.responseText;
                if(res.split('dataError|').length == 1){
                  if(res == 'operationComplete'){
                    //se capturo todo correctamente
                    swal.fire(
                      'Verificación capturada',
                      'Su solicitud pasara a ser validada por mesa de control',
                      'success',
                    ).then(function(){
                      window.location = 'index.php';
                    })
                  }else{
                    //error desconocido del servidor
                    swal.fire(
                      'Error desconocido',
                      'Ocurrió un error desconocido, verifica con sistemas',
                      'error',
                    )
                  }
                }else{
                  //ocurrio un error al
                  let error = res.split('DataError|')[1];
                  swal.fire(
                    'Error',
                    'Ha ocurrido un error: '+error,
                    'error'
                  )
                }
              }else{
                //error de comunicacion con el servisor
                swal.fire(
                  'Error',
                  'Error de comunicación, verifica tu conexión a internet',
                  'error'
                )
              }

            }else{
              //no estan marcadas las ubicaciones del aval
              swal.fire(
                'Información Requerida',
                'La ubicación de la vivienda del aval no se ha definido en el mapa',
                'error',
              )
            }
          }else{
            swal.fire(
              'Campos incompletos',
              'Verifica que los datos del aval esten capturados correctamente.',
              'error',
            )
          }

        }else{
          //no se realizara verificacion de avales, procedemos a guardar la verificacion
          let formulario = new FormData(document.getElementById('newVeryData'));
          let envio = new XMLHttpRequest();

          envio.open('POST','../includes/operations/saveVeriDom.php',false);
          envio.send(formulario);

          if(envio.status == 200){
            //verificamos la respuesta
            let res = envio.responseText;
            if(res.split('dataError|').length == 1){
              if(res == 'operationComplete'){
                //se capturo todo correctamente
                swal.fire(
                  'Verificación capturada',
                  'Su solicitud pasara a ser validada por mesa de control',
                  'success',
                ).then(function(){
                  window.location = 'index.php';
                })
              }else{
                //error desconocido del servidor
                swal.fire(
                  'Error desconocido',
                  'Ocurrió un error desconocido, verifica con sistemas',
                  'error',
                )
              }
            }else{
              //ocurrio un error al
              let error = res.split('DataError|')[1];
              swal.fire(
                'Error',
                'Ha ocurrido un error: '+error,
                'error'
              )
            }
          }else{
            //error de comunicacion con el servisor
            swal.fire(
              'Error',
              'Error de comunicación, verifica tu conexión a internet',
              'error'
            )
          }


          
        }

        // if(pasaAvales){
          
          
        // }else{
        //   //los datos de los avales no son correctos
        //   swal.fire(
        //     'Información Incompleta',
        //     'Verifica que los campos capturados contengan información valida.',
        //     'warning',
        //   )
        // }
      }else{
        //no se indico
        swal.fire(
          'Cuidado',
          'Antes de continuar indica si la verificación contiene avales.',
          'info',
        )
      }
      
    }else{
      //no se indico ubicacion de la persona
      swal.fire(
        'Información Requerida',
        'La ubicación de la vivienda no se ha definido en el mapa',
        'error',
      )
    }
  }else{
    //no se completaron todos los campos
    swal.fire(
      'Datos incompletos',
      'Verifica los datos capturados',
      'error',
    )
  }





  
  
  

  // let countWrong = 0; 
  // if(countWrong == 0){
  //   //procedemos a mandar la info
  //   let send = new XMLHttpRequest();
  //   let data = new FormData(document.getElementById('newVeryData'));

    

  //   send.open('POST','../includes/operations/saveVeriDom.php',false);
  //   // send.send(data);

  //   //console.log(send.status);

  //   if(send.status == 200){ 
  //     //1578
  //     console.log(send.responseText);
  //     let res = send.responseText;
  //     if(res.split("dataError|").length == 1){
  //       if(res == 'operationComplete'){
  //         swal.fire(
  //           'Verificación registrada',
  //           'Su solicitud pasara a ser validada por mesa de control',
  //           'success',
  //         )
  //       }else{
  //         //error desconocido en la respuesta del servidor
  //         swal.fire(
  //           'Inconsistencia en el servidor',
  //           'Favor de reportar a sistemas este inconveniente',
  //           'warning'
  //         )
  //       }
  //     }else{
  //       //error en la respuesta del servidor
  //       let error = res.split("dataError|")[1];
  //       swal.fire(
  //         'Ha ocurrido un error',
  //         'Error inesperado al procesar la información: '+error,
  //         'error'
  //       )
  //     }
  //   }else{
  //     //ocurrio un error de comunicacion
  //     swal.fire(
  //       'Servidor Inalcanzable',
  //       'Verifica tu conexión a internet',
  //       'warning'
  //     )
  //   }

  // }else{
  //   //se detecto algun error
  //   swal.fire(
  //       'Datos Incorrectos',
  //       'Usuario y/o contraseña incorrectos.',
  //       'warning'
  //   )
  // }
  
}); // fin btnSave


function avalNumbers(a){
  let campos = "";

  if(a == 1){
    campos += `
      <div class="input-field col s12 l2 offset-l5">
        <input type="number" name="avalNumbers" id="avalNumbers" min="0" max="4" onchange="setAvalNumbers(this.value)">
        <label for="avalNumbers">Numero de Avales</label>
      </div>
    `;
  }else{
    campos += `<input type="hidden" name="avalNumbers" id="avalNumbers" value="0">`;
  }

  document.getElementById('resNumAvales').innerHTML = campos;
}//fin funcion avalRequired


function setAvalNumbers(a){
  //verificamos el dato
  if(a >= 1 && a <= 4){
    //podemos continuar
    //agregaremos los campos necesarios

    let datosAvales = ['nombreAval','paternoAval','maternoAval','calleAval','noCalleAval','noCalleExtAval',
    'colAval','cpAval','celAval','idenAval','refAval','latDomAval','lngDomAval'];
    let bodyAval = '';
    let numAv = 1;

    let newOptions = '';
    let estados = document.getElementById('estadoPer');

    for(x = 0; x < estados.options.length; x++){
      let optionSel = "<option value='"+estados.options[x].value+"'>"+estados.options[x].text+"</option>";
      newOptions += optionSel;
      
    }//fin del for
    // console.log(newOptions);
    for (let nAval = 1; nAval <= a; nAval++) {

      bodyAval += `
      <h5 class='center-align'>Datos Aval ${numAv}</h5>
      <div class='input-field col s12 m4'>
        <input type='text' id='nameAval${numAv}' onkeyup="valDato(this.value,this.id)" name='nameAval${numAv}'>
        <label for='nameAval${numAv}'>Nombre Aval ${numAv}</label>
        <span id='helpernameAval${numAv}'></span>
      </div>
      <div class='input-field col s12 m4'>
        <input type='text' id='patAval${numAv}' onkeyup="valDato(this.value,this.id)" name='patAval${numAv}'>
        <label for='patAval${numAv}'>Apellido Paterno Aval ${numAv}</label>
        <span id='helperpatAval${numAv}'></span>
      </div>
      <div class='input-field col s12 m4'>
        <input type='text' id='matAval${numAv}' onkeyup="valDato(this.value,this.id)" name='matAval${numAv}'>
        <label for='matAval${numAv}'>Apellido Materno Aval ${numAv}</label>
        <span id='helpermatAval${numAv}'></span>
      </div>

      <div class='input-field col s6 m4'>
        <select id="estadoAval${numAv}" name="estadoAval${numAv}" onchange="getMunicipios(this.value,${numAv})">
        ${newOptions}
        </select>
        <label for="estadoAval${numAv}">Estado Aval ${numAv}</label>
        <span id='helperestadoAval${numAv}'></span>
      </div>
      <div class='input-field col s6 m4'>
        <select id="municipioAval${numAv}" name="municipioAval${numAv}" onchange="getLocalidades(this.value,${numAv})">
          <option value=''>Seleccione</option>
        </select>
        <label for="municipioAval${numAv}">Municipio Aval ${numAv}</label>
        <span id='helpermunicipioAval${numAv}'></span>
        
      </div>
      <div class='input-field col s6 m4'>
        <input type='text' id='localidadAval${numAv}' onkeyup="valDato(this.value,this.id)" name='localidadAval${numAv}' class='autocomplete'>
        <label for='localidadAval${numAv}'>Localidad Aval ${numAv}</label>
        <span id='helperlocalidadAval${numAv}'></span>
      </div>


      <div class='input-field col s6 m3'>
        <input type='text' id='celAval${numAv}' onkeyup="valDato(this.value,this.id)" name='celAval${numAv}'>
        <label for='celAval${numAv}'>Celular Aval ${numAv}</label>
        <span id='helpercelAval${numAv}'></span>
      </div>
      <div class='input-field col s12 m5'>
        <input type='text' id='calleAval${numAv}' onkeyup="valDato(this.value,this.id)" name='calleAval${numAv}'>
        <label for='calleAval${numAv}'>Calle Aval ${numAv}</label>
        <span id='helpercalleAval${numAv}'></span>
      </div>

      <div class="input-field col s6 m2">
        <input type='number' id='numExtAval${numAv}' name='numExtAval${numAv}'>
        <label for='numExtAval${numAv}'>No. Exterior ${numAv}</label>
        <span id='helpernumExtAval${numAv}'></span>
      </div>

      <div class='input-field col s6 m2'>
        <input type='text' id='numIntAval${numAv}' onkeyup="valDato(this.value,this.id)" name='numIntAval${numAv}'>
        <label for='numIntAval${numAv}'>No. Interior ${numAv}</label>
        <span id='helpernumIntAval${numAv}'></span>
      </div>
      <div class='input-field col s12 m6'>
        <input type='text' id='entreCalleAval${numAv}' onkeyup="valDato(this.value,this.id)" name='entreCalleAval${numAv}'>
        <label for='entreCalleAval${numAv}'>Entre Calles Aval ${numAv}</label>
        <span id='helperentreCalleAval${numAv}'></span>
      </div>

      <div class='input-field col s6 m4'>
        <input type='text' id='colAval${numAv}' onkeyup="valDato(this.value,this.id)" name='colAval${numAv}'>
        <label for='colAval${numAv}'>Colonia Aval ${numAv}</label>
        <span id='helpercolAval${numAv}'></span>
      </div>

      <div class='input-field col s6 m2'>
        <input type='text' id='cpAval${numAv}' onkeyup="valDato(this.value,this.id)" name='cpAval${numAv}'>
        <label for='cpAval${numAv}'>CP Aval ${numAv}</label>
        <span id='helpercpAval${numAv}'></span>
      </div>

      <div class='input-field col s12 m6'>
        <input type='text' id='refDomAval${numAv}' onkeyup="valDato(this.value,this.id)" name='refDomAval${numAv}'>
        <label for='refDomAval${numAv}'>Referencia Aval ${numAv}</label>
        <span id='helperrefDomAval${numAv}'></span>
      </div>

      <div class='row'>
          <input type='hidden' id='latDomAval${numAv}' name='latDomAval${numAv}'>
          <input type='hidden' id='lngDomAval${numAv}' name='lngDomAval${numAv}'>
      </div>
      <div class='row'>
        <div class='col s12'>
          <div class='divider'></div>
        </div>
      </div>
    `;
    numAv++;

    }//fin del for insert campos

    bodyAval += `<div class="row">
    <h5 class="center-align">Indica la ubicación de los Avales</h5>
    <div id="mapAvales" class="col s12 l8 offset-l2" style="height:400px;"></div></div>`;

    document.getElementById('resDataAval').innerHTML = bodyAval;

    var elems2 = document.querySelectorAll('select');
    var instances2 = M.FormSelect.init(elems2, options);

    //creamos un nuevo for para inicializar los mapas de los avales
    initMapAval(a);

  }else{
    //seteamos el valor a 0
    document.getElementById('avalNumbers').value = 0;
    //si tiene valor el div lo ponemos en 0
    document.getElementById('resDataAval').innerHTML = "";
  }

}//fin de funcion setAvalNumbers

function getLocalidades(municipio,avalNumero){
  let dato = new FormData();
  let envia = new XMLHttpRequest();

  dato.append('municipioCheck',municipio);
  envia.open('POST','../includes/operations/saveVeriDom.php',false);

  envia.send(dato);
  if(envia.status == 200){
    let res = envia.responseText;
    if(res.split("dataError|").length == 1){
      let result = JSON.parse(res);
      let datos = new Array();
      for(let x = 0; x < result.length; x++){
        let nombreMunicipio = result[x].nombre;
        datos[nombreMunicipio] = null;
      }

       let elInput = '';
       if(avalNumero > 0){
        elInput = document.getElementById('localidadAval'+avalNumero);
       }else{
        elInput = document.getElementById('localidadPer');
       }
       let instanceAuto = M.Autocomplete.init(elInput,{
        limit:'infinit',
        minLength:1

      });

      instanceAuto.updateData(datos);
      
    }else{
      //ocurrio un error al obtener la informacion
      let error = res.split("dataError|")[1];
      swal.fire(
        'Ha ocurrido un error',
        'Ocurrió un error al obtener la información: '+error,
        'error'
      )
    }
  }else{
    //error de comunicacion
    swal.fire(
      'Servidor Inalcanzable',
      'Verifica tu conexión a internet',
      'warning'
    )
  }
}

function getMunicipios(estado,avalNumero){
  let dato = new FormData();
  let envia = new XMLHttpRequest();

  dato.append('estadoCheck',estado);
  envia.open('POST','../includes/operations/saveVeriDom.php',false);

  envia.send(dato);

  if(envia.status == 200){
    let res = envia.responseText;
    if(res.split('dataError|').length == 1){
      let response = JSON.parse(res);
      //ahora generamos los option value del combo
      let opt = "<option value = '' selected disabled>Seleccione</option>";

      for(let x = 0; x < response.length; x++){
        let name = response[x].nombre;
        let id = response[x].id;
        opt += `<option value='${id}'>${name}</option>`;
      }//fin del for
      if(avalNumero > 0){
        document.getElementById('municipioAval'+avalNumero).innerHTML = opt;
      }else{
        document.getElementById('municipioPer').innerHTML = opt;
      }
      //inicializamos de nuevo los combos
      var options = '';
      var elems = document.querySelectorAll('select');
      var instances = M.FormSelect.init(elems, options);
    }else{
      let error = res.split('dataError|')[1];
      swal.fire(
        'Ha ocurrido un error',
        'Ocurrió un error inesperado: '+error,
        'error'
      )
    }
  }else{
    //error de comunicacion
    swal.fire(
      'Servidor Inalcanzable',
      'Verifica tu conexión a internet',
      'warning'
    )
  }
}//fin de funcion getMunicipios

function getEstados(idCombo){

  let comboEstados = document.getElementById('estadoPer');
  let copied = comboEstados.cloneNode(true);

  document.getElementById(idCombo).innerHTML = copied;

  // let datos = new FormData();
  // let envia = new XMLHttpRequest();

  // datos.append('getEstados','si');

  // envia.open('POST','../includes/operations/saveVeriDom.php',false);
  // envia.send(datos);

  // if(envia == 200){
  //   let res = envia.responseText;
  //   if(res.split("dataError|").length == 1){
  //     let response = JSON.parse(res);
  //     console.log(response);
  //   }else{
  //     let error = res.split('dataError|')[1];
  //     swal.fire(
  //       'Ha ocurrido un error',
  //       'Ocurrió un error inesperado: '+error,
  //       'error'
  //     )
  //   }
  // }else{
  //   //error de comunicacion
  //   swal.fire(
  //     'Servidor Inalcanzable',
  //     'Verifica tu conexión a internet',
  //     'warning'
  //   )
  // }
}//fin de la funcion getEstados