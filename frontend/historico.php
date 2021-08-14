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

$quantidadePorPagina = "5";
$paginaAtual = isset($_GET["pagina"])?intval($_GET["pagina"]) - 1:0;
$intervaloAtual = $paginaAtual * $quantidadePorPagina;
$consulta = "SELECT m.*, p.descricao as produto FROM movimentacao m INNER JOIN produto p ON (m.id_produto = p.id) LIMIT $intervaloAtual, $quantidadePorPagina";
$resultados = mysqli_query($conexao, $consulta);
$movimentacoes = array();
while ($row = mysqli_fetch_assoc($resultados)) {
    $movimentacoes[] = ["id" => $row["id"], "produto" => $row["produto"], "preco" => $row["preco"], "data_entrada" => $row["data_entrada"], "data_saida" => $row["data_saida"], "id_produto" => $row["id_produto"]];
}

$totalLinhas = mysqli_query($conexao,"select id from movimentacao")->num_rows;
$totalPaginas = ceil($totalLinhas / $quantidadePorPagina);

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
                <h2 class="text-center">Produtos em Estoque</h2>
            </div>
            <div class="col-md-1">
                <div class="btn-group">
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
                <form action="../backend/cadProduto.php" method="post">   
                    <div class="modal fade" id="modalCadProduto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Cadastrar Produto</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Confirmar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form> 
                <button class="btn btn-success col-12 m-md-2" data-bs-toggle="modal" data-bs-target="#modalCadMovimentacao">Entrada de Produto</button>
                <form action="../backend/CadMovimentacao.php" method="post">   
                    <input type="hidden" name="id" id="id">
                    <div class="modal fade" id="modalCadMovimentacao" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel"> Movimentação de Estoque</h5>
                                    <button type="button" onClick="cancelarEdicao()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-2">
                                        <label htmlFor="produto" class="col-sm-2 col-form-label"> Produto: </label>
                                        <div class="col-sm-10">
                                            <select class="form-control" required id="produto" name="produto">
                                                <?php foreach ($produtos as $produto) {?>
                                                    <option value="<?php echo $produto["id"];?>"><?php echo $produto["descricao"];?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label htmlFor="preco" class="col-sm-4 col-form-label"> Preço: </label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" required id="preco" name="preco" placeholder="Preço"/>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label htmlFor="data_entrada" class="col-sm-4 col-form-label"> Data de Entrada: </label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control" required id="data_entrada" name="data_entrada"/>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label htmlFor="data_saida" class="col-sm-4 col-form-label"> Data de Saída: </label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control" id="data_saida" name="data_saida"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onClick="cancelarEdicao()">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Confirmar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form> 
                <a href="./produtos.php" class="btn btn-success col-12 m-md-2">Produtos</a>
                <a href="./home.php" class="btn btn-primary col-12 m-md-2">Voltar</a>
            </div>
            <div class="col-md-10">
                <div class="table-responsive">
                    <table class="table table-bordered border-success table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col" colspan="3">Produto</th>
                                <th scope="col">Preço</th>
                                <th scope="col">Data Entrada</th>
                                <th scope="col">Data Saída</th>
                                <th scope="col">Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($movimentacoes as $value) {
                                $parametrosJS = $value["id"].",".$value["id_produto"].",".$value["preco"].",'".$value["data_entrada"]."'";
                                $dataEntrada = new DateTime($value["data_entrada"]);
                                $dataEntrada = $dataEntrada->format("d/m/Y");
                                $dataSaida = is_null($value["data_saida"])?"-":$value["data_saida"];
                                if($dataSaida != "-"){
                                    $dataSaida = new DateTime($dataSaida);
                                    $dataSaida = $dataSaida->format("d/m/Y");
                                }
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $value["id"];?></th>
                                    <td colspan="3"><?php echo $value["id_produto"]." - ".$value["produto"];?></td>
                                    <td>R$ <?php echo $value["preco"];?></td>
                                    <td><?php echo $dataEntrada;?></td>
                                    <td><?php echo $dataSaida;?></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" onClick="editarEstoque(<?php echo $parametrosJS;?>)" data-bs-target="#modalCadMovimentacao"><img src="./images/edit.png" alt="Editar"></button>
                                    </td>
                                </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo $paginaAtual == 0?"disabled":""; ?>"><a class="page-link" tabindex="-1" area-disabled="<?php echo $paginaAtual == 0?"false":"true"; ?>" href="?pagina=<?php echo $paginaAtual;?>">Anterior</a></li>
                                <?php for ($i=1; $i <= $totalPaginas; $i++) { ?>  
                                    <li class="page-item <?php echo $i == $paginaAtual+1?"active":""; ?>"><a class="page-link" href="?pagina=<?php echo $i?>"><?php echo $i?></a></li>
                                <?php }?>
                                <li class="page-item <?php echo $paginaAtual+1 == $totalPaginas?"disabled":""; ?>"><a class="page-link" tabindex="-1" area-disabled="<?php echo $paginaAtual+1 == $totalPaginas?"false":"true"; ?>" href="?pagina=<?php echo $paginaAtual+2;?>">Próxima</a></li>
                            </ul>
                        </nav
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var editarEstoque = (id,id_produto,preco,dataEntrada) =>{
            document.querySelector("#id").value = id;
            document.querySelector("#produto").value = id_produto;
            document.querySelector("#preco").value = preco;
            document.querySelector("#data_entrada").value = dataEntrada;
        }

        var cancelarEdicao = () =>{
            document.querySelector("#id").value = "";
            document.querySelector("#produto").value = "";
            document.querySelector("#preco").value = "";
            document.querySelector("#data_entrada").value = "";
            document.querySelector("#data_saida").value = "";
        }
    </script>  
</body>
</html>