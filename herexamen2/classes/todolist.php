<?php
class TodoList
{
    private $name;
    private $description;

    public function __construct($name, $description)
    {
        $this->setName($name);
        $this->setDescription($description);
    }

    // Getter voor naam
    public function getName()
    {
        return $this->name;
    }

    // Setter voor naam
    public function setName($name)
    {
        if (empty($name)) {
            throw new Exception("Name cannot be empty");
        }
        $this->name = $name;
    }

    // Getter voor beschrijving
    public function getDescription()
    {
        return $this->description;
    }

    // Setter voor beschrijving
    public function setDescription($description)
    {
        if (empty($description)) {
            throw new Exception("Description cannot be empty");
        }
        $this->description = $description;
    }
}
?>