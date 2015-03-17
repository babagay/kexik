<?php

/* grid-categories.phtml */
class __TwigTemplate_62d560c7c55a0c39efc2a6cb38a316011d8dfa346f599c1ae9a1ff7d810bbb71 extends Twig_Template
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
        echo "\" data-url=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("admin/grid-categories"), "html", null, true);
        echo "\">


    <form class=\"filter-form\" action=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/admin/grid-categories"), "html", null, true);
        echo "\">
        <div class=\"input-group-btn grid-filter-search\">
            <button type=\"button\" class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\">Поле для поиска <span class=\"caret\"></span></button>
            <ul class=\"dropdown-menu\">
                <li><a href=\"#\" data-filter=\"";
        // line 12
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "getUid"), "html", null, true);
        echo "-filter-categories_name\"> Название </a></li>
                <li><a href=\"#\" data-filter=\"";
        // line 13
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "getUid"), "html", null, true);
        echo "-filter-categories_seo_page_name\"> СЕО </a></li>
            </ul>
        </div><!-- /btn-group -->
        <input name=\"grid-filter-categories_name\" class=\"grid-filter-search-input\" value=\"\" type=\"hidden\"/>
        <input name=\"grid-filter-categories_seo_page_name\" class=\"grid-filter-search-input\" value=\"\" type=\"hidden\"/>

        <input name=\"search-like\" type=\"search\" class=\"form-control\" value=\"\" required />
    </form>


<div class=\"input-group\">
    <a href=\"";
        // line 24
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->url("admin", "categories-crud"), "html", null, true);
        echo "\" class=\"btn btn-primary pull-left dialog\" data-ajax-method=\"get\">Создать</a>
</div>
  
    ";
        // line 28
        echo "
";
        // line 29
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->partial("grid/limit.phtml", array("grid" => $_grid_)), "html", null, true);
        echo "

<table class=\"table grid\">
    <thead>
    <tr>
        <th width=\"120px\"><a href=\"";
        // line 34
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "order", array(0 => "categories_name", 1 => null, 2 => "desc", 3 => false), "method"), "html", null, true);
        echo "\">Название</a></th>
        <th width=\"120px\"><a href=\"";
        // line 35
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "order", array(0 => "sort_order"), "method"), "html", null, true);
        echo "\">Порядок</a></th>
        <th width=\"120px\"><a href=\"";
        // line 36
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "order", array(0 => "date_added"), "method"), "html", null, true);
        echo "\">Дата создания</a></th>
        <th width=\"120px\"><a href=\"";
        // line 37
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "order", array(0 => "categories_status"), "method"), "html", null, true);
        echo "\">Статус</a></th>
        <th width=\"120px\"><a href=\"";
        // line 38
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "order", array(0 => "categories_seo_page_name"), "method"), "html", null, true);
        echo "\">СЕО</a></th>
        <th width=\"120px\">Действия</th>
    </tr>
    </thead>
    <tbody>
    ";
        // line 43
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($_grid_, "getData"));
        foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
            // line 44
            echo "    <tr>
        <td>";
            // line 45
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "categories_name", array(), "array"), "html", null, true);
            echo "</td>
        <td>";
            // line 46
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "sort_order", array(), "array"), "html", null, true);
            echo "</td>
        <td>";
            // line 47
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "date_added", array(), "array"), "html", null, true);
            echo "</td>
        <td>";
            // line 48
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "categories_status", array(), "array"), "html", null, true);
            echo "</td>
        <td>";
            // line 49
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "categories_seo_page_name", array(), "array"), "html", null, true);
            echo "</td>
        <td class=\"controls\">
            <a href=\"";
            // line 51
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->url("admin", "categories-crud", array("categories_id" => $this->getAttribute($_row_, "categories_id", array(), "array"))), "html", null, true);
            echo "\" class=\"btn btn-xs btn-primary dialog\" data-ajax-method=\"get\">
                <i class=\"fa fa-pencil\"></i>
            </a>
            <a href=\"";
            // line 54
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->url("admin", "categories-crud", array("categories_id" => $this->getAttribute($_row_, "categories_id", array(), "array"))), "html", null, true);
            echo "\" class=\"confirm btn btn-xs btn-danger ajax\" data-ajax-method=\"delete\">
                <i class=\"fa fa-times\"></i>
            </a>
            ";
            // line 58
            echo "            ";
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            if (($this->getAttribute($_row_, "parent_id", array(), "array") == 0)) {
                // line 59
                echo "                <a href=\"";
                if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
                echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->url("admin", "grid-categories", array("sql-filter-parent_id" => $this->getAttribute($_row_, "categories_id", array(), "array"))), "html", null, true);
                echo "\" class=\"confirm filter btn btn-xs btn-default ajax\" data-ajax-method=\"get\">
                    <i class=\"fa fa-table\"></i>
                </a>
            ";
            } else {
                // line 63
                echo "                <a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->url("admin", "grid-categories"), "html", null, true);
                echo "\" class=\"confirm filter btn btn-xs btn-default ajax\" data-ajax-method=\"get\">
                    <i class=\"fa fa-table\"></i>
                </a>
            ";
            }
            // line 67
            echo "        </td>
    </tr>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 70
        echo "    </tbody>
</table>




";
        // line 76
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->partial("grid/empty-rows.phtml", array("grid" => $_grid_)), "html", null, true);
        echo "
";
        // line 77
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->partial("grid/pagination.phtml", array("grid" => $_grid_)), "html", null, true);
        echo "
";
        // line 78
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->partial("grid/total.phtml", array("grid" => $_grid_)), "html", null, true);
        echo "



</div>

";
    }

    public function getTemplateName()
    {
        return "grid-categories.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  203 => 78,  198 => 77,  193 => 76,  185 => 70,  177 => 67,  169 => 63,  160 => 59,  156 => 58,  149 => 54,  142 => 51,  136 => 49,  131 => 48,  126 => 47,  121 => 46,  116 => 45,  113 => 44,  108 => 43,  99 => 38,  94 => 37,  89 => 36,  84 => 35,  79 => 34,  70 => 29,  67 => 28,  61 => 24,  46 => 13,  41 => 12,  34 => 8,  25 => 5,  19 => 1,);
    }
}
