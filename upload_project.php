<?php
// Function to handle image uploads
function uploadImage($file)
{
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($file["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($file["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        return false;
    } else {
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($file["name"])) . " has been uploaded.";
            // Update projects.json with the new image path
            updateJson($targetFile);
            return $targetFile;
        } else {
            echo "Sorry, there was an error uploading your file.";
            return false;
        }
    }
}

// Function to update projects.json
function updateJson($imagePath)
{
    $jsonFile = 'projects.json';
    if (!file_exists($jsonFile)) {
        $data = [];
    } else {
        $data = json_decode(file_get_contents($jsonFile), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Erro ao ler o arquivo JSON.";
            exit;
        }
    }

    // Append new image path to the projects array
    $data[] = [
        "title" => "New Project",
        "description" => "Description of the new project.",
        "image" => $imagePath
    ];

    // Save updated data back to JSON file
    if (file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT)) === false) {
        echo "Erro ao salvar os dados no arquivo JSON.";
        exit;
    }
}

// Database connection (update with your own credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Portfolio2025";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$project_name = isset($_POST['project_name']) ? htmlspecialchars(trim($_POST['project_name'])) : '';
$project_description = isset($_POST['project_description']) ? htmlspecialchars(trim($_POST['project_description'])) : '';
$project_image = isset($_FILES['project_image']) ? $_FILES['project_image'] : null;

// Validate input
if (empty($project_name) || empty($project_description)) {
    echo "Nome do projeto e descrição são obrigatórios.";
    exit;
}

// Handle file upload
if ($project_image && $project_image['error'] == 0) {
    $uploadedFilePath = uploadImage($project_image);
    if ($uploadedFilePath === false) {
        exit;
    }
} else {
    $uploadedFilePath = null;
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO projects (name, description, image) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $project_name, $project_description, $uploadedFilePath);

// Execute the statement
if ($stmt->execute()) {
    echo "Novo projeto adicionado com sucesso.";
} else {
    error_log("Erro ao adicionar o projeto: " . $stmt->error);
    echo "Erro ao adicionar o projeto. Por favor, tente novamente mais tarde.";
}

$stmt->close();
$conn->close();
