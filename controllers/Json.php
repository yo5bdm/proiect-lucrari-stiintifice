<?php
class Json extends Controller {
    
    public $json = true;
    
    private function getJson() {
        return json_decode(file_get_contents('php://input'),true);
    }
    
    public function listalucrari() {
        $date = new Date();
        $this->setData($date->getListaLucrari());
    }
    public function getLucrare($id) {
        $date = new Date();
        $content = $date->userId(App::$app->user->getId())->getLucrarea($id);
        $this->setData($content);
    }
    public function stergelucrarea($id) {
        $db = new Date();
        if($db->stergeLucrarea($id)) {
            $this->setData('1');
        } else {
            $this->setData('0');
        }
    }
    public function savelucrare() {
        if($this->getJson()!=NULL) {
            $json = $this->getJson();
            $lucrare = new Lucrare();
            $lucrare->fromArray($json['datele']);
            $lucrareModel = new Date();
            $lucrareModel->salveazaLucrarea($lucrare);
            $this->setData('1');
        } else {
            $this->setData('0');
        }
    }
    public function updatelucrare() {
        if($this->getJson()!=NULL) {
            $json = $this->getJson();
            $lucrare = new Lucrare();
            $lucrare->fromArray($json['datele']);
            $lucrare->setID($json['lucrareid']);
            $lucrareModel = new Date();
            if($lucrareModel->actualizeazaLucrarea($lucrare)) {
                $this->setData('1');
            } else {
                $this->setData('0');
            }
        } else {
            $this->setData('-1');
        }
    }
    
    public function utilizator($id) {
        $date = new Date();
        $data = $date->userId($id)->getListaLucrari();
        $this->setData($data);
    }
    public function listauseri() {
        $date = new Users();
        $data = $date->getData();
        $this->setData($data);
    }
    public function getuser($nume) {
        $users = new Users();
        $u = $users->getByName($nume);
        $this->setData($u);
    }
    public function getusers() {
        $users = new Users();
        $u = $users->getNameList();
        $this->setData($u);
    }
    public function getmyuser() {
        if(App::$app->user->isLoggedIn()) {
            $users = new Users();
            $user = $users->getById(App::$app->user->getID());
            $this->setData($user);
        } else {
            $this->setData(false);
        }
    }
    public function updateuser() {
        if(App::$app->user->isLoggedIn()){
            $db = new Users();
            $this->setData($db->updateUser($this->getJson()));
        }
    }
    
    
    public function getunitate() {
        $date = new Date();
        $this->setData($date->getUnitate());
    }
    
    public function saveunitate() {
        if($this->getJson()!=NULL) {
            $json = $this->getJson();
            $date = new Date();
            $date->salveazaUnitate($json);
            $this->setData('1');
        } else {
            $this->setData('0');
        }
    }
    
    public function getgrupuri() {
        $date = new Date();
        $this->setData($date->getListaGrupuri());
    }
    
    public function admin() { //toate datele necesare pe pagina de admin
        $date = new Date();
        $this->setData($date->getData());
    }
    public function savecategorii() {
        if($this->getJson()!=NULL) {
            $json = $this->getJson();
            $date = new Date();
            $date->updateCategorii($json['datele']);
            $this->setData('1');
        } else {
            $this->setData('0');
        }
    }
    public function getcategorii() {
        $db = new Date();
        $this->setData($db->getCategorii());
    }
    
    public function savebazededate() {
        if($this->getJson()!=NULL) {
            $json = $this->getJson();
            $date = new Date();
            $date->updateDBs($json['datele']);
            $this->setData('1');
        } else {
            $this->setData('0');
        }
    }
    public function getbazededate() {
        $db = new Date();
        $this->setData($db->getDBs());
    }
    
}