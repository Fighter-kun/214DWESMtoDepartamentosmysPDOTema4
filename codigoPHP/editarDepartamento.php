<?php
/**
 * @author Carlos García Cachón
 * @version 1.0
 * @since 11/01/2024
 * @copyright Todos los derechos reservados a Carlos García
 * 
 * @Annotation Proyecto MtoDepartamentosmysPDOTema4 - Parte de 'editarDepartamento' 
 * 
 */

// Estructura del botón cancelar, si el ususario pulsa el botón 'cancelar'
if (isset($_REQUEST['cancelar'])) {
    header('Location: ../indexMtoDepartamentos.php'); // Llevo al usuario de vuelta al index
    exit();
}

// Incluyo la librería de validación para comprobar los campos y el fichero de configuración de la BD
require_once '../core/231018libreriaValidacion.php';
require_once "../config/confDBPDO.php";

// Declaracion de la variable de confirmación de envio de formulario correcto
$entradaOK = true;

// Declaramos el array de errores y lo inicializamos vacío
$aErrores = ['T02_DescDepartamento' => '',
            'T02_VolumenDeNegocio' => ''];

// Recuperamos el código del departamento que hemos seleccionamo mediante el metodo 'POST'
$codDepartamentoSeleccionado = $_REQUEST['codDepartamento'];
// Bloque para recoger datos que mostramos en el formulario
try {
    $miDB = new PDO(DSN, USERNAME, PASSWORD); // Instanciamos un objeto PDO y establecemos la conexión
    // CONSULTA
    // Hacemos un 'SELECT' sobre la tabla 'T02_Departamento' para recuperar toda la información del departamento que vamos a modificar
    $sqlDepartamento = $miDB->prepare("SELECT * FROM T02_Departamento WHERE T02_CodDepartamento = '" . $codDepartamentoSeleccionado . "';");

    $sqlDepartamento->execute(); // Ejecuto la consulta con el array de parametros
    $oDepartamentoAEditar = $sqlDepartamento->fetchObject(); // Obtengo un objeto con el departamento
    
    // Almaceno la información del departamento actual en las siguiente variables, para mostrarlas en el formulario
    $codDepartamentoAEditar = $oDepartamentoAEditar->T02_CodDepartamento;
    $descripcionDepartamentoAEditar = $oDepartamentoAEditar->T02_DescDepartamento;
    $fechaCreacionDepartamentoAEditar = $oDepartamentoAEditar->T02_FechaCreacionDepartamento;
    $volumenNegocioAEditar = $oDepartamentoAEditar->T02_VolumenDeNegocio;
    $fechaBajaDepartamentoAEditar = $oDepartamentoAEditar->T02_FechaBajaDepartamento;
    
    if (isset($_REQUEST['confirmarCambios'])) { // Comprobamos que el usuario haya enviado el formulario para 'confirmar los cambios'
        $aErrores['T02_DescDepartamento'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['T02_DescDepartamento'], 255, 3, 0);
        $aErrores['T02_VolumenDeNegocio'] = validacionFormularios::comprobarFloat($_REQUEST['T02_VolumenDeNegocio_'], PHP_FLOAT_MAX, -PHP_FLOAT_MAX, 0); 

        // Recorremos el array de errores
        foreach ($aErrores as $campo => $error) {
            if ($error != null) { // Comprobamos que el campo no esté vacio
                $entradaOK = false; // En caso de que haya algún error le asignamos a entradaOK el valor false para que vuelva a rellenar el formulario
                $_REQUEST[$campo] = ""; // Limpiamos los campos del formulario
            }
        }
    } else {
        $entradaOK = false; // Si el usuario no ha enviado el formulario asignamos a entradaOK el valor false para que rellene el formulario
    }
    if ($entradaOK) { // Si el usuario ha rellenado el formulario correctamente rellenamos el array aFormulario con las respuestas introducidas por el usuario
            // CONSULTA
            // Usamos un 'UPDATE' para aplicar los cambios de la nueva descripción o volumen de negocio 
            $consultaUpdate = <<<CONSULTA
                UPDATE T02_Departamento SET 
                T02_DescDepartamento='{$_REQUEST['T02_DescDepartamento']}', 
                T02_VolumenDeNegocio='{$_REQUEST['T02_VolumenDeNegocio_']}'
                WHERE T02_CodDepartamento='{$codDepartamentoSeleccionado}';
            CONSULTA;

            $sqlUpdateDepartamento = $miDB->prepare($consultaUpdate); // Preparamos la consulta
            $sqlUpdateDepartamento->execute(); // Ejecutamos la consulta
            header('Location: ../indexMtoDepartamentos.php'); // Llevo al usuario de vuelta al index
            exit();
  
    }
    else {// Si el usuario no ha rellenado el formulario correctamente volverá a rellenarlo
        ?>
        <!DOCTYPE html>
        <!--
            Descripción: 214DWESMtoDepartamentosmysPDOTema4 - editarDepartamento
            Autor: Carlos García Cachón
            Fecha de creación/modificación: 12/01/2024
        --> 
        <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="author" content="Carlos García Cachón">
                <meta name="description" content="CodigoLogin">
                <meta name="keywords" content="CodigoLogin">
                <meta name="generator" content="Apache NetBeans IDE 19">
                <meta name="generator" content="60">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <title>Carlos García Cachón</title>
                <link rel="icon" type="image/jpg" href="../webroot/media/images/favicon.ico"/>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
                      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
                <link rel="stylesheet" href="../webroot/css/style.css">
                <style>
                    .obligatorio {
                        background-color: #ffff7a;
                    }
                    .bloqueado:disabled {
                        background-color: #665 ;
                        color: white;
                    }
                    .error {
                        color: red;
                        width: 450px;
                    }
                    .errorException {
                        color:#FF0000;
                        font-weight:bold;
                    }
                    .respuestaCorrecta {
                        color:#4CAF50;
                        font-weight:bold;
                    }
                    .btn-danger {
                        background-color: red;
                    }
                </style>
            </head>

            <body>
                <header class="text-center">
                    <h1>Mantenimiento Departamentos:</h1>
                </header>
                <main>
                    <div class="container mt-3">
                        <div class="row d-flex justify-content-start">
                            <div class="col">
                                <!-- Codigo del formulario -->
                                <form name="editarDepartamento" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                    <fieldset>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th class="rounded-top" colspan="3"><legend>Modificar Departamento</legend></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <input type="hidden" name="codDepartamento" value="<?php echo $codDepartamentoAEditar; ?>">
                                                    <!-- Codigo Departamento Deshabilitado -->
                                                    <td class="d-flex justify-content-start">
                                                        <label for="codDepartamentoAEditar">Código de Departamento:</label>
                                                    </td>
                                                    <td>
                                                        <input class="bloqueado d-flex justify-content-start" type="text" name="codDepartamentoAEditar"
                                                               value="<?php echo ($codDepartamentoAEditar); ?>" disabled>
                                                    </td>
                                                    <td class="error">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <!-- Descripcion Departamento Opcional -->
                                                    <td class="d-flex justify-content-start">
                                                        <label for="T02_DescDepartamento">Descripción de Departamento:</label>
                                                    </td>
                                                    <td>                                                                                                <!-- El value contiene una operador ternario en el que por medio de un metodo 'isset()'
                                                                                                                                                        comprobamos que exista la variable y no sea 'null'. En el caso verdadero devovleremos el contenido del campo
                                                                                                                                                        que contiene '$_REQUEST' , en caso falso sobrescribira el campo a '' .-->
                                                        <input class="d-flex justify-content-start" type="text" name="T02_DescDepartamento" value="<?php echo (isset($_REQUEST['T02_DescDepartamento']) ? $_REQUEST['T02_DescDepartamento'] : $descripcionDepartamentoAEditar); ?>">
                                                    </td>
                                                    <td class="error">
                                                        <?php
                                                        if (!empty($aErrores['T02_DescDepartamento'])) {
                                                            echo $aErrores['T02_DescDepartamento'];
                                                        }
                                                        ?> <!-- Aquí comprobamos que el campo del array '$aErrores' no esta vacío, si es así, mostramos el error. -->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <!-- Fecha Creación Departamento Deshabilitado -->
                                                    <td class="d-flex justify-content-start">
                                                        <label for="fechaCreacionDepartamentoAEditar">Fecha de Creación:</label>
                                                    </td>
                                                    <td>
                                                        <input class="bloqueado d-flex justify-content-start" type="text" name="fechaCreacionDepartamentoAEditar"
                                                               value="<?php echo ($fechaCreacionDepartamentoAEditar); ?>" disabled>
                                                    </td>
                                                    <td class="error">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <!-- Volumen Negocio Departamento Opcional -->
                                                    <td class="d-flex justify-content-start">
                                                        <label for="T02_VolumenDeNegocio_">Volumen de Negocio:</label>
                                                    </td>
                                                    <td>                                                                                                <!-- El value contiene una operador ternario en el que por medio de un metodo 'isset()'
                                                                                                                                                        comprobamos que exista la variable y no sea 'null'. En el caso verdadero devovleremos el contenido del campo
                                                                                                                                                        que contiene '$_REQUEST' , en caso falso sobrescribira el campo a '' .-->
                                                        <input class="d-flex justify-content-start" type="number" name="T02_VolumenDeNegocio_" value="<?php echo (isset($_REQUEST['T02_VolumenDeNegocio']) ? $_REQUEST['T02_VolumenDeNegocio'] : $volumenNegocioAEditar); ?>">
                                                    </td>
                                                    <td class="error">
                                                        <?php
                                                        if (!empty($aErrores['T02_VolumenDeNegocio'])) {
                                                            echo $aErrores['T02_VolumenDeNegocio'];
                                                        }
                                                        ?> <!-- Aquí comprobamos que el campo del array '$aErrores' no esta vacío, si es así, mostramos el error. -->
                                                    </td>
                                                </tr>
                                                <?php
                                                if (!is_null($fechaBajaDepartamentoAEditar)) {
                                                    echo ("<tr>
                                                    <!-- Fecha Baja Departamento Deshabilitado -->
                                                    <td class=\"d-flex justify-content-start\">
                                                        <label for=\"fechaBajaDepartamentoAEditar\">Fecha de Baja:</label>
                                                    </td>
                                                    <td>
                                                        <input class=\"bloqueado d-flex justify-content-start\" type=\"text\" name=\"fechaBajaDepartamentoAEditar\"
                                                               value=\"$fechaBajaDepartamentoAEditar\" disabled>
                                                    </td>
                                                    <td class=\"error\">
                                                    </td>
                                                </tr>");
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <div class="text-center">
                                            <button class="btn btn-secondary" aria-disabled="true" type="submit" name="confirmarCambios">Confirmar Cambios</button>
                                            <button class="btn btn-secondary" aria-disabled="true" type="submit" name="cancelar">Cancelar</button>
                                        </div>
                                    </fieldset>
                                </form>
                                <?php
                            }
                        } catch (PDOException $miExcepcionPDO) {
                            $errorExcepcion = $miExcepcionPDO->getCode(); // Almacenamos el código del error de la excepción en la variable '$errorExcepcion'
                            $mensajeExcepcion = $miExcepcionPDO->getMessage(); // Almacenamos el mensaje de la excepción en la variable '$mensajeExcepcion'

                            echo ("<span class='errorException'>Error: </span>" . $mensajeExcepcion . "<br>"); // Mostramos el mensaje de la excepción
                            echo ("<span class='errorException'>Código del error: </span>" . $errorExcepcion); // Mostramos el código de la excepción
                        } finally {
                            unset($miDB); //Cerramos la conexión con la base de datos
                        }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="position-fixed bottom-0 end-0">
            <div class="row text-center">
                <div class="footer-item">
                    <address>© <a href="../../index.html" style="color: white; text-decoration: none; background-color: #666">Carlos García Cachón</a>
                        IES LOS SAUCES 2023-24 </address>
                </div>
                <div class="footer-item">
                    <a href="../214DWESProyectoDWES/indexProyectoDWES.html" style="color: white; text-decoration: none; background-color: #666">Inicio</a>
                </div>
                <div class="footer-item">
                    <a href="https://github.com/Fighter-kun/214DWESMtoDepartamentosmysPDOTema4.git" target="_blank"><img
                            src="../webroot/media/images/github.png" alt="LogoGitHub" class="pe-5"/></a>
                </div>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    </body>

    </html>