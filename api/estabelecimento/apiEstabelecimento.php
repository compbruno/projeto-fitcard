<?php

namespace api\estabelecimento;

use core\Model;
use object\estabelecimento\Estabelecimento;

class apiEstabelecimento extends Model {
    public function get(Estabelecimento $obj) {
        $query = $this->First($this->Select("SELECT e.IDESTABELECIMENTO, e.CNPJ, e.RAZAO_SOCIAL, e.NOME_FANTASIA, e.EMAIL, e.CATEGORIA, e.AGENCIA, e.CONTA, e.STATUS, e.DATA_CADASTRO, t.DDD, t.NUMERO, en.RUA, en.NUM, en.BAIRRO, en.CIDADE, en.ESTADO FROM estabelecimento AS e LEFT JOIN telefone AS t ON e.IDESTABELECIMENTO = t.ID_ESTABELECIMENTO LEFT JOIN endereco AS en ON e.IDESTABELECIMENTO = en.ID_ESTABELECIMENTO WHERE idestabelecimento = '{$obj->idestabelecimento}'"));
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
                if($key == "email" && $value == "") {
                    $estabelecimento->$key = "NULL";
                } else {
                    $estabelecimento->$key = '"'.$value.'"';
                }
            }
            if(in_array($key, array('ddd', 'numero'))) {
                $telefone->$key = '"'.$value.'"';
            }
            if(in_array($key, array('rua', 'num', 'bairro', 'cidade', 'estado'))) {
                $endereco->$key = '"'.$value.'"';
            }
        }
        $response_save->telefone         = false;
        $response_save->endereco         = false;
        $response_save->estabelecimento  = false;

        if (empty($obj->idestabelecimento)) {
            $response = $this->Insert($estabelecimento, 'estabelecimento');
            if($response['success'] == 1) {
                $id_inserido = $response['codigo'];

                $telefone->id_estabelecimento = $id_inserido;
                $endereco->id_estabelecimento = $id_inserido;

                $save_endereco = $this->Insert($endereco, 'endereco');
                $save_telefone = $this->Insert($telefone, 'telefone');

                $response_save->estabelecimento = true;
                $response_save->telefone = $save_telefone['success'] == 1 ? true : false;
                $response_save->endereco = $save_endereco['success'] == 1 ? true : false;
            }
        } else {
            $update_estabelecimento = $this->Update($estabelecimento, array('idestabelecimento' => $obj->idestabelecimento), 'estabelecimento');

            $response_save->estabelecimento = $update_estabelecimento['success'] == 1 ? true : false;

            if ($this->First($this->Select("SELECT t.id_estabelecimento FROM telefone AS t WHERE t.id_estabelecimento = '$obj->idestabelecimento' LIMIT 1"))) {
                $save_telefone = $this->Update($telefone, array('id_estabelecimento' => $obj->idestabelecimento), 'telefone');
            } else {
                $telefone->id_estabelecimento = $obj->idestabelecimento;
                $save_telefone = $this->Insert($telefone, 'telefone');
            }

            $response_save->telefone = $save_telefone['success'] == 1 ? true : false;

            if ($this->First($this->Select("SELECT e.id_estabelecimento FROM endereco AS e WHERE e.id_estabelecimento = '$obj->idestabelecimento' LIMIT 1"))) {
                $save_endereco = $this->Update($endereco, array('id_estabelecimento' => $obj->idestabelecimento), 'endereco');
            } else {
                $endereco->id_estabelecimento = $obj->idestabelecimento;
                $save_endereco = $this->Insert($endereco, 'endereco');
            }
            $response_save->endereco = $save_endereco['success'] == 1 ? true : false;
        }
        return $response_save;
    }
}