<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Http\Resources\ResponseResource;
use App\Models\Note;
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

    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $search = $request?->search ?? null;
        $user = Auth::user();

        $data = $this->notes->allByUser($user->id, $perPage, $search);

        return new ResponseResource(true, 'List Notes', $data);
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

                return new ResponseResource(true, 
                    'Store Data Success', 
                    NoteResource::make($note));
            });
        } catch (Exception $e) {
            report($e);

            return (new ResponseResource(false, 'Store Data Failed', $e->getMessage()))
                ->response()
                ->setStatusCode(500);
        }
    }

    public function show(string $id)
    {
        $note = $this->notes->findById($id, auth()->id());
        return $note ? 
            (new ResponseResource(true, 'Note Data', NoteResource::make($note)))
            : (new ResponseResource(false, 'Data Not Found', ''))
                ->response()
                ->setStatusCode(404);
    }

    public function update(UpdateNoteRequest $request, string $id)
    {
        $note = $this->notes->findById($id, auth()->id());
        if (!$note) {
            return (new ResponseResource(false, 'Data Not Found', ''))
                ->response()
                ->setStatusCode(404);
        }

        try {
            $payload = $request->validated();
            return DB::transaction(function () use ($note, $payload) {
                $note = $this->notes->update(
                    $note,
                    $payload
                );

                return (new ResponseResource(true, 'Update Data Success', NoteResource::make($note)));
            });
        } catch (Exception $e) {
            report($e);

            return (new ResponseResource(false, 'Update Data Failed', $e->getMessage()))
                ->response()
                ->setStatusCode(500);
        }
    }

    public function destroy(string $id)
    {
        $note = $this->notes->findById($id, auth()->id());
        if (!$note) {
            return (new ResponseResource(false, 'Data Not Found', ''))
                ->response()
                ->setStatusCode(404);
        }

        try {
            $this->notes->delete($note);

            return (new ResponseResource(true, 'Data Successfully Deleted', ''))
                ->response()
                ->setStatusCode(200);
        } catch (Exception $e) {
            report($e);

            return (new ResponseResource(false, 'Delete Data Failed', ''))
                ->response()
                ->setStatusCode(500);
        }
    }
}
