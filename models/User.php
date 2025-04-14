<?php
namespace Models;

use PDO;
use PDOException;

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($data) {
        try {
            // Verificar si ya existe un usuario con ese correo electrónico
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$data['email']]);

            if ($stmt->rowCount() > 0) {
                return false; // Ya existe
            }

            // Insertar nuevo usuario
            $stmt = $this->conn->prepare("
                INSERT INTO users (first_name, last_name, username, email, password, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");

            return $stmt->execute([
                $data['first_name'],
                $data['last_name'],
                $data['username'],
                $data['email'],
                $data['password']
            ]);
        } catch (PDOException $e) {
            // Opcional: log del error
            error_log($e->getMessage());
            return false;
        }
    }

    public function login($email, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }

            return false;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
