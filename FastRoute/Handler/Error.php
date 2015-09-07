<?php

class FastRoute_Handler_Error implements FastRoute_Handler {

	public function excute(PhalApi_Response $response) {
		$response->output();
		exit(0);
	}
}
