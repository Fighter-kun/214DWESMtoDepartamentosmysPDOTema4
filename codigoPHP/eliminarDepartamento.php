<?php
/**
 * @author Carlos García Cachón
 * @version 1.0
 * @since 13/01/2024
 * @copyright Todos los derechos reservados a Carlos García
 * 
 * @Annotation Proyecto MtoDepartamentosmysPDOTema4 - Parte de 'eliminarDepartamento' 
 * 
 */
// Estructura del botón cancelar, si el usuario pulsa el botón 'cancelar'
if (isset($_REQUEST['cancelarEliminar'])) {
    header('Location: ../indexMtoDepartamentos.php'); // Llevo al usuario de vuelta al index
    exit();
}

// Incluyo el fichero de configuración de la BD
require_once "../config/confDBPDO.php";

// Bloque para recoger datos que mostramos en el formulario
try {
    $miDB = new PDO(DSN, USERNAME, PASSWORD); // Instanciamos un objeto PDO y establecemos la conexión
    // CONSULTA - SELECT
    /*
     * Hacemos un 'SELECT' sobre la tabla 'T02_Departamento' para recuperar toda la información del departamento que vamos a modificar.
     * En la variable '$_REQUEST['codDepartamento']' esta almacenado el codigo de departamento que hemos recuperado del index al pulsar el botón
     */ 
    $sqlDepartamento = $miDB->prepare("SELECT * FROM T02_Departamento WHERE T02_CodDepartamento = '" . $_REQUEST['codDepartamento'] . "';");

    $sqlDepartamento->execute(); // Ejecuto la consulta con el array de parametros
    $oDepartamentoAEditar = $sqlDepartamento->fetchObject(); // Obtengo un objeto con el departamento
    // Almaceno la información del departamento actual en las siguiente variables, para mostrarlas en el formulario
    $codDepartamentoAEditar = $oDepartamentoAEditar->T02_CodDepartamento;
    $descripcionDepartamentoAEditar = $oDepartamentoAEditar->T02_DescDepartamento;
    $fechaCreacionDepartamentoAEditar = $oDepartamentoAEditar->T02_FechaCreacionDepartamento;
    $volumenNegocioAEditar = $oDepartamentoAEditar->T02_VolumenDeNegocio;
    $fechaBajaDepartamentoAEditar = $oDepartamentoAEditar->T02_FechaBajaDepartamento;

    if (isset($_REQUEST['confirmarCambiosEliminar'])) { // Comprobamos que el usuario haya enviado el formulario para 'confirmar los cambios'
    // CONSULTA - DELETE
    // Usamos un 'DELETE' para eliminar el departamento seleccionado 
        $consultaDelete = <<<CONSULTA
            DELETE FROM T02_Departamento WHERE T02_CodDepartamento = '{$_REQUEST['codDepartamento']}';
        CONSULTA;

        $sqlDeleteDepartamento = $miDB->prepare($consultaDelete); // Preparamos la consulta
        $sqlDeleteDepartamento->execute(); // Ejecutamos la consulta
        header('Location: ../indexMtoDepartamentos.php'); // Llevo al usuario de vuelta al index
        exit();
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
<!DOCTYPE html>
<!--
    Descripción: 214DWESMtoDepartamentosmysPDOTema4 - eliminarDepartamento
    Autor: Carlos García Cachón
    Fecha de creación/modificación: 13/01/2024
--> 
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Carlos García Cachón">
        <meta name="description" content="214DWESMtoDepartamentosmysPDOTema4">
        <meta name="keywords" content="214DWESMtoDepartamentosmysPDOTema4, eliminarDepartamento">
        <meta name="generator" content="Apache NetBeans IDE 19">
        <meta name="generator" content="60">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Carlos García Cachón</title>
        <link rel="icon" type="image/jpg" href="../webroot/media/images/favicon.ico"/>
        <link rel="stylesheet" href="../webroot/bootstrap-5.3.2-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../webroot/css/style.css">
        <style>
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
            input {
                width: 90%;
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
                                            <th class="rounded-top" colspan="3"><legend>Eliminar Departamento</legend></th>
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
                                        <!-- Descripcion Departamento Deshabilitado -->
                                        <td class="d-flex justify-content-start">
                                            <label for="descripcionDepartamentoAEditar">Descripción de Departamento:</label>
                                        </td>
                                        <td>                                                                                                
                                            <input class="bloqueado d-flex justify-content-start" type="text" name="descripcionDepartamentoAEditar" value="<?php echo ($descripcionDepartamentoAEditar); ?>" disabled>
                                        </td>
                                        <td class="error">
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
                                        <!-- Volumen Negocio Departamento Bloqueado -->
                                        <td class="d-flex justify-content-start">
                                            <label for="T02_VolumenDeNegocio_">Volumen de Negocio:</label>
                                        </td>
                                        <td>                                                                                                
                                            <input class="bloqueado d-flex justify-content-start" type="number" name="T02_VolumenDeNegocio_" value="<?php echo ($volumenNegocioAEditar); ?>" disabled>
                                        </td>
                                        <td class="error">
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
                                    <button class="btn btn-danger" aria-disabled="true" type="submit" name="confirmarCambiosEliminar">Eliminar</button>
                                    <button class="btn btn-secondary" aria-disabled="true" type="submit" name="cancelarEliminar">Cancelar</button>
                                </div>
                            </fieldset>
                        </form>
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
                <a href="../../214DWESProyectoDWES/indexProyectoDWES.html" style="color: white; text-decoration: none; background-color: #666">Inicio</a>
            </div>
            <div class="footer-item">
                <a href="https://github.com/Fighter-kun/214DWESMtoDepartamentosmysPDOTema4.git" target="_blank"><img
                        src="../webroot/media/images/github.png" alt="LogoGitHub" class="pe-5"/></a>
            </div>
        </div>
    </footer>

    <script src="../webroot/bootstrap-5.3.2-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>