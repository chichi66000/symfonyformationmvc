<?php
namespace App\data;

class Todo {
    public int $id=1;
    public string $task;
    public string $description;
    public bool $completed;
    private static int $count = 1;

    public function __construct($task, $description) 
    {
        $this->completed = false;
        $this->task = $task;
        $this->description = $description;
        $this->id = self::$count;
        self::$count++;























































    }
}
