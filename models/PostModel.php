<?php

// 1. Récupérer toutes les catégories (Pour le select du formulaire)
function getAllCategories() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    return $stmt->fetchAll();
}

// 2. Créer un post (Statut PENDING par défaut)
function createPost($userId, $categoryId, $filename, $description) {
    global $pdo;
    
    $sql = "INSERT INTO posts (id_user, id_category, image_url, description, statut, creation) 
            VALUES (?, ?, ?, ?, 'PENDING', NOW())";
            
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$userId, $categoryId, $filename, $description]);
}

// 3. Récupérer les posts PUBLIÉS (Pour le Feed d'accueil)
function getPublishedPosts($categoryId = null) {
    global $pdo;
    
    $sql = "SELECT p.*, u.username, c.name as cat_name 
            FROM posts p
            JOIN users u ON p.id_user = u.id_user
            JOIN categories c ON p.id_category = c.id_category
            WHERE p.statut = 'PUBLISHED'";
    
    $params = [];

    // Si une catégorie est demandée dans l'URL
    if ($categoryId) {
        $sql .= " AND p.id_category = ?";
        $params[] = $categoryId;
    }

    $sql .= " ORDER BY p.creation DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}


// 4. Récupérer les posts EN ATTENTE (Pour l'Admin)
function getPendingPosts() {
    global $pdo;
    // On veut voir qui a posté (users) et quelle catégorie (categories)
    $sql = "SELECT p.*, u.username, c.name as cat_name 
            FROM posts p
            JOIN users u ON p.id_user = u.id_user
            JOIN categories c ON p.id_category = c.id_category
            WHERE p.statut = 'PENDING'
            ORDER BY p.creation ASC";
            
    return $pdo->query($sql)->fetchAll();
}

// 5. Mettre à jour le statut (Valider ou Refuser)
function updatePostStatus($postId, $status) {
    global $pdo;
    $sql = "UPDATE posts SET statut = ? WHERE id_post = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$status, $postId]);
}