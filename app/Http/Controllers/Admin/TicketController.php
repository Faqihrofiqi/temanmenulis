<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketMessageRequest;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $priority = $request->query('priority');

        $tickets = Ticket::query()
            ->with(['user', 'order', 'messages' => fn ($query) => $query->latest()->limit(1)])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($priority, fn ($query) => $query->where('priority', $priority))
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.tickets.index', [
            'tickets' => $tickets,
            'selectedStatus' => $status,
            'selectedPriority' => $priority,
        ]);
    }

    public function show(Ticket $ticket): View
    {
        return view('admin.tickets.show', [
            'ticket' => $ticket->load([
                'messages' => fn ($query) => $query->orderBy('created_at'),
                'messages.user',
                'order',
                'user',
            ]),
            'statusOptions' => ['open', 'in_progress', 'resolved', 'closed'],
        ]);
    }

    public function reply(StoreTicketMessageRequest $request, Ticket $ticket): RedirectResponse
    {
        $ticket->messages()->create([
            'user_id' => $request->user()->id,
            'message' => $request->input('message'),
            'is_internal' => $request->boolean('is_internal', false),
            'attachments' => $this->storeAttachments($request),
        ]);

        $ticket->update([
            'status' => $request->input('status', $ticket->status),
            'last_replied_at' => now(),
        ]);

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function close(Ticket $ticket): RedirectResponse
    {
        $ticket->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return back()->with('success', 'Tiket ditandai selesai.');
    }

    private function storeAttachments(Request $request): array
    {
        if (! $request->hasFile('attachments')) {
            return [];
        }

        $paths = [];

        foreach ($request->file('attachments') as $file) {
            if ($file) {
                $paths[] = Storage::disk('public')->putFile('tickets', $file);
            }
        }

        return $paths;
    }
}
