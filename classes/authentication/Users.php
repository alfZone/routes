<?php

/**
 * @autores Alf
 * @copyright 2021
 * @ver 1.0
 */

namespace classes\authentication;
use classes\db\Database;
use classes\db\LayerDB;
//require __DIR__ . '/../config.php';
//require __DIR__ . '/../bootstrap.php';

ini_set("error_reporting", E_ALL);

//include_once $_SERVER['DOCUMENT_ROOT'] . "/forum/config.php";
//include_once $_SERVER['DOCUMENT_ROOT'] . "/classes/ClassDatabase.php";


class Users extends LayerDB{
 
  public $instrucaoSQL = array ("loginGoogle"=>'SELECT `email`, `usertype`, `usertype` as tipo, name as nome, name,`username`, id, `usertype` as type  FROM `jos_users` WHERE `email`=:email and block=0',
                                //"loginGoogle" => 'SELECT `id`, `name`, `email`,`username`,`usertype` FROM `jos_users` WHERE `email`=:email and block=0'
                                "numberUserAtivos" => 'SELECT count(`id`) as numero FROM `jos_users` where `block`=0',
                                "numberUserAtivosPorTipo" => 'SELECT jos_usersType.tipo, usertype,  count(`id`) as numero 
                                                              FROM `jos_users` 
                                                              inner join jos_usersType on jos_users.usertype=jos_usersType.tipoUser 
                                                              where `block`=0 GROUP by usertype',
                                "numberAdmin" => 'SELECT count(`id`) as numero FROM `jos_users` where `block`=0 and usertype=200',
                                "listOfUsers" => 'SELECT `id`, `name` FROM `jos_users` WHERE `block`=0 order by `name`',
                                "photoUpdate" => 'UPDATE jos_users SET foto = :photo WHERE id = :id AND (isNull(foto) or (foto <> :photo))'
                                
                                );
  
   
 
  

 public function doAction($accao, $parameters=""){
    //echo "fdfsfddsdsfdsfds";
   
   //echo "<br><br>aqui $accao ffff  ";
   
     //print_r($parameters);
  
    switch ($accao){

      case "updUsers":
            $this->execQuery($accao, $parameters);
            break;
      case "loginGoogle":
      case "numberUserAtivos":
      case "numberUserAtivosPorTipo":
      case "numberAdmin":
            $this->getQuery($accao, $parameters);
            break;
      default:
          $this->autoQuery($accao, $parameters);
          break;
    }

  }
 
  
 
  
  
  

 
 
}
//#########################################################################################################################################################################################################
//$aux=new ClassTempos("MediasTemposFuncionariosMes");
/*$d=$aux->resposta[0]['dados'];
    echo " var chart_data = [$d];";
    $d=$aux->resposta[1]['dados'];
    echo " var chart_data1 = [$d];";
    $d=$aux->resposta[2]['dados'];
    echo " var chart_data2 = [$d];";

*/
//phpinfo();
//$socio=new ClassSocios("listMembersActive");
//print_r($socio->results);
//echo "aaa";
?>