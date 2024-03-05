
Documentazione dell'API
Benvenuto nella documentazione dell'API! Questa API fornisce un'interfaccia per interagire con il database e eseguire operazioni CRUD (Create, Read, Update, Delete) su di esso.

Endpoint Base
L'endpoint base per accedere all'API è:

http://example.com/api/v1
Operazioni CRUD per gli Utenti
Ottieni tutti gli utenti
bash

GET /api/v1/users
Restituisce tutti gli utenti presenti nel database.

Ottieni un singolo utente
bash

GET /api/v1/users/{id}
Restituisce le informazioni di un singolo utente in base al suo ID.

Crea un nuovo utente
bash

POST /api/v1/users
Content-Type: application/json

{
    "username": "new_user",
    "email": "new_user@example.com",
    "password": "new_password"
}
Crea un nuovo utente con le informazioni fornite nel corpo della richiesta.

Aggiorna un utente esistente
bash

PUT /api/v1/users/{id}
Content-Type: application/json

{
    "email": "updated_email@example.com"
}
Aggiorna le informazioni di un utente esistente in base al suo ID.

Elimina un utente esistente
bash

DELETE /api/v1/users/{id}
Elimina un utente esistente in base al suo ID.

Esempi di Utilizzo
Esempio di utilizzo con cURL
Ottieni tutti gli utenti:
bash

curl http://example.com/api/v1/users
Ottieni un singolo utente:
bash

curl http://example.com/api/v1/users/1
Crea un nuovo utente:
bash

curl -X POST http://example.com/api/v1/users \
     -H "Content-Type: application/json" \
     -d '{"username": "new_user", "email": "new_user@example.com", "password": "new_password"}'
Aggiorna un utente esistente:
bash

curl -X PUT http://example.com/api/v1/users/1 \
     -H "Content-Type: application/json" \
     -d '{"email": "updated_email@example.com"}'
Elimina un utente esistente:
bash

curl -X DELETE http://example.com/api/v1/users/1
Esempio di utilizzo con PHP (cURL)
php

<?php

// Ottieni tutti gli utenti
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://example.com/api/v1/users");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo $response;

?>
