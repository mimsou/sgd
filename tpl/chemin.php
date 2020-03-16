<ol class="breadcrumb">
    <?php for ($i = 0; $i < count($url_array); $i++): ?>
        <?php if($i!=count($url_array)-1): ?>
        <li><a href="<?php echo $url_array[$i]; ?>" ><?php echo $task_name_array[$i] ?></a></li>
        <?php else : ?>
        <li><?php echo $task_name_array[$i] ?></li>
        <?php endif; ?>
    <?php endfor; ?>
</ol>

