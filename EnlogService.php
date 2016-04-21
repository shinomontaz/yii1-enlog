<?php

/**
 * Class EnlogService
 * Enlog v1 API implementation
 * Created by: denis.rybakov
 * Date: 09.04.16
 */
class EnlogService extends CComponent {

    public $url;
    public $name;
    public $pass;
    public $isTest = true;

    /**
     * @param string $name
     * @param string $pass
     * @return EnlogService
     */
    public function auth( $name, $pass ) {
      $this->name = $name;
      $this->pass = $pass;
      return $this;
    }
	
    /**
     * {@inheritdoc }
     */
    public function init() {
    }

    /**
     * @param $method
     * @param $params
     *
     * @return mixed
     * @throws Exception
     */
    public function request( $method, $params ) {
        // create payment
        $request = [
            'jsonrpc' => '2.0',
            'method'  => $method,
            'params'  => $params,
            'id'      => md5(microtime()),
        ];

        $jsonRequest = json_encode($request);

        $_ctx = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  =>	'Content-Type: application/json-rpc' . "\r\n".
                    'Rpc-User: '. $this->name . "\r\n" .
                    'Rpc-Hash: '. $this->pass . "\r\n",
                'content' => $jsonRequest
            ],
            "ssl"=> [
                "verify_peer"=>false,
                "verify_peer_name"=>false,
                'allow_self_signed' => true,
            ]
        ]);

        $jsonResponse = file_get_contents($this->url, false, $_ctx);
        $jsonResponse = json_decode( $jsonResponse );
        if( !isset( $jsonResponse->result ) ) {
            throw new Exception( VarDumper::dumpAsString($jsonResponse) );
        }
        else if( $jsonResponse->result && !isset( $jsonResponse->result->error ) ) {
            return $jsonResponse->result;
        }
        else {
            throw new Exception( $jsonResponse->result->error );
        }
    }
}
