<?php
require '../../config.php'; // $pdo

$sql = "
  INSERT IGNORE INTO inscriptions (etudiant_id, matiere_id)
  SELECT e.id, m.id
  FROM etudiants e
  JOIN matieres m
  JOIN semestres s ON m.semestre_id = s.id
  WHERE 
    (s.annee_etude BETWEEN 1 AND 3 AND e.niveau = CONCAT('L', s.annee_etude))
  OR (s.annee_etude BETWEEN 4 AND 5 AND e.niveau = CONCAT('M', s.annee_etude - 3))
";

$count = $pdo->exec($sql);
echo "Nombre de paires étudiant→matière insérées : $count\n";
