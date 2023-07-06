<?php
session_start();

if (empty($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include_once('config.php');
include_once('database.php');

$sql = "SELECT * FROM todos WHERE `user_id` = (SELECT id FROM users WHERE username = '{$_SESSION['username']}') ORDER BY `id` DESC";
$result = mysqli_query($db, $sql);
$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
        <div class="todo-table">
            <h1>Task Manager</h1>

            <?php if (!empty($_GET['success'])) : ?>
                <p class="success"><?php echo $_GET['success']; ?></p>
            <?php endif; ?>

            <?php if (!empty($_GET['error'])) : ?>
                <p class="error"><?php echo $_GET['error']; ?></p>
            <?php endif; ?>

            <div class="user-greeting" >
                <center>
                <p style="padding: 10px">Welcome, <?php echo $_SESSION['username']; ?>!</p>
                <a href="logout.php" class="btn btn-primary">Logout</a>
                </center>
            </div>

            <div class="add-task">
                <a href="add-todo.html" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Task</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $task) : ?>
                        <tr>
                            <td><?php echo $task['title']; ?></td>
                            <td><?php echo $task['due_date']; ?></td>
                            <td><?php echo $task['status'] ? 'Completed' : 'Pending'; ?></td>
                            <td>
                                <a href="edit-todo.php?id=<?php echo $task['id']; ?>" class="btn btn-icon"><i class="fa fa-pencil"></i></a>
                                <form action="process.php" method="POST" class="form-inline">
                                    <input type="hidden" name="todo" value="<?php echo $task['id']; ?>">
                                    <input type="hidden" name="action" value="complete">
                                    <button type="submit" class="btn btn-icon"><i class="fa fa-check"></i></button>
                                </form>
                                <form action="process.php" method="POST" class="form-inline">
                                    <input type="hidden" name="todo" value="<?php echo $task['id']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn btn-icon"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
