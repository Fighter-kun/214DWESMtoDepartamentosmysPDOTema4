/**
 * Author:  Carlos García Cachón
 * Created: 09/01/2024
 */

-- Elimino el usuario de la base de datos
DROP USER  user214DWESProyectoTema4;

-- Cambio a una base de datos diferente antes de eliminarla (En este caso la que crea por defecto 'mysql')
USE mysql;

-- Elimino la base de datos
DROP DATABASE DB214DWESProyectoTema4;
