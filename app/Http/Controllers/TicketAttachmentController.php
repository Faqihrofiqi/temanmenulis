<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TicketAttachmentController extends Controller
{
    public function __invoke(Request $request, Ticket $ticket, string $attachment): StreamedResponse
    {
        $user = $request->user();

        if (! $user->isAdmin() && $ticket->user_id !== $user->id) {
            abort(403);
        }

        try {
            $path = Crypt::decryptString($attachment);
        } catch (DecryptException $exception) {
            abort(404);
        }

        $ticket->loadMissing('messages');

        $hasAttachment = $ticket->messages
            ->flatMap(fn ($message) => collect($message->attachments ?? []))
            ->contains($path);

        if (! $hasAttachment || ! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->download($path, basename($path));
    }
}
