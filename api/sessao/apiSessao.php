<?php

namespace api\Sessao;

use core\Model;
use object\sessao\Sessao;

class apiSessao extends Model {
    public function login(Sessao $obj) {
        $user = trim($obj->usuario);
        $pass = trim($obj->senha);

        $sql = "SELECT u.usuario, u.senha FROM usuario AS u WHERE usuario = '{$user}'";
        $query = $this->First($this->Select($sql));

        if (isset($query->senha)) {
            if ($pass == $query->senha) {
                $_SESSION['usuario'] = $query->senha;
                header('location:' . APP_ROOT . 'portal/estabelecimento');
            } else {
                echo '<script>alert("usuario ou senha inválidos")</script>';
                header('location:' . APP_ROOT . 'portal/sessao');
            }
        } else {
            echo '<script>alert("usuario ou senha inválidos")</script>';
            header('location:' . APP_ROOT . 'portal/sessao');
        }
    }

    public function logout() {
        unset($_SESSION['usuario']);
        header('location:' . APP_ROOT . 'portal/sessao');
    }
}