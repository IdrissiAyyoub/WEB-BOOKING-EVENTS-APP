<?php
$server = 'mysql:host=localhost;dbname=farha';
$username = 'root';
$password = '';

try {

    $DATABASE = new PDO($server, $username, $password);
} catch (PDOException $e) {
    echo 'Error :' . $e->getmessage();
}

function  CapacityRoom($numVersion, $DATABASE)
{
    $version = "SELECT * FROM salle inner join version on version.numSalle = salle.numSalle where numVersion = :versionID";
    $stmVersion = $DATABASE->prepare($version);
    $stmVersion->bindParam(':versionID', $numVersion);
    $stmVersion->execute();
    $rowVersion = $stmVersion->fetch(PDO::FETCH_ASSOC);
    return $rowVersion['capacite'];
}

function CountTickts($numVersion, $DATABASE)
{
    $countBillet = "SELECT count(*) as count FROM billet INNER JOIN facture 
    on billet.idFacture = facture.idFacture 
    where numVersion = :versionID ";
    $stmBillet = $DATABASE->prepare($countBillet);
    $stmBillet -> bindparam(':versionID', $numVersion);
    $stmBillet->execute();
    $rowBillet = $stmBillet -> fetch(PDO::FETCH_ASSOC);
    return $rowBillet['count'];
}
