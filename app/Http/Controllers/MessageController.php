<?php

namespace App\Http\Controllers;

use App\Mail\MessageMail;
use App\Models\Message;
use App\Models\Student;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
     * @return RedirectResponse
     */
    public function store(): RedirectResponse
    {
        $file = config('app.message_dir') . DIRECTORY_SEPARATOR . Str::uuid();

        DB::beginTransaction();
        try {
            Storage::put($file, request()->get('body'));
            request()->merge(['body' => $file]);

            /** @var Message $message */
            $message = Message::create(request()->only((new Message)->getFillable()));

            $message->teachers()->attach(request()->get('teachers'));
            $message->students()->attach(request()->get('students'));

            $message->save();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();
            Storage::delete($file);

            return redirect()->route('message.index');
        }

        return redirect()->route('message.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Message $message
     * @return View
     */
    public function edit(Message $message): View
    {
        $response = [
            'message' => Message::find($message)->load(['teachers', 'students'])->first(),
            'teachers' => Teacher::all(),
            'students' => Student::all(),
        ];

        return view('pages.message.edit', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Message $message
     * @return RedirectResponse
     */
    public function update(Message $message): RedirectResponse
    {
        DB::beginTransaction();
        try {
            Storage::put($message->body_url, request()->get('body'));

            $message->fill(request()->except('body'));

            $message->teachers()->sync(request()->get('teachers'), true);
            $message->students()->sync(request()->get('students'), true);

            $message->save();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();

            return redirect()->route('message.index');
        }

        return redirect()->route('message.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Message $message
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Message $message): RedirectResponse
    {
        $message->teachers()->detach();
        $message->students()->detach();
        $message->delete();

        Storage::delete($message->body_url);

        return redirect()->route('message.index');
    }

    /**
     * @param Message $message
     * @return RedirectResponse
     */
    public function send(Message $message): RedirectResponse
    {
        /** @var Teacher $teacher */
        foreach ($message->teachers as $teacher) {
            Mail::to($teacher->email)
                ->queue(new MessageMail(
                    [
                        'subject' => $message->subject,
                        'body_content' => $message->body_content,
                        'name' => $teacher->fullname,
                    ]
                ));
        }

        /** @var Student $student */
        foreach ($message->students as $student) {
            Mail::to($student->email)->queue(new MessageMail(
                [
                    'subject' => $message->subject,
                    'body_content' => $message->body_content,
                    'name' => $student->fullname,
                ]
            ));
        }

        $message->update(['sent' => true]);

        return redirect()->route('message.index');
    }
}
