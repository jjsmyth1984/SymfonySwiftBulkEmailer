<?php


namespace App\Model;

/***
 * Class ProcessCSV
 * @package App\Model
 */
class ProcessCSV
{

    /***
     * @param array|null $fileData
     * @param array|null $results
     * @return iterable|null
     */
    public function getCSVData(?array $fileData, ?array $results = array()): ?iterable
    {

        if (($handle = fopen($fileData["tmp_name"], "r")) !== false) {
            $counter = 0;
            while (($data = fgetcsv($handle, 50000, ",")) !== false) {
                $results[$counter]["toEmail"] = $data[0];
                $results[$counter]["toName"] = $data[1];
                $counter++;
            }
        } else {
            echo "Return Code: Could not process fopen<br />";
        }

        return $results;

    }

}