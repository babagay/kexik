<?php

/* categories.phtml */
class __TwigTemplate_d9bfd3ac0028fb3f42c7115504ffa4da8cd2f684058c73c5c790f395b3acc800 extends Twig_Template
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
        if (isset($context["categories"])) { $_categories_ = $context["categories"]; } else { $_categories_ = null; }
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($_categories_);
        foreach ($context['_seq'] as $context["_key"] => $context["group"]) {
            // line 3
            echo "    <div>

        <input type=\"hidden\" name=\"categories_id\" value=\"";
            // line 5
            if (isset($context["group"])) { $_group_ = $context["group"]; } else { $_group_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_group_, "categories_id"), "html", null, true);
            echo "\">
        <input type=\"hidden\" name=\"categories_description\" value=\"";
            // line 6
            if (isset($context["group"])) { $_group_ = $context["group"]; } else { $_group_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_group_, "categories_description"), "html", null, true);
            echo "\">
        <input type=\"hidden\" name=\"categories_image\" value=\"";
            // line 7
            if (isset($context["group"])) { $_group_ = $context["group"]; } else { $_group_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_group_, "categories_image"), "html", null, true);
            echo "\">
        <input type=\"hidden\" name=\"parent_id\" value=\"";
            // line 8
            if (isset($context["group"])) { $_group_ = $context["group"]; } else { $_group_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_group_, "parent_id"), "html", null, true);
            echo "\">
        <input type=\"hidden\" name=\"sort_order\" value=\"";
            // line 9
            if (isset($context["group"])) { $_group_ = $context["group"]; } else { $_group_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_group_, "sort_order"), "html", null, true);
            echo "\">
        <input type=\"hidden\" name=\"date_added\" value=\"";
            // line 10
            if (isset($context["group"])) { $_group_ = $context["group"]; } else { $_group_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_group_, "date_added"), "html", null, true);
            echo "\">
        <input type=\"hidden\" name=\"categories_status\" value=\"";
            // line 11
            if (isset($context["group"])) { $_group_ = $context["group"]; } else { $_group_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_group_, "categories_status"), "html", null, true);
            echo "\">
        <input type=\"hidden\" name=\"categories_level\" value=\"";
            // line 12
            if (isset($context["group"])) { $_group_ = $context["group"]; } else { $_group_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_group_, "categories_level"), "html", null, true);
            echo "\">
        <input type=\"hidden\" name=\"categories_seo_page_name\" value=\"";
            // line 13
            if (isset($context["group"])) { $_group_ = $context["group"]; } else { $_group_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_group_, "categories_seo_page_name"), "html", null, true);
            echo "\">

        ";
            // line 15
            if (isset($context["category_parent"])) { $_category_parent_ = $context["category_parent"]; } else { $_category_parent_ = null; }
            if ($_category_parent_) {
                echo " ";
                if (isset($context["category_parent"])) { $_category_parent_ = $context["category_parent"]; } else { $_category_parent_ = null; }
                $context["parent"] = ("/" . $_category_parent_);
                // line 16
                echo "        ";
            } else {
                echo " ";
                $context["parent"] = "";
                echo " ";
            }
            // line 17
            echo "
        ";
            // line 18
            if (isset($context["group"])) { $_group_ = $context["group"]; } else { $_group_ = null; }
            if ($this->getAttribute($_group_, "categories_seo_page_name")) {
                // line 19
                echo "            ";
                if (isset($context["group"])) { $_group_ = $context["group"]; } else { $_group_ = null; }
                if (isset($context["parent"])) { $_parent_ = $context["parent"]; } else { $_parent_ = null; }
                echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref($this->getAttribute($_group_, "categories_name"), array(0 => ("каталог" . $_parent_), 1 => $this->getAttribute($_group_, "categories_seo_page_name"))), "html", null, true);
                echo "
        ";
            } else {
                // line 21
                echo "          ";
                if (isset($context["group"])) { $_group_ = $context["group"]; } else { $_group_ = null; }
                if (isset($context["parent"])) { $_parent_ = $context["parent"]; } else { $_parent_ = null; }
                echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref($this->getAttribute($_group_, "categories_name"), array(0 => ("каталог" . $_parent_), 1 => $this->getAttribute($_group_, "categories_id"))), "html", null, true);
                echo "
        ";
            }
            // line 23
            echo "

    </div>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['group'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
    }

    public function getTemplateName()
    {
        return "categories.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  112 => 23,  104 => 21,  96 => 19,  93 => 18,  90 => 17,  83 => 16,  77 => 15,  71 => 13,  66 => 12,  61 => 11,  56 => 10,  51 => 9,  46 => 8,  41 => 7,  36 => 6,  31 => 5,  27 => 3,  22 => 2,  19 => 1,);
    }
}
