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

        <title>Professores</title>
    </head>

    <body>
        <?php
        session_start();
        include "./classes/Professor.php";

        /*-- Processa as informações recebidas --*/
        if (isset($_GET['acao'])) {
            if ($_GET['acao'] == "salvar") {
                if ($_POST['enviar-professor']) {
                    $professor = new Professor();

                    $professor->setProfessor(
                        $_POST['codigo_professor'],
                        $_POST['nome-professor']
                    );

                    if ($professor->salvar()) {
                        $msg['msg'] = "Registro Salvo com sucesso!";
                        $msg['class'] = "success";
                    } else {
                        $msg['msg'] = "Falha ao salvar Registro!";
                        $msg['class'] = "success";
                    }
                    $_SESSION['msgs'][] = $msg;
                    unset($professor);
                }
            } else if ($_GET['acao'] == "excluir") {
                if (isset($_GET['codigo'])) {
                    if (Professor::excluir($_GET['codigo'])) {
                        $msg['msg'] = "Registro excluido com sucesso!";
                        $msg['class'] = "success";
                    } else {
                        $msg['msg'] = "Falha ao excluir Registro!";
                        $msg['class'] = "danger";
                    }
                    $_SESSION['msgs'][] = $msg;
                }
                header("location: professores.php");
            } else if ($_GET['acao'] == "editar") {
                if (isset($_GET['codigo'])) {
                    $professor = Professor::getProfessor($_GET['codigo']);
                }
            }
        }

        if (!isset($professor)) {
            $professor = new Professor();
            $professor->setProfessor(null, null);
        }

        /*-- Exibe as mensagens --*/
        if (isset($_SESSION['msgs'])) {

            foreach ($_SESSION['msgs'] as $msg)
                echo "<div class=' all-msgs alert alert-{$msg['class']}'>{$msg['msg']}</div>";

            echo "<script defer> 
            setTimeout(
            function(){
                document.querySelector('.all-msgs').style='display:none; ';
            }
            ,
            3000
            );
            </script>";

            unset($_SESSION['msgs']);
            }
            ?>

            <!-- Exibe o formulário -->
            <div class="container-fluid">
                <h2>Cadastrar Professores</<h2>
                <form name="form-professor" method="POST" action="?acao=salvar">
                    <input type="hidden" name="codigo_professor" value="<?php echo $professor->getCodigo() ?>" />
                        <div class="input-group mb-2">
                            <span class="input-group-text">Nome do Professor:</span>
                            <input type="text" class="form-control" id="nome-professor" name="nome-professor" value="<?php echo $professor->getNome() ?>">
                        </div>
                    <input type="submit" class="btn btn-primary" name="enviar-professor" value="Enviar" />
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
                                <th class='column2'>Nome do Professor</th>
                                <th class='column4'></th>
                                <th class='column5'></th>
                                <th class='column6'></th>
                            </tr>
                            </thead>
                            <tbody>
        <!-- Chama a função listar da classe Professor.php -->
        <?php 
            $professores = Professor::listar();
            foreach($professores as $professor){
                echo "
                    <tr>
                        <td class='column1'>{$professor->getCodigo()}</td>
                        <td class='column2'>{$professor->getNome()}</td>
                        <td class='column4'></td>
                        <td class='column5'>
                            <span class='badge rounded-pill bg-primary'>
                                <a href='?acao=editar&codigo={$professor->getCodigo()}' style='color:#fff'><i class='bi bi-pencil-square'></i></a>
                            </span>
                            <span class='badge rounded-pill bg-danger'>
                                <a href='?acao=excluir&codigo={$professor->getCodigo()}'style='color:#fff'><i class='bi bi-trash'></i></a>
                            </span>
                        </td>
                        <td class='column6'>
                    </tr>";    
            }
            ?>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

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