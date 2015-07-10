<?php

    namespace Application;

    use Bluz;
    use Application\Admin;

    $_this = $this;

    return
        /**
         * @return \closure
         */
        function ($categories_id = null) use ($_this, $view, $module, $controller) {
            /**
             * @var \Application\Bootstrap $this
             * @var \Bluz\View\View        $view
             */

            $parent_id = 0;
            $prev_parent_id     = null;
            $categories_level     = 0;
            $params    = $_this->getRequest ()->getParams ();

            if (sizeof ($params)) {
                foreach ($params as $param => $value) {
                    if(is_string($param)){
                        if ($param == 'sql-filter-parent_id') {
                            if(is_numeric($value))
                                $parent_id = $value;
                        }
                        /* elseif ($param == 'categories_level') {
                            if(is_numeric($value))
                                $categories_level = $value;
                        } */
                        elseif ($param == 'prev_parent_id') {
                            if(is_numeric($value))
                                $prev_parent_id = $value;
                        }
                        elseif ($param == 'categories_id') {
                            if(is_numeric($value) AND is_null($categories_id))
                                $categories_id = $value;
                        }
                    }
                }
            }

            if(!is_null($parent_id) AND $parent_id > 0) {
                $category = Categories\Table::getInstance()->find($parent_id);
                if(isset($category[0])){
                    $prev_parent_id = $category[0]->parent_id;
                    $categories_level = $category[0]->categories_level + 1;
                }
            }

            $grid = new  Categories\SelectGrid();
            $grid->setModule ($module);
            $grid->setController ($controller);

            $view->grid      = $grid;
            $view->categories_level     = $categories_level;
            $view->parent_id = $parent_id;
            $view->prev_parent_id = $prev_parent_id;

            //return 'grid-sql.phtml';
        };