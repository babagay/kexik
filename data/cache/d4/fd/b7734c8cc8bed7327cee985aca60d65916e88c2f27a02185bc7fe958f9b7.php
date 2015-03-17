<?php

/* crud.phtml */
class __TwigTemplate_d4fdb7734c8cc8bed7327cee985aca60d65916e88c2f27a02185bc7fe958f9b7 extends Twig_Template
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
        $context["uid"] = $this->env->getExtension('zoqa_twig')->uniq_id("form_");
        // line 2
        echo "

<form id=\"";
        // line 4
        if (isset($context["uid"])) { $_uid_ = $context["uid"]; } else { $_uid_ = null; }
        echo twig_escape_filter($this->env, $_uid_, "html", null, true);
        echo "\" action=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->url("test", "crud"), "html", null, true);
        echo "\" class=\"form-horizontal ajax\" method=\"";
        if (isset($context["method"])) { $_method_ = $context["method"]; } else { $_method_ = null; }
        echo twig_escape_filter($this->env, $_method_, "html", null, true);
        echo "\">
    <input type=\"hidden\" name=\"id\" value=\"";
        // line 5
        if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_row_, "id"), "html", null, true);
        echo "\"/>
    <div class=\"modal-header\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
        <h3 class=\"modal-title\">Test Table</h3>
    </div>
    <div class=\"modal-body\">
        <div class=\"form-group\">
            <label class=\"control-label col-lg-2\" for=\"name\">";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Name"), "html", null, true);
        echo "</label>
            <div class=\"col-lg-10\">
                <input type=\"text\" class=\"form-control\" id=\"name\" name=\"name\" value=\"";
        // line 14
        if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_row_, "name"), "html", null, true);
        echo "\" required />
            </div>
        </div>
        <div class=\"form-group\">
            <label class=\"control-label col-lg-2\" for=\"email\">";
        // line 18
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Email"), "html", null, true);
        echo "</label>
            <div class=\"col-lg-10\">
                <input type=\"email\" class=\"form-control\" id=\"email\" name=\"email\" value=\"";
        // line 20
        if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_row_, "email"), "html", null, true);
        echo "\" required />
            </div>
        </div>
        <div class=\"form-group\">
            <label class=\"control-label col-lg-2\" for=\"status\">";
        // line 24
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Status"), "html", null, true);
        echo "</label>
            <div class=\"col-lg-10\">
                <select id=\"status\" name=\"status\" class=\"form-control\">
                    <option ";
        // line 27
        if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
        if (($this->getAttribute($_row_, "status") == "active")) {
            echo " 'selected' ";
        }
        echo ">active</option>
                    <option ";
        // line 28
        if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
        if (($this->getAttribute($_row_, "status") == "disable")) {
            echo " 'selected' ";
        }
        echo ">disable</option>
                    <option ";
        // line 29
        if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
        if (($this->getAttribute($_row_, "status") == "delete")) {
            echo " 'selected' ";
        }
        echo ">delete</option>
                </select>
            </div>
        </div>
    </div>
    <div class=\"modal-footer\">
        <button type=\"submit\" class=\"btn btn-primary\">";
        // line 35
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Save"), "html", null, true);
        echo "</button>
        <a href=\"#\" class=\"btn btn-default\" data-dismiss=\"modal\">";
        // line 36
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Close"), "html", null, true);
        echo "</a>
    </div>
</form>

";
        // line 40
        if (isset($context["errors"])) { $_errors_ = $context["errors"]; } else { $_errors_ = null; }
        if ($_errors_) {
            // line 41
            echo "    Error

";
        }
        // line 44
        echo "
";
    }

    public function getTemplateName()
    {
        return "crud.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  123 => 44,  118 => 41,  115 => 40,  108 => 36,  104 => 35,  92 => 29,  85 => 28,  78 => 27,  72 => 24,  64 => 20,  59 => 18,  51 => 14,  46 => 12,  35 => 5,  25 => 4,  21 => 2,  19 => 1,);
    }
}
