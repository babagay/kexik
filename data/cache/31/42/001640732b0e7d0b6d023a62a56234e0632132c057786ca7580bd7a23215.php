<?php

/* index.phtml */
class __TwigTemplate_3142001640732b0e7d0b6d023a62a56234e0632132c057786ca7580bd7a23215 extends Twig_Template
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
        if (isset($context["Layout"])) { $_Layout_ = $context["Layout"]; } else { $_Layout_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_Layout_, "test", array(), "method"), "html", null, true);
        echo "

            ";
        // line 33
        echo "            ";
        $context["foo"] = "138";
        // line 34
        echo "
            ";
        // line 35
        if (isset($context["request"])) { $_request_ = $context["request"]; } else { $_request_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_request_, "get", array(0 => 1), "method"), "html", null, true);
        echo "

            ";
        // line 37
        if (isset($context["view"])) { $_view_ = $context["view"]; } else { $_view_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_view_, "asd", array(0 => "set asd var"), "method"), "html", null, true);
        echo "


            ";
        // line 41
        echo "            ";
        if (isset($context["View"])) { $_View_ = $context["View"]; } else { $_View_ = null; }
        if ($this->getAttribute($_View_, "breadCrumbs")) {
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
        return array (  130 => 65,  128 => 64,  121 => 60,  117 => 58,  99 => 42,  95 => 41,  88 => 37,  82 => 35,  79 => 34,  76 => 33,  69 => 30,  62 => 22,  60 => 21,  57 => 20,  55 => 19,  51 => 17,  48 => 15,  44 => 13,  42 => 12,  37 => 9,  34 => 7,  30 => 6,  26 => 4,  24 => 3,  21 => 2,  19 => 1,);
    }
}
