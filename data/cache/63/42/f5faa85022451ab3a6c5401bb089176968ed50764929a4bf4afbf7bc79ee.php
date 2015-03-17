<?php

/* partial/twig_aside.phtml */
class __TwigTemplate_6342f5faa85022451ab3a6c5401bb089176968ed50764929a4bf4afbf7bc79ee extends Twig_Template
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
        return array (  28 => 148,  180 => 95,  173 => 91,  168 => 88,  166 => 86,  163 => 85,  140 => 64,  124 => 56,  118 => 53,  114 => 52,  110 => 51,  106 => 50,  100 => 47,  88 => 41,  84 => 40,  80 => 39,  76 => 38,  72 => 37,  65 => 33,  61 => 32,  56 => 30,  52 => 29,  48 => 28,  44 => 27,  36 => 25,  30 => 22,  25 => 21,  167 => 77,  165 => 76,  160 => 73,  158 => 61,  156 => 60,  146 => 52,  134 => 44,  129 => 58,  125 => 40,  111 => 39,  108 => 38,  105 => 37,  103 => 36,  99 => 35,  96 => 46,  93 => 33,  86 => 31,  79 => 29,  75 => 28,  58 => 27,  55 => 26,  53 => 25,  50 => 24,  45 => 16,  43 => 15,  40 => 26,  37 => 12,  33 => 10,  31 => 9,  26 => 5,  24 => 5,  21 => 3,  19 => 2,);
    }
}
