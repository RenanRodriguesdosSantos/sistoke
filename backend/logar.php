<?php session_start();
//inclusão das credenciais de acesso ao banco de dados
include_once("conexao.php");

//armazenamento das variáveis POST
$login = $_POST['user'];
$senha = $_POST['password'];

//texto da consulta ao banco de dados
$consulta = "SELECT * FROM users WHERE email LIKE '$login' AND password LIKE md5('$senha') ";
//efetivação da consulta ao banco de dados
$resultado = mysqli_query($conexao, $consulta);

// Retorna a quantidade de linhas da consulta
  $quantidade=mysqli_num_rows($resultado);
//vamos testar se o retorno foi de apenas UMA linha , ou seja, login E senha corretos.
  if($quantidade == 1) //login E senha corretos
 {
// 	#echo "Login ou senha corretos";
// 	//armazenar em um vetor o retorno do BD
 	$linha = mysqli_fetch_assoc($resultado);
// 	//criar a sessão
 	$_SESSION['user'] = $linha['nome'];
 	$_SESSION['idUser'] = $linha['id'];
 	
	//redirecionar para a página inicial
	header('Location:../frontend/home.php');
	}else{ //login ou senha incorretos
	//redireciona para o formulário de login
	header('Location:../index.php?erro=senha');		

  }