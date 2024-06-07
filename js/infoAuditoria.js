
let inputBuscar = document.getElementById("busArtiByCod");
inputBuscar.addEventListener('change', function(){
    //verificamos que no este en blanco
    let valor = inputBuscar.value.trim().split("").length;
    if(valor > 1){
        let codigo = inputBuscar.value;

        let datos = new FormData();
        datos.append("buscarByCod",codigo);
        
        let envio = new XMLHttpRequest();
        envio.open("POST","../includes/operations/auditaInventario.php",false);
        envio.send(datos);

        if(envio.status == 200){
            if(envio.responseText != "NoDataResult"){
                let info = JSON.parse(envio.responseText);
                
                let nuevaTabla = `
                <table><thead><tr>
                <th>Id</th>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Lugar Resguardo</th>
                <th>Sucursal</th>
                <th>Ver</th>
                </tr></thead><tbody>`;
                for(let i = 0; i< info.length; i++){
                    let idInven = info[i]['id_inventario'];
                    let nombre = info[i]['nombre_objeto'];
                    let lugarRes = info[i]['lugar_resguardo'];
                    let sucurRes = info[i]['sucursal_resguardo'];
                    let codigo = info[i]['codigo'];

                    nuevaTabla += `<tr>
                        <td>${idInven}</td>
                        <td>${codigo}</td>
                        <td class='truncate'>${nombre}</td>
                        <td>${lugarRes}</td>
                        <td>${sucurRes}</td>
                        
                        <td><a href='ver-obj-ind.php?obj=${idInven}' target='_blank'>
                            <i class='material-icons'>info</i>
                            </a>
                        </td>
                    </tr>`;
                    
                }//fin del for

                nuevaTabla += `</tbody></table>`;

                document.getElementById("resulTable").innerHTML = nuevaTabla;

            }else{
                //sin resultados
            }
        }else{
            Swal.fire(
                'Servidor Inalcansable',
                'Verifica tu conexion a internet',
                'error'
            )
        }

    }else{
        //no hacemos nada
    }
})


function getBySelects(){
    let clasi = document.getElementById("clasificacionObjeto").value;
    let sucur = document.getElementById("sucursalResguardo").value;
    let lug = document.getElementById("lugarResguaro").value;
  
    if(clasi != "" || sucur != "" ||lug != ""){
      //realizamos e envio de informacion
      let datos = new FormData();
      datos.append("clasiSel",clasi);
      datos.append("sucurSel",sucur);
      datos.append("lugSel",lug);
      datos.append("prodByCombos","prodss");
  
      let envio = new XMLHttpRequest();
      envio.open("POST","../includes/operations/auditaInventario.php",false);
  
      envio.send(datos);
      if(envio.status == 200){
        console.log(envio.responseText);
      }else{
        //sin servidor
        Swal.fire(
          'Servidor Inalcansable',
          'Verifique su conexion a internet',
          'error'
        )
      }
    }
  }

  function generaFormato(a){
    window.open("generaFormatoRev.php?infoVeri="+a ,"_blank");

  }

  if(document.getElementById("finalizarRev")){
    let finalizar = document.getElementById("finalizarRev");

    finalizar.addEventListener("click", function(){
        //confirmamos que si desea finalizar
        Swal.fire({
            title: 'Estas seguro de finalizar?',
            text: 'Una vez confirmado no se podran realizar acciones en esta seccion.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, finalizar tarea'
        }).then((result) => {
            console.log(result.value);
            if(result.value == true){
                //se finaliza la tarea
                //enviamos la peticion 
                let datos = new FormData();
                let info = document.getElementById("revData").value;
                datos.append("finalizarRev",info);

                let enviar = new XMLHttpRequest();
                enviar.open("POST","../includes/operations/auditaInventario.php",false);

                enviar.send(datos);
                if(enviar.status == 200){
                    let res = enviar.responseText;
                    if(res == "OperationComplete"){
                        Swal.fire(
                            'Operacion Completada',
                            'Se ha actualizado la informacion correctamente',
                            'success'
                        ).then(function(){
                            location.reload();
                        })
                    }else{
                        //respuesta incorrecta, veroificamos el error
                        let err = res.split("DataError|")[1];
                        Swal.fire(
                            'Ha ocurrido un error',
                            'Verificar: '+err,
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
            }
        })
    })
  }

