<?php
use src\Route as Route;

Route::get('/', function(){require _CAMINHO_TEMPLATE1. "index.php";});
Route::get('/admin', function(){require _CAMINHO_TEMPLATE_ADMIN. "/index.php";});

//Artigos
Route::get(['set' => '/artigos/numeros', 'as' => 'artigos.contarArtigos'], 'ControllerArtigos@contarArtigos');
Route::get('/artigos', function(){  require _CAMINHO_ADMIN. "artigosGerir.php";});                //mostra os últimos artigos
Route::post('/artigos', function(){  require _CAMINHO_ADMIN. "artigosGerir.php";});

Route::get(['set' => '/artigo/{id}/ver', 'as' => 'artigos.ArtigoVer'], 'ControllerArtigos@ArtigoVer');                      //web service
Route::get('/artigo/ver/{id}', function(){  require _CAMINHO_TEMPLATE1. "artigo.php";});          //ver artigo
Route::get('/artigo/ver/', function(){  require _CAMINHO_TEMPLATE1. "artigo.php";});          //ver artigo
Route::post(['set' => '/artigo/add', 'as' => 'artigos.addArtigo'], 'ControllerArtigos@addArtigo'); 
Route::get(['set' => '/artigo/add', 'as' => 'artigos.addArtigo'], 'ControllerArtigos@addArtigo'); 

//Users
Route::get(['set' => '/users/contar', 'as' => 'users.contarUsers'], 'ControllerUser@contarUsers');
Route::get('/users', function(){  require _CAMINHO_ADMIN. "utilizadoresGere.php";});          //mostra todos os users
Route::post('/users', function(){  require _CAMINHO_ADMIN. "utilizadoresGere.php";});    
Route::get(['set' => '/users/lista', 'as' => 'users.listOfUsers'], 'ControllerUser@listOfUsers');

//Autenticação


?>