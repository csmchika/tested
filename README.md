# tested
В index.php лежит форма для загрузки файла, методом пост файл переходит в обработчик test.php
Внутри test.php лежит 3 функции:
dbconnect - через PDO - делаем конект в БД, возвращаем PDO для работы с ним дальше
createDbTable - в ТЗ нужно было создать отдельный файл создания таблицы, собтвенно читаем этот файл и выполняем на нашей БД, создается таблица guide, переходим в save_db
save_db - Для начала конвертируем исходный csv в массив и делаем копию(для возврата, понимаю, что это огромный напряг по времени и памяти, наверняка можно сделать без него, динамически изменяя исходный csv)
Проходимся по массиву сверяя сначала правильность name через регулярное выражение, потом наличие данного id в исходной таблице, если такой id уже есть, обновляем значение, если нет, то добавляем
В копию нашего массива изначально добавили колону ERROR, потом если ошибок нет, то добавляем пустую строку, наличие ошибок проверяем в else в каждой итерации цикла
После цикла проходимся по всем линиям копии массива и put`ом отправляем в csv, после выполнения скрипта, появляется кнопка, по которой скачивется нужный csv
(скорее всего правильным решением было динамически изменять исходный csv или чтобы не проходиться лишний раз, записывать каждую строчку прямо в цикле, но у меня закончилось время)