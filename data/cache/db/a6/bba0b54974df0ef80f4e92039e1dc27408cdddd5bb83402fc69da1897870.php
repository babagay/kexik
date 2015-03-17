<?php

/* grid/empty-rows.phtml */
class __TwigTemplate_dba6bba0b54974df0ef80f4e92039e1dc27408cdddd5bb83402fc69da1897870 extends Twig_Template
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
        // line 11
        echo "


";
        // line 17
        echo "

";
        // line 24
        echo "


";
        // line 27
        if (isset($context["emptyRows"])) { $_emptyRows_ = $context["emptyRows"]; } else { $_emptyRows_ = null; }
        if (($_emptyRows_ > 0)) {
            // line 28
            echo "<table class=\"table grid\">
    ";
            // line 29
            if (isset($context["emptyRows"])) { $_emptyRows_ = $context["emptyRows"]; } else { $_emptyRows_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable(range(1, $_emptyRows_));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 30
                echo "    <tr>
        <td>&nbsp;</td>
    </tr>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 34
            echo "</table>
";
        }
    }

    public function getTemplateName()
    {
        return "grid/empty-rows.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 34,  44 => 30,  39 => 29,  36 => 28,  33 => 27,  24 => 17,  116 => 32,  105 => 26,  95 => 25,  85 => 24,  75 => 23,  65 => 22,  55 => 21,  50 => 19,  42 => 15,  37 => 13,  31 => 11,  28 => 24,  22 => 6,  178 => 67,  173 => 66,  168 => 65,  160 => 59,  147 => 53,  140 => 50,  134 => 48,  129 => 47,  124 => 46,  119 => 45,  114 => 44,  111 => 43,  106 => 42,  97 => 37,  92 => 36,  87 => 35,  82 => 34,  77 => 33,  68 => 28,  61 => 24,  46 => 17,  41 => 12,  34 => 12,  25 => 9,  19 => 11,);
    }
}
