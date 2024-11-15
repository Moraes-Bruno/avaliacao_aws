<?php

class ApiClient {
    private $apiUrl;

    public function __construct($apiUrl) {
        $this->apiUrl = $apiUrl;
    }

    private function sendRequest($method, $url, $data = null) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ]);

        if ($data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    // CREATE
    public function createItem($item) {
        return $this->sendRequest('POST', $this->apiUrl, $item);
    }

    // READ
    public function getItems() {
        return $this->sendRequest('GET', $this->apiUrl);
    }

    // UPDATE
    public function updateItem($id, $item) {
        $url = "{$this->apiUrl}/{$id}";
        return $this->sendRequest('PUT', $url, $item);
    }

    // DELETE
    public function deleteItem($id) {
        $url = "{$this->apiUrl}/{$id}";
        return $this->sendRequest('DELETE', $url);
    }
}
?>
