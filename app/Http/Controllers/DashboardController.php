<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\Ticket;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $orderQuery = $user->isAdmin() ? Order::query() : $user->orders();
        $ticketQuery = $user->isAdmin() ? Ticket::query() : $user->tickets();

        $stats = [
            'totalOrders' => (clone $orderQuery)->count(),
            'activeOrders' => (clone $orderQuery)->whereIn('status', ['pending', 'processing'])->count(),
            'totalRevenue' => (clone $orderQuery)->whereIn('status', ['paid', 'processing', 'completed'])->sum('amount'),
            'openTickets' => (clone $ticketQuery)->where('status', '!=', 'closed')->count(),
        ];

        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentOrders' => (clone $orderQuery)->with(['service', 'user', 'payments'])->latest()->take(5)->get(),
            'openTickets' => (clone $ticketQuery)->with(['order', 'user'])->latest()->take(5)->get(),
            'pendingReviewOrders' => $user->isAdmin()
                ? collect()
                : (clone $orderQuery)
                    ->with(['service'])
                    ->where('status', 'completed')
                    ->whereDoesntHave('review')
                    ->latest('updated_at')
                    ->take(3)
                    ->get(),
            'spotlightServices' => Service::query()
                ->where('is_active', true)
                ->orderByPromo()
                ->orderByDesc('is_featured')
                ->orderBy('price')
                ->take(3)
                ->get(),
            'adminOrders' => $user->isAdmin()
                ? Order::with(['service', 'user'])->latest()->take(6)->get()
                : collect(),
            'adminTickets' => $user->isAdmin()
                ? Ticket::with(['user', 'order'])->latest('updated_at')->take(6)->get()
                : collect(),
            'serviceSnapshots' => $user->isAdmin()
                ? [
                    'active' => Service::where('is_active', true)->count(),
                    'promo' => Service::query()
                        ->where('is_active', true)
                        ->whereNotNull('discount_percentage')
                        ->where('discount_percentage', '>', 0)
                        ->where(fn ($query) => $query->whereNull('discount_ends_at')->orWhere('discount_ends_at', '>', now()))
                        ->count(),
                    'inactive' => Service::where('is_active', false)->count(),
                ]
                : null,
        ]);
    }
}
