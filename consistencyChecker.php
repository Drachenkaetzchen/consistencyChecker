<?php

class consistencyChecker extends oxAdminView
{
    protected $_sThisTemplate = 'consistencyChecker.tpl';

    public function render()
    {
        $this->_aViewData["config"] = $this->getCheckerConfiguration();

       return $this->_sThisTemplate;
    }

    protected function getCheckerConfiguration () {
        $check = [
            [
                "table" => "oxorder",
                "canonicalTable" => "Bestellungen",
                "field" => "oxordernr",
                "canonicalField" => "Bestellnummer"
            ],
            [
                "table" => "oxorder",
                "canonicalTable" => "Bestellungen",
                "field" => "oxbillnr",
                "canonicalField" => "Rechnungsnummer",
            ],
            [
                "table" => "oxuser",
                "canonicalTable" => "Kunden",
                "field" => "oxcustnr",
                "canonicalField" => "Kundennummer",
            ],
        ];

        return $check;
    }
    public function execute () {

        $check = $this->getCheckerConfiguration();
        foreach ($check as $key => $config) {
            $check[$key]["results"] = $this->checkConsistency($config["table"], $config["field"]);
        }

        $this->_aViewData["resultData"] = $check;
    }

    public function checkConsistency($table, $fieldName)
    {
        $result = [];

        $sSQL = "SELECT $fieldName AS field, COUNT($fieldName) AS cnt FROM $table WHERE $fieldName != '' GROUP BY $fieldName HAVING cnt > 1";

        $duplicates = oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->getArray($sSQL);

        $result["duplicates"] = $duplicates;
        $result["duplicateCount"] = count($duplicates);


        $sSQL = "SELECT CAST($fieldName AS UNSIGNED INTEGER) AS number FROM $table WHERE $fieldName != '' ORDER BY CAST($fieldName AS UNSIGNED INTEGER) ASC";
        $numbers = oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->getArray($sSQL);

        $gaps = [];
        $numberOfGaps = 0;
        $missingNumbers = 0;

        foreach ($numbers as $key => $data) {
            $number = intval($data["number"]);

            if ($key > 0) {
                $previousNumber = intval($numbers[$key-1]["number"]);

                /**
                 * Check that the current number is the previous number +1
                 *
                 * If the previous and current numbers are identical, skip it since
                 * it is a duplicate which is handled above.
                 */
                if ($number != $previousNumber + 1 &&
                    $number != $previousNumber) {

                    $missingNumbers+= $number - $previousNumber;
                    $numberOfGaps++;
                    $gaps[] = [
                        "previousNumber" => $previousNumber,
                        "missingNumbers" => $number - $previousNumber,
                        "currentNumber" => $number];
                }
            }
        }

        $result["gaps"] = $gaps;
        $result["missingNumbers"] = $missingNumbers;
        $result["numberOfGaps"] = $numberOfGaps;

        return $result;
    }

}
