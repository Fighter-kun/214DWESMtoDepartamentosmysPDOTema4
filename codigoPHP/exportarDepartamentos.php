<?php

/**
 * @author Carlos García Cachón
 * @version 1.0
 * @since 13/01/2024
 * @copyright Todos los derechos reservados a Carlos García
 * 
 * @Annotation Proyecto MtoDepartamentosmysPDOTema4 - Parte de 'exportarDepartamentos' 
 * 
 */
// Incluyo el fichero de configuración de la BD
require_once "../config/confDBPDO.php";

//Abro un bloque try catch para tener un mayor control de los errores
try {
    $miDB = new PDO(DSN, USERNAME, PASSWORD); // Instanciamos un objeto PDO y establecemos la conexión
    // CONSULTA - 'SELECT'
    $consultaExportar = 'SELECT * FROM T02_Departamento';
    // Preparamos la consulta que previamente vamos a ejecutar
    $resultadoConsultaExportar = $miDB->prepare($consultaExportar);
    // Ejecutamos la consulta
    $resultadoConsultaExportar->execute();

    // JSON
    // Creo un objeto con el resultado de la consulta
    $oResultadoJson = $resultadoConsultaExportar->fetchObject();

    $aDepartamentos = []; // Inicializamos un array vacío para almacenar todos los departamentos
    $numeroDepartamento = 0; // Inicializamos el contador
    // Recorro los registros que devuelve la consulta y obtengo por cada valor su resultado
    while ($oResultadoJson) {
        //Guardamos los valores en un array asociativo
        $aDepartamento = [
            'T02_CodDepartamento' => $oResultadoJson->T02_CodDepartamento,
            'T02_DescDepartamento' => $oResultadoJson->T02_DescDepartamento,
            'T02_FechaCreacionDepartamento' => $oResultadoJson->T02_FechaCreacionDepartamento,
            'T02_VolumenDeNegocio' => $oResultadoJson->T02_VolumenDeNegocio,
            'T02_FechaBajaDepartamento' => $oResultadoJson->T02_FechaBajaDepartamento
        ];

        // Añadimos el array $aDepartamento al array $aDepartamentos
        $aDepartamentos[] = $aDepartamento;

        //Incremento el contador de departamentos para almacenar información en la siguiente posición        
        $numeroDepartamento++;

        //Guardo el registro actual y avanzo el puntero al siguiente registro que obtengo de la consulta
        $oResultadoJson = $resultadoConsultaExportar->fetchObject();
    }


    /**
     * La funcion json_encode devuelve un string con la representacion JSON
     * Le pasamos el array aDepartamentos y utilizanos el atributo JSON_PRRETY_PRINT para que use espacios en blanco para formatear los datos devueltos.
     */
    $json = json_encode($aDepartamentos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    /**
     * Mediante la funcion file_put_contents() podremos escribir informacion en un fichero
     * Pasandole como parametros la ruta donde queresmos que se guarde y el que queremos sobrescribir
     * JSON_UNESCAPED_UNICODE: Codifica caracteres Unicode multibyte literalmente
     */
    // Ruta del archivo JSON
    $rutaArchivoJSON = "../tmp/departamentos.json";

    // Verificamos que existe una carpeta para archivos temporales
    if (!file_exists("../tmp/")) {
        mkdir("../tmp/", 0777, true); // En caso negativo la creamos
    }

    // Intenta escribir en el archivo
    if (file_put_contents($rutaArchivoJSON, $json) !== false) {
        echo "<br><span style='color: green'>Exportado correctamente</span>";
    } else {
        echo "<br><span style='color: red'>Error al exportar el archivo</span>";
    }

    // XML

    /**
     * Instanciamos el nuevo documento usando el objeto DOMDocument
     * Le asignamos dos parametros -> Version, Codificacion XML
     */
    $archivoXML = new DOMDocument("1.0", "utf-8");

    // Le decimos que queremos formatear el codigo poniendo a true la propiedad formatOutput
    $archivoXML->formatOutput = true;

    /*     * Creo el nodo raiz departamentos del de dependeran los demas
     * createElement() -> Crea un nuevo nodo elemento
     * En este caso le pasamos como parametro el nombre del elemento
     * */
    $nDepartamentos = $archivoXML->createElement('Departamentos');

    /*     * Introduzco el nodo raiz en el archivo
     * appenChild() -> Añade un nuevo hijo al final de los hijos
     */
    $root = $archivoXML->appendChild($nDepartamentos);

    // Ejecutamos la consulta de nuevo para reiniciar el puntero
    $resultadoConsultaExportar->execute();
    // Guardo el primer registro como un objeto
    $oResultadoXML = $resultadoConsultaExportar->fetchObject();

    /**
     * Recorro los registros que devuelve la consulta y obtengo por cada valor su resultado
     */
    while ($oResultadoXML) {
        // Guardamos los valores en un array asociativo
        // Creo el nodo departamento para cada uno de ellos
        $nDepartamento = $root->appendChild($archivoXML->createElement('Departamento'));

        // Creo el elemento con el nombre CodDepartamento y despues el valor obtenido de la consulta
        $nDepartamento->appendChild($archivoXML->createElement('T02_CodDepartamento', $oResultadoXML->T02_CodDepartamento));

        // Creo el elemento con el nombre DescDepartamento y despues el valor obtenido de la consulta
        $nDepartamento->appendChild($archivoXML->createElement('T02_DescDepartamento', $oResultadoXML->T02_DescDepartamento));

        // Creo el elemento con el nombre FechaCreacion Departamento y despues el valor obtenido de la consulta
        $nDepartamento->appendChild($archivoXML->createElement('T02_FechaCreacionDepartamento', $oResultadoXML->T02_FechaCreacionDepartamento));

        // Creo el elemento con el nombre VolumenNegocio y despues el valor obtenido de la consulta          
        $nDepartamento->appendChild($archivoXML->createElement('T02_VolumenDeNegocio', $oResultadoXML->T02_VolumenDeNegocio));

        /*
         * Creo el elemento con el nombre FechaBaja y despues por medio de un operador ternario 
         * en caso de ser 'null' su valor, le almaceno NULL (Si no se queda vacío) 
         */ 
        $nDepartamento->appendChild($archivoXML->createElement('T02_FechaBajaDepartamento', ($oResultadoXML->T02_FechaBajaDepartamento !== null) ? $oResultadoXML->T02_FechaBajaDepartamento : 'NULL'));

        //Guardo el registro actual y avanzo el puntero al siguiente registro que obtengo de la consulta
        $oResultadoXML = $resultadoConsultaExportar->fetchObject();
    }

    /**
     * Guardamos el archivo en la ruta indicada
     * save() -> Copia el árbol XML interno a un archivo
     */
    // Intenta escribir en el archivo
    if ($archivoXML->save('../tmp/departamentos.xml') !== false) {
        echo "<br><span style='color: green'>Exportado correctamente</span>";
    } else {
        echo "<br><span style='color: red'>Error al exportar el archivo</span>";
    }

    // Ruta del archivo ZIP
    $rutaArchivoZIP = "../tmp/departamentos.zip";

    $oZip = new ZipArchive(); // Intancio un objeto de la clase 'ZipArchive()' para guardar en zip ambos archivos 
    /*
     * En la siguiente condición abrimos el objeto '$oZIP' y en los parámetros le indicamos
     * la ruta donde se encuentra el ZIP y que debe crear el archivo si no existe, o actualizarlo.
     * Comprobamos con el operador '=== true' que 'open()' a sido exitoso.
     */
    if ($oZip->open($rutaArchivoZIP, ZipArchive::CREATE) === true) {

        // Agregamos los archivos JSON y XML al ZIP
        $oZip->addFile("../tmp/departamentos.json", "departamentos.json");
        $oZip->addFile("../tmp/departamentos.xml", "departamentos.xml");

        $oZip->close(); // Cerramos el ZIP
        
        // Descarga el archivo ZIP
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . basename($rutaArchivoZIP));
        header('Content-Length: ' . filesize($rutaArchivoZIP));

        /*
         * La función 'ob_clean()' la utilizaremos para limpiar el almacenamiento del 
         * buffer antes de enviar los datos al navegador de manera que solo se manden el arhivo zip 
         */
        ob_clean();
        
        /*
         * La función 'flush()' asegura que todos los datos almacenados en el buffer se envíen 
         * inmediatamente al navegador para evitar que el navegador espere a que se ejecute todo el script
         */
        flush();

        /*
         * La función 'readfile()' que recibe como parámetro la ruta del archivo zip, se encarga de leer
         * el archivo y enviarlo directamente a la salida del buffer
         */
        readfile($rutaArchivoZIP);
        
        // Por último eliminamos los archivos temporales después de la descarga
        unlink($rutaArchivoZIP);
        unlink("../tmp/departamentos.json");
        unlink("../tmp/departamentos.xml");
        exit(); // Detenemos el script
    } else {
        echo "<br><span style='color: red'>Error al exportar el archivo ZIP</span>";
    }

    //Controlamos las excepciones mediante la clase PDOException
} catch (PDOException $miExcepcionPDO) {
    /**
     * Revierte o deshace los cambios
     * Esto solo se usara si estamos usando consultas preparadas
     */
    $miDB->rollback();

    //Almacenamos el código del error de la excepción en la variable '$errorExcepcion'
    $errorExcepcion = $miExcepcionPDO->getCode();

    // Almacenamos el mensaje de la excepción en la variable '$mensajeExcepcion'
    $mensajeExcepcion = $miExcepcionPDO->getMessage();

    // Mostramos el mensaje de la excepción
    echo "<span style='color: red'>Error: </span>" . $mensajeExcepcion . "<br>";

    // Mostramos el código de la excepción
    echo "<span style='color: red'>Código del error: </span>" . $errorExcepcion;

    //En culaquier cosa cerramos la sesion
} finally {
    // El metodo unset sirve para cerrar la sesion con la base de datos
    unset($miDB);
}