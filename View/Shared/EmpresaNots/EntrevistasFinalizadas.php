<?php
session_start();

require_once "../../../Model/ModelEntrevistas.class.php";
require_once "../../../Model/ModelEmpresa.class.php";
require_once "../../../Model/ModelAluno.class.php";
require_once "../../../Model/ModelVaga.class.php";
require_once "../../../Model/ModelMensagens.class.php";

$Entrevista      = new ModelEntrevistas();
$Empresa         = new ModelEmpresa();
$Aluno           = new ModelAluno();
$Vaga            = new ModelVaga();
$Notificacao     = new Mensagens();

$idUsuario       = $_SESSION['id'];

$ConsultEmpresa  = mysqli_fetch_object($Empresa -> ReadEmpresa("where codUsuario = $idUsuario"));
$idEmpresa       = $ConsultEmpresa->idEmpresa;

if (mysqli_num_rows($Entrevista -> ReadEntrevista("where codEmpresa = $idEmpresa"))){

    $ConsultaEntrevistaFinalizada  = $Entrevista -> ReadEntrevista("where codEmpresa = $idEmpresa and ativo = '' order by idEntrevista desc ");
}
else{
  $ConsultaNum = 0;
  $ConsultaEntrevistaFinalizada = false;
}

$totalVaga        = mysqli_num_rows($Vaga -> ReadVaga("where codEmpresa = $idEmpresa"));
?>
<head>

  <script type="text/javascript" src="js/HomeEmpresa.js"></script>
</head>


<?php
if ($ConsultaEntrevistaFinalizada) {
  echo "<h1 class='center-align flow-text'>Entrevistas já finalizadas</h1><ul class='collection'>";
  $pagina = (isset( $_GET['pagina']) ) ? $_GET['pagina'] : 1;
  $registros = 5;



  $inicio = ($registros*$pagina)-$registros;

  $ConsultaNot = $Notificacao -> ReadMensagens("where codUsuario = $idUsuario order by idMensagem desc limit $inicio, $registros") ;
  # code...
  $i = 1;
while ($ResultFinalizada = mysqli_fetch_object($ConsultaEntrevistaFinalizada)) {
  $idAluno        = $ResultFinalizada -> codAluno;
  $idEntrevista   = $ResultFinalizada ->idEntrevista;

  $ResultMensagem = mysqli_fetch_object($Notificacao->ReadMensagens("where codEntrevista = $idEntrevista"));
  $ResultAluno    = mysqli_fetch_object($Aluno -> ReadAluno("where idAluno = $idAluno"));

  $linha          = "linha".$i;
  ?>
  <li class="collection-item avatar hoverable" id="<?= $linha ?>">
    <div class="col s12 m1">
      <img src="Images/Upload/<?= $ResultAluno -> foto ?>" alt="" class="circle" />
    </div>
    <div class="col s8 m8 pull-m1 ">
      <span class="title">Aluno: <?= $ResultAluno -> nome ?></span>
      <br>
      <span>Vaga: <?= $ResultFinalizada -> vaga ?></span><br>
      <span class=""> Status: <?= $ResultFinalizada -> status ?></span>

    </div>
    <div class="col s4 m2 push-m1">
      <button type="button" name="button" class="btn-flat btn-large ">
        <i class="material-icons ApagarEntrevistasFinalizadas red-text " data-linha="<?= $linha ?>" data-identrevista="<?= $idEntrevista?>" >delete</i>
      </button>

    </div>

  </li><br>
  <?php
  $i++;
  }
 echo "</ul>";
}else{
  echo "<h1 class='center-align flow-text'>Nenhuma entrevista finalizada</h1>";
}
?>
