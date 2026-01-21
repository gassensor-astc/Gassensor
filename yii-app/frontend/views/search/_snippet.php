<?php
/* @var $this yii\web\View */
/* @var $q string */
/* @var $url string */
/* @var $texts string[] */

?>

<?php foreach ($texts as $str): ?>

    <?php if (mb_stripos($str, $q) !== false): ?>
        <div class="snippet m-1 p-1">
          <a href="<?= $url ?>" target="_blank">
              <?= preg_replace("($q)iu", "<b>$q</b>", strip_tags($str)) ?>
          </a>
        </div>

        <?php break; ?>

    <?php endif; ?>

<?php endforeach; ?>


