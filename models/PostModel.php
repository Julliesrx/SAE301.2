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
    
    // --- MODIFICATION ICI : On ajoute u.pp ---
    $sql = "SELECT p.*, u.username, u.pp, c.name as cat_name 
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
    // --- MODIFICATION ICI : On ajoute u.pp ---
    $sql = "SELECT p.*, u.username, u.pp, c.name as cat_name 
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

// --- GESTION LIKES & DISLIKES ---

// Vérifications
function hasLiked($userId, $postId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE id_user = ? AND id_post = ?");
    $stmt->execute([$userId, $postId]);
    return $stmt->fetchColumn() > 0;
}

function hasDisliked($userId, $postId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM dislikes WHERE id_user = ? AND id_post = ?");
    $stmt->execute([$userId, $postId]);
    return $stmt->fetchColumn() > 0;
}

// Actions LIKE
function addLike($userId, $postId) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO likes (id_user, id_post) VALUES (?, ?)");
    return $stmt->execute([$userId, $postId]);
}

function removeLike($userId, $postId) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM likes WHERE id_user = ? AND id_post = ?");
    return $stmt->execute([$userId, $postId]);
}

// Actions DISLIKE
function addDislike($userId, $postId) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO dislikes (id_user, id_post) VALUES (?, ?)");
    return $stmt->execute([$userId, $postId]);
}

function removeDislike($userId, $postId) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM dislikes WHERE id_user = ? AND id_post = ?");
    return $stmt->execute([$userId, $postId]);
}

// Récupérer les posts d'un utilisateur spécifique (Pour l'onglet "Mes Posts")
function getPostsByUser($userId) {
    global $pdo;
    $sql = "SELECT p.*, c.name as cat_name 
            FROM posts p
            JOIN categories c ON p.id_category = c.id_category
            WHERE p.id_user = ? AND p.statut = 'PUBLISHED'
            ORDER BY p.creation DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

// Récupérer les posts LIKÉS par un utilisateur (Pour l'onglet "J'aime")
function getLikedPostsByUser($userId) {
    global $pdo;
    // --- MODIFICATION ICI : On ajoute u.pp ---
    $sql = "SELECT p.*, u.username, u.pp, c.name as cat_name 
            FROM likes l
            JOIN posts p ON l.id_post = p.id_post
            JOIN users u ON p.id_user = u.id_user
            JOIN categories c ON p.id_category = c.id_category
            WHERE l.id_user = ? AND p.statut = 'PUBLISHED'
            ORDER BY l.created_at DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

// Supprimer un post (Pour l'auteur ou l'admin)
function deletePost($postId) {
    global $pdo;
    // On récupère d'abord l'image pour la supprimer du dossier (optionnel mais propre)
    $stmt = $pdo->prepare("SELECT image_url FROM posts WHERE id_post = ?");
    $stmt->execute([$postId]);
    $img = $stmt->fetchColumn();

    if ($img && file_exists("assets/uploads/$img")) {
        unlink("assets/uploads/$img"); // Supprime le fichier
    }

    $stmt = $pdo->prepare("DELETE FROM posts WHERE id_post = ?");
    return $stmt->execute([$postId]);
}

// Vérifier qui est l'auteur (Sécurité)
function getPostAuthorId($postId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id_user FROM posts WHERE id_post = ?");
    $stmt->execute([$postId]);
    return $stmt->fetchColumn();
}