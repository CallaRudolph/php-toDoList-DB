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
            $completed = true;
            $test_task = new Task($description, $date, $completed);

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
            $completed = true;
            $test_task = new Task($description, $date, $completed);

            //Act
            $test_task->setDescription("Drink coffee.");
            $result = $test_task->getDescription();

            //Assert
            $this->assertEquals("Drink coffee.", $result);
        }

        function testGetDate()
        {
            //Arrange
            $description = "Do dishes.";
            $date = "July 4";
            $completed = true;
            $test_task = new Task($description, $date, $completed);

            //Act
            $result = $test_task->getDate();

            //Assert
            $this->assertEquals($date, $result);
        }

        function testSetDate()
        {
            //Arrange
            $description = "Do dishes.";
            $date = "July 4";
            $completed = true;
            $test_task = new Task($description, $date, $completed);

            //Act
            $test_task->setDate("December 25");
            $result = $test_task->getDate();

            //Assert
            $this->assertEquals("December 25", $result);
        }

        function testGetCompleted()
        {
            // Arrange
            $description = "Do dishes.";
            $date = "July 4";
            $completed = false;
            $test_task = new Task($description, $date, $completed);

            // Act
            $result = $test_task->getCompleted();

            // Assert
            $this->assertEquals($completed, $result);
        }

        function testSetCompleted()
        {
            // Arrange
            $description = "Do dishes.";
            $date = "July 4";
            $completed = true;
            $test_task = new Task($description, $date, $completed);

            // Act
            $test_task->setCompleted(false);
            $result = $test_task->getCompleted();

            // Assert
            $this->assertEquals(false, $result);
        }

        function testGetID()
        {
            //Arrange
            $description = "Watch the new Thor movie";
            $date = "July 4";
            $completed = true;
            $test_task = new Task($description, $date, $completed);
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
            $completed = true;
            $test_task = new Task($description, $date, $completed);

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
            $completed = true;
            $test_task = new Task($description, $date, $completed);
            $test_task->save();

            $description_2 = "Water the lawn";
            $date_2 = "Christmas";
            $completed_2 = 0;
            $test_task_2 = new Task($description_2, $date_2, $completed_2);
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
            $completed = true;
            $test_task = new Task($description, $date, $completed);
            $test_task->save();

            $description_2 = "Water the lawn";
            $date_2 = "Christmas";
            $completed_2 = false;
            $test_task_2 = new Task($description_2, $date_2, $completed_2);
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
            $completed = true;
            $test_task = new Task($description, $date, $completed);
            $test_task->save();

            $description_2 = "Water the lawn";
            $date_2 = "Christmas";
            $completed_2 = false;
            $test_task_2 = new Task($description_2, $date_2, $completed_2);
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
            $completed = true;
            $test_task = new Task($description, $date, $completed);
            $test_task->save();

            $new_description = "Clean the dog";

            //Act
            $test_task->update($new_description);

            //Assert
            $this->assertEquals("Clean the dog", $test_task->getDescription());
        }

        function testUpdateDate()
        {
            //Arrange
            $description = "Wash the dog";
            $date = "July 4";
            $completed = true;
            $test_task = new Task($description, $date, $completed);
            $test_task->save();

            $new_date = "Christmas";

            //Act
            $test_task->updateDate($new_date);

            //Assert
            $this->assertEquals("Christmas", $test_task->getDate());
        }

        function testUpdateCompleted()
        {
            // Arrange
            $description = "Wash the dog";
            $date = "July 4";
            $completed = true;
            $test_task = new Task($description, $date, $completed);
            $test_task->save();
            $new_completed = false;

            // Act
            $test_task->updateCompleted($new_completed);

            // Assert
            $this->assertEquals(false, $test_task->getCompleted());
     }

        function testDelete()
        {
            //Arrange
            $name = "Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "Wash the dog";
            $date = "July 4";
            $completed = true;
            $test_task = new Task($description, $date, $completed);
            $test_task->save();

            //Act
            $test_task->addCategory($test_category);
            $test_task->delete();

            //Assert
            $this->assertEquals([], $test_category->getTasks());
        }

        function testAddCategory()
        {
            //Arrange
            $name = "Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "Wash the dog";
            $date = "July 4";
            $completed = true;
            $test_task = new Task($description, $date, $completed);
            $test_task->save();

            //Act
            $test_task->addCategory($test_category);

            //Assert
            $this->assertEquals($test_task->getCategories(), [$test_category]);
        }

        function testGetCategories()
        {
            //Arrange
            $name = "Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            $name2 = "Home stuff";
            $test_category2 = new Category($name2);
            $test_category2->save();

            $description = "Wash the dog";
            $date = "July 4";
            $completed = true;
            $test_task = new Task($description, $date, $completed);
            $test_task->save();

            //Act
            $test_task->addCategory($test_category);
            $test_task->addCategory($test_category2);

            //Assert
            $this->assertEquals($test_task->getCategories(), [$test_category, $test_category2]);
        }
    }
?>
