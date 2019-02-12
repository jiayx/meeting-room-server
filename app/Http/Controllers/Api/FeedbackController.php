<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends ApiController
{
    public function index()
    {
        $feedback = Feedback::all();

        return $this->response($feedback);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|string|max:233',
        ]);

        $content = $request->input('content');

        try {
            $userId = current_user_id();
        } catch (\Exception $e) {
            $userId = 0;
        }

        $feedback = Feedback::create([
            'user_id' => $userId,
            'content' => $content,
        ]);

        return $this->response($feedback);
    }
}
