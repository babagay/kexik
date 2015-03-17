<?php
    /**
     * Аналог хелпера Page
     */
    namespace Bluz\Grid\Helper;

    use Bluz\Application\Application;
    use Bluz\Grid;

    $_this = $this;

    return
        /**
         * @return string|null
         */
        function ($page = 1)  use($_this) {
            /**
             * @var Grid\Grid $this
             * $_this Core\Model\Test\SqlGrid, например
             */

            //fb($page);


            if ($page < 1 or $page > $_this->pages()) {
                return null;
            }

            return $_this->getUrl(array('page' => $page));
        };
