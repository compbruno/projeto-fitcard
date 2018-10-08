<?php
    namespace controller\portal;

    use api\estabelecimento\apiEstabelecimento;
    use core\Controller;
    use object\estabelecimento\Estabelecimento;
    use helper\Security;

    class estabelecimentoController extends Controller {
        public function index() {
            $api = new apiEstabelecimento();

            $this->dados = array (
                'list' => $api->getList()
            );
            $this->view();
        }

        public function __construct() {
            parent::__construct();
    
            new Security();
        }

        public function formCadastro() {
            $estabelecimento = new Estabelecimento();
            $estabelecimento->idestabelecimento = $this->getParams(0);

            $api = new apiEstabelecimento();
            $api->get($estabelecimento);
            
            $this->dados = array (
                'dados' => $estabelecimento
            );
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
            $this->view("formEditar");
        }


        public function save() {
            $api = new apiEstabelecimento();
            $_POST['data_cadastro'] = date("Y-m-d");
            $api->save(new Estabelecimento('POST'));
            header('location:' . APP_ROOT . 'portal/estabelecimento');
        }

        public function delete() {
            $api = new apiEstabelecimento();
            $id_estabelecimento = $this->getParams(0);
            $api->deletar($id_estabelecimento);
            header('location:' . APP_ROOT . 'portal/estabelecimento');
        }
    }
?>