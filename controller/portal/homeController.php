<?php

namespace controller\portal;

use helper\Security;
use core\Controller;

class homeController extends Controller {
    public function __construct() {
        parent::__construct();

        new Security();
    }

    public function index() {
        $this->view();
    }
}