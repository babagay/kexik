<?php

/* index.phtml */
class __TwigTemplate_572340206c65b1e43125e5d4a2f0b0f4b641b9e7481f5df8305e620e26ef661f extends Twig_Template
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
        // line 12
        echo "<div class=\"modal-header\">
    <h3 class=\"modal-title\">";
        // line 13
        if (isset($context["title"])) { $_title_ = $context["title"]; } else { $_title_ = null; }
        echo twig_escape_filter($this->env, $_title_, "html", null, true);
        echo "</h3>
</div>

<div class=\"modal-body\">

    <p>";
        // line 18
        if (isset($context["description"])) { $_description_ = $context["description"]; } else { $_description_ = null; }
        echo twig_escape_filter($this->env, $_description_, "html", null, true);
        echo "</p>

    ";
        // line 20
        if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
        if ($_message_) {
            // line 21
            echo "         <p>";
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            echo twig_escape_filter($this->env, $_message_, "html", null, true);
            echo "</p>
    ";
        }
        // line 23
        echo "</div>

";
    }

    public function getTemplateName()
    {
        return "index.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  47 => 23,  40 => 21,  37 => 20,  31 => 18,  22 => 13,  19 => 12,);
    }
}
