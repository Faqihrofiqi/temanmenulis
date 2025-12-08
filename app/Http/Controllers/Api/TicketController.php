<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketMessageRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Ticket::query()->with('order');

        if (! $request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        }

        $tickets = $query
            ->when($request->filled('status'), fn ($builder) => $builder->where('status', $request->query('status')))
            ->latest()
            ->paginate(min($request->integer('per_page', 10), 50));

        return response()->json($tickets);
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        $order = null;

        if ($request->filled('order_number')) {
            $order = Order::query()
                ->where('order_number', $request->input('order_number'))
                ->when(! $request->user()->isAdmin(), fn ($builder) => $builder->where('user_id', $request->user()->id))
                ->first();
        }

        $ticket = Ticket::create([
            'user_id' => $request->user()->id,
            'order_id' => optional($order)->id,
            'subject' => $request->input('subject'),
            'priority' => $request->input('priority', 'normal'),
            'status' => 'open',
            'channel' => 'api',
        ]);

        $ticket->messages()->create([
            'user_id' => $request->user()->id,
            'message' => $request->input('message'),
            'attachments' => $this->storeAttachments($request),
        ]);

        return response()->json([
            'data' => $ticket->load(['order', 'messages.user']),
        ], 201);
    }

    public function storeMessage(StoreTicketMessageRequest $request, Ticket $ticket): JsonResponse
    {
        $this->ensureCanAccess($ticket, $request->user());

        $message = $ticket->messages()->create([
            'user_id' => $request->user()->id,
            'message' => $request->input('message'),
            'is_internal' => $request->boolean('is_internal', false),
            'attachments' => $this->storeAttachments($request),
        ]);

        $ticket->update(['last_replied_at' => now()]);

        return response()->json([
            'data' => $message->load('user'),
        ], 201);
    }

    private function ensureCanAccess(Ticket $ticket, User $user): void
    {
        if ($user->isAdmin()) {
            return;
        }

        abort_if($ticket->user_id !== $user->id, 403);
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
