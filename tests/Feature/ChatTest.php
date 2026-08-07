<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\ChatPesan;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    private function seedRoles(): void
    {
        $this->seed(RolePermissionSeeder::class);
    }

    private function admin(): User
    {
        $this->seedRoles();
        $admin = User::firstOrCreate(
            ['email' => 'admin-chat@test.local'],
            [
                'name' => 'Admin Chat',
                'nik' => '3201010101010601',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        if (! $admin->hasRole('Admin Desa')) {
            $admin->assignRole('Admin Desa');
        }

        return $admin;
    }

    private function warga(): User
    {
        $this->seedRoles();
        $warga = User::firstOrCreate(
            ['email' => 'warga-chat@test.local'],
            [
                'name' => 'Warga Chat',
                'nik' => '3201010101010602',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'rt' => '01',
                'rw' => '01',
            ]
        );
        if (! $warga->hasRole('Warga')) {
            $warga->assignRole('Warga');
        }

        return $warga;
    }

    public function test_warga_bisa_membuka_chat_dan_mengirim_pesan(): void
    {
        $warga = $this->warga();
        $admin = $this->admin();

        $this->actingAs($warga)
            ->get(route('warga.rt.chat', ['rt' => '01', 'rw' => '01']))
            ->assertOk()
            ->assertSee('Chat dengan Admin Desa');

        $this->actingAs($warga)
            ->post(route('warga.rt.chat.store', ['rt' => '01', 'rw' => '01']), [
                'isi' => 'Assalamualaikum, saya mau tanya soal surat keterangan',
            ])->assertOk();

        $this->assertDatabaseHas('chats', ['user_id' => $warga->id]);
        $this->assertDatabaseHas('chat_pesans', [
            'sender_role' => 'warga',
            'isi' => 'Assalamualaikum, saya mau tanya soal surat keterangan',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $admin->id,
            'judul' => 'Pesan baru dari Warga Chat',
            'icon' => 'forum',
        ]);
    }

    public function test_admin_bisa_melihat_dan_membalas_chat(): void
    {
        $this->admin();
        $warga = $this->warga();

        $this->actingAs($warga)
            ->post(route('warga.rt.chat.store', ['rt' => '01', 'rw' => '01']), ['isi' => 'Halo admin']);

        $chat = Chat::first();

        // Kotak masuk admin
        $this->actingAs($this->admin())
            ->get(route('admin.chat.index'))
            ->assertOk()
            ->assertSee('Warga Chat');

        // Buka percakapan -> pesan warga ditandai sudah dibaca admin
        $this->actingAs($this->admin())
            ->get(route('admin.chat.show', $chat))
            ->assertOk()
            ->assertSee('Warga Chat');

        $this->assertTrue(ChatPesan::first()->dibaca_admin);

        // Pesan warga tampil di endpoint polling admin
        $this->actingAs($this->admin())
            ->getJson(route('admin.chat.data', $chat))
            ->assertOk()
            ->assertJsonPath('pesans.0.isi', 'Halo admin')
            ->assertJsonPath('pesans.0.sender_role', 'warga');

        // Admin membalas
        $this->actingAs($this->admin())
            ->post(route('admin.chat.store', $chat), ['isi' => 'Waalaikumsalam, silakan datang ke balai desa'])
            ->assertOk();

        // Warga menerima balasan via polling (tandai sudah dibaca warga)
        $this->actingAs($warga)
            ->getJson(route('warga.rt.chat.data', ['rt' => '01', 'rw' => '01']))
            ->assertOk()
            ->assertJsonPath('pesans.1.isi', 'Waalaikumsalam, silakan datang ke balai desa')
            ->assertJsonPath('pesans.1.sender_role', 'admin');
    }

    public function test_unread_admin_count_dan_badge(): void
    {
        $admin = $this->admin();
        $warga = $this->warga();

        $this->actingAs($warga)
            ->post(route('warga.rt.chat.store', ['rt' => '01', 'rw' => '01']), ['isi' => 'Pesan belum dibaca admin']);

        $this->assertEquals(1, Chat::unreadAdminCount());

        $chat = Chat::first();
        $this->actingAs($admin)
            ->get(route('admin.chat.data', $chat))
            ->assertOk();

        $this->assertEquals(0, Chat::unreadAdminCount());
    }

    public function test_warga_tidak_bisa_akses_area_chat_admin(): void
    {
        $warga = $this->warga();

        $this->actingAs($warga)
            ->get(route('admin.chat.index'))
            ->assertForbidden();
    }

    public function test_balasan_admin_tampil_di_lonceng_notifikasi_warga(): void
    {
        $this->admin();
        $warga = $this->warga();

        $this->actingAs($warga)
            ->post(route('warga.rt.chat.store', ['rt' => '01', 'rw' => '01']), ['isi' => 'Halo admin']);

        $chat = Chat::first();

        $this->actingAs($this->admin())
            ->post(route('admin.chat.store', $chat), ['isi' => 'Silakan datang ke balai desa'])
            ->assertOk();

        // Warga melihat notifikasi di lonceng
        $this->actingAs($warga)
            ->getJson(route('warga.rt.notif.data', ['rt' => '01', 'rw' => '01']))
            ->assertOk()
            ->assertJsonPath('unread', 1)
            ->assertJsonPath('items.0.judul', 'Balasan admin desa')
            ->assertJsonPath('items.0.link', route('warga.rt.chat', ['rt' => '01', 'rw' => '01']));

        // Tandai sudah dibaca
        $notifId = \App\Models\Notification::where('user_id', $warga->id)->first()->id;

        $this->actingAs($warga)
            ->post(route('warga.rt.notif.read', ['rt' => '01', 'rw' => '01', 'id' => $notifId]))
            ->assertOk();

        $this->actingAs($warga)
            ->getJson(route('warga.rt.notif.data', ['rt' => '01', 'rw' => '01']))
            ->assertJsonPath('unread', 0);
    }

    public function test_guest_tidak_bisa_akses_chat_warga(): void
    {
        $this->get(route('warga.rt.chat', ['rt' => '01', 'rw' => '01']))
            ->assertRedirect(route('warga.rt.login', ['rt' => '01', 'rw' => '01']));
    }
}
