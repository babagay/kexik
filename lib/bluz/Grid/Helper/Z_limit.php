<?php
    /**
     * @copyright Bluz PHP Team
     * @link https://github.com/bluzphp/framework
     *
     *       "Левое" имя объясняется тем, что с другими именами (Limit, Getlimit) работало не корректно...
     */

    /**
     * @namespace
     */
    namespace Bluz\Grid\Helper;

    use Bluz\Application\Application;
    use Bluz\Grid;

    $_this = $this;

    return
        /**
         * @return string|null $url
         * @use Grid\Grid $_this
         */
        function ($limit = 25) use($_this) {
            /**
             * @var Grid\Grid $this
             */

            $grid = $_this;

            $rewrite['limit'] = (int)$limit;

            if ($limit != $_this->getLimit()) {
                $rewrite['page'] = 1;
            }

            return $_this->getUrl($rewrite);



        };

