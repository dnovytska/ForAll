<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For All</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/globals.css" />
    <link rel="stylesheet" href="../css/Login.css" />
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
                            <button class="login-register">Login</button>
                            <button class="login-register">Registar-se</button>
                        </div>
                    </div>
                </div>
                <div class="rectangle-2">
                    <div class="menu-item">
                        <a href="PaginaPrincipal.html">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Página Principal
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="SobreNos.html">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Sobre Nós
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="login-container">
            <h2>Login</h2>
            <form action="../php/Login.php" method="POST">
                <div>
                    <label for="role">Escolha seu tipo de utilizador:</label>
                    <div class="role-buttons">
                        <button type="button" id="role-candidato" onclick="selectRole('candidato')">Candidato</button>
                        <button type="button" id="role-empregador" onclick="selectRole('empregador')">Empregador</button>
                        <button type="button" id="role-admin" onclick="selectRole('admin')">Admin</button>
                    </div>
                    <input type="hidden" id="role" name="role" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div>
                    <button type="submit">Entrar</button>
                </div>
            </form>
            <p>Não tem uma conta? <a href="registro_candidato.html">Registre-se aqui</a></p>
        </section>
    </main>

    <script>
        function selectRole(role) {
            // Remover a classe 'selected' de todos os botões
            const buttons = document.querySelectorAll('.role-buttons button');
            buttons.forEach(button => button.classList.remove('selected'));
            
            // Adicionar a classe 'selected' ao botão clicado
            const selectedButton = document.getElementById('role-' + role);
            selectedButton.classList.add('selected');
            
            // Definir o valor do campo 'role' com o valor do botão selecionado
            document.getElementById('role').value = role;
        }
    </script>
</body>
</html>
