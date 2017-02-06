<?php
require_once "includes.php";

function DisplayRanking($userid){
    $rankedlist = GetRankedList($userid);
    $count = 1;
    foreach($rankedlist as $item){
    ?>
        <div class="rank-container">
            <div class="rank-count">
                <?php echo $count; ?>
            </div>
            <div class="rank-item-container" draggable="true">
                <div class="rank-item-title">
                    <?php echo $item->_title; ?>
                </div>
            </div>
        </div>
    <?php $count++; 
    }
}

?>