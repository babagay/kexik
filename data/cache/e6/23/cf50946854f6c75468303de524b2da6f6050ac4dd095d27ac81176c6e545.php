<?php

/* partial/twig_aside.phtml */
class __TwigTemplate_e623cf50946854f6c75468303de524b2da6f6050ac4dd095d27ac81176c6e545 extends Twig_Template
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
        echo "
 <aside id=\"main-nav\">
            <nav>
                ";
        // line 5
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->widget("index", "mainmenu"), "html", null, true);
        echo "
                ";
        // line 148
        echo "            </nav>
        </aside>


";
    }

    public function getTemplateName()
    {
        return "partial/twig_aside.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  28 => 148,  181 => 95,  174 => 91,  169 => 88,  167 => 87,  165 => 86,  162 => 85,  139 => 64,  128 => 58,  123 => 56,  109 => 51,  105 => 50,  99 => 47,  87 => 41,  83 => 40,  79 => 39,  71 => 37,  64 => 33,  47 => 28,  43 => 27,  39 => 26,  35 => 25,  29 => 22,  25 => 21,  126 => 65,  124 => 64,  117 => 53,  113 => 52,  95 => 46,  92 => 41,  86 => 37,  81 => 35,  78 => 34,  75 => 38,  69 => 30,  62 => 22,  60 => 32,  57 => 20,  55 => 30,  51 => 29,  48 => 15,  44 => 13,  42 => 12,  37 => 9,  34 => 7,  30 => 6,  26 => 4,  24 => 5,  21 => 2,  19 => 2,);
    }
}
