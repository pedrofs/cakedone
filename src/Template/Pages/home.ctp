<?php $this->layout = false; ?>


<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">

        <?php
          echo $this->element('header');
        ?>
    </head>

    <body ng-app="cakedone" class="grey lighten-4">
      <ui-view></ui-view>
    </bod>
</html>