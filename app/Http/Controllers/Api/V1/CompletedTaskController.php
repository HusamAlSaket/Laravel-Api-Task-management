<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Task;

class CompletedTaskController extends Controller
{
    /**
     * Mark a task as complete.
     */
    public function __invoke(Task $task)
    {
        $task->update(['is_completed' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Task marked as complete',
            'task' => $task,
        ]);
    }
}
