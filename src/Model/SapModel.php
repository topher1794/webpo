<?php 


namespace stockalignment\Model;

use PDO;

class SapModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }



    public function getSAPProperties(): array
    {

        $stmt = $this->pdo->prepare("SELECT id FROM StockAlignUsers WHERE settingstype = :settings");
        $stmt->execute(['settings' => "SAP"]);
        return $stmt->fetch();
    }

    
   
}