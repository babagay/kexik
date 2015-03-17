<?php

/* categories-crud.phtml */
class __TwigTemplate_345b0c1f4a1204ce56d4df23c5c124a191e85f18e550df9b903afac6ff520e80 extends Twig_Template
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
";
        // line 5
        echo "<form id=\"";
        if (isset($context["uid"])) { $_uid_ = $context["uid"]; } else { $_uid_ = null; }
        echo twig_escape_filter($this->env, $_uid_, "html", null, true);
        echo "\" action=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->url("admin", "categories-crud"), "html", null, true);
        echo "\" class=\"form-horizontal ajax\" method=\"";
        if (isset($context["method"])) { $_method_ = $context["method"]; } else { $_method_ = null; }
        echo twig_escape_filter($this->env, $_method_, "html", null, true);
        echo "\">
    <input type=\"hidden\" name=\"categories_id\" value=\"";
        // line 6
        if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_row_, "categories_id"), "html", null, true);
        echo "\"/>
    <div class=\"modal-header\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
        <h3 class=\"modal-title\">Категория товаров</h3>
    </div>
    <div class=\"modal-body\">
        <div class=\"form-group\">
            <label class=\"control-label col-lg-2\" for=\"categories_name\">";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Название"), "html", null, true);
        echo "</label>
            <div class=\"col-lg-10\">
                <input type=\"text\" class=\"form-control\" id=\"categories_name\" name=\"categories_name\" value=\"";
        // line 15
        if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_row_, "categories_name"), "html", null, true);
        echo "\" required />
            </div>
        </div>
        <div class=\"form-group\">
            <label class=\"control-label col-lg-2\" for=\"categories_seo_page_name\">";
        // line 19
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("СЕО"), "html", null, true);
        echo "</label>
            <div class=\"col-lg-10\">
                <input type=\"categories_seo_page_name\" class=\"form-control\" id=\"categories_seo_page_name\" name=\"categories_seo_page_name\" value=\"";
        // line 21
        if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_row_, "categories_seo_page_name"), "html", null, true);
        echo "\" required />
            </div>
        </div>
        <!--div class=\"form-group\">
            <label class=\"control-label col-lg-2\" for=\"status\">";
        // line 25
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Status"), "html", null, true);
        echo "</label>
            <div class=\"col-lg-10\">
                <select id=\"status\" name=\"status\" class=\"form-control\">
                    <option ";
        // line 28
        if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
        if (($this->getAttribute($_row_, "status") == "active")) {
            echo " 'selected' ";
        }
        echo ">active</option>
                    <option ";
        // line 29
        if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
        if (($this->getAttribute($_row_, "status") == "disable")) {
            echo " 'selected' ";
        }
        echo ">disable</option>
                    <option ";
        // line 30
        if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
        if (($this->getAttribute($_row_, "status") == "delete")) {
            echo " 'selected' ";
        }
        echo ">delete</option>
                </select>
            </div>
        </div-->
    </div>
    <div class=\"modal-footer\">
        <button type=\"submit\" class=\"btn btn-primary\">";
        // line 36
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Сохранить"), "html", null, true);
        echo "</button>
        <a href=\"#\" class=\"btn btn-default\" data-dismiss=\"modal\">";
        // line 37
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Отмена"), "html", null, true);
        echo "</a>
    </div>
</form>

";
        // line 41
        if (isset($context["errors"])) { $_errors_ = $context["errors"]; } else { $_errors_ = null; }
        if ($_errors_) {
            // line 42
            echo "Error

";
        }
    }

    public function getTemplateName()
    {
        return "categories-crud.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  118 => 42,  115 => 41,  108 => 37,  104 => 36,  92 => 30,  85 => 29,  78 => 28,  72 => 25,  64 => 21,  59 => 19,  51 => 15,  46 => 13,  35 => 6,  24 => 5,  21 => 2,  19 => 1,);
    }
}
