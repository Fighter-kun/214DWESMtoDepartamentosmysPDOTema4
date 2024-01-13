<?php
/**
 * @author Carlos García Cachón
 * @version 1.0
 * @since 05/01/2024
 * @copyright Todos los derechos reservados a Carlos García
 * 
 * @Annotation Proyecto MtoDepartamentosmysPDOTema4 - Parte de 'Index' 
 * 
 */

// Estructura del botón exportar, si el ususario pulsa el botón 'exportar'
if (isset($_REQUEST['exportarDepartamentos'])) {
    header('Location: codigoPHP/exportarDepartamentos.php'); // Llevo al usuario a exportarDepartamentos
    exit();
}

// Estructura del botón importar, si el ususario pulsa el botón 'importar'
if (isset($_REQUEST['importarDepartamentos'])) {
    header('Location: codigoPHP/exportarDepartamentos.php'); // Llevo al usuario a importarDepartamentos
    exit();
}
?>
<!DOCTYPE html>
<!--
        Descripción: 214DWESMtoDepartamentosmysPDOTema4
        Autor: Carlos García Cachón
        Fecha de creación/modificación: 05/01/2024
-->
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Carlos García Cachón">
        <meta name="description" content="214DWESMtoDepartamentosmysPDOTema4">
        <meta name="keywords" content="214DWESMtoDepartamentosmysPDOTema4, DWES">
        <meta name="generator" content="Apache NetBeans IDE 19">
        <meta name="generator" content="60">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Carlos García Cachón</title>
        <link rel="icon" type="image/jpg" href="webroot/media/images/favicon.ico">
        <link rel="stylesheet" href="webroot/bootstrap-5.3.2-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="webroot/css/style.css">
        <style>
            .error {
                color: red;
                width: 450px;
            }
            input[name="DescDepartamento"] {
                width: 110%;
                margin-right: 50%;
            }
            form[name="buscarDepartamentos"] {
                position: absolute;
                top: 200px;
                width: 70%;
            }
            
            .tablaMuestra {
                position: absolute;
                top: 35%;
                width: 70%;
            }
            .grupoDeBotones {
                margin-top: 50%;
            }
        </style>
    </head>

    <body>
        <header class="text-center">
            <h1>Mantenimiento Departamentos</h1>
        </header>
        <main>
            <div class="container mt-3">
                <div class="row mb-5">
                    <div class="col text-center">
                        <?php
                        /**
                         * @author Carlos García Cachón
                         * @version 1.1
                         * @since 08/11/2023
                         */
                        //Incluyo las librerias de validación para comprobar los campos
                        require_once 'core/231018libreriaValidacion.php';
                        // Incluyo la configuración de conexión a la BD
                        require_once 'config/confDBPDO.php';

                        //Declaración de constantes por OBLIGATORIEDAD
                        define('OPCIONAL', 0);
                        define('OBLIGATORIO', 1);

                        //Declaración de variables de estructura para validar la ENTRADA de RESPUESTAS o ERRORES
                        //Valores por defecto
                        $entradaOK = true; //Indica si todas las respuestas son correctas
                        $aRespuestas = [
                            'DescDepartamento' => '',
                        ]; //Almacena las respuestas
                        $aErrores = [
                            'DescDepartamento' => '',
                        ]; //Almacena los errores
                        //Comprobamos si se ha enviado el formulario
                        if (isset($_REQUEST['enviar'])) {
                            //Introducimos valores en el array $aErrores si ocurre un error
                            $aErrores = [
                                'DescDepartamento' => validacionFormularios::comprobarAlfabetico($_REQUEST['DescDepartamento'], 255, 1, 0),
                            ];

                            //Recorremos el array de errores
                            foreach ($aErrores as $campo => $error) {
                                if ($error == !null) {
                                    //Limpiamos el campos
                                    $entradaOK = false;
                                    $_REQUEST[$campo] = '';
                                    //Si ha dado un error la respuesta pasa a valer el valor que ha introducido el usuario
                                } else {
                                    $aRespuestas['DescDepartamento'] = $_REQUEST['DescDepartamento'];
                                }
                            }
                        } else {
                            $entradaOK = false; //Si no ha pulsado el botón de enviar la validación es incorrecta.
                        }

                        try {
                            //Establecimiento de la conexion
                            /*
                              Instanciamos un objeto PDO y establecemos la conexión
                              Construccion de la cadena PDO: (ej. 'mysql:host=localhost; dbname=midb')
                              host – nombre o dirección IP del servidor
                              dbname – nombre de la base de datos
                             */
                            $miDB = new PDO(DSN, USERNAME, PASSWORD);

                            //Preparamos la consulta
                            $resultadoConsulta = $miDB->query("SELECT * FROM T02_Departamento WHERE T02_DescDepartamento LIKE'%$aRespuestas[DescDepartamento]%' ;");
                            // Ejecutando la declaración SQL
                            if ($resultadoConsulta->rowCount() == 0) {
                                $aErrores['DescDepartamento'] = "No existen departamentos con esa descripcion";
                            }
                            // Creamos una tabla en la que mostraremos la tabla de la BD
                            echo ("<div class='list-group text-center tablaMuestra'>");
                            echo ("<table>
                                        <thead>
                                        <tr>
                                            <th colspan='2'><-T-></th>
                                            <th>Codigo de Departamento</th>
                                            <th>Descripcion de Departamento</th>
                                            <th>Fecha de Creacion</th>
                                            <th>Volumen de Negocio</th>
                                            <th>Fecha de Baja</th>
                                        </tr>
                                        </thead>");

                            /* Aqui recorremos todos los valores de la tabla, columna por columna, usando el parametro 'PDO::FETCH_ASSOC' , 
                             * el cual nos indica que los resultados deben ser devueltos como un array asociativo, donde los nombres de las columnas de 
                             * la tabla se utilizan como claves (keys) en el array.
                             */
                            echo ("<tbody>");
                            while ($oDepartamento = $resultadoConsulta->fetchObject()) {
                                echo ("<tr>");
                                echo ("<td>");
                                

                                // Formulario para editar
                                echo ("<form action='codigoPHP/editarDepartamento.php' method='post'>");
                                echo ("<input type='hidden' name='codDepartamento' value='" . $oDepartamento->T02_CodDepartamento . "'>");
                                echo ("<button type='submit'><svg fill='#666' width='16' height='16' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path d='M4.481 15.659c-1.334 3.916-1.48 4.232-1.48 4.587 0 .528.46.749.749.749.352 0 .668-.137 4.574-1.492zm1.06-1.061 3.846 3.846 11.321-11.311c.195-.195.293-.45.293-.707 0-.255-.098-.51-.293-.706-.692-.691-1.742-1.74-2.435-2.432-.195-.195-.451-.293-.707-.293-.254 0-.51.098-.706.293z' fill-rule='evenodd'/></svg></button>");
                                echo ("</form>");
                                echo ("</td>");
                                
                                
                                // Formulario para eliminar
                                echo ("<td>");
                                echo ("<form action='codigoPHP/eliminarDepartamento.php' method='post'>");
                                echo ("<input type='hidden' name='codDepartamento' value='" . $oDepartamento->T02_CodDepartamento . "'>");
                                echo ("<button type='submit'><svg width='16' height='16' clip-rule='evenodd' fill-rule='evenodd' stroke-linejoin='round' stroke-miterlimit='2' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path d='m12 10.93 5.719-5.72c.146-.146.339-.219.531-.219.404 0 .75.324.75.749 0 .193-.073.385-.219.532l-5.72 5.719 5.719 5.719c.147.147.22.339.22.531 0 .427-.349.75-.75.75-.192 0-.385-.073-.531-.219l-5.719-5.719-5.719 5.719c-.146.146-.339.219-.531.219-.401 0-.75-.323-.75-.75 0-.192.073-.384.22-.531l5.719-5.719-5.72-5.719c-.146-.147-.219-.339-.219-.532 0-.425.346-.749.75-.749.192 0 .385.073.531.219z' fill='red'/></svg></button>");
                                echo ("</form>");
                                echo ("</td>");
                                
                                echo ("<td>" . $oDepartamento->T02_CodDepartamento . "</td>");
                                echo ("<td>" . $oDepartamento->T02_DescDepartamento . "</td>");
                                echo ("<td>" . $oDepartamento->T02_FechaCreacionDepartamento . "</td>");
                                echo ("<td>" . $oDepartamento->T02_VolumenDeNegocio . "</td>");
                                echo ("<td>" . $oDepartamento->T02_FechaBajaDepartamento . "</td>");
                                echo ("</tr>");
                            }

                            echo ("</tbody>");
                            /* Ahora usamos la función 'rowCount()' que nos devuelve el número de filas afectadas por la consulta y 
                             * almacenamos el valor en la variable '$numeroDeRegistros'
                             */
                            $numeroDeRegistrosConsulta = $resultadoConsulta->rowCount();
                            // Y mostramos el número de registros
                            echo ("<tfoot ><tr style='background-color: #666; color:white;'><td colspan='7'>Número de registros en la tabla Departamento: " . $numeroDeRegistrosConsulta . '</td></tr></tfoot>');
                            echo ("</table>");
                            echo ("</div>");
                            //Mediante PDOExprecion controlamos los errores
                        } catch (PDOException $excepcion) {
                            echo 'Error: ' . $excepcion->getMessage() . "<br>"; //Obtiene el valor de un atributo
                            echo 'Código de error: ' . $excepcion->getCode() . "<br>"; // Establece el valor de un atributo
                        } finally {
                            unset($miDB);
                        }
                        //Si la entrada es Ok almacenamos el valor de la respuesta del usuario en el array $aRespuestas
                        if ($entradaOK) {
                            //Almacenamos el valor en el array
                            $aRespuestas = [
                                'DescDepartamento' => $_REQUEST['DescDepartamento'],
                            ];

                            try {
                                //Establecimiento de la conexion
                                /*
                                  Instanciamos un objeto PDO y establecemos la conexión
                                  Construccion de la cadena PDO: (ej. 'mysql:host=localhost; dbname=midb')
                                  host – nombre o dirección IP del servidor
                                  dbname – nombre de la base de datos
                                 */
                                $miDB = new PDO(DSN, USERNAME, PASSWORD);

                                //Preparamos la consulta
                                $resultadoConsulta = $miDB->query("SELECT * FROM T02_Departamento WHERE T02_DescDepartamento LIKE'%$aRespuestas[DescDepartamento]%';");
                                // Ejecutando la declaración SQL
                                if ($resultadoConsulta->rowCount() == 0) {
                                    $aErrores['DescDepartamento'] = "No existen departamentos con esa descripcion";
                                }
                                // Creamos una tabla en la que mostraremos la tabla de la BD
                                echo ("<div class='list-group text-center tablaMuestra'>");
                                echo ("<table>
                                        <thead>
                                        <tr>
                                            <th colspan='2'><-T-></th>
                                            <th>Codigo de Departamento</th>
                                            <th>Descripcion de Departamento</th>
                                            <th>Fecha de Creacion</th>
                                            <th>Volumen de Negocio</th>
                                            <th>Fecha de Baja</th>
                                        </tr>
                                        </thead>");

                                /* Aqui recorremos todos los valores de la tabla, columna por columna, usando el parametro 'PDO::FETCH_ASSOC' , 
                                 * el cual nos indica que los resultados deben ser devueltos como un array asociativo, donde los nombres de las columnas de 
                                 * la tabla se utilizan como claves (keys) en el array.
                                 */
                                echo ("<tbody>");
                                while ($oDepartamento = $resultadoConsulta->fetchObject()) {
                                    echo ("<tr>");
                                    echo ("<td>");
                                    
                                    // Formulario para editar
                                    echo ("<form action='codigoPHP/editarDepartamento.php' method='post'>");
                                    echo ("<input type='hidden' name='codDepartamento' value='" . $oDepartamento->T02_CodDepartamento . "'>");
                                    echo ("<button type='submit'><svg fill='#666' width='16' height='16' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path d='M4.481 15.659c-1.334 3.916-1.48 4.232-1.48 4.587 0 .528.46.749.749.749.352 0 .668-.137 4.574-1.492zm1.06-1.061 3.846 3.846 11.321-11.311c.195-.195.293-.45.293-.707 0-.255-.098-.51-.293-.706-.692-.691-1.742-1.74-2.435-2.432-.195-.195-.451-.293-.707-.293-.254 0-.51.098-.706.293z' fill-rule='evenodd'/></svg></button>");
                                    echo ("</form>");
                                    echo ("</td>");
                                    
                                    // Formulario para eliminar
                                    echo ("<td>");
                                    echo ("<form action='codigoPHP/eliminarDepartamento.php' method='post'>");
                                    echo ("<input type='hidden' name='codDepartamento' value='" . $oDepartamento->T02_CodDepartamento . "'>");
                                    echo ("<button type='submit'><svg width='16' height='16' clip-rule='evenodd' fill-rule='evenodd' stroke-linejoin='round' stroke-miterlimit='2' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path d='m12 10.93 5.719-5.72c.146-.146.339-.219.531-.219.404 0 .75.324.75.749 0 .193-.073.385-.219.532l-5.72 5.719 5.719 5.719c.147.147.22.339.22.531 0 .427-.349.75-.75.75-.192 0-.385-.073-.531-.219l-5.719-5.719-5.719 5.719c-.146.146-.339.219-.531.219-.401 0-.75-.323-.75-.75 0-.192.073-.384.22-.531l5.719-5.719-5.72-5.719c-.146-.147-.219-.339-.219-.532 0-.425.346-.749.75-.749.192 0 .385.073.531.219z' fill='red'/></svg></button>");
                                    echo ("</form>");
                                    echo ("</td>");
                                    echo ("<td>" . $oDepartamento->T02_CodDepartamento . "</td>");
                                    echo ("<td>" . $oDepartamento->T02_DescDepartamento . "</td>");
                                    echo ("<td>" . $oDepartamento->T02_FechaCreacionDepartamento . "</td>");
                                    echo ("<td>" . $oDepartamento->T02_VolumenDeNegocio . "</td>");
                                    echo ("<td>" . $oDepartamento->T02_FechaBajaDepartamento . "</td>");
                                    echo ("</tr>");
                                }

                                echo ("</tbody>");
                                /* Ahora usamos la función 'rowCount()' que nos devuelve el número de filas afectadas por la consulta y 
                                 * almacenamos el valor en la variable '$numeroDeRegistros'
                                 */
                                $numeroDeRegistrosConsulta = $resultadoConsulta->rowCount();
                                // Y mostramos el número de registros
                                echo ("<tfoot ><tr style='background-color: #666; color:white;'><td colspan='7'>Número de registros en la tabla Departamento: " . $numeroDeRegistrosConsulta . '</td></tr></tfoot>');
                                echo ("</table>");
                                echo ("</div>");
                                //Mediante PDOExprecion controlamos los errores
                            } catch (PDOException $excepcion) {
                                echo 'Error: ' . $excepcion->getMessage() . "<br>"; //Obtiene el valor de un atributo
                                echo 'Código de error: ' . $excepcion->getCode() . "<br>"; // Establece el valor de un atributo
                            } finally {
                                unset($miDB);
                            }
                        } //Despues de que se ejecute el codigo anterior mostramos pase lo que pase el formulario
                        ?>
                        <form name="buscarDepartamentos" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <fieldset>
                                <table>
                                    <thead>

                                    </thead>
                                    <tbody>
                                        <tr style="background-color: #f2f2f2;">
                                            <!-- CodDepartamento Obligatorio -->
                                            <td class="d-flex justify-content-start" colspan='2'>
                                                <label for="DescDepartamento"></label>
                                            </td>
                                            <td>                                                                                                <!-- El value contiene una operador ternario en el que por medio de un metodo 'isset()'
                                                                                                                                                comprobamos que exista la variable y no sea 'null'. En el caso verdadero devovleremos el contenido del campo
                                                                                                                                                que contiene '$_REQUEST' , en caso falso sobrescribira el campo a '' .-->
                                                <input class="d-flex justify-content-start" type="text" name="DescDepartamento" value="<?php echo (isset($_REQUEST['DescDepartamento']) ? $_REQUEST['DescDepartamento'] : ''); ?>">
                                            </td>


                                            <td><button class="btn btn-secondary" role="button" aria-disabled="true" type="submit" name="enviar">Buscar</button></td>
                                        </tr>
                                        <tr style="background-color: #f2f2f2;">
                                            <td class="error" colspan="3">
                                                <?php
                                                if (!empty($aErrores['DescDepartamento'])) {
                                                    echo $aErrores['DescDepartamento'];
                                                }
                                                ?> <!-- Aquí comprobamos que el campo del array '$aErrores' no esta vacío, si es así, mostramos el error. -->
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>

                            </fieldset>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <form name="indexMtoDepartamentos" method="post">
                        <a class="btn btn-secondary" role="button" aria-disabled="true" href='../214DWESProyectoTema4/indexProyectoTema4.html'>Salir</a>
                        <button class="btn btn-secondary" role="button" aria-disabled="true" type="submit" name="exportarDepartamentos">Exportar</button>
                        <button class="btn btn-secondary" role="button" aria-disabled="true" type="submit" name="importarDepartamentos">Importar</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <footer class="position-fixed bottom-0 end-0">
            <div class="row text-center">
                <div class="footer-item">
                    <address>© <a href="../index.html" style="color: white; text-decoration: none;">Carlos García Cachón</a>
                        IES LOS SAUCES 2023-24 </address>
                </div>
                <div class="footer-item">
                    <a href="../214DWESProyectoDWES/indexProyectoDWES.html" style="color: white; text-decoration: none;">Inicio</a>
                </div>
                <div class="footer-item">
                    <a href="https://github.com/Fighter-kun/214DWESMtoDepartamentosmysPDOTema4.git" target="_blank"><img
                            src="webroot/media/images/github.png" alt="LogoGitHub" /></a>
                </div>
            </div>
        </footer>

        <script src="webroot/bootstrap-5.3.2-dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>