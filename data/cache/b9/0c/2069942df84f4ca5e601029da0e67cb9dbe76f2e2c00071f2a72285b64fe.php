<?php

/* backend.phtml */
class __TwigTemplate_b90c2069942df84f4ca5e601029da0e67cb9dbe76f2e2c00071f2a72285b64fe extends Twig_Template
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
        // line 2
        $this->env->loadTemplate("partial/twig_header.phtml")->display($context);
        // line 3
        echo "
";
        // line 5
        echo "


";
        // line 9
        if ($this->env->getExtension('zoqa_twig')->user("acl", "system", "Management")) {
            // line 10
            echo "    ";
            $this->env->loadTemplate("partial/twig_nav-bar.phtml")->display($context);
        } else {
            // line 12
            echo "    ";
        }
        // line 14
        echo "<div class=\"content-wrapper _layout-small\">
    ";
        // line 16
        echo "
    <div id=\"content\" class=\"backend\">
        <div class=\"content-text\">

            ";
        // line 39
        echo "
            <!--form name=\"state\">
                <input type=\"hidden\" name=\"filter-categories_id\" value=\"";
        // line 41
        if (isset($context["filter_categories_id"])) { $_filter_categories_id_ = $context["filter_categories_id"]; } else { $_filter_categories_id_ = null; }
        echo twig_escape_filter($this->env, $_filter_categories_id_, "html", null, true);
        echo "\">
                <input type=\"hidden\" name=\"filter-manufacturers_id\" value=\"eq-\">
                <input type=\"hidden\" name=\"order\" value=\"asc-products_shopping_cart_price\">
                <input type=\"hidden\" name=\"default_order_field\" value=\"products_shopping_cart_price\">
                <input type=\"hidden\" name=\"page\" value=\"1\">
            </form-->

            <div id=\"content_box\">

                ";
        // line 50
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->getContent(), "html", null, true);
        echo "

            </div>

        </div>
    </div>



</div>

";
        // line 61
        $this->env->loadTemplate("partial/twig_footer.phtml")->display($context);
        // line 62
        echo "
";
    }

    public function getTemplateName()
    {
        return "backend.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  80 => 62,  78 => 61,  64 => 50,  51 => 41,  47 => 39,  41 => 16,  38 => 14,  35 => 12,  31 => 10,  29 => 9,  24 => 5,  21 => 3,  19 => 2,);
    }
}
