<?php

/**
 * @autores alf
 * @copyright 2025
 * @ver 3.0
 */


namespace app;
use src\Connection;
use src\Control;
use PDO;

//require_once 'Database.php'; // Arquivo de conexão com a base de dados

class ControllerCarros {

    private $conn;
    private $database;

    public function __construct() {
        $this->database = new Connection();
        $this->conn = $this->database->getConnection();
    }

    // Obter todos os carros
    public function getAll() {
        $carros = $this->database->getData("SELECT * FROM alfCarros");
        echo json_encode($carros, JSON_UNESCAPED_UNICODE);
    }

    // Obter carro por ID
    public function getById($id) {
        $p['id']=$id;
        $carro = $this->database->getData("SELECT * FROM alfCarros WHERE id = :id", $p);
        //print_r($carro);
        if ($carro) {
            echo json_encode($carro, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['msg' => 'Carro não encontrado', 'status' => '404']);
        }
    }

    // Criar um novo carro
    public function create() {

        $ctrl = new Control();
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!$ctrl->checkRateLimit($ip, 3, 60)) {
            echo json_encode(['error' => 'Muitas tentativas. Aguarde alguns minutos.'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);

        if($input){
            $_POST = $input;
        }
        
        $p['marca']=$_POST['Marca'];
        $p['detalhes']=$_POST['Detalhes'];
        $p['foto']=$_POST['Foto'];
        $resp = $this->database->setData("INSERT INTO alfCarros (marca, detalhes, foto) VALUES (:marca, :detalhes, :foto)", $p);
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    }

    // Atualizar um carro
    public function update() {
        parse_str(file_get_contents("php://input"), $putData);
        $p['marca']=$putData['Marca'];
        $p['detalhes']=$putData['Detalhes'];
        $p['foto']=$putData['Foto'];
        $p['id']=$putData['id'];

        $resp = $this->database->setData("UPDATE alfCarros SET marca = :marca, detalhes = :detalhes, foto = :foto WHERE id = :id", $p);
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    }

    // Deletar um carro
    public function delete($id) {
        $p['id']=$id;

        $resp = $this->database->setData("DELETE FROM alfCarros WHERE id = :id", $p);
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    }

}
?>
