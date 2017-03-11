<?php if (site()->user()) : ?>
<?php $state = isset($state) ? $state : [] ?>
<?php echo pageLock($page)->script($state) ?>
<?php endif ?>
