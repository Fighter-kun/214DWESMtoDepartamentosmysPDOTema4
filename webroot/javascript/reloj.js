var time = setInterval(function () {
    startTime()
}, 1000);

function startTime() {
    // Obtenemos la fecha actual
    var today = new Date();

    // Obtén las referencias a los elementos del reloj y la fecha
    var clockElement = document.getElementById("clock");
    var dateElement = document.getElementById("date");

    // Obtenemos las horas, minutos y segundos
    var hr = today.getHours();
    var min = today.getMinutes();
    var sec = today.getSeconds();

    // Formateamos las horas, minutos y segundos con la función checkTime
    hr = checkTime(hr);
    min = checkTime(min);
    sec = checkTime(sec);

    // Actualiza el contenido del reloj con imágenes en lugar de texto
    updateClockWithImages(clockElement, hr + ":" + min + ":" + sec);


    // Definimos arrays para los meses y días de la semana
    var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    var days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

    // Obtenemos el nombre del día de la semana, el día del mes, el nombre del mes y el año
    var curWeekDay = days[today.getDay()];
    var curDay = today.getDate();
    var curMonth = months[today.getMonth()];
    var curYear = today.getFullYear();

    // Construimos la cadena de la fecha y la mostramos en el elemento con id "date"
    var date = curWeekDay + ", " + curDay + " " + curMonth + " " + curYear;
    document.getElementById("date").innerHTML = date;

    // Configuramos la función para que se llame a sí misma cada 500 milisegundos (medio segundo)
    
}

// Función para agregar un cero delante de números menores a 10
function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

// Función para actualizar el contenido del reloj con imágenes
function updateClockWithImages(clockElement, timeString) {
    // Dividir la cadena de tiempo en dígitos individuales
    var digits = timeString.split('');

    // Mapear cada dígito a su imagen correspondiente
    var digitImages = digits.map(function (digit) {
        // Utilizar la ruta adecuada para cada imagen
        // return '<div class="digit" style="background-image: url(\'../webroot/media/images/' + digit + '.png\')"></div>';
        if (digit == ':') {
            return '<img class="digit" src="../webroot/media/images/puntos.png"/>';
        } else {
            return '<img class="digit" src="../webroot/media/images/' + digit + '.png"/>';
        }
        
        
    });

    // Unir las imágenes y establecerlas como contenido del reloj
    clockElement.innerHTML = digitImages.join('');
}