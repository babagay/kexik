<?php

/* grid/total.phtml */
class __TwigTemplate_f7f42530acabcedb3fbdbeb46462deb2a02c17711eba6e952d55f52158f4f2a2 extends Twig_Template
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
        // line 8
        echo "
";
        // line 9
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        $context["pages_total"] = $this->getAttribute($_grid_, "pages");
        // line 10
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        $context["reset"] = $this->getAttribute($_grid_, "reset");
        // line 11
        echo "


<ul class=\"pager pull-left\">
    <li>
        <a href=\"";
        // line 16
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "reset", array(), "method"), "html", null, true);
        echo "\" class=\"filter\">";
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Всего записей: "), "html", null, true);
        echo "
            <strong>";
        // line 17
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "total"), "html", null, true);
        echo "</strong> <!-- ";
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->_n("record", "records", $this->getAttribute($_grid_, "total")), "html", null, true);
        echo " -->
            ";
        // line 18
        if (isset($context["pages_total"])) { $_pages_total_ = $context["pages_total"]; } else { $_pages_total_ = null; }
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->_n((("Страниц: " . $_pages_total_) . " "), (("on " . $_pages_total_) . " pages"), $this->getAttribute($_grid_, "pages"), (("<strong> " . $this->getAttribute($_grid_, "pages")) . " </strong>")), "html", null, true);
        echo "</a>
    </li>
    <li>
    </li>
</ul>

";
    }

    public function getTemplateName()
    {
        return "grid/total.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  50 => 18,  42 => 17,  35 => 16,  28 => 11,  25 => 10,  22 => 9,  19 => 8,);
    }
}
