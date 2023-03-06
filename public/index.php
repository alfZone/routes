<?php


/**
 * @autores Alf
 * @copyright 2020
 * @ver 1.0
 */


 
//echo $_SERVER['DOCUMENT_ROOT'] . _CAMINHO_CLASSES

//https://alexandrebbarbosa.wordpress.com/2019/04/19/phpconstruir-um-sistema-de-rotas-para-mvc-segunda-parte/

//echo __DIR__;

require __DIR__ . '/../config.php';
require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../src/helpers/helper_routes.php';
//define ("_CAMINHO_TEMPLATE", "../templates/opt2/");
//define ("_CAMINHO_TEMPLATE", "../templates/opt3/");

resolve();




?>