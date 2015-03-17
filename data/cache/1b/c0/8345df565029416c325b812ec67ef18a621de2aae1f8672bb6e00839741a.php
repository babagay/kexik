<?php

/* small.phtml */
class __TwigTemplate_1bc08345df565029416c325b812ec67ef18a621de2aae1f8672bb6e00839741a extends Twig_Template
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
        $this->env->loadTemplate("partial/twig_header_small.phtml")->display($context);
        // line 3
        if ($this->env->getExtension('zoqa_twig')->user("acl", "system", "Management")) {
            // line 4
            echo "    ";
            $this->env->loadTemplate("partial/twig_nav-bar.phtml")->display($context);
        } else {
            // line 6
            echo "    ";
        }
        // line 8
        echo "


    <div class=\"container layout-small\">
        <div class=\"container\">
           
            ";
        // line 15
        echo "\t\t\t";
        // line 16
        echo "            ";
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->getContent(), "html", null, true);
        echo "
            
        </div>
    </div>


";
        // line 22
        $this->env->loadTemplate("partial/twig_footer.phtml")->display($context);
    }

    public function getTemplateName()
    {
        return "small.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  50 => 22,  40 => 16,  38 => 15,  30 => 8,  27 => 6,  23 => 4,  21 => 3,  19 => 2,);
    }
}
