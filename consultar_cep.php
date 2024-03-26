<?php

function consultarCEP($cep) {
    $url = "https://viacep.com.br/ws/" . urlencode($cep) . "/json/";
    $resultado = file_get_contents($url);
    return $resultado;
}

function armazenarEndereco($endereco) {
    $registro = json_encode($endereco, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_SLASHES);
    $registro = str_replace(array("\\n", "\\\""), array("\n", "\""), $registro);
    $registro = trim($registro, '"');
    //echo $registro;
    file_put_contents("registros.txt", $registro . PHP_EOL, FILE_APPEND);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cep = $_POST["cep"];
    $endereco = consultarCEP($cep);
    armazenarEndereco($endereco);
}

function getRegistros() {
    $conteudo = file_get_contents("registros.txt");
    $registros = explode("{", $conteudo);

    array_shift($registros);

    foreach ($registros as &$registro) {
        $registro = trim($registro);
        $registro = "{" . trim($registro);
    }

    $registros = array_map('rtrim', $registros, array('" '));

    return $registros;
}

$registros = getRegistros();

foreach ($registros as $registro) {
    $endereco = json_decode($registro, true);
    
    if ($endereco !== null && is_array($endereco)) {
        echo "CEP: " . $endereco['cep'] . "<br>";
        echo "Logradouro: " . $endereco['logradouro'] . "<br>";
        echo "Bairro: " . $endereco['bairro'] . "<br>";
        echo "Cidade: " . $endereco['localidade'] . "<br>";
        echo "Estado: " . $endereco['uf'] . "<br><br>";
    } else {
        echo "Erro ao decodificar o registro JSON: " . $registro . "<br>";
    }
}

?>