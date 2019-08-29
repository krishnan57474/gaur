<?php

declare(strict_types=1);

namespace Gaur\Controller;

use Gaur\HTTP\Input;

trait AjaxControllerTrait
{
    /**
     * Input errors
     *
     * @var array
     */
    protected $errors;

    /**
     * Filtered inputs
     *
     * @var array
     */
    protected $finputs;

    /**
     * Ajax action
     *
     * @return void
     */
    protected function aaction(): void
    {
        $action   = (new Input())->post('action');
        $response = [
            'status' => false
        ];

        $this->errors  = [];
        $this->finputs = [];

        if (ctype_alnum($action)) {
            $action = ucfirst($action);
        } else {
            $action = '';
        }

        if ($action !== ''
            && method_exists($this, 'aaction' . $action)
        ) {
            $response['status'] = true;
            $this->{'aaction' . $action}($response);
        }

        $this->response->setJSON($response)->send();
    }

    /**
     * Handle ajax request
     *
     * @return bool
     */
    protected function isAjaxRequest(): bool
    {
        if ($this->request->getMethod() !== 'post'
            || !$this->request->isAJAX()
            || (new Input())->post('j-ar') !== 'r'
        ) {
            return false;
        }

        $this->aaction();
        return true;
    }
}
