<?php

namespace Application;

return
    /**
     * @return \closure
     */
    function () {
        /**
         * @var \Application\Bootstrap $this
         */

        $app_object = app()->getInstance();

        $view = $app_object->getView();


        $html = '';

        $db = app()->getDb();

        $params = $app_object->getRequest()->getParams();

        $categories = array();


        if (isset($params['статья'])) {
            //TODO
            // Если мы в каталоге, подсветить пункт меню
            //$descriptor = urldecode($params['статья']);

            $categories = $db->fetchRow("SELECT * FROM categories WHERE parent_id = " . $db->quote(0));

        } else {
            // По умолчанию

            $categories = $db->fetchAll("SELECT * FROM categories WHERE parent_id = " . $db->quote(0) . " ORDER BY sort_order ASC ");
            //fb($categories);
        }


        if (is_array($categories)) {
            if (count($categories) > 0) {
                $html .= '<ul id="main-navigation">';
                $index_2 = 0;
                foreach ($categories as $index => $category) {
                    $sub_categories = $db->fetchAll("SELECT * FROM categories WHERE parent_id = " . $db->quote($category['categories_id']) . " ORDER BY sort_order ASC ");

                    $submenu = '';
                    $sub_category_html = '';

                    if (is_array($sub_categories)) {
                        if (count($sub_categories) > 0) {
                            $sub_category_html = '<table>';
                            $i                 = 1;
                            $row               = "";
                            foreach ($sub_categories as $ind => $sub_category) {
                                if (($i % 2) == 0) {
                                    //$row .= "</tr>";
                                } else {
                                    $row = "<tr>";
                                }

                                $parent_lnk = trim($category['categories_seo_page_name']) != '' ? $category['categories_seo_page_name'] : $category['categories_id'];
                                $end_category_lnk = trim($sub_category['categories_seo_page_name']) != '' ? $sub_category['categories_seo_page_name'] : $sub_category['categories_id'];

                                $link = $view->baseUrl("каталог/$parent_lnk/$end_category_lnk");
                                // $view->ahref("text","href" )
                                $row .= "<td> <a href='$link'>{$sub_category['categories_name']}</a>  </td>";

                                if (($i % 2) == 0) {
                                    $row .= "</tr>";
                                    $sub_category_html .= $row;
                                    $row = "";
                                }

                                $i++;
                            }

                            if (($i % 2) == 0) {
                                $sub_category_html .= $row . "</tr>";
                            }

                            $sub_category_html .= '</table>';

                            /*  $sub_category_html = '<ul>';
                              $i = 1;
                              $row = "";
                              foreach($sub_categories as $ind => $sub_category){
                                  if( ($i % 2) == 0){

                                  } else {

                                  }

                                  $parent_lnk = trim($category['categories_seo_page_name']) != '' ? $category['categories_seo_page_name'] : $category['categories_id'];
                                  $end_category_lnk = trim($sub_category['categories_seo_page_name']) != '' ? $sub_category['categories_seo_page_name'] : $sub_category['categories_id'];

                                  $link = $view->baseUrl("каталог/$parent_lnk/$end_category_lnk");
                                  // $view->ahref("text","href" )
                                  $row .= "<li> <a href='$link'>{$sub_category['categories_name']}</a>  </li>";

                                  if( ($i % 2) == 0){

                                      $sub_category_html .= $row;
                                      $row = "";
                                  }

                                  $i++;
                              }

                              $sub_category_html .= '</ul>';*/
                            $submenu = '<div class="submenu" style="background:  url(public/images/' . $category['categories_background'] . ') 0 0 no-repeat;  background-size: cover;">
                                          <!--   <img class="holder" src="' . $view->baseUrl('public/images/' . $category['categories_background']) . '"/>-->' .
                                $sub_category_html .
                                '</div>';
                        }
                    }

                    $active = '';
                    if ($category['categories_seo_page_name'] == app()->getRequest()->get(2)) {
                        $active = "active";
                    }


                    if ((int)$category['categories_level'] == 1) {
                        // Эта категория не имеет подкатегорий - endcategory
                        $html .= '
                        <li class="left-nav' . ++$index_2 . '">
                            <img src="' . $view->baseUrl('public/images/' . $category['categories_icon']) . '">
                            <a href="' . $view->baseUrl('каталог/' . $category['categories_seo_page_name']) . '" >
                                <span class="_jsCaller ' . $active . '" param="0">' . $category['categories_name'] . '</span>
                            </a>
                            <div class="submenu-wrapper">' .
                            $submenu
                            . '</div>
                        </li>';
                    } else {
                        $html .= '
                        <li class="left-nav' . ++$index_2 . '">
                            <img src="' . $view->baseUrl('public/images/' . $category['categories_icon']) . '">
                            <a href="javascript:return false;" >
                                <span class="jsCaller ' . $active . '" param="0">' . $category['categories_name'] . '</span>
                            </a>
                            <div class="submenu-wrapper">' .
                            $submenu
                            . '</div>
                        </li>';
                    }


                }
                $html .= '</ul>';
            }
        }

        echo($html);

    };