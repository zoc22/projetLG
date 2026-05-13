<?php

namespace Modules\Support\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Support\Services\SupportService;
use Modules\Support\Models\Ticket;
use Modules\Support\Http\Resources\TicketResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Gestion du support via API.
 */
class SupportController extends Controller
{
    public function __construct(protected SupportService $service) {}

    /**
     * Liste mes tickets.
     */
    public function index(): JsonResponse
    {
        $tickets = Ticket::where('user_id', Auth::id())
            ->with(['messages'])
            ->latest()
            ->get();
            
        return response()->json(TicketResource::collection($tickets));
    }

    /**
     * Ouvrir une nouvelle demande.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject'  => 'required|string|max:200',
            'message'  => 'required|string|min:10',
            'priority' => 'sometimes|in:low,medium,high'
        ]);

        $ticket = $this->service->openTicket(Auth::id(), $validated);

        return response()->json(new TicketResource($ticket), 201);
    }

    /**
     * Répondre à un ticket.
     */
    public function reply(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|min:2'
        ]);

        $message = $this->service->addMessage($id, Auth::id(), $validated['content']);

        return response()->json($message, 201);
    }

    /**
     * Clôturer la demande.
     */
    public function close(string $id): JsonResponse
    {
        $this->service->closeTicket($id);
        return response()->json(['message' => 'Ticket closed successfully.']);
    }
}
