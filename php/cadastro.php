<?php
    $banco = '../db/escola.db';
    $db = new SQLite3($banco);

    // Recebendo os dados do formulário via POST
    $nome = $_POST['name'];
    $email = $_POST['email'];
    $idade = $_POST['idade'];
    $curso = $_POST['curso'];

    // Prepara a consulta SQL usando placeholders para prevenir SQL injection
    $stmt = $db->prepare("INSERT INTO alunos (Nome, Email, Curso, Idade) VALUES (:nome, :email, :curso, :idade)");

    // Bind dos parâmetros às variáveis para prevenir SQL injection
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':curso', $curso);
    $stmt->bindParam(':idade', $idade);

    // Executa a consulta e verifica se foi bem-sucedida
    if($stmt->execute()){
        echo "Cadastro concluído com sucesso!";
    } else{
        echo "Erro ao cadastrar";
    }
?>
