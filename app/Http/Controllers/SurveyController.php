<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::with('status')->get();
        return response()->json(['data' => $surveys]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_survey' => 'required|string|max:255',
            'description_survey' => 'nullable|string|max:255',
            'content_survey' => 'nullable|array',
        ]);

        $user = $request->user();
        $statusId = 1;

        $survey = Survey::create([
            'title_survey' => $request->title_survey,
            'description_survey' => $request->description_survey,
            'content_survey' => $request->content_survey,
            'user_id' => $user->id,
            'status_id' => $statusId,
        ]);

        return response()->json($survey, 201);
    }

    public function show($id)
    {
        $survey = Survey::with('status')->find($id);

        if (!$survey) {
            return response()->json(['message' => 'Survey not found'], 404);
        }

        return response()->json($survey);
    }

    public function update(Request $request, $id)
    {
        try {
            $survey = Survey::find($id);

            if (!$survey) {
                return response()->json(['message' => 'Survey not found'], 404);
            }

            $request->validate([
                'title_survey' => 'sometimes|required|string|max:255',
                'description_survey' => 'sometimes|nullable|string|max:255',
                'content_survey' => 'sometimes|nullable|array',
                'status_id' => 'sometimes|required|exists:survey_statuses,id',
            ]);

            $survey->update($request->only([
                'title_survey',
                'description_survey',
                'content_survey',
                'status_id',
            ]));

            return response()->json($survey);
        } catch (\Exception $e) {
            Log::error('Error saving survey: ' . $e->getMessage());
            return response()->json(['message' => 'Error saving survey', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatusAndSurvey(Request $request, $id)
    {
        try {
            $survey = Survey::find($id);

            if (!$survey) {
                return response()->json(['message' => 'Survey not found'], 404);
            }

            $request->validate([
                'title_survey' => 'sometimes|string|max:255',
                'description_survey' => 'sometimes|nullable|string|max:255',
                'content_survey' => 'sometimes|nullable|array',
                'status_id' => 'required|exists:survey_statuses,id',
            ]);

            $survey->update($request->only([
                'title_survey',
                'description_survey',
                'content_survey',
                'status_id',
            ]));

            return response()->json(['message' => 'Survey updated successfully', 'survey' => $survey]);
        } catch (\Exception $e) {
            Log::error('Error updating survey: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating survey', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        $survey = Survey::find($id);

        if (!$survey) {
            return response()->json(['message' => 'Survey not found'], 404);
        }

        $survey->delete();

        return response()->json(['message' => 'Survey deleted']);
    }
}
