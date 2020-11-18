## 安装

```shell
$ composer require mradang/laravel-rbac -vvv
```

### 可选项

1. 发布配置文件

```shell
$ php artisan vendor:publish --provider="mradang\\LaravelRbac\\LaravelRbacServiceProvider"
```

## 配置

1. 添加 .env 环境变量，使用默认值时可省略
```
# 指定 token 的有效时间（单位秒），默认 24 小时（60*60*24=86400）
RBAC_JWT_TTL=86400
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
- post /rbac/allNodes
- post /rbac/allNodesWithRole
- post /rbac/refreshNodes
- post /rbac/allRoles
- post /rbac/createRole
- post /rbac/findRoleWithNodes
- post /rbac/syncNodeRoles
- post /rbac/syncRoleNodes
- post /rbac/updateRole
- post /rbac/deleteRole
- post /rbac/saveRoleSort

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
二选一即可
1. auth 需要授权的路由
2. auth.basic 只需登录的路由

## 用户认证功能

### 基础配置
user 数据表必须包含字段：id, name, secret
```php
$table->increments('id');
$table->string('name');
$table->string('secret')->nullable();
```

### 模型 Trait
```php
use mradang\LaravelRbac\Traits\UserModelTrait;
```

增加以下内容：
> - belongsToMany rbacRoles 角色关联（多对多）
> - array getAccessAttribute 权限属性 access，user 模型需实现 getIsAdminAttribute（超级管理员）属性
> - string rbacMakeToken(array $fields = ['id', 'name']) 生成用户访问令牌
> - bool rbacResetSecret() 重置用户安全码
> - void rbacSyncRoles(array $roles) 同步用户与角色的关联，$roles 为角色 id 数组
> - void rbacDeleteUser() 删除用户权限信息
