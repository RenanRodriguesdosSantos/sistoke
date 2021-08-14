<?php
session_start();
if(!isset($_SESSION['idUser'])){
    unset($_SESSION['idUser']);
    unset($_SESSION['user']);
    session_unset();
    header("location: ../index.php");
}
$user = $_SESSION["user"];
$idUser = $_SESSION['idUser'];
require_once("../backend/conexao.php");

$consulta = "SELECT * FROM Produto";
$resultados = mysqli_query($conexao, $consulta);
$produtos = array();
while ($row = mysqli_fetch_assoc($resultados)) {
    $produtos[] = ["id" => $row["id"], "descricao" => $row["descricao"]];
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SisToke</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="confirmarSenha.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container shadow-lg p-3 mb-5 bg-body rounded comp-container">
        <div class="row p-md-3">
            <div class="col-md-2">
                <img class="img-fluid" src="./images/logo_fabricio.jpg" alt="LOGO">
            </div>
            <div class="col-md-9">
                <h2 class="text-center">Lista de Produtos</h2>
            </div>
            <div class="col-md-1">
                <div class="btn-group dropdown">
                    <button class="btn btn-success btn-lg text-uppercase rounded-circle pb-md-2 pt-md-2 ps-md-3 pe-md-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo str_split($user)[0];?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><p class="dropdown-item text-center text-uppercase"><?php echo $user;?></p></li>
                        <li><button class="btn bg-info text-center col-md-12 border dropdown-item" data-bs-toggle="modal" data-bs-target="#modalAlterarSenha">Alterar Senha</button></li>
                        <li><button class="btn bg-info text-center col-md-12 border dropdown-item" data-bs-toggle="modal" data-bs-target="#modalConfirmarSair">Sair</button></li>
                    </ul>
                </div>
                <div class="modal fade" id="modalConfirmarSair" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Sair</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Deseja Realmente Sair?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <a href="../backend/sair.php" class="btn btn-danger">Confirmar</a>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="../backend/alterarSenha.php" method="post" onsubmit="return confirmarSenhaForm()">
                    <div class="modal fade" id="modalAlterarSenha" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Alterar Senha</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label htmlFor="senhaAtual" class="col-sm-4 col-form-label"> Senha Atual: </label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" id="senhaAtual" name="senhaAtual" placeholder="Senha Atual"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label htmlFor="novaSenha" class="col-sm-4 col-form-label"> Nova Senha: </label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" id="novaSenha" name="novaSenha" placeholder="Nova Senha"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label htmlFor="confirmarSenha" class="col-sm-4 col-form-label"> Confirmar Senha: </label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" id="confirmarSenha" name="confirmarSenha" placeholder="Confirmar Senha"/>
                                        </div>
                                    </div>
                                    <div class="alert alert-danger d-none" role="alert" id="alertSenha">
                                        Senhas Diferentes!
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-danger">Confirmar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>    
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <button class="btn btn-success col-12 m-md-2" data-bs-toggle="modal" data-bs-target="#modalCadProduto">Cadastrar Produto</button>
                <a href="home.php" class="btn btn-primary col-12 m-md-2"> Voltar </a>
                <form action="../backend/cadProduto.php?listaProdutos=true" method="post">
                    <input type="hidden" name="id" id="id">   
                    <div class="modal fade" id="modalCadProduto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Cadastrar Produto</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" onClick="cancelarEdicao()" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label htmlFor="descricao" class="col-sm-2 col-form-label"> Descrição: </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" required id="descricao" name="descricao" placeholder="Descrição do Produto"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onClick="cancelarEdicao()" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Confirmar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-10">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped border-success">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Produto</th>
                                <th scope="col">Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produtos as $produto) {
                                $parametrosJS = $produto["id"].",'".$produto["descricao"]."'";
                            ?>
                                <tr>
                                    <th scope="col"><?php echo $produto["id"];?></th>
                                    <td><?php echo $produto["descricao"];?></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" onClick="editarProduto(<?php echo $parametrosJS;?>)" data-bs-target="#modalCadProduto"><img src="./images/edit.png" alt="Editar"></button>
                                    </td>
                                </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        var editarProduto = (id, produto) =>{
            document.querySelector("#id").value = id;
            document.querySelector("#descricao").value = produto;
        }

        var cancelarEdicao = () => {
            document.querySelector("#id").value = "";
            document.querySelector("#descricao").value = "";
        }
    </script>  
</body>
</html>