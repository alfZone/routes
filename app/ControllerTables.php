<?php
namespace app;
use src\Connection;
use classes\db\TableBD;
use PDO;

//Generic Controller for handling CRUD operations on database tables
//This class uses the Connection class to interact with the database
//It provides methods to get all records, get a record by ID, create a new record
//update an existing record, and delete a record by ID
//It is designed to be flexible and can work with any table in the database
//It uses the TableBD class to prepare the table and handle field mappings
//It returns JSON responses with status codes and messages for success or failure
//Version 1.0
//Date: 2025/05/27  
class ControllerTables {

    private $conn;
    private $database;

    public function __construct() {
        $this->database = new Connection();
        $this->conn = $this->database->getConnection();

    }

    // Obter todos os registos
    public function getAll($table) {
        $records = $this->database->getData("SELECT * FROM $table");
        if ($records) {
            $records[0]['status'] = '200'; 
            $records[0]['numElements'] = sizeof($records);  
            echo json_encode($records);
        } else {
            echo json_encode(['msg' => 'Registo nao encontrado', 'status' => '404']);
        }
    }

    // Get a record by ID
    public function getById($table, $id) {
        $tab=new TableBD();
        $tab->prepareTable($table);
        $key = $tab->getKey();
        $p['id']=$id;
        $records = $this->database->getData("SELECT * FROM $table WHERE $key = :id", $p);
        if ($records) {
            $records[0]['status'] = '200'; 
            $records[0]['numElements'] = sizeof($records);  
            echo json_encode($records);
        } else {
            echo json_encode(['msg' => 'Registo nao encontrado', 'status' => '404']);
        }
    }

    // Create a new record
    public function create($table) {
        $tab=new TableBD();
        $tab->prepareTable($table);
        $tab->setAllFieldAtive("new",1);
        $tab->getRequestData("");
        $sql= $tab->prepareSQLInsert();
        $records = $this->database->setData($sql);
        if ($records) {
            echo json_encode($records);
        } else {
            echo json_encode(['msg' => 'Falhou a criação do registo ', 'status' => '404']);
        }
    }

    // update an existing record
    public function update($table) {
        parse_str(file_get_contents("php://input"), $putData);
        $_REQUEST= $putData;
        $tab=new TableBD();
        $tab->prepareTable($table);
        $tab->setAllFieldAtive("new",1);
        $tab->getRequestData("");
        $sql= $tab->preparaSQLupdate(0,"");
        $records = $this->database->setData($sql);
        if ($records) {  
            echo json_encode($records);
        } else {
            echo json_encode(['msg' => 'Falhou a atualização do Registo', 'status' => '404']);
        }
    }

    // Deletar um carro
    public function delete($table, $id) {

        $tab=new TableBD();
        $tab->prepareTable($table);
        $key = $tab->getKey();
        $_REQUEST[$key]=$id;
        $tab->getRequestData("");
         $sql=$tab->prepareSQLdelete();
        $records = $this->database->setData($sql);
        if ($records) {
  
            echo json_encode($records);
        } else {
            echo json_encode(['msg' => 'Falhou a apagar o Registo', 'status' => '404']);
        }
    }

}
?>

