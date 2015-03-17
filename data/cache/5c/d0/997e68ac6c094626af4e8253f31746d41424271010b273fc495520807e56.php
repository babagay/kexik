<?php

/* signin.phtml */
class __TwigTemplate_5cd0997e68ac6c094626af4e8253f31746d41424271010b273fc495520807e56 extends Twig_Template
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
        echo "<?php
/**
 * @var \\Application\\Users\\Row \$user
 * @var \\Bluz\\View\\View \$this
 */
?>
<div class=\"panel panel-default\">
    <div class=\"panel-heading\">
        <h3 class=\"panel-title\">";
        // line 9
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Авторизуйтесь, пожалуйста"), "html", null, true);
        echo "</h3>
    </div>
    <div class=\"panel-body\">
        <form action=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->url("users", "signin"), "html", null, true);
        echo "\" method=\"post\">
            <div class=\"form-group input-group\">
                <span class=\"input-group-addon\"><i class=\"fa fa-user fa-fw\"></i></span>
                <input type=\"text\" name=\"login\" class=\"form-control\" value=\"";
        // line 15
        if (isset($context["login"])) { $_login_ = $context["login"]; } else { $_login_ = null; }
        echo twig_escape_filter($this->env, $_login_, "html", null, true);
        echo "\" placeholder=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("login"), "html", null, true);
        echo "\" maxlength=\"255\" required />
            </div>

            <div class=\"form-group input-group\">
                <span class=\"input-group-addon\"><i class=\"fa fa-lock fa-fw\"></i></span>
                <input type=\"password\" name=\"password\" class=\"form-control\" placeholder=\"";
        // line 20
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("password"), "html", null, true);
        echo "\" required />
            </div>

            <input type=\"submit\" value=\"";
        // line 23
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Войти"), "html", null, true);
        echo "\" class=\"btn btn-primary\"/>
";
        // line 27
        echo "        </form>
    </div>
    <div class=\"panel-footer clearfix\">
        <div class=\"pull-left\">
            ";
        // line 32
        echo "        </div>
        <!--div class=\"pull-left\">
            ";
        // line 35
        echo "        </div-->
        ";
        // line 39
        echo "    </div>



</div>
";
    }

    public function getTemplateName()
    {
        return "signin.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  75 => 39,  72 => 35,  68 => 32,  62 => 27,  58 => 23,  52 => 20,  41 => 15,  35 => 12,  29 => 9,  19 => 1,);
    }
}
