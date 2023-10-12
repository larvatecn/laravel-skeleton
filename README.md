# Laravel API 基础模板

开箱即用的 Laravel API 基础结构。
> 🚨自己用的哈，仅供参考，不提供咨询解答服务。

## 特点
- DDD（领域模型驱动）结构；
- 内置生成器，一键生成模块；
- 内置 laravel/sanctum 的授权机制；
- 高度完善的控制器、模型、模块模板；
- 集成常用基础扩展；
- 内置模型通用高阶 Traits 封装;
- 自动注册 Policies；
- 内置用户系统和基础接口；

## 安装

1. 创建项目

```bash
$ composer create-project larva/laravel-skeleton:dev-master -vv
```


2. 创建配置文件

```bash
$ cp .env.example .env
```

3. 创建数据表和测试数据

```bash
$ php artisan migrate --seed
```
## License
MIT
