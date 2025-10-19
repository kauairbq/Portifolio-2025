<?php
// Script para testes minuciosos adicionais cobrindo todos os fluxos e cenários possíveis

function testService($url, $postData, $expectedSuccess = true, $description = '')
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    echo "Teste: $description\n";
    if ($error) {
        echo "Erro ao acessar $url: $error\n\n";
        return false;
    } else {
        echo "Resposta: $response\n";
        $data = json_decode($response, true);
        if ($data === null) {
            echo "Resposta inválida JSON\n\n";
            return false;
        }
        if (isset($data['success'])) {
            if ($data['success'] === $expectedSuccess) {
                echo "Resultado: Sucesso esperado\n\n";
                return true;
            } else {
                echo "Resultado: Falha - esperado success=$expectedSuccess, recebido success=" . var_export($data['success'], true) . "\n\n";
                return false;
            }
        } else {
            echo "Chave 'success' não encontrada na resposta\n\n";
            return false;
        }
    }
}

$baseUrl = "http://localhost/";

// Testes de login
testService($baseUrl . "process_login.php", ['username' => 'testuser', 'password' => 'testpass'], true, 'Login válido');
testService($baseUrl . "process_login.php", ['username' => 'testuser', 'password' => 'wrongpass'], false, 'Login com senha incorreta');
testService($baseUrl . "process_login.php", ['username' => '', 'password' => ''], false, 'Login com campos vazios');

// Testes de registro
$newUser = 'newuser' . rand(10000, 99999);
$newEmail = $newUser . '@example.com';
testService($baseUrl . "process_register.php", [
    'username' => $newUser,
    'password' => 'Newpass1',
    'confirm-password' => 'Newpass1',
    'email' => $newEmail,
    'user_type' => 'customer'
], true, 'Registro válido');

testService($baseUrl . "process_register.php", [
    'username' => 'ab',
    'password' => 'Newpass1',
    'confirm-password' => 'Newpass1',
    'email' => 'invalidemail',
    'user_type' => 'customer'
], false, 'Registro com nome de usuário curto e email inválido');

testService($baseUrl . "process_register.php", [
    'username' => $newUser,
    'password' => 'short',
    'confirm-password' => 'short',
    'email' => $newEmail,
    'user_type' => 'customer'
], false, 'Registro com senha curta');

testService($baseUrl . "process_register.php", [
    'username' => $newUser,
    'password' => 'Newpass1',
    'confirm-password' => 'Mismatch1',
    'email' => $newEmail,
    'user_type' => 'customer'
], false, 'Registro com senhas não correspondentes');

// Testes de carrinho
testService($baseUrl . "add_to_cart.php", ['product_id' => 1], true, 'Adicionar produto válido ao carrinho');
testService($baseUrl . "add_to_cart.php", ['product_id' => 99999], false, 'Adicionar produto inválido ao carrinho');

testService($baseUrl . "update_cart.php", ['product_id' => 1, 'quantity' => 2], true, 'Atualizar quantidade válida');
testService($baseUrl . "update_cart.php", ['product_id' => 1, 'quantity' => 9999], false, 'Atualizar quantidade inválida');

testService($baseUrl . "remove_from_cart.php", ['product_id' => 1], true, 'Remover produto do carrinho');
testService($baseUrl . "remove_from_cart.php", ['product_id' => 99999], false, 'Remover produto inexistente do carrinho');

// Testes de checkout
testService($baseUrl . "checkout.php", ['payment_method' => 'credit_card'], true, 'Checkout válido');
testService($baseUrl . "checkout.php", [], false, 'Checkout sem dados');

// Testes de feedback
testService($baseUrl . "submit_feedback.php", ['name' => 'User', 'email' => 'user@example.com', 'message' => 'Teste de feedback'], true, 'Envio de feedback válido');
testService($baseUrl . "submit_feedback.php", ['name' => '', 'email' => 'invalid', 'message' => ''], false, 'Envio de feedback inválido');

echo "Testes minuciosos concluídos.\n";
