<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Repositories\NoteRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    protected $notes;

    public function __construct(NoteRepository $notes)
    {
        $this->notes = $notes;
    }

    public function index()
    {
        $data = $this->notes->allByUser(auth()->id());
        return response()->json(
            NoteResource::collection($data)
        );
    }

    public function store(StoreNoteRequest $request)
    {
        try {
            $payload = $request->validated();
            return DB::transaction(function () use ($payload) {
                $user = Auth::user();

                $note = $this->notes->create(
                    $payload['title'],
                    $payload['content'] ?? null,
                    !empty($payload['remind_at']) ? Carbon::make($payload['remind_at']) : null,
                    $user->id
                );

                return response()->json(
                    NoteResource::make($note)
                );
            });
        } catch (Exception $e) {
            report($e);

            return response()->json([
                'error'   => 'Store Data failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $note = $this->notes->findById($id, auth()->id());
        return $note ? response()->json(NoteResource::make($note)) : response()->json(['error' => 'Not Found'], 404);
    }

    public function update(UpdateNoteRequest $request, string $id)
    {
        $note = $this->notes->findById($id, auth()->id());
        if (!$note) {
            return response()->json(['error' => 'Not Found'], 404);
        }

        try {
            $payload = $request->validated();
            return DB::transaction(function () use ($note, $payload) {
                $note = $this->notes->update(
                    $note,
                    $payload
                );

                return response()->json(
                    NoteResource::make($note)
                );
            });
        } catch (Exception $e) {
            report($e);

            return response()->json([
                'error'   => 'Update Data failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $note = $this->notes->findById($id, auth()->id());
        if (!$note) {
            return response()->json(['error' => 'Not Found'], 404);
        }

        try {
            $this->notes->delete($note);

            return response()->json([
                'error'   => '',
                'message' => 'Data Successfully deleted'
            ]);
        } catch (Exception $e) {
            report($e);

            return response()->json([
                'error'   => 'Delete Data failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
