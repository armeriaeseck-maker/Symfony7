<?php

namespace App\Service;

use  \PHPMailer\PHPMailer\PHPMailer;
use Override;

class PHPMailService extends \PHPMailer\PHPMailer\PHPMailer
{
       #[Override]
       public function __destruct()
       {

              $this->isSMTP();
              $this->Host       = 'smtp.gmail.com';
              $this->SMTPAuth   = true;
              $this->Username   = 'armeriaeseck@gmail.com';
              $this->Password   = 'frvdspbvfyzcxbmf';
              $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
              $this->Port       = 587;
       }
}
