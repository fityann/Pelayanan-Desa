<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        $chats = Chat::with(['user', 'pesans'])
            ->latest('last_message_at')
            ->get();

        return view('admin.chat.index', compact('chats'));
    }

    public function show(Chat $chat): View
    {
        $chat->tandaiDibacaAdmin();
        $chat->load(['user', 'pesans.sender']);

        return view('admin.chat.show', compact('chat'));
    }

    /**
     * Data JSON untuk polling (pesan terbaru + tandai sudah dibaca).
     */
    public function data(Request $request, Chat $chat): JsonResponse
    {
        $chat->tandaiDibacaAdmin();

        return response()->json([
            'pesans' => $this->formatPesans($chat),
        ]);
    }

    public function kirim(Request $request, Chat $chat): JsonResponse
    {
        $request->validate([
            'isi' => ['required', 'string', 'max:2000'],
        ]);

        $chat->pesans()->create([
            'sender_id' => auth()->id(),
            'sender_role' => 'admin',
            'isi' => $request->isi,
            'dibaca_admin' => true,
            'dibaca_warga' => false,
        ]);
        $chat->update(['last_message_at' => now()]);

        Notification::buat($chat->user_id, [
            'judul' => 'Balasan admin desa',
            'pesan' => Str::limit($request->isi, 100),
            'tipe' => 'sistem',
            'icon' => 'forum',
            'warna' => 'bg-primary/10 text-primary',
            'link' => route('warga.rt.chat', ['rt' => $chat->rt, 'rw' => $chat->rw]),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Jumlah percakapan belum dibaca (untuk badge sidebar admin).
     */
    public function unread(): JsonResponse
    {
        return response()->json(['count' => Chat::unreadAdminCount()]);
    }

    private function formatPesans(Chat $chat): array
    {
        return $chat->pesans()->reorder('created_at')->get()->map(fn ($pesan) => [
            'id' => $pesan->id,
            'isi' => $pesan->isi,
            'sender_role' => $pesan->sender_role,
            'waktu' => $pesan->created_at->diffForHumans(),
        ])->values()->all();
    }
}
