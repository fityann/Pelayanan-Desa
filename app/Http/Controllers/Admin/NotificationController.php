<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = Notification::untuk(auth()->id())
            ->latest()
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Data JSON untuk dropdown navbar (10 notifikasi terbaru + jumlah belum dibaca).
     */
    public function data(): JsonResponse
    {
        $belumDibaca = Notification::untuk(auth()->id())
            ->belumDibaca()
            ->count();

        $items = Notification::untuk(auth()->id())
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
        $notification = Notification::untuk(auth()->id())->findOrFail($id);
        $notification->tandaiDibaca();

        return response()->json(['success' => true]);
    }

    public function markAll(): JsonResponse
    {
        Notification::untuk(auth()->id())
            ->belumDibaca()
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function destroy(Notification $notification): RedirectResponse
    {
        $this->authorizeOwner($notification);
        $notification->delete();

        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi dihapus');
    }

    private function authorizeOwner(Notification $notification): void
    {
        abort_if($notification->user_id !== auth()->id(), 404);
    }
}
