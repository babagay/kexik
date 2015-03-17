<?php

/* partial/twig_header_small.phtml */
class __TwigTemplate_7ef0049ed6db3f23c3b8b2d8fabe65a39d0eae583100899abdb39d3292dfbcb9 extends Twig_Template
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
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->title(), "html", null, true);
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


    <link rel=\"icon\"  href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("favicon.png"), "html", null, true);
        echo "\" type=\"image/png\" />
    <script src=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/require.js"), "html", null, true);
        echo "\"></script>

    ";
        // line 16
        $context["http_host"] = $this->env->getExtension('zoqa_twig')->getRequest();
        // line 18
        echo "
    ";
        // line 19
        if (isset($context["http_host"])) { $_http_host_ = $context["http_host"]; } else { $_http_host_ = null; }
        if (($this->getAttribute($this->getAttribute($_http_host_, "server"), "HTTP_HOST") == "localhost")) {
            // line 20
            echo "    <script src=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/config_local.js"), "html", null, true);
            echo "\"></script>

    ";
        } else {
            // line 23
            echo "    <script src=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/public/js/config.js"), "html", null, true);
            echo "\"></script>
    ";
        }
        // line 25
        echo "

    ";
        // line 27
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->headScript(), "html", null, true);
        echo "
    ";
        // line 28
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->headStyle(), "html", null, true);
        echo "
    ";
        // line 29
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->link(), "html", null, true);
        echo "
    ";
        // line 30
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->meta(), "html", null, true);
        echo "

    ";
        // line 33
        echo "
</head>
<body class=\"front-end\" data-spy=\"scroll\" data-bluz-module=\"";
        // line 35
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->module(), "html", null, true);
        echo "\" data-bluz-controller=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->controller(), "html", null, true);
        echo "\">








";
    }

    public function getTemplateName()
    {
        return "partial/twig_header_small.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  96 => 35,  92 => 33,  87 => 30,  83 => 29,  79 => 28,  75 => 27,  71 => 25,  65 => 23,  58 => 20,  55 => 19,  52 => 18,  45 => 13,  41 => 12,  35 => 9,  29 => 6,  25 => 5,  50 => 16,  40 => 16,  38 => 15,  30 => 8,  27 => 6,  23 => 4,  21 => 3,  19 => 1,);
    }
}
