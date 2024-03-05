<?php
class UserController {
    public function showAll() {
        // Logica per recuperare tutti gli utenti
        return json_encode(['message' => 'Get all users']);
    }

    public function showUser($id) {
       
        return json_encode(['message' => 'Get user with ID ' . $id]);
    }

    public function new() {
        // Logica per creare un nuovo utente
        return json_encode(['message' => 'Create user']);
    }

    public function update($id) {
        // Logica per aggiornare un utente esistente con ID $id
        return json_encode(['message' => 'Update user with ID ' . $id]);
    }

    public function destroy($id) {
        // Logica per eliminare un utente esistente con ID $id
        return json_encode(['message' => 'Delete user with ID ' . $id]);
    }
}