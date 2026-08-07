<?php

namespace Tests\Feature;

use App\Models\Informasi;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InformasiCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::firstOrCreate(
            ['email' => 'admin-informasi@test.local'],
            [
                'name' => 'Admin Desa',
                'nik' => '3201010101010401',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$user->hasRole('Admin Desa')) {
            $user->assignRole('Admin Desa');
        }
        return $user;
    }

    private function dataBerita(array $overrides = []): array
    {
        return array_merge([
            'judul' => 'Berita Pembangunan Desa',
            'isi' => 'Isi berita pembangunan desa yang informatif.',
            'kategori' => 'berita',
            'lokasi' => 'Kantor Desa',
        ], $overrides);
    }

    public function test_admin_dapat_membuka_halaman_tambah_informasi(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.informasi.create'))
            ->assertOk()
            ->assertSee('Tambah Informasi');
    }

    public function test_admin_dapat_menyimpan_berita_baru(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.informasi.store'), $this->dataBerita())
            ->assertRedirect(route('admin.informasi.index'));

        $this->assertDatabaseHas('informasis', [
            'judul' => 'Berita Pembangunan Desa',
            'kategori' => 'berita',
            'published' => false,
        ]);
    }

    public function test_admin_dapat_menyimpan_berita_publish_dan_target_wilayah(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.informasi.store'), $this->dataBerita([
                'publish' => '1',
                'rt' => '01',
                'rw' => '01',
            ]))
            ->assertRedirect(route('admin.informasi.index'));

        $this->assertDatabaseHas('informasis', [
            'judul' => 'Berita Pembangunan Desa',
            'published' => true,
            'rt' => '01',
            'rw' => '01',
        ]);
    }

    public function test_validasi_gagal_saat_judul_kosong(): void
    {
        $this->actingAs($this->admin())
            ->from(route('admin.informasi.create'))
            ->post(route('admin.informasi.store'), $this->dataBerita(['judul' => '']))
            ->assertRedirect(route('admin.informasi.create'))
            ->assertSessionHasErrors('judul');

        $this->assertDatabaseCount('informasis', 0);
    }

    public function test_validasi_gagal_saat_isi_kosong(): void
    {
        $this->actingAs($this->admin())
            ->from(route('admin.informasi.create'))
            ->post(route('admin.informasi.store'), $this->dataBerita(['isi' => '']))
            ->assertRedirect(route('admin.informasi.create'))
            ->assertSessionHasErrors('isi');

        $this->assertDatabaseCount('informasis', 0);
    }

    public function test_admin_dapat_upload_gambar_berita(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())
            ->post(route('admin.informasi.store'), $this->dataBerita([
                'gambar' => UploadedFile::fake()->image('foto.jpg'),
            ]))
            ->assertRedirect(route('admin.informasi.index'));

        $informasi = Informasi::first();
        $this->assertNotNull($informasi->gambar);
        Storage::disk('public')->assertExists($informasi->gambar);
    }

    public function test_admin_dapat_mengedit_berita(): void
    {
        $informasi = Informasi::create($this->dataBerita() + ['user_id' => $this->admin()->id]);

        $this->actingAs($this->admin())
            ->patch(route('admin.informasi.update', $informasi), $this->dataBerita([
                'judul' => 'Judul Diperbarui',
                'publish' => '1',
            ]))
            ->assertRedirect(route('admin.informasi.index'));

        $this->assertDatabaseHas('informasis', [
            'id' => $informasi->id,
            'judul' => 'Judul Diperbarui',
            'published' => true,
        ]);
    }

    public function test_admin_dapat_menghapus_berita(): void
    {
        $informasi = Informasi::create($this->dataBerita() + ['user_id' => $this->admin()->id]);

        $this->actingAs($this->admin())
            ->delete(route('admin.informasi.destroy', $informasi))
            ->assertRedirect(route('admin.informasi.index'));

        $this->assertDatabaseMissing('informasis', ['id' => $informasi->id]);
    }
}
