<?php require_once 'classConexao.php'; 

    class Funcionario {
        private $pdo;

        public function __construct(){
            $conexao = new Conexao();
            $this->pdo = $conexao->conectar();
        }

        public function insere ($cpf, $nome, $telefone, $endereco, $codCargo, $codDepartamento){
            $insere = $this->pdo->prepare("insert into funcionario ( cpf, nome, telefone, endereco, codCargo, codDepartamento) 
            values (:c,:n,:t,:e,:coc,:co)");
            // Bind param recebe algo como uma variável já o bind value para uma string em si
            $insere->bindValue(":c",$cpf);
            $insere->bindValue(":n",$nome);        
            $insere->bindValue(":t",$telefone);
            $insere->bindValue(":e",$endereco);
            $insere->bindValue(":coc",$codCargo);
            $insere->bindValue(":co",$codDepartamento);
            $insere->execute();
        }

        public function valida($cpf, $nome, $telefone, $endereco, $codCargo, $codDepartamento) {
            $valida = $this->pdo->prepare("select cpf from funcionario where cpf = :cpf");
            $valida->bindValue(":cpf", $cpf);
            $valida -> execute();
        
            if ($valida->rowCount()>0){
                echo"<script>alert('Funcionario já cadastrado, verifique duplicidade')</script>";
            }
            else {
                $this->insere($cpf, $nome, $telefone, $endereco, $codCargo, $codDepartamento);
                echo "<script>alert('Cadastrado o novo Funcionario com sucesso!')</script>";
            }
        }

        public function consultarFuncionarios() {
            $consulta = $this->pdo->query("SELECT f.*, d.nomeDepartamento, c.nomeCargo FROM 
            funcionario f INNER JOIN departamento d ON f.codDepartamento = d.codDepartamento 
            INNER JOIN cargo c ON f.codCargo = c.codCargo");
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }
    
        public function excluirFuncionario($funcional) {
            $comandosql = $this->pdo->prepare("DELETE FROM funcionario WHERE funcional = :funcional");
            $comandosql->bindParam(':funcional', $funcional);
            return $comandosql->execute();
        }
    
        public function consultarCargo() {
            $consulta = $this->pdo->query("SELECT * FROM cargo");
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function consultarDepartamento() {
            $consulta = $this->pdo->query("SELECT * FROM departamento");
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }



    }


?>
