<?php
require_once "../Model/ModelEmpresa.class.php";
require_once "../Model/ModelMensagens.class.php";
require_once "../Model/ModelEntrevistas.class.php";
require_once "../Model/ModelRespostas.class.php";

  date_default_timezone_set('America/Sao_Paulo');
  $dataAtual          = date("Y-m-d");
  $horaAtual          = date("H:i:s");

  $idEmpresa          = isset($_POST['idEmpresa'])?$_POST['idEmpresa']:null;
  $idEntrevista       = isset($_POST['idEntrevista'])?$_POST['idEntrevista']:null;
  $codUsuarioAluno    = isset($_POST['codUsuarioAluno'])?$_POST['codUsuarioAluno']:null;
  $motivo             = isset($_POST['motivo'])?$_POST['motivo']:null;
  $mensagem           = isset($_POST['mensagem'])?$_POST['mensagem']:null;

  $Empresa            = new ModelEmpresa();
  $ResultEmpresa      = mysqli_fetch_object($Empresa -> ReadEmpresa("where idEmpresa = $idEmpresa"));
  $nomeEmpresa        = $ResultEmpresa -> nome;
  $Entrevista         = new ModelEntrevistas();
  $Notificacao        = new Mensagens();
  $Resposta           = new Respostas();


  // $ResultEntrevista   = mysqli_fetch_object($Entrevista -> ReadEntrevista("where idEntrevista =  $idEntrevista"));
  // echo $idEntrevista."retorno";
  if ($Entrevista -> UpdateEntrevista("ativo",'',"where idEntrevista = $idEntrevista") && $Entrevista ->                 UpdateEntrevista("status",'Cancelado pela empresa',"where idEntrevista = $idEntrevista")) {
      echo "Fez o update na tabela entrevista e update no status na tabela entrevista";
      $data = array(
        "titulo"         => $motivo,
        "de"             => $nomeEmpresa,
        "data"           => $dataAtual,
        "hora"           => $horaAtual,
        "mensagem"       => $mensagem,
        "codUsuario"     => $codUsuarioAluno,
        "codEntrevista"  => $idEntrevista
      );
      if ($Notificacao -> CreateMensagens($data)) {
            echo "Criou notificação para o aluno";

      }
  }else{
    echo "Erro ao tentar fazer update na tabela entrevista";
  }

 ?>
