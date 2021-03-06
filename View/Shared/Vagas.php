<?php
//    include_once("Controller/ManipulaVarSession.class.php");
    include_once("Model/DataBase.class.php");
    include_once("Controller/VerificaSeEstaLogado.class.php");

    $Verifica = new VerificaSeEstaLogado();
    $Verifica->EstaLogado();
    $DB = new DataBase();
    $pesquisa = isset($_GET['pesquisa'])?$_GET['pesquisa']:null;
    $filtro   = isset($_GET['filtro'])?$_GET['filtro']:null;

    $Result = $DB->SearchQuery("vaga","where ativo = 'S'");
    $idUsuario = $_SESSION['id'];
    if ($filtro && $pesquisa) {
      echo "<input type='hidden' name='filtroSemPesquisa' value='".$filtro."' />
      <input type='hidden' name='pesquisaSemPesquisa' value='".$pesquisa."' />";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Contrate um Aluno</title>
    <script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
    <script type="text/javascript" src="js/Vagas.js"></script>
</head>
<body>



    <?php
        if (mysqli_num_rows($Result) > 0) {
          ?>
          <div class="container">
            <div class="row">
              <form method="Controller/Pesquisa.php" class="" method="get" id="pesquisar">
              <div class="input-field col s12 m12">
                <label for="pesquisa">Pesquisa:</label>
                <input type="text" name="pesquisa" id="pesquisa" required>

              </div>
              <div class="input-field col s12 m8">
                  <input class="with-gap" name="filtro" type="radio" id="todos"  value="todos" />
                  <label for="todos">Vagas</label>
                  <input class="with-gap" name="filtro" type="radio" id="salario" value="salario" />
                  <label for="salario">Salário</label>
                  <!-- <input class="with-gap" name="filtro" type="radio" id="empresa" value="empresa"  />
                  <label for="empresa">Empresa</label> -->
              </div>
              <div class="input-field col s12 m4">
                <input type="submit" value="Pesquisar" class="btn btn-large blue">
              </div>
            </form>
            </div>

             <?php
             //verifica a página atual caso seja informada na URL, senão atribui como 1ª página
             $pagina = (isset( $_GET['pagina']) ) ? $_GET['pagina'] : $_GET['pagina']=1;

             //seleciona todos os itens da tabela
             $vagas = $DB->SearchQuery("vaga");
             //conta o total de itens
             $total = mysqli_num_rows($vagas);

             //seta a quantidade de itens por página, neste caso, 2 itens
             $registros = 6;

             //calcula o número de páginas arredondando o resultado para cima
             $numPaginas = ceil($total/$registros);

             //variavel para calcular o início da visualização com base na página atual
             $inicio = ($registros*$pagina)-$registros;

             //seleciona os itens por página
             $queryVagas   = $DB->SearchQuery("vaga v", "where ativo='S' order by idVaga desc limit $inicio,$registros");
             //$total1       = mysqli_num_rows($queryVagas);
            //  var_dump($aluno);
             foreach ($aluno as $valor) {
               # code...
               echo $valor."<br />";
             }
             //exibe os produtos selecionados
            //  while ($aluno = mysqli_fetch_object($alunos1)) {
            //     echo "=============######================<br/><br/>";
            //       echo $aluno->competencia." - ";
             //
            //     echo "<br/><br/><br/>";
            //  }

             //exibe a paginação
              ?>
              <div class="col s12 m12 l12" id="VagaSemPesquisa">
                <h1 class="flow-text center-align">Vagas no Contrate um Aluno</h1>

                <div class="row">

              <?php
                  while($Linha = mysqli_fetch_assoc($queryVagas) ) {
                      $CodEmpresa = $Linha['codEmpresa'];
                      $Result2 = $DB->SearchQuery("empresa", "where idEmpresa = $CodEmpresa");
                      $EmpresaAssoc = mysqli_fetch_assoc($Result2);
                      ?>
                      <div class='col m6'>
                          <div class='card small hoverable'>
                              <div class='card-image waves-effect waves-block waves-light'>

                                  <img class='activator' src='images/office.jpg'>

                              </div>
                              <div class='card-content'>
                                  <span class='card-title activator grey-text text-darken-4'>
                                      <?php $TamTitulo = strlen($Linha['titulo']);
                                              $TituloMenor = substr($Linha['titulo'], 0, 30)."...";
                                          if($TamTitulo > 29)
                                          {
                                              echo $TituloMenor;
                                          }else
                                          {
                                              echo $Linha['titulo'];
                                          }
                                      ?><i class='material-icons right'>add</i></span>
                                  <p class="blue-text"><?php echo $EmpresaAssoc['nome']; ?></p>
                              </div>
                              <div class='card-reveal'>
                                    <span class='card-title grey-text text-darken-4'>
                                        <?php $TamTitulo = strlen($Linha['titulo']);
                                              if($TamTitulo > 29)
                                              {
                                                  echo $TituloMenor;
                                              }else
                                              {
                                                  echo $Linha['titulo'];
                                              }
                                        ?><br>
                                        <br>
                                        <i class='material-icons right'>close</i></span>
                                  <p>Descrição: <?php
                                                  $TamDesc = strlen($Linha['descricao']);
                                                  if($TamDesc > 150)
                                                  {
                                                      echo substr($Linha['descricao'], 0, 49)."...";
                                                      echo "<a href='OnePage.php?link=Vaga&id=".$Linha["idVaga"]."'>Ver mais</a>";
                                                  }else
                                                  {
                                                      echo $Linha['descricao'];
                                                  }

                                              ?></p>
                                  <p>Carga horária: <?php echo number_format($Linha['cargaHoraria'], 1, ',', '.'); ?></p>
                                  <p>Salário: R$: <?php echo number_format($Linha['salario'], 2, ',', '.'); ?></p>

                                  <a class='btn blue' href='OnePage.php?link=Vaga<?php echo "&id=".$Linha["idVaga"]."&anterior=".$_SERVER['QUERY_STRING']; ?>'>Ver vaga</a>
                              </div>
                          </div>
                      </div>
                      <?php
                  }
                  $paginaMenosUm  = ($pagina - 1);
                  $paginaMaisUm   = ($pagina + 1);
                  ?>
                </div>
                <center>
                    <div class="row">

                          <ul class="pagination">
                              <li class="<?php if($_GET['pagina'] == 1) echo 'disabled' ?>">
                                  <a href="<?php if($_GET['pagina'] > 1) echo 'OnePage.php?link=Vagas&pagina='.$paginaMenosUm ?>">
                                      <i class="material-icons">chevron_left</i>
                                  </a>
                              </li>
                              <?php
                                  for($i = 1; $i < $numPaginas + 1; $i++) {
                                       //echo "<a href='OnePage.php?link=Vagas&pagina=$i'>".$i."</a> ";
                                       if($i == $_GET['pagina'])
                                          echo "<li class='active blue'><a href='OnePage.php?link=Vagas&pagina=$i'>$i</a></li>";
                                       else
                                          echo "<li class='waves-effect'><a href='OnePage.php?link=Vagas&pagina=$i'>$i</a></li>";
                                  }
                      ?>
                          <li class="waves-effect <?php if($_GET['pagina'] == $numPaginas) echo 'disabled' ?>">
                              <a href="<?php if($_GET['pagina'] < $numPaginas) echo 'OnePage.php?link=Vagas&pagina='.$paginaMaisUm ?>">
                                  <i class="material-icons">chevron_right</i>
                              </a>
                          </li>
                      </ul>
                  </div>
                </center>
            </div>
            <div id="VagasComPesquisa" class="col s12 m12 l12">

            </div>
          </div>




          <?php
        }else{
            ?>
          <div class="container">
            <div class="row">
              <div class="col s12 m12">

                <h1 class="center-align flow-text">Desculpe mas não temos vagas cadastradas no momento</h1>
              </div>
            </div>
          </div>

            <?php
        }
     ?>
</body>
</html>
