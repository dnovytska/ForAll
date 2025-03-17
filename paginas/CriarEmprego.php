<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For All</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/globals.css" />
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
                        <a href="VerEmpregos.php">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Meus Empregos
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="notificacoes.html">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Notificações
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="SobreNos.html">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Sobre Nós
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="CriarEmprego.php">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Criar Novo Emprego
                        </a>
                    </div>
                    <span class="username">Username</span>
                </div>
            </div>
        </div>
    </header>
    
    <main>
        <div>
            <h1>Criar Novo Emprego</h1>
        </div>
        
        <div class="flex-row-f">
            <div class="ellipse"></div>
            <div class="design-unnamed"></div>
            
            <form action="../php/GuardarEmprego.php" method="post">

                <label for="titulo">Título de Emprego</label>
                <input type="text" id="titulo" name="titulo" class="nome-input" placeholder="Digite o título do emprego" required />

                <label for="responsabilidades">Responsabilidades</label>
                <input type="text" id="responsabilidades" name="responsabilidades" class="responsabilidades-input" placeholder="Digite as responsabilidades" required />
                
                <label for="competencias">Competências</label>
                <input type="text" id="competencias" name="competencias" class="competencias-input" placeholder="Digite as competências" required />
                
                <label for="beneficios">Benefícios</label>
                <input type="text" id="beneficios" name="beneficios" class="beneficios-input" placeholder="Digite os benefícios" required />
                
                <label for="areas_idarea">Área</label>
                <select id="areas_idarea" name="areas_idarea" class="area-input" required>
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'psiforall'); 

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $areasSql = "SELECT * FROM areas";
                    $result = $conn->query($areasSql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['idarea'] . "'>" . $row['nome'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Nenhuma área disponível</option>";
                    }

                    $conn->close();
                    ?>
                </select>
                
                <label for="localizacoes_idlocalizacao">Localização</label>
                <select id="localizacoes_idlocalizacao" name="localizacoes_idlocalizacao" class="localizacao-input" required>
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'psiforall'); 

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $localizacoesSql = "SELECT * FROM localizacoes";
                    $result = $conn->query($localizacoesSql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['idlocalizacao'] . "'>" . $row['nome'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Nenhuma localização disponível</option>";
                    }

                    $conn->close();
                    ?>
                </select>
                
                <label for="ordenado">Ordenado</label>
                <input type="number" id="ordenado" name="ordenado" class="ordenado-input" placeholder="Digite o ordenado" required />

                <label for="quantidade">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" class="quantidade-input" placeholder="Digite o quantidade" required />
                
                <label for="nome-empresa">Nome da Empresa</label>
                <input type="text" id="nome-empresa" name="nome-empresa" class="nome-empresa-input" placeholder="Digite o nome da empresa" required />

                <button type="submit" class="button-black">Salvar</button>
                <button type="button" class="button-black" onclick="window.location.href='PaginaPrincipal.html'">Cancelar</button>
            </form>
        </div>

        
        <footer>
            <div class="rectangle-f"></div>
        </footer>
    </main>
</body>
</html>
