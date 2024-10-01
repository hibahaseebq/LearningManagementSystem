<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\QuizAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    // Upload a video associated with a quiz assignment
    // public function upload(Request $request)
    // {
    //     $validated = $request->validate([
    //         'quiz_assignment_id' => 'required|exists:quiz_assignments,id',
    //         'video' => 'required|file|mimetypes:video/mp4,video/mpeg,video/ogg|max:10240', // 10MB max
    //         'comment' => 'nullable|string',
    //     ]);

    //     $path = $request->file('video')->store('videos');

    //     $video = Video::create([
    //         'quiz_assignment_id' => $validated['quiz_assignment_id'],
    //         'video_path' => $path,
    //         'comment' => $validated['comment'],
    //     ]);

    //     return successResponse('Video uploaded successfully', ['video' => $video], 201);
    // }

    // // Retrieve the video for a specific quiz assignment
    // public function show($videoId)
    // {
    //     $video = Video::find($videoId);
    //     if (!$video) {
    //         return errorResponse('Video not found', 404);
    //     }

    //     $videoPath = Storage::url($video->video_path);
    //     return successResponse('Video retrieved successfully', ['video_url' => $videoPath, 'comment' => $video->comment]);
    // }

    // // Delete a video
    // public function destroy($videoId)
    // {
    //     $video = Video::find($videoId);
    //     if (!$video) {
    //         return errorResponse('Video not found', 404);
    //     }

    //     Storage::delete($video->video_path);
    //     $video->delete();

    //     return successResponse('Video deleted successfully');
    // }
}
