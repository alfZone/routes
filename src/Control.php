<?php

//version 2.0
// 2025/10/31

namespace src;

class Control
{
	

	public function __construct(){

	}

	public function checkRateLimit($identifier, $limit = 10, $window = 60) {
        $rateDir = sys_get_temp_dir() . '/rate_limits/';
        
        // Criar diretório se não existir
        if (!is_dir($rateDir)) {
            mkdir($rateDir, 0755, true);
        }
        
        $rateFile = $rateDir . md5($identifier);
        $now = time();
        
        if (file_exists($rateFile)) {
            $data = json_decode(file_get_contents($rateFile), true);
            
            // Limpar requisições antigas
            $data['requests'] = array_values(array_filter($data['requests'], function($time) use ($now, $window) {
                return ($now - $time) < $window;
            }));
            
            if (count($data['requests']) >= $limit) {
                return false;
            }
            
            $data['requests'][] = $now;
            file_put_contents($rateFile, json_encode($data));
        } else {
            file_put_contents($rateFile, json_encode([
                'requests' => [$now]
            ]));
        }
        
        return true;
    }

    public function rate() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $key = "rate_limit:$ip";
        $requests = $redis->incr($key);
        $redis->expire($key, 60); // janela de 60s

        if ($requests > 100) {
            http_response_code(429);
            die(json_encode(['error' => 'Too many requests']));
        }
    }


    public function cabecalhos(){
        // Permite apenas origens conhecidas
        $allowedOrigins = [_URL];
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        if (in_array($origin, $allowedOrigins)) {
            header("Access-Control-Allow-Origin: $origin");
        }

        // Headers de segurança essenciais
        header("X-Content-Type-Options: nosniff");
        header("X-Frame-Options: DENY");
        header("Content-Security-Policy: default-src 'self'");
        header("Strict-Transport-Security: max-age=31536000");
    }

    public function protecaoCSRF(){
        // Gera token na sessão
        @session_start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Valida o token no pedido
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            die(json_encode(['error' => 'CSRF token inválido']));
        }
    }

    public function clean($data){
        // Ao devolver dados para o cliente
        $output = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

        // Ao guardar HTML (ex: editor de texto rico)
        // Usa HTMLPurifier para limpar HTML malicioso
        $purifier = new HTMLPurifier();
        $clean = $purifier->purify($_POST['content']);
        return $clean;
    }

    public function validaToken(){
        // Valida o token em cada pedido
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? '';

        if (!validateJWT($token)) {
            http_response_code(401);
            die(json_encode(['error' => 'Unauthorized']));
        }
    }

}

?>
