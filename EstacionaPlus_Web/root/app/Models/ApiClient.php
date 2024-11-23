<?php

namespace App\Models;

class ApiClient {
    
    private $apiUrl;
    
    public function __construct($apiUrl = "https://ekiuak4c87.execute-api.us-east-1.amazonaws.com/prod") {
        $this->apiUrl = $apiUrl;
    }

    private function sendRequest($operation = "", $payload = "") {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl . "/estacionamentos",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ]);
        
        $data = array(
            "operation"=>$operation,
            "payload"=>$payload
            );
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
    
    public function validLogin($email, $senha){
        $payload = array(
            "email"=>$email,
            "senha"=>$senha
        );
        
                $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl . "/login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ]);
        
        $data = array(
            "payload"=>$payload
            );
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    // CREATE
    public function create($item) {
        $payload = array(
        "Estacionamento"=>$item
        );

        return $this->sendRequest("create", $payload);
    }

    // READ ONE ITEM
    public function readOne($id) {
        $payload = $this->createIdPayload($id);
        return $this->sendRequest("readOne", $payload);
    }
    
    // READ
    public function readAll() {
        $payload = "";
        return $this->sendRequest("read", $payload);
    }

    // UPDATE
    public function update($id, $item) {
        $payload = array(
            "Key"=>array(
              "_id"=>$id
            ),
            "UpdateExpression"=>"SET #campoNome = :newNome and #campoEndereco = :newEndereco and #campoTotalVagas = :newTotalVagas and #campoVagas = :newVagas",
            "ExpressionAttributeNames"=>array(
              "#campoNome"=>"nome",
              "#campoEndereco"=>"endereco",
              "#campoTotalVagas"=>"totalVagas",
              "#campoVagas"=>"vagas"
            ),
            "ExpressionAttributeValues"=>array(
              ":newNome"=>$item['nome'],
              ":newEndereco"=>$item['endereco'],
              ":newTotalVagas"=>$item['totalVagas'],
              ":newVagas"=>$item['vagas']
            )
        );
        return $this->sendRequest("update", $payload);
    }

    // DELETE
    public function delete($id) {
        $payload = $this->createIdPayload($id);
        return $this->sendRequest("delete", $payload);
    }
    
    private function createIdPayload($id){
        return   array(
            "Key"=>array(
              "_id"=>$id
            )
          );
    }
}
?>
