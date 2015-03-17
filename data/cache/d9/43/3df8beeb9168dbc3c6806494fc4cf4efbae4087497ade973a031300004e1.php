<?php

/* grid-products.phtml */
class __TwigTemplate_d9433df8beeb9168dbc3c6806494fc4cf4efbae4087497ade973a031300004e1 extends Twig_Template
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
        echo "<script>
    require([\"bluz.grid\"]);
</script>

<div class=\"clearfix\" data-spy=\"grid\" data-grid=\"";
        // line 5
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "getUid"), "html", null, true);
        echo "\" \">

";
        // line 7
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->partial("grid/filter-search.phtml", array("grid" => $_grid_)), "html", null, true);
        echo "



";
        // line 11
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->partial("grid/limit.phtml", array("grid" => $_grid_)), "html", null, true);
        echo "

    <table class=\"table grid\">
        <thead>
        <tr>
            <th width=\"120px\"><a href=\"";
        // line 16
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "order", array(0 => "products_id", 1 => null, 2 => "desc", 3 => false), "method"), "html", null, true);
        echo "\">Артикул</a></th>
            <th width=\"120px\"><a href=\"";
        // line 17
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "order", array(0 => "products_barcode"), "method"), "html", null, true);
        echo "\">Штрихкод</a></th>
            <th width=\"120px\"><a href=\"";
        // line 18
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "order", array(0 => "products_name"), "method"), "html", null, true);
        echo "\">Наименование</a></th>
            <th width=\"120px\"><a href=\"";
        // line 19
        if (isset($context["grid_order_4"])) { $_grid_order_4_ = $context["grid_order_4"]; } else { $_grid_order_4_ = null; }
        echo twig_escape_filter($this->env, $_grid_order_4_, "html", null, true);
        echo "\">Единица измерения</a></th>
            <th width=\"120px\"><a href=\"";
        // line 20
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "order", array(0 => "products_shopping_cart_price"), "method"), "html", null, true);
        echo "\">Розница</a></th>
            <th width=\"120px\"><a href=\"";
        // line 21
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "order", array(0 => "products_price_wholesale"), "method"), "html", null, true);
        echo "\">Опт</a></th>
            <th width=\"120px\"><a href=\"";
        // line 22
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "order", array(0 => "products_quantity"), "method"), "html", null, true);
        echo "\">Количество</a></th>
            <th width=\"120px\"></th>
        </tr>
        </thead>
        <tbody>
        ";
        // line 27
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($_grid_, "getData"));
        foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
            // line 28
            echo "        <tr>
            <td>";
            // line 29
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "products_id", array(), "array"), "html", null, true);
            echo "</td>
            <td>";
            // line 30
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "products_barcode", array(), "array"), "html", null, true);
            echo "</td>
            <td>";
            // line 31
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "products_name", array(), "array"), "html", null, true);
            echo "</td>
            <td>";
            // line 32
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "products_unit", array(), "array"), "html", null, true);
            echo "</td>
            <td>";
            // line 33
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "products_shopping_cart_price", array(), "array"), "html", null, true);
            echo "</td>
            <td>";
            // line 34
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "products_price_wholesale", array(), "array"), "html", null, true);
            echo "</td>
            <td>";
            // line 35
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "products_quantity", array(), "array"), "html", null, true);
            echo "</td>
            <td class=\"controls\">
                <a href=\"";
            // line 37
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->url("test", "crud", array("id" => $this->getAttribute($_row_, "id", array(), "array"))), "html", null, true);
            echo "\" class=\"btn btn-xs btn-primary dialog\" data-ajax-method=\"get\">
                    <i class=\"fa fa-pencil\"></i>
                </a>
                <a href=\"";
            // line 40
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->url("test", "crud", array("id" => $this->getAttribute($_row_, "id", array(), "array"))), "html", null, true);
            echo "\" class=\"confirm btn btn-xs btn-danger ajax\" data-ajax-method=\"delete\">
                    <i class=\"fa fa-times\"></i>
                </a>
            </td>
        </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 46
        echo "        </tbody>
    </table>




";
        // line 52
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->partial("grid/empty-rows.phtml", array("grid" => $_grid_)), "html", null, true);
        echo "
";
        // line 53
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->partial("grid/pagination.phtml", array("grid" => $_grid_)), "html", null, true);
        echo "
";
        // line 54
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->partial("grid/total.phtml", array("grid" => $_grid_)), "html", null, true);
        echo "



</div>

";
    }

    public function getTemplateName()
    {
        return "grid-products.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  169 => 54,  164 => 53,  159 => 52,  151 => 46,  138 => 40,  131 => 37,  125 => 35,  120 => 34,  115 => 33,  110 => 32,  105 => 31,  100 => 30,  95 => 29,  92 => 28,  87 => 27,  78 => 22,  73 => 21,  68 => 20,  63 => 19,  58 => 18,  53 => 17,  48 => 16,  39 => 11,  31 => 7,  25 => 5,  19 => 1,);
    }
}
