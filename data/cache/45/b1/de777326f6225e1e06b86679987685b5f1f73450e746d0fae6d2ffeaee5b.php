<?php

/* partial/twig_header.phtml */
class __TwigTemplate_45b1de777326f6225e1e06b86679987685b5f1f73450e746d0fae6d2ffeaee5b extends Twig_Template
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
        echo "<!DOCTYPE html>
<html dir=\"ltr\" lang=\"en-US\">
<head>
    <meta charset=\"UTF-8\"/>
    <title>";
        // line 5
        if (isset($context["zoqa_title"])) { $_zoqa_title_ = $context["zoqa_title"]; } else { $_zoqa_title_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->title($_zoqa_title_), "html", null, true);
        echo "</title>
    <base href=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl(), "html", null, true);
        echo "\" />
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
    <link rel=\"profile\" href=\"http://gmpg.org/xfn/11\"/>
    <link rel=\"stylesheet\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/css/bootstrap.css"), "html", null, true);
        echo "\" />
    <link rel=\"stylesheet\" href=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/css/price.css"), "html", null, true);
        echo "\" />
    <link rel=\"stylesheet\" href=\"";
        // line 11
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/css/font-awesome.css"), "html", null, true);
        echo "\" />
    <link rel=\"stylesheet\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/css/styles.css"), "html", null, true);
        echo "\" />
    <link rel=\"stylesheet\" href=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/css/print.css"), "html", null, true);
        echo "\" media=\"print\" />
    <link rel=\"shortcut icon\"  href=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("favicon.png"), "html", null, true);
        echo "\" type=\"image/png\" />

    <script src=\"";
        // line 16
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/require.js"), "html", null, true);
        echo "\"></script>
    <script src=\"";
        // line 17
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/config.js"), "html", null, true);
        echo "\"></script>
    
    <!-----------------Юля-+++------------------------>
    ";
        // line 25
        echo "    <!------------------------------------------>

    <!-- For IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src=\"";
        // line 29
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/vendor/html5shiv.js"), "html", null, true);
        echo "\"></script>
    <script src=\"";
        // line 30
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/vendor/html5shiv-printshiv.js"), "html", null, true);
        echo "\"></script>
    <![endif]-->

";
        // line 33
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->headScript(), "html", null, true);
        echo "
";
        // line 34
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->headStyle(), "html", null, true);
        echo "
";
        // line 35
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->link(), "html", null, true);
        echo "
";
        // line 36
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->meta(), "html", null, true);
        echo "

";
        // line 38
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->trigger("header"), "html", null, true);
        echo "
</head>
<body data-spy=\"scroll\" data-bluz-module=\"";
        // line 40
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->module(), "html", null, true);
        echo "\" data-bluz-controller=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->controller(), "html", null, true);
        echo "\">

";
        // line 42
        if ($this->env->getExtension('zoqa_twig')->hasMessages()) {
            echo " ";
            // line 44
            echo "
    <script>
        require(['bluz.notify'], function(notify) {
            notify.set(";
            // line 47
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->getMessages(), "html", null, true);
            echo " );
        });
    </script>
";
        }
        // line 51
        echo "
";
    }

    public function getTemplateName()
    {
        return "partial/twig_header.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  131 => 51,  124 => 47,  119 => 44,  116 => 42,  109 => 40,  104 => 38,  99 => 36,  95 => 35,  91 => 34,  87 => 33,  81 => 30,  77 => 29,  71 => 25,  65 => 17,  61 => 16,  56 => 14,  52 => 13,  48 => 12,  44 => 11,  40 => 10,  36 => 9,  30 => 6,  25 => 5,  19 => 1,);
    }
}
