<?php
    /**
     * @author   Anton Shevchuk
     * @created  22.10.12 18:40
     */
    namespace Application;

    use Application\Users\Table;

    return
        /**
         * @return \closure
         */
        function () {
            /**
             * @var \Application\Bootstrap $this
             */

            $app_object = app()->getInstance();
            $db =  app()->getDb();

            $params = $app_object->getRequest()->getParams();

            if(isset($params['статья'])){

                $descriptor = urldecode($params['статья']);

                $article = $db->fetchRow("SELECT * FROM zoqa_articles WHERE descriptor = ". $db->quote($descriptor) . " OR articles_id =  ". $db->quote($descriptor)  );

                $article["dateline"] = $app_object->Date((int)$article["dateline"]);
                $article["group_name"] = \Core\Model\ArticleGroups\Table::findRow($article["groups_id"])->getName();

                $images = null;

                if((string)$article["images_icons"] != '') {
                        $images            = explode( ';', $article["images_icons"] );
                        $article["images"] = $images;
                } elseif($article["images"] != ''){
                    $images            = explode( ';', $article["images"] );
                    $article["images"] = $images;
                }

                if(  (int)$article["visibility"] == 1 ){

                    if(is_array($images)) {
                        ?>
                        <script>
                            require(["bluz.widget"]);
                        </script>

                        <div class="widget col-4" data-widget-key="users-stats">
                            <div class="widget-title">
                                <span class="iconholder-left"><i class="fa fa-signal"></i></span>
                                <h4>Images</h4>

                        <span class="iconholder-right widget-control" data-widget-control="collapse">
                            <i class="fa fa-chevron-up"></i>
                        </span>
                            </div>



                            <div class="widget-content">
                                <?php
                                    if( is_array( $images ) )
                                        foreach( $images as $image ) {
                                            echo "<div class=''>";
                                            echo "<img src='$image' class='widget-image' />";
                                            echo "</div>";
                                        }
                                ?>
                            </div>
                        </div>
                    <?php
                    }
                }
            }

            /*
            $total = $app_object->getDb()->fetchOne('SELECT COUNT(*) FROM zoqa_users');
            $active = $app_object->getDb()->fetchOne('SELECT COUNT(*) FROM zoqa_users WHERE status = ?', array(Table::STATUS_ACTIVE));
            $last = $app_object->getDb()->fetchRow('SELECT id, login FROM zoqa_users ORDER BY id DESC LIMIT 1');
            ?>
            <script>
                require(["bluz.widget"]);
            </script>
            <div class="widget col-4" data-widget-key="users-stats">
                <div class="widget-title">
                    <span class="iconholder-left"><i class="fa fa-signal"></i></span>
                    <h4>Users</h4>
            <span class="iconholder-right widget-control" data-widget-control="collapse">
                <i class="fa fa-chevron-up"></i>
            </span>
                </div>
                <div class="widget-content">
                    <ul class="widget-stats">
                        <li>
                            <a href="<?=app()->getRouter()->url('users', 'grid')?>">
                                <i class="fa fa-user fa-fw"></i>
                                <strong><?=$total?></strong>
                                <small><?=__('Total Users')?></small>
                            </a>
                        </li>
                        <li>
                            <a href="
                    {# app()->getRouter()->url('users', 'grid', ['users-filter-status' => Table::STATUS_ACTIVE])?>" #}
                         {{ url(('users', 'grid', ['users-filter-status' => Table::STATUS_ACTIVE]) }}

                                <i class="fa fa-eye fa-fw"></i>
                                <strong><?=$active?></strong>
                                <small><?=__('Active Users')?></small>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                          {#  <a href=" app()->getRouter()->url('users', 'profile', ['id' => $last['id']])?>"  #}
                            <a href="{{ url('users', 'profile', ['id' => $last['id']]) }}">
                                <i class="fa fa-user fa-fw"></i>
                                <strong><?=$last['login']?></strong>
                                <small><?=__('Last Registers')?></small>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        <?php
        */

      /*




        */
        };
