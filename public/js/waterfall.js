/**
 * Сервис очередей
 * Сейчас в ачестве задачи - аякс-запрос
 *
 * todo Вариант, когда запросы летят последовательно
 * todo Сначала собирать цепочку, а потом запускать
 *
 * ? что будет, если добавить в очерь айтем в момент, когда идет обработка запросов
 */
define(['jquery'], function ($) {

    "use strict";

    var waterfall = {

        counter: 0,

        itemId: 0,

        id: 0,

        /**
         * Очередь запросов
         * [!] Имена полей должны совпадать с имена полей массива параметров для $.ajax()
         * @type {*[]}
         */
        queue: [],

        /**
         * Очередь для последовательного варианта
         */
        QueueReal: [],

        /**
         *
         * @param func - колбэк функция
         * @returns {Function}
         */
        getCall: function (func) {

            this.counter++;
            //this.id++;

            /**
             * closure
             * @var data то, что вернул аякс запрос
             */
            return function (data) {

                waterfall.counter--;

                func(data);

                if(waterfall.counter == 0) {
                    try {
                        waterfall.onDone();
                        waterfall.onDone = null;
                    } catch(e){
                        console.log(e)
                    }
                }
            }
        },

        /**
         * Генерируем водопад (запросы начинают лететь по ходу генерации, т.е. асинхронно и без задержек)
         * [!] генерируем колбэки сдесь же
         *
         *
         * id, parent_id
         *
         * [?] Для чего нужен Deferred()
         * @see https://habrahabr.ru/post/122002/, https://github.com/dio-el-claire/jquery.waterfall
         *
         */
        processParallel: function(){
            this.queue
                .map( function(entry){
                    entry.success = waterfall.getCall( entry.success );
                    return entry;
                })
                .forEach(function (entry) {
                    $.ajax(entry);
                });

            this.queue = [];
            this.id = 0;
        },

        /**
         * Используется для зависимой очереди
         * Выполняет запросы последовательно
         * TODO
         */
        processSequental: function(){
            /**
             * TODO выполнить первый айтем
             * поискать, есть ли айтемы с parentId == айтем.id, если да, выполнить их (тоже последовательно)
             * пометить item.done выполненных айтемов как true
             *
             * Перейти ко второму айтему
             *
             *
             * ...
             * parentId = null
             * Дай мне массив айтемов для данного parentId: itemArr = getArray(parentId)
             * closure = generateClosure(itemArr)
             * Запускаем запросы на выполнение: closure()
             *
             * parentId = 1
             * ...
             */

            var i, parentId = null,
                Generate = this.generate,
                item
                ;

            //if(arguments.length > 0) parentId = arguments[0];

            // бежим по queue.
            // составляем запрос для текущего айтема:  generate(item.id)
            // кладем этот код в массив this.QueueReal
            // дальше бежим по queue
            // берем только те айтемы, у которых done = false
            // по завершении выполняем все функции массива

            for(i=0; i<this.queue.length; i++){

                item = this.queue[i]

                if(
                    //this.queue[i].parentId == parentId &&
                    item.done == false ) {

                    this.QueueReal.push(

                         function() {
                            $.ajax(
                                {
                                    url: item.url,
                                    type: item.type,
                                    dataType: item.dataType,
                                    success: Generate(item.parentId)
                                }
                            );
                        }
                    )
                }
            }

            // Запустить очередь на выполнение
            var _closure = this.startQueue(this.QueueReal)
            _closure()

            // Сбросить очередь
            this.id = 0;
            this.queue = [];
            this.QueueReal = [];


            
            //todo в конце вызвать onDone
        },

        /**
         * Используется для зависимой очереди
         * @returns {Function}
         *
         */
        startQueue: function(arr){
            var i
            return function () {
                for(i=0; i < arr.length; i++){
                    arr[i]()
                }
            }
        },

        /**
         * Создает айтемы зависимой очереди
         * @param parentId
         * @returns {*}
         */
        generate: function (parentId) {
            var result = null,
                j = 0, item,
                Queue = waterfall.queue,
                Generate = waterfall.generate
                ;

            for(j; j<Queue.length; j++){
                if( Queue[j].parentId == parentId && Queue[j].done == false ){

                      Queue[j].done = true

                        item = Queue[j]

                        result = function() {
                            $.ajax(
                                {
                                    url: item.url,
                                    type: item.type,
                                    dataType: item.dataType,
                                    success: function(){
                                        waterfall.getCall(item.success);
                                        Generate( item.id )
                                    }
                                }
                            );
                        }

                }
            }

            if( result == null )
                result = function () {}

            return result;
        },

        //todo
        getArray: function (parentId) {
            return []
        },

        //todo - возвращает success-функцию
        generateClosure: function(itemArr){
            var i
            return function () {
                for(i=0; i < itemArr.length; i++){
                    itemArr[i]()
                }
            }
        },

        //todo
        generateAjax: function (parentId) {
            return function () {
                //$.ajax( ...
            }
        },

        //todo
        generateAjaxArr: function (parentId) {

        },

        /**
         * Добавляет айтем в очередь, предварительно сгенерировав колбэк
         * присваивает иму id.
         * Используется для зависимой очереди
         *
         * [!] Допустимо указать item.parentId
         *
         * @param item
         * @return itemId
         */
        push: function(item){
            var itemId

            //item.success = waterfall.getCall( item.success );

            itemId = ++this.itemId;

            if( itemId == 1 ){
                item.parentId = null;
            }

            item.id = itemId;
            item.done = false;

            this.queue.push(item)

            return itemId;
        },

        /**
         * Добавляет айтем в очередь КАК ЕСТЬ
         * @param item
         * @returns {waterfall}
         */
        add: function (item) {
            this.queue.push(item)

            return this;
        }
    };


    $(function(){
    });

    return waterfall;
});

