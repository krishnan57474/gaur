<?php

declare(strict_types=1);

namespace App\Controllers;

use Gaur\Controller;
use Gaur\Controller\APIControllerTrait;
use Gaur\HTTP\FileUpload;
use Gaur\HTTP\Input;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;
use Gaur\Mail\Mail;
use Gaur\Security\CSRF;

class Enquiry extends Controller
{
    use APIControllerTrait;

    /**
     * Default page for this controller
     *
     * @return void
     */
    protected function index(): void
    {
        $data = [];

        // 60 minutes
        $data['csrf'] = (new CSRF(__CLASS__))->create(60);
        session_write_close();

        echo view('app/default/enquiry', $data);
    }

    /**
     * Submit form
     *
     * @return void
     */
    protected function submit(): void
    {
        if (!$this->validateInput()
            || !$this->validateAttachment()
        ) {
            Response::setStatus(StatusCode::BAD_REQUEST);
            Response::setJson(
                [
                    'errors' => $this->errors
                ]
            );
            return;
        }

        (new CSRF(__CLASS__))->remove();
        session_write_close();

        $message = 'Congratulations! your message has been successfully sent. We will send you a reply as soon as possible. Thank you for your interest in ' . config('Config\App')->siteName;

        if ($this->sendMail()) {
            Response::setStatus(StatusCode::OK);
            Response::setJson(
                [
                    'data' => [ 'message' => $message ]
                ]
            );
        } else {
            Response::setStatus(StatusCode::INTERNAL_SERVER_ERROR);
            Response::setJson();
        }
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

        foreach ($inputs['attach'] as $k => $attach) {
            $attachments['attachment ' . ($k + 1)] = $path . $attach;
        }

        unset($inputs['attach']);

        $data = [
            'to'          => 'contact@example.com',
            'subject'     => 'Contact enquiry',
            'inputs'      => $inputs,
            'attachments' => $attachments
        ];

        $status = Mail::send(
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
        $rfields = [
            'name',
            'email',
            'phone',
            'message'
        ];

        foreach ($rfields as $field) {
            $this->finputs[$field] = Input::data($field);

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

    /**
     * Validate attachment
     *
     * @return bool
     */
    protected function validateAttachment(): bool
    {
        $fileUpload = new FileUpload();

        $this->finputs['attach'] = $fileUpload->upload(
            [
                'count' => 1,
                'index' => false,
                'name'  => 'attach',
                'path'  => FCPATH . 'assets/enquiry/',
                'size'  => '10MB',
                'types' => ['jpeg', 'jpg', 'png']
            ]
        );

        if ($fileUpload->getError()) {
            $this->errors[] = $fileUpload->getError();
        } elseif (!$this->finputs['attach']) {
            $this->errors[] = 'No attachment found';
        }

        return !$this->errors;
    }
}
