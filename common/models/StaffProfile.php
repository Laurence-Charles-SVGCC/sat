<?php

namespace common\models;

class StaffProfile
{
    public $userId;
    public $username;
    public $fullname;

    function __construct($userId, $username, $fullname)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->fullname = $fullname;
    }
}
