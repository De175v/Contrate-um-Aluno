<?php
    session_start();
    require_once "../Model/ModelEntrevistas.class.php";
    require_once "../Model/ModelBeneficiosExperiencia.class.php";
    require_once "../Model/ModelEmpresa.class.php";

    $empresa                = new ModelEmpresa();
    $entrevista             = new ModelEntrevistas();
    $beneficiosExperiencia  = new ModelBeneficiosExperiencia();

    $idUsuario    = $_SESSION['id'];
    $beneficios   = json_decode($_POST['beneficios'],true);

    $data         = isset($_POST['data'])       ? $_POST['data']:null;
    $hora         = isset($_POST['hora'])       ? $_POST['hora']:null;
    $local        = isset($_POST['local'])      ? $_POST['local']:null;
    $numero       = isset($_POST['numero'])     ? $_POST['numero']:null;
    $bairro       = isset($_POST['bairro'])     ? $_POST['bairro']:null;
    $complemento  = isset($_POST['complemento'])? $_POST['complemento']:null;
    $cidade       = isset($_POST['cidade'])     ? $_POST['cidade']:null;
    $estado       = isset($_POST['estado'])     ? $_POST['estado']:null;
    $vaga         = isset($_POST['vaga'])       ? $_POST['vaga']:null;
    $salario      = isset($_POST['salario'])    ? $_POST['salario']:null;
    $cargaHoraria = isset($_POST['cargaHoraria'])? $_POST['cargaHoraria']:null;
    $descricao    = isset($_POST['descricao'])  ? $_POST['descricao']:null;
    $idAluno      = $_POST['idAluno'];

    $empresaObject  = mysqli_fetch_object($empresa->ReadEmpresa("where codUsuario = $idUsuario"));
    var_dump($empresaObject);

    $idEmpresa  = $empresaObject->idEmpresa;
    $dados = array(
        "data"          => $data,
        "hora"          => $hora,
        "local"         => $local,
        "numero"        => $numero,
        "bairro"        => $bairro,
        "complemento"   => $complemento,
        "cidade"        => $cidade,
        "estado"        => $estado,
        "vaga"          => $vaga,
        "salario"       => $salario,
        "cargaHoraria"  => $cargaHoraria,
        "descricao"     => $descricao,
        "codAluno"      => $idAluno,
        "codEmpresa"    => $idEmpresa
    );
    $insert             = $entrevista->CreateEntrevista($dados);
    $selectObject       = mysqli_fetch_object($entrevista->ReadEntrevista("order by idEntrevista desc limit 1"));
    $ultimaExperiencia  = $selectObject->idEntrevista;

    for($i = 0; $i < count($beneficios); $i++){
        $dados22 = array(
            "beneficio"     => $beneficios[$i]['tag'],
            "codEntrevista" => $ultimaExperiencia
        );
        $insertBeneficiosExperiencia    = $beneficiosExperiencia->CreateBeneficiosExperiencia($dados22);
    }

    echo $ultimaExperiencia;
 ?>