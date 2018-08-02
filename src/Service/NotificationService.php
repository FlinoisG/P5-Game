<?php

namespace App\Service;

class NotificationService
{

    private $color;
    private $message;

    public function notification($message)
    {
        $this->color = $color;
        $this->message = $message;
        $notifWindowContent = $this->message;
        $notifWindowColor = $this->color;
        require('../src/View/notifWindowView.php');
    }

    public function setColor($color)
    {
        $this->color = $color;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

}
