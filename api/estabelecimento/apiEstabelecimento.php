<?php

namespace api\estabelecimento;

use core\Model;
use object\estabelecimento\Estabelecimento;

class apiEstabelecimento extends Model {
    public function get(Estabelecimento $obj) {
        $query = $this->First($this->Select("SELECT e.IDESTABELECIMENTO, e.CNPJ, e.RAZAO_SOCIAL, e.NOME_FANTASIA, e.EMAIL, e.CATEGORIA, e.AGENCIA, e.CONTA, e.STATUS, e.DATA_CADASTRO, t.DDD, t.NUMERO, en.RUA, en.BAIRRO, en.CIDADE, en.ESTADO FROM estabelecimento AS e LEFT JOIN telefone AS t ON e.IDESTABELECIMENTO = t.ID_ESTABELECIMENTO LEFT JOIN endereco AS en ON e.IDESTABELECIMENTO = en.ID_ESTABELECIMENTO WHERE idestabelecimento = '{$obj->idestabelecimento}'"));
        $this->setObject($obj, $query);
    }

    public function getList() {
        return $this->Select("SELECT e.IDESTABELECIMENTO, e.CNPJ, e.RAZAO_SOCIAL, e.NOME_FANTASIA, e.EMAIL, e.CATEGORIA, e.STATUS, e.DATA_CADASTRO FROM estabelecimento AS e");
    }

    public function deletar($id) {
        return $this->Delete(array('idestabelecimento' => $id), 'estabelecimento');
    }

    public function save(Estabelecimento $obj) {
        foreach ($obj as $key => $value) {
            if(!in_array($key, array('ddd', 'numero', 'rua', 'num', 'bairro', 'cidade', 'estado'))) {
                $estabelecimento->$key = $value;
            }
            if(in_array($key, array('ddd', 'numero'))) {
                $telefone->$key = $value;
            }
            if(in_array($key, array('rua', 'num', 'bairro', 'cidade', 'estado'))) {
                $endereco->$key = $value;
            }
        }
        if (empty($obj->idestabelecimento)) {
            $response = $this->Insert($estabelecimento, 'estabelecimento');
            if($response['success'] == 1) {
                $id_inserido = $response['codigo'];

                $telefone->id_estabelecimento = $id_inserido;
                $endereco->id_estabelecimento = $id_inserido;

                $this->Insert($endereco, 'endereco');
                $this->Insert($telefone, 'telefone');
                //echo "Estabelecimento salvo com sucesso. ID: $id_inserido";
            }
        } else {
            $this->Update($estabelecimento, array('idestabelecimento' => $obj->idestabelecimento), 'estabelecimento');
            $this->Update($telefone, array('id_estabelecimento' => $obj->idestabelecimento), 'telefone');
            $this->Update($endereco, array('id_estabelecimento' => $obj->idestabelecimento), 'endereco');
            //echo "Estabelecimento editado com sucesso.";
        }
    }
}