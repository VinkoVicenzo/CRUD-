<html>
    <head>
        <!-- Tags Metas Padrão -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Language" content="pt-br">
        <meta author="Vinko Vicenzo">

        <!-- Bootstrap CSS -->
        <link rel="icon" type="image/png" href="https://colorlib.com/etc/tb/Table_Responsive_v1/images/icons/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="https://colorlib.com/etc/tb/Table_Responsive_v1/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://colorlib.com/etc/tb/Table_Responsive_v1/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="https://colorlib.com/etc/tb/Table_Responsive_v1/vendor/animate/animate.css">
        <link rel="stylesheet" type="text/css" href="https://colorlib.com/etc/tb/Table_Responsive_v1/vendor/select2/select2.min.css">
        <link rel="stylesheet" type="text/css" href="https://colorlib.com/etc/tb/Table_Responsive_v1/vendor/perfect-scrollbar/perfect-scrollbar.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://colorlib.com/etc/tb/Table_Responsive_v1/css/util.css">
        <link rel="stylesheet" type="text/css" href="https://colorlib.com/etc/tb/Table_Responsive_v1/css/main.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="./css/style.css">

        <title>Alunos</title>
    </head>

    <body>
        <?php
        session_start();
        include "./classes/Turma.php";
        include "./classes/Aluno.php";

        /*-- Processa as informações recebidas --*/
        if (isset($_GET['acao'])) {
            if ($_GET['acao'] == "salvar") {
                if ($_POST['enviar-aluno']) {
                    $turma = new Turma();
                    $turma->setTurma($_POST['codigo-turma-aluno'], null, null, null);
                    $aluno = new Aluno();

                    $aluno->setAluno(
                        $_POST['codigo_aluno'],
                        $_POST['nome-aluno'],
                        $_POST['matricula-aluno'],
                        $turma
                    );

                    if ($aluno->salvar()) {
                        $msg['msg'] = "Registro Salvo com sucesso!";
                        $msg['class'] = "success";
                    } else {
                        $msg['msg'] = "Falha ao salvar Registro!";
                        $msg['class'] = "success";
                    }
                    $_SESSION['msgs'][] = $msg;
                    unset($aluno);
                }
            } else if ($_GET['acao'] == "excluir") {
                if (isset($_GET['codigo'])) {
                    if (Aluno::excluir($_GET['codigo'])) {
                        $msg['msg'] = "Registro excluido com sucesso!";
                        $msg['class'] = "success";
                    } else {
                        $msg['msg'] = "Falha ao excluir Registro!";
                        $msg['class'] = "danger";
                    }
                    $_SESSION['msgs'][] = $msg;
                }
                header("location: alunos.php");
            } else if ($_GET['acao'] == "editar") {
                if (isset($_GET['codigo'])) {
                    $aluno = Aluno::getAluno($_GET['codigo']);
                }
            }
        }

        if (!isset($aluno)) {
            $aluno = new Aluno();
            $aluno->setAluno(null, null, null, new Turma());
        }

        /*-- Exibe as Mensagens --*/
        if (isset($_SESSION['msgs'])) {

            foreach ($_SESSION['msgs'] as $msg)
                echo "<div class=' all-msgs alert alert-{$msg['class']}'>{$msg['msg']}</div>";

            echo
            "<script defer> 
            setTimeout(
            function() {
                document.querySelector('.all-msgs').style='display:none';
            }        
            ,
            3000);
            </script>";

            unset($_SESSION['msgs']);
            }

            ?>
            <!-- Exibe o formulário -->
            <div class="container-fluid">
                <h2> Cadastro de Alunos</h2>
                    <form name="form-aluno" method="POST" action="?acao=salvar">
                        <input type="hidden" name="codigo_aluno" value="<?php echo $aluno->getCodigo() ?>" />
                            <div class="input-group mb-2">
                                <span class="input-group-text">Nome do Aluno:</span>
                                <input type="text" class="form-control" id="nome-aluno" name="nome-aluno" value="<?php echo $aluno->getNome() ?>">
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Matrícula:</span>
                                <input type="text" class="form-control" id="matricula-aluno" name="matricula-aluno" value="<?php echo $aluno->getMatricula() ?>">
                            </div>

                            <div class="input-group mb-2 mb-2">
                                <label class="input-group-text" for="inputGroupTurma">Turma</label>
                                <select class="form-select" name="codigo-turma-aluno">
                                    <option value="<?php echo $aluno->getTurma()->getCodigo()  ?>"><?php echo $aluno->getTurma()->getNome() ?></option>
                                    <?php
                                    $turma = new Turma();
                                    $turmas = Turma::listar();

                                    if ($turmas) {
                                        foreach ($turmas as $item) {
                                            echo "<option value='{$item->getCodigo()}'>{$item->getNome()}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                                <input type="submit" class="btn btn-primary" name="enviar-aluno" value="Enviar" />

                    </form>
            </div>
            <!-- Parte superior da tabela -->
            <div class="limiter">
                <div class="container-table100">
                    <div class="wrap-table100">
                        <div class="table100">
                            <table>
                            <thead>
                                <tr class='table100-head'>
                                <th class='column1'>#</th>
                                <th class='column2'>Aluno</th>
                                <th class='column4'>Matricula</th>
                                <th class='column5'>Turma</th>
                                <th class='column6'>Professor</th>
                                <th class='column7'></th>
                            </tr>
                            </thead>
                            <tbody>
        <!-- Chama a função listar da classe Professor.php -->
        <?php 

        $alunos = Aluno::listar();
        foreach ($alunos as $item){
            echo "
                <tr>
                    <td class='column1'>{$item->getCodigo()}</td>
                    <td class='column2'>{$item->getNome()}</td>
                    <td class='column4'>{$item->getMatricula()}</td>
                    <td class='column5'>{$item->getTurma()->getNome()}</td>
                    <td class='column6'>{$item->getTurma()->getProfessor()->getNome()}</td>
                    <td class='column7'>
                        <span class='badge rounded-pill bg-primary'>
                            <a href='?acao=editar&codigo={$item->getCodigo()}' style='color:#fff'><i class='bi bi-pencil-square'></i></a>
                        </span>
                        <span class='badge rounded-pill bg-danger'>
                            <a href='?acao=excluir&codigo={$item->getCodigo()}'style='color:#fff'><i class='bi bi-trash'></i></a>
                        </span>
                    </td>
                </tr>";
            }
            ?>
                    
        <!-- Links dos arquivos do JavaScript -->
        <script src="https://colorlib.com/etc/tb/Table_Responsive_v1/vendor/jquery/jquery-3.2.1.min.js"></script>
        <script src="colorlib.com/etc/tb/Table_Responsive_v1/vendor/bootstrap/js/popper.js"></script>
        <script src="https://colorlib.com/etc/tb/Table_Responsive_v1/vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="https://colorlib.com/etc/tb/Table_Responsive_v1/vendor/select2/select2.min.js"></script>
        <script src="https://colorlib.com/etc/tb/Table_Responsive_v1/js/main.js"></script>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-23581568-13');
            </script>
        <script defer src="https://static.cloudflareinsights.com/beacon.min.js" data-cf-beacon='{"rayId":"68f2240e49ad4d3c","token":"cd0b4b3a733644fc843ef0b185f98241","version":"2021.8.1","si":10}'></script>
    </body>
</html>    