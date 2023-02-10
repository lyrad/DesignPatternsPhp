<?php
// Structurels : Adapter.
// Permet à deux objets avec des interfaces incompatibles de collaborer ensemble. L'objet adapté
// n'a pas connaissance de l'adapteur.
//
// Objectifs :
//  - Reconvertir l'interface d'un objet de façon à ce qu'un autre objet puisse la comprendre.
//  - Effectuer les opérations de convertion.

/**
 * Objet de référence, c'est avec lui qu'on veut rendre compatible un autre objet.
 */
class Clio implements Car
{
	private int $milage;
	
	/**
	 * Retourne la valeur du kilometrage, en miles et JSON.
	 */
    public function getMilageAsJson(): string
    {
        return \json_encode([
			'mileage' => $this->milage
		]);
    }
}

/**
 * Objet que l'on veut adapter (Km XML => Miles JSON).
 */
class Velib implements Bicycle
{
	private int $milage;
	
    /**
     * Retourne la valeur du kilométrage, en kilomètres et XML.
     */ 
    public function getMilageAsXml(): string
    {
		return '<xml><milage>' . $this->mileage '</milage></xml>';
    }
}

/**
 * Interface de l'adpater.
 * Doit respecter l'interface d'un des deux objets au minimum.
 * Ici, c'est la méthode de l'objet de référence, que l'on veut "pousser" dans l'objet à adapter.
 */
interface BicycleMilageKmJsonExporter
{
    public function getMilageAsJson(): string;
}

/**
 * Implémentation concrète de l'adapter.
 * Ici on nomme d'après l'interface, mais d'après l'objet possible aussi.
 */
class BicycleAdapter implements BicycleMilageKmJsonExporter
{
    private Bicycle $bicycle;

    public function __construct(Bicycle $bicycle)
    {
        $this->bicycle = $bicycle;
    }

    public function getMilageAsJson(): string
    {
        $resultInKmXml = $this->bicycle->getMilageAsXml();
        
        return $this->convertKmXmlToMileJson(string $resultInKmXml);
    }
	
	// Méthode de transformation.
	public function convertKmXmlToMileJson(string $resultInKmXml): string
	{
		$resultInKm = unXML($resultInKmXml);
		
		return \json_encode([
			'mileage' => $resultInKm * 0.621371;
		]);
	}
}

/**
 * Exemple.
 */
 $clio = new Clio();
 $velib = new Velib();
 
 // Dans une classe, on injecterait via l'interface...
 $adapter = new BicycleAdapter($velib);
 
 // ... on zappe le format pour l'exemple.
 if ($clio->getMilageAsJson() > $adapter->getMilageAsJson()) {
	echo "Cette clio a plus roulé que ce vélib!";
 }
 