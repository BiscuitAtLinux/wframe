# wframe
wframe是一个基于[Workerman](http://www.workerman.net/)超级精简且高性能的Web框架，提供以下特性
* 内置HTTP服务器，不同于PHP-FPM编程模型。每次请求后不释放内存，不需要每次请求重复执行初始化操作，性能高
* 请求处理逻辑简单，只需要关注Router和Plugin既可完成Web应用
	* Router：路由，将不同的请求路径映射到不同的业务处理代码上
	* Plugin：插件，类似Java Servlet的Filter，可用于日志打印、参数校验、编码转换
* 文件结构简单，内置基于PHP命名空间的`SimpleNamespaceRouter`
# 依赖
* [Composer](https://getcomposer.org/)
* [Workerman](http://www.workerman.net/)
# 运行
* `composer install`
* `php app_server.php start`
* 访问 http://localhost:9502