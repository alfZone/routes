<?php

//version 2.0
// 2025/10/31

namespace src;

use PDO;

// Definir a constante fora de classes ou namespaces
define('_DEBUG', true);

class Connection
{
    // put the database stuffs here in that scope
    private $host = _SERVER;
    private $db_name = _BD;
    private $username = _BDUSER;
    private $password = _BDPASS;
    public $conn;

    public function getConnection(){
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (\PDOException $exception) {
            echo "Erro na conexão: " . $exception->getMessage();
        }
        return $this->conn;
    }

    function bindParamAuto($stmt, $param, $value){
        if (is_int($value)) {
            $type = PDO::PARAM_INT;
        } elseif (is_bool($value)) {
            $type = PDO::PARAM_BOOL;
        } elseif (is_null($value)) {
            $type = PDO::PARAM_NULL;
        } else {
            $type = PDO::PARAM_STR;
        }
        $stmt->bindParam($param, $value, $type);
    }

    //######################## GET DATA  #####################################

    public function getData($sql, $parameters = []){
        try {
            $stmt = $this->conn->prepare($sql);
            if (!$parameters == "") {
                foreach ($parameters as $key => $value) {
                    $this->bindParamAuto($stmt, ':' . $key, $value);
                }
            }
            $stmt->execute();
            $data =$stmt->fetchAll(\PDO::FETCH_ASSOC);
            $data[0]['numElements']=count($data);
            $data[0]['msg']='ok';
            $data[0]['status']='200';
            return $data;
        } catch (\PDOException $e) {
            return json_encode(['msg' => 'Erro: ' . $e->getMessage(), 'status' => '500']);
        }
    }


    //######################## SET DATA  #####################################

    public function setData($sql, $parameters = []) {
        try {
            $stmt = $this->conn->prepare($sql);

            // Vincula os parâmetros, se fornecidos
            if (!empty($parameters)) {
                foreach ($parameters as $key => $value) {
                    //echo $key . "|";
                    $this->bindParamAuto($stmt, ':' . $key, $value);
                }
            }

            /* 
            echo "<pre>SQL original:\n$sql\n\nParâmetros:\n";
            print_r($parameters);
            echo "</pre>";
            */
            // Executa a consulta
            $stmt->execute();

            // Obtém o número de linhas afetadas
            $affectedRows = $stmt->rowCount();

            // Verifica o tipo de operação (baseado no comando SQL)
            $operation = strtoupper(explode(' ', trim($sql))[0]);
            $response = ['status' => '200', 'operation' => $operation];

            switch ($operation) {
                case 'INSERT':
                    // Para INSERT, podemos retornar o último ID inserido (se for relevante)
                    $response['msg'] = 'Registro inserido com sucesso.';
                    $response['lastInsertId'] = $this->conn->lastInsertId();
                    break;

                case 'UPDATE':
                    $response['msg'] = $affectedRows > 0
                        ? "{$affectedRows} linha(s) atualizada(s) com sucesso."
                        : "Nenhuma linha foi atualizada. Verifique as condições.";
                    break;

                case 'DELETE':
                    $response['msg'] = $affectedRows > 0
                        ? "{$affectedRows} linha(s) apagadas com sucesso."
                        : "Nenhuma linha foi apagada. Verifique as condições.";
                    break;

                default:
                    $response['msg'] = 'Operação executada com sucesso.';
            }

            return $response;
        } catch (\PDOException $e) {
            // Tratamento de erros
            return [
                'status' => '500',
                'msg' => 'Erro: ' . $e->getMessage()
            ];
        }
    }


    /**
     * Método auxiliar para verificar se está em modo debug
     * Retorna true para exibir informações de depuração
     */
    private function isDebug()
    {
        return defined('_DEBUG') && _DEBUG === true;
    }

    public function montarSqlComParametros($sql, $params)
    {
        foreach ($params as $key => $value) {
            $placeholder = ':' . $key;
            $valor = is_numeric($value) ? $value : "'" . addslashes($value) . "'";
            $sql = str_replace($placeholder, $valor, $sql);
        }
        return $sql;
    }
}
