<?php

require_once('../config.php');
require_once(SRCDIR['CLASSES'] . 'InputHandling.class.php');
require_once(SRCDIR['CLASSES'] . 'DBconnection.class.php');


class Create extends InputHandling
{
    private $name;
    private $email;
    private $birthDate;
    private $occupation;
    private $password;

    private $encryptedPassword;
    private $userPictureFilename;
    protected $invalidInputs = [];

    public function __construct($data, $image)
    {
        $this->name = $data['_name'];
        $this->email = $data['email'];
        $this->birthDate = $data['birth-date'];
        $this->occupation = $data['occupation'];
        $this->password = $data['_password'];
        $this->userPictureInfo = $image['img'];

        $this->validateInputs();

        if (count($this->invalidInputs) == 0 && !$this->emailExists()) {
            $this->sanitizeInputs();
            $this->genereteEncryptedPassword();
            if ($this->saveImg() && $this->insertInDatabase()) {
                echo 'SUCCESS'; // Success
            } else {
                $this->deleteImg();
                echo 'INTERNAL_ERROR'; // Error while inserting in the database or saving the image
            }
        } else {
            echo 'INVALID_INPUT'; // Invalid data or e-mail already registered in the database
        }
    }

    private function insertInDatabase()
    {
        $db = new DBconnection();

        if ($db->connect()) {
            $cmd = $db->connection->prepare('INSERT INTO employee (_name, email, birth_date, occupation, _password, img_filename) VALUES(:_name, :email, :birth_date, :occupation, :_password, :img_filename)');
            $cmd->bindValue(':_name', $this->name);
            $cmd->bindValue(':email', $this->email);
            $cmd->bindValue(':birth_date', $this->birthDate);
            $cmd->bindValue(':occupation', $this->occupation);
            $cmd->bindValue(':_password', $this->encryptedPassword);
            $cmd->bindValue(':img_filename', $this->userPictureFilename);
            $cmd->execute();

            return $cmd->rowCount() > 0;
        }
    }

    private function saveImg()
    {
        $imgFormat = explode('/', $this->userPictureInfo['type'])[1];
        $filename = basename($this->userPictureInfo['tmp_name']);
        $newFilename = explode('.', $filename)[0] . '.' . $imgFormat;
        $this->userPictureFilename = $newFilename;

        $tmpDir = $this->userPictureInfo['tmp_name'];
        $this->saveDir = '../../public/img/users_profile_picture/' . $newFilename;

        return move_uploaded_file($tmpDir, $this->saveDir);
    }

    private function deleteImg()
    {
        if (file_exists($this->saveDir)) {
            unlink($this->saveDir);
        }
    }

    private function genereteEncryptedPassword()
    {
        $this->encryptedPassword = password_hash($this->password, PASSWORD_DEFAULT);
    }

    private function emailExists()
    {
        $db = new DBconnection();

        if ($db->connect()) {
            $cmd = $db->connection->prepare('SELECT id FROM employee WHERE email = :email');
            $cmd->bindValue(':email', $this->email);
            $cmd->execute();

            return $cmd->rowCount() > 0;
        }

        return false;
    }

    private function validateInputs()
    {
        $data = [$this->name, $this->email, $this->birthDate, $this->occupation, $this->password, $this->userPictureInfo];
        foreach ($data as $dataName => $dataValue) {
            if (empty($dataValue) || !isset($dataValue)) {
                $this->invalidInputs[] = $dataName;
                return false;
            }
        }

        if (!$this->validateAlphabeticString($this->name)) {
            $this->invalidInputs[] = 'Nome';
        }

        if (!$this->validateEmail($this->email)) {
            $this->invalidInputs[] = 'E-mail';
        }

        if (!$this->validateDate($this->birthDate)) {
            $this->invalidInputs[] = 'Data de aniversário';
        }

        if (!$this->validateAlphabeticString($this->occupation)) {
            $this->invalidInputs[] = 'Profissão';
        }

        if (!$this->validatePassword($this->password)) {
            $this->invalidInputs[] = 'Senha';
        }

        if (!$this->validateImg($this->userPictureInfo)) {
            $this->invalidInputs[] = 'Foto do funcionario';
        }
    }

    private function sanitizeInputs()
    {
        $this->email = $this->sanitizeEmail($this->email);
        $this->name = $this->sanitizeAlphabeticalString($this->name);
        $this->password = $this->sanitizeAlphabeticalString($this->password);
        $this->birthDate = $this->sanitizeAlphabeticalString($this->birthDate);
        $this->occupation = $this->sanitizeAlphabeticalString($this->occupation);
    }
}
