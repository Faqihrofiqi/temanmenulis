<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min($request->integer('per_page', 12), 50);

        $services = Service::query()
            ->where('is_active', true)
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->query('category')))
            ->when($request->boolean('featured'), fn ($query) => $query->where('is_featured', true))
            ->orderBy('name')
            ->withCount('orders')
            ->paginate($perPage);

        return response()->json($services);
    }

    public function show(Service $service): JsonResponse
    {
        abort_if(! $service->is_active, 404);

        return response()->json([
            'data' => $service->loadCount('orders')
        ]);
    }
}
