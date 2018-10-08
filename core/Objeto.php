<?php

namespace core;

class Objeto {
    public function __construct($method = null, $all = true) {
        if ($method == 'POST') {
            foreach ($_POST as $key => $value) {
                $this->$key = $value;
            }
        }

        if (isset($_FILES)) {
            foreach ($_FILES as $key => $value) {
                if ($all || isset($this->$key)) {
                    $this->$key = $value;
                }
            }
        }
    }
}