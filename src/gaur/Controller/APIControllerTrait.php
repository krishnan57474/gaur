<?php

declare(strict_types=1);

namespace Gaur\Controller;

use Config\Services;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;
use Gaur\Security\CSRF;

trait APIControllerTrait
{
    /**
     * Input errors
     *
     * @var string[]
     */
    protected $errors;

    /**
     * Filtered inputs
     *
     * @var mixed[]
     */
    protected $finputs;

    /**
     * Handle api request
     *
     * @param string $method  method in the controller gets called
     * @param string ...$args list of arguments
     *
     * @return void
     */
    protected function api(string $method, string ...$args): void
    {
        $allowedAcceptTypes = [
            'application/json'
        ];

        if (!Services::negotiator()->media($allowedAcceptTypes, true)) {
            Response::setStatus(StatusCode::NOT_ACCEPTABLE);
            Response::setJson([
                'errors' => [ 'The requested accept type is not supported' ]
            ]);
            return;
        }

        $allowedContentTypes = [
            'application/json',
            'multipart/form-data'
        ];

        $contentType = strtolower(
            explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0] ?? ''
        );

        if ($contentType !== ''
            && !in_array($contentType, $allowedContentTypes)
        ) {
            Response::setStatus(StatusCode::UNSUPPORTED_MEDIA_TYPE);
            Response::setJson([
                'errors' => [ 'The requested content type is not supported' ]
            ]);
            return;
        }

        $this->errors  = [];
        $this->finputs = [];

        $this->{$method}(...$args);
    }

    /**
     * Check admin status
     *
     * @return bool
     */
    protected function isAdmin(): bool
    {
        $isAdmin = $_SESSION['is_admin'] ?? false;

        if (!$isAdmin) {
            Response::setStatus(StatusCode::FORBIDDEN);
            Response::setJson();
        }

        return $isAdmin;
    }

    /**
     * Check logged in status
     *
     * @return bool
     */
    protected function isLoggedIn(): bool
    {
        $isLoggedIn = isset($_SESSION['user_id']);

        if (!$isLoggedIn) {
            Response::setStatus(StatusCode::UNAUTHORIZED);
            Response::setJson();
        }

        return $isLoggedIn;
    }

    /**
     * Check not logged in status
     *
     * @return bool
     */
    protected function isNotLoggedIn(): bool
    {
        $isLoggedIn = isset($_SESSION['user_id']);

        if ($isLoggedIn) {
            Response::setStatus(StatusCode::FORBIDDEN);
            Response::setJson();
        }

        return !$isLoggedIn;
    }

    /**
     * Validate CSRF token
     *
     * @return bool
     */
    protected function isValidCsrf(): bool
    {
        $csrf = (new CSRF(__CLASS__))->validate();

        if (!$csrf) {
            Response::setStatus(StatusCode::BAD_REQUEST);
            Response::setJson([
                'errors' => [ 'Request has expired' ]
            ]);
        }

        return $csrf;
    }
}
