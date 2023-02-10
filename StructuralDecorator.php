<?php
// Structurels : Decorator.
// Permet d’ajouter dynamiquement de nouveaux comportements à un objet sans en modifier le code, et sans
// que le client n'ai connaissance des classes implémentant ces nouveaux comportements.
// Pour cela, le component (objet à décoré) est encapsulé dans un décorator, pouvant lui même être
// encapsulé dans un autre décorator, à l'infini.
//
// Composants :
//   - Un component (objet à décorer) implémentant une interface, et définissant un comportement par défaut
//     pour les opérations.
//   - Un décorateur global (?potentiellement abstrait?) définissant les méthodes par défaut pour le stockage
//     du component dans les décorators. Il implémente l'interface du component (et optionellement, sa propre 
//     interface). Implémente également l'appel aux méthodes "opérations" du component (?pas le cas si abstrait?)
//     pour le comportement par défaut.
//   - Des décorateurs concrèts entendant le décorateur global, et surchargeant/utilisant l'implémentation des 
//     méthodes opérations.

/**
 * Interface du component.
 */
interface NotifierInterface
{
    public function notify(): string;
}

/**
 * Implémentattion concrète du component.
 * Définit le comportement par défaut des méthodes "opération" (notify).
 */
class Notifier implements NotifierInterface
{
    public function notify(): string
    {
        return 'Default notifier : nothing done.';
    }
}

/**
 * Interface du/des decorator(s) (pas obligatoire).
 */
interface NotifierDecoratorInterface
{
    public function notify(): string;
}

/**
 * Classe de base pour les decorators.
 * L'objectif est de définir les méthodes communes au decorators (encapsulage de composants).
 * ?Opportunité classe abstraite laissant la méthode notify aux implémentations concrètes du decorator?
 */
class NotifierDecorator implements ComponentInterface, NotifierDecoratorInterface
{
	private ComponentInterface $component;

    public function __construct(ComponentInterface $component)
    {
        $this->component = $component;
    }
	
	// Déléguation des opérations à effectuer à la classe component.
	public function notify(): string
    {
        return $this->component->notify();
    }
}

/**
 * Decorator : implémentation concrète. 
 * Ajouter l'envoi d'un SMS à un component implémentant la méthode notify.
 */
final class SmsNotifierDecorator extends NotifierDecorator
{
    public function notify(): string
    {
		// Appel du parent plutot que du composant directement (Simplifie l'extenssion des Decorators).
        $return = parent::notify();
        return $return . ' - ' . $this->sendSMS();
    }

    private function sendSms(): string
    {
		// Code envoyant un SMS.
		return "SMS sent!";
    }
}

/**
 * Decorator : implémentation concrète. 
 * Ajouter l'envoi d'un Email à un component implémentant la méthode notify.
 */
final class EmailNotifierDecorator extends NotifierDecorator
{
    public function notify(): string
    {
		// Appel du parent plutot que du composant directement (Simplifie l'extenssion des Decorators).
        $return = parent::notify();
        return $return . ' - ' . $this->sendSMS();
    }

    private function sendEmail(): string
    {
        // Code envoyant un Email.
		return 'Email sent!';
    }
}

/**
 * Mise en pratique.
 */
 
 /**
  * Client. Travail avec des Interface de component, indépendament des décorateurs.
  */
class Client
{
    private $notifier;
    
    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function notify()
    {
        return $this->notifier->notify();
    }
}
 
// Création du component.
$notifier = new Notifier();

// Décoration du component avec le smsNotifierDecorator.
$smsNotifier = new SmsNotifierDecorator($notifier);

// Décoration du component avec les deux décorators.
// Les decorateurs peuvent encapsuler un composant ou un autre décorateur.
$emailAndSmsDecorator = new EmailNotifierDecorator($smsDecorator);

// Appel client.
$client = new Client($emailAndSmsDecorator);
echo $client->notify();


