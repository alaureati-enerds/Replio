# Replio

Replio è una libreria PHP progettata per semplificare la gestione delle risposte HTTP nelle applicazioni web. Offre un'interfaccia flessibile per creare risposte standardizzate con supporto per codici di stato, header personalizzati e opzioni di codifica JSON.

## Caratteristiche

- Creazione di risposte HTTP standardizzate
- Supporto per codici di stato HTTP predefiniti
- Personalizzazione di header HTTP
- Aggiunta di dati ed errori alle risposte
- Opzioni di codifica JSON personalizzabili
- Facile da integrare e utilizzare

## Installazione

1. Aggiungi il pacchetto al tuo progetto utilizzando Composer:

   ```bash
   composer require alaureati-enerds/replio
   ```

2. Assicurati che il file `vendor/autoload.php` sia incluso nel tuo progetto per caricare automaticamente la classe:

   ```php
   require_once 'vendor/autoload.php';
   ```

## Utilizzo

### Risposta di successo

```php
use Replio\Replio;

$replio = new Replio();
$replio->success('Operazione completata con successo!', ['key' => 'value'])
       ->send();
```

### Risposta di errore

```php
use Replio\Replio;

$replio = new Replio();
$replio->error('Errore nella richiesta', Replio::HTTP_BAD_REQUEST, ['campo' => 'Errore dettagliato'])
       ->send();
```

### Personalizzazione della risposta

```php
use Replio\Replio;

$replio = new Replio();
$replio->withData(['chiave' => 'valore'])
       ->withStatus(Replio::HTTP_CREATED)
       ->withHeaders(['X-Custom-Header' => 'Valore personalizzato'])
       ->send();
```

## Metodi Principali

#### `success(string $message = '', $data = null): self`

Imposta una risposta di successo.

#### `error(string $message, int $httpStatus = 400, array $errors = []): self`

Imposta una risposta di errore.

#### `withData($data): self`

Aggiunge dati alla risposta.

#### `withErrors(array $errors): self`

Aggiunge errori specifici alla risposta.

#### `withStatus(int $httpStatus): self`

Imposta il codice di stato HTTP della risposta.

#### `withHeaders(array $headers): self`

Aggiunge header personalizzati alla risposta.

#### `withJsonOptions(int $options): self`

Imposta le opzioni di codifica JSON.

#### `send(): void`

Invia la risposta HTTP al client.

## Licenza

Questo progetto è rilasciato sotto la licenza MIT. Per ulteriori dettagli, consulta il file LICENSE.

## Contributi

I contributi sono benvenuti! Sentiti libero di aprire una issue o creare una pull request.

## Contatti

Per domande o segnalazioni, puoi scrivere una mail a [a.laureati@enerds.it](mailto:a.laureati@enerds.it).
