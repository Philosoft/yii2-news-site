<?php

/**
 * @var \app\modules\notification\models\Notification[] $notifications
 * @var \yii\web\View $this
 */
foreach ($notifications as $notification):
    ?>
    <div class="alert alert-success">
        <button
            type="button"
            class="close notification__dismiss-button"
            data-dismiss="alert"
            aria-label="Close"
            data-notification-id="<?= $notification->id ?>"
            data-notification-allow-dismiss="0"
            data-notification-dismiss-url="/notification/notification/dismiss"
        >
            <span aria-hidden="true">&times;</span>
        </button>
        <?= $notification->text ?>
    </div>
<?php
endforeach;

$statusOk = \app\modules\notification\controllers\NotificationController::STATUS__OK;

$this->registerJs(<<<ENDJS
$(".notification__dismiss-button").click(function (e) {
    var \$this = $(this);
    var allowDismiss = \$this.data("notification-allow-dismiss") === true;
    
    if (!allowDismiss) {
        e.preventDefault();
        
        $.ajax(
            \$this.data("notification-dismiss-url"),
            {
                method: "GET",
                data: {id: \$this.data("notification-id")}
            }
        )
        .done(function (data) {
            if (
                typeof data === "object"
                && "status" in data
                && data.status == "$statusOk"
            ) {
                \$this.data("notification-allow-dismiss", true);
                \$this.trigger("click");
            }
        });
        
        return false;
    }
});
ENDJS
);