<?php
    namespace controller\portal;

    use api\estabelecimento\apiEstabelecimento;
    use core\Controller;
    use object\estabelecimento\Estabelecimento;
    use helper\Security;

    class estabelecimentoController extends Controller {
        public function __construct() {
            parent::__construct();
    
            new Security();
        }

        public function index() {
            $api = new apiEstabelecimento();

            $this->dados = array (
                'list' => $api->getList()
            );
            $this->view();
        }

        public function salvo() {
            $api = new apiEstabelecimento();
            $sucesso = $this->getParams(0);

            $this->dados = array (
                'list' => $api->getList(),
                'salvo' => $sucesso
            );
            $this->view("index");
        }

        public function removido() {
            $api = new apiEstabelecimento();
            $sucesso = $this->getParams(0);

            $this->dados = array (
                'list' => $api->getList(),
                'removido' => $sucesso
            );
            $this->view("index");
        }

        public function formCadastro() {
            $estabelecimento = new Estabelecimento();
            $estabelecimento->idestabelecimento = $this->getParams(0);
            
            $api = new apiEstabelecimento();
            $api->get($estabelecimento);
            
            $this->dados = array (
                'dados' => $estabelecimento
            );
            $this->layout = "_layout.form";
            $this->view("formCadastro");
        }

        public function formEditar() {
            $estabelecimento = new Estabelecimento();
            $estabelecimento->idestabelecimento = $this->getParams(0);
            
            $api = new apiEstabelecimento();
            $api->get($estabelecimento);
            
            $this->dados = array (
                'dados' => $estabelecimento
            );

            $this->layout = "_layout.form";
            $this->view("formEditar");
        }


        public function save() {
            $api = new apiEstabelecimento();
            $_POST['data_cadastro'] = date("Y-m-d");
            $response = $api->save(new Estabelecimento('POST'));
            if ($response->estabelecimento === true) {
                header('location:' . APP_ROOT . 'portal/estabelecimento/salvo/true'); 
            } else {
                header('location:' . APP_ROOT . 'portal/estabelecimento/salvo/false');
            }
        }

        public function delete() {
            $api = new apiEstabelecimento();
            $id_estabelecimento = $this->getParams(0);
            $response = $api->deletar($id_estabelecimento);
            header('location:' . APP_ROOT . 'portal/estabelecimento/removido/true'); 
        }
    }
?>