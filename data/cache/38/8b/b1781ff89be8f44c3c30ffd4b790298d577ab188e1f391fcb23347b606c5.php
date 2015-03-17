<?php

/* ajax-html.phtml */
class __TwigTemplate_388bb1781ff89be8f44c3c30ffd4b790298d577ab188e1f391fcb23347b606c5 extends Twig_Template
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
        echo "<h2>Loaded by AJAX request</h2>
<p>Server time is <strong><?=\$time?></strong></p>";
    }

    public function getTemplateName()
    {
        return "ajax-html.phtml";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }
}
