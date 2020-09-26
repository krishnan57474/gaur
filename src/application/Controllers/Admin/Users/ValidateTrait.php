<?php

declare(strict_types=1);

namespace App\Controllers\Admin\Users;

use App\Models\Admin\Users\User;

trait ValidateTrait
{
    /**
     * Validate admin
     *
     * @return bool
     */
    protected function validateAdmin(): bool
    {
        if (!ctype_digit($this->finputs['admin'])
            || $this->finputs['admin'] > 1
        ) {
            $this->errors[] = 'Admin does not appear to be valid!';
        }

        return !$this->errors;
    }

    /**
     * Validate identity
     *
     * @return bool
     */
    protected function validateIdentity(): bool
    {
        if (!ctype_alnum($this->finputs['username'])) {
            $this->errors[] = 'Username does not appear to be valid!';
            goto exitValidation;
        } elseif (strlen($this->finputs['username']) < 3
            || strlen($this->finputs['username']) > 32
        ) {
            $this->errors[] = 'Username must be between 3 and 32 characters!';
            goto exitValidation;
        }

        if (!filter_var($this->finputs['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Email address does not appear to be valid!';
            goto exitValidation;
        } elseif (mb_strlen($this->finputs['email']) > 128) {
            $this->errors[] = 'Email address must be less than 129 characters!';
            goto exitValidation;
        }

        exitValidation:
        return !$this->errors;
    }

    /**
     * Validate password
     *
     * @return bool
     */
    protected function validatePassword(): bool
    {
        if ($this->finputs['password'] !== ''
            && (mb_strlen($this->finputs['password']) < 4
            || mb_strlen($this->finputs['password']) > 64)
        ) {
            $this->errors[] = 'Password must be between 4 and 64 characters!';
            goto exitValidation;
        }

        if ($this->finputs['password'] !== $this->finputs['password-confirm']) {
            $this->errors[] = 'Password confirmation does not match the password!';
            goto exitValidation;
        }

        exitValidation:
        return !$this->errors;
    }

    /**
     * Validate status
     *
     * @return bool
     */
    protected function validateStatus(): bool
    {
        if (!ctype_digit($this->finputs['status'])
            || $this->finputs['status'] > 1
        ) {
            $this->errors[] = 'Status does not appear to be valid!';
        }

        return !$this->errors;
    }

    /**
     * Validate username and email address
     *
     * @param int $id user id
     *
     * @return bool
     */
    protected function validateUser(int $id = 0): bool
    {
        $user     = new User();
        $userInfo = null;

        if ($id) {
            $userInfo = $user->get($id);
        }

        // Normalize input
        $this->finputs['username'] = strtolower($this->finputs['username']);
        $this->finputs['email']    = mb_strtolower($this->finputs['email']);

        if ($this->finputs['username'] !== ($userInfo['username'] ?? '')
            && $user->isUsernameExists($this->finputs['username'])
        ) {
            $this->errors[] = 'Username is already in use!';
        } elseif ($this->finputs['email'] !== ($userInfo['email'] ?? '')
            && $user->isEmailExists($this->finputs['email'])
        ) {
            $this->errors[] = 'Email address is already in use!';
        }

        return !$this->errors;
    }
}
