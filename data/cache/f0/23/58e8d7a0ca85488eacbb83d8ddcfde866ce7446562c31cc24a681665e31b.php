<?php

/* grid/limit.phtml */
class __TwigTemplate_f02358e8d7a0ca85488eacbb83d8ddcfde866ce7446562c31cc24a681665e31b extends Twig_Template
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
        // line 3
        echo "
";
        // line 6
        echo "
";
        // line 9
        echo "
";
        // line 10
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        $context["grid_limit"] = $this->getAttribute($_grid_, "getLimit");
        // line 11
        echo "
";
        // line 12
        if (isset($context["products_name_filter"])) { $_products_name_filter_ = $context["products_name_filter"]; } else { $_products_name_filter_ = null; }
        if (($_products_name_filter_ != "")) {
            // line 13
            echo "    ";
            if (isset($context["products_name_filter"])) { $_products_name_filter_ = $context["products_name_filter"]; } else { $_products_name_filter_ = null; }
            $context["filter_str_pgn"] = ("/sql-filter-products_name/like-" . $_products_name_filter_);
        } else {
            // line 15
            echo "    ";
            $context["filter_str_pgn"] = "";
        }
        // line 17
        echo "
<ul class=\"pagination pagination-small pull-right\">
    <li class=\"disabled\"><a href=\"#\">";
        // line 19
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Количество строк"), "html", null, true);
        echo "</a></li>

    <li ";
        // line 21
        if (isset($context["grid_limit"])) { $_grid_limit_ = $context["grid_limit"]; } else { $_grid_limit_ = null; }
        if (($_grid_limit_ == 3)) {
            echo " class=\"active\" ";
        }
        echo "><a href=\"";
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "z_limit", array("key" => 3)), "html", null, true);
        echo "\">3</a></li>
    <li ";
        // line 22
        if (isset($context["grid_limit"])) { $_grid_limit_ = $context["grid_limit"]; } else { $_grid_limit_ = null; }
        if (($_grid_limit_ == 5)) {
            echo " class=\"active\" ";
        }
        echo "><a href=\"";
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "z_limit", array("key" => 5)), "html", null, true);
        echo "\">5</a></li>
    <li ";
        // line 23
        if (isset($context["grid_limit"])) { $_grid_limit_ = $context["grid_limit"]; } else { $_grid_limit_ = null; }
        if (($_grid_limit_ == 25)) {
            echo " class=\"active\" ";
        }
        echo "><a href=\"";
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "z_limit", array("key" => 25)), "html", null, true);
        echo "\">25</a></li>
    <li ";
        // line 24
        if (isset($context["grid_limit"])) { $_grid_limit_ = $context["grid_limit"]; } else { $_grid_limit_ = null; }
        if (($_grid_limit_ == 50)) {
            echo " class=\"active\" ";
        }
        echo "><a href=\"";
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "z_limit", array(0 => 50), "method"), "html", null, true);
        echo "\">50</a></li>
    <li ";
        // line 25
        if (isset($context["grid_limit"])) { $_grid_limit_ = $context["grid_limit"]; } else { $_grid_limit_ = null; }
        if (($_grid_limit_ == 100)) {
            echo " class=\"active\" ";
        }
        echo "><a href=\"";
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "z_limit", array(0 => 100), "method"), "html", null, true);
        echo "\">100</a></li>
    <li ";
        // line 26
        if (isset($context["grid_limit"])) { $_grid_limit_ = $context["grid_limit"]; } else { $_grid_limit_ = null; }
        if (($_grid_limit_ == 500)) {
            echo " class=\"active\" ";
        }
        echo "><a href=\"";
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "z_limit", array(0 => 500), "method"), "html", null, true);
        echo "\">500</a></li>

    ";
        // line 32
        echo "</ul>
";
    }

    public function getTemplateName()
    {
        return "grid/limit.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  116 => 32,  105 => 26,  95 => 25,  85 => 24,  75 => 23,  65 => 22,  55 => 21,  50 => 19,  42 => 15,  37 => 13,  31 => 11,  28 => 10,  22 => 6,  178 => 67,  173 => 66,  168 => 65,  160 => 59,  147 => 53,  140 => 50,  134 => 48,  129 => 47,  124 => 46,  119 => 45,  114 => 44,  111 => 43,  106 => 42,  97 => 37,  92 => 36,  87 => 35,  82 => 34,  77 => 33,  68 => 28,  61 => 24,  46 => 17,  41 => 12,  34 => 12,  25 => 9,  19 => 3,);
    }
}
