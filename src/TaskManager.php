<?php

class TaskManager
{
    private $filePath;

    public function __construct($filePath = '../data/tasks.json')
    {
        $this->filePath = $filePath;

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    public function getTaskById($id)
    {
        $tasks = $this->getAllTasks();

        foreach ($tasks as $task) {
            if ($task['id'] == $id) {
                return $task;
            }
        }

        return null;
    }


    public function getAllTasks()
    {
        $data = file_get_contents($this->filePath);
        return json_decode($data, true) ?? [];
    }

    public function addTask($name, $isPublic = false, $userId = null)
    {
        $tasks = $this->getAllTasks();

        $id = count($tasks) > 0 ? max(array_column($tasks, 'id')) + 1 : 1;

        $newTask = [
            'id' => $id,
            'name' => htmlspecialchars($name),
            'is_completed' => false,
            'is_public' => $isPublic,
            'user_id' => $userId,
            'due_date' => null
        ];

        $tasks[] = $newTask;
        $this->saveTasks($tasks);
    }

    public function updateTaskStatus($id, $isCompleted)
    {
        $tasks = $this->getAllTasks();

        foreach ($tasks as &$task) {
            if ($task['id'] == $id) {
                $task['is_completed'] = $isCompleted;
                break;
            }
        }

        $this->saveTasks($tasks);
    }

    public function deleteTask($id)
    {
        $tasks = $this->getAllTasks();

        $tasks = array_filter($tasks, function ($task) use ($id) {
            return $task['id'] != $id;
        });

        $this->saveTasks(array_values($tasks));
    }

    private function saveTasks($tasks)
    {
        file_put_contents($this->filePath, json_encode($tasks, JSON_PRETTY_PRINT));
    }
}
