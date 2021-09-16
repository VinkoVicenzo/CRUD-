<?php
require_once "./classes/Database.php";

class Aluno {
    private $codigo;
    private $nome;
    private $matricula;
    private $turma;

    public function setAluno($codigo, $nome, $matricula, $turma) {
        $this->codigo = $codigo;
        $this->nome = $nome;
        $this->matricula = $matricula;
        $this->turma = $turma;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function getTurma() {
        return $this->turma;
    }

    public static function listar() {
        $db = Database::conexao();
        $alunos = null;
        $retorno = $db->query("SELECT * FROM aluno");

        while ($item = $retorno->fetch(PDO::FETCH_ASSOC)) {
            $turma = Turma::getTurma($item['turma_codigo']);
            $aluno = new Aluno();
            $aluno->setAluno($item['codigo'], $item['nome'], $item['matricula'], $turma);

            $alunos[] = $aluno;
        }

        return $alunos;
    }

    public static function excluir($codigo) {
        $db = Database::conexao();
        if ($db->query("DELETE FROM aluno WHERE codigo=$codigo")) {
            return true;
        }
        return false;
    }

    public function salvar() {
        try {
            $db = Database::conexao();
            if (empty($this->codigo)) {
                $stm = $db->prepare("INSERT INTO aluno (nome, matricula, turma_codigo) VALUES (:nome,:matricula,:turma)");
                $stm->execute(array(":nome" => $this->getNome(), ":matricula" => $this->getMatricula(), ":turma" => $this->getTurma()->getCodigo()));
            } else {
                $stm = $db->prepare("UPDATE aluno SET nome=:nome,matricula=:matricula,turma_codigo=:turma_codigo WHERE codigo=:codigo");
                $stm->execute(array(":nome" => $this->nome, ":matricula" => $this->matricula, ":turma_codigo" => $this->turma->getCodigo(), ":codigo" => $this->codigo));
            }
            #pegar o id do registro no banco de dados
            #setar o id do objeto
            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage() . "<br>";
            return false;
        }
        return true;
    }

    public static function getAluno($codigo) {
        $db = Database::conexao();
        $retorno = $db->query("SELECT * FROM aluno WHERE codigo=$codigo");
        if ($retorno) {
            $item = $retorno->fetch(PDO::FETCH_ASSOC);
            $turma = Turma::getTurma($item['turma_codigo']);
            $aluno = new Aluno();
            $aluno->setAluno($item['codigo'], $item['nome'], $item['matricula'], $turma);
            return $aluno;
        }
        return false;
    }
}
