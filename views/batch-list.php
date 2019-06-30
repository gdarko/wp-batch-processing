<div class="wrap">
    <h2><?php _e( 'Batches', 'wp-batch-processing' ); ?></a></h2>
    <form method="post">
        <input type="hidden" name="page" value="batch_runner_list_table">
        <?php
        $list_table = new WP_BP_List_Table();
        $list_table->prepare_items();
        $list_table->display();
        ?>
    </form>
</div>