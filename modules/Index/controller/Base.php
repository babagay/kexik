<?php
/**
 * Default module/controller
 *
 * @author   Anton Shevchuk
 * @created  06.07.11 18:39
 * @return closure
 */
namespace Application;

return
    /**
     * @return \closure
     */
    function (){
        /**
         * @var \Application\Bootstrap $this
         */

        $app_object = app()->getInstance();




       // $app_object->getLayout()->title('Блог'); // дает Base :: Блог

       // fb( $app_object->getRequest()->getModule() );
		 
    };