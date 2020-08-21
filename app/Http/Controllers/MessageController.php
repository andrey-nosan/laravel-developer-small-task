<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        /** @var Message $message */
        $message = Message::create(request()->only((new Message)->getFillable()));

        if (request()->has('teachers')) {
            $teachers = Teacher::whereIn('id', request()->get('teachers'))->get();
            $message->teachers()->attach($teachers);
        }

        if (request()->has('students')) {
            $students = Student::whereIn('id', request()->get('students'))->get();
            $message->students()->attach($students);
        }

        $message->save();

        return redirect()->action('MessageController@index');
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
        $message->fill(request()->only($message->getFillable()));

        $message->teachers()->detach();
        $message->students()->detach();

        if (request()->has('teachers')) {
            $teachers = Teacher::whereIn('id', request()->get('teachers'))->get();
            $message->teachers()->sync($teachers, true);
        }

        if (request()->has('students')) {
            $students = Student::whereIn('id', request()->get('students'))->get();
            $message->students()->sync($students, true);
        }

        $message->save();

        return redirect()->action('MessageController@index');
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

        return redirect()->route('message.index');
    }

    public function send(Message $message, Request $request)
    {
        //todo send message to recipients
    }
}
