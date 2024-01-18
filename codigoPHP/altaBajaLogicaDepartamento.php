<?php

/**
 * @author Carlos García Cachón
 * @version 1.0
 * @since 15/01/2024
 * @copyright Todos los derechos reservados a Carlos García
 * 
 * @Annotation Proyecto MtoDepartamentosmysPDOTema4 - Parte de 'altaBajaLogicaDepartamentos' 
 * 
 */
// Incluyo el fichero de configuración de la BD
require_once "../config/confDBPDO.php";

// Recuperamos el código del departamento que hemos seleccionamo mediante el metodo 'POST'
$codDepartamentoSeleccionado = $_REQUEST['codDepartamento'];
try {
    $miDB = new PDO(DSN, USERNAME, PASSWORD); // Instanciamos un objeto PDO y establecemos la conexión
    // CONSULTA
    // Hacemos un 'SELECT' sobre la tabla 'T02_Departamento' para recuperar toda la información del departamento que vamos a modificar
    $sqlDepartamento = $miDB->prepare("SELECT * FROM T02_Departamento WHERE T02_CodDepartamento = '" . $codDepartamentoSeleccionado . "';");

    $sqlDepartamento->execute(); // Ejecuto la consulta con el array de parametros
    $oDepartamentoAEditar = $sqlDepartamento->fetchObject(); // Obtengo un objeto con el departamento
    // Almaceno la información de la fecha de baja del departamento
    $fechaBajaDepartamento = $oDepartamentoAEditar->T02_FechaBajaDepartamento;
    
    // Ahora pregunto si su valor es 'NULL'
    if (is_null($fechaBajaDepartamento)) {
        // En caso positivo hago un UPDATE para deshabilitar el departamento
        
        // Almaceno el valor de la fecha actual formateada
        $fechaYHoraActualCreacion = new DateTime('now', new DateTimeZone('Europe/Madrid'));
        $fechaYHoraActualBajaDepartamento = $fechaYHoraActualCreacion->format('Y-m-d H:i:s');
        $sqlBajaDepartamento = $miDB->prepare("UPDATE T02_Departamento SET T02_FechaBajaDepartamento = '" . $fechaYHoraActualBajaDepartamento . "' WHERE T02_CodDepartamento = '" . $codDepartamentoSeleccionado . "';");
        $sqlBajaDepartamento->execute(); // Ejecuto la consulta 
    } else {
        // En caso negativo hago un UPDATE para habilitar el departamento
        $sqlAltaDepartamento = $miDB->prepare("UPDATE T02_Departamento SET T02_FechaBajaDepartamento = NULL WHERE T02_CodDepartamento = '" . $codDepartamentoSeleccionado . "';");
        $sqlAltaDepartamento->execute(); // Ejecuto la consulta 
    }
    
    header('Location: ../indexMtoDepartamentos.php'); // Llevo al usuario de vuelta al index
    exit();
    
} catch (PDOException $miExcepcionPDO) {
    $errorExcepcion = $miExcepcionPDO->getCode(); // Almacenamos el código del error de la excepción en la variable '$errorExcepcion'
    $mensajeExcepcion = $miExcepcionPDO->getMessage(); // Almacenamos el mensaje de la excepción en la variable '$mensajeExcepcion'

    echo ("<span class='errorException'>Error: </span>" . $mensajeExcepcion . "<br>"); // Mostramos el mensaje de la excepción
    echo ("<span class='errorException'>Código del error: </span>" . $errorExcepcion); // Mostramos el código de la excepción
} finally {
    unset($miDB); //Cerramos la conexión con la base de datos
}