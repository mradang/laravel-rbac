<?php

namespace mradang\LaravelRbac\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use mradang\LaravelRbac\Models\RbacAccess;
use mradang\LaravelRbac\Models\RbacNode;

class RbacNodeService
{
    public static function all()
    {
        return RbacNode::orderBy('name')->get();
    }

    private static function apiRoutes(): Collection
    {
        $nodes = collect([]);
        $routes = Route::getRoutes();
        foreach ($routes as $route) {
            $uri = Str::start($route->uri, '/');
            if (Str::startsWith($uri, '/api/')) {
                $nodes->push($route);
            }
        }
        return $nodes;
    }

    // 无需授权的节点
    public static function publicNodes(): Collection
    {
        return self::apiRoutes()
            ->filter(function ($route) {
                return !in_array('auth', $route->middleware());
            })
            ->map(function ($route) {
                $uri = Str::start($route->uri, '/');
                return Str::after($uri, '/api');
            });
    }

    // 需要授权的节点
    public static function AuthNodes(): Collection
    {
        return self::apiRoutes()
            ->filter(function ($route) {
                return in_array('auth', $route->middleware());
            })
            ->map(function ($route) {
                $uri = Str::start($route->uri, '/');
                return Str::after($uri, '/api');
            });
    }

    private static function getRouteDesc()
    {
        $filename = storage_path('app/route_desc.json');

        $desc = [];
        if (is_file($filename) && is_readable($filename)) {
            $desc = json_decode(file_get_contents($filename), true);
        }

        // 设置 rbac 内置路由说明
        Arr::set($desc, 'rbac.allNodes', '功能节点列表');
        Arr::set($desc, 'rbac.allRoles', '角色列表');
        Arr::set($desc, 'rbac.createRole', '新建角色');
        Arr::set($desc, 'rbac.deleteRole', '删除角色');
        Arr::set($desc, 'rbac.updateRole', '修改角色');
        Arr::set($desc, 'rbac.findRoleWithNodes', '获取角色及功能节点');
        Arr::set($desc, 'rbac.saveRoleSort', '角色排序');
        Arr::set($desc, 'rbac.syncRoleNodes', '设置角色权限');

        return $desc;
    }

    private static function setRouteDesc(array $desc)
    {
        $filename = storage_path('app/route_desc.json');
        $content = json_encode($desc, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);
        if ($content && is_writable(dirname($filename))) {
            return file_put_contents($filename, $content);
        }
    }

    public static function makeRouteDescFile()
    {
        // 读取节点数据
        $nodes = self::AuthNodes();
        // 读取功能说明文件
        $desc = self::getRouteDesc();

        // 重新生成功能说明文件
        $new = [];
        foreach ($nodes as $node) {
            list(, $module) = explode('/', $node);
            if (!array_key_exists($module, $new)) {
                $new[$module] = [];
            }
            $function = Str::after($node, "/$module/");
            $new[$module][$function] = Arr::get($desc, "$module.$function", '');
        }

        // 排序
        foreach ($new as $key => &$value) {
            ksort($value);
        }
        ksort($new);

        // 写入文件
        return self::setRouteDesc($new);
    }

    public static function refresh()
    {
        // 读取功能说明文件
        $desc = self::getRouteDesc();

        // 获取需要授权的路由节点，并更新数据库
        $nodes = self::AuthNodes();
        $ids = [];
        foreach ($nodes as $node) {
            list(, $module) = explode('/', $node);
            $function = Str::after($node, "/$module/");
            $rbac_node = RbacNode::firstOrNew(['name' => $node]);
            $rbac_node->description = Arr::get($desc, "$module.$function", '');
            $rbac_node->save();
            $ids[] = $rbac_node->id;
        }

        // 清理无效节点
        RbacNode::whereNotIn('id', $ids)->delete();
        // 清理无效权限
        RbacAccess::whereNotIn('node_id', RbacNode::pluck('id'))->delete();
    }

    public static function syncRoles($id, array $roles)
    {
        $node = RbacNode::findOrFail($id);
        $node->roles()->sync($roles);
        return $node->roles;
    }
}
