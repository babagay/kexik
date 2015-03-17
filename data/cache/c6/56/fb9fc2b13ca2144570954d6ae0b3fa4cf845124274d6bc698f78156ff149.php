<?php

/* grid-array.phtml */
class __TwigTemplate_c656fb9fc2b13ca2144570954d6ae0b3fa4cf845124274d6bc698f78156ff149 extends Twig_Template
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
        // line 6
        echo "
<div class=\"clearfix\" data-spy=\"grid\" data-grid=\"";
        // line 7
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "getUid"), "html", null, true);
        echo "\">
    <script>
        // use data-spy=\"grid\" for use AJAX for reload grid
        require([\"bluz.grid\"]);
    </script>
    <p>
        Filters:<br/>
        <a href=\"";
        // line 14
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "filter", array(0 => "id", 1 => "gt", 2 => 3), "method"), "html", null, true);
        echo "\">Id &gt; 3</a> <br/>
        <a href=\"";
        // line 15
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "filter", array(0 => "id", 1 => "lt", 2 => 9), "method"), "html", null, true);
        echo "\">Id &lt; 9</a> <br/>
        <a href=\"";
        // line 16
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "filter", array(0 => "id", 1 => "ne", 2 => 5, 3 => false), "method"), "html", null, true);
        echo "\">AND Id != 5</a>
    </p>

    ";
        // line 19
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->partial("grid/total.phtml", array("grid" => $_grid_)), "html", null, true);
        echo "
    ";
        // line 20
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->partial("grid/limit.phtml", array("grid" => $_grid_)), "html", null, true);
        echo "

    ";
        // line 37
        echo "
    ";
        // line 39
        echo "    ";
        // line 40
        echo "
    ";
        // line 42
        echo "    ";
        // line 46
        echo "
    ";
        // line 48
        echo "    ";
        // line 49
        echo "
    <table class=\"table grid\">
        <thead>
            <tr>
                <th width=\"40px\"><a href=\"";
        // line 53
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "order", array(0 => "id", 1 => null, 2 => "desc", 3 => false), "method"), "html", null, true);
        echo "\">Id</a></th>
                <th><a href=\"";
        // line 54
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "order", array(0 => "name"), "method"), "html", null, true);
        echo "\">Name</a></th>
                <th><a href=\"";
        // line 55
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "order", array(0 => "email"), "method"), "html", null, true);
        echo "\">Email</a></th>
            </tr>
        </thead>
        <tbody>
            ";
        // line 60
        echo "            ";
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($_grid_, "getData"));
        foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
            // line 61
            echo "            <tr>
                <td>";
            // line 62
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "id", array(), "array"), "html", null, true);
            echo "</td>
                <td>";
            // line 63
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "name"), "html", null, true);
            echo "</td>
                <td>";
            // line 64
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "email"), "html", null, true);
            echo "</td>
            </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 67
        echo "        </tbody>
    </table>

    ";
        // line 70
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->partial("grid/empty-rows.phtml", array("grid" => $_grid_)), "html", null, true);
        echo "
    ";
        // line 71
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->partial("grid/pagination.phtml", array("grid" => $_grid_)), "html", null, true);
        echo "
    ";
        // line 72
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->partial("grid/total.phtml", array("grid" => $_grid_)), "html", null, true);
        echo "

    ";
        // line 79
        echo "</div>";
    }

    public function getTemplateName()
    {
        return "grid-array.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  150 => 79,  144 => 72,  139 => 71,  134 => 70,  129 => 67,  119 => 64,  114 => 63,  109 => 62,  106 => 61,  100 => 60,  92 => 55,  87 => 54,  82 => 53,  76 => 49,  74 => 48,  71 => 46,  69 => 42,  66 => 40,  64 => 39,  61 => 37,  55 => 20,  50 => 19,  43 => 16,  38 => 15,  33 => 14,  22 => 7,  19 => 6,);
    }
}
