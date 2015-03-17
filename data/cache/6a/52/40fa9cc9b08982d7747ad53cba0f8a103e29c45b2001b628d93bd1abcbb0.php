<?php

/* products.phtml */
class __TwigTemplate_6a5240fa9cc9b08982d7747ad53cba0f8a103e29c45b2001b628d93bd1abcbb0 extends Twig_Template
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
        echo "<FORM ENCTYPE=\"multipart/form-data\" ACTION=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->baseUrl("/admin/products-upload"), "html", null, true);
        echo "\" METHOD=POST  target=\"upload_target\">

    <input type=\"file\" name=\"products_list\">

    <input type=\"hidden\" name=\"asd\" value=\"1\">

    <p class=\"btn-toolbar\">
        <!--input type=\"button\" class=\"btn btn-primary\" value=\"Insert\" onClick=\"return savePage()\"-->
        <input type=\"submit\" class=\"btn btn-primary\" value=\"Залить\" >
    </p>
</FORM>

<iframe id=\"upload_target\" name=\"upload_target\"   style=\"width:0;height:0;border:0px solid #fff;\"></iframe>

<script>

    require([\"jquery\"],  function (\$) {


        \$(\"#upload_target\").load(function(e){
            \$(\"div[data-spy=grid] .pagination li.active a\").click();
        });

    });

    </script>

";
        // line 28
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->dispatch("admin", "grid-products"), "html", null, true);
        echo "
";
    }

    public function getTemplateName()
    {
        return "products.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  50 => 28,  19 => 1,);
    }
}
