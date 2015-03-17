<?php  
 
class FaceView extends BasicModulView implements BasicViewInterface{ 
// представление модуля 

    function __construct(){
       
        parent::__construct(); 
        
        $this->view = View::getInstance(); // через этот объект - работать с парент шаблоном
      
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