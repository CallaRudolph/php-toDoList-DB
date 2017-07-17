<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Task.php";
    require_once "src/Category.php";

    $server = 'mysql:host=localhost:8889;dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class TaskTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Task::deleteAll();
            Category::deleteAll();
        }

        function testGetDescription()
        {
            //Arrange
            $description = "Do dishes.";
            $date = "July 4";
            $test_task = new Task($description, $date);

            //Act
            $result = $test_task->getDescription();

            //Assert
            $this->assertEquals($description, $result);
        }

        function testSetDescription()
        {
            //Arrange
            $description = "Do dishes.";
            $date = "July 4";
            $test_task = new Task($description, $date);

            //Act
            $test_task->setDescription("Drink coffee.");
            $result = $test_task->getDescription();

            //Assert
            $this->assertEquals("Drink coffee.", $result);
        }

        function testGetID()
        {
            //Arrange
            $description = "Watch the new Thor movie";
            $date = "July 4";
            $test_task = new Task($description, $date);
            $test_task->save();

            //Acts
            $result = $test_task->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function testSave()
        {
            //Arrange
            $description = "Wash the dog";
            $date = "July 4";
            $test_task = new Task($description, $date);

            //Act
            $executed = $test_task->save();

            //Assert
            $this->assertTrue($executed, "Task not successfully saved to database");
        }

        function testGetAll()
        {
            //Arrange
            $description = "Wash the dog";
            $date = "July 5";
            $test_task = new Task($description, $date);
            $test_task->save();

            $description_2 = "Water the lawn";
            $date_2 = "Christmas";
            $test_task_2 = new Task($description_2, $date_2);
            $test_task_2->save();

            //Act
            $result = Task::getAll();

            //Assert
            $this->assertEquals([$test_task, $test_task_2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $description = "Wash the dog";
            $date = "July 5";
            $test_task = new Task($description, $date);
            $test_task->save();

            $description_2 = "Water the lawn";
            $date_2 = "Christmas";
            $test_task_2 = new Task($description_2, $date_2);
            $test_task_2->save();

            //Act
            Task::deleteAll();

            //Assert
            $result = Task::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $description = "Wash the dog";
            $date = "July 4";
            $test_task = new Task($description, $date);
            $test_task->save();

            $description_2 = "Water the lawn";
            $date_2 = "Christmas";
            $test_task_2 = new Task($description_2, $date_2);
            $test_task_2->save();

            //Act
            $result = Task::find($test_task->getId());

            //Assert
            $this->assertEquals($test_task, $result);
        }

        function testUpdate()
        {
            //Arrange
            $description = "Wash the dog";
            $date = "July 4";
            $test_task = new Task($description, $date);
            $test_task->save();

            $new_description = "Clean the dog";

            //Act
            $test_task->update($new_description);

            //Assert
            $this->assertEquals("Clean the dog", $test_task->getDescription());
        }

        function testDelete()
        {
            //Arrange
            $description = "Wash the dog";
            $date = "July 4";
            $test_task = new Task($description, $date);
            $test_task->save();

            $description_2 = "Water the lawn";
            $date_2 = "Christmas";
            $test_task_2 = new Task($description_2, $date_2);
            $test_task_2->save();

            //Act
            $test_task->delete();

            //Assert
            $this->assertEquals([$test_task_2], Task::getAll());
        }
    }
?>
