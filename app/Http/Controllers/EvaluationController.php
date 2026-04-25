<?php
// app/Http/Controllers/EvaluationController.php

namespace App\Http\Controllers;

use App\Models\SelfEvaluation;
use App\Services\EvaluationService;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function __construct(
        protected EvaluationService $evaluationService
    ) {
    }

    public function index(Request $request)
    {
        $userId = auth()->id();
        $type = $request->query('type', 'all');

        $evaluations = $type === 'all'
            ? $this->evaluationService->getAllForUser($userId)
            : $this->evaluationService->getByPeriodType($userId, $type);

        $averages = [
            'day' => $this->evaluationService->getAverageScore($userId, 'day'),
            'week' => $this->evaluationService->getAverageScore($userId, 'week'),
            'month' => $this->evaluationService->getAverageScore($userId, 'month'),
            'year' => $this->evaluationService->getAverageScore($userId, 'year'),
        ];

        return view('evaluations.index', compact('evaluations', 'type', 'averages'));
    }

    public function create()
    {
        return view('evaluations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'period_type' => 'required|in:day,week,month,year',
            'period_date' => 'nullable|date',
            'score' => 'required|integer|min:1|max:10',
            'comment' => 'nullable|string|max:1000',
        ]);

        $this->evaluationService->create(auth()->id(), $validated);

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluation saved successfully.');
    }

    public function show(int $id)
    {
        $evaluation = $this->evaluationService->findById($id);

        if (!$evaluation || !$this->evaluationService->authorizeUser($evaluation, auth()->id())) {
            abort(403);
        }

        $label = $this->evaluationService->getPeriodLabel(
            $evaluation->period_type,
            $evaluation->period_date
        );

        return view('evaluations.show', compact('evaluation', 'label'));
    }

    public function edit(int $id)
    {
        $evaluation = $this->evaluationService->findById($id);

        if (!$evaluation || !$this->evaluationService->authorizeUser($evaluation, auth()->id())) {
            abort(403);
        }

        return view('evaluations.edit', compact('evaluation'));
    }

    public function update(Request $request, int $id)
    {
        $evaluation = $this->evaluationService->findById($id);

        if (!$evaluation || !$this->evaluationService->authorizeUser($evaluation, auth()->id())) {
            abort(403);
        }

        $validated = $request->validate([
            'score' => 'required|integer|min:1|max:10',
            'comment' => 'nullable|string|max:1000',
        ]);

        $this->evaluationService->update($evaluation, $validated);

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluation updated successfully.');
    }

    public function destroy(int $id)
    {
        $evaluation = $this->evaluationService->findById($id);

        if (!$evaluation || !$this->evaluationService->authorizeUser($evaluation, auth()->id())) {
            abort(403);
        }

        $this->evaluationService->delete($evaluation);

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluation deleted successfully.');
    }
}