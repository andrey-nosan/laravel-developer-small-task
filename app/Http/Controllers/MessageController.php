<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $response = [
            'messages' => Message::paginate(config('app.pagination.size')),
        ];

        return view('pages.message.index', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $response = [
            'teachers' => Teacher::all(),
            'students' => Student::all(),
        ];

        return view('pages.message.create', $response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(): RedirectResponse
    {
        $file = config('app.message_dir') . DIRECTORY_SEPARATOR . Str::uuid();
        Storage::put($file, request()->get('body'));
        request()->merge(['body' => $file]);

        /** @var Message $message */
        $message = Message::create(request()->only((new Message)->getFillable()));

        $message->teachers()->attach(request()->get('teachers'));
        $message->students()->attach(request()->get('students'));

        $message->save();

        return redirect()->route('message.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Message $message
     * @return View
     */
    public function edit(Message $message): View
    {
        $message = Message::find($message);

        $response = [
            'message' => $message->load(['teachers', 'students'])->first(),
            'teachers' => Teacher::all(),
            'students' => Student::all(),
        ];

        return view('pages.message.edit', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Models\Message $message
     * @return RedirectResponse
     */
    public function update(Message $message): RedirectResponse
    {
        Storage::put($message->body_url, request()->get('body'));

        $message->fill(request()->except('body'));

        $message->teachers()->sync(request()->get('teachers'), true);
        $message->students()->sync(request()->get('students'), true);

        $message->save();

        return redirect()->route('message.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Message $message
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Message $message): RedirectResponse
    {
        $message->teachers()->detach();
        $message->students()->detach();
        $message->delete();

        Storage::delete($message->body_url);

        return redirect()->route('message.index');
    }

    public function send(Message $message, Request $request)
    {
        //todo send message to recipients
    }
}
