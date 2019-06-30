<?php
/* @var mixed $id - The batch id */
?>
<div class="wrap">
    <div class="batch-single">
    	<?php WP_BP_Helper::render('batch-manage', array('id' => $id)); ?>
    </div>
</div>