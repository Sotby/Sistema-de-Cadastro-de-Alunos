<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <title>SENAI</title>
</head>
<body>
    <nav>
        <img src="./assets/logo.png" alt="Logo">
    </nav>
    <header>
        <h1> Cadastre um Aluno</h1>
        <form id="cadastroForm">
            <label for="name">Nome</label>
            <input type="text" name="name" required>
            <label for="email">Email</label>
            <input type="email" name="email" required>
            <label for="idade">Idade</label>
            <input type="number" name="idade" required>
            <label for="curso">Curso</label>
            <input type="text" name="curso" required>
            <button type="submit">Cadastrar</button>
        </form>

        <script>
            document.getElementById('cadastroForm').addEventListener('submit', function(e) {
                e.preventDefault(); // Evita o envio padrão do formulário
                const formData = new FormData(this);

                fetch('php/cadastro.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Exibe a resposta do servidor
                    location.reload(); // Atualiza a página para refletir a adição
                })
                .catch(error => console.error('Erro:', error));
            });
        </script>
    </header>
    <section>
        <div>
        <h1>Últimos Alunos Cadastrados</h1>
        <?php
        try {
            // Conectando ao banco de dados SQLite
            $db = new SQLite3("db/escola.db");

            // Inicializando a consulta SQL base
            $sql = "SELECT * FROM alunos WHERE 1=1";
            $params = [];

            // Verificando se o campo ID foi preenchido
            if (isset($_GET['ID']) && !empty($_GET['ID'])) {
                $sql .= " AND ID = :ID";
                $params[':ID'] = $_GET['ID'];
            }

            // Verificando se o campo Nome foi preenchido
            if (isset($_GET['Nome']) && !empty($_GET['Nome'])) {
                $sql .= " AND Nome LIKE :Nome";
                $params[':Nome'] = '%' . $_GET['Nome'] . '%';
            }

            // Verificando se o campo Curso foi preenchido
            if (isset($_GET['Curso']) && !empty($_GET['Curso'])) {
                $sql .= " AND Curso LIKE :Curso";
                $params[':Curso'] = '%' . $_GET['Curso'] . '%';
            }

            // Verifica se pelo menos um campo foi preenchido
            if (count($params) > 0) {
                // Preparando a consulta
                $stmt = $db->prepare($sql);

                // Ligando os parâmetros
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value);
                }

                // Executa a consulta preparada
                $result = $stmt->execute();
            } else {
                // Consulta padrão quando nenhum campo é preenchido
                $sql = "SELECT * FROM alunos ORDER BY ID DESC LIMIT 10";
                $result = $db->query($sql);
            }

            // Verificando se há registros retornados
            if ($result && $result->fetchArray(SQLITE3_ASSOC)) {
                // Reinicia o cursor do resultado
                $result->reset();

                echo "<table>
                        <tr id='Theader'>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Idade</th>
                            <th>Email</th>
                            <th>Curso</th>
                            <th>Ação</th>
                        </tr>";

                // Iterando sobre os resultados e preenchendo a tabela
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    echo "<tr>
                            <td>".$row['ID']."</td>
                            <td>".$row['Nome']."</td>
                            <td>".$row['Idade']."</td>
                            <td>".$row['Email']."</td>
                            <td>".$row['Curso']."</td>
                            <td>
                                <form class='deleteForm' data-id='".$row['ID']."' onsubmit='return confirm(\"Tem certeza que deseja excluir este aluno?\");'>
                                    <button type='submit'>Excluir</button>
                                </form>
                            </td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "Nenhum dado encontrado.";
            }

        } catch (Exception $e) {
            echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
        }
        ?>
        </div>
        <script>
            document.querySelectorAll('.deleteForm').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // Evita o envio padrão do formulário
                    const id = this.getAttribute('data-id');

                    fetch('php/deletar.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `id=${id}`
                    })
                    .then(response => response.text())
                    .then(data => {
                        alert(data); // Exibe a resposta do servidor
                        location.reload(); // Atualiza a página para refletir a exclusão
                    })
                    .catch(error => console.error('Erro:', error));
                });
            });
        </script>
        <div id="search">
            <h1>Procure um aluno</h1>
            <form action="" method="get" id="searchform">
                <label for="ID">ID</label>
                <input type="number" name="ID">
                <label for="Nome">Nome</label>
                <input type="text" name="Nome">
                <label for="Curso">Curso</label>
                <input type="text" name="Curso">
                <button type="submit">Buscar</button>
            </form>
        </div>
    </section>
</body>
</html>
