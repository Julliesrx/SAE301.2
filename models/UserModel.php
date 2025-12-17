<?php

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

//  user
function createUser($username, $email, $password, $passionName) {
    global $pdo;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (username, email, password_hash, role, passion, pp) VALUES (?, ?, ?, 'user', ?, 'default.jpg')";
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([$username, $email, $hash, $passionName]);
}

function updateUser($userId, $bio, $passionName, $newPP = null) {
    global $pdo;
    
    if ($newPP) {
        $sql = "UPDATE users SET bio = ?, passion = ?, pp = ? WHERE id_user = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$bio, $passionName, $newPP, $userId]);
    } 
    else {
        $sql = "UPDATE users SET bio = ?, passion = ? WHERE id_user = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$bio, $passionName, $userId]);
    }
}