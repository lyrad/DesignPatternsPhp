<?php
// Créationnels : Singleton.
//
// Objectifs :
//  - Limiter le nombre total d'instances pour une classe à une.
//  - Fournir un accès global à cette instance.
//
// Attention : Anti-pattern, viole le principe d'une seule responsabilité par classe, difficilement testable (ne peut pas être changé une fois construit).

use PDO;

class Database
{
    private static $instance = null;

    protected $connection;

	// Le constructeur doit être privé, la classe ne pourra être instanciée avec le mot clé "new".
    private function __construct()
    {
        $this->connection = new PDO(PDO_DSN, USER, PASSWD);
    }

	// Méthode statique stockant l'instance dans une propriété statique (peut être appelée de n'importe ou).
	// La classe ne sera donc instaciée qu'une fois.
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}

/**
 * Exemple.
 */
 $dtb = Database::getInstance();
 
 unset($dtb);
 
 // L'instance retournée est la même.
 $newDtb = Database::getInstance();