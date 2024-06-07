

if(document.getElementById("verInvent")){
    let btnInventario = document.getElementById("verInvent");
    btnInventario.addEventListener("click", function(){
        window.location = "inventario.php";
    });
}

if(document.getElementById("verProv")){
    let btnProveedor = document.getElementById("verProv");
    btnProveedor.addEventListener("click", function(){
        window.location = "proveedores.php";
    });
}

if(document.getElementById("verControles")){
    let btnControl = document.getElementById("verControles");
    btnControl.addEventListener("click",function(){
        window.location = "verControles.php";
    });
}

if(document.getElementById("updatePicture")){
    let btnPicture = document.getElementById("updatePicture");
    btnPicture.addEventListener("click", function(){
        window.location = "updatePicture.php";
    })
}

if(document.getElementById("showTickets")){
    let btnTickets = document.getElementById("showTickets");
    btnTickets.addEventListener("click",function(){
        window.location = "ver-tickets.php";
    })
}

if(document.getElementById("showCardTicket")){
    let btnShowT = document.getElementById("showCardTicket");
    btnShowT.addEventListener("click",function(){
        window.location = "ver-tickets.php";
    })
}

if(document.getElementById("showManuales")){
    let btnManuales = document.getElementById("showManuales");
    btnManuales.addEventListener("click",function(){
        window.location = "ver-manuales.php";
    })
}


