<?php

abstract class InputHandling
{
    private $ALLOWED_IMAGE_FORMATS = ['png', 'jpg', 'jpeg'];

    protected function validateAlphabeticString($string)
    {
        $string = trim($string);
        return preg_match('/^[A-zÀ-ú ]+$/', $string) && strlen($string) > 0;
    }

    protected function validatePassword($password)
    {
        $password = trim($password);
        return preg_match('/^[a-zA-Z0-9]+$/', $password) && strlen($password) > 0;
    }

    protected function validateEmail($email)
    {
        $email = trim($email);
        return preg_match('/^[^@]+@[^@]+\.[a-z]{2,6}$/i', $email) && strlen($email) > 0;
    }

    protected function validateDate($date)
    {
        $date = trim($date);
        return preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $date) && strlen($date) > 0;
    }

    protected function validateImg($image)
    {
        if (!empty($image) && strlen($image['tmp_name']) > 0) {
            $imgFormat = explode('/', $image['type'])[1];
            return in_array($imgFormat, $this->ALLOWED_IMAGE_FORMATS) && file_exists($image['tmp_name']);
        }

        return false;
    }

    protected function sanitizeEmail($email)
    {
        $email = trim($email);
        return strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
    }

    protected function sanitizeAlphabeticalString($string)
    {
        $string = trim($string);
        return strtolower(trim(filter_var($string, FILTER_SANITIZE_FULL_SPECIAL_CHARS)));
    }

    protected function isInt($number)
    {
        $number = trim($number);
        try {
            $n = intval($number);
            return is_int($n);
        } catch (Exception $th) {}
        return false;
    }

    protected function getRespectiveValidationFunc($dataName)
    {
        $func = '';
        switch ($dataName) {
            case '_name':
                $func = 'validateAlphabeticString';
                break;
            case 'email':
                $func = 'validateEmail';
                break;
            case 'birth-date':
                $func = 'validateDate';
                break;
            case 'occupation':
                $func = 'validateAlphabeticString';
                break;
            case '_password':
                $func = 'validatePassword';
                break;
            case 'img':
                $func = 'validateImg';
                break;
            case 'reg-date':
                $func = 'validateDate';
            case 'user-id':
                $func = 'isInt';
        }
        return $func;
    }

    protected function getRespectiveSanitizationFunc($dataName)
    {
        $func = '';
        switch ($dataName) {
            case '_name':
                $func = 'sanitizeAlphabeticalString';
                break;
            case 'email':
                $func = 'sanitizeEmail';
                break;
            case 'occupation':
                $func = 'sanitizeAlphabeticalString';
                break;
            default:
                $func = '';
                break;
        }
        return $func;
    }
}
