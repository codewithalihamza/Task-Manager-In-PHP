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
            // Handle file upload
            $attachment = null;
            if (!empty($_FILES['attachment']['name'])) {
                $targetDir = 'attachments/';
                $fileName = basename($_FILES['attachment']['name']);
                $targetPath = $targetDir . $fileName;
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

                // Check if the file is an actual file or a fake file
                if (isset($_POST["submit"])) {
                    $check = getimagesize($_FILES['attachment']['tmp_name']);
                    if ($check !== false) {
                        $uploadOk = 1;
                    } else {
                        $uploadOk = 0;
                    }
                }

                // Check if file already exists
                if (file_exists($targetPath)) {
                    $uploadOk = 0;
                }

                // Check file size
                if ($_FILES['attachment']['size'] > 500000) {
                    $uploadOk = 0;
                }

                // Allow only specific file formats
                if ($imageFileType !== 'pdf' && $imageFileType !== 'jpg' && $imageFileType !== 'jpeg' && $imageFileType !== 'png') {
                    $uploadOk = 0;
                }

                // Move the uploaded file to the target directory
                if ($uploadOk) {
                    if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
                        $attachment = $fileName;
                    }
                }
            }

            // Handle task update
            if (empty($_POST['title'])) {
                header('Location: edit-todo.php?id=' . $_POST['id'] . '&error=Title is required');
                exit();
            }

            $id = $_POST['id'];
            $title = $_POST['title'];
            $due_date = $_POST['due_date'];

            $sql = "UPDATE todos SET `title` = '$title', `due_date` = '$due_date', `attachment` = '$attachment' WHERE `id` = $id";
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

if (empty($_GET['id'])) {
    header('Location: index.php?error=Select at least one todo');
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM todos WHERE `id` = $id";
$result = mysqli_query($db, $sql);
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/main.css">
</head>

<body>
    <div class="container">
        <form action="edit-todo.php" method="POST" enctype="multipart/form-data">
            <div class="todo-table">
                <h1>Edit Task</h1>
                <?php if (!empty($_GET['error'])) : ?>
                    <p class="error"><?php echo $_GET['error']; ?></p>
                <?php endif; ?>
                <div class="form-elements">
                    <input type="text" name="title" required value="<?php echo $data['title']; ?>" placeholder="Type your task here...">
                    <input type="date" name="due_date" value="<?php echo $data['due_date']; ?>" placeholder="Due Date (YYYY-MM-DD)">
                    <input type="file" name="attachment">
                </div>

                <input type="hidden" name="action" value="add">
                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                <button class="btn btn-purple" type="submit"><i class="fa fa-save"></i> Save</button>
                <a href="index.php" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </form>
    </div>
</body>

</html>
