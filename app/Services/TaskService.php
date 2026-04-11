<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TimeTracking;
use App\Repositories\TaskRepository;
use App\Repositories\TimeTrackingRepository;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    public function __construct(
        protected TaskRepository $taskRepository,
        protected TimeTrackingRepository $timeRepository
    ){}


    public function getAllForUser(int $userId)
    {
        return $this->taskRepository->getAllForUser($userId);
    }

    public function findById(int $taskId): ?Task{
        return $this->taskRepository->findById($taskId);
    }

    public function create(int $userId, array $data): Task
    {
        return $this->taskRepository->create([
            'user_id'  => $userId,
            'goal_id'  => $data['goal_id'] ?? null,
            'title'    => $data['title'],
            'description'  => $data['description'] ?? null,
            'priority'     => $data['priority'],
            'status'    => 'todo',
            'period'   => $data['period'],
            'due_date'  => $data['due_date'] ?? null,

        ]);
    }

    public function update(Task $task , array $data) 
    {
        return $this->taskRepository->update($task, [
            'goal_id' => $data['goal_id'] ?? null ,
            'title'   => $data['title'],
            'description' => $data['description'] ?? null,
            'priority'    => $data['priority'],
            'status'      => $data['status'],
            'period'      => $data['period'],
            'due_date'    => $data['due_date'] ?? null,

        ]);
    }

    public function delete(Task $task): void
    {
        $this->taskRepository->delete($task);
    }

    public function getByPeriod(int $userId , string $period){
        return $this->taskRepository->getByPeriod($userId, $period);
    }

    public function getPending(int $userId )
    {
        return $this->taskRepository->getPending($userId);
    }

    public function getToday(int $userId)
    {
        return $this->taskRepository->getToday($userId);
    }

    public function authorizeUser(Task $task , int $userId): bool{

        return $task->user_id === $userId ;
    }

    public function startTimer(Task $task): TimeTracking
    {
        $running = $this->timeRepository->getRunningForTask($task->task_id);
        if($running){
            $this->timeRepository->stop($running);
        }

        $this->taskRepository->update($task, ['status' => 'in_progress']);

        return $this->timeRepository->start($task->task_id);
    }

    public function stopTimer(Task $task): ?TimeTracking
    {
        $running = $this->timeRepository->getRunningForTask($task->task_id);

        if(!$running){
            return null;
        }

        return $this->timeRepository->stop($running);
    }

    public function getTimeLogs(Task $task)
    {
        return $this->timeRepository->getForTask($task->task_id);
    }














}