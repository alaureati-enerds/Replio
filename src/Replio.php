<?php

namespace Replio;

class Replio
{
    private array $response;
    private int $httpStatus;
    private array $headers;
    private int $jsonOptions = 0;

    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    public function __construct()
    {
        $this->response = [
            'success' => false,
            'message' => '',
            'data' => null,
            'errors' => [],
        ];
        $this->httpStatus = self::HTTP_OK;
    }

    /**
     * Risposta di successo
     */
    public function success(string $message = '', $data = null): self
    {
        $this->response['success'] = true;
        $this->response['message'] = $message;
        $this->response['data'] = $data;
        $this->httpStatus = self::HTTP_OK;
        return $this;
    }

    /**
     * Risposta di errore
     */
    public function error(string $message, int $httpStatus = self::HTTP_BAD_REQUEST, array $errors = []): self
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
     * Aggiunge header HTTP personalizzati
     */
    public function withHeaders(array $headers): self
    {
        foreach ($headers as $key => $value) {
            $this->headers[$key] = $value;
        }
        return $this;
    }

    /**
     * Imposta le opzioni di codifica JSON
     */
    public function withJsonOptions(int $options): self
    {
        $this->jsonOptions = $options;
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->httpStatus);

        $contentTypeSet = false;
        foreach ($this->headers as $key => $value) {
            if (strtolower($key) === 'content-type') {
                $contentTypeSet = true;
            }
            header("$key: $value");
        }

        if (!$contentTypeSet) {
            header('Content-Type: application/json');
        }

        $jsonResponse = json_encode($this->response, $this->jsonOptions);

        if ($jsonResponse === false) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Errore interno: impossibile generare la risposta JSON.',
                'errors' => [json_last_error_msg()]
            ]);
        } else {
            echo $jsonResponse;
        }

        exit;
    }
}
