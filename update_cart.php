<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

    if ($product_id <= 0 || $quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID de produto ou quantidade inválidos.']);
        exit;
    }

    if (!isset($_SESSION['cart'])) {
        echo json_encode(['success' => false, 'message' => 'Carrinho vazio.']);
        exit;
    }

    if (!array_key_exists($product_id, $_SESSION['cart'])) {
        echo json_encode(['success' => false, 'message' => 'Produto não encontrado no carrinho.']);
        exit;
    }

    // Connect to database to check stock
    include 'db.php';

    $stmt = $conn->prepare('SELECT stock FROM products WHERE id = ?');
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Erro na preparação da consulta.']);
        exit;
    }
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $stmt->bind_result($stock);
    if ($stmt->fetch()) {
        if ($quantity > $stock) {
            echo json_encode(['success' => false, 'message' => 'Quantidade solicitada excede o estoque disponível.']);
            $stmt->close();
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Produto não encontrado no banco de dados.']);
        $stmt->close();
        exit;
    }
    $stmt->close();

    $_SESSION['cart'][$product_id] = $quantity;

    echo json_encode(['success' => true, 'message' => 'Quantidade atualizada.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
}
