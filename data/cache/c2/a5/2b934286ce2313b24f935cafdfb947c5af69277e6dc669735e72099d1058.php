<?php

/* grid-sql.phtml */
class __TwigTemplate_c2a52b934286ce2313b24f935cafdfb947c5af69277e6dc669735e72099d1058 extends Twig_Template
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
        // line 6
        echo "<script>
    // use data-spy=\"grid\" for use AJAX for reload grid
    // metka-123
    require([\"bluz.grid\"]);
</script>

 <div class=\"clearfix\" data-spy=\"grid\" data-grid=\"";
        // line 12
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "getUid"), "html", null, true);
        echo "\" >




    <p>
    Filters:<br/>
        ";
        // line 19
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        $context["filter1"] = $this->getAttribute($_grid_, "filter", array(0 => "id", 1 => "gt", 2 => 30), "method");
        // line 20
        echo "        ";
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        $context["filter2"] = $this->getAttribute($_grid_, "filter", array(0 => "id", 1 => "lt", 2 => 70), "method");
        // line 21
        echo "        ";
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        $context["filter3"] = $this->getAttribute($_grid_, "filter", array(0 => "id", 1 => "ne", 2 => 5, 3 => false), "method");
        // line 22
        echo "        ";
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        $context["filter4"] = $this->getAttribute($_grid_, "filter", array(0 => "status", 1 => "eq", 2 => "active"), "method");
        // line 23
        echo "        ";
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        $context["filter5"] = $this->getAttribute($_grid_, "filter", array(0 => "status", 1 => "eq", 2 => "active", 3 => false), "method");
        // line 24
        echo "
        <a href=\"";
        // line 25
        if (isset($context["filter1"])) { $_filter1_ = $context["filter1"]; } else { $_filter1_ = null; }
        echo twig_escape_filter($this->env, $_filter1_, "html", null, true);
        echo "\">Id &gt; 30</a> <br/>
        <a href=\"";
        // line 26
        if (isset($context["filter2"])) { $_filter2_ = $context["filter2"]; } else { $_filter2_ = null; }
        echo twig_escape_filter($this->env, $_filter2_, "html", null, true);
        echo "\">Id &lt; 70</a> <br/>
        <a href=\"";
        // line 27
        if (isset($context["filter3"])) { $_filter3_ = $context["filter3"]; } else { $_filter3_ = null; }
        echo twig_escape_filter($this->env, $_filter3_, "html", null, true);
        echo "\">AND Id != 5</a> <br/>
        <a href=\"";
        // line 28
        if (isset($context["filter4"])) { $_filter4_ = $context["filter4"]; } else { $_filter4_ = null; }
        echo twig_escape_filter($this->env, $_filter4_, "html", null, true);
        echo "\">All Active Users</a> <br/>

    ";
        // line 31
        echo "        <a href=\"";
        if (isset($context["filter5"])) { $_filter5_ = $context["filter5"]; } else { $_filter5_ = null; }
        echo twig_escape_filter($this->env, $_filter5_, "html", null, true);
        echo "\">AND Active Users</a> <br/>
        <a href=\"";
        // line 32
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "filter", array("column" => "status", "filter" => "eq", "value" => "active", "reset" => 0)), "html", null, true);
        echo "\">AND Active Users</a> <br/>
    </p>

     <div class=\"input-group\">
        <a href=\"";
        // line 36
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->url("test", "crud"), "html", null, true);
        echo "\" class=\"btn btn-primary pull-left dialog\" data-ajax-method=\"get\">Create</a>
    </div>

    <ul class=\"pagination pagination-small pull-right\">
        <li class=\"disabled\"><a href=\"#\">";
        // line 40
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Limit"), "html", null, true);
        echo "</a></li>

        <li ";
        // line 42
        if (isset($context["grid_getLimit"])) { $_grid_getLimit_ = $context["grid_getLimit"]; } else { $_grid_getLimit_ = null; }
        if (($_grid_getLimit_ == 5)) {
            echo " class=\"active\" ";
        }
        echo "><a href=\"";
        if (isset($context["grid_limit_5"])) { $_grid_limit_5_ = $context["grid_limit_5"]; } else { $_grid_limit_5_ = null; }
        echo twig_escape_filter($this->env, $_grid_limit_5_, "html", null, true);
        echo "\">5</a></li>
        <li ";
        // line 43
        if (isset($context["grid_getLimit"])) { $_grid_getLimit_ = $context["grid_getLimit"]; } else { $_grid_getLimit_ = null; }
        if (($_grid_getLimit_ == 25)) {
            echo " class=\"active\" ";
        }
        echo "><a href=\"";
        if (isset($context["grid_limit_25"])) { $_grid_limit_25_ = $context["grid_limit_25"]; } else { $_grid_limit_25_ = null; }
        echo twig_escape_filter($this->env, $_grid_limit_25_, "html", null, true);
        echo "\">25</a></li>
        <li ";
        // line 44
        if (isset($context["grid_getLimit"])) { $_grid_getLimit_ = $context["grid_getLimit"]; } else { $_grid_getLimit_ = null; }
        if (($_grid_getLimit_ == 50)) {
            echo " class=\"active\" ";
        }
        echo "><a href=\"";
        if (isset($context["grid_limit_50"])) { $_grid_limit_50_ = $context["grid_limit_50"]; } else { $_grid_limit_50_ = null; }
        echo twig_escape_filter($this->env, $_grid_limit_50_, "html", null, true);
        echo "\">50</a></li>
        <li ";
        // line 45
        if (isset($context["grid_getLimit"])) { $_grid_getLimit_ = $context["grid_getLimit"]; } else { $_grid_getLimit_ = null; }
        if (($_grid_getLimit_ == 100)) {
            echo " class=\"active\" ";
        }
        echo "><a href=\"";
        if (isset($context["grid_limit_100"])) { $_grid_limit_100_ = $context["grid_limit_100"]; } else { $_grid_limit_100_ = null; }
        echo twig_escape_filter($this->env, $_grid_limit_100_, "html", null, true);
        echo "\">100</a></li>
    </ul>

    <table class=\"table grid\">
        <thead>
                <tr>
        <th width=\"120px\"><a href=\"";
        // line 51
        if (isset($context["grid_order_1"])) { $_grid_order_1_ = $context["grid_order_1"]; } else { $_grid_order_1_ = null; }
        echo twig_escape_filter($this->env, $_grid_order_1_, "html", null, true);
        echo "\">Id</a></th>
        <th width=\"120px\"><a href=\"";
        // line 52
        if (isset($context["grid_order_2"])) { $_grid_order_2_ = $context["grid_order_2"]; } else { $_grid_order_2_ = null; }
        echo twig_escape_filter($this->env, $_grid_order_2_, "html", null, true);
        echo "\">name</a></th>
        <th width=\"120px\"><a href=\"";
        // line 53
        if (isset($context["grid_order_3"])) { $_grid_order_3_ = $context["grid_order_3"]; } else { $_grid_order_3_ = null; }
        echo twig_escape_filter($this->env, $_grid_order_3_, "html", null, true);
        echo "\">Status</a></th>
        <th width=\"120px\"></th>
                    </tr>
              </thead>
                <tbody>
        ";
        // line 59
        echo "         ";
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($_grid_, "getData"));
        foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
            // line 60
            echo "            <tr>
                        <td>";
            // line 61
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "id", array(), "array"), "html", null, true);
            echo "</td>
                        <td>";
            // line 62
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "name", array(), "array"), "html", null, true);
            echo "</td>
                        <td>";
            // line 63
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_row_, "status", array(), "array"), "html", null, true);
            echo "</td>
                        <td class=\"controls\">
                            <a href=\"";
            // line 65
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->url("test", "crud", array("id" => $this->getAttribute($_row_, "id", array(), "array"))), "html", null, true);
            echo "\" class=\"btn btn-xs btn-primary dialog\" data-ajax-method=\"get\">
                                <i class=\"fa fa-pencil\"></i>
                            </a>
                            <a href=\"";
            // line 68
            if (isset($context["row"])) { $_row_ = $context["row"]; } else { $_row_ = null; }
            echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->url("test", "crud", array("id" => $this->getAttribute($_row_, "id", array(), "array"))), "html", null, true);
            echo "\" class=\"confirm btn btn-xs btn-danger ajax\" data-ajax-method=\"delete\">
                                <i class=\"fa fa-times\"></i>
                            </a>
                        </td>
                    </tr>
         ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 74
        echo "         </tbody>
        </table>


        ";
        // line 78
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        if (($this->getAttribute($_grid_, "pages") > 1)) {
            // line 79
            echo "            <ul class=\"pagination pull-right\">
            ";
            // line 80
            if (isset($context["prev"])) { $_prev_ = $context["prev"]; } else { $_prev_ = null; }
            if ($_prev_) {
                // line 81
                echo "                <li><a href=\"";
                if (isset($context["prev"])) { $_prev_ = $context["prev"]; } else { $_prev_ = null; }
                echo twig_escape_filter($this->env, $_prev_, "html", null, true);
                echo "\">&laquo;</a></li>
            ";
            } else {
                // line 83
                echo "                <li class=\"disabled\"><a href=\"#\" onclick=\"javascript:return false;\">&laquo;</a></li>
            ";
            }
            // line 85
            echo "
            ";
            // line 86
            if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable(range(1, $this->getAttribute($_grid_, "pages")));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 87
                echo "            ";
                // line 88
                echo "                ";
                if (isset($context["i"])) { $_i_ = $context["i"]; } else { $_i_ = null; }
                $context["page"] = $_i_;
                // line 89
                echo "                <li ";
                if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
                if (isset($context["page"])) { $_page_ = $context["page"]; } else { $_page_ = null; }
                if (($this->getAttribute($_grid_, "getPage") == $_page_)) {
                    echo "class=\"active\" ";
                }
                echo ">

                ";
                // line 92
                echo "                ";
                // line 93
                echo "                    <a href=\"";
                if (isset($context["pages_link"])) { $_pages_link_ = $context["pages_link"]; } else { $_pages_link_ = null; }
                if (isset($context["i"])) { $_i_ = $context["i"]; } else { $_i_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_pages_link_, $_i_, array(), "array"), "html", null, true);
                echo "\">";
                if (isset($context["page"])) { $_page_ = $context["page"]; } else { $_page_ = null; }
                echo twig_escape_filter($this->env, $_page_, "html", null, true);
                echo "</a>
                </li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 96
            echo "
            ";
            // line 97
            if (isset($context["next"])) { $_next_ = $context["next"]; } else { $_next_ = null; }
            if ($_next_) {
                // line 98
                echo "                <li><a href=\"";
                if (isset($context["next"])) { $_next_ = $context["next"]; } else { $_next_ = null; }
                echo twig_escape_filter($this->env, $_next_, "html", null, true);
                echo "\">&raquo;</a></li>
            ";
            } else {
                // line 100
                echo "                <li class=\"disabled\"><a href=\"#\" onclick=\"javascript:return false;\">&raquo;</a></li>
            ";
            }
            // line 102
            echo "            </ul>
        ";
        }
        // line 104
        echo "



        <ul class=\"pager pull-left\">
            <li>
                <a href=\"";
        // line 110
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "reset"), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->__("Total"), "html", null, true);
        echo ":
             <strong>";
        // line 111
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_grid_, "total"), "html", null, true);
        echo "</strong>
               ";
        // line 112
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->_n("record", "records", $this->getAttribute($_grid_, "total")), "html", null, true);
        echo "
                ";
        // line 113
        if (isset($context["grid"])) { $_grid_ = $context["grid"]; } else { $_grid_ = null; }
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->_n("on %s page", "on %s pages", $this->getAttribute($_grid_, "pages"), $this->getAttribute($_grid_, "pages")), "html", null, true);
        echo "</a>
        </li>
        </ul>

            ";
        // line 118
        echo "            ";
        // line 119
        echo "                ";
        // line 120
        echo "            ";
        // line 121
        echo "</div>









";
    }

    public function getTemplateName()
    {
        return "grid-sql.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  343 => 121,  341 => 120,  339 => 119,  337 => 118,  329 => 113,  324 => 112,  319 => 111,  312 => 110,  304 => 104,  300 => 102,  296 => 100,  289 => 98,  286 => 97,  283 => 96,  268 => 93,  266 => 92,  256 => 89,  252 => 88,  250 => 87,  245 => 86,  242 => 85,  238 => 83,  231 => 81,  228 => 80,  225 => 79,  222 => 78,  216 => 74,  203 => 68,  196 => 65,  190 => 63,  185 => 62,  180 => 61,  177 => 60,  171 => 59,  162 => 53,  157 => 52,  152 => 51,  137 => 45,  127 => 44,  117 => 43,  107 => 42,  102 => 40,  95 => 36,  87 => 32,  81 => 31,  75 => 28,  70 => 27,  65 => 26,  60 => 25,  57 => 24,  53 => 23,  49 => 22,  45 => 21,  41 => 20,  38 => 19,  27 => 12,  19 => 6,);
    }
}
