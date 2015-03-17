<?php

/* grid/pagination.phtml */
class __TwigTemplate_1354572775f089b6218bcd250cfe847e17192c9e9d6d2c6c125a2d8910d12ea1 extends Twig_Template
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
        echo "
";
        // line 2
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        $context["grid_pages"] = $this->getAttribute($_grid_, "pages");
        // line 3
        echo "
";
        // line 4
        if (isset($context["products_name_filter"])) { $_products_name_filter_ = $context["products_name_filter"]; } else { $_products_name_filter_ = null; }
        if (($_products_name_filter_ != "")) {
            // line 5
            echo "    ";
            if (isset($context["products_name_filter"])) { $_products_name_filter_ = $context["products_name_filter"]; } else { $_products_name_filter_ = null; }
            $context["filter_str"] = ("/sql-filter-products_name/like-" . $_products_name_filter_);
        } else {
            // line 7
            echo "    ";
            $context["filter_str"] = "";
        }
        // line 9
        echo "
";
        // line 11
        if (isset($context["grid_pages"])) { $_grid_pages_ = $context["grid_pages"]; } else { $_grid_pages_ = null; }
        if (($_grid_pages_ > 1)) {
            // line 12
            echo "    <ul class=\"pagination pull-right\">
        ";
            // line 13
            if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
            if ($this->getAttribute($_grid_, "prev")) {
                // line 14
                echo "            <li><a href=\"";
                if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "prev"), "html", null, true);
                echo "\">&laquo;</a></li>
        ";
            } else {
                // line 16
                echo "            <li class=\"disabled\"><a href=\"#\" onclick=\"javascript:return false;\">&laquo;</a></li>
        ";
            }
            // line 18
            echo "
        ";
            // line 24
            echo "
        ";
            // line 26
            echo "        ";
            if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable(range(1, $this->getAttribute($_grid_, "pages")));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 27
                echo "            ";
                if (isset($context["i"])) { $_i_ = $context["i"]; } else { $_i_ = null; }
                $context["page"] = $_i_;
                // line 28
                echo "            <li ";
                if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
                if (isset($context["page"])) { $_page_ = $context["page"]; } else { $_page_ = null; }
                if (($this->getAttribute($_grid_, "getPage") == $_page_)) {
                    echo "class=\"active\" ";
                }
                echo ">

                <a href=\"";
                // line 30
                if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
                if (isset($context["page"])) { $_page_ = $context["page"]; } else { $_page_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "z_page", array("page" => $_page_)), "html", null, true);
                if (isset($context["filter_str"])) { $_filter_str_ = $context["filter_str"]; } else { $_filter_str_ = null; }
                echo twig_escape_filter($this->env, $_filter_str_, "html", null, true);
                echo "\">";
                if (isset($context["page"])) { $_page_ = $context["page"]; } else { $_page_ = null; }
                echo twig_escape_filter($this->env, $_page_, "html", null, true);
                echo "</a>

            </li>
            ";
                // line 38
                echo "        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 39
            echo "
        ";
            // line 41
            echo "        ";
            if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
            if ($this->getAttribute($_grid_, "next", array(), "method")) {
                // line 42
                echo "            <li><a href=\"";
                if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "next"), "html", null, true);
                echo "\">&raquo;</a></li>
        ";
            } else {
                // line 44
                echo "            <li class=\"disabled\"><a href=\"#\" onclick=\"javascript:return false;\">&raquo;</a></li>
        ";
            }
            // line 46
            echo "    </ul>
";
        } else {
            // line 48
            echo "
";
        }
    }

    public function getTemplateName()
    {
        return "grid/pagination.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  130 => 48,  126 => 46,  122 => 44,  115 => 42,  108 => 39,  102 => 38,  89 => 30,  79 => 28,  69 => 26,  66 => 24,  63 => 18,  59 => 16,  52 => 14,  49 => 13,  43 => 11,  40 => 9,  53 => 34,  44 => 30,  39 => 29,  36 => 7,  33 => 27,  24 => 17,  116 => 32,  105 => 26,  95 => 25,  85 => 24,  75 => 27,  65 => 22,  55 => 21,  50 => 19,  42 => 15,  37 => 13,  31 => 5,  28 => 4,  22 => 2,  178 => 67,  173 => 66,  168 => 65,  160 => 59,  147 => 53,  140 => 50,  134 => 48,  129 => 47,  124 => 46,  119 => 45,  114 => 44,  111 => 41,  106 => 42,  97 => 37,  92 => 36,  87 => 35,  82 => 34,  77 => 33,  68 => 28,  61 => 24,  46 => 12,  41 => 12,  34 => 12,  25 => 3,  19 => 1,);
    }
}
