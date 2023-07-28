<?php
    require_once 'backend/sdbh.php';
    $dbh = new sdbh();

?>
<html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container">
            <div class="row row-header">
                <div class="col-12">
                    <img src="assets/img/logo.png" alt="logo" style="max-height:50px"/>
                    <h1>Прокат</h1>
                </div>
            </div>
            
            <!-- TODO: реализовать форму расчета -->
            <div class="row row-form">
                <div class="col-12">
                    <h4>Форма расчета:</h4>
                    <p></p>
                </div>
            </div>
            <div class="container super-form">
                <div class="row row-body">
                    <div class="col-3">
                        <span style="text-align: center">Форма обратной связи</span>
                        <i class="bi bi-activity"></i>
                    </div>
                    <div class="col-9">
                        <form action="" method="get" id="form">
                                <label class="form-label" for="product">Выберите продукт:</label>
                                <select class="form-select" name="product" id="product">
                                    <?
                                    //присваиваем переменной массив со всеми значениями NAME
                                    $autos = $dbh->select_property('a25_products', 'NAME');
                                    //перебираем каждую запись и выводим значение в options 
                                    foreach ($autos as $auto) {
                                    ?><option><? echo $auto['NAME']?></option><?}      
                                    ?>
                                </select>
    
                                <label for="customRange1" class="form-label">Количество дней:</label>
                                <input type="text" name="time" class="form-control" id="customRange1" min="1" max="30">
    
                                <label for="customRange1" class="form-label">Дополнительно:</label>
    
                                
                                <?
                                    //присваиваем массив по аналогии с названиями, но с десериализацией.
                                    $services = unserialize($dbh->mselect_rows('a25_settings', ['set_key' => 'services'], 0, 1, 'id')[0]['set_value']);
         
                                    //перебираем весь массив и выводим на экран в виде чекбоксов, присваивая id каждому чекбоксу. с каждой новою записью id увеличивается на 1, чтобы id был уникальным
                                    
                                    foreach($services as $k => $s) {
                                     $id++; 
                                ?>
                                    <div class="form-check"><input class="form-check-input" name="dop[]" type="checkbox" value="<?=$s?>" id="<?=$id?>" >
                                        <label class="form-check-label" for="<?=$id?>"><?=$k?></label>
                                    </div>
                                <?
                                    
                                    }
                                    //проверяем отмеченные чекбоксы один за другим. Если чекбокс отмечен, добавляем его значение к переменной $serv 
                                    if (isset($_GET['dop']) && is_array($_GET['dop'])) {
                                        foreach ($_GET['dop'] as $selected) {
                                            $serv += intval($selected);
                                        }
                                    }
                                ?>
                                    
    
                                <button type="submit" class="btn btn-primary">Рассчитать</button>
    
                        </form>
                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-3 no-color"></div>
                <div class="results col-9">
                <?
                    //переменная $days подтягивает количество дней, введеное в форме. $massive подтягивает значения поля TARIFF в соответсвии с введенным названием товара, преобразовывая его в массив
                    $days = $_GET['time'];
                    $massive = unserialize($dbh->mselect_rows('a25_products', ['NAME' => $_GET['product']], 0, 1, 'id')[0]['TARIFF']);
                    //далее проверяется, отправилось ли поля с днями в GET, если да, то проверяется корректность ввода, это должно быть целое число больше нуля
                    if (isset($_GET['time'])){
                        if (is_numeric($days) && $days > 0){
                            //Внутри цикла проверяется, если значение переменной `$days` больше или равно текущему ключу `$key`. Если условие выполняется, переменной `$closestKey` присваивается значение текущего ключа.
                            if (!empty($massive)) {
                                $final = 0;
                                $closestKey = null;
                                foreach ($massive as $key => $value) {
                                    if ($days>=$key) {
                                        $closestKey = $key;
                                    }
                                }
                                if ($closestKey !== null) {
                                    // Если переменная `$closestKey` не равна `null`, идет вычисление общей суммы
                                    $final = ($massive[$closestKey] + $serv) * $days;
                                }
                                echo 'Ваш тариф: ', $massive[$closestKey], '<br/>';
                                echo 'Доп услуги: ', $serv * $days, '<br/>';
                                echo 'Итоговая цена: ', $final;
                            } else {
                                //если в колонке TARIFF значение не указано, то $massive будет пустой. В таком случае выбирается значение PRICE и идет расчет по ней.
                                $price = $dbh->mselect_rows('a25_products', ['NAME' => $_GET['product']], 0, 1, 'id')[0]['PRICE'];
                                echo 'Ваш тариф: ', $price, '<br/>';
                                echo 'Доп услуги: ', $serv * $days, '<br/>';
                                echo 'Итоговая цена: ', ($price + $serv) * $days;
                            }
                        } else {
                            echo 'Введите корректное количество дней';
                        }
                    }
                ?>
                </div>
            </div>
        </div>
    </body>
</html>