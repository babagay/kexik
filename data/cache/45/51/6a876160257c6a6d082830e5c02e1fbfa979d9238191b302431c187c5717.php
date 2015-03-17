<?php

/* Base.phtml */
class __TwigTemplate_45516a876160257c6a6d082830e5c02e1fbfa979d9238191b302431c187c5717 extends Twig_Template
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
        echo "<h3>Hello, ";
        if (isset($context["username"])) { $_username_ = $context["username"]; } else { $_username_ = null; }
        echo twig_escape_filter($this->env, $_username_, "html", null, true);
        echo "</h3>
<p>This is page consist many examples, all of them you can find in test module</p>
<h3>Controller features</h3>
<ul>
    <li>";
        // line 5
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Controller &rarr; Closure", array(0 => "test", 1 => "closure")), "html", null, true);
        echo "</li>
    <li>";
        // line 6
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Controller &rarr; Disable View", array(0 => "test", 1 => "no-view")), "html", null, true);
        echo "</li>
    <li>";
        // line 7
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Reflection Data", array(0 => "test", 1 => "reflection")), "html", null, true);
        echo "</li>
</ul>
<hr/>
<ul>
    <li>";
        // line 11
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("CRUD", array(0 => "test", 1 => "grid-sql")), "html", null, true);
        echo " &mdash; Create, Read, Update and Delete examples</li>
    <li>";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Backbone", array(0 => "test", 1 => "backbone")), "html", null, true);
        echo " &mdash; working too</li>
</ul>

<h3>CLI</h3>
<p>
For test CLI you can run <code>`php ./bin/cli.php --uri 'test/cli' --env='dev'`</code>.
You can find source code at <code>`application/modules/test/controllers/cli.php`</code>
</p>

<h3>Packages</h3>
<ul>
    <li>";
        // line 23
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Cache View", array(0 => "test", 1 => "cache-view")), "html", null, true);
        echo "</li>
    <li>";
        // line 24
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Cache Data", array(0 => "test", 1 => "cache-data", 2 => array("id" => 1))), "html", null, true);
        echo "</li>
    <li>";
        // line 25
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("DB Operations", array(0 => "test", 1 => "db")), "html", null, true);
        echo "</li>
    <li>";
        // line 26
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("DB Query Builders", array(0 => "test", 1 => "db-query")), "html", null, true);
        echo "</li>
    <li>";
        // line 27
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("DB Relations", array(0 => "test", 1 => "db-relations")), "html", null, true);
        echo "</li>
    <li>";
        // line 28
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("DB Table", array(0 => "test", 1 => "db-table")), "html", null, true);
        echo "</li>
    <li>";
        // line 29
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Events", array(0 => "test", 1 => "events")), "html", null, true);
        echo "</li>
    <li>";
        // line 30
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Routers", array(0 => "test", 1 => "route")), "html", null, true);
        echo "</li>
    <li>";
        // line 31
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Session", array(0 => "test", 1 => "session")), "html", null, true);
        echo "</li>
    <li>";
        // line 32
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Grid with Array source", array(0 => "test", 1 => "grid-array")), "html", null, true);
        echo "</li>
    <li>";
        // line 33
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Grid with Select source", array(0 => "test", 1 => "grid-select")), "html", null, true);
        echo "</li>
    <li>";
        // line 34
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Grid with SQL source", array(0 => "test", 1 => "grid-sql")), "html", null, true);
        echo "</li>
    <li>";
        // line 35
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Grid with custom route", array(0 => "test", 1 => "grid-route", 2 => array("alias" => "example"))), "html", null, true);
        echo "</li>
    <li>";
        // line 36
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Mailer", array(0 => "test", 1 => "mailer")), "html", null, true);
        echo "</li>
    <li>";
        // line 37
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("View Partials", array(0 => "test", 1 => "view-partial")), "html", null, true);
        echo "</li>
    <li>";
        // line 38
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("View Form Helpers", array(0 => "test", 1 => "view-helpers")), "html", null, true);
        echo "</li>
</ul>
<hr/>
<ul>
    <li>";
        // line 42
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Options", array(0 => "test", 1 => "options")), "html", null, true);
        echo " &mdash; example of usage module Options from another module</li>
</ul>
<hr/>

<h3>Magic AJAX calls</h3>
<p>Try AJAX calls over GET/POST/PUT/DELTE HTTPs methods</p>
<div class=\"btn-group\">
    ";
        // line 49
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("GET", array(0 => "test", 1 => "ajax"), array("class" => "ajax btn btn-default", "data-ajax-method" => "GET")), "html", null, true);
        echo "
    ";
        // line 50
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("POST", array(0 => "test", 1 => "ajax"), array("class" => "ajax btn btn-default", "data-ajax-method" => "POST")), "html", null, true);
        echo "
    ";
        // line 51
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("PUT", array(0 => "test", 1 => "ajax"), array("class" => "ajax btn btn-default", "data-ajax-method" => "PUT")), "html", null, true);
        echo "
    ";
        // line 52
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("DELETE", array(0 => "test", 1 => "ajax"), array("class" => "ajax btn btn-default", "data-ajax-method" => "DELETE")), "html", null, true);
        echo "
</div>
<hr/>
<div class=\"btn-group\">
    <a href=\"/test/ajax\" id=\"ajax-callback\" class=\"btn btn-default ajax\">AJAX call with fired trigger</a>
</div>
<hr/>
<p>Example of JavaScript:</p>
<pre>
    require([\"jquery\", \"bluz.notify\"], function(\$, notify) {
        \$(function(){
            \$('#ajax-callback')
                .on('success.ajax.bluz', function(event, data, textStatus, jqXHR){
                    notify.addSuccess(\"Event `success.ajax.bluz` is fired\")
                })
                .on('error.ajax.bluz', function(event, jqXHR, textStatus, errorThrown){
                    notify.addSuccess(\"Event `error.ajax.bluz` is fired\")
                });
        });
    });
</pre>
<script>
    require([\"jquery\", \"bluz.notify\"], function(\$, notify) {
        \$(function(){
            \$(\"#ajax-callback\")
                .on(\"success.ajax.bluz\", function(event, data, textStatus, jqXHR){
                    notify.addSuccess(\"Event `success.ajax.bluz` is fired\")
                })
                .on(\"error.ajax.bluz\", function(event, jqXHR, textStatus, errorThrown){
                    notify.addSuccess(\"Event `error.ajax.bluz` is fired\")
                });
        });
    });
</script>
<hr/>
<div class=\"btn-group\">
    ";
        // line 88
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Ajax + Messages", array(0 => "test", 1 => "ajax"), array("class" => "ajax btn btn-default", "data-messages" => true)), "html", null, true);
        echo "
    ";
        // line 89
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Ajax + Confirm", array(0 => "test", 1 => "ajax"), array("class" => "ajax confirm btn btn-danger")), "html", null, true);
        echo "
    ";
        // line 90
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Loading", array(0 => "test", 1 => "ajax-html"), array("class" => "load btn btn-default", "data-ajax-target" => "#load")), "html", null, true);
        echo "
    ";
        // line 91
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Confirm", array(0 => "index", 1 => "index"), array("class" => "confirm btn btn-default", "data-confirm" => "Confirm?")), "html", null, true);
        echo "
</div>
<hr/>

<h3>Access denied</h3>
<div class=\"btn-group\">
    <a href=\"/test/privilege\" class=\"btn btn-default bluz-tooltip ajax\" data-toggle=\"tooltip\" title=\"Return error message\">Denied page, AJAX call</a>
    <a href=\"/test/privilege\" class=\"btn btn-default bluz-tooltip dialog\" data-toggle=\"tooltip\" title=\"Return error dialog\">Denied page, dialog</a>
</div>
<hr/>

<h3>Exceptions</h3>
<ul>
    <li>";
        // line 104
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Throw exception from controller", array(0 => "test", 1 => "exception")), "html", null, true);
        echo "</li>
    <li>";
        // line 105
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->ahref("Throw exception from view", array(0 => "test", 1 => "exception-view")), "html", null, true);
        echo "</li>
</ul>
<hr/>

<h3>Dispatch controller</h3>
<pre>
    dispatch('test', 'closure')
</pre>
<div id=\"load\">
    ";
        // line 114
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->dispatch("test", "closure"), "html", null, true);
        echo "
</div>

<h3>Call widget</h3>
<pre>
     widget('test', 'lorem')
    widget('blog','images',[1,2])
</pre>
<div>
    ";
        // line 123
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->widget("test", "lorem"), "html", null, true);
        echo "

</div>

<h3>Call API method</h3>
<pre>
      api('test', 'example', [4]) - так работает, н овыдает ошибку API \"Array/\": API not found 'Array/'
      api_closure('test', 'example', [4]) - так работает без ошибок
</pre>
<div>
    ";
        // line 133
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->api_closure("test", "example", array(0 => 4)), "html", null, true);
        echo "
</div>

<h3>Wrong controller</h3>
<pre>
     dispatch('test', 'not-found')
</pre>
<div>
    ";
        // line 141
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->dispatch("test", "not-found"), "html", null, true);
        echo "
</div>

<h3>Wrong widget</h3>
<pre>
     widget('test', 'not-found')
</pre>
<div>
    ";
        // line 149
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->widget("test", "not-found"), "html", null, true);
        echo "
</div>

<h3>Wrong without priveleges</h3>
<pre>
     widget('test', 'acl-denied')
</pre>
<div>
    ";
        // line 157
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->widget("test", "acl-denied"), "html", null, true);
        echo "
</div>

<h3>Wrong API method</h3>
<pre>
     api('test', 'not-found')
</pre>
<div>
    ";
        // line 165
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->api("test", "not-found"), "html", null, true);
        echo "
</div>

<h3>Dispatch grid based on array</h3>
<pre>
     dispatch('test', 'grid-array')
</pre>
<div>
    ";
        // line 173
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->dispatch("test", "grid-array"), "html", null, true);
        echo "
</div>

<h3>Dispatch grid based on SQL</h3>
<pre>
     dispatch('test', 'grid-sql')
</pre>
<div>
    ";
        // line 181
        echo twig_escape_filter($this->env, $this->env->getExtension('zoqa_twig')->dispatch("test", "grid-sql"), "html", null, true);
        echo "
</div>";
    }

    public function getTemplateName()
    {
        return "Base.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  324 => 181,  313 => 173,  302 => 165,  291 => 157,  280 => 149,  269 => 141,  258 => 133,  245 => 123,  233 => 114,  221 => 105,  217 => 104,  201 => 91,  197 => 90,  193 => 89,  189 => 88,  150 => 52,  146 => 51,  142 => 50,  138 => 49,  128 => 42,  121 => 38,  117 => 37,  113 => 36,  109 => 35,  105 => 34,  101 => 33,  97 => 32,  93 => 31,  89 => 30,  85 => 29,  81 => 28,  77 => 27,  73 => 26,  69 => 25,  65 => 24,  61 => 23,  47 => 12,  43 => 11,  36 => 7,  32 => 6,  28 => 5,  19 => 1,);
    }
}
