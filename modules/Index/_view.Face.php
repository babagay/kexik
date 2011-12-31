<?php

class FaceView extends BasicModulView implements BasicViewInterface{
// ������������� ������ 

    function __construct(){

        parent::__construct();

        $this->view = View::getInstance(); // ����� ���� ������ - �������� � ������ ��������

        $this->basic_tpl = 'Face.tpl';

    }

    function getView(){
        return $this->view;
    }

    function render($tpl = null){

        if($tpl == null)
          $tpl = $this->basic_tpl;

        parent::render($tpl);


        return $this->html;

        //return $this->html = parent::render();
    }

}



?>