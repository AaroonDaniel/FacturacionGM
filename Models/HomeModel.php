<?php
class HomeModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUsuarios(){
        $sql="select * from usuarios";
        $data=$this->selectAll($sql);
        return $data;
    }
    
}
?>