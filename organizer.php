<?php
class Organizer
{
    public function __construct()
    {
        if (!isset($_SESSION['tasks']))
        {
            $_SESSION['tasks'] = [];
        }
    }
    public function addTask($date, $task)
    {
        if (!isset($_SESSION['tasks'][$date]))
        {
            $_SESSION['tasks'][$date] = [];
        }
        if (!in_array($task, $_SESSION['tasks'][$date]))
        {
            $_SESSION['tasks'][$date][] = $task;
        }
    }
    public function editTask($date, $oldTask, $newTask)
    {
        if (isset($_SESSION['tasks'][$date]))
        {
            $key = array_search($oldTask, $_SESSION['tasks'][$date]);
            if ($key !== false)
            {
                $_SESSION['tasks'][$date][$key] = $newTask;
            }
        }
    }
    public function removeTask($date, $task)
    {
        if (isset($_SESSION['tasks'][$date]))
        {
            $key = array_search($task, $_SESSION['tasks'][$date]);
            if ($key !== false)
            {
                unset($_SESSION['tasks'][$date][$key]);
                if (empty($_SESSION['tasks'][$date]))
                {
                    unset($_SESSION['tasks'][$date]);
                }
            }
        }
    }
    public function getFilteredTasks($filter = 'all')
    {
        $tasks = $_SESSION['tasks'];
        $filteredTasks = [];

        $today = date('Y-m-d');
        $weekAgo = date('Y-m-d', strtotime('-1 week'));
        $monthAgo = date('Y-m-d', strtotime('-1 month'));

        foreach ($tasks as $date => $taskList)
        {
            if ($filter === 'today' && $date === $today)
            {
                $filteredTasks[$date] = $taskList;
            }
            elseif ($filter === 'week' && $date >= $weekAgo)
            {
                $filteredTasks[$date] = $taskList;
            }
            elseif ($filter === 'month' && $date >= $monthAgo)
            {
                $filteredTasks[$date] = $taskList;
            }
            elseif ($filter === 'all')
            {
                $filteredTasks[$date] = $taskList;
            }
        }

        return $filteredTasks;
    }
}
