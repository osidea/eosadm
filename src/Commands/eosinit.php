<?php

namespace EOSFM\Framework\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use EOSFM\Framework\Extended\UCenter;

class eosinit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eos:init {step?} {key?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'initialize eos admin framework';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public $password = '';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->argument('key') != 'dev'){
            if(file_exists(base_path('eosadm.lock'))){
                $this->info('========================================================');
                $this->info('> 程序已初始化完成!!');
                $this->info('> 重新初始化程序,请删除根目录下的eosadm.lock文件');
                $this->info('========================================================');
                exit;
            }
        }

        //
        $step = $this->argument('step');
        switch ($step) {
            case 'admin':
                $admin = $this->ask('后台管理员总帐号');
                if (!$admin) {
                    $admin = 'eosadmin';
                }
                $this -> setAdm();
                $phone = $this->ask('手机号码');
                if (!$phone) {
                    $phone = 13000000000;
                }
                $email = $this->ask('Email');
                if (!$email) {
                    $email = 'admin@admin.com';
                }
                $create_user = UCenter::create(
                    [
                        'username' => $admin,
                        'password' => $this -> password,
                        'phone' => $phone,
                        'email' => $email,
                        'status' => 1,
                        'auth' => 'yy' //总管理员 + 开发模式
                    ]
                );

                //----------------------------------------------------------------------------------------------------------------------------------------------------------------
                $config = [
                    ['name' => 'site_name', 'type' => 1, 'title' => '站点名称', 'value' => env('APP_NAME'), 'group' => 'site', 'remark' => '站点主标题', 'lock' => 1],
                    ['name' => 'site_keyword', 'type' => 2, 'title' => '站点关键字', 'value' => env('APP_NAME'), 'group' => 'site', 'remark' => '用于SEO优化', 'lock' => 1],
                    ['name' => 'site_description', 'type' => 2, 'title' => '站点描述', 'value' => env('APP_NAME'), 'group' => 'site', 'remark' => '用于SEO优化', 'lock' => 1],
                    ['name' => 'site_status', 'type' => 8, 'title' => '站点状态', 'value' => 0, 'extra' => '0:关闭,1:开启', 'group' => 'site', 'remark' => '关闭站点后仅可访问控制台', 'lock' => 1],
                    ['name' => 'site_close_text', 'type' => 1, 'title' => '关闭站点提示语', 'value' => '站点维护中,马上回来！', 'group' => 'site', 'remark' => '关闭站点后的提示语', 'lock' => 1],
                ];
                $this->info('');
                $bar = $this->output->createProgressBar(count($config)+1);
                foreach($config as $value){
                    DS('system_config', $value);
                    $bar->advance();
                }
                $bar->finish();
                $this->info('');

                $config_group = [
                    ['id' => 1, 'name' => '站点配置', 'key' => 'site'],
                ];
                $this->info('');
                $bar = $this->output->createProgressBar(count($config_group)+1);
                foreach($config_group as $value){
                    DS('system_config_group', $value);
                    $bar->advance();
                }
                $bar->finish();
                $this->info('');
                $auth = [
                    ['id' => 1, 'pid' => 0, 'name' => '管理控制台', 'c' => 'index', 'f' => 'index', 'o' => '', 'auth' => 0, 'status' => 1],
                    ['id' => 910, 'pid' => 0, 'name' => '模型管理', 'c' => 'system', 'f' => 'model', 'o' => '', 'auth' => 1, 'status' => 1, 'show' => 0],
                    ['id' => 911, 'pid' => 910, 'name' => '添加/编辑模型', 'c' => 'system', 'f' => 'model', 'o' => 'save', 'auth' => 1, 'status' => 1, 'show' => 0],
                    ['id' => 912, 'pid' => 910, 'name' => '删除模型', 'c' => 'system', 'f' => 'model', 'o' => 'del', 'auth' => 1, 'status' => 1, 'show' => 0],
                    ['id' => 913, 'pid' => 910, 'name' => '生成模型', 'c' => 'system', 'f' => 'model', 'o' => 'generate', 'auth' => 1, 'status' => 1, 'show' => 0],
                    ['id' => 920, 'pid' => 0, 'name' => '字段管理', 'c' => 'system', 'f' => 'field', 'o' => '', 'auth' => 1, 'status' => 1, 'show' => 0],
                    ['id' => 921, 'pid' => 920, 'name' => '添加/编辑字段', 'c' => 'system', 'f' => 'field', 'o' => 'save', 'auth' => 1, 'status' => 1, 'show' => 0],
                    ['id' => 922, 'pid' => 920, 'name' => '删除字段', 'c' => 'system', 'f' => 'field', 'o' => 'del', 'auth' => 1, 'status' => 1, 'show' => 0],
                    ['id' => 930, 'pid' => 0, 'name' => '权限规则', 'c' => 'system', 'f' => 'auth', 'o' => '', 'auth' => 1, 'status' => 1, 'show' => 0],
                    ['id' => 931, 'pid' => 930, 'name' => '添加/编辑规则', 'c' => 'system', 'f' => 'auth', 'o' => 'save', 'auth' => 1, 'status' => 1, 'show' => 0],
                    ['id' => 932, 'pid' => 930, 'name' => '删除规则', 'c' => 'system', 'f' => 'auth', 'o' => 'del', 'auth' => 1, 'status' => 1, 'show' => 0],

                ];
                $this->info('');
                $bar = $this->output->createProgressBar(count($auth)+1);
                foreach($auth as $value){

                    DS('auth_rule', $value);
                    $bar->advance();
                }
                $bar->finish();
                $this->info('');
                $model = [
                    ['name' => '模型管理', 'model_name' => 'system_model', 'status' => 1, 'done' => 'back'],
                    ['name' => '字段管理', 'model_name' => 'system_model_field', 'status' => 1, 'done' => 'back'],
                    ['name' => '权限规则', 'model_name' => 'auth_rule', 'status' => 1, 'done' => 'back'],
                    ['name' => '配置分组', 'model_name' => 'system_config_group', 'status' => 1, 'done' => 'back'],
                    ['name' => '配置管理', 'model_name' => 'system_config', 'status' => 1, 'done' => 'back'],
                ];
                $this->info('');
                $bar = $this->output->createProgressBar(count($model)+1);
                foreach($model as $value){
                    DS('system_model', $value);
                    $bar->advance();
                }
                $bar->finish();
                $this->info('');
                $model_field = [
                    ['name' => '名称', 'model_name' => 'system_model', 'field' => 'name'],
                    ['name' => '模型名称', 'model_name' => 'system_model', 'field' => 'model_name'],
                    ['name' => '请求地址', 'model_name' => 'system_model', 'field' => 'action'],
                    ['name' => '完成跳转', 'model_name' => 'system_model', 'field' => 'done'],

                    ['name' => '字段标题', 'model_name' => 'system_model_field', 'field' => 'name'],
                    ['name' => '字段名称', 'model_name' => 'system_model_field', 'field' => 'field'],
                    ['name' => '所属模型', 'model_name' => 'system_model_field', 'field' => 'model_name', 'show' => 0, 'default_value' => '#I#'],
                    ['name' => '提示信息', 'model_name' => 'system_model_field', 'field' => 'placeholder'],
                    ['name' => '默认值', 'model_name' => 'system_model_field', 'field' => 'default_value', 'placeholder' => '字段默认值 (#I#)'],
                    ['name' => '扩展值', 'model_name' => 'system_model_field', 'field' => 'extra_custom', 'type' => 'text'],
                    ['name' => '扩展模型', 'model_name' => 'system_model_field', 'field' => 'extra_model'],
                    ['name' => '模型条件', 'model_name' => 'system_model_field', 'field' => 'extra_where'],
                    ['name' => '标签名', 'model_name' => 'system_model_field', 'field' => 'extra_name'],
                    ['name' => '标签值', 'model_name' => 'system_model_field', 'field' => 'extra_value'],
                    ['name' => '字段说明', 'model_name' => 'system_model_field', 'field' => 'remark'],
                    ['name' => '字段类型', 'model_name' => 'system_model_field', 'field' => 'type', 'extra_custom' => 'input:输入框,number:数字,password:密码,text:文本,select:下拉选项,radio:单选', 'type' => 'select'],
                    ['name' => '显示', 'model_name' => 'system_model_field', 'field' => 'show', 'default_value' => 1, 'extra_custom' => '0:隐藏,1:显示', 'type' => 'radio'],

                    ['name' => '名称', 'model_name' => 'auth_rule', 'field' => 'name'],
                    ['name' => '图标', 'model_name' => 'auth_rule', 'field' => 'icon'],
                    ['name' => '父级菜单', 'model_name' => 'auth_rule', 'field' => 'pid', 'type' => 'select', 'extra_custom' => '0:顶级菜单', 'extra_model' => 'auth_rule', 'extra_where' => '{"pid": 0}', 'extra_name' => 'name', 'extra_value' => 'id'],
                    ['name' => '控制器', 'model_name' => 'auth_rule', 'field' => 'c'],
                    ['name' => '公共方法', 'model_name' => 'auth_rule', 'field' => 'f'],
                    ['name' => '操作', 'model_name' => 'auth_rule', 'field' => 'o'],
                    ['name' => '扩展', 'model_name' => 'auth_rule', 'field' => 'to'],
                    ['name' => '开启权限', 'model_name' => 'auth_rule', 'field' => 'auth', 'default_value' => 1, 'extra_custom' => '0:不限,1:受限', 'type' => 'radio'],
                    ['name' => '显示', 'model_name' => 'auth_rule', 'field' => 'show', 'default_value' => 1, 'extra_custom' => '0:隐藏,1:显示', 'type' => 'radio'],
                    ['name' => '状态', 'model_name' => 'auth_rule', 'field' => 'status', 'default_value' => 1, 'extra_custom' => '0:停用,1:启用', 'type' => 'radio'],

                    ['name' => '名称', 'model_name' => 'system_config_group', 'field' => 'name'],
                    ['name' => '分组标识', 'model_name' => 'system_config_group', 'field' => 'key'],

                    ['name' => '变量标识', 'model_name' => 'system_config', 'field' => 'name'],
                    ['name' => '变量名', 'model_name' => 'system_config', 'field' => 'title'],
                    ['name' => '分组标识', 'model_name' => 'system_config', 'field' => 'group', 'type' => 'select', 'extra_model' => 'system_config_group', 'extra_name' => 'name', 'extra_value' => 'key'],
                    ['name' => '说明', 'model_name' => 'system_config', 'field' => 'remark'],
                    ['name' => '类型', 'model_name' => 'system_config', 'field' => 'type', 'type' => 'select', 'extra_custom' => '1:输入框,3:数字,4:密码,2:文本,8:下拉选项,9:单选'],
                    ['name' => '排序', 'model_name' => 'system_config', 'field' => 'sort', 'default_value' => 0],
                    ['name' => '配置项', 'model_name' => 'system_config', 'field' => 'extra', 'remark' => '如果是枚举型 需要配置该项'],
                    ['name' => '变量值', 'model_name' => 'system_config', 'field' => 'value'],
                    ['name' => '状态', 'model_name' => 'system_config', 'field' => 'status', 'default_value' => 1, 'extra_custom' => '0:停用,1:启用', 'type' => 'radio'],
                    ['name' => '锁定', 'model_name' => 'system_config', 'field' => 'lock', 'default_value' => 0, 'show' => 1,'extra_custom' => '0:不锁定,1:锁定', 'type' => 'radio', 'remark' => '锁定后将无法修改'],
                ];
                $this->info('');
                $bar = $this->output->createProgressBar(count($model_field)+1);
                foreach($model_field as $value){
                    DS('system_model_field', $value);
                    $bar->advance();
                }
                $bar->finish();
                $this->info('');

                //----------------------------------------------------------------------------------------------------------------------------------------------------------------

                if($create_user['errcode'] == 0){
                    file_put_contents(base_path('eosadm.lock'), time());
                    $this->info('========================================================');
                    $this->info('> 程序安装完成!!');
                    $this->info('> EOS-Panel:http://yourdomain/eosadm');
                    $this->info('> username:'. $admin);
                    $this->info('> password:'. $this -> password);
                    $this->info('========================================================');
                } else {
                    $this->info('========================================================');
                    $this->info('> 配置管理员错误');
                    $this->info('> '. $create_user['errcode']. ' '. $create_user['message']);
                    $this->info('> 请重新运行 php artisan eos:init admin 配置管理员');
                    $this->info('========================================================');
                }

                break;
            case 'publish':
                $this->call('vendor:publish', ['--tag' => 'eosadm', '--force' => true]);
                $this->call('eos:init', ['step' => 'admin']);
                break;
            case 'db':
                $this->info('========================================================');
                clearDir(database_path('/migrations'));
                $this->call('migrate');
//                $this->call('migrate:fresh');
                sleep(1);
                $this->info('========================================================');
                $this->info('> 数据安装完成!!');
                $this->info('========================================================');
                $this->call('key:generate');
                $this->call('eos:init', ['step' => 'publish']);

                break;
            default:
                $bar = $this->output->createProgressBar(10);
                $this->setDb($bar);
                $bar->finish();
                $this->info('');
                $this->info('========================================================');
                $this->info('> 数据库配置成功!!');
                $this->info('');
                $this->info('> 请运行 php artisan eos:init db 安装数据');
                $this->info('========================================================');
                break;
        }

    }

    public function setAdm()
    {
        $password = $this->secret('后台管理员密码');
        if (!$password) {
            $password = 'eosadmin';
        }
        $repassword = $this->secret('重复输入密码');
        if (!$repassword) {
            $repassword = 'eosadmin';
        }

        if($password != $repassword){
            $this->info('========================================================');
            $this->info('> 两次输入的密码不一致,请重新输入');
            $this->info('========================================================');
            $this->setAdm();
        } else {
            $this -> password = $password;
        }
    }

    public function setDb($bar)
    {
        $appname = $this->ask('应用名称(EOSAdmin)');
        if (!$appname) {
            $appname = 'EOSAdmin';
        }
        $dbhost = $this->ask('配置数据库地址(localhost)');
        if (!$dbhost) {
            $dbhost = 'localhost';

        }
        if (stripos($dbhost, ':') !== false) {
            $tmp = explode(':', $dbhost);
            $dbhost = $tmp[0];
            $dbport = $tmp[1];
        } else {
            $dbport = '3306';
        }
        $dbname = $this->ask('配置数据库名(eosadmin)');
        if (!$dbname) {
            $dbname = 'eosadmin';
        }
        $dbuser = $this->ask('配置数据库用户名(root)');
        if (!$dbuser) {
            $dbuser = 'root';
        }
        $dbpassword = $this->ask('配置数据库密码');
        if (!$dbpassword) {
            $dbpassword = '123456';
        }
        $dbprefix = $this->ask('配置数据表前缀(os_)');
        if (!$dbprefix) {
            $dbprefix = 'os_';
        }
        $this->info('========================================================');
        $this->info('> 数据库地址: ' . $dbhost);
        $this->info('> 数据库端口: ' . $dbport);
        $this->info('> 数据库名: ' . $dbname);
        $this->info('> 数据库用户名: ' . $dbuser);
        $this->info('> 数据库密码: ' . $dbpassword);
        $this->info('> 数据表前缀: ' . $dbprefix);
        $this->info('========================================================');

        if ($this->confirm('是否确认以上数据库配置?')) {
            $bar->advance();
            $DBSET = "mysql:host=$dbhost;dbname=$dbname";
            try {
                $DBSET = new \PDO($DBSET, $dbuser, $dbpassword);
                $checkDB = new Connection($DBSET);
                $bar->advance();
            } catch (\Exception $e) {
                if ($this->confirm('数据库无法连接,是否重新配置?')) {
                    $this->setDb();
                    exit;
                } else {
                    $this->error('配置失败');
                    exit;
                }
            }

            $dbconfig = <<<CONFIG
<?php

return [

    /*
    |========================================================-------------
    | Default Database Connection Name
    |========================================================-------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |========================================================-------------
    | Database Connections
    |========================================================-------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => env('DB_PREFIX', 'os_'),
            'strict' => true,
            'engine' => null,
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
        ],

    ],

    /*
    |========================================================-------------
    | Migration Repository Table
    |========================================================-------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |========================================================-------------
    | Redis Databases
    |========================================================-------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
CONFIG;

            $envconfig = <<<ENV
APP_NAME=$appname
APP_ENV=local
APP_KEY=base64:VBJ+5c0AYcp7oULJ6n2zAjRgVwHz7Q4Tm3YdL0CBAMs=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=$dbhost
DB_PORT=$dbport
DB_DATABASE=$dbname
DB_USERNAME=$dbuser
DB_PASSWORD=$dbpassword
DB_PREFIX=$dbprefix

BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_DRIVER=sync

ENV;


            $bar->advance();
            file_put_contents(config_path('database.php'), $dbconfig);
            sleep(1);
            $bar->advance();
            file_put_contents(base_path('.env'), $envconfig);
            $bar->advance();
            sleep(3);

        } else {
            $this->setDb();
        }
    }
}

function clearDir($dirName)
{
    if ($handle = opendir("$dirName")) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (is_dir("$dirName/$item")) {
                    clearDir("$dirName/$item");
                } else {
                    if (unlink("$dirName/$item")){}
                }
            }
        }
    }
}