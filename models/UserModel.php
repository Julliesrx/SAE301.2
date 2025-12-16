<?php

function createUser($username, $email, $password) {
    global $pdo; // Récupère la connexion de db.php

    // 1. Hachage du mot de passe
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // 2. Insertion (Attention aux noms de tes colonnes)
    $sql = "INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'user')";
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([$username, $email, $hash]);
}

function getUserByEmail($email) {
    global $pdo;
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    return $stmt->fetch();
}