<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Data JSON untuk lonceng notifikasi warga (10 terbaru + jumlah belum dibaca).
     */
    public function data(Request $request): JsonResponse
    {
        $belumDibaca = Notification::untuk(auth('warga')->id())->belumDibaca()->count();

        $items = Notification::untuk(auth('warga')->id())
            ->latest()
            ->take(10)
            ->get()
            ->map(fn (Notification $n) => [
                'id' => $n->id,
                'judul' => $n->judul,
                'pesan' => $n->pesan,
                'tipe' => $n->tipe,
                'icon' => $n->icon,
                'warna' => $n->warna,
                'link' => $n->link,
                'is_read' => $n->is_read,
                'waktu' => $n->created_at->diffForHumans(),
            ]);

        return response()->json([
            'unread' => $belumDibaca,
            'items' => $items,
        ]);
    }

    public function markRead(int $id): JsonResponse
    {
        Notification::untuk(auth('warga')->id())->findOrFail($id)->tandaiDibaca();

        return response()->json(['success' => true]);
    }

    public function markAll(): JsonResponse
    {
        Notification::untuk(auth('warga')->id())
            ->belumDibaca()
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
