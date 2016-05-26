## [Simple-PHPRestWeb-ForMobileAPI](https://github.com/wuyunfeng/Simple-PHPRestWeb-ForMobileAPI.git)

> 快速部署一个简单的Nginx，原生PHP小框架 给手机端App提供简单API接口，实现快速开发，不再依赖后端

## Fist Step

安装`nginx`，如果你是Mac，那么你只需要一句命令行就可以搞定
		
		brew install nginx
安装的位置在/usr/local下，这时候你需要找到nginx的配置文件`nginx.conf`,推荐的做法是建立一个`vhost`文件夹，单独写一个`xxx.conf`,配置模版如下：
		
		server
		{
			listen 10000;
			server_name ｀your ip address｀;
			#index index.html index.htm index.php;
			index index.php;
			root "/path/to/simple-php-webapp/public";
			access_log "/path/to/your_name.log" main;
			try_files $uri $uri/ /index.php?$args;
			location ~ .*\.(php|php5)?$
			{
				fastcgi_pass 127.0.0.1:9000;
				fastcgi_index index.php;
				include fastcgi.conf;
    		}
    		location ~ /\.(ht|svn|git) 
    		{
        		deny all;
    		}
		}
在这个配置文件中，我们通过`try_files`指令，将收到的请求重定向至`index.php`，然后启动nginx,mac 下如下：
		
		sudo nginx

##Second Step

安装`PHP`,如果你是Mac，那么你只需要一句命令行就可以搞定
		
		brew install php
		
然后找到`php.ini`, `php-fpm.ini`进行简单的配置，然后启动`PHP-FPM`,Mac 下如下：

		sudo php-fpm
保证｀php-fpm`对一些日志文件有权限，能不给root权限的情况下尽量不要给

##Thrid Step

以上两步完成后，在浏览器里输入：

		http://127.0.0.1:10000/api/list/get?name=wuyunfeng
如果浏览器有`Json`输出，这时候你已经可以开发简单的Mobile API了，`/api/list/get` 是简单的将你请求中包含的`get`参数进行`Json`序列化后输出（当然｀api｀是在程序里｀hard code｀的，你可以修改)
`MobileAPIController`对此输出进行负责

现在，你要开发属于你自己的业务了：


   * 将你喜欢的/list/get写在route.php中，叫｀路由｀步骤，就是找到你要执行的代码
   
   
   			return array(
    			"GET" => array(
        					"list" => array(
           					 	"get" => "MobileAPIController@executePrintGetAction",
       						 )
    					),
    			"POST" => array(
        					"list" => array(
           					 	"post" => "MobileAPIController@executePrintPostAction",
        					)
   		 				),
					);


* 自定义你自己的控制器，如果你需要打日志，可以直接继承｀BaseController｀


		class MobileAPIController extends BaseController
		{

   			 //do request request log
   			 function __construct()
   			 {
        		parent::__construct();
    		}

    		function executePrintGetAction()
    		{
       			 Response::make(array(
           							 'format' => Response::FORMAT_JSON,
            						'response' => $_GET
       							 ));
   		 	}

    		function executePrintPostAction()
    		{
        		Response::make(array(
            						'format' => Response::FORMAT_JSON,
            						'response' => $_GET
        						));
    		}

		}
		


* Response提供简单的输出封装，第2步中对输出进行｀Json｀序列化封装，如果你懂PHP那么一看明了，😄。如果不懂可以边查边学。


##Finally

  后续会继续对此小框架进行简单封装
  
  1. mysql等
  
  2. 消息队列等
  
  3. redis等
####激情，拥抱变化 😄






