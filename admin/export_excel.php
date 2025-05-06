<?php
require_once '../vendor/autoload.php';
require_once '../config.php'; // Connexion base de données

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Créer une nouvelle feuille de calcul
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Titre des colonnes
$sheet->setCellValue('A1', 'Nom');
$sheet->setCellValue('B1', 'Prénom');
$sheet->setCellValue('C1', 'Matricule');
$sheet->setCellValue('D1', 'Email');
$sheet->setCellValue('E1', 'Niveau');

// Récupération des données
$query = $pdo->query("SELECT * FROM etudiants ORDER BY nom ASC");
$rowIndex = 2;

while ($row = $query->fetch()) {
    $sheet->setCellValue('A' . $rowIndex, $row['nom']);
    $sheet->setCellValue('B' . $rowIndex, $row['prenom']);
    $sheet->setCellValue('C' . $rowIndex, $row['matricule']);
    $sheet->setCellValue('D' . $rowIndex, $row['email']);
    $sheet->setCellValue('E' . $rowIndex, $row['niveau']);
    $rowIndex++;
}

// En-têtes pour téléchargement
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="liste_etudiants.xlsx"');
header('Cache-Control: max-age=0');

// Écriture du fichier
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
