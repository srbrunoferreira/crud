<?php

use FFI\Exception;

require_once('../config.php');
require_once(SRCDIR['CLASSES'] . 'DBconnection.class.php');


class Delete
{
    private $rowId;
    private $userPictureFilename;

    public function __construct($data)
    {
        $this->rowId = $data['rowId'];
        $this->userPictureFilename = $data['userPictureFilename'];

        if ($this->validateId()) {
            if ($this->deleteFromDatabase()) {
                $this->deleteUserPicture();
                echo 'SUCCESS'; // Success
            } else {
                echo 'INTERNAL_ERROR'; // Error while inserting in the database or saving the image
            }
        } else {
            echo 'INVALID_INPUT'; // Invalid data or e-mail already registered in the database
        }
    }

    private function validateId()
    {
        try {
            $rowId = intval($this->rowId);
            if (is_int($rowId)) {
                $this->rowId = intval(filter_var($rowId, FILTER_SANITIZE_NUMBER_INT));
                return true;
            }
        } catch (Exception $excep) {
            return false;
        }

        return false;
    }

    private function deleteFromDatabase()
    {
        try {
            $db = new DBconnection();
            $db->connect();
            $cmd = $db->connection->prepare('DELETE FROM employee WHERE id = :id');
            $cmd->bindValue(':id', $this->rowId);
            $cmd->execute();
        } catch (Exception $ex) {
            return false;
        }

        return $cmd->rowCount() > 0;
    }

    private function deleteUserPicture() {
        $saveDir = PUBLICDIR['USER_PICTURE'] . $this->userPictureFilename;
        if (file_exists($saveDir)) {
            unlink($saveDir);
        }
    }
}
