/**
 * Created by Julia on 17.02.2015.
 */

window.onload = function() {
    //привязка кнопок к функции changeActive(), которая ставит active на текущей кнопке
    var links = document.getElementsByClassName('jsCaller');
    for (var i = 0; i < links.length; i++) {
        var param = links[i].getAttribute('param');
        //links[i].setAttribute('onclick', 'changeActive(' + param + '); return false;');
        links[i].setAttribute('onclick', 'return false;');
    }
}

//Установить сотояние active на кнопке, которая была выбрана, чтобы применить стили (окрасить в телесный цвет кнопку)
function changeActive(param){
    var clean_container = document.getElementById('main-navigation').getElementsByTagName('li');
    for (var i = 0; i < clean_container.length; i++) {
       var clean_span = clean_container.item(i).getElementsByTagName('span').item(0);
        clean_span.setAttribute('class', 'jsCaller');
    }
    var span_container = document.getElementsByClassName('jsCaller').item(param);
    span_container.className='active';
}

function showSelect(){
    document.getElementById('select-no-display').setAttribute('display','none');
}

require(["jquery"], function($) {

});

