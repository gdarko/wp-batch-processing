<?php
/* @var mixed $id - The batch id */
$batch = WP_Batch_Processor::get_instance()->get_batch( $id );
if ( is_null( $batch ) ) {
	echo 'Batch not found.';

	return;
}
$percentage = $batch->get_percentage();

$items_count     = $batch->get_items_count();
$processed_count = $batch->get_processed_count();
?>

<h1><?php echo $batch->title; ?></a></h1>

<div class="batch-process">
	<?php if ( $items_count > 0 ): ?>

        <div class="batch-process-main">
            <ul class="batch-process-stats">
                <li><strong>Total:</strong> <span id="batch-process-total"><?php echo (int) $items_count; ?></span></li>
                <li><strong>Processed:</strong> <span
                            id="batch-process-processed"><?php echo (int) $processed_count; ?></span> <span
                            id="batch-process-percentage">(<?php echo $percentage; ?>%)</span></li>
            </ul>
            <div class="batch-process-progress-bar">
				<?php
				$style = $percentage > 0 ? 'width:' . $percentage . '%' : '';
				?>
                <div class="batch-process-progress-bar-inner" style="<?php echo $style; ?>"></div>
            </div>
            <div class="batch-process-current-item">
                <?php if($batch->is_finished()): ?>
                <span class="batch-message-text--green"><?php _e('Process finished!'); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="batch-process-actions">
			<?php if ( ! $batch->is_finished() ): ?>
                <button class="button button-primary" id="batch-process-start"><?php $processed_count > 0 && $processed_count < $items_count ? _e('Continue') : _e('Start'); ?></button>
                <button class="button" id="batch-process-restart">Restart</button>
                <button class="button batch-process--button-stop" id="batch-process-stop">Stop</button>
			<?php else: ?>
                <button class="button-primary" id="batch-process-restart">Restart</button>
			<?php endif; ?>
        </div>
    <?php else: ?>
    <div class="batch-process-main">
        <h4><?php _e('Missing data!'); ?></h4>
        <p><?php _e('No items to process. Please set up your data.'); ?></p>
    </div>
	<?php endif; ?>


</div>

<div id="batch-errors" style="display: none;">
    <h3>List of errors</h3>
    <ol id="batch-errors-list">

    </ol>
</div>


<style type="text/css">
    .batch-process-stats {
        text-align: center;
        list-style: none;
    }

    .batch-process-stats li {
        display: inline-block;
        min-width: 20%;
    }

    .batch-message-text--green {
        color: green;
    }

    .batch-process-progress-bar-inner {
        position: absolute;
        background: #0073aa;
        width: 0;
        height: 100%;
        left: 0;
    }

    .batch-process-progress-bar {
        background: #f0f0f0;
        min-height: 20px;
        position: relative;
    }

    .batch-process-actions {
        padding: 10px;
        text-align: center;
    }

    .batch-process-current-item {
        text-align: center;
    }

    .batch-process-main {
        padding: 10px 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .batch-process {
        max-width: 500px;
        background: #fff;
        margin-top: 15px;
        border: 1px solid #ddd;
    }

    .batch-process-current-item {
        padding-top: 5px;
        padding-bottom: 5px;
        min-height: 12px;
    }

    .batch-process--button-stop {
        margin-left: 15px !important;
        border: none !important;
        background: #990000 !important;
        color: #ffffff !important;
    }
</style>
