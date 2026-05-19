<?php

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


}

?>
