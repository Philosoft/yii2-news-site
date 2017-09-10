<?php

/**
 * @var \yii\web\View $this
 */

use app\modules\news\helpers\PaginationHelper;
use yii\helpers\Html;

?>

    <div class="row">
        <div class="col-md-3">
            <?php
            echo Html::tag("span", "Choose news per page ");
            echo Html::dropDownList(
                "name",
                PaginationHelper::getCurrentPageSize(),
                PaginationHelper::getAllowedPageSizesSelection(),
                [
                    "id" => "select-page-size"
                ]
            );
            ?>
        </div>
    </div>
<?php

$this->registerJs(<<<ENDJS
    $("#select-page-size").change(function () {
        $.get("/news/news/set-page-size?pageSize=" + $(this).val()).done(function () { window.location.reload(); });
    });
ENDJS
);