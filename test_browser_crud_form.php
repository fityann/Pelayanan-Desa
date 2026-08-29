<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$consoleKernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$consoleKernel->bootstrap();

use App\Models\User;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Spatie\Permission\Models\Role;

$adminUser = User::where('email', 'admin@puspamukti.local')->first();
Auth::guard('web')->login($adminUser);

$controller = new UserController();
$testRole = Role::where('name', 'Admin Desa')->first() ?? Role::first();

echo "========================================================\n";
echo " 🌐 LIVE BROWSER FORM CRUD CONTROLLER SUITE             \n";
echo "========================================================\n\n";

// 1. CREATE USER VIA FORM ACTION
echo "1. 📝 SUBMITTING 'CREATE USER' FORM...\n";
$emailTest = 'browser_form_user_' . rand(1000, 9999) . '@puspamukti.local';
$nikTest = '99' . str_pad(rand(100000000000, 999999999999), 14, '0', STR_PAD_LEFT);

$postReq = Request::create('/admin/users', 'POST', [
    'name' => 'Browser Form User Test',
    'email' => $emailTest,
    'nik' => $nikTest,
    'password' => 'Password123',
    'password_confirmation' => 'Password123',
    'role' => $testRole->id,
]);

try {
    $res = $controller->store($postReq);
    $createdUser = User::where('email', $emailTest)->first();

    if ($createdUser) {
        echo "   ✅ PASS: Form Create User Submitted Successfully! (ID #{$createdUser->id}, Name: '{$createdUser->name}')\n";
    } else {
        echo "   ❌ FAIL: Form Create User Failed\n";
    }
} catch (\Throwable $e) {
    echo "   ❌ CRASH in Form Create User: " . $e->getMessage() . "\n";
}

// 2. EDIT / UPDATE USER VIA FORM ACTION
if (isset($createdUser) && $createdUser) {
    echo "\n2. ✏️ SUBMITTING 'UPDATE USER' FORM FOR ID #{$createdUser->id}...\n";
    $putReq = Request::create("/admin/users/{$createdUser->id}", 'PUT', [
        'name' => 'Browser Form User Test (UPDATED)',
        'email' => $emailTest,
        'nik' => $nikTest,
        'role' => $testRole->id,
    ]);

    try {
        $res = $controller->update($putReq, $createdUser);
        $createdUser->refresh();

        if ($createdUser->name === 'Browser Form User Test (UPDATED)') {
            echo "   ✅ PASS: Form Update User Submitted Successfully! (Updated Name: '{$createdUser->name}')\n";
        } else {
            echo "   ❌ FAIL: Form Update User Failed\n";
        }
    } catch (\Throwable $e) {
        echo "   ❌ CRASH in Form Update User: " . $e->getMessage() . "\n";
    }

    // 3. DELETE USER VIA FORM ACTION
    echo "\n3. 🗑️ SUBMITTING 'DELETE USER' ACTION FOR ID #{$createdUser->id}...\n";
    try {
        $res = $controller->destroy($createdUser);
        $exists = User::find($createdUser->id);

        if (!$exists) {
            echo "   ✅ PASS: Browser Delete User Action Completed Successfully!\n";
        } else {
            echo "   ❌ FAIL: Browser Delete User Action Failed\n";
        }
    } catch (\Throwable $e) {
        echo "   ❌ CRASH in Browser Delete User: " . $e->getMessage() . "\n";
    }
}

echo "\n========================================================\n";
echo " ✨ LIVE BROWSER FORM CRUD CONTROLLER COMPLETED!        \n";
echo "========================================================\n";
