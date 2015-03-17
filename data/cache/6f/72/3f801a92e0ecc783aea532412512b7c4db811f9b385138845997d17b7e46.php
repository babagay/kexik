<?php

/* partial/twig_header_front.phtml */
class __TwigTemplate_6f723f801a92e0ecc783aea532412512b7c4db811f9b385138845997d17b7e46 extends Twig_Template
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
        if (isset($context["zoqa_title"])) { $_zoqa_title_ = $context["zoqa_title"]; } else { $_zoqa_title_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->title($_zoqa_title_), "html", null, true);
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
        return array (  182 => 95,  175 => 91,  170 => 88,  168 => 87,  166 => 86,  163 => 85,  140 => 64,  129 => 58,  124 => 56,  118 => 53,  114 => 52,  110 => 51,  106 => 50,  100 => 47,  96 => 46,  84 => 40,  80 => 39,  72 => 37,  65 => 33,  61 => 32,  56 => 30,  52 => 29,  40 => 26,  36 => 25,  25 => 21,  130 => 65,  128 => 64,  121 => 60,  117 => 58,  99 => 42,  95 => 41,  88 => 41,  82 => 35,  79 => 34,  76 => 38,  69 => 30,  62 => 22,  60 => 21,  57 => 20,  55 => 19,  51 => 17,  48 => 28,  44 => 27,  42 => 12,  37 => 9,  34 => 7,  30 => 22,  26 => 4,  24 => 3,  21 => 2,  19 => 17,);
    }
}
