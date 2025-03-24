<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar-se - Candidato</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/globals.css">
    <link rel="stylesheet" href="../css/ListarDados.css">
    <style>
        /* Estilos para o formulário de registro */
        body {
            font-family: 'Inria Serif', serif;
            background-color: #FFFFFF; /* Fundo branco */
            margin: 0;
            padding: 0;
            color: #22202A; /* Cor principal do texto */
        }

        main {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #FFFFFF; /* Fundo branco para o conteúdo */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #22202A; /* Cor do título */
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #7E7D85; /* Cor da borda */
            border-radius: 5px;
        }

        button {
            background-color: #22202A; /* Cor do botão */
            color: #E5E5EC; /* Cor do texto do botão */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #7E7D85; /* Cor do botão ao passar o mouse */
        }
    </style>
</head>
<body>

<header>
    <div class="main-container">
        <div class="slice">
            <div class="rectangle">
                <div class="rectangle-1">
                    <div class="rh-logo">
                        <img src="../images/logo.png" alt="Logo">
                    </div>
                    <span class="for-all">For all</span>
                    <span class="gestao-recursos-humanos">Gestão de Recursos Humanos</span>

                    <div class="auth-buttons">
                        <a href="Login.php"><button class="login-register">Login</button></a>
                        <a href="Registo.php"><button class="login-register">Registar-se</button></a>
                    </div>
                </div>
            </div>

            <div class="rectangle-2">
                <div class="menu-item">
                    <a href="PaginaPrincipal.php">
                        <img src="../images/circle.png" alt="Circle Icon">
                        Página Principal
                    </a>
                </div>
                <div class="menu-item">
                    <a href="SobreNos.php">
                        <img src="../images/circle.png" alt="Circle Icon">
                        Sobre Nós
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<main>
    <h2>Registrar-se como Candidato</h2>
    <form action="../php/RegistoCandidato.php" method="POST" enctype="multipart/form-data">
        <label for="nome">Nome Completo:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>

        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" required>

        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>

        <label for="data_nascimento">Data de Nascimento:</label>
        <input type="date" id="data_nascimento" name="data_nascimento" required>

        <label for="anos_experiencia">Anos de Experiência:</label>
        <input type="number" id="anos_experiencia" name="anos_experiencia" min="0">

        <label for="habilitacoes_academicas">Habilitações Acadêmicas:</label>
        <textarea id="habilitacoes_academicas" name="habilitacoes_academicas" required></textarea>

        <label for="area">Selecione a Área:</label>
        <select name="area_id" id="area" required>
            <?php
            // Conectar ao base de dados para buscar áreas
            include '../php/db.php'; // Conexão com o base de dados
            $area_query = "SELECT idarea, nome FROM areas";
            $area_result = mysqli_query($conn, $area_query);
            
            while ($area = mysqli_fetch_assoc($area_result)) {
                echo '<option value="' . $area['idarea'] . '">' . htmlspecialchars($area['nome']) . '</option>';
            }
            ?>
        </select>
        <label for="localizacao">Selecione Localização:</label>
        <select name="area_id" id="area" required>
            <?php
            // Conectar ao base de dados para buscar áreas
            include '../php/db.php'; // Conexão com o base de dados
            $area_query = "SELECT idlocalizacao, nome FROM localizacoes";
            $area_result = mysqli_query($conn, $area_query);
            
            while ($area = mysqli_fetch_assoc($area_result)) {
                echo '<option value="' . $area['idlocalizacao'] . '">' . htmlspecialchars($area['nome']) . '</option>';
            }
            ?>
        </select>

        <label for="cv">Currículo (PDF):</label>
        <input type="file" id="cv" name="cv" accept=".pdf" required>

        <button type="submit">Registrar-se</button>
    </form>
</main>

<footer>
    <p>&copy; 2025 For All. Todos os direitos reservados.</p>
</footer>

</body>
</html>