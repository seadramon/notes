<?php
namespace App\Repositories;

use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class NoteRepository
{
    public function allByUser(string $userId): ?Collection
    {
        return Note::where('user_id', $userId)->get();
    }

    public function findById(string $id, string $userId): ?Note
    {
        return Note::where('id', $id)->where('user_id', $userId)->first();
    }

    public function create(string $title, ?string $content, ?Carbon $remindAt, string $creator): Note
    {
        return Note::create([
            'title' => $title,
            'content' => $content,
            'remind_at' => $remindAt,
            'user_id' => $creator
        ]);
    }

    public function update(Note $note, array $data): Note
    {
        $note->update($data);

        return $note->refresh();
    }

    public function delete(Note $note): bool
    {
        return $note->delete();
    }
}