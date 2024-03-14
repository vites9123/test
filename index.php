<?php 
require_once ($_SERVER['DOCUMENT_ROOT'] . '/module/model/test/ModelTest.php');

$TestModel = new Products($db);

?>
<div style="display: flex;">
    <div style="display: block;">
        <?php
            $data_test = $TestModel->displayGroups();
        ?>
    </div>
    
    <div style="display: block; padding-left: 50px;">
         <?php
            if (isset($_GET['group'])) {
                $groupId = $_GET['group'];
                $TestModel->displayProducts($groupId);
            } 
         ?>   
    </div>
</div>