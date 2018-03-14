<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=App::$app->settings->printCssFiles()?>
    <?=App::$app->settings->printJsFiles()?>
    <title><?=$this->title?></title>
</head>
<body class="bg-light">
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand"><?=App::$app->settings->numeAplicatie?></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
              <?php foreach(App::$app->settings->meniu as $text => $link): ?>
              <li><a href="<?=Helpers::generateUrl($link)?>"><?=ucfirst($text)?></a></li>
              <?php endforeach; ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    
    <div class="container">
        <?=$this->content?>
        <hr>
        <footer class="text-center"><strong><?=App::$app->settings->numeAplicatie?></strong>, Autori: <?=App::$app->settings->autori?></footer>
    </div>
    
</body>

</html>