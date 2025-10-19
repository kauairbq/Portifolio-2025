<?php
include 'db.php';
session_start();

// Fetch products from database
$result = $conn->query("SELECT * FROM products WHERE stock > 0");
$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Loja Online</title>
    <style>
        body {
            background-color: #212226;
            color: #fff;
            font-family: "Lato", sans-serif;
            margin: 0;
            padding: 20px;
        }

        .product {
            border: 1px solid #CFF250;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 15px;
            background-color: #333;
        }

        .product h2 {
            margin: 0 0 10px 0;
            color: #CFF250;
        }

        .product p {
            margin: 5px 0;
        }

        .product button {
            background-color: #CFF250;
            border: none;
            color: #212226;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .product button:hover {
            background-color: #212226;
            color: #CFF250;
        }

        .cart-link {
            margin-bottom: 20px;
            display: inline-block;
            color: #CFF250;
            text-decoration: none;
            font-weight: bold;
        }

        .cart-link:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function addToCart(productId) {
            fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'product_id=' + productId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Produto adicionado ao carrinho!');
                    } else {
                        alert('Erro ao adicionar ao carrinho: ' + data.message);
                    }
                })
                .catch(() => alert('Erro na requisição.'));
        }
    </script>
</head>

<body>
    <a href="cart.php" class="cart-link">Ver Carrinho</a>
    <h1>Loja Online</h1>
    <?php foreach ($products as $product): ?>
        <div class="product">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            <p>Preço: €<?php echo number_format($product['price'], 2); ?></p>
            <p>Stock: <?php echo (int)$product['stock']; ?></p>
            <button onclick="addToCart(<?php echo (int)$product['id']; ?>)">Adicionar ao Carrinho</button>
        </div>
    <?php endforeach; ?>
</body>

</html>