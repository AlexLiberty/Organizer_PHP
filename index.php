<?php
session_start();
define('ORGANIZER_INCLUDED', true);
require_once 'Organizer.php';

$organizer = new Organizer();

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $date = isset($_POST['date']) ? $_POST['date'] : null;
    $task = isset($_POST['task']) ? $_POST['task'] : null;

    if ($action === 'add' && $date && $task)
    {
        $organizer->addTask($date, $task);
    }
    elseif ($action === 'edit' && $date && $task && isset($_POST['oldTask']))
    {
        $oldTask = $_POST['oldTask'];
        $organizer->editTask($date, $oldTask, $task);
    }
    elseif ($action === 'remove' && $date && $task)
    {
        $organizer->removeTask($date, $task);
    }
}

$sortOption = isset($_POST['sort']) ? $_POST['sort'] : 'all';
$tasks = $organizer->getFilteredTasks($sortOption);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Органайзер задач</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1>Органайзер задач</h1>

    <form action="" method="POST">
        <input type="hidden" name="action" value="add">
        <label for="date">Дата:</label>
        <input type="date" name="date" required>
        <label for="task">Задача:</label>
        <input type="text" name="task" required>
        <button type="submit">Додати задачу</button>
    </form>

    <form action="" method="POST">
        <label>Сортувати по:</label>
        <label><input type="radio" name="sort" value="today" <?php if ($sortOption === 'today') echo 'checked'; ?>> Сьогодні</label>
        <label><input type="radio" name="sort" value="week" <?php if ($sortOption === 'week') echo 'checked'; ?>> За тиждень</label>
        <label><input type="radio" name="sort" value="month" <?php if ($sortOption === 'month') echo 'checked'; ?>> За місяць</label>
        <label><input type="radio" name="sort" value="all" <?php if ($sortOption === 'all') echo 'checked'; ?>> Показати всі</label>
        <button type="submit">Застосувати</button>
    </form>

    <h2>Список задач</h2>
    <div class="task-list">
        <?php if (!empty($tasks)) : ?>
            <?php foreach ($tasks as $date => $taskList) : ?>
                <div class="task-date"><strong><?php echo htmlspecialchars($date); ?></strong></div>
                <?php foreach ($taskList as $task) : ?>
                    <div class="task-item">
                        <div class="task-text"><?php echo htmlspecialchars($task); ?></div>

                        <div class="task-actions">
                            <form action="" method="POST" class="inline">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="date" value="<?php echo $date; ?>">
                                <input type="hidden" name="oldTask" value="<?php echo htmlspecialchars($task); ?>">
                                <input type="text" name="task" placeholder="Змінити задачу" required>
                                <button type="submit" class="edit-btn">Редагувати</button>
                            </form>

                            <form action="" method="POST" class="inline">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="date" value="<?php echo $date; ?>">
                                <input type="hidden" name="task" value="<?php echo htmlspecialchars($task); ?>">
                                <button type="submit" class="delete-btn">Видалити</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Задачі відсутні.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
