## 安装

```shell
$ composer require mradang/laravel-rbac -vvv
```

## 配置

1. 添加 .env 环境变量，使用默认值时可省略

```
# 指定用户模型类，实现 RBAC 的关键配置
RBAC_USER_MODEL=\App\Models\User
```

2. 刷新数据库迁移

```bash
php artisan migrate:refresh
```

## 添加的内容

### 添加的数据表迁移

- rbac_access
- rbac_node
- rbac_role_user
- rbac_role

### 添加的路由

- post /api/rbac/allNodes
- post /api/rbac/allRoles
- post /api/rbac/createRole
- post /api/rbac/deleteRole
- post /api/rbac/updateRole
- post /api/rbac/findRoleWithNodes
- post /api/rbac/saveRoleSort
- post /api/rbac/syncRoleNodes

### 添加的命令

1. 生成路由描述文件：storage/app/route_desc.json

```bash
php artisan rbac:MakeRouteDescFile
```

2. 刷新路由节点及描述

```bash
php artisan rbac:RefreshRbacNode
```

### 添加的路由中间件

- rbac

## 路由保护

### 用户模型 Trait

```php
use mradang\LaravelRbac\Traits\UserModelTrait;
```

增加以下内容：

> - belongsToMany rbacRoles 角色关联（多对多）
> - array getAccessAttribute 权限属性 access，user 模型需实现 getIsAdminAttribute（超级管理员）属性
> - void rbacSyncRoles(array $roles) 同步用户与角色的关联，$roles 为角色 id 数组
> - void rbacDeleteUser() 删除用户权限信息

### 签发令牌

```php
$token = $user->createToken('app-name', $user->access)->plainTextToken;
```

### 验证登录

```php
Route::middleware(['auth:sanctum'])
```

### 验证权限

```php
Route::middleware(['auth:sanctum', 'rbac'])
```
