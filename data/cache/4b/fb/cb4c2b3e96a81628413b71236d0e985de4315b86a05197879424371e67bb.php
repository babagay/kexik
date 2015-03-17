<?php

/* grid/filter-search.phtml */
class __TwigTemplate_4bfbcb4c2b3e96a81628413b71236d0e985de4315b86a05197879424371e67bb extends Twig_Template
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
        echo " ";
        // line 7
        echo "
<form class=\"filter-form\" action=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/admin/grid-products"), "html", null, true);
        echo "\">
    <div class=\"input-group-btn grid-filter-search\">
        <button type=\"button\" class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\">Поле для поиска <span class=\"caret\"></span></button>
        <ul class=\"dropdown-menu\">
            <li><a href=\"#\" data-filter=\"";
        // line 12
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "getUid"), "html", null, true);
        echo "-filter-products_id\"> Артикул </a></li>
            <li><a href=\"#\" data-filter=\"";
        // line 13
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "getUid"), "html", null, true);
        echo "-filter-products_barcode\"> Штрихкод </a></li>
            <li><a href=\"#\" data-filter=\"";
        // line 14
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "getUid"), "html", null, true);
        echo "-filter-products_name\"> Наименование </a></li>
        </ul>
    </div><!-- /btn-group -->
    <input name=\"grid-filter-products_id\" class=\"grid-filter-search-input\" value=\"\" type=\"hidden\"/>
    <input name=\"grid-filter-products_barcode\" class=\"grid-filter-search-input\" value=\"\" type=\"hidden\"/>
    <input name=\"grid-filter-products_name\" class=\"grid-filter-search-input\" value=\"\" type=\"hidden\"/>

    <input name=\"search-like\" type=\"search\" class=\"form-control\" value=\"\" required />
</form>

";
    }

    public function getTemplateName()
    {
        return "grid/filter-search.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  41 => 14,  36 => 13,  31 => 12,  24 => 8,  21 => 7,  19 => 1,);
    }
}
