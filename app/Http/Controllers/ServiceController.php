<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');

        $services = Service::query()
            ->when($category, fn ($query) => $query->where('category', $category))
            ->where('is_active', true)
            ->orderByPromo()
            ->orderByDesc('is_featured')
            ->orderBy('price')
            ->paginate(9)
            ->withQueryString();

        $promoHighlights = Service::query()
            ->where('is_active', true)
            ->whereNotNull('discount_percentage')
            ->where('discount_percentage', '>', 0)
            ->where(fn ($query) => $query->whereNull('discount_ends_at')->orWhere('discount_ends_at', '>', now()))
            ->orderByDesc('discount_percentage')
            ->take(4)
            ->get();

        $categories = Service::query()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return view('pages.services.index', [
            'services' => $services,
            'categories' => $categories,
            'category' => $category,
            'promoHighlights' => $promoHighlights,
            'title' => 'Katalog Layanan — Deadlineku',
        ]);
    }

    public function show(Service $service)
    {
        return view('pages.services.show', [
            'service' => $service,
            'title' => $service->name.' — Layanan Deadlineku',
        ]);
    }
}
