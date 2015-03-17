<?php

/* front_end.phtml */
class __TwigTemplate_baeacbc03bb412d285a2b850ad9c31e00e7f5ba87ba55943ce75eef6b2ab899a extends Twig_Template
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
        $this->env->loadTemplate("partial/twig_header_front.phtml")->display($context);
        // line 3
        echo "
";
        // line 4
        $this->env->loadTemplate("partial/twig_aside.phtml")->display($context);
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
        echo "    <div class=\"content-wrapper _layout-small\">
        ";
        // line 15
        $this->env->loadTemplate("partial/twig_aside_right.phtml")->display($context);
        // line 16
        echo "
        <div id=\"content\">
            <div class=\"content-text\">
                ";
        // line 24
        echo "
                ";
        // line 25
        if ($this->env->getExtension('zoqa_twig')->breadCrumbs()) {
            // line 26
            echo "                <ul class=\"breadcrumb\">
                    ";
            // line 27
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->env->getExtension('zoqa_twig')->breadCrumbs());
            $context['loop'] = array(
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            );
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["crumb"]) {
                // line 28
                echo "                    ";
                if (isset($context["loop"])) { $_loop_ = $context["loop"]; } else { $_loop_ = null; }
                if (($this->getAttribute($_loop_, "revindex") == 1)) {
                    // line 29
                    echo "                    <li class=\"active\">";
                    if (isset($context["crumb"])) { $_crumb_ = $context["crumb"]; } else { $_crumb_ = null; }
                    echo twig_escape_filter($this->env, $_crumb_, "html", null, true);
                    echo "</li>
                    ";
                } else {
                    // line 31
                    echo "                    <li> ";
                    if (isset($context["crumb"])) { $_crumb_ = $context["crumb"]; } else { $_crumb_ = null; }
                    echo twig_escape_filter($this->env, $_crumb_, "html", null, true);
                    echo " </li>
                    ";
                }
                // line 33
                echo "
                    ";
                // line 34
                if (isset($context["loop"])) { $_loop_ = $context["loop"]; } else { $_loop_ = null; }
                if (($this->getAttribute($_loop_, "revindex") != 1)) {
                    // line 35
                    echo "                    ";
                    if (isset($context["loop"])) { $_loop_ = $context["loop"]; } else { $_loop_ = null; }
                    if (($this->getAttribute($_loop_, "length") > 1)) {
                        // line 36
                        echo "                    ";
                        // line 37
                        echo "                    ";
                    }
                    // line 38
                    echo "                    ";
                }
                // line 39
                echo "                    ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['crumb'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 40
            echo "                </ul>
                ";
        }
        // line 42
        echo "
                <form name=\"state\">
                    <input type=\"hidden\" name=\"filter-categories_id\" value=\"";
        // line 44
        if (isset($context["filter_categories_id"])) { $_filter_categories_id_ = $context["filter_categories_id"]; } else { $_filter_categories_id_ = null; }
        echo twig_escape_filter($this->env, $_filter_categories_id_, "html", null, true);
        echo "\">
                    <input type=\"hidden\" name=\"filter-manufacturers_id\" value=\"eq-\">
                    <input type=\"hidden\" name=\"order\" value=\"asc-products_shopping_cart_price\">
                    <input type=\"hidden\" name=\"default_order_field\" value=\"products_shopping_cart_price\">
                    <input type=\"hidden\" name=\"page\" value=\"1\">
                </form>

                <div id=\"content_box\">

                ";
        // line 53
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->getContent(), "html", null, true);
        echo "

                </div>

            </div>
        </div>

        ";
        // line 61
        echo "        ";
        // line 62
        echo "        ";
        // line 74
        echo "
    </div>

";
        // line 77
        $this->env->loadTemplate("partial/twig_footer.phtml")->display($context);
        // line 78
        echo "


";
    }

    public function getTemplateName()
    {
        return "front_end.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  167 => 78,  165 => 77,  160 => 74,  158 => 62,  156 => 61,  146 => 53,  133 => 44,  129 => 42,  125 => 40,  111 => 39,  108 => 38,  105 => 37,  103 => 36,  99 => 35,  96 => 34,  93 => 33,  86 => 31,  79 => 29,  75 => 28,  58 => 27,  55 => 26,  53 => 25,  50 => 24,  45 => 16,  43 => 15,  40 => 14,  37 => 12,  33 => 10,  31 => 9,  26 => 5,  24 => 4,  21 => 3,  19 => 2,);
    }
}
