<?php

class FaceWidget extends abstractWidget {
// ������


    private $name = 'FaceWidget';


    function __construct($input_data = null){
        $this->input_data = $input_data;

        parent::__construct();
    }

    function process(){
        $this->html = 'asd';
    }

}



?>