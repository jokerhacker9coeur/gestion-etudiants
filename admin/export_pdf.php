<?php
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');
require_once('../config.php'); // Connexion à la base de données

// Création du document PDF
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator('Gestion Étudiants');
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Liste des étudiants');
$pdf->SetHeaderData('', 0, 'Liste des étudiants', '');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 12));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 10));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(15, 27, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->SetFont('dejavusans', '', 10);

// Nouvelle page
$pdf->AddPage();

// Titre
$pdf->SetFont('dejavusans', 'B', 14);
$pdf->Cell(0, 10, 'Liste des étudiants', 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('dejavusans', '', 10);

// Récupération des données
$query = $pdo->query("SELECT * FROM etudiants ORDER BY nom ASC");

// Tableau HTML
$html = '
<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr style="background-color:#f2f2f2;">
            <th><b>Nom</b></th>
            <th><b>Prénom</b></th>
            <th><b>Matricule</b></th>
            <th><b>Email</b></th>
            <th><b>Niveau</b></th>
        </tr>
    </thead>
    <tbody>';
    
while ($row = $query->fetch()) {

    $html .= '<tr>
                <td>' . htmlspecialchars($row['nom']) . '</td>
                <td>' . htmlspecialchars($row['prenom']) . '</td>
                <td>' . htmlspecialchars($row['matricule']) . '</td>
                <td>' . htmlspecialchars($row['email']) . '</td>
                <td>' . htmlspecialchars($row['niveau']) . '</td>
              </tr>';
}
$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Envoi du fichier PDF au navigateur
$pdf->Output('liste_etudiants.pdf', 'I');
