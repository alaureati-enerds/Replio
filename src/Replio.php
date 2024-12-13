<?php

namespace Replio;

class Replio
{
    private array $response;
    private int $httpStatus;

    public function __construct()
    {
        $this->response = [
            'success' => false,
            'message' => '',
            'data' => null,
            'errors' => [],
        ];
        $this->httpStatus = 200;
    }

    /**
     * Risposta di successo
     */
    public function success(string $message = '', $data = null): self
    {
        $this->response['success'] = true;
        $this->response['message'] = $message;
        $this->response['data'] = $data;
        $this->httpStatus = 200;
        return $this;
    }

    /**
     * Risposta di errore
     */
    public function error(string $message, int $httpStatus = 400, array $errors = []): self
    {
        $this->response['success'] = false;
        $this->response['message'] = $message;
        $this->response['errors'] = $errors;
        $this->httpStatus = $httpStatus;
        return $this;
    }

    /**
     * Aggiunge ulteriori dati alla risposta
     */
    public function withData($data): self
    {
        $this->response['data'] = $data;
        return $this;
    }

    /**
     * Aggiunge errori specifici alla risposta
     */
    public function withErrors(array $errors): self
    {
        $this->response['errors'] = $errors;
        return $this;
    }

    /**
     * Imposta lo status HTTP della risposta
     */
    public function withStatus(int $httpStatus): self
    {
        $this->httpStatus = $httpStatus;
        return $this;
    }

    /**
     * Restituisce la risposta come JSON
     */
    public function send(): void
    {
        http_response_code($this->httpStatus);
        header('Content-Type: application/json');
        echo json_encode($this->response);
        exit;
    }
}
