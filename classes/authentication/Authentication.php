<?php

namespace classes\authentication;
//https://console.cloud.google.com/apis/credentials?project=serene-circlet-163208
//https://docs.microsoft.com/pt-br/azure/active-directory/develop/v2-oauth2-auth-code-flow

/**
 * @author alf
 * @copyright 2024
 * @ver 5.0
 */
 
//echo "aqui";
class Authentication{
    
	public $results;  
	   
    public function __construct(){
			if (session_status()!== PHP_SESSION_ACTIVE){
				session_start();
			}
    }

 
    //Save session varibles for autentication
    public function setAuthentication($user, $name,$email, $foto, $id, $level=1){
      $_SESSION['user']=$user;
      $_SESSION['name']=$name;
      $_SESSION['level']=$level;
      $_SESSION['email']=$email;
      $_SESSION['foto']=$foto;
      $_SESSION['id']=$id;
    }
  
  //get session varibles for autentication
    public function getAuthentication(){
      if (isset($_SESSION['user']) ){
        $this->results[0]['user']=$_SESSION['user'];
        $this->results[0]['nome']=$_SESSION['name'];
        $this->results[0]['level']=$_SESSION['level'];
        $this->results[0]['email']=$_SESSION['email'];
        $this->results[0]['foto']=$_SESSION['foto'];
        $this->results[0]['id']=$_SESSION['id'];
      }else{
        $this->results[0]['user']=null;
        $this->results[0]['nome']=null;
        $this->results[0]['level']=null;
        $this->results[0]['email']=null;
        $this->results[0]['foto']=null;
        $this->results[0]['id']=null;
      }
      
      return $this->results;
    }
  
  
  public function webService(){
    
    return json_encode($this->results, JSON_UNESCAPED_UNICODE);
    
  }
  
  //clean session varibles for autentication
    public function logout(){
      $_SESSION['user']=null;
      $_SESSION['name']=null;
      $_SESSION['level']=null;
      $_SESSION['email']=null;
      $_SESSION['foto']=null;
      $_SESSION['id']=null;
    }
  
      //get session varible photo
    public function getPhoto(){
      return $_SESSION['foto'];
    }
  
    //get session varible user
    public function getUser(){
      return $_SESSION['user'];
    }
  
  //get session varible user
    public function getIdUser(){
      return $_SESSION['id'];
    }
  
  //get session varible user
    public function getName(){
      return $_SESSION['name'];
    }
  
  //get session varible user
    public function getLevel(){
      return $_SESSION['level'];
    }
   //get session varible user
    public function getEmail(){
      return $_SESSION['email'];
    }
  
   
  //verify if a usser is loged
  public function isLoged(){
    $resp=False;
    if (isset( $_SESSION['user'])){
      if ($_SESSION['user']!=""){
        $resp=True;
      }
    return $resp;
    }
  }
}

?>