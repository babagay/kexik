<?php
    /**
     * Example of API method - вызов методов из шаблона
     *
     * @author   Anton Shevchuk
     * @created  15.10.12 15:22
     *
     *           Вызов: {{ api_closure("blog","test",  { "num":3, "n":5 } ) }}
     */
    namespace Application;

    return
        /**
         * @param int $num
         * @return \closure
         */
        function ($num, $n = null) {
            return $num*$n;
        };