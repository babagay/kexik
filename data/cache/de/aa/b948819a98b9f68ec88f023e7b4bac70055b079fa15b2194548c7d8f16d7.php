<?php

/* Base.phtml */
class __TwigTemplate_deaab948819a98b9f68ec88f023e7b4bac70055b079fa15b2194548c7d8f16d7 extends Twig_Template
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
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->dispatch("admin", "grid-products"), "html", null, true);
    }

    public function getTemplateName()
    {
        return "Base.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }
}
