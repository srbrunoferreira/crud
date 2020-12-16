<?php

use FFI\Exception;

require_once('../config.php');
require_once(SRCDIR['CLASSES'] . 'InputHandling.class.php');
require_once(SRCDIR['CLASSES'] . 'DBconnection.class.php');


class Read extends InputHandling
{
    private $invalidFilters = [];
    protected $queryFilters;

    public function __construct($queryFilters)
    {
        $this->queryFilters = $queryFilters;
        $this->validateFilters();
        $this->sanitizeFilters();

        if (count($this->invalidFilters) == 0) {
            if ($this->selectFromDatabase()) {
                if (!empty($this->rows)) {
                    $this->generateView();
                } else {
                    echo 'NOTHING_FOUND'; // The request did not find a match
                }
            } else {
                echo 'INTERNAL_ERROR'; // Error while inserting in the database or saving the image
            }
        } else {
            echo 'INVALID_INPUT'; // Invalid data or e-mail already registered in the database
        }
    }

    private function selectFromDatabase()
    {
        $query = $this->generateQuery();

        try {
            $db = new DBconnection();
            $db->connect();
            $cmd = $db->connection->query($query);
        } catch (Exception $ex) {
            return false;
        }

        $this->rows = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return true;
    }

    private function generateView()
    {
        foreach ($this->rows as $row) {
            $logic = file_exists(PUBLICDIR['USER_PICTURE'] . $row['img_filename']);
            $pictureSavePath = PUBLICDIR['USER_PICTURE'];
            $imgFilename = $logic? $row['img_filename']: 'default.png';
            echo
                "<div id='row-" . $row['id'] . "' class='response-row'>" .
                    "<div class='response-img-container response-row-column'>" .
                        "<img class='img_filename' src='" . $pictureSavePath . $imgFilename . "'>" .
                    "</div>" .
                    "<div class='response-txt-data-container response-row-column'>" .
                        "<table>" .
                            "<tr>" .
                                "<td class='response-txt-data-name'>Nome</td>" .
                                "<td class='response-txt-data _name'>" . $row['_name'] . "</td>" .
                            "</tr>" .
                            "<tr>" .
                                "<td class='response-txt-data-name'>E-mail</td>" .
                                "<td class='response-txt-data email'>" . $row['email'] . "</td>" .
                            "</tr>" .
                            "<tr>" .
                                "<td class='response-txt-data-name'>Nascido em</td>" .
                                "<td class='response-txt-data birth_date'>" . $row['birth_date'] . "</td>" .
                            "</tr>" .
                            "<tr>" .
                                "<td class='response-txt-data-name'>Profissão</td>" .
                                "<td class='response-txt-data occupation'>" . $row['occupation'] . "</td>" .
                            "</tr>" .
                            "<tr>" .
                                "<td class='response-txt-data-name'>Registrado em</td>" .
                                "<td class='response-txt-data reg_date'>" . $row['reg_date'] . "</td>" .
                            "</tr>" .
                        "</table>" .
                    "</div>" .
                    "<div class='response-btns-container response-row-column'>" .
                        "<button class='update-btn btn-edit-user' name='update' type='button' data-action='update' data-id='" . $row['id'] . "'>Editar</button>" .
                        "<button class='delete-btn btn-delete-user' name='delete' type='button' data-action='delete' data-id='" . $row['id'] . "'>Apagar</button>" .
                    "</div>" .
                "</div>";
        }
    }

    private function generateQuery()
    {
        $query = 'SELECT * FROM employee WHERE ';
        $filter = '';

        foreach ($this->queryFilters as $filterName => $filterValue) {
            $filterName = str_replace('-', '_', $filterName);
            $filter = $filter . $filterName . ' LIKE ' . "'%$filterValue%'";
            $query = $query . $filter;

            $filter = ' AND ';
        }

        $query = $query . ' ORDER BY _name';

        return $query;
    }

    private function validateFilters()
    {
        foreach ($this->queryFilters as $filterName => $filterValue) {
            if ($filterName == '_name' && !$this->validateAlphabeticString($filterValue)) {
                $this->invalidFilters[] = $filterName;
            } elseif ($filterName == 'email' && !$this->validateEmail($filterValue)) {
                $this->invalidFilters[] = $filterName;
            } elseif ($filterName == 'birth-date' && !$this->validateDate($filterValue)) {
                $this->invalidFilters[] = $filterName;
            } elseif ($filterName == 'occupation' && !$this->validateAlphabeticString($filterValue)) {
                $this->invalidFilters[] = $filterName;
            } elseif ($filterName == 'reg-date' && !$this->validateDate($filterValue)) {
                $this->invalidFilters[] = $filterName;
            }
        }
    }

    private function sanitizeFilters()
    {
        $sanitizedFilters = [];

        foreach ($this->queryFilters as $filterName => $filterValue) {
            if (!empty($filterValue)) {
                switch ($filterName) {
                    case '_name':
                        $sanitizedFilters[$filterName] = $this->sanitizeAlphabeticalString($filterValue);
                        break;
                    case 'email':
                        $sanitizedFilters[$filterName] = $this->sanitizeEmail($filterValue);
                        break;
                    case 'birth-date':
                        $sanitizedFilters[$filterName] = trim($filterValue);
                        break;
                    case 'occupation':
                        $sanitizedFilters[$filterName] = $this->sanitizeAlphabeticalString($filterValue);
                        break;
                    case 'reg-date':
                        $sanitizedFilters[$filterName] = trim($filterValue);
                        break;
                }
            }

            $this->queryFilters = $sanitizedFilters;
        }
    }
}
