<?php

namespace App\Controller;

use App\Model\EmailHTML;
use App\Model\ProcessCSV;
use App\Model\Swift;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/***
 * Class BulkEmailController
 * @package App\Controller
 */
class BulkEmailController extends AbstractController
{


    /**
     * @Route("/", name="bulk_email")
     */
    public function index(Request $request)
    {

        // Check to see if fileUpload us uploaded
        if ($request->isMethod("POST")) {
            if ($_FILES["fileUpload"]["error"] === 0) {
                // Confirm file type
                if ($_FILES["fileUpload"]["type"] === "text/csv") {
                    // Process uploaded csv and gather customer data
                    $csvData = (new ProcessCSV())->getCSVData($_FILES["fileUpload"]);
                    // Build html email template with gathered customer data
                    $emailDetails = array(
                        "fromEmail" => "test@test.com",
                        "fromName" => "Test",
                        "subject" => "Hello World!"
                    );
                    $report = $this->processEmails($csvData, $emailDetails);
                } else {
                    $report = "Return Code: Wrong file type<br />";
                }
            } else {
                $report = "Return Code: " . $_FILES["file"]["error"] . "<br />";
            }

            // Json response
            $aResults = json_encode(
                array(
                    "result" => "Success",
                    "report" => $report
                )
            );

            $response = new Response($aResults);
            $response->headers->set("Content-Type", "application/json");

            return $response;

        }

        return $this->render(
            "bulk_email/index.html.twig",
            [
                "controller_name" => "BulkEmailController",
            ]
        );
    }

    /***
     * @param array|null $csvData
     * @param array|null $emailDetails
     * @param array|null $results
     * @return iterable|null
     */
    private function processEmails(?array $csvData, ?array $emailDetails, ?array $results = array()): ?iterable
    {

        // Email connection
        $mailer = (new Swift())->swiftConnection();

        foreach ($csvData as $value) {
            // Email valuation
            if (filter_var($value["toEmail"], FILTER_VALIDATE_EMAIL)) {
                // Build html email template with gathered customer data
                $emailDetails["html"] = (new EmailHTML())->emailTemplate($value);
                // Send email
                $results[] = (new Swift())->sendBasicEmail($emailDetails, $mailer, $value["toEmail"], $value["toName"]);
            } else {
                $results[] = "Failed emailed validation.";
            }
        }

        return $results;

    }

}
