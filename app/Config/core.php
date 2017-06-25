<?php

/**
 * This is core configuration file.
 *
 * Use it to configure core behavior of Cake.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
//setLocale(LC_ALL, 'deu');
//Configure::write('Config.language', 'deu');

/**
 * CakePHP Debug Level:
 *
 * Production Mode:
 * 	0: No error messages, errors, or warnings shown. Flash messages redirect.
 *
 * Development Mode:
 * 	1: Errors and warnings shown, model caches refreshed, flash messages halted.
 * 	2: As in 1, but also with full debug messages and SQL output.
 *
 * In production mode, flash messages redirect after a time interval.
 * In development mode, you need to click the flash message to continue.
 */
Configure::write('debug', 2);

/**
 * Configure the Error handler used to handle errors for your application. By default
 * ErrorHandler::handleError() is used. It will display errors using Debugger, when debug > 0
 * and log errors with CakeLog when debug = 0.
 *
 * Options:
 *
 * - `handler` - callback - The callback to handle errors. You can set this to any callable type,
 *   including anonymous functions.
 *   Make sure you add App::uses('MyHandler', 'Error'); when using a custom handler class
 * - `level` - integer - The level of errors you are interested in capturing.
 * - `trace` - boolean - Include stack traces for errors in log files.
 *
 * @see ErrorHandler for more information on error handling and configuration.
 */
Configure::write('Error', array(
    'handler' => 'ErrorHandler::handleError',
    'level' => E_ALL & ~E_DEPRECATED,
    'trace' => true
));

/**
 * Configure the Exception handler used for uncaught exceptions. By default,
 * ErrorHandler::handleException() is used. It will display a HTML page for the exception, and
 * while debug > 0, framework errors like Missing Controller will be displayed. When debug = 0,
 * framework errors will be coerced into generic HTTP errors.
 *
 * Options:
 *
 * - `handler` - callback - The callback to handle exceptions. You can set this to any callback type,
 *   including anonymous functions.
 *   Make sure you add App::uses('MyHandler', 'Error'); when using a custom handler class
 * - `renderer` - string - The class responsible for rendering uncaught exceptions. If you choose a custom class you
 *   should place the file for that class in app/Lib/Error. This class needs to implement a render method.
 * - `log` - boolean - Should Exceptions be logged?
 * - `extraFatalErrorMemory` - integer - Increases memory limit at shutdown so fatal errors are logged. Specify
 *   amount in megabytes or use 0 to disable (default: 4 MB)
 * - `skipLog` - array - list of exceptions to skip for logging. Exceptions that
 *   extend one of the listed exceptions will also be skipped for logging.
 *   Example: `'skipLog' => array('NotFoundException', 'UnauthorizedException')`
 *
 * @see ErrorHandler for more information on exception handling and configuration.
 */
Configure::write('Exception', array(
    'handler' => 'ErrorHandler::handleException',
    'renderer' => 'ExceptionRenderer',
    'log' => true
));

/**
 * Application wide charset encoding
 */
Configure::write('App.encoding', 'UTF-8');

/**
 * To configure CakePHP *not* to use mod_rewrite and to
 * use CakePHP pretty URLs, remove these .htaccess
 * files:
 *
 * /.htaccess
 * /app/.htaccess
 * /app/webroot/.htaccess
 *
 * And uncomment the App.baseUrl below. But keep in mind
 * that plugin assets such as images, CSS and JavaScript files
 * will not work without URL rewriting!
 * To work around this issue you should either symlink or copy
 * the plugin assets into you app's webroot directory. This is
 * recommended even when you are using mod_rewrite. Handling static
 * assets through the Dispatcher is incredibly inefficient and
 * included primarily as a development convenience - and
 * thus not recommended for production applications.
 */
Configure::write('App.baseUrl', env('SCRIPT_NAME'));

/**
 * To configure CakePHP to use a particular domain URL
 * for any URL generation inside the application, set the following
 * configuration variable to the http(s) address to your domain. This
 * will override the automatic detection of full base URL and can be
 * useful when generating links from the CLI (e.g. sending emails).
 * If the application runs in a subfolder, you should also set App.base.
 */
//Configure::write('App.fullBaseUrl', 'http://example.com');

/**
 * The base directory the app resides in. Should be used if the
 * application runs in a subfolder and App.fullBaseUrl is set.
 */
//Configure::write('App.base', '/my_app');

/**
 * Web path to the public images directory under webroot.
 * If not set defaults to 'img/'
 */
//Configure::write('App.imageBaseUrl', 'img/');

/**
 * Web path to the CSS files directory under webroot.
 * If not set defaults to 'css/'
 */
//Configure::write('App.cssBaseUrl', 'css/');

/**
 * Web path to the js files directory under webroot.
 * If not set defaults to 'js/'
 */
//Configure::write('App.jsBaseUrl', 'js/');

/**
 * Uncomment the define below to use CakePHP prefix routes.
 *
 * The value of the define determines the names of the routes
 * and their associated controller actions:
 *
 * Set to an array of prefixes you want to use in your application. Use for
 * admin or other prefixed routes.
 *
 * 	Routing.prefixes = array('admin', 'manager');
 *
 * Enables:
 * 	`admin_index()` and `/admin/controller/index`
 * 	`manager_index()` and `/manager/controller/index`
 */
//Configure::write('Routing.prefixes', array('admin'));

/**
 * Turn off all caching application-wide.
 */
//Configure::write('Cache.disable', true);

/**
 * Enable cache checking.
 *
 * If set to true, for view caching you must still use the controller
 * public $cacheAction inside your controllers to define caching settings.
 * You can either set it controller-wide by setting public $cacheAction = true,
 * or in each action using $this->cacheAction = true.
 */
//Configure::write('Cache.check', true);

/**
 * Enable cache view prefixes.
 *
 * If set it will be prepended to the cache name for view file caching. This is
 * helpful if you deploy the same application via multiple subdomains and languages,
 * for instance. Each version can then have its own view cache namespace.
 * Note: The final cache file name will then be `prefix_cachefilename`.
 */
//Configure::write('Cache.viewPrefix', 'prefix');

/**
 * Session configuration.
 *
 * Contains an array of settings to use for session configuration. The defaults key is
 * used to define a default preset to use for sessions, any settings declared here will override
 * the settings of the default config.
 *
 * ## Options
 *
 * - `Session.cookie` - The name of the cookie to use. Defaults to 'CAKEPHP'
 * - `Session.timeout` - The number of minutes you want sessions to live for. This timeout is handled by CakePHP
 * - `Session.cookieTimeout` - The number of minutes you want session cookies to live for.
 * - `Session.checkAgent` - Do you want the user agent to be checked when starting sessions? You might want to set the
 *    value to false, when dealing with older versions of IE, Chrome Frame or certain web-browsing devices and AJAX
 * - `Session.defaults` - The default configuration set to use as a basis for your session.
 *    There are four builtins: php, cake, cache, database.
 * - `Session.handler` - Can be used to enable a custom session handler. Expects an array of callables,
 *    that can be used with `session_save_handler`. Using this option will automatically add `session.save_handler`
 *    to the ini array.
 * - `Session.autoRegenerate` - Enabling this setting, turns on automatic renewal of sessions, and
 *    sessionids that change frequently. See CakeSession::$requestCountdown.
 * - `Session.cacheLimiter` - Configure the cache control headers used for the session cookie.
 *   See http://php.net/session_cache_limiter for accepted values.
 * - `Session.ini` - An associative array of additional ini values to set.
 *
 * The built in defaults are:
 *
 * - 'php' - Uses settings defined in your php.ini.
 * - 'cake' - Saves session files in CakePHP's /tmp directory.
 * - 'database' - Uses CakePHP's database sessions.
 * - 'cache' - Use the Cache class to save sessions.
 *
 * To define a custom session handler, save it at /app/Model/Datasource/Session/<name>.php.
 * Make sure the class implements `CakeSessionHandlerInterface` and set Session.handler to <name>
 *
 * To use database sessions, run the app/Config/Schema/sessions.php schema using
 * the cake shell command: cake schema create Sessions
 */
Configure::write('Session', array(
    'defaults' => 'php'
));

/**
 * A random string used in security hashing methods.
 */
Configure::write('Security.salt', 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi000');

/**
 * A random numeric string (digits only) used to encrypt/decrypt strings.
 */
Configure::write('Security.cipherSeed', '76859309657453542496749683645000');

/**
 * Apply timestamps with the last modified time to static assets (js, css, images).
 * Will append a query string parameter containing the time the file was modified. This is
 * useful for invalidating browser caches.
 *
 * Set to `true` to apply timestamps when debug > 0. Set to 'force' to always enable
 * timestamping regardless of debug value.
 */
//Configure::write('Asset.timestamp', true);

/**
 * Compress CSS output by removing comments, whitespace, repeating tags, etc.
 * This requires a/var/cache directory to be writable by the web server for caching.
 * and /vendors/csspp/csspp.php
 *
 * To use, prefix the CSS link URL with '/ccss/' instead of '/css/' or use HtmlHelper::css().
 */
//Configure::write('Asset.filter.css', 'css.php');

/**
 * Plug in your own custom JavaScript compressor by dropping a script in your webroot to handle the
 * output, and setting the config below to the name of the script.
 *
 * To use, prefix your JavaScript link URLs with '/cjs/' instead of '/js/' or use JsHelper::link().
 */
//Configure::write('Asset.filter.js', 'custom_javascript_output_filter.php');

/**
 * The class name and database used in CakePHP's
 * access control lists.
 */
Configure::write('Acl.classname', 'DbAcl');
Configure::write('Acl.database', 'default');

/**
 * Uncomment this line and correct your server timezone to fix
 * any date & time related errors.
 */
//date_default_timezone_set('UTC');

/**
 * `Config.timezone` is available in which you can set users' timezone string.
 * If a method of CakeTime class is called with $timezone parameter as null and `Config.timezone` is set,
 * then the value of `Config.timezone` will be used. This feature allows you to set users' timezone just
 * once instead of passing it each time in function calls.
 */
//Configure::write('Config.timezone', 'Europe/Paris');

/**
 * Cache Engine Configuration
 * Default settings provided below
 *
 * File storage engine.
 *
 * 	 Cache::config('default', array(
 * 		'engine' => 'File', //[required]
 * 		'duration' => 3600, //[optional]
 * 		'probability' => 100, //[optional]
 * 		'path' => CACHE, //[optional] use system tmp directory - remember to use absolute path
 * 		'prefix' => 'cake_', //[optional]  prefix every cache file with this string
 * 		'lock' => false, //[optional]  use file locking
 * 		'serialize' => true, //[optional]
 * 		'mask' => 0664, //[optional]
 * 	));
 *
 * APC (http://pecl.php.net/package/APC)
 *
 * 	 Cache::config('default', array(
 * 		'engine' => 'Apc', //[required]
 * 		'duration' => 3600, //[optional]
 * 		'probability' => 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * 	));
 *
 * Xcache (http://xcache.lighttpd.net/)
 *
 * 	 Cache::config('default', array(
 * 		'engine' => 'Xcache', //[required]
 * 		'duration' => 3600, //[optional]
 * 		'probability' => 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional] prefix every cache file with this string
 * 		'user' => 'user', //user from xcache.admin.user settings
 * 		'password' => 'password', //plaintext password (xcache.admin.pass)
 * 	));
 *
 * Memcached (http://www.danga.com/memcached/)
 *
 * Uses the memcached extension. See http://php.net/memcached
 *
 * 	 Cache::config('default', array(
 * 		'engine' => 'Memcached', //[required]
 * 		'duration' => 3600, //[optional]
 * 		'probability' => 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * 		'servers' => array(
 * 			'127.0.0.1:11211' // localhost, default port 11211
 * 		), //[optional]
 * 		'persistent' => 'my_connection', // [optional] The name of the persistent connection.
 * 		'compress' => false, // [optional] compress data in Memcached (slower, but uses less memory)
 * 	));
 *
 *  Wincache (http://php.net/wincache)
 *
 * 	 Cache::config('default', array(
 * 		'engine' => 'Wincache', //[required]
 * 		'duration' => 3600, //[optional]
 * 		'probability' => 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * 	));
 */
/**
 * Configure the cache handlers that CakePHP will use for internal
 * metadata like class maps, and model schema.
 *
 * By default File is used, but for improved performance you should use APC.
 *
 * Note: 'default' and other application caches should be configured in app/Config/bootstrap.php.
 *       Please check the comments in bootstrap.php for more info on the cache engines available
 *       and their settings.
 */
$engine = 'File';

// In development mode, caches should expire quickly.
$duration = '+999 days';
if (Configure::read('debug') > 0) {
    $duration = '+10 seconds';
}

// Prefix each application on the same server with a different string, to avoid Memcache and APC conflicts.
$prefix = 'myapp_';

/**
 * Configure the cache used for general framework caching. Path information,
 * object listings, and translation cache files are stored with this configuration.
 */
Cache::config('_cake_core_', array(
    'engine' => $engine,
    'prefix' => $prefix . 'cake_core_',
    'path' => CACHE . 'persistent' . DS,
    'serialize' => ($engine === 'File'),
    'duration' => $duration
));

/**
 * Configure the cache for model and datasource caches. This cache configuration
 * is used to store schema descriptions, and table listings in connections.
 */
Cache::config('_cake_model_', array(
    'engine' => $engine,
    'prefix' => $prefix . 'cake_model_',
    'path' => CACHE . 'models' . DS,
    'serialize' => ($engine === 'File'),
    'duration' => $duration
));

// 科研项目
Configure::write('keyanlist', array(
    array('data_fee' => '资料费', 'facility1' => '设备费1'),
    array('facility2' => '设备费2', 'facility3' => '设备费3'),
    array('material1' => '材料费1', 'material2' => '材料费2'),
    array('material3' => '材料费3', 'material4' => '材料费4'),
    array('assay' => '测试化验费', 'elding' => '燃料动力费'),
    array('publish' => '印刷、出版费', 'property_right' => '知识产权费'),
    array('travel' => '差旅费', 'meeting' => '会议、会务费'),
    array('cooperation' => '国内协作费', 'labour' => '劳务费'),
    array('consult' => '专家咨询费', 'other' => '其他费用'),
    array('indirect' => '间接费', 'train' => '培训费'),
    array('vehicle' => '车辆使用费', 'collection' => '采集费'),
));

// 行政项目
Configure::write('xizhenglist', array(
    array('data_fee' => '资料费', 'facility1' => '设备费1'),
    array('facility2' => '设备费2', 'facility3' => '设备费3'),
    array('material1' => '材料费1', 'material2' => '材料费2'),
    array('material3' => '材料费3', 'material4' => '材料费4'),
    array('assay' => '测试化验费', 'elding' => '燃料动力费'),
    array('publish' => '印刷、出版费', 'property_right' => '知识产权费'),
    array('travel' => '差旅费', 'meeting' => '会议、会务费'),
    array('cooperation' => '国内协作费', 'labour' => '劳务费'),
    array('consult' => '专家咨询费', 'other' => '其他费用'),
    array('indirect' => '间接费', 'train' => '培训费'),
    array('vehicle' => '车辆使用费', 'collection' => '采集费'),
));


// 起草申请
Configure::write('applylist', array(
    '人事科' => array('请假单' => '#', '差旅审批单' => '#', '调休申请表' => '#', '调整工作时间申请单' => '#',
        '田间作业包工申请表' => '#', '所内调动申请表' => '#', '所内调动移交表' => '#', '工作调动移交表' => '#',
        '职工离职移交表' => '#', '职工退休移交表' => '#', '年度考核登记表' => '#', '职工年休假安排计划' => '#',
        '职工调整年休假安排表' => '#', '职工带薪年休假审批表' => '#', '因公不休或不全休带薪休假审批表' => '#',),
    '财务科' => array('果树所借款单' => '#', '果树所领款单' => '#', '果树所差旅费报销单' => '#', '果树所报销汇总单' => '#'),
    '所办公室' => array('印信使用签批单' => '#', '所内公文' => '#', '来文' => '#', '发文' => '#'),
));

//定义审核的状态值 审核状态：1 行政审核未通过，2 行审通过，3 财务审核未通过，4 财审通过
    Configure::write('code_arr', array(
        0 => '未审核',
        1 => '行政审核未通过',
        2 => '行审通过',
        3 => '财务审核未通过',
        4 => '财审通过',
        ));

// 申请单 列表
    Configure::write('declaresList', array(
        '报销汇总单' => 'apply_baoxiaohuizong',
        ));    
    
    //报销单状态
    Configure::write('code_bxd_arr', array(
        0 => '未审核',
        1 => '项目负责人拒绝',
        2 => '项目负责人同意',
        3 => '项目组负责人拒绝',
        4 => '项目组负责人同意',
        5 => '科室负责人拒绝',
        6 => '科室负责人同意',
        7 => '分管所领导拒绝',
        8 => '分管所领导同意',
        9 => '所长拒绝',
        10 => '所长同意',
        11 => '分管财务所长拒绝',
        12 => '分管财务所长同意',
        13 => '财务科长拒绝',
        14 => '财务科长同意',
    ));