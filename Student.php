<?php

namespace Student;

use Sheets\SheetsHandler;

require_once "./SheetsHandler.php";


class Student
{

    /**
     * The sheet id that you can get from the URL.
     * @param string
     */

    private string $sheetId;

    /**
     * The sheet cell or column interval. Defines the region that the values are gonna be obtained.
     * 
     * @param string
     */
    private string $rangeSheet;

    /**
     * The total of classes given.
     * 
     * @param int
     */
    private int $maximumClasses = 60;

    public function __construct(string $sheetId, string $rangeSheet)
    {
        $this->sheetId = $sheetId;
        $this->rangeSheet = $rangeSheet;
    }

    /**
     * Retrieve the data, calling the connection method.
     * 
     * @return array
     */

    public function getData(): array
    {
        return (new SheetsHandler($this->sheetId, $this->rangeSheet))->handleRowsResult();
    }


    /**
     * Change the array, replacing the array key, for a more readable form.
     * 
     * @return array
     */

    public function changedArray(): array
    {
        $newArray = [];

        foreach ($this->getData() as $result => $key) {
            $newArray[$result] = [
                'matricula' => $key[0],
                'aluno' => $key[1],
                'faltas' => $key[2],
                'p1' => $key[3],
                'p2' => $key[4],
                'p3' => $key[5],
                'situacao' => '',
                'naf' => '',
                'media' => ''
            ];
        }

        return $newArray;
    }

    /**
     * Calculates the average value from the 3 semesters, changing the student situation.
     * 
     * @return array
     */

    public function calcAverage(): array
    {

        $average = [];
        $newArray = [];

        foreach ($this->changedArray() as $key => $value) {
            $semesterGrade[0] = $value['p1'];
            $semesterGrade[1] = $value['p2'];
            $semesterGrade[2] = $value['p3'];

            $average = (array_sum($semesterGrade) / 3);
            $value['media'] = round($average);

            switch ($average) {
                case $average < 50:
                    $value['situacao'] = 'Reprovado por nota';
                    break;

                case $average >= 70:
                    $value['situacao'] = 'Aprovado';
                    break;

                case $average >= 50 || $average < 70:
                    $value['situacao'] = 'Exame Final';
                    break;
            }
            $newArray[$key] = $value;
        }

        return $newArray;
    }

    /**
     * Checks the student absence, making a new change in the situation.
     * 
     * @return array
     */

    public function checkAbsence(): array
    {
        $calcStudents = $this->calcAverage();
        $maximumPercent = (25 / 100) * $this->maximumClasses;
        $newArray = [];

        foreach ($calcStudents as $key => $value) {
            if ($value['faltas'] > $maximumPercent) {
                $value['situacao'] = 'Reprovado por falta';
            }

            $newArray[$key] = $value;
        }

        return $newArray;
    }

    /**
     * At last, searches for the students that do not have the necessary grade and calculates that.
     * 
     * @return array
     */

    public function checkLackOfGrade(): array
    {
        $newArray = [];

        foreach ($this->checkAbsence() as $key => $value) {
            $minimumGrade = 100 - $value['media'];
            if ($value['situacao'] == 'Exame Final') {
                $value['naf'] = round($minimumGrade);
            } else {
                $value['naf'] = 0;
            }

            $newArray[$key] = $value;
        }

        return $newArray;
    }
}
