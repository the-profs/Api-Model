<?php 

/* UTILIZZO DELLA CLASSE Database:
    require_once 'Database.php';

    Istanzia la classe Database
    $database = new Database();

    Esegui una query di selezione
    $sql = "SELECT * FROM users WHERE id = ?";
    $params = [1];
    $users = $database->select($sql, $params);

    Esegui una query di inserimento
    $sql = "INSERT INTO users (username, email) VALUES (?, ?)";
    $params = ['john_doe', 'john@example.com'];
    $insertedId = $database->insert($sql, $params);

    Esegui una query di aggiornamento
    $sql = "UPDATE users SET username = ? WHERE id = ?";
    $params = ['jane_doe', 2];
    $affectedRows = $database->execute($sql, $params);

    Esegui una query di cancellazione
    $sql = "DELETE FROM users WHERE id = ?";
    $params = [3];
    $affectedRows = $database->execute($sql, $params);

    Chiudi la connessione al database
    $database->close();
*/
class Database {
    private $config;
    private $conn;

    // Costruttore per stabilire la connessione al database e caricare le informazioni sulle tabelle
    public function __construct() {
        $this->config = require 'database_config.php';

        $host = $this->config['host'];
        $username = $this->config['username'];
        $password = $this->config['password'];
        $database = $this->config['database'];
        $port = $this->config['port'];

        $this->conn = new mysqli($host, $username, $password, $database, $port);

        // Verifica se la connessione ha avuto successo
        if ($this->conn->connect_error) {
            die("Connessione al database fallita: " . $this->conn->connect_error);
        }
    }

    // Metodo per eseguire query preparate con i bind
    public function executeQuery($sql, $params = []) {
        $statement = $this->conn->prepare($sql);

        if ($statement === false) {
            die("Errore nella preparazione della query: " . $this->conn->error);
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $statement->bind_param($types, ...$params);
        }

        $result = $statement->execute();

        if ($result === false) {
            die("Errore nell'esecuzione della query: " . $statement->error);
        }

        return $statement->get_result();
    }

    // Metodo per eseguire una query di selezione e restituire i risultati come array associativo
    public function select($sql, $params = []) {
        $result = $this->executeQuery($sql, $params);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    // Metodo per eseguire una query di inserimento e restituire l'ID dell'ultimo record inserito
    public function insert($sql, $params = []) {
        $result = $this->executeQuery($sql, $params);
        return $this->conn->insert_id;
    }

    // Metodo per eseguire una query di aggiornamento o cancellazione e restituire il numero di righe interessate
    public function execute($sql, $params = []) {
        $result = $this->executeQuery($sql, $params);
        return $this->conn->affected_rows;
    }

    // Metodo per evitare l'iniezione SQL
    public function escapeString($str) {
        return $this->conn->real_escape_string($str);
    }

    // Metodo per chiudere la connessione al database
    public function close() {
        $this->conn->close();
    }

     // Metodo per generare una query di selezione con condizioni e bind
     public function generateSelectQuery($table, $conditions = []) {
        $sql = "SELECT * FROM $table";

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $conditionsSql = [];

            foreach ($conditions as $column => $value) {
                $conditionsSql[] = "$column = ?";
            }

            $sql .= implode(" AND ", $conditionsSql);
        }

        return $sql;
    }

    // Metodo per generare una query di inserimento con bind
    public function generateInsertQuery($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        return $sql;
    }

    // Metodo per generare una query di aggiornamento con bind
    public function generateUpdateQuery($table, $data, $conditions = []) {
        $setSql = [];

        foreach ($data as $column => $value) {
            $setSql[] = "$column = ?";
        }

        $sql = "UPDATE $table SET " . implode(", ", $setSql);

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $conditionsSql = [];

            foreach ($conditions as $column => $value) {
                $conditionsSql[] = "$column = ?";
            }

            $sql .= implode(" AND ", $conditionsSql);
        }

        return $sql;
    }    
}