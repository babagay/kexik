<?php
    /**
     * AJAX closure
     *
     * @author   Anton Shevchuk
     * @created  26.09.11 17:41
     * @return closure
     */
    namespace Application;

    use Bluz;

    $_this = $this;

    return

        function ($articles_id = null ) use ($view,$_this) {
            /**
             * @var \Application\Bootstrap $this

            fb($view); //  Bluz\View\View
            fb($_this); //  Application\Bootstrap
             *
             * @var \Bluz\View\View $view
             */

            $db =  app()->getDb();

            /*
             * // Для случая , когда ответ ожидается в JSON
            return function() {

                $res = array(
                    'response' => 'ok',
                );

                return $res;
            };
            */

            $mess = $title = '';

           $operation =  app()->getInstance()->getRequest()->operation;


            switch($operation){
                case 'hide':
                    $visibility = 0;
                    $db->query("UPDATE zoqa_articles SET visibility = :visibility WHERE articles_id = :articles_id", array('visibility' => $visibility, 'articles_id' => $articles_id));
                    $title = "Операция: [$operation]";
                    $mess = "Статья скрыта";
                    break;
                case 'unhide':
                    $visibility = 1;
                    $db->query("UPDATE zoqa_articles SET visibility = :visibility WHERE articles_id = :articles_id", array('visibility' => $visibility, 'articles_id' => $articles_id));
                    $title = "Операция: [$operation]";
                    $mess = "Статья открыта";
                    break;
                default:
                    break;
            }



            return function () use($mess, $title) {
                echo "<div class='jumbotron'><div class='container'>";
                echo "<h3>$title</h3>";
                echo "<p class='text-warning text-primary'>$mess</p>";
                echo "</div></div>";
            };

        };