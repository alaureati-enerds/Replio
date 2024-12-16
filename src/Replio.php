<?php

namespace Replio;

/**
 * Classe Replio
 * 
 * Questa classe fornisce un'interfaccia per creare e gestire risposte HTTP
 * standardizzate, supportando codici di stato, header personalizzati, opzioni JSON
 * e strutture di risposta flessibili.
 */
class Replio
{
    /**
     * @var array $response Struttura della risposta standard
     */
    private array $response;

    /**
     * @var int $httpStatus Codice HTTP della risposta
     */
    private int $httpStatus;

    /**
     * @var array $headers Header HTTP personalizzati
     */
    private array $headers;

    /**
     * @var int $jsonOptions Opzioni di codifica JSON
     */
    private int $jsonOptions = 0;

    // Costanti per i codici di stato HTTP
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    /**
     * Costruttore
     * 
     * Inizializza la struttura della risposta con valori predefiniti.
     */
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
     * Imposta una risposta di successo.
     * 
     * @param string $message Messaggio di successo (opzionale)
     * @param mixed $data Dati aggiuntivi da includere nella risposta (opzionale)
     * @return self
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
     * Imposta una risposta di errore.
     * 
     * @param string $message Messaggio di errore
     * @param int $httpStatus Codice di stato HTTP (opzionale, default: 400)
     * @param array $errors Elenco dettagliato degli errori (opzionale)
     * @return self
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
     * Aggiunge ulteriori dati alla risposta.
     * 
     * @param mixed $data Dati aggiuntivi
     * @return self
     */
    public function withData($data): self
    {
        $this->response['data'] = $data;
        return $this;
    }

    /**
     * Aggiunge errori specifici alla risposta.
     * 
     * @param array $errors Array di errori
     * @return self
     */
    public function withErrors(array $errors): self
    {
        $this->response['errors'] = $errors;
        return $this;
    }

    /**
     * Imposta il codice di stato HTTP della risposta.
     * 
     * @param int $httpStatus Codice di stato HTTP
     * @return self
     */
    public function withStatus(int $httpStatus): self
    {
        $this->httpStatus = $httpStatus;
        return $this;
    }

    /**
     * Aggiunge header HTTP personalizzati alla risposta.
     * 
     * @param array $headers Array di header in formato chiave => valore
     * @return self
     */
    public function withHeaders(array $headers): self
    {
        foreach ($headers as $key => $value) {
            $this->headers[$key] = $value;
        }
        return $this;
    }

    /**
     * Imposta le opzioni di codifica JSON.
     * 
     * @param int $options Costanti JSON, come JSON_PRETTY_PRINT o JSON_UNESCAPED_SLASHES
     * @return self
     */
    public function withJsonOptions(int $options): self
    {
        $this->jsonOptions = $options;
        return $this;
    }

    /**
     * Invia la risposta HTTP al client.
     * 
     * Questo metodo:
     * - Imposta il codice di stato HTTP
     * - Aggiunge header HTTP personalizzati
     * - Codifica la risposta in formato JSON
     * 
     * @return void
     */
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
    }
}
