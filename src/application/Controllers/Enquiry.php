<?php

declare(strict_types=1);

namespace App\Controllers;

use Gaur\{
    Controller,
    Controller\AjaxUploadControllerTrait,
    HTTP\Input,
    HTTP\UploadCache,
    Mail\Mail,
    Security\CSRF
};

class Enquiry extends Controller
{
    use AjaxUploadControllerTrait;

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

        (new UploadCache(__CLASS__))->create();

        // 60 minutes
        $data['csrf'] = (new CSRF(__CLASS__))->create(60);
        session_write_close();

        echo view('app/default/enquiry', $data);
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
        $csrf = new CSRF(__CLASS__);

        if (!$csrf->validate()) {
            $response['status'] = false;
            return;
        }

        if (!$this->validateInput()) {
            $this->removeAttachment(__CLASS__);
            $response['errors'] = $this->errors;
            return;
        }

        $this->assembleAttachment(
            __CLASS__,
            'attach',
            FCPATH . 'assets/enquiry/'
        );

        if ($this->sendMail()) {
            $response['data'] = 'Congratulations! your message has been successfully sent. We will send you a reply as soon as possible. Thank you for your interest in ' . config('Config\App')->siteName;
        } else {
            $response['errors'] = [
                'Oops! something went wrong please try again later'
            ];
        }

        $csrf->remove();
        session_write_close();
    }

    /**
     * Ajax file upload
     *
     * @param array $response ajax response
     *
     * @return void
     */
    protected function aactionUpload(array &$response): void
    {
        if (!(new CSRF(__CLASS__))->validate()) {
            $response['status'] = false;
            return;
        }

        $uploadCache = new UploadCache(__CLASS__);
        $uploadCount = count($uploadCache->get());
        $uploadLimit = 1;
        $afield      = 'attach';

        $this->finputs[$afield] = null;

        if ($uploadCount + 1 <= $uploadLimit) {
            $this->validateAttachment(
                $afield,
                [
                    'count' => 1,
                    'index' => false,
                    'name'  => $afield,
                    'size'  => '10MB',
                    'types' => ['jpeg', 'jpg', 'png']
                ]
            );
        } else {
            $this->errors[] = 'Maximum number of files exceeded';
        }

        if ($this->finputs[$afield]) {
            $uploadCache->add(
                $uploadCount,
                $this->finputs[$afield][0]
            );
        } elseif ($this->errors) {
            $this->removeAttachment(__CLASS__);
            $response['errors'] = $this->errors;
        }

        session_write_close();

        $response['data'] = !$this->errors;
    }

    /**
     * Send email
     *
     * @return bool
     */
    protected function sendMail(): bool
    {
        helper('xhtml');

        $inputs      = array_slice($this->finputs, 0);
        $attachments = [];
        $path        = 'assets/enquiry/';

        foreach ($inputs['attach'] as $k => $v) {
            $attachments['attachment ' . ($k + 1)] = $path . $v;
        }

        unset($inputs['attach']);

        $data = [
            'to'          => 'contact@example.com',
            'subject'     => 'Contact enquiry',
            'inputs'      => $inputs,
            'attachments' => $attachments
        ];

        $status = (new Mail())->send(
            'email/default/contact',
            $data
        );

        return $status;
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

        if (!(new UploadCache(__CLASS__))->get()) {
            $this->errors[] = 'No attachment found!';
            goto exitValidation;
        }

        exitValidation:
        return !$this->errors;
    }
}
