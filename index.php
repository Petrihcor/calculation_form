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
            <div class="row row-body">
                <div class="col-12">
   
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
                                //использую свою функцию на основе mselect_rows для отображения названий всех имеющихся товаров
                                //здесь обьявляется переменная, которой присвоен массив. В этом объекте данные из базы данных.
                                //А именно происходит SQL запрос с названием таблицы и стобцом, который необходимо вывести 
                                $autos = $dbh->denis('a25_products', 'NAME');
                                //здесь происходит перебор каждой записи и вывод на экран. В итоге выводится элемент 
                                foreach ($autos as $auto) {
                                ?><option><? echo $auto['NAME']?></option><?}      
                                ?>
                            </select>

                            <label for="customRange1" class="form-label">Количество дней:</label>
                            <input type="text" name="time" class="form-control" id="customRange1" min="1" max="30">

                            <label for="customRange1" class="form-label">Дополнительно:</label>

                            
                            <?
                            $services = unserialize($dbh->mselect_rows('a25_settings', ['set_key' => 'services'], 0, 1, 'id')[0]['set_value']);
                            $id = 0;
                            $total = 0;
                            foreach($services as $k => $s) {
                             $id++; 
                            ?>
                                <div class="form-check"><input class="form-check-input" name="dop[]" type="checkbox" value="<?=$s?>" id="<?=$id?>" >
                                    <label class="form-check-label" for="<?=$id?>"><?=$k?></label>
                                </div>
                            <?
                                
                            }
                            if (isset($_GET['dop']) && is_array($_GET['dop'])) {
                                foreach ($_GET['dop'] as $selected) {
                                    $serv += intval($selected);
                                }
                            }
                            ?>
                                

                            <button type="submit" class="btn btn-primary">Рассчитать</button>

                    </form>
                    <?
                    $days = $_GET['time'];
                    $massive = unserialize($dbh->mselect_rows('a25_products', ['NAME' => $_GET['product']], 0, 1, 'id')[0]['TARIFF']);
                    if (isset($_GET['time']) && is_numeric($days) && $days > 0){
                        //сейчас выводит только последний чекбокс, т.к. id = 4

                        if (!empty($massive)) {
                            $final = 0;
                            $closestKey = null;
                            foreach ($massive as $key => $value) {
                                if ($days>=$key) {
                                    $closestKey = $key;
                                }
                            }
                             if ($closestKey !== null) {
                                $final = ($massive[$closestKey] + $serv) * $days;
                            }
                            echo $final;
                        } else {
                            $price = $dbh->mselect_rows('a25_products', ['NAME' => $_GET['product']], 0, 1, 'id')[0]['PRICE'];
                            echo ($price + $serv) * $days;
                        }
                    }
                    ?>
                </div>
            </div>
        </div> 
        </div>
    </body>
</html>