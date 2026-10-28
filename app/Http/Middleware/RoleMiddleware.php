<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        // 1. Jika user adalah Owner (Super Admin), izinkan akses ke semua modul
        if ($user->isOwner()) {
            return $next($request);
        }

        // 2. Jika user adalah Peran Kustom, periksa izin spesifik modul yang diminta
        if ($user->isCustom()) {
            $routeName = $request->route() ? ($request->route()->getName() ?? '') : '';

            $permissionChecks = [
                'kasir'     => ['names' => ['kasir.', 'shift.'], 'paths' => ['kasir*', 'shift*']],
                'pelanggan' => ['names' => ['pelanggan.'], 'paths' => ['pelanggan*']],
                'dapur'     => ['names' => ['dapur.'], 'paths' => ['dapur*']],
                'produk'    => ['names' => ['produk.', 'kategori.'], 'paths' => ['produk*', 'kategori*']],
                'resep'     => ['names' => ['resep.'], 'paths' => ['resep*']],
                'diskon'    => ['names' => ['diskon.'], 'paths' => ['diskon*']],
                'bahanbaku' => ['names' => ['bahanbaku.', 'restock.'], 'paths' => ['bahanbaku*', 'restock*']],
                'laporan'   => ['names' => ['laporan.'], 'paths' => ['laporan*']],
                'karyawan'  => ['names' => ['karyawan.'], 'paths' => ['karyawan*']],
            ];

            foreach ($permissionChecks as $permission => $rule) {
                $matches = false;
                foreach ($rule['names'] as $namePrefix) {
                    if (str_starts_with($routeName, $namePrefix)) {
                        $matches = true;
                        break;
                    }
                }

                if (!$matches) {
                    foreach ($rule['paths'] as $pathPattern) {
                        if ($request->is($pathPattern)) {
                            $matches = true;
                            break;
                        }
                    }
                }

                if ($matches) {
                    if ($user->hasPermission($permission)) {
                        return $next($request);
                    } else {
                        abort(403, 'Akses Ditolak. Akun peran kustom Anda tidak memiliki izin untuk modul ini.');
                    }
                }
            }
        }

        $userRole = $user->role;

        // Mapping alias peran bawaan (Kasir <-> Cashier, Dapur <-> Kitchen)
        $roleAliases = [
            'Kasir' => ['Kasir', 'Cashier'],
            'Cashier' => ['Kasir', 'Cashier'],
            'Dapur' => ['Dapur', 'Kitchen'],
            'Kitchen' => ['Dapur', 'Kitchen'],
            'Stock_Admin' => ['Stock_Admin', 'Admin_Stok', 'Admin Gudang', 'Admin_Gudang'],
            'Admin_Gudang' => ['Stock_Admin', 'Admin_Stok', 'Admin Gudang', 'Admin_Gudang'],
            'Admin_Stok' => ['Stock_Admin', 'Admin_Stok', 'Admin Gudang', 'Admin_Gudang'],
            'Kustom' => ['Kustom', 'Custom', 'kustom', 'custom'],
            'Custom' => ['Kustom', 'Custom', 'kustom', 'custom'],
        ];

        $allowed = false;
        foreach ($roles as $role) {
            $validVariants = $roleAliases[$role] ?? [$role];
            if (in_array($userRole, $validVariants)) {
                $allowed = true;
                break;
            }
        }

        if (!$allowed) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki hak khusus ke halaman ini.');
        }

        return $next($request);
    }
}
