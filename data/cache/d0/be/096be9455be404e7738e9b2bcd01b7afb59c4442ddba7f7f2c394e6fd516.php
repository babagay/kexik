<?php

/* partial/twig_nav-bar.phtml */
class __TwigTemplate_d0be096be9455be404e7738e9b2bcd01b7afb59c4442ddba7f7f2c394e6fd516 extends Twig_Template
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
";
        // line 4
        echo "
<div class=\"navbar navbar-inverse navbar-fixed-top backend\">
    <div class=\"navbar-inner\">
        <div class=\"container-fluid\">
            <div class=\"navbar-header\">
                <a class=\"navbar-brand ";
        // line 9
        if ($this->env->getExtension('zoqa_twig')->module("index")) {
            echo "active";
        }
        echo "\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/"), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Keks.ik"), "html", null, true);
        echo "</a>
                <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\".navbar-collapse\">
                    <span class=\"fa fa-bar\"></span>
                    <span class=\"fa fa-bar\"></span>
                    <span class=\"fa fa-bar\"></span>
                </button>
            </div>
            <div class=\"navbar-collapse collapse\">
                <ul class=\"nav navbar-nav\">
                    <!--li ";
        // line 18
        if ($this->env->getExtension('zoqa_twig')->module("Index")) {
            echo "class=\"active\"";
        }
        echo ">
                        ";
        // line 19
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Home", array(0 => "index", 1 => "index")), "html", null, true);
        echo "
                    </li-->
\t\t\t\t\t<li ";
        // line 21
        if ($this->env->getExtension('zoqa_twig')->module("admin", "categories")) {
            echo "class=\"active\"";
        }
        echo ">
                        ";
        // line 22
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Категории", array(0 => "admin", 1 => "categories")), "html", null, true);
        echo "
                    </li>
                    <li ";
        // line 24
        if ($this->env->getExtension('zoqa_twig')->module("admin", "products")) {
            echo "class=\"active\"";
        }
        echo ">
                        ";
        // line 25
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Продукты", array(0 => "admin", 1 => "products")), "html", null, true);
        echo "
                    </li>
                    <!--li ";
        // line 27
        if ($this->env->getExtension('zoqa_twig')->module("blog", "Base")) {
            echo "class=\"active\"";
        }
        echo ">
                        ";
        // line 28
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Блог", array(0 => "блог", 1 => "")), "html", null, true);
        echo "
                    </li>
                    <li ";
        // line 30
        if ($this->env->getExtension('zoqa_twig')->module("autor")) {
            echo "class=\"active\"";
        }
        echo ">
                        ";
        // line 31
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Автор", array(0 => "автор", 1 => "")), "html", null, true);
        echo "
                    </li>
                    <li ";
        // line 33
        if ($this->env->getExtension('zoqa_twig')->module("dashboard")) {
            echo "class=\"active\"";
        }
        echo ">
                      ";
        // line 34
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Dashboard", array(0 => "dashboard", 1 => "index")), "html", null, true);
        echo "
                    </li>
                    <li ";
        // line 36
        if ($this->env->getExtension('zoqa_twig')->module("test")) {
            echo "class=\"active\"";
        }
        echo ">
                        ";
        // line 37
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Test", array(0 => "test", 1 => "")), "html", null, true);
        echo "
                    </li-->
                </ul>

                ";
        // line 41
        if ($this->env->getExtension('zoqa_twig')->user("info")) {
            // line 42
            echo "                ";
            // line 43
            echo "
                    ";
            // line 44
            $context["info"] = $this->env->getExtension('zoqa_twig')->user("info");
            // line 45
            echo "
                    <p class=\"navbar-text navbar-right\">

                        Logged in as ";
            // line 48
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref($this->getAttribute((isset($context["info"]) ? $context["info"] : null), "login"), array(0 => "users", 1 => "profile", 2 => array("id" => $this->getAttribute((isset($context["info"]) ? $context["info"] : null), "id")))), "html", null, true);
            echo "

                        (";
            // line 50
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Sign Out", array(0 => "users", 1 => "signout")), "html", null, true);
            echo ")

                        ";
            // line 57
            echo "                    </p>
                ";
        } else {
            // line 59
            echo "                    <ul class=\"nav navbar-nav navbar-right\">
                        <li>";
            // line 60
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref($this->env->getExtension('zoqa_twig')->__("Sign In"), array(0 => "users", 1 => "signin")), "html", null, true);
            echo "</li>
                    </ul>
                ";
        }
        // line 63
        echo "



                <div id=\"loading\"></div>

                <div id=\"feedback-message\"></div>


            </div>
            <p class=\"navbar-text navbar-left breadcrumbs back-end\">
                ";
        // line 74
        if ($this->env->getExtension('zoqa_twig')->breadCrumbs()) {
            // line 75
            echo "                ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->env->getExtension('zoqa_twig')->breadCrumbs());
            $context['loop'] = array(
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            );
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["crumb"]) {
                // line 76
                echo "                ";
                echo twig_escape_filter($this->env, (isset($context["crumb"]) ? $context["crumb"] : null), "html", null, true);
                echo "
                ";
                // line 77
                if (($this->getAttribute((isset($context["loop"]) ? $context["loop"] : null), "revindex") != 1)) {
                    // line 78
                    echo "                ";
                    if (($this->getAttribute((isset($context["loop"]) ? $context["loop"] : null), "length") > 1)) {
                        // line 79
                        echo "                —
                ";
                    }
                    // line 81
                    echo "                ";
                }
                // line 82
                echo "                ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['crumb'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 83
            echo "                ";
        }
        // line 84
        echo "            </p>
        </div>
    </div>
</div>

";
    }

    public function getTemplateName()
    {
        return "partial/twig_nav-bar.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  230 => 84,  227 => 83,  213 => 82,  210 => 81,  206 => 79,  203 => 78,  201 => 77,  196 => 76,  178 => 75,  176 => 74,  163 => 63,  157 => 60,  154 => 59,  150 => 57,  145 => 50,  140 => 48,  135 => 45,  133 => 44,  130 => 43,  128 => 42,  126 => 41,  119 => 37,  113 => 36,  108 => 34,  102 => 33,  97 => 31,  91 => 30,  86 => 28,  80 => 27,  75 => 25,  69 => 24,  64 => 22,  58 => 21,  53 => 19,  47 => 18,  29 => 9,  22 => 4,  19 => 2,);
    }
}
