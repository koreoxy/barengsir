<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public $menus;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->menus = [
            [
                'name' => 'Dashboard',
                'route' => 'dashboard',
                'url' => route('dashboard'),
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
            ],
            [
                'name' => 'Transaksi',
                'route' => 'transaction.index',
                'url' => route('transaction.index'),
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />',
            ],
            [
                'name' => 'Produk',
                'role' => 'admin',
                'route' => 'product.*', // Wildcard to make it active for all sub-routes
                'url' => '#',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />',
                'submenus' => [
                    ['name' => 'Semua Produk', 'url' => route('product.index'), 'route' => 'product.index'],
                    ['name' => 'Tambah Produk', 'url' => route('product.create'), 'route' => 'product.create'],
                    ['name' => 'Kategori', 'url' => route('category.index'), 'route' => 'category.index'],
                    ['name' => 'Brand', 'url' => route('brand.index'), 'route' => 'brand.index'],
                    ['name' => 'Stok Barang', 'url' => route('product.stock'), 'route' => 'product.stock'],
                    ['name' => 'Stock Opname', 'url' => route('product.stock_opname'), 'route' => 'product.stock_opname'],
                    ['name' => 'Barcode Generator', 'url' => route('product.barcode'), 'route' => 'product.barcode'],
                ]
            ],
            [
                'name' => 'Pelanggan',
                'route' => 'customer.index', // placeholder
                'url' => '#',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />',
            ],
            [
                'name' => 'Laporan',
                'route' => 'report.index',
                'url' => route('report.index'),
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />',
            ],
            [
                'name' => 'Pengaturan',
                'route' => 'setting.index', // placeholder
                'url' => route('setting.index'),
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
            ],
        ];

        // Filter menus based on user role
        $this->menus = array_filter($this->menus, function($menu) {
            if (isset($menu['role']) && $menu['role'] === 'admin') {
                return auth()->check() && auth()->user()->role === 'admin';
            }
            return true;
        });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar');
    }
}
