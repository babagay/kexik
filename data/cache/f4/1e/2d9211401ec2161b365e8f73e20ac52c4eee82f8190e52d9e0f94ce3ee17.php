<?php

/* partial/twig_aside_right.phtml */
class __TwigTemplate_f41e2d9211401ec2161b365e8f73e20ac52c4eee82f8190e52d9e0f94ce3ee17 extends Twig_Template
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
        return array (  28 => 148,  180 => 95,  173 => 91,  168 => 88,  166 => 86,  163 => 85,  140 => 64,  124 => 56,  118 => 53,  114 => 52,  110 => 51,  106 => 50,  100 => 47,  88 => 41,  84 => 40,  80 => 39,  76 => 38,  72 => 37,  65 => 33,  61 => 32,  56 => 30,  52 => 29,  48 => 28,  44 => 27,  36 => 25,  30 => 22,  25 => 21,  167 => 77,  165 => 76,  160 => 73,  158 => 61,  156 => 60,  146 => 52,  134 => 44,  129 => 58,  125 => 40,  111 => 39,  108 => 38,  105 => 37,  103 => 36,  99 => 35,  96 => 46,  93 => 33,  86 => 31,  79 => 29,  75 => 28,  58 => 27,  55 => 26,  53 => 25,  50 => 24,  45 => 16,  43 => 15,  40 => 26,  37 => 12,  33 => 10,  31 => 9,  26 => 5,  24 => 5,  21 => 3,  19 => 2,);
    }
}
