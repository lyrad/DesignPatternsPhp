<?php
// Comportementaux : Observer.
// Permet de créer un mécanisme de souscription pour notifier des objets à propos 
// d’événements qui se produisent au niveau de l’objet qu’ils observent.
//
// Define a subscription mechanism to notify multiple objects about any events that happen 
// to the object they’re observing.
// Composants :
//   - Un Subject (ou Publisher) : objet à oberver.
// 	 - Des Observers (ou Subscribers) : Objets étant avertis d'une mise à jour dans le publisher.

// Attention : PHP possède déja des objets de type "Subject" et "Observer" : les interface
// SplSubject et SplObserver.

// Symfony : le composant EventDispatcher implémente le design pattern Observer.


/**
 * Subject : objet à observer.
 * Un plannificateur de concerts pour des groupes de musiques.
 */
final class ConcertPlanner implements \SplSubject
{
    private \SplObjectStorage $observers;
	
    private array $state;
    
    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }

    public function attach(\SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    public function detach(\SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    public function getState(): array
    {
        return $this->state;
    }

    public function plan(string $groupName, string $date, string $location)
    {
        $this->state = [
            'group' => $groupName,
            'date' => date('d/m/Y', $date),
            'location' => $location,
        ];
        
        $this->notify();
    }

    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}

/**
 * Observer.
 * Un fan qui souhaite être notifié si un group X se produit près de chez lui.
 */
final class Fan implements \SplObserver
{
    public function getLocation(): string
    {
        return 'Marseille';
    }

    public function getFollowedGroups(): string
    {
        return [
            'SPG',
			'NTM',
        ];
    }
	
    public function update(\SplSubject $subject): void
    {
        $state = $subject->getState();
        
        if(\in_array($state['group'], $this->getFollowedGroups())
            && $state['location'] === $this->getLocation()) {
            // Code pour notifier l'utilisateur.
			echo "Fan notified!"
        }
    }
}

/**
 * Observer.
 * Les flics qui veulent être notifiés à chaque fois qu'un groupe en particulier
 * se produit quelque part.
 */
final class Perdraux implements \SplObserver
{
    public function getBlacklistedGroups(): string
    {
        return [
            'SPG',
        ];
    }
	
    public function update(\SplSubject $subject): void
    {
        $state = $subject->getState();
        
        if(\in_array($state['group'], $this->getBlacklistedGroups())) { 
            // Code pour notifier l'utilisateur.
			echo "Perdraux notified!"
        }
    }
}

/**
 * Mise en pratique.
 */
 
$concertsPlanner = new ConcertPlanner();

$observers = [
	new Fan(),
	new Fan(),
	new Fan(),
	new Perdraux(),
	
];

foreach ($observers as $observer) {
    $concertsPlanner->attach($observer);
}

$concertsPlanner->plan('SPG', '12/06/2022', 'Bordeaux');
