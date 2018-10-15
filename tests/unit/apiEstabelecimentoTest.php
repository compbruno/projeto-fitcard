<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class apiEstabelecimentoTest extends TestCase {
    use TestCaseTrait;
    // só instancia o pdo uma vez para limpeza de teste e carregamento de ambiente
    static private $pdo = null;

    // só instancia PHPUnit_Extensions_Database_DB_IDatabaseConnection uma vez por teste
    private $conn = null;

    final public function getConnection() {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, ':memory:');
        }
        return $this->conn;
    }

    protected function getDataSet() {
        /*return new MyApp_DbUnit_ArrayDataSet(
            [
                'estabelecimento' => [
                    [
                        'idestabelecimento' => 1,
                        'razao_social' => 'Teste LTDA!',
                        'cnpj' => '81.425.869/0001-80',
                        'nome_fantasia' => 'teste'
                    ],
                ],
            ]
        );*/
    }

    public function Insert($obj, $table) {
        try {
            $sql = "INSERT INTO {$table} (".implode(",", array_keys((array)$obj)).") VALUES (".implode(",", array_values((array)$obj)).")";
            $state = $this->conn->prepare($sql);
            $state->execute(array('widgets'));
        } catch (\PDOException $e) {
            die($e->getMessage() . " " . $sql);
        }
        return array('success' => true, 'feedback' => '', 'codigo' => $this->Last($table));
    }

    public function testSave() {
        $estabelecimento['razao_social'] = "Razao social Teste";
        $estabelecimento['cnpj'] = "81.425.869/0001-80";
        $response = $this->Insert($estabelecimento, "estabelecimento");
        $this->assertEquals($response['success'], 'true');
    }
}