<?php
    $banco = '../db/escola.db';
    
    if(!file_exists($banco)){
        $db = new SQLite3($banco);

        $db-> exec("Create table alunos(
        ID INTEGER PRIMARY KEY AUTOINCREMENT,
        Nome TEXT NOT NULL,
        Email TEXT NOT NULL,
        Curso TEXT NOT NULL,
        Idade TEXT NOT NULL
        )"
    );
    } else {
        echo "Banco de dados já existe";
    }
?>