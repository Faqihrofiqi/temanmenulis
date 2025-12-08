<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketMessageRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $statusFilter = $request->query('status');

        $ticketsQuery = $user->tickets()->with('order')->latest();

        if ($statusFilter && in_array($statusFilter, ['open', 'in_progress', 'resolved', 'closed'], true)) {
            $ticketsQuery->where('status', $statusFilter);
        }

        $tickets = $ticketsQuery->paginate(10)->withQueryString();

        $statusCounts = $user->tickets()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $stats = [
            'total' => $statusCounts->sum() ?: 0,
            'open' => $statusCounts->get('open', 0),
            'in_progress' => $statusCounts->get('in_progress', 0),
            'resolved' => $statusCounts->get('resolved', 0),
            'closed' => $statusCounts->get('closed', 0),
        ];

        return view('tickets.index', [
            'tickets' => $tickets,
            'stats' => $stats,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $order = null;

        if ($request->filled('order_number')) {
            $order = Order::where('order_number', $request->input('order_number'))
                ->where('user_id', $request->user()->id)
                ->first();
        }

        $ticket = Ticket::create([
            'user_id' => $request->user()->id,
            'order_id' => optional($order)->id,
            'subject' => $request->input('subject'),
            'priority' => $request->input('priority', 'normal'),
            'status' => 'open',
            'channel' => 'support',
        ]);

        $ticket->messages()->create([
            'user_id' => $request->user()->id,
            'message' => $request->input('message'),
            'attachments' => $this->storeAttachments($request),
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Tiket berhasil dibuat.');
    }

    public function show(Ticket $ticket): View
    {
        $this->ensureOwnership($ticket);

        return view('tickets.show', [
            'ticket' => $ticket->load(['messages.user', 'order']),
        ]);
    }

    public function reply(Ticket $ticket, StoreTicketMessageRequest $request): RedirectResponse
    {
        $this->ensureOwnership($ticket);

        $ticket->messages()->create([
            'user_id' => $request->user()->id,
            'message' => $request->input('message'),
            'attachments' => $this->storeAttachments($request),
        ]);

        $ticket->update(['last_replied_at' => now()]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Pesan berhasil dikirim.');
    }

    private function ensureOwnership(Ticket $ticket): void
    {
        abort_if($ticket->user_id !== auth()->id(), 403);
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
