<?php

/* products.phtml */
class __TwigTemplate_b160ccd283248fbd2b295c4bb9f9f7f19af55426990872b9d3dd359a319c91cb extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
        if (isset($context["products"])) { $_products_ = $context["products"]; } else { $_products_ = null; }
        if ($_product_) {
            // line 2
            echo "    <div>
        ";
            // line 3
            if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_product_, "products_name"), "html", null, true);
            echo "
        <input type=\"hidden\" name=\"products_id\" value=\"";
            // line 4
            if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_product_, "products_id"), "html", null, true);
            echo "\">
    </div>

";
        } elseif ($_products_) {
            // line 8
            echo "
<div class=\"position-relative\">
    <div class=\"select-cabnet\"><span> Цена:   </span>
        <ul class=\"select-no-display\">
            <li><a href=\"";
            // line 12
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("catalog/products"), "html", null, true);
            echo "\" direction=\"asc\" class=\"filter-price-order\">по возрастанию </a></li>
            <li><a href=\"";
            // line 13
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("catalog/products"), "html", null, true);
            echo "\" direction=\"desc\" class=\"filter-price-order\">по убыванию</a></li>
        </ul>
    </div>
</div>

";
            // line 18
            if (isset($context["categories_id"])) { $_categories_id_ = $context["categories_id"]; } else { $_categories_id_ = null; }
            if (isset($context["manufacturers_id"])) { $_manufacturers_id_ = $context["manufacturers_id"]; } else { $_manufacturers_id_ = null; }
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->widget("catalog", "manufacturer", array("категория" => $_categories_id_, "manufacturers_id" => $_manufacturers_id_)), "html", null, true);
            echo "

<script>
    require([\"jquery\", \"bluz.notify\"], function(\$, notify) {
        \$(\"a.filter-price-order\").off().on(\"click\",function(event, data, textStatus, jqXHR){

            var direction = \$(this).attr(\"direction\")
            var default_order_field =  \$(\"form[name=state] input[name=default_order_field]\").val()

            \$(\"form[name=state] input[name=order]\").val(direction + \"-\" + default_order_field)


            var data = {}

            data[\"direction\"] = direction; //?

            var url = \$(this).attr(\"href\") + \"?\" + \$(\"form[name=state]\").serialize();

            \$.ajax({
                type: \"POST\",
                data: data,
                url: url,
                context: document.body,
                dataType: \"html\"
            }).done(function(response,status,responseObj) {
                    \$( this ).addClass( \"done\" );
                    if(status == \"success\"){

                        if(responseObj.status != 200){
                            notify.addError(\"Error 404\")
                        }

                        \$(\"#content_box\").html(response)
                    }
                });

            return false;
        });

        \$(\"a.filter-vendor\").off().on(\"click\",function(event, data, textStatus, jqXHR){


            var data = {}

            //data = \$(\"form[name=state]\").serialize();

            data[\"uri\"] = window.location.href;
            data[\"manufacturers_id\"] =  \$(this).attr(\"manufacturers_id\");
            data[\"категория\"] = \$(this).attr(\"categories_id\");

            \$(\"form[name=state] input[name=filter-manufacturers_id]\").val(\"eq-\" + data[\"manufacturers_id\"]);
            \$(\"form[name=state] input[name=filter-categories_id]\").val(\"eq-\" + data[\"категория\"]);

            var url = \$(this).attr(\"href\") + \"?\" + \$(\"form[name=state]\").serialize();


            \$.ajax({
                type: \"POST\",
                data: data,
                url: url,
                context: document.body,
                dataType: \"html\"
            }).done(function(response,status,responseObj) {
                    \$( this ).addClass( \"done\" );
                    if(status == \"success\"){
                        /*
                         // responseObj.responseText - is response
                         if(response.response == \"ok\"){
                         // console.log(\"ок\")
                         // var \$div = bluz_ajax.createModal(\"content\") // не работает
                         } else {
                         // console.log(\"Ошибка данных\")
                         }
                         */

                        if(responseObj.status != 200){
                            notify.addError(\"Error 404\")
                        }

                        //articles_frame_counter = \$(\"input[name=articles_frame_counter]\").val();
                        //articles_frame_counter++;
                        //\$(\"input[name=articles_frame_counter]\").val(articles_frame_counter)

                        //console.log(response)

                        \$(\"#content_box\").html(response)
                    }
                });
            return false;
        });
    });
</script>


<div class=\"wrapper-goods\">

    ";
            // line 114
            if (isset($context["products"])) { $_products_ = $context["products"]; } else { $_products_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($_products_);
            foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
                // line 115
                echo "
        <div class=\"goods\">
            ";
                // line 117
                if (isset($context["category_parent"])) { $_category_parent_ = $context["category_parent"]; } else { $_category_parent_ = null; }
                if ($_category_parent_) {
                    echo " ";
                    if (isset($context["category_parent"])) { $_category_parent_ = $context["category_parent"]; } else { $_category_parent_ = null; }
                    $context["parent"] = ("/" . $_category_parent_);
                    // line 118
                    echo "            ";
                } else {
                    echo " ";
                    $context["parent"] = "";
                    echo " ";
                }
                // line 119
                echo "
            ";
                // line 120
                if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
                if ($this->getAttribute($_product_, "products_seo_page_name")) {
                    echo " ";
                    if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
                    $context["product_link"] = $this->getAttribute($_product_, "products_seo_page_name");
                    // line 121
                    echo "            ";
                } else {
                    echo " ";
                    if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
                    $context["product_link"] = $this->getAttribute($_product_, "products_id");
                    echo " ";
                }
                // line 122
                echo "

            <div class=\"name\">";
                // line 124
                if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
                if (isset($context["parent"])) { $_parent_ = $context["parent"]; } else { $_parent_ = null; }
                if (isset($context["category_name"])) { $_category_name_ = $context["category_name"]; } else { $_category_name_ = null; }
                if (isset($context["product_link"])) { $_product_link_ = $context["product_link"]; } else { $_product_link_ = null; }
                echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref($this->getAttribute($_product_, "products_name"), array(0 => ((("каталог" . $_parent_) . "/") . $_category_name_), 1 => $_product_link_)), "html", null, true);
                echo "</div>
            <div class=\"image-box\">
                <img src=\"";
                // line 126
                echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("public/images/goods1.jpg"), "html", null, true);
                echo "\">
            </div>
            <div class=\"description\">
                <div class=\"weight\">";
                // line 129
                if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_product_, "products_unit"), "html", null, true);
                echo "</div>
                <div class=\"cost\">";
                // line 130
                if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_product_, "products_shopping_cart_price"), "html", null, true);
                echo "</div>
                <div class=\"basket\"><a href=\"\" >В корзину</a></div>
                <div class=\"number-arrow\">
                    <a class=\"inc\"></a>
                    <input type=\"number\" min=\"1\" value=\"1\" class=\"num\">
                    <a class=\"dec\"></a>
                </div>
                <div class=\"price\">";
                // line 137
                if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_product_, "products_shopping_cart_price"), "html", null, true);
                echo "</div>
            </div>

            <input type=\"hidden\" name=\"products_id\" value=\"";
                // line 140
                if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_product_, "products_id"), "html", null, true);
                echo "\">
            <input type=\"hidden\" name=\"products_barcode\" value=\"";
                // line 141
                if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_product_, "products_barcode"), "html", null, true);
                echo "\">
            <input type=\"hidden\" name=\"products_name\" value=\"";
                // line 142
                if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_product_, "products_name"), "html", null, true);
                echo "\">
            <input type=\"hidden\" name=\"products_unit\" value=\"";
                // line 143
                if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_product_, "products_unit"), "html", null, true);
                echo "\">
            <input type=\"hidden\" name=\"products_departament\" value=\"";
                // line 144
                if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_product_, "products_departament"), "html", null, true);
                echo "\">
            <input type=\"hidden\" name=\"products_shopping_cart_price\" value=\"";
                // line 145
                if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_product_, "products_shopping_cart_price"), "html", null, true);
                echo "\">
            <input type=\"hidden\" name=\"products_quantity\" value=\"";
                // line 146
                if (isset($context["product"])) { $_product_ = $context["product"]; } else { $_product_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_product_, "products_quantity"), "html", null, true);
                echo "\">
            ";
                // line 148
                echo "


        </div>

    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['product'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 154
            echo "</div>
";
        }
        // line 156
        echo "
";
    }

    public function getTemplateName()
    {
        return "products.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  289 => 156,  285 => 154,  274 => 148,  269 => 146,  264 => 145,  259 => 144,  254 => 143,  249 => 142,  244 => 141,  239 => 140,  232 => 137,  221 => 130,  216 => 129,  210 => 126,  201 => 124,  197 => 122,  189 => 121,  183 => 120,  180 => 119,  173 => 118,  167 => 117,  163 => 115,  158 => 114,  57 => 18,  49 => 13,  45 => 12,  39 => 8,  31 => 4,  26 => 3,  23 => 2,  19 => 1,);
    }
}
