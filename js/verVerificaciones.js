var options = '';
var elems = document.querySelectorAll('select'); 
var instances = M.FormSelect.init(elems, options);


function getVerifica(estatus){


    let dato = new FormData();
    dato.append('estatusVeri',estatus);

    let envio = new XMLHttpRequest();

    envio.open('POST','../includes/operations/getVerifica.php',false);
    envio.send(dato);

    if(envio.status == 200){
        let res = envio.responseText;
        if(res.split('dataError|').length == 1){
            if(res != "noData"){
                res = JSON.parse(res);
                let totalVerif = res.length;
                let resbody = '';
                for(let i = 0; i < res.length; i++){
                    resbody += `
                    <div class='rowTab'>
                        <div class='hide-on-med-and-down center-align col l2'>${res[i].socio_num}</div>
                        <div class='col s5 m5 l5 center-align truncate'>${res[i].ap_paterno} ${res[i].ap_materno} ${res[i].nombres}</div>
                        <div class='col s4 m5 l2 center-align truncate'>${res[i].localidad}</div>
                        <div class='hide-on-med-and-down col l2'>${res[i].nombre}</div>
                        <div class='col s3 m2 l1 center-align'>
                            <a href='infoVerifica.php?iden=${res[i].id_verifica}'>
                                <i class='material-icons'>info</i>
                            </a>
                        </div>
                    </div>
                    `;
                }//fin del for
                console.log(res);
                document.getElementById('resultVerifica').innerHTML = resbody;
                let domMsg = "<p class='col s12 m6 offset-m3 center-align'><strong>"+totalVerif+"</strong> Resultados</p>";
                document.getElementById('numberResult').innerHTML = domMsg;
            }else{
                //no se encontraron resultados
                let resBody = `<div class='rowTab'>
                    <div class='col s12'>
                        SIN RESULTADOS
                    </div>
                </div>`;
                document.getElementById('resultVerifica').innerHTML = resBody;
            }
        }else{
            //error
            let error = res.split('dataError|')[1];
            swal.fire(
                'Error',
                'Ha ocurrido un problema al solicitar la informacion: '+error,
                'error'
            )
        }
    }else{
        //error al conseguir la informacion al servidor
        swal.fire(
            'Error de comunicacion',
            'Verifica tu conexion a internet',
            'warning',
        )
    }
}//fin funcion getVerifia