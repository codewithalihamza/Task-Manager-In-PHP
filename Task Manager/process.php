<?php
session_start();

if (empty($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include_once('config.php');
include_once('database.php');

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            if (empty($_POST['title'])) {
                header('Location: add-todo.html');
                exit();
            }

            $title = $_POST['title'];
            $due_date = $_POST['due_date'];

              // Handle file upload
            $attachment = null;
            if ($_FILES['attachment']['name']) {
                $attachment = $_FILES['attachment']['name'];
                $targetDir = "attachments/";
                $targetFile = $targetDir . basename($attachment);
                move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetFile);
            }

            $sql = "INSERT INTO todos (`user_id`, `title`, `due_date`, `attachment`) VALUES ((SELECT id FROM users WHERE username = '{$_SESSION['username']}'), '$title', '$due_date', '$attachment')";
            $result = mysqli_query($db, $sql);


          

            if ($result !== false) {
                header('Location: index.php?success=New Task added');
            } else {
                header('Location: index.php?error=Failed to add task');
            }
            exit();
            break;

        case 'complete':
            if (empty($_POST['todo'])) {
                header('Location: index.php?error=Select at least one Task');
                exit();
            }

            $todoId = $_POST['todo'];

            $sql = "UPDATE todos SET `status` = 1 WHERE id = $todoId";
            $result = mysqli_query($db, $sql);

            if ($result !== false) {
                header('Location: index.php?success=Task marked complete');
            } else {
                header('Location: index.php?error=Failed to mark task as complete');
            }
            exit();
            break;

        case 'delete':
            if (empty($_POST['todo'])) {
                header('Location: index.php?error=Select at least one todo');
                exit();
            }

            $todoId = $_POST['todo'];

            $sql = "DELETE FROM todos WHERE id = $todoId";
            $result = mysqli_query($db, $sql);

            if ($result !== false) {
                header('Location: index.php?success=Task deleted');
            } else {
                header('Location: index.php?error=Failed to delete task');
            }
            exit();
            break;

        case 'edited':
            if (empty($_POST['id'])) {
                header('Location: index.php?error=Select at least one Task');
                exit();
            }

            $id = $_POST['id'];
            $title = $_POST['title'];
            $due_date = $_POST['due_date'];

            $sql = "UPDATE todos SET `title` = '$title', `due_date` = '$due_date' WHERE `id` = $id";
            $result = mysqli_query($db, $sql);

            if ($result !== false) {
                header('Location: index.php?success=Task updated');
            } else {
                header('Location: index.php?error=Failed to update task');
            }
            exit();
            break;
    }
}

header('Location: index.php');
exit();
