<?php

declare(strict_types=1);

return static function (Symfony\Config\FrameworkConfig $frameworkConfig): void {
    $frameworkConfig->mailer()
        ->dsn('%env(MAILER_DSN)%')
        ->envelope([
            'sender' => '%mailer.sender_name% <%mailer.sender_email%>',
            'recipients' => '%mailer.recipients%',
        ])
    ;
};
