/**
 * Сервис очередей
 * Сейчас в ачестве задачи - аякс-запрос
 *
 * todo Вариант, когда запросы летят последовательно
 * todo Сначала собирать цепочку, а потом запускать
 */
define(['jquery'], function ($) {

    "use strict";

    var waterfall = {

        counter: 0,

        id: 0,

        /**
         * Очередь запросов
         * [!] Имена полей должны совпадать с имена полей массива параметров для $.ajax()
         * @type {*[]}
         */
        queue: [],

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
             */

            var i, parentId = null;

            //todo if(args.length > 0) parentId = args[0]



            for(i=0; i<this.queue.length; i++){

                if( this.queue[i].parentId == parentId && this.queue[i] == false ) {

                    $.ajax(
                        {
                            ///...
                            success: waterfall.getCall(item.success)

                            //todo запихнуть  в этот success аякс-вызов зависимого айтема
                            // т.е. в success-блоке рекурсивно вызвать
                            // waterfall.processSequental( this.queue[i].id )
                        }
                    );
                }
            }


            this.id = 0;
            this.queue = [];
        },

        /**
         * Добавляет айтем в очередь, предварительно сгенерировав колбэк
         * присваивает иму id
         *
         * [!] Допустимо указать item.parentId
         * @param item
         *
         * TODO
         */
        push: function(item){
            var itemId

            //item.success = waterfall.getCall( item.success );

            itemId = this.id++;//todo разобраться, почему не инкрементит

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

