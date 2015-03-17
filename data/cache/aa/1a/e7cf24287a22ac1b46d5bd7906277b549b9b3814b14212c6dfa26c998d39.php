<?php

/* partial/twig_aside_right.phtml */
class __TwigTemplate_aa1ae7cf24287a22ac1b46d5bd7906277b549b9b3814b14212c6dfa26c998d39 extends Twig_Template
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
<aside id=\"right\">
                <div class=\"cabinet\"><a href=\"\" >Войти в кабинет</a></div>
                <div class=\"basket\"><a href=\"\" >Ваша корзина</a></div>
                <div class=\"partners\">
                    <img src=\"public/images/bank.png\">
                    <img src=\"public/images/master.png\" class=\"middle\">
                    <img src=\"public/images/post.png\">
                </div>

                <div class=\"cabnet-form\">
                    <div class=\"select-cabnet\"><span>Личный кабинет:  <span>&#9660;</span>  </span>
                        <ul class=\"select-no-display\">
                            <li><a href=\"\">Система СКИДОК</a></li>
                            <li><a href=\"\">Пополнение счета</a></li>
                        </ul>
                    </div>
                    <p>Текущая скидка: <span class=\"discount\">0 %</span></p>
                    <p>Баланс: <span class=\"discount\">3000</span>грн.</p>
                    <p>До ПОДАРКА: <span class=\"discount\">10</span> заказов</p>
                    <button> ВЫХОД</button>
                </div>
</aside>";
    }

    public function getTemplateName()
    {
        return "partial/twig_aside_right.phtml";
    }

    public function getDebugInfo()
    {
        return array (  230 => 84,  227 => 83,  213 => 82,  210 => 81,  206 => 79,  203 => 78,  201 => 77,  196 => 76,  178 => 75,  176 => 74,  163 => 63,  157 => 60,  154 => 59,  150 => 57,  145 => 50,  140 => 48,  135 => 45,  133 => 44,  130 => 43,  128 => 42,  126 => 41,  119 => 37,  113 => 36,  108 => 34,  102 => 33,  97 => 31,  91 => 30,  86 => 28,  80 => 27,  75 => 25,  69 => 24,  64 => 22,  58 => 21,  53 => 19,  47 => 18,  29 => 9,  22 => 4,  19 => 2,);
    }
}
