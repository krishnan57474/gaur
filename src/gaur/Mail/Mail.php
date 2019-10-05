<?php

declare(strict_types=1);

namespace Gaur\Mail;

use Config\Services;

class Mail
{
    /**
     * Send email
     *
     * @param string $path view path
     * @param array  $data email data
     *
     * @return bool
     */
    public function send(string $path, array $data): bool
    {
        $msg   = view($path, $data);
        $email = Services::email();

        $email->setTo($data['to']);

        if (isset($data['cc'])) {
            $email->setCC($data['cc']);
        }

        if (isset($data['bcc'])) {
            $email->setBCC($data['bcc']);
        }

        $email->setSubject($data['subject']);
        $email->setMessage($msg);

        return $email->send();
    }
}
