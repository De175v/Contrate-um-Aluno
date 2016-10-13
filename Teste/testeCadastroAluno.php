<head>
    <meta charset="utf-8">
</head>
<?php

    session_start(); 
    include_once("../Model/DataBase.class.php");
    include_once("../Util.php");
    include_once("../Controller/ManipulaVarSession.class.php");

    $VarSessions = new ManipulaVarSession();
 
    $DB = new DataBase();

    if(isset($_FILES['foto'])){
//        $nomeOriginal   = $_FILES['foto']['name']; #Nome do arquivo original
//        $tipo           = $_FILES['foto']['type']; #O tipo do arquivo
//        $tamanho        = $_FILES['foto']['size']; #Tamanho em bytes
        $nomeTemporario = $_FILES['foto']['tmp_name']; #O nome temporário com o qual o arquivo
//        # enviado foi armazenado no servidor.
//        $codErro        = $_FILES['foto']['error']; #O código de erro associado ao upload do arquivo

        $diretorio = "../Images/Upload/";
        $extensao = strtolower(substr($_FILES['foto']['name'], -4));
        $novoNome = md5(time()).$extensao;
        
        $localFull = $diretorio.$novoNome;
        if(move_uploaded_file($nomeTemporario, $diretorio.$novoNome))
        {
            echo "deu certo";
        }#Move o arquivo temporário para pasta
        }else{
            echo "erro";
        }
    
    echo "<br>";
    
    $Telefones      = json_decode($_POST['Telefones'],true);
    $Experiencias   = json_decode($_POST['Experiencias'],true);
    $Formacoes      = json_decode($_POST['Formacoes'],true);
    $Qualificaoes   = json_decode($_POST['Qualificacoes'],true);

    for($i = 1; $i < count($Experiencias);$i++){
        $dados = array(
            "descricao" => $Experiencias[$i]['tempoExperiencia'],
            "dataInicio" => $Experiencias[$i]['cargo'],
            "dataSaida" => $Experiencias[$i]['texto'],
            "cargo" => 0,
            "codAluno" => 0);
        //$DB->InsertQuery("experiencias", $dados);
    }
    var_dump($dados);

    $tabela = "aluno";
    $dadosAluno  = array(
        "dataNascimento"            => (isset($_POST["nascimento"])) ? $_POST["nascimento"] : $MsgString,
        "formacao"                  => (isset($_POST["formacao"])) ? $_POST["formacao"] : $MsgString,
        "experiencias"              => (isset($_POST["experiencias"])) ? $_POST["experiencias"] : $MsgString,
        "informacoesAdicionais"     => (isset($_POST["info"])) ? $_POST["info"] : $MsgString,
        "foto"                      => $novoNome,
        "nome"                      => (isset($_POST["nome"])) ? $_POST["nome"] : $MsgString,
        "cpf"                       => (isset($_POST["cpf"])) ? $_POST["cpf"] : $MsgString,
        "objetivo"                  => (isset($_POST["objetivo"])) ? $_POST["objetivo"] : $MsgString,
        "qualificacoes"             => (isset($_POST["qualificacoes"])) ? $_POST["qualificacoes"] : $MsgString,
        "telefone"                  => (isset($_POST["telefone"])) ? $_POST["telefone"] : $MsgString,
        "endereco"                  => (isset($_POST["endereco"])) ? $_POST["endereco"] : $MsgString,
        "rg"                        => (isset($_POST["rg"])) ? $_POST["rg"] : $MsgString,
        "codCurso"                  => (isset($_POST["curso"])) ? $_POST["curso"] : $MsgNumber,
        "codUsuario"                => $_SESSION['id']
    );
?>