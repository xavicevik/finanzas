<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send any email
    | messages sent by your application. Alternative mailers may be setup
    | and used as needed; however, this mailer will be used by default.
    |
    */

    'default' => env('MAIL_MAILER', 'smtp'),

    'resetpassword' => [
        'subject' => env('RESET_PASS_SUBJECT', 'Reset Password Notification'),
        'line1' => env('RESET_PASS_LINE1', 'Usted ha recibido este correo porque recibimos una solicitud de reseteo de contraseña para su cuenta.'),
        'action' => env('RESET_PASS_ACTION', 'Resetear contraseña'),
        'line2' => env('RESET_PASS_LINE2', 'Este enlace de cambio de contraseña expira en :count minutos.'),
        'line3' => env('RESET_PASS_LINE3', 'Si no requiere cambio de contraseña, no debe realizar ningúna acción.'),
    ],

    'solicitudes' => [
        'registrar' => [
            'subject' => env('REGISTRAR_PASS_SUBJECT', 'Registro de Solicitud de Aval'),
            'line1' => env('REGISTRAR_PASS_LINE1', 'Se ha realizado el registro de una solicitud de Aval.'),
            'action' => env('REGISTRAR_PASS_ACTION', 'Solicitud de Aval'),
            'line2' => env('REGISTRAR_PASS_LINE2', 'Se registró la solicitud '),
            'line3' => env('REGISTRAR_PASS_LINE3', 'La solicitud se encuentra pendiente de revisión'),
        ],
        'revisar' => [
            'subject' => env('REVISAR_PASS_SUBJECT', 'Revisión de solicitud de Aval'),
            'line1' => env('REVISAR_PASS_LINE1', 'Señor usuario, se ha realizado la revisión de la solicitud de aval registrada.'),
            'action' => env('REVISAR_PASS_ACTION', 'Revisión de solicitud'),
            'line2' => env('REVISAR_PASS_LINE2', 'sa revisó la solicitud '),
            'line3' => env('REVISAR_PASS_LINE3', 'La solicitud se encuentra en proceso'),
        ],
        'avalar' => [
            'subject' => env('AVALAR_PASS_SUBJECT', 'Aprobación de solicitud de Aval'),
            'line1' => env('AVALAR_PASS_LINE1', 'Señor usuario, se ha realizado la aprobación de la solicitud de aval registrada.'),
            'action' => env('AVALAR_PASS_ACTION', 'Aprobación de solicitud'),
            'line2' => env('AVALAR_PASS_LINE2', 'sa aprobó la solicitud'),
            'line3' => env('AVALAR_PASS_LINE3', 'La solicitud se encuentra en proceso de aval'),
        ],
        'rechazar' => [
            'subject' => env('RECHAZAR_PASS_SUBJECT', 'Rechazo de solicitud de Aval'),
            'line1' => env('RECHAZAR_PASS_LINE1', 'Se ha rechazado la solicitud de aval, por favor valide los datos ingresados y/o contacte al encargado'),
            'action' => env('RECHAZAR_PASS_ACTION', 'Rechazo solicitud'),
            'line2' => env('RECHAZAR_PASS_LINE2', 'Se ha rechazado la solicitud.'),
            'line3' => env('RECHAZAR_PASS_LINE3', 'Si la solicitud fue rechazada por el revisor, validar los archivos ingresados'),
        ],
        'actualizar' => [
            'subject' => env('ACTUALIZAR_PASS_SUBJECT', 'Actualización de solicitud de Aval'),
            'line1' => env('ACTUALIZAR_PASS_LINE1', 'Se ha realizado la actualización de la solicitud de aval, por favor valide los datos ingresados y/o contacte al encargado'),
            'action' => env('ACTUALIZAR_PASS_ACTION', 'Actualización de  solicitud'),
            'line2' => env('ACTUALIZAR_PASS_LINE2', 'Se ha actualizado la solicitud.'),
            'line3' => env('ACTUALIZAR_PASS_LINE3', 'La solicitud se encuentra en pendiente de revisión'),
        ],
        'anular' => [
            'subject' => env('ANULAR_PASS_SUBJECT', 'Anulación de solicitud de Aval'),
            'line1' => env('ANULAR_PASS_LINE1', 'Señor usuario, se ha realizado la anulación de la solicitud de aval registrada.'),
            'action' => env('ANULAR_PASS_ACTION', 'Anulación de solicitud'),
            'line2' => env('ANULAR_PASS_LINE2', 'sa anuló la solicitud'),
            'line3' => env('ANULAR_PASS_LINE3', 'La solicitud se encuentra en anulada'),
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers to be used while
    | sending an e-mail. You will specify which one you are using for your
    | mailers below. You are free to add additional mailers as required.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses",
    |            "postmark", "log", "array", "failover"
    |
    */

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'mailgun' => [
            'transport' => 'mailgun',
        ],

        'postmark' => [
            'transport' => 'postmark',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all e-mails sent by your application to be sent from
    | the same address. Here, you may specify a name and address that is
    | used globally for all e-mails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    |
    | If you are using Markdown based email rendering, you may configure your
    | theme and component paths here, allowing you to customize the design
    | of the emails. Or, you may simply stick with the Laravel defaults!
    |
    */

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
