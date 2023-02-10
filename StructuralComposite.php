<?php
// Structurels : Composite.
// Permet d’organiser des objets sous forme d’arbre. On peut ensuite travailler avec chacun des
// éléments comme s'il était indépendant.
//
// Composants :
//   - Une interface Componant, déclarrant les méthodes de tous les composants de l'arbre.
//   - Une classe abstraitre Composant, implémentant les méthodes et attributs communes à tous les éléments de l'arbre.
//     Les méthode "operation" qui varient en fonction des type de composants sont déclarées comme abstraites.
//   - Des "composite" étandant la classe abstraite "composant" et implémentant son interface.
//     Se sont les composants qui peuvent avoir des composants enfants (oportunité classe abstraite composite?). 
//     Ils implémentent les méthodes abstraites "operations".
//   - Des "leafs" étandant la classe abstraite "composant" et implémentant son interface.
//     Se sont les composants qui ne peuvent pas avoir d'enfants. Ils implémentent les méthodes abstraites "operations".

/**
 * Component interface.
 * Implémente toutes les opération communes à tous les objets.
 */
interface Component
{
   public function getPath(): int;
   
   public function getParent(): Component;
   
   public function setParent(null|Component $employer): Component;
}

/**
 * Component implémentation abstraite.
 * N'implémente pas l'interface (les classes abstraintes ne peuvent implémenter d'interfaces).
 */
abstract class SimpleComponent
{
	protected Component $parent;
	
	protected string $path;
	
	protected string $name;
	
	public function __construct(string $name, Component $parent = null)
	{
		$this->setName($name);
		
		if($parent instanceof Component) {
			$this->setParent($parent);
			$this->parent->addChild($this);
			$this->path = $this->parent->getPath();
		} else {
			$this->path = '/';
		}
	}
	
	public function getParent(): Component
	{
		return $this->parent;
	}
	
	public function setParent(Component $component): self
	{
		$this->parent = $parent;
		
		return $this;
	}
	
	public function getName(): string
	{
		return $this->name;
	}
	
	public function setName(string $name): self
	{
		$this->name = $name;
		
		return $this;
	}
	
	abstract function getPath(): int;
}

/**
 * Composite implémentation concrète.
 */
final class Directory extends SimpleComponent implements Component
{
	protected array $children = [];
	
	public function addChild(Component $component)
	{
		$this->children[] = $component;
	}
	
	public function removeChild(Component $component)
	{
		
	}
	
	public function getPath(): int
	{
		return $this->parent->getPath() . $this->name . '/';
	}
	
	public function getChildrenTree(): string
	{
		$tree = '';
		foreach ($this->children as $child) {
			$tree .= $child->getPath() . "\n";
		}
	}
}
 
/**
 * Leaf implémentation concrète.
 */
final class File extends SimpleComponent implements Component
{
	public function getPath(): int
	{
		return $this->parent->getPath() . $this->name;
	}
}

/**
 * Mise en pratique.
 */
$root = new Directory();
$dir0 = new Directory('dir0', $root);
$dir1 = new Directory('dir1', $root);
$dir2 = new Directory('dir2', $root);
$dir3 = new Directory('dir3', $dir1);
$dir4 = new Directory('dir4', $dir1);
$dir5 = new Directory('dir5', $dir2);

$file0 = new File('file0', $dir0);
$file1 = new File('file1', $dir1);
$file2 = new File('file2', $dir2);
$file3 = new File('file3', $dir2);
$file4 = new File('file4', $dir3);
$file5 = new File('file5', $dir4);
$file6 = new File('file6', $dir4);
$file7 = new File('file7', $dir4);

echo $root->getChildrenTree();