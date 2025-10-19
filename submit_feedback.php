<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = strip_tags(trim($_POST["nome"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $mensagem = trim($_POST["mensagem"]);

    if (empty($nome) || empty($mensagem) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      http_response_code(400);
      echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos corretamente.']);
      exit;
    }

    include 'db.php';
    if (!isset($conn)) {
      throw new Exception("Conexão com o banco não inicializada.");
    }

    $stmt = $conn->prepare("INSERT INTO feedback (nome, email, mensagem) VALUES (?, ?, ?)");
    if (!$stmt) {
      throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sss", $nome, $email, $mensagem);

    if ($stmt->execute()) {
      // Debug: Log dos dados recebidos
      error_log("Debug: nome=$nome, email=$email, mensagem=$mensagem");

      // Temporariamente comentar o envio de email para teste
      /*
      $destinatario = "kauai_lucas@hotmail.com";
      $subject = "Novo Feedback do Site";
      $email_content = "Nome: $nome\nEmail: $email\n\nMensagem:\n$mensagem\n";
      $email_headers = "From: $nome <$email>\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8\r\n";

      try {
        $email_sent = mail($destinatario, $subject, $email_content, $email_headers);
      } catch (Exception $e) {
        $email_sent = false;
        error_log("Erro ao enviar e-mail: " . $e->getMessage());
      }

      if ($email_sent) {
        echo json_encode(['success' => true, 'message' => 'Obrigado! Seu feedback foi enviado com sucesso.']);
      } else {
        echo json_encode(['success' => true, 'message' => 'Feedback salvo, mas o e-mail não pôde ser enviado (verifique configuração do servidor).']);
      }
      */

      // Resposta de sucesso sem email
      echo json_encode(['success' => true, 'message' => 'Obrigado! Seu feedback foi enviado com sucesso.']);
    } else {
      throw new Exception("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
  } else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
  }
} catch (Exception $e) {
  error_log("Erro no feedback.php: " . $e->getMessage());
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Erro interno do servidor.']);
}
