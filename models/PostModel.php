<?php

function getAllCategories()
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    return $stmt->fetchAll();
}

// post
function createPost($userId, $categoryId, $filename, $description)
{
    global $pdo;

    $sql = "INSERT INTO posts (id_user, id_category, image_url, description, statut, creation) 
            VALUES (?, ?, ?, ?, 'PENDING', NOW())";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$userId, $categoryId, $filename, $description]);
}

function getPublishedPosts($categoryId = null)
{
    global $pdo;

    $sql = "SELECT p.*, u.username, u.pp, c.name as cat_name 
            FROM posts p
            JOIN users u ON p.id_user = u.id_user
            JOIN categories c ON p.id_category = c.id_category
            WHERE p.statut = 'PUBLISHED'";

    $params = [];

    if ($categoryId) {
        $sql .= " AND p.id_category = ?";
        $params[] = $categoryId;
    }

    $sql .= " ORDER BY p.creation DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getPostsByUser($userId)
{
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

// admin
function getPendingPosts()
{
    global $pdo;
    $sql = "SELECT p.*, u.username, u.pp, c.name as cat_name 
            FROM posts p
            JOIN users u ON p.id_user = u.id_user
            JOIN categories c ON p.id_category = c.id_category
            WHERE p.statut = 'PENDING'
            ORDER BY p.creation ASC";

    return $pdo->query($sql)->fetchAll();
}

function updatePostStatus($postId, $status)
{
    global $pdo;
    $sql = "UPDATE posts SET statut = ? WHERE id_post = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$status, $postId]);
}

// like & masquée
function hasLiked($userId, $postId)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE id_user = ? AND id_post = ?");
    $stmt->execute([$userId, $postId]);
    return $stmt->fetchColumn() > 0;
}

function hasDisliked($userId, $postId)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM dislikes WHERE id_user = ? AND id_post = ?");
    $stmt->execute([$userId, $postId]);
    return $stmt->fetchColumn() > 0;
}

function addLike($userId, $postId)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO likes (id_user, id_post) VALUES (?, ?)");
    return $stmt->execute([$userId, $postId]);
}

function removeLike($userId, $postId)
{
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM likes WHERE id_user = ? AND id_post = ?");
    return $stmt->execute([$userId, $postId]);
}

function addDislike($userId, $postId)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO dislikes (id_user, id_post) VALUES (?, ?)");
    return $stmt->execute([$userId, $postId]);
}

function removeDislike($userId, $postId)
{
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM dislikes WHERE id_user = ? AND id_post = ?");
    return $stmt->execute([$userId, $postId]);
}

function getLikedPostsByUser($userId)
{
    global $pdo;
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

function deletePost($postId)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT image_url FROM posts WHERE id_post = ?");
    $stmt->execute([$postId]);
    $img = $stmt->fetchColumn();

    if ($img && file_exists("assets/uploads/$img")) {
        unlink("assets/uploads/$img"); 
    }

    $stmt = $pdo->prepare("DELETE FROM posts WHERE id_post = ?");
    return $stmt->execute([$postId]);
}

function getPostAuthorId($postId)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT id_user FROM posts WHERE id_post = ?");
    $stmt->execute([$postId]);
    return $stmt->fetchColumn();
}

// commentaires
function addComment($userId, $postId, $content)
{
    global $pdo;
    $sql = "INSERT INTO comments (id_user, id_post, content, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$userId, $postId, $content]);
}

function getCommentsByPost($postId)
{
    global $pdo;
    $sql = "SELECT c.*, u.username, u.pp 
            FROM comments c 
            JOIN users u ON c.id_user = u.id_user 
            WHERE c.id_post = ? 
            ORDER BY c.created_at ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$postId]);
    return $stmt->fetchAll();
}
