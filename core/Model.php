<?php

namespace core;

class Model extends Config {
    protected $con;

    public function __construct() {
        try {
            $this->con = new \PDO("mysql:host=" . self::srvMyHost . ";dbname=" . self::srvMyDbName, self::srvMyUser, self::srvMyPass);
            $this->con->exec("set names " . self::dbCharset);
            $this->con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    public function Select($sql) {
        try {
            $state = $this->con->prepare($sql);
            $state->execute();
        } catch (\PDOException $e) {
            die($e->getMessage() . " " . $sql);
        }
        $array = array();
        while($row = $state->fetchObject()) {
            $array[] = $row;
        }
        return $array;
    }

    public function Insert($obj, $table) {
        try {
            $sql = "INSERT INTO {$table} (".implode(",", array_keys((array)$obj)).") VALUES (".implode(",", array_values((array)$obj)).")";
            $state = $this->con->prepare($sql);
            $state->execute(array('widgets'));
        } catch (\PDOException $e) {
            die($e->getMessage() . " " . $sql);
        }
        return array('success' => true, 'feedback' => '', 'codigo' => $this->Last($table));
    }

    public function Update($obj, $condition, $table) {
        try {
            foreach ($obj as $key => $value) {
                $dados[] = "`{$key}` = " .(is_null($value) ? " NULL " : "{$value}");
            }

            foreach ($condition as $key => $value) {
                $where[] = "`{$key}` " .(is_null($value) ? " IS NULL " : " = {$value}");
            }

            $sql = "UPDATE {$table} SET " . implode(',', $dados) . " WHERE " . implode(' AND ', $where);
            $state = $this->con->prepare($sql);
            $state->execute(array('widgets'));
        } catch (\PDOException $e) {
            die ($e->getMessage() . " " . $sql);
        }
        return array('success' => true, 'feedback' => '');
    }

    public function Delete($condition, $table) {
        try {
            foreach ($condition as $key => $value) {
                $where = "$key =  {$value}";
            }
            $sql = "DELETE FROM $table WHERE $where";
            $state = $this->con->prepare($sql);
            $state->execute(array('widgets'));
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
        return array('success' => true, 'feedback' => '');
    }

    public function Last($table) {
        try {
            $state = $this->con->prepare("SELECT last_insert_id() AS LAST FROM {$table}");
            $state->execute();
            $state = $state->fetchObject();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
        return $state->LAST;
    }

    public function First($obj) {
        if (isset($obj[0])) {
            return $obj[0];
        } else {
            return null;
        }
    }

    public function setObject($obj, $values, $exists = true)  {
        if (is_object($obj)) {
            if (count($values) > 0) {
                foreach ($values as $key => $value) {
                    if (property_exists($obj, $key) || $exists) {
                        $atributo = mb_strtolower($key);
                        $obj->$atributo = $values->$key;
                    }
                }
            }
        }
    }
}