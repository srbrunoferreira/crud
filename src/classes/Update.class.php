<?php

use FFI\Exception;

require_once('../config.php');
require_once(SRCDIR['CLASSES'] . 'InputHandling.class.php');
require_once(SRCDIR['CLASSES'] . 'DBconnection.class.php');


class Update extends InputHandling
{
    private $ALLOWED_INPUTS_KEYS = [
        'img' => 'img',
        '_name' => 'name',
        'email' => 'email',
        'birth-date' => 'birth_date',
        'occupation' => 'occupation',
        '_password' => 'password',
        'user-id' => 'user_id'
    ];

    private $nonValidatedData = [];
    private $validatedData = [];

    function __construct($data)
    {
        $this->nonValidatedData = $data;
        $this->validateData();

        if (isset($this->validatedData['textData']['user-id']) && strlen($this->validatedData['textData']['user-id']) > 0) {
            $this->sanitizeData();
            if (isset($this->validatedData['textData']) && isset($this->validatedData['img']) && count($this->validatedData['textData']) > 1) {
                if ($this->updateDbData() && $this->updateImage()) {
                    echo 'SUCCESS';
                } else {
                    echo 'INTERNAL_ERROR';
                }
            } elseif (isset($this->validatedData['textData']) && count($this->validatedData['textData']) > 1) {
                if ($this->updateDbData()) {
                    echo 'SUCCESS';
                } else {
                    echo 'INTERNAL_ERROR';
                }
            } else {
                if ($this->updateImage()) {
                    echo 'SUCCESS';
                } else {
                    echo 'INTERNAL_ERROR';
                }
            }
        } else {
            echo 'INVALID_INPUT';
        }
    }

    private function updateImage()
    {
        try {
            $db = new DBconnection();
            $db->connect();
            $cmd = $db->connection->prepare('SELECT img_filename FROM employee WHERE id = :userId');
            $cmd->bindValue(':userId', $this->validatedData['textData']['user-id']);
            $cmd->execute();

            $actualImgFilename = $cmd->fetch(PDO::FETCH_ASSOC)['img_filename'];
            $saveDir = '../../public/img/users_profile_picture/';
            $dir = $saveDir . $actualImgFilename;
            unlink($dir);

            $cmd = $db->connection->prepare('UPDATE employee SET img_filename = :imgFilename WHERE id = :userId');
            $cmd->bindValue(':imgFilename', $this->validatedData['img']['img_filename']);
            $cmd->bindValue(':userId', $this->validatedData['textData']['user-id']);
            $cmd->execute();

            if (move_uploaded_file(
                $this->validatedData['img']['tmp_dir'],
                '../../public/img/users_profile_picture/' . $this->validatedData['img']['img_filename']
            )) {
                return true;
            }
        } catch (Exception $ex) {
            return false;
        }
    }

    private function updateDbData()
    {
        $query = $this->generateQuery();
        try {
            $db = new DBconnection();
            $db->connect();
            $cmd = $db->connection->prepare($query);

            foreach (array_keys($this->validatedData['textData']) as $dataName) {
                $dataValue = $this->validatedData['textData'][$dataName];
                if ($dataName == '_password') {
                    $dataValue = password_hash($this->validatedData['textData'][$dataName], PASSWORD_DEFAULT);
                }
                $dataName = ':' . $this->ALLOWED_INPUTS_KEYS[$dataName];
                $cmd->bindValue($dataName, $dataValue);
            }

            $cmd->execute();
        } catch (Exception $ex) {
            return false;
        }

        return $cmd->rowCount() > 0;
    }

    private function generateQuery()
    {
        $query = 'UPDATE employee SET ';
        $and = '';
        // "update cliente set nome = '".$nome."', email = '".$email."',telefone = '".$tel."';
        foreach (array_keys($this->validatedData['textData']) as $dbColumn) {
            if ($dbColumn != 'user-id') {
                $dbColBind = $this->ALLOWED_INPUTS_KEYS[$dbColumn];
                $update = $and . $dbColumn . ' = :' . "$dbColBind";
                $query = $query . $update;
                $and = ', ';
            }
        }

        $query = $query . ' WHERE id = :user_id';

        return str_replace('-', '_', $query);
    }

    private function getImageInfo()
    {
        $imgFormat = explode('/', $this->nonValidatedData[1]['img']['type'])[1];

        $tmpFilename = basename($this->nonValidatedData[1]['img']['tmp_name']);
        $tmpFilename = explode('.', $tmpFilename)[0];
        $tmpFilename = $tmpFilename . '.' . $imgFormat;

        return [
            'img_filename' => $tmpFilename,
            'tmp_dir' => $this->nonValidatedData[1]['img']['tmp_name']
        ];
    }

    private function validateData()
    {
        $validatedData = [];
        if (isset($this->nonValidatedData[0])) {
            foreach ($this->nonValidatedData[0] as $dataName => $dataValue) {

                // The logic is necessary because empty inputs are allowed
                // And only the not empty inputs and valid inputs will be catch
                // _name e user-id
                if (!strlen($dataValue) == 0) {
                    $dataValue = trim($dataValue);
                    $func = 'parent::' . $this->getRespectiveValidationFunc($dataName);
                    $isValidInput = call_user_func_array($func, [$dataValue]) && array_key_exists($dataName, $this->ALLOWED_INPUTS_KEYS);

                    if ($isValidInput) {
                        $validatedData['textData'][$dataName] = $dataValue;
                    } else {
                        return false;
                    }
                }
            }
        }

        if (array_key_exists(1, $this->nonValidatedData) && array_key_exists('img', $this->nonValidatedData[1]) && strlen($this->nonValidatedData[1]['img']['name']) > 0 && !$this->validateImg($this->nonValidatedData[1]['img'])) {
            return false;
        } elseif (array_key_exists(1, $this->nonValidatedData) && array_key_exists('img', $this->nonValidatedData[1]) && $this->validateImg($this->nonValidatedData[1]['img'])) {
            $validatedData['img'] = $this->getImageInfo();
        }

        $this->validatedData = $validatedData;

        return true;
    }

    private function sanitizeData()
    {
        foreach ($this->validatedData['textData'] as $dataName => $dataValue) {
            $funcName = $this->getRespectiveSanitizationFunc($dataName);
            $funcComplete = 'parent::' . $funcName;
            if ($funcName != '') {
                $this->validatedData['textData'][$dataName] = call_user_func_array($funcComplete, [$dataValue]);
            }
        }
    }
}
