<?php
class Home extends Controller {
    public function __construct() {
        session_start();
        parent::__construct();
    }

    public function index() {
        $this->views->getView($this, "index");
    }
    public function inicio(){
        $this->views->getView($this, "inicio");
    }
}
?>