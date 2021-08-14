<?php
session_start();
if(!isset($_SESSION['idUser'])){
    unset($_SESSION['idUser']);
    unset($_SESSION['user']);
    session_unset();
    header("location: ../index.php");
}

require_once("../backend/conexao.php");

$descricao = $_POST["descricao"];

if($descricao != ""){
    if($_POST["id"] != ""){
        $id = $_POST["id"];
        $consulta = "UPDATE produto SET descricao = '$descricao' WHERE id = '$id'";
        mysqli_query($conexao, $consulta);
    }
    else{
        $consulta = "INSERT INTO produto(descricao) VALUE ('$descricao')";
        mysqli_query($conexao, $consulta);
    }
}

if($_GET["listaProdutos"]){
    header("Location: ../frontend/produtos.php");
}
else{
    header("Location: ../frontend/home.php");
}