<?php
$localhost = "localhost";
$user = "root";
$password = "";
$banco = "Ferrovia";

$conexao = mysqli_connect($localhost, $user, $password, $banco);

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

$conexao->set_charset("utf8");
?>