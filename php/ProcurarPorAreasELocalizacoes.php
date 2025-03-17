<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For All - Resultados</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" />
    <link rel="stylesheet" href="../css/globals.css" />
    <style>
        body {
            font-family: 'Inria Serif', serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        main {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }

        .job-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .job-item {
            width: 100%;
            max-width: 350px;
            background-color: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s;
        }

        .job-item:hover {
            transform: scale(1.05);
        }

        .job-item h3 {
            font-size: 1.2em;
            color: #333;
            margin-bottom: 10px;
        }

        .job-item p {
            color: #777;
            margin-bottom: 10px;
        }

        .no-results {
            text-align: center;
            font-size: 1.2em;
            color: #777;
        }

        .button-white {
            background-color: #fff;
            border: 2px solid #333;
            border-radius: 5px;
            padding: 8px 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button-white.selected {
            background-color: #333;
            color: #fff;
        }

        .button-white:hover {
            background-color: #eee;
        }
    </style>
</head>
<body>

<main>
    <h1>Resultados da Procura</h1>

    <?php
    include 'db.php';  // Arquivo de conexão com a base de dados

    $areas = isset($_POST['areas']) ? explode(',', $_POST['areas']) : [];
    $localizacoes = isset($_POST['localizacoes']) ? explode(',', $_POST['localizacoes']) : [];

    // Se houver áreas, buscamos os IDs correspondentes
    $areaIds = [];
    if (!empty($areas)) {
        $areaNames = implode("','", $areas); // Monta uma string com os nomes das áreas
        $query = "SELECT idarea FROM areas WHERE nome IN ('$areaNames')";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $areaIds[] = $row['idarea']; // Armazena os IDs das áreas
        }
    }

    // Se houver localizações, buscamos os IDs correspondentes
    $locationIds = [];
    if (!empty($localizacoes)) {
        $locationNames = implode("','", $localizacoes); // Monta uma string com os nomes das localizações
        $query = "SELECT idlocalizacao FROM localizacoes WHERE nome IN ('$locationNames')";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $locationIds[] = $row['idlocalizacao']; // Armazena os IDs das localizações
        }
    }

    // Agora podemos fazer a consulta usando os IDs
    $query = "SELECT * FROM empregos WHERE 1=1";

    if (!empty($areaIds)) {
        $areaIdsString = implode(",", $areaIds);
        $query .= " AND areas_idarea IN ($areaIdsString)";
    }

    if (!empty($locationIds)) {
        $locationIdsString = implode(",", $locationIds);
        $query .= " AND localizacoes_idlocalizacao IN ($locationIdsString)";
    }

    $result = mysqli_query($conn, $query);

    // Exibe os resultados em formato de lista de empregos
    if (mysqli_num_rows($result) > 0) {
        echo "<div class='job-list'>";
        while ($row = mysqli_fetch_assoc($result)) {
            // Buscando nome da área
            $areaQuery = "SELECT nome FROM areas WHERE idarea = " . $row['areas_idarea'];
            $areaResult = mysqli_query($conn, $areaQuery);
            $area = mysqli_fetch_assoc($areaResult)['nome'];

            // Buscando nome da localização
            $locationQuery = "SELECT nome FROM localizacoes WHERE idlocalizacao = " . $row['localizacoes_idlocalizacao'];
            $locationResult = mysqli_query($conn, $locationQuery);
            $location = mysqli_fetch_assoc($locationResult)['nome'];

            // Criando link para a página de detalhes
            $jobUrl = "Emprego.php?id=" . $row['idemprego'];

            echo "<div class='job-item' onclick='window.location=\"$jobUrl\"'>";
            echo "<h3>" . $row['titulo'] . "</h3>";
            echo "<p><strong>Área:</strong> " . $area . "</p>";
            echo "<p><strong>Localização:</strong> " . $location . "</p>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p class='no-results'>Nenhum emprego encontrado com os critérios selecionados.</p>";
    }

    mysqli_close($conn); // Fechar a conexão
    ?>
</main>

</body>
</html>
