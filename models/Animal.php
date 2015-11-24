<?php
  class Animal {
    private $idAnimal;
    private $type;
    private $name;
    private $breed;
    private $age;
    private $gender;
    private $catsFriend;
    private $dogsFriend;
    private $childrenFriend;
    private $description;
    private $state;

    private static $STATE_ADOPTED = 2;
    private static $STATE_ADOPTION = 1;

    public function __construct($idAnimal) {
      $db = DbManager::getPDO();
      $query = "SELECT * FROM Animal WHERE idAnimal = ".$idAnimal."";
      $res = $db->query($query)->fetch();
      $this->idAnimal = $res['idAnimal'];
      $this->type = $res['idType'];
      $this->name = $res['name'];
      $this->breed = $res['breed'];
      $this->age = $res['age'];
      $this->gender = $res['gender'];
      $this->catsFriend = $res['catsFriend'];
      $this->dogsFriend = $res['dogsFriend'];
      $this->childrenFriend = $res['childrenFriend'];
      $this->description = $res['description'];
      $this->state = $res['idState'];
    }

    /**
     * @return true si l'animal est présent dans la BDD, false sinon.
     */
    public static function isAnimalExistInDataBase($idAnimal) {
      $db = DbManager::getPDO();
      $query = "SELECT idAnimal FROM Animal WHERE idAnimal='".$idAnimal."';";
      $res = $db->query($query)->fetch();
      return $res['idAnimal'] === $idAnimal;
    }

    /**
     * @return true si l'ajout c'est bien passé, false sinon
     */
    public static function addAnimalInDataBase($type, $name, $breed, $age, $gender, $catsFriend, $dogsFriend,
                                               $childrenFriend, $description, $state) {
      $db = DbManager::getPDO();
      $query = "INSERT INTO Animal(type, name, breed, age, gender, catsFriend, dogsFriend, childrenFriend, description, state)
                VALUES (".$type.",'".$name."','".$breed."','".$age."','".$gender."','".$catsFriend."','".$dogsFriend."','".$childrenFriend."',
                '".$description."',".$state.");";
      return $db->exec($query);
    }

    /**
     * @action met à jour le statut de l'animal
     * @return true/false suivant le resultat de la requete,
     *        "Unknown animal" si l'animal n'est pas présent dans la BDD
     */
    public static function updateStatus($idAnimal, $newStatus) {
      if(Animal::isAnimalExistInDataBase($idAnimal)) {
        $db = DbManager::getPDO();
        $query = "UPDATE Animal SET idState = ".$newStatus." WHERE idAnimal = ".$idAnimal.";";
        return ($db->exec($query)>=0);
      } else {
        return "Unknown animal";
      }
    }

    /**
     * @return l'identifiant de l'animal en question
     */
    public static function getAnimalsId($type, $name, $breed, $age, $gender, $catsFriend, $dogsFriend,
                                        $childrenFriend, $description, $state) {
      $db = DbManager::getPDO();
      $query = "SELECT idAnimal FROM Animal WHERE type=".$type.", name='".$name."', age='".$age."', gender='".$gender."', catsFriend='".$catsFriend."'
                ,dogsFriend='".$dogsFriend."', childrenFriend='".$childrenFriend."', description='".$description."', idState='".$state."';";
      $res = $db->query($query)->fetch();
      return $res['idAnimal'];
    }

    /**
     * @return transforme le résultat du fetch d'un animal en un tableau contenant
     *         les informations de l'animal pour ensuite le transmettre au clien
     */
    private static function getAnimalArrayFromFetch($animal) {
      $animalArray["idAnimal"] = intval($animal["idAnimal"]);
      $animalArray["idType"] = intval($animal["idType"]);
      $animalArray["name"] = $animal["name"];
      $animalArray["breed"] = $animal["breed"];
      $animalArray["age"] = $animal["age"];
      $animalArray["gender"] = $animal["gender"];
      $animalArray["catsFriend"] = $animal["catsFriend"];
      $animalArray["dogsFriend"] = $animal["dogsFriend"];
      $animalArray["childrenFriend"] = $animal["childrenFriend"];
      $animalArray["description"] = $animal["description"];
      $animalArray["idState"] = intval($animal["idState"]);
      return $animalArray;
    }

    /**
     * @return la liste des animaux à l'adoption
     */
    public static function getHomelessAnimals() {
      $db = DbManager::getPDO();
      $query = "SELECT * FROM Animal WHERE idState='".self::$STATE_ADOPTION."';";
      $res = $db->query($query)->fetchAll();

      for ($i=0; $i<count($res); $i++) {
        $animal = Animal::getAnimalArrayFromFetch($res[$i]);
        $listAnimals[$animal['idAnimal']] = $animal;
      }

      return $listAnimals;
    }
  }



 ?>
