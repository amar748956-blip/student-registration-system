<?php
/**
 * Validator — server-side validation logic (OOP, reusable).
 *
 * Collects errors so all problems can be reported at once.
 */

declare(strict_types=1);

class Validator
{
    /** @var array<string,string> */
    private array $errors = [];

    /**
     * Validate the submitted student data.
     *
     * @param array<string,mixed> $data
     * @return array<string,string> Map of field => error message.
     */
    public function validateStudent(array $data): array
    {
        $this->errors = [];

        // Full Name
        if (empty(trim((string) ($data['full_name'] ?? '')))) {
            $this->errors['full_name'] = 'Please enter your full name';
        }

        // Email
        $email = trim((string) ($data['email'] ?? ''));
        if ($email === '') {
            $this->errors['email'] = 'Please enter your email address';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Please enter a valid email address';
        }

        // Phone — exactly 10 digits
        $phone = trim((string) ($data['phone'] ?? ''));
        if ($phone === '') {
            $this->errors['phone'] = 'Please enter your phone number';
        } elseif (!preg_match('/^\d{10}$/', $phone)) {
            $this->errors['phone'] = 'Phone number must contain 10 digits';
        }

        // Gender
        if (empty($data['gender'])) {
            $this->errors['gender'] = 'Please select your gender';
        }

        // Date of Birth
        if (empty($data['dob'])) {
            $this->errors['dob'] = 'Please select your date of birth';
        }

        // Country
        if (empty($data['country'])) {
            $this->errors['country'] = 'Please select your country';
        }

        return $this->errors;
    }

    /**
     * Whether the last validation passed.
     */
    public function passes(): bool
    {
        return empty($this->errors);
    }

    /**
     * @return array<string,string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
