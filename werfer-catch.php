<?php
/**
 * Beispiel für den Einsatz der BadFunctionCallException 
 * innerhalb der magischen Methode __call()
 */

class Werfer {
  protected $name;
  protected $alter;
  public function __construct($name, $alter) {
    $this->name  = $name;
    $this->alter = $alter;
  }
  public function __call($method, $args) {
    // nur Methoden die mit 'get' beginnen werden akzeptiert
    if (substr($method, 0, 3) == 'get') {
      $prop = strtolower(substr($method, 3));
      return $this->{$prop};
    }
    throw new BadFunctionCallException("{$method} ist nicht erlaubt", 
              403);
  }
  public function __set($prop, $value) {
    if (in_array($prop, array('name', 'alter'))) {
      // ungültige Werte
      if (empty($value)) {
        throw new OutOfBoundsException("{$prop} darf nich leer sein", 
                  451);
      }
      $this->$prop = $value;
      return;
    }
    // ungültige Eigenschaftsnamen
    throw new InvalidArgumentException("{$prop} gibt es nicht", 
              404);
  }
}

$bully = new Werfer('Bully', 42);

// Aufruf "virtueller Methoden" für die geschützten
// Eigenschaften $name, $alter
echo $bully->getName(), ' ist ', $bully->getAlter(), PHP_EOL;

// edit() löst die Ausnahme aus
try {
    echo $bully->edit('Gucky'), PHP_EOL;
} catch(BadFunctionCallException $e) {
    // API-Fehler: Plan B!
    $bully->name =" Gucky";
} catch(Exception $e) {
    echo "Andere Fehler! Kein Plan!";
}

echo "\$bully heisst jetzt: " . $bully->getName();
