<?php

//version 1.1
// 2026/05/31

namespace src;

class Response{
	

	public function __construct(){

	}

	public function ok($msg,$data=""){
		$resp = [
			'success' => true,  // boolean, não string
			'message' => $msg,
			'code' => 200,
			'status' => 200,
			'timestamp' => date('Y-m-d H:i:s'),
			'data' => $data,              // para respostas de sucesso
		];
		return json_encode($resp, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	}

	public function data($data=""){
		return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	}

	public function error($msg, $code = 500, $error=""){
		$resp = [
			'success' => false,
			'message' => $msg,
			'code' => $code,
			'status' => $code,
			'timestamp' => date('Y-m-d H:i:s'),
			'error' => $error
		];
		return json_encode($resp, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	}

	public function header(){
		
	}

	public function status(){

	}

	public function content(){

	}
}

?>
