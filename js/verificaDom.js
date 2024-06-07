
var options = ''; 
var elems = document.querySelectorAll('.collapsible');
var instances = M.Collapsible.init(elems, options);

var elemSel = document.querySelectorAll('select'); 
var instanceSelec = M.FormSelect.init(elemSel, options);


var elemsModal = document.querySelectorAll('.modal');
var instancesModal = M.Modal.init(elemsModal, options);

var btnFirma = document.querySelector('#btnFirmar');

btnFirma.addEventListener('click',function(){
  let divFirma = document.querySelector('#contenidoFirma');

  divFirma.classList.remove('hide');
})//fin btnFirma action

let miCanvas = document.querySelector('#firmaDigital');
var codigoCanva;
let lineas = [];
let correccionX = 0;
let correccionY = 500;
let pintarLinea = false;
// Marca el nuevo punto
let nuevaPosicionX = 0;
let nuevaPosicionY = 0;

let posicion = miCanvas.getBoundingClientRect();
correccionX = posicion.x;
correccionY = posicion.y;

miCanvas.width = 650;
miCanvas.height = 300;

    //======================================================================
    // FUNCIONES
    //======================================================================

    /**
     * Funcion que empieza a dibujar la linea
     */
    function empezarDibujo () {
        pintarLinea = true;
        lineas.push([]);
        codigoCanva = lineas;
        console.log(codigoCanva);
    };
    
    /**
     * Funcion que guarda la posicion de la nueva línea
     */
    function guardarLinea() {
        lineas[lineas.length - 1].push({
            x: nuevaPosicionX,
            y: nuevaPosicionY
        });
    }

    /**
     * Funcion dibuja la linea
     */
    function dibujarLinea (event) {
        event.preventDefault();
        if (pintarLinea) {
            let ctx = miCanvas.getContext('2d')
            // Estilos de linea
            ctx.lineJoin = ctx.lineCap = 'round';
            ctx.lineWidth = 2;
            // Color de la linea
            ctx.strokeStyle = '#000';
            // Marca el nuevo punto
            if (event.changedTouches == undefined) {
                // Versión ratón
                nuevaPosicionX = event.layerX;
                nuevaPosicionY = event.layerY;
            } else {
                // Versión touch, pantalla tactil
                nuevaPosicionX = event.changedTouches[0].pageX - 110;
                nuevaPosicionY = event.changedTouches[0].pageY - 605;
            }
            // Guarda la linea
            guardarLinea();
            // Redibuja todas las lineas guardadas
            ctx.beginPath();
            lineas.forEach(function (segmento) {
                ctx.moveTo(segmento[0].x, segmento[0].y);
                segmento.forEach(function (punto, index) {
                    ctx.lineTo(punto.x, punto.y);
                });
            });
            ctx.stroke();
        }
    }

    /**
     * Funcion que deja de dibujar la linea
     */
    function pararDibujar () {
        pintarLinea = false;
        guardarLinea();
    }

    miCanvas.addEventListener('mousedown', empezarDibujo, false);
    miCanvas.addEventListener('mousemove', dibujarLinea, false);
    miCanvas.addEventListener('mouseup', pararDibujar, false);

    // Eventos pantallas táctiles
    miCanvas.addEventListener('touchstart', empezarDibujo, false);
    miCanvas.addEventListener('touchmove', dibujarLinea, false);