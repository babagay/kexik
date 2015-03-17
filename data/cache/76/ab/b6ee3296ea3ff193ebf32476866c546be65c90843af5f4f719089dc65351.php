<?php

/* categories.phtml */
class __TwigTemplate_76abb6ee3296ea3ff193ebf32476866c546be65c90843af5f4f719089dc65351 extends Twig_Template
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
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->dispatch("admin", "grid-categories"), "html", null, true);
        echo "

";
        // line 62
        echo "
";
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
        return array (  24 => 62,  19 => 1,);
    }
}
