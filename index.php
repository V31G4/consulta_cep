<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Endereço por CEP</title>
</head>
<body>
    <h1>Consulta de Endereço por CEP</h1>
    <form method="POST" action="consultar_cep.php">
        <label for="cep">CEP:</label>
        <input type="text" id="cep" name="cep" required>
        <button type="submit">Consultar</button>
    </form>
    
    <h2>Registros de Endereço Consultados:</h2>
    <ul>
    <?php
    $conteudo = file_get_contents("registros.txt");
    $registros = explode("{", $conteudo);

    foreach ($registros as $registro) {
        $registro = "{" . $registro;

        if (strpos($registro, "cep") !== false) {
            $endereco = json_decode($registro, true);

            if ($endereco !== null && is_array($endereco)) {
                echo "<li>";
                echo "CEP: " . $endereco['cep'] . "<br>";
                echo "Logradouro: " . $endereco['logradouro'] . "<br>";
                echo "Bairro: " . $endereco['bairro'] . "<br>";
                echo "Cidade: " . $endereco['localidade'] . "<br>";
                echo "Estado: " . $endereco['uf'] . "<br>";
                echo "</li><br>";
            } else {
                echo "Erro ao decodificar o registro JSON: " . $registro . "<br>";
            }
        }
    }
    ?>
    </ul>
</body>
</html>