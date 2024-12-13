# Replio

Replio è una libreria PHP progettata per semplificare la gestione delle risposte JSON nelle API. Fornisce una struttura coerente per le risposte e metodi utility per gestire facilmente successi, errori e dati aggiuntivi.

---

## Installazione

Puoi installare Replio nel tuo progetto tramite Composer:

```bash
composer require alaureati-enerds/replio
```

---

## Utilizzo

Esempi pratici di come utilizzare la libreria.

### Risposta di Successo

```php
require 'vendor/autoload.php';

use Replio\Replio;

$response = new Replio();
$response->success('Operazione riuscita', ['id' => 123])->send();
```

### Risposta di Errore

```php
require 'vendor/autoload.php';

use Replio\Replio;

$response = new Replio();
$response->error('Errore nella richiesta', 422, ['field' => 'Questo campo è obbligatorio'])->send();
```

### Risposta Personalizzata

Puoi aggiungere dati o errori specifici e modificare lo stato HTTP:

```php
require 'vendor/autoload.php';

use Replio\Replio;

$response = new Replio();
$response
    ->success('Operazione parzialmente riuscita')
    ->withData(['processed' => 5, 'failed' => 2])
    ->withErrors(['missing_fields' => ['name', 'email']])
    ->withStatus(207)
    ->send();
```

---

## Metodi Disponibili

- **success(string $message, mixed $data = null)**: Imposta una risposta di successo.
- **error(string $message, int $httpStatus, array $errors = [])**: Imposta una risposta di errore.
- **withData(mixed $data)**: Aggiunge dati alla risposta.
- **withErrors(array $errors)**: Aggiunge errori specifici.
- **withStatus(int $httpStatus)**: Imposta lo stato HTTP della risposta.
- **send()**: Invia la risposta JSON al client.

---

## Struttura della Risposta JSON

La struttura della risposta generata da Replio è la seguente:

```json
{
    "success": true|false,
    "message": "Descrizione della risposta",
    "data": "Dati della risposta (opzionale)",
    "errors": "Elenco di errori (opzionale)"
}
```

---

## Test

Puoi eseguire i test utilizzando PHPUnit:

```bash
./vendor/bin/phpunit tests
```

---

## Contributi

I contributi sono benvenuti! Sentiti libero di aprire una issue o creare una pull request.

---

## Licenza

Questo progetto è distribuito sotto licenza MIT. Consulta il file [LICENSE](LICENSE) per maggiori dettagli.
