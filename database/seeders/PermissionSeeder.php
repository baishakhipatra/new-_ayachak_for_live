<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
   
    public function run(): void
    {
        $permissions = [
            ['parent_name' => 'dashboard', 'name' => 'view_dashboard', 'route' => 'admin.home'],

            ['parent_name' => 'admin-user-management', 'name' => 'list_admin_users', 'route' => 'admin.admin-user-management.index'],
            ['parent_name' => 'admin-user-management', 'name' => 'create_admin_user', 'route' => 'admin.admin-user-management.create'],
            ['parent_name' => 'admin-user-management', 'name' => 'store_admin_user', 'route' => 'admin.admin-user-management.store'],
            ['parent_name' => 'admin-user-management', 'name' => 'view_admin_user', 'route' => 'admin.admin-user-management.show'],
            ['parent_name' => 'admin-user-management', 'name' => 'edit_admin_user', 'route' => 'admin.admin-user-management.edit'],
            ['parent_name' => 'admin-user-management', 'name' => 'update_admin_user', 'route' => 'admin.admin-user-management.update'],
            ['parent_name' => 'admin-user-management', 'name' => 'change_admin_user_status', 'route' => 'admin.admin-user-management.status'],
            ['parent_name' => 'admin-user-management', 'name' => 'delete_admin_user', 'route' => 'admin.admin-user-management.delete'],
            ['parent_name' => 'admin-user-management', 'name' => 'export_admin_user', 'route' => 'admin.admin-user-management.export'],

            ['parent_name' => 'designation', 'name' => 'list_designations', 'route' => 'admin.designation.index'],
            ['parent_name' => 'designation', 'name' => 'create_designation', 'route' => 'admin.designation.create'],
            ['parent_name' => 'designation', 'name' => 'store_designation', 'route' => 'admin.designation.store'],
            ['parent_name' => 'designation', 'name' => 'edit_designation', 'route' => 'admin.designation.edit'],
            ['parent_name' => 'designation', 'name' => 'update_designation', 'route' => 'admin.designation.update'],
            ['parent_name' => 'designation', 'name' => 'change_designation_status', 'route' => 'admin.designation.status'],
            ['parent_name' => 'designation', 'name' => 'delete_designation', 'route' => 'admin.designation.delete'],
            ['parent_name' => 'designation', 'name' => 'update_designation_permissions', 'route' => 'admin.designation.permissions'],

            ['parent_name' => 'pages', 'name' => 'list_pages', 'route' => 'admin.pages.index'],
            ['parent_name' => 'pages', 'name' => 'create_page', 'route' => 'admin.pages.create'],
            ['parent_name' => 'pages', 'name' => 'store_page', 'route' => 'admin.pages.store'],
            ['parent_name' => 'pages', 'name' => 'view_page', 'route' => 'admin.pages.show'],
            ['parent_name' => 'pages', 'name' => 'edit_page', 'route' => 'admin.pages.edit'],
            ['parent_name' => 'pages', 'name' => 'update_page', 'route' => 'admin.pages.update'],
            ['parent_name' => 'pages', 'name' => 'change_page_status', 'route' => 'admin.pages.status'],
            ['parent_name' => 'pages', 'name' => 'delete_page', 'route' => 'admin.pages.delete'],

            ['parent_name' => 'menus', 'name' => 'list_menus', 'route' => 'admin.menus.index'],
            ['parent_name' => 'menus', 'name' => 'store_menu', 'route' => 'admin.menus.store'],
            ['parent_name' => 'menus', 'name' => 'update_menu_order', 'route' => 'admin.menus.updateOrder'],

            ['parent_name' => 'donations', 'name' => 'list_donations', 'route' => 'admin.donations.index'],
            ['parent_name' => 'donations', 'name' => 'create_donation', 'route' => 'admin.donations.create'],
            ['parent_name' => 'donations', 'name' => 'store_donation', 'route' => 'admin.donations.store'],
            ['parent_name' => 'donations', 'name' => 'view_donation', 'route' => 'admin.donations.show'],
            ['parent_name' => 'donations', 'name' => 'export_donations', 'route' => 'admin.donations.export'],

            ['parent_name' => 'events', 'name' => 'list_events', 'route' => 'admin.events.index'],
            ['parent_name' => 'events', 'name' => 'create_event', 'route' => 'admin.events.create'],
            ['parent_name' => 'events', 'name' => 'store_event', 'route' => 'admin.events.store'],
            ['parent_name' => 'events', 'name' => 'edit_event', 'route' => 'admin.events.edit'],
            ['parent_name' => 'events', 'name' => 'update_event', 'route' => 'admin.events.update'],
            ['parent_name' => 'events', 'name' => 'delete_event', 'route' => 'admin.events.delete'],
            ['parent_name' => 'events', 'name' => 'change_event_status', 'route' => 'admin.events.status'],

            ['parent_name' => 'product', 'name' => 'list_products', 'route' => 'admin.product.index'],
            ['parent_name' => 'product', 'name' => 'create_product', 'route' => 'admin.product.create'],
            ['parent_name' => 'product', 'name' => 'store_product', 'route' => 'admin.product.store'],
            ['parent_name' => 'product', 'name' => 'view_product', 'route' => 'admin.product.view'],
            ['parent_name' => 'product', 'name' => 'edit_product', 'route' => 'admin.product.edit'],
            ['parent_name' => 'product', 'name' => 'delete_product', 'route' => 'admin.product.delete'],
            ['parent_name' => 'product', 'name' => 'export_products', 'route' => 'admin.product.export.all'],

            ['parent_name' => 'order', 'name' => 'list_orders', 'route' => 'admin.order.index'],
            ['parent_name' => 'order', 'name' => 'store_order', 'route' => 'admin.order.store'],
            ['parent_name' => 'order', 'name' => 'view_order', 'route' => 'admin.order.view'],
            ['parent_name' => 'order', 'name' => 'update_order', 'route' => 'admin.order.update'],
            ['parent_name' => 'order', 'name' => 'change_order_status', 'route' => 'admin.order.status'],
            ['parent_name' => 'order', 'name' => 'export_orders', 'route' => 'admin.order.export.all'],
            ['parent_name' => 'order', 'name' => 'order_report', 'route' => 'admin.order.report'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                [
                    'parent_name' => $permission['parent_name'],
                    'route'       => $permission['route'],
                ]
            );
        }
    }
}
