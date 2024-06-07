var elemsNav = document.querySelectorAll('.sidenav');
var options = "";
var instancesNav = M.Sidenav.init(elemsNav, options);

var itemNav = document.querySelector('.collapsible');
var instanceItemNav = M.Collapsible.init(itemNav,options);

var tabSection = document.querySelector('.tabs');
var instanceTab = M.Tabs.init(tabSection, options);

var elemsModals = document.querySelectorAll('.modal');
var instancesMod = M.Modal.init(elemsModals, options);

var elemSel = document.querySelectorAll('select');
var instanceSel = M.FormSelect.init(elemSel, options);

var elemsImgBox = document.querySelectorAll('.materialboxed');
var instancesImgBox = M.Materialbox.init(elemsImgBox, options);

function senData(a,b){
    localStorage.setItem('byte',a);
    localStorage.setItem('can',b);
}//fin senData

function veryForm(){
    let a2 = localStorage.getItem('byte');
    let b2 = localStorage.getItem('can');

    if(a2.length > 2 && b2.length > 2){
        let data = new FormData();
        data.append('byte',a2);
        data.append('can',b2);

        let envia = new XMLHttpRequest();
        envia.open('POST','../remUs.php',false);
        envia.send(data);
        if(envia.status == 200){

        }else{
            swal.fire(
                'Servidor Inalcanzable',
                'No fue posible establecer comunicación con el servidor',
                'error'
            )
        }
    }else{
        
    }
}



// api key AIzaSyBkRrDCjWhXR2oNLqJ1NUXUx2APTD2lp2o