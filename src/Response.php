<?php

//version 1.0
// 2026/05/31

namespace src;

class Response
{
	

	public function __construct(){

	}

	public function ok($msg,$data=""){
		$resp = [
			'success' => true,  // boolean, não string
			'message' => $msg,
			'code' => 200,
			'timestamp' => date('Y-m-d H:i:s'),
			'data' => $data,              // para respostas de sucesso
		];
		echo json_encode($resp, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	}

	public function data($data=""){
		echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	}

	public function error($msg, $code = 500, $error=""){
		$resp = [
			'success' => false,
			'message' => $msg,
			'code' => $code,
			'timestamp' => date('Y-m-d H:i:s'),
			'error' => $error
		];
		echo json_encode($resp, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	}

	public function header(){
		
	}

	public function status(){

	}

	public function content(){

	}
}

?>
