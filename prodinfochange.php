<?php
    require(dirname(__FILE__).'/config/config.inc.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $columntoreplace = $_POST["columntoreplace"];
        $changetxt = $_POST["changetxt"];
        $tochangetxt = $_POST["tochangetxt"];

        $sql = "UPDATE `nb_product_lang` pl 
        JOIN `nb_product` p ON pl.id_product = p.id_product 
        JOIN `nb_product_attribute` pv ON p.id_product = pv.id_product 
        SET ".$columntoreplace." = replace($columntoreplace, '$changetxt', '$tochangetxt')";


        if (!Db::getInstance()->execute($sql)) {           
            die('Erro');
        } else {
            echo 'Completo';
        }
    } else {
        echo'
        <!doctype html>
        <html lang="en">
          <head>
            <!-- Required meta tags -->
            <meta charset="utf-8">

            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <!-- Bootstrap CSS -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
            <title>Hello, world!</title>
          </head>
          <body>

          <div style="max-width:500px;margin:100px auto;">
            <div style="margin-bottom:20px;">
                <img src="/img/net-bricolage-logo_invoice-1652882795.jpg"/>
            </div>

            <h1>
                Alterar textos em massa
            </h1>

            <form method="post" action="'.$_SERVER["PHP_SELF"].'">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Alterar </label>

                        <select class="form-control" name="columntoreplace">
                            <option value="pl.name">Titulo</option>
                            <option value="p.reference">Referência</option>
                            <option value="pv.reference">Referência Declinações</option>
                            <option value="pl.description_short">Resumo</option>
                            <option value="pl.description">Descrição</option>
                            <option value="pl.link_rewrite">URL</option>
                            <option value="pl.meta_title">Meta Titulo</option>
                            <option value="pl.meta_description">Meta descrição</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">Trocar</label>
                        <input class="form-control" type="text" id="fname" name="changetxt">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">Por</label>
                        <input class="form-control" type="text" id="fname" name="tochangetxt">
                    </div>

                    <button class="btn btn-primary" type="submit" name="update">Submit</button>
                </form>
            </div>

            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
          </body>
        </html>';

    }
?>
