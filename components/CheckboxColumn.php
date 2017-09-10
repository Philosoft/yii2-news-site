<?php

namespace app\components;


use yii\grid\DataColumn;
use yii\helpers\Html;

class CheckboxColumn extends DataColumn
{
    public $attribute = "active";
    public $classTemplate = "checkbox-column__checkbox--{{widgetId}}";
    public $action = "/module/controller/action";
    public $primaryKeyAttribute = "id";
    public $ajaxMethod = "POST";

    public function init()
    {
        $this->grid->view->registerJs(<<<ENDJS
        $(".{$this->getCheckboxClass()}").change(function (e) {
            e.preventDefault();

            $.ajax(
                "{$this->action}",
                {
                    type: "{$this->ajaxMethod}",
                    data: {
                        {$this->primaryKeyAttribute}: $(this).data("id")
                    }
                }
            )
            .done(function (data) {
                if (typeof data === "object" && "status" in data) {
                    $(this).prop("checked", data.status);
                }
            });
            
            return false;
        });
ENDJS
        );
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        return Html::checkbox(
            "",
            $model->{$this->attribute} == 1,
            [
                "class" => $this->getCheckboxClass(),
                "data" => [
                    "id" => $model->{$this->primaryKeyAttribute}
                ]
            ]
        );
    }

    protected function getCheckboxClass()
    {
        return str_replace(
            "{{widgetId}}",
            $this->grid->getId(),
            $this->classTemplate
        );
    }
}