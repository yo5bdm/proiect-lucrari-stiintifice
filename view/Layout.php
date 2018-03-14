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
    <nav class="navbar navbar-default navbar-fixed-top">
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
              <?php if(App::$app->user->isLoggedIn()): ?>
              <?php foreach(App::$app->settings->meniuLogat as $text => $link): ?>
              <li><a href="<?=Helpers::generateUrl($link)?>"><?=ucfirst($text)?></a></li>
              <?php endforeach; ?>
              <li><a href="<?=Helpers::generateUrl(["c"=>"site","a"=>"profil"])?>"><?=App::$app->user->getName()?></a></li>
              <?php else: ?>
              <?php foreach(App::$app->settings->meniuVizitator as $text => $link): ?>
              <li><a href="<?=Helpers::generateUrl($link)?>"><?=ucfirst($text)?></a></li>
              <?php endforeach; ?>
              <?php endif; ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    
    <div class="container">
        <?=$this->mesaj()?>
        <?=$this->content?>
        <hr>
        <footer class="navbar navbar-default navbar-fixed-bottom">
            <div class="container">
                <p class="text-muted" style="padding-top: 17px;">Autori: <?=App::$app->settings->autori?></p>
            </div>
        </footer>
    </div>
<!--    <pre>
        <?php //print_r($_SESSION); ?>
    </pre>-->
</body>

</html>