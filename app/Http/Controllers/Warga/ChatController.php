<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(string $rt, string $rw): View
    {
        $chat = $this->getChat($rt, $rw);

        return view('warga.chat', compact('chat', 'rt', 'rw'));
    }

    /**
     * Data JSON untuk polling (pesan terbaru + tandai sudah dibaca).
     */
    public function data(Request $request, string $rt, string $rw): JsonResponse
    {
        $chat = $this->getChat($rt, $rw);
        $chat->tandaiDibacaWarga();

        return response()->json([
            'pesans' => $this->formatPesans($chat),
        ]);
    }

    public function kirim(Request $request, string $rt, string $rw): JsonResponse
    {
        $request->validate([
            'isi' => ['required', 'string', 'max:2000'],
        ]);

        $chat = $this->getChat($rt, $rw);

        $chat->pesans()->create([
            'sender_id' => auth('warga')->id(),
            'sender_role' => 'warga',
            'isi' => $request->isi,
            'dibaca_admin' => false,
            'dibaca_warga' => true,
        ]);
        $chat->update(['last_message_at' => now()]);

        Notification::kirimKeStaff([
            'judul' => 'Pesan baru dari '.auth('warga')->user()->name,
            'pesan' => Str::limit($request->isi, 100),
            'tipe' => 'sistem',
            'icon' => 'forum',
            'warna' => 'bg-primary/10 text-primary',
            'link' => route('admin.chat.show', $chat),
        ]);

        return response()->json(['success' => true]);
    }

    private function getChat(string $rt, string $rw): Chat
    {
        return Chat::untukWarga(auth('warga')->id(), $rt, $rw);
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
