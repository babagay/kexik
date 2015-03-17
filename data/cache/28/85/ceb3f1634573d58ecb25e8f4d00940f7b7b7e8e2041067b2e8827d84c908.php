<?php

/* index.phtml */
class __TwigTemplate_2885ceb3f1634573d58ecb25e8f4d00940f7b7b7e8e2041067b2e8827d84c908 extends Twig_Template
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
        $this->env->loadTemplate("partial/twig_header_front.phtml")->display($context);
        // line 2
        echo "
";
        // line 3
        $this->env->loadTemplate("partial/twig_aside.phtml")->display($context);
        // line 4
        echo "

";
        // line 6
        if ($this->env->getExtension('zoqa_twig')->user()) {
            echo " 
    ";
            // line 7
            $this->env->loadTemplate("partial/twig_nav-bar.phtml")->display($context);
        }
        // line 9
        echo "


";
        // line 12
        if ($this->env->getExtension('zoqa_twig')->user("acl", "system", "Management")) {
            // line 13
            echo "    ";
            $this->env->loadTemplate("partial/twig_nav-bar.phtml")->display($context);
        } else {
            // line 15
            echo "    ";
        }
        // line 17
        echo " <div class=\"content-wrapper\">
 
    ";
        // line 19
        $this->env->loadTemplate("partial/twig_aside_right.phtml")->display($context);
        // line 20
        echo "    
    ";
        // line 21
        $this->env->loadTemplate("partial/twig_content.phtml")->display($context);
        // line 22
        echo "    
    
  <!--  <div class=\"container\">
        <div class=\"row\"> 

            ";
        // line 30
        echo "            ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["Layout"]) ? $context["Layout"] : null), "test", array(), "method"), "html", null, true);
        echo "

            ";
        // line 33
        echo "            ";
        $context["foo"] = "138";
        // line 34
        echo "
            ";
        // line 35
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["request"]) ? $context["request"] : null), "get", array(0 => 1), "method"), "html", null, true);
        echo "

            ";
        // line 37
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["view"]) ? $context["view"] : null), "asd", array(0 => "set asd var"), "method"), "html", null, true);
        echo "


            ";
        // line 41
        echo "            ";
        if ($this->getAttribute((isset($context["View"]) ? $context["View"] : null), "breadCrumbs")) {
            // line 42
            echo "            <ul class=\"breadcrumb\">
                <li>
                    <a href=\"/\" class=\"bluz-tooltip\" data-placement=\"bottom\" data-original-title=\"{ __('Back to homepage') }\">
                        <i class=\"fa fa-home\"></i>
                        {  __('Home')  } 
                    </a>
                </li>
                {for crump in View.breadCrumbs}
                
                    <li>
                        {crump}                        
                    </li>
                
                {endfor}
            </ul>
            ";
        }
        // line 58
        echo "        </div>
        
        ";
        // line 60
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->getContent(), "html", null, true);
        echo "-->  


    </div>
";
        // line 64
        $this->env->loadTemplate("partial/twig_footer.phtml")->display($context);
        // line 65
        echo "</div>";
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
        return array (  126 => 65,  124 => 64,  117 => 60,  113 => 58,  95 => 42,  92 => 41,  86 => 37,  81 => 35,  78 => 34,  75 => 33,  69 => 30,  62 => 22,  60 => 21,  57 => 20,  55 => 19,  51 => 17,  48 => 15,  44 => 13,  42 => 12,  37 => 9,  34 => 7,  30 => 6,  26 => 4,  24 => 3,  21 => 2,  19 => 1,);
    }
}
