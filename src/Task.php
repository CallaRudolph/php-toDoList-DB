<?php
    class Task
    {
        private $description;
        private $date;
        private $id;

        function __construct($description, $date, $id = null)
        {
            $this->description = $description;
            $this->date = $date;
            $this->id = $id;
        }

        function setDescription($new_description)
        {
            $this->description = (string) $new_description;
        }

        function getDescription()
        {
            return $this->description;
        }

        function setDate($new_date)
        {
            $this->date = (string) $new_date;
        }

        function getDate()
        {
            return $this->date;
        }

        function getID()
        {
            return $this->id;
        }

        function save()
        {
            $executed = $GLOBALS['DB']->exec("INSERT INTO tasks (description, due_date) VALUES ('{$this->getDescription()}', '{$this->getDate()}');");
            if ($executed) {
                $this->id = $GLOBALS['DB']->lastInsertID();
                return true;
            } else {
                return false;
            }
        }

        static function getAll()
        {
            $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
            $tasks = array();
            foreach($returned_tasks as $task) {
                $task_description = $task['description'];
                $task_date = $task['due_date'];
                $task_id = $task['id'];
                $new_task = new Task($task_description, $task_date, $task_id);
                array_push($tasks, $new_task);
            }
            return $tasks;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM tasks;");
        }

        static function find($search_id)
        {
            $found_task = null;
            $returned_tasks = $GLOBALS['DB']->prepare("SELECT * FROM tasks WHERE id = :id");
            $returned_tasks->bindParam(':id', $search_id, PDO::PARAM_STR);
            $returned_tasks->execute();
            foreach ($returned_tasks as $task) {
                $task_description = $task['description'];
                $task_date = $task['due_date'];
                $task_id = $task['id'];
                if ($task_id == $search_id) {
                    $found_task = new Task($task_description, $task_date, $task_id);
                }
            }
            return $found_task;
        }

        function update($new_description)
        {
            $executed = $GLOBALS['DB']->exec("UPDATE tasks SET description = '{$new_description}' WHERE id = {$this->getID()};");
            if ($executed) {
                $this->setDescription($new_description);
                return true;
            } else {
                return false;
            }
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM tasks WHERE id = {$this->getID()};");
        }
    }
 ?>
