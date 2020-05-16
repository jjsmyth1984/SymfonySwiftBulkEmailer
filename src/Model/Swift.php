<?php

namespace App\Model;

use Swift_Mailer;
use Swift_SmtpTransport;
use Symfony\Component\Dotenv\Dotenv;

/***
 * Class Swift
 * @package App\Model
 */
class Swift
{

    /***
     * @return Swift_Mailer
     */
    public function swiftConnection()
    {

        // Get email provider connection details from project .env file
        $dotEnv = new Dotenv();
        $dotEnv->load(dirname(__DIR__, 2) . "/.env");

        // Create the Transport
        $transport = (new Swift_SmtpTransport($_ENV["SWIFT_HOST"], $_ENV["SWIFT_PORT"], $_ENV["SWIFT_ENCRYPTION"]))
            ->setUsername($_ENV["SWIFT_USERNAME"])
            ->setPassword($_ENV["SWIFT_PASSWORD"]);

        // ADD THIS LINE FOR LOCAL TESTING
        $transport->setLocalDomain('[127.0.0.1]');

        // Create the Mailer using your created Transport
        return new Swift_Mailer($transport);

    }

    /***
     * @param array|null $aBasicEmail
     * @param Swift_Mailer $mailer
     * @param String|null $toEmail
     * @param String|null $toName
     * @param String|null $contentType
     * @return int
     */
    public function sendBasicEmail(?array $aBasicEmail, \Swift_Mailer $mailer, ?string $toEmail, ?string $toName, ?string $contentType = "text/html")
    {

        // Create a message
        $message = (new \Swift_Message($aBasicEmail["subject"]))
            ->setFrom([$aBasicEmail["fromEmail"] => $aBasicEmail["fromName"]])
            ->setTo([$toEmail, $toEmail => $toName])
            ->setBody($aBasicEmail["html"], $contentType);

        // Send the message
        return $mailer->send($message);

    }

}