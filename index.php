<?php

require_once './Student.php';

use Student\Student;

$sheetId = "11noy7IP6xjwcCidWczdvB1iNOw2_3TRlDmGbET35JXk";
$sheetRangeValues = "4:27";

// function dd(array $array)
// {
//     echo "<pre>";
//     print_r($array);
//     echo "<pre>";
// }


$studentValues = (new Student($sheetId, $sheetRangeValues))->checkLackOfGrade();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desafio Tunts.Rocks 2024 - Davi Moreira de Santana</title>
    <link rel="stylesheet" href="./index.css">
</head>

<body>
    <h1>Nota final</h1>
    <table>
        <thead>
            <tr>
                <th>Registry</th>
                <th>Student</th>
                <th>Absences</th>
                <th>Semester 1</th>
                <th>Semester 2</th>
                <th>Semester 3</th>
                <th>Situation</th>
                <th>NAF</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($studentValues as $values): ?>
            <tr>
                <td><?= $values['matricula'] ?></td>
                <td><?= $values['aluno'] ?></td>
                <td><?= $values['faltas'] ?></td>
                <td><?= $values['p1'] ?></td>
                <td><?= $values['p2'] ?></td>
                <td><?= $values['p3'] ?></td>
                <td><?= $values['situacao'] ?></td>
                <td><?= $values['naf'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>