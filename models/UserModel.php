<?php
// models/UserModel.php

// 1. Récupérer un user par son ID
function getUserById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getUserByEmail($email) {
    global $pdo;
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    return $stmt->fetch();
}

// 2. Création (Mise à jour pour inclure la passion/catégorie)
function createUser($username, $email, $password, $passionName) {
    global $pdo;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    // On insère aussi la passion (Nom de la catégorie choisie)
    $sql = "INSERT INTO users (username, email, password_hash, role, passion, pp) VALUES (?, ?, ?, 'user', ?, 'default.jpg')";
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([$username, $email, $hash, $passionName]);
}

// 3. Mise à jour du profil (NOUVEAU)
function updateUser($userId, $bio, $passionName, $newPP = null) {
    global $pdo;
    
    // Si on change la photo
    if ($newPP) {
        $sql = "UPDATE users SET bio = ?, passion = ?, pp = ? WHERE id_user = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$bio, $passionName, $newPP, $userId]);
    } 
    // Si on garde l'ancienne photo
    else {
        $sql = "UPDATE users SET bio = ?, passion = ? WHERE id_user = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$bio, $passionName, $userId]);
    }
}