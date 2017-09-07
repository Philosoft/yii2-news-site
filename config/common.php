<?php

return [
    "components" => [
        "authManager" => [
            "class" => "Da\\User\\Component\\AuthDbManagerComponent"
        ]
    ],
    "modules" => [
        "user" => [
            "class" => \Da\User\Module::class,
            "administrators" => ["thephilosoft"]
        ]
    ]
];