<?php
$banco = '../db/escola.db';
$db = new SQLite3($banco);

// Obtendo o ID do POST
$ID = $_POST['id'];

// Preparando a consulta para evitar SQL Injection
$stmt = $db->prepare("DELETE FROM alunos WHERE ID = :ID");
$stmt->bindValue(':ID', $ID, SQLITE3_INTEGER); // Certifique-se de usar o tipo correto

// Executando a consulta
if ($stmt->execute()) {
    echo "Registro deletado";
} else {
    echo "Falha ao deletar registro: " . $db->lastErrorMsg();
}

// Fechando a conexão com o banco de dados
$db->close();
?>
