<?php

class Task
{
    private $id;
    private $name;
    private $isCompleted;

    public function __construct($id, $name, $isCompleted = false)
    {
        $this->id = $id;
        $this->name = htmlspecialchars($name);
        $this->isCompleted = $isCompleted;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isCompleted()
    {
        return $this->isCompleted;
    }

    public function setName($name)
    {
        $this->name = htmlspecialchars($name);
    }

    public function setCompleted($isCompleted)
    {
        $this->isCompleted = $isCompleted;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_completed' => $this->isCompleted,
        ];
    }

    public static function fromArray($data)
    {
        return new self(
            $data['id'] ?? null,
            $data['name'] ?? '',
            $data['is_completed'] ?? false
        );
    }
}
