<?php

declare(strict_types=1);

namespace App\Controllers;

use Gaur\{
    Controller,
    Controller\AjaxControllerTrait,
    HTTP\Input,
    Mail\Mail,
    Security\CSRF
};

class Contact extends Controller
{
    use AjaxControllerTrait;

    /**
     * Default page for this controller
     *
     * @return void
     */
    protected function index(): void
    {
        if ($this->isAjaxRequest()) {
            return;
        }

        $data = [];

        // 60 minutes
        $data['csrf'] = (new CSRF())->create(__CLASS__, 60);
        session_write_close();

        echo view('app/default/contact', $data);
    }

    /**
     * Ajax form submit
     *
     * @param array $response ajax response
     *
     * @return void
     */
    protected function aactionSubmit(array &$response): void
    {
        $csrf = new CSRF();

        if (!$csrf->validate(__CLASS__)) {
            $response['status'] = false;
            return;
        }

        if (!$this->validateInput()) {
            $response['errors'] = $this->errors;
            return;
        }

        if ($this->sendMail()) {
            $response['data'] = 'Congratulations! your message has been successfully sent. We will send you a reply as soon as possible. Thank you for your interest in ' . config('Config\App')->siteName;
        } else {
            $response['errors'] = [
                'Oops! something went wrong please try again later'
            ];
        }

        $csrf->remove(__CLASS__);
        session_write_close();
    }

    /**
     * Send email
     *
     * @return bool
     */
    protected function sendMail(): bool
    {
        $data = [
            'to'      => 'contact@example.com',
            'subject' => 'Contact enquiry',
            'inputs'  => $this->finputs
        ];

        return (new Mail())->send('email/default/contact', $data);
    }

    /**
     * Validate user inputs
     *
     * @return bool
     */
    protected function validateInput(): bool
    {
        $input   = new Input();
        $rfields = [
            'name',
            'email',
            'phone',
            'message'
        ];

        foreach ($rfields as $field) {
            $this->finputs[$field] = $input->post($field);

            if ($this->finputs[$field] === '') {
                $this->errors[] = 'Please fill all required fields!';
                goto exitValidation;
            }
        }

        if (mb_strlen($this->finputs['name']) > 32) {
            $this->errors[] = 'Name must be less than 33 characters!';
            goto exitValidation;
        }

        if (!filter_var($this->finputs['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Email address does not appear to be valid!';
            goto exitValidation;
        } elseif (mb_strlen($this->finputs['email']) > 254) {
            $this->errors[] = 'Email address must be less than 255 characters!';
            goto exitValidation;
        }

        if (!ctype_digit($this->finputs['phone'])) {
            $this->errors[] = 'Phone number does not appear to be valid!';
            goto exitValidation;
        } elseif (strlen($this->finputs['phone']) !== 10) {
            $this->errors[] = 'Phone number must be 10 digits!';
            goto exitValidation;
        }

        if (mb_strlen($this->finputs['message']) > 1024) {
            $this->errors[] = 'Message must be less than 1025 characters!';
            goto exitValidation;
        }

        exitValidation:
        return !$this->errors;
    }
}