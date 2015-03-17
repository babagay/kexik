<?php

/* partial/twig_header_front.phtml */
class __TwigTemplate_8ea0e28636cacd7099850fcb4d0454790423e60fdadd768c7cff323af9d2c39f extends Twig_Template
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
        // line 17
        echo "<!DOCTYPE html>
<html dir=\"ltr\" lang=\"en-US\">
<head>
    <meta charset=\"UTF-8\"/>
    <title>";
        // line 21
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->title((isset($context["zoqa_title"]) ? $context["zoqa_title"] : null)), "html", null, true);
        echo "</title>
    <base href=\"";
        // line 22
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl(), "html", null, true);
        echo "\" />
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
    <link rel=\"profile\" href=\"http://gmpg.org/xfn/11\"/>
    <link rel=\"stylesheet\" href=\"";
        // line 25
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/css/bootstrap.css"), "html", null, true);
        echo "\" />
    <link rel=\"stylesheet\" href=\"";
        // line 26
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/css/price.css"), "html", null, true);
        echo "\" />
    <link rel=\"stylesheet\" href=\"";
        // line 27
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/css/font-awesome.css"), "html", null, true);
        echo "\" />
    <link rel=\"stylesheet\" href=\"";
        // line 28
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/css/styles.css"), "html", null, true);
        echo "\" />
    <link rel=\"stylesheet\" href=\"";
        // line 29
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/css/print.css"), "html", null, true);
        echo "\" media=\"print\" />
    <link rel=\"shortcut icon\"  href=\"";
        // line 30
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("favicon.png"), "html", null, true);
        echo "\" type=\"image/png\" />

    <script src=\"";
        // line 32
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/require.js"), "html", null, true);
        echo "\"></script>
    <script src=\"";
        // line 33
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/config.js"), "html", null, true);
        echo "\"></script>

    <!-----------------Юля-+++------------------------>

    <script src=\"";
        // line 37
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/scripts/script.js"), "html", null, true);
        echo "\"></script>
    <script src=\"";
        // line 38
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/scripts/jquery.min.js"), "html", null, true);
        echo "\"></script>
    <script src=\"";
        // line 39
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/scripts/custom_animations.js"), "html", null, true);
        echo "\"></script>
    <script src=\"";
        // line 40
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/scripts/custom_easing.js"), "html", null, true);
        echo "\"></script>
    <script src=\"";
        // line 41
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/scripts/jSlider.js"), "html", null, true);
        echo "\"></script>
    <!------------------------------------------>

    <!-- For IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src=\"";
        // line 46
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/vendor/html5shiv.js"), "html", null, true);
        echo "\"></script>
    <script src=\"";
        // line 47
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/vendor/html5shiv-printshiv.js"), "html", null, true);
        echo "\"></script>
    <![endif]-->

    ";
        // line 50
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->headScript(), "html", null, true);
        echo "
    ";
        // line 51
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->headStyle(), "html", null, true);
        echo "
    ";
        // line 52
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->link(), "html", null, true);
        echo "
    ";
        // line 53
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->meta(), "html", null, true);
        echo "


    ";
        // line 56
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->trigger("header"), "html", null, true);
        echo "
</head>
<body data-spy=\"scroll\" data-bluz-module=\"";
        // line 58
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->module(), "html", null, true);
        echo "\" data-bluz-controller=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->controller(), "html", null, true);
        echo "\">
<!-----------------Юля------------------------->
<div id=\"wrapper\">
    <header>
        <div class=\"wrapper-header\">
            <div class=\"wrapper-header-text\">
                <h1 class=\"logo\"><a href=\"";
        // line 64
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/"), "html", null, true);
        echo "\"><img src=\"public/images/logo.png\"></a></h1>
                <div class=\"row\">
                    <span class=\"phone\">(068) 611-11-49 </span>
                    <span class=\"phone\">(050) 403-11-33 </span>
                    <span class=\"phone\">(093) 123-51-40 </span>
                </div>
                <div class=\"search-wrapper\">
                    <div class=\"search-wrapper-for-substrate\">
                        <div class=\"search-substrate\"> <input type=\"text\"> </div>
                    </div>
                    <button class=\"search\">Искать</button>
                </div>
            </div>
        </div>
    </header>




    <!------------------------------------------>
    ";
        // line 85
        echo "
    ";
        // line 86
        if ($this->env->getExtension('zoqa_twig')->hasMessages()) {
            // line 87
            echo "    ";
            // line 88
            echo "
    <script>
        require(['bluz.notify'], function(notify) {
            notify.set(";
            // line 91
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->getMessages(), "html", null, true);
            echo " );
        });
    </script>
    ";
        }
        // line 95
        echo "
";
    }

    public function getTemplateName()
    {
        return "partial/twig_header_front.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  181 => 95,  174 => 91,  169 => 88,  167 => 87,  165 => 86,  162 => 85,  139 => 64,  128 => 58,  123 => 56,  109 => 51,  105 => 50,  99 => 47,  87 => 41,  83 => 40,  79 => 39,  71 => 37,  64 => 33,  47 => 28,  43 => 27,  39 => 26,  35 => 25,  29 => 22,  25 => 21,  126 => 65,  124 => 64,  117 => 53,  113 => 52,  95 => 46,  92 => 41,  86 => 37,  81 => 35,  78 => 34,  75 => 38,  69 => 30,  62 => 22,  60 => 32,  57 => 20,  55 => 30,  51 => 29,  48 => 15,  44 => 13,  42 => 12,  37 => 9,  34 => 7,  30 => 6,  26 => 4,  24 => 3,  21 => 2,  19 => 17,);
    }
}
