<?php


namespace App\Repositories;

use App\Models\SelfEvaluation;
use Illuminate\Database\Eloquent\Collection;

class EvaluationRepository
{
    public function getAllForUser(int $userId): Collection
    {
        return SelfEvaluation::forUser($userId)
            ->orderBy('period_date', 'desc')
            ->get();
    }

    public function findById(int $evaluationId): ?SelfEvaluation
    {
        return SelfEvaluation::find($evaluationId);
    }

    public function findForPeriod(int $userId, string $type, string $date): ?SelfEvaluation
    {
        return SelfEvaluation::forUser($userId)
            ->forPeriod($type, $date)
            ->first();
    }

    public function create(array $data): SelfEvaluation
    {
        return SelfEvaluation::create($data);
    }

    public function update(SelfEvaluation $evaluation, array $data): SelfEvaluation
    {
        $evaluation->update($data);
        return $evaluation->fresh();
    }

    public function delete(SelfEvaluation $evaluation): void
    {
        $evaluation->delete();
    }

    public function getByPeriodType(int $userId, string $type): Collection
    {
        return SelfEvaluation::forUser($userId)
            ->byPeriodType($type)
            ->orderBy('period_date', 'desc')
            ->get();
    }

    public function getRecent(int $userId, int $limit = 5): Collection
    {
        return SelfEvaluation::forUser($userId)
            ->orderBy('period_date', 'desc')
            ->limit($limit)
            ->get();
    }
}