<?php



namespace App\Services;

use App\Models\SelfEvaluation;
use App\Repositories\EvaluationRepository;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class EvaluationService
{
    public function __construct(
        protected EvaluationRepository $evaluationRepository
    ) {}

    public function getAllForUser(int $userId): Collection
    {
        return $this->evaluationRepository->getAllForUser($userId);
    }

    public function findById(int $evaluationId): ?SelfEvaluation
    {
        return $this->evaluationRepository->findById($evaluationId);
    }

    public function create(int $userId, array $data): SelfEvaluation
    {
        $periodDate = $this->resolvePeriodDate($data['period_type'], $data['period_date'] ?? null);

        // Check if evaluation already exists for this period
        $existing = $this->evaluationRepository->findForPeriod(
            $userId,
            $data['period_type'],
            $periodDate
        );

        if ($existing) {
            return $this->evaluationRepository->update($existing, [
                'score'   => $data['score'],
                'comment' => $data['comment'] ?? null,
            ]);
        }

        return $this->evaluationRepository->create([
            'user_id'     => $userId,
            'period_type' => $data['period_type'],
            'period_date' => $periodDate,
            'score'       => $data['score'],
            'comment'     => $data['comment'] ?? null,
        ]);
    }

    public function update(SelfEvaluation $evaluation, array $data): SelfEvaluation
    {
        return $this->evaluationRepository->update($evaluation, [
            'score'   => $data['score'],
            'comment' => $data['comment'] ?? null,
        ]);
    }

    public function delete(SelfEvaluation $evaluation): void
    {
        $this->evaluationRepository->delete($evaluation);
    }

    public function getByPeriodType(int $userId, string $type): Collection
    {
        return $this->evaluationRepository->getByPeriodType($userId, $type);
    }

    public function getRecent(int $userId): Collection
    {
        return $this->evaluationRepository->getRecent($userId);
    }

    public function authorizeUser(SelfEvaluation $evaluation, int $userId): bool
    {
        return $evaluation->user_id === $userId;
    }

    public function getAverageScore(int $userId, string $periodType): float
    {
        $evaluations = $this->evaluationRepository->getByPeriodType($userId, $periodType);

        if ($evaluations->isEmpty()) return 0;

        return round($evaluations->avg('score'), 1);
    }

    // Resolve period date based on type
    private function resolvePeriodDate(string $type, ?string $date): string
    {
        if ($date) return $date;

        return match($type) {
            'day'   => today()->toDateString(),
            'week'  => now()->startOfWeek()->toDateString(),
            'month' => now()->startOfMonth()->toDateString(),
            'year'  => now()->startOfYear()->toDateString(),
            default => today()->toDateString(),
        };
    }

    public function getPeriodLabel(string $type, string $date): string
    {
        $carbon = Carbon::parse($date);

        return match($type) {
            'day'   => $carbon->format('D, M j Y'),
            'week'  => 'Week of ' . $carbon->format('M j') . ' – ' . $carbon->copy()->endOfWeek()->format('M j, Y'),
            'month' => $carbon->format('F Y'),
            'year'  => $carbon->format('Y'),
            default => $carbon->toFormattedDateString(),
        };
    }
}