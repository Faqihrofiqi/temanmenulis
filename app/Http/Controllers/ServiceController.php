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
            ->orderByDesc('is_featured')
            ->orderBy('price')
            ->paginate(9)
            ->withQueryString();

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
