## Laravel 阶梯降频轮询出队列示例

### 1. 项目初始化
```shell
composer install
```

### 2. 配置 .env
```shell
cp .env.example .env
```

### 3. 生成 key
```shell
php artisan key:generate
```

### 4. 数据库准备
```shell
# 创建表结构
php artisan migrate

# 插入测试数据
php artisan db:seed
```

### 5. 测试出队列效果
```shell
# 先开始队列监听worker
php artisan queue:work --sleep=1

# 入队列
php artisan app:test
```

### 6. 查看队列执行情况
```shell
# 打开日志，查看队列执行情况
tail -f storage/logs/laravel.log
```
