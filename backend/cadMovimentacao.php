<?php
session_start();
if(!isset($_SESSION['idUser'])){
    unset($_SESSION['idUser']);
    unset($_SESSION['user']);
    session_unset();
    header("location: ../index.php");
}
//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once("../backend/conexao.php");

$produto = $_POST["produto"];
$preco = $_POST["preco"];
$data_entrada = $_POST["data_entrada"];
$data_saida = $_POST["data_saida"] != ""?"'".$_POST["data_saida"]."'":"null";
$id = $_POST["id"];

if($produto != "" && $preco != "" && $data_entrada != ""){

    if($id != ""){
        $id = $_POST["id"];
        $consulta = "UPDATE movimentacao SET id_produto = '$produto', preco = '$preco', data_entrada = '$data_entrada', data_saida = $data_saida WHERE id = '$id'";
        mysqli_query($conexao, $consulta);
    }
    else{
        $consulta = "INSERT INTO movimentacao(id_produto,preco,data_entrada,data_saida) VALUE ('$produto','$preco','$data_entrada',$data_saida)";
        mysqli_query($conexao, $consulta);
    }
}

header("Location: ../frontend/home.php");