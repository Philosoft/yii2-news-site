<?php

require __DIR__ . "/common-events.php";

return [
    "components" => [
        "authManager" => [
            "class" => "Da\\User\\Component\\AuthDbManagerComponent"
        ],
        "mailer" => [
            "class" => "yii\\swiftmailer\\Mailer",
            // send all mails to a file
            "useFileTransport" => true,
        ],
    ],
    "modules" => [
        "user" => [
            "class" => \Da\User\Module::class,
            "administrators" => ["admin"],
            "controllerMap" => [
                "admin" => "app\\controllers\\AdminUsersController"
            ],
            "classMap" => [
                "Profile" => "app\\models\Profile",
                "UserSearch" => "app\\models\\UserSearch"
            ],
            "mailParams" => [
                "fromEmail" => "philosoft@yandex.ru",
                "welcomeMailSubject" => "Welcome to yii2 news site"
            ]
        ],
    ]
];