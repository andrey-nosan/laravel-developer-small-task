<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageStoreRequest;
use App\Mail\MessageMail;
use Exception;
use App\Models\{Message, Student, Teacher};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{DB, Mail, Storage};
use Illuminate\Support\Str;

class MessageController extends Controller
{
    /**
     * Returns a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        return [
            'messages' => Message::orderBy('updated_at', 'desc')->paginate(config('app.pagination.size')),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        $response = [
            'teachers' => Teacher::all(),
            'students' => Student::all(),
        ];

        return response()->json($response);
    }

    /**
     * @param Message $message
     * @return JsonResponse
     */
    public function edit(Message $message): JsonResponse
    {
        $response = [
            'message' => $message->load(['teachers', 'students']),
            'teachers' => Teacher::all(),
            'students' => Student::all(),
        ];

        return response()->json($response);
    }

    /**
     * @param MessageStoreRequest $request
     * @return JsonResponse
     */
    public function store(MessageStoreRequest $request): JsonResponse
    {
        $file = config('app.message_dir') . DIRECTORY_SEPARATOR . Str::uuid();
        Storage::put($file, $request->get('body'));

        $request->merge(['body' => $file]);

        DB::beginTransaction();
        try {

            /** @var Message $message */
            $message = Message::create($request->only((new Message)->getFillable()));

            $message->teachers()->attach($request->get('teachers'));
            $message->students()->attach($request->get('students'));

            $message->save();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();
            Storage::delete($file);
        } finally {
            return response()->json($message->load(['teachers', 'students']));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MessageStoreRequest $request
     * @param Message $message
     * @return JsonResponse
     */
    public function update(MessageStoreRequest $request, Message $message): JsonResponse
    {
        DB::beginTransaction();
        try {
            Storage::put($message->body_url, $request->get('body'));

            $message->fill($request->except('body'));
            $message->updated_at = now();

            $message->teachers()->sync($request->get('teachers'), true);
            $message->students()->sync($request->get('students'), true);

            $message->save();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();
        } finally {
            return response()->json($message->load(['teachers', 'students']));
        }
    }

    /**
     * @param Message $message
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Message $message): JsonResponse
    {
        $message->teachers()->detach();
        $message->students()->detach();
        $message->delete();

        Storage::delete($message->body_url);

        return response()->json([
            'status' => 'OK',
            'message' => 'Deleted',
        ]);
    }

    /**
     * @param Message $message
     * @return JsonResponse
     */
    public function send(Message $message): JsonResponse
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

        return response()->json($message->load(['teachers', 'students']));
    }
}
