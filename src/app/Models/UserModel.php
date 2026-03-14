<?php

class UserModel {
    public function __construct(private PDO $db) {}

    public function insertNewUser($username, $email, $password): string {
        $hashPass = password_hash($password, PASSWORD_BCRYPT);
        $confirmToken = bin2hex(random_bytes(32));
        $confirmTokenExpireAt = date("Y-m-d H:i:s", time() + 3 * 86400);

        $uuid = bin2hex(random_bytes(16));
        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split($uuid, 4));

        try {
            $stmt = $this->db->prepare("INSERT INTO users (id, username, email, password_hash, confirm_token, confirm_token_expires_at) VALUES (:uuid, :username, :email, :password_hash, :confirm_token, :confirm_token_expires_at)");
            $stmt->execute([
                ':uuid'          => $uuid,
                ':username'      => $username,
                ':email'         => $email,
                ':password_hash' => $hashPass,
                ':confirm_token' => $confirmToken,
                ':confirm_token_expires_at' => $confirmTokenExpireAt        
            ]);
            return $confirmToken;
        } catch (PDOException $e) {
            $message = $e->getMessage();
            if ($e->getCode() === '23000') {
                if (str_contains($message, 'users.email')) {
                    throw new DuplicateEmailException("Email is already exist: $email");
                }
                if (str_contains($message, 'users.username')) {
                    throw new DuplicateUsernameException("Username is already exist: $username");
                }
                throw new DuplicateEntryException("Duplicated Entry");
            }
            throw new DatabaseException("Database Error: " . $message);
        }
    }

    public function findByEmailOrUsername(string $email, string $username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute([':username' => $username, ':email' => $email]);
        $existUser = $stmt->fetch();
        return $existUser;
    }

    public function findByConfirmToken(string $token) {
        $stmt = $this->db->prepare("SELECT is_confirmed, confirm_token_expires_at, id FROM users WHERE confirm_token = :confirm_token");
        $stmt->execute([':confirm_token' => $token]);
        $existUser = $stmt->fetch();
        return $existUser;
    }

    public function confirmUser(string $id) {
        try {
            $stmt = $this->db->prepare("UPDATE users SET confirm_token = NULL, confirm_token_expires_at = NULL, is_confirmed = 1, account_status='active' WHERE id = :userId");
            $stmt->execute([':userId' => $id]);
        } catch (PDOException $e) {
            error_log("DB Error: " . $e->getMessage());
        }
    }

    public function removeConfirmToken(string $id) {
        try {
            $stmt = $this->db->prepare("UPDATE users SET confirm_token = NULL WHERE id = :userId");
            $stmt->execute([':userId' => $id]);
        } catch (PDOException $e) {
            error_log("DB Error: " . $e->getMessage());
        }
    }
}