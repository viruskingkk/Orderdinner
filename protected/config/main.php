<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'訂單系統',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.helps.*',	
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		'api',//用於用戶端訪問的介面
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			//'loginUrl'=>array('user/login'),
		),
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=dinner',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => 'liv_',
		),
		
		'errorHandler'=>array(
			'errorAction'=>'error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
				
			),
		),
		'smarty'=>array(                                               
			'class' => 'application.extensions.smarty.CSmarty',                       
		),
		'curl'=>array(                                               
			'class' => 'application.extensions.Curl',                       
		),
		'material'=>array(                                               
			'class' => 'application.extensions.MaterialUpload',
			'dirPath' => 'application.uploads',              
		),
		'check_time'=>array(                                               
			'class' => 'application.extensions.CheckTime',
		),
		'record'=>array(                                               
			'class' => 'application.extensions.Record',
		),
		'menu_upload'=>array(                                               
			'class' => 'application.extensions.MenuUpload',
			'dirPath' => 'application.uploads',              
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'app_version' => '1.0.0',//用戶端的版本
		'login_expire_time' => 3600 * 24 * 30,//用戶端登陸過期時間
		//允許帳戶餘額不足可以下單的用戶id
		'allow_user_id' => array(67,32,34,36,71),
		'record_money' => array(
			0 => '扣款',
			1 => '充值',
		),
		'status_color' => array(
			1 => 'red',
			2 => 'blue',
			3 => 'green',
			4 => '#EA0CF3',
		),
		//會員充值金額配置
		'member_recharge' => array(
			1 => 1,
			2 => 5,
			3 => 10,
			4 => 30,
			5 => 50,
			6 => 100,
			7 => 200,
			8 => 300,
			9 => 400,
			10 => 500,
			11 => 600,
			12 => 700,
			13 => 800,
			14 => 900,
			15 => 1000,
			16 => 1500,
			17 => 2000,
			18 => 99999,
		),
		//留言狀態
		'message_status' => array(
			1 => '已審核',
			2 => '被打回',
		),
		//使用者狀態
		'member_status' => array(
			1 => '儲值',
			2 => '現金',
		),
		//公告狀態
		'notice_status' => array(
			1 => '待發佈',
			2 => '已發佈',
			3 => '被打回',
		),
		//商家的狀態
		'shop_status' => array(
			1 => '待上市',
			2 => '已上市',
			3 => '被下市',
		),
		//菜的狀態
		'menu_status' => array(
			1 => '待上架',
			2 => '已上架',
			3 => '被下架',
		),
		'member_sex' => array(
			0 => '男',
			1 => '女',
		),
		//訂單狀態
		'order_status' => array(
			1 => '待付款',
			2 => '已付款',
			3 => '使用者取消訂單',
			4 => '女神取消訂單',
		),
		'homeIndexPic' => 'home.jpg',
		'material_path' => 'application.uploads',
		'img_url' => '../dinner/protected/uploads/',
		'pagesize' => 10,//配置後臺分頁顯示的個數
		'menu' => array(
						   	array(
						        'zh_name'       => '首頁',
						        'en_name'       => 'home',
						        'link'          => '#',
						        'child'  		=>  array(
												   			array(
													             'zh_name'       => '首頁',
														         'en_name'       => 'home',
														         'link'          => 'index', 
												   				),
						       							 ),  
						    ),
						    array(
						        'zh_name'       => '管理功能',
						        'en_name'       => 'menu_manger',
						        'link'          => '#',    
						        'child'  		=>  array(
						    								array(
														        'zh_name'       => '菜單管理',
														        'en_name'       => 'menus_manger',
														        'link'          => 'menus',  
						    								), 
						    								
						    								array(
														        'zh_name'       => '菜系管理',
														        'en_name'       => 'menu_sort_manger',
														        'link'          => 'foodsort',  
						    								), 
						    								
						    								array(
														        'zh_name'       => '用戶管理',
														        'en_name'       => 'members_mamger',
														        'link'          => 'members',  
						    								),
						    								array(
														        'zh_name'       => '商家管理',
														        'en_name'       => 'shops_manger',
														        'link'          => 'shops',  
						    								), 
						    								array(
														        'zh_name'       => '訂單管理',
														        'en_name'       => 'order_manger',
														        'link'          => 'foodorder',  
						    								),
						    								array(
														        'zh_name'       => '公告管理',
														        'en_name'       => 'announcement_manger',
														        'link'          => 'announcement',  
						    								),
						    								array(
														        'zh_name'       => '留言管理',
														        'en_name'       => 'message_manger',
														        'link'          => 'message',
						    								),
												        ),            
						    ),
						    array(
						        'zh_name'       => '系統設置',
						        'en_name'       => 'system_seeting',
						        'link'          => '#',    
						        'child'  		=>  array(
						    								array(
														        'zh_name'       => '素材管理',
														        'en_name'       => 'material_manger',
														        'link'          => 'material',  
						    								), 
						    								array(
														        'zh_name'       => '點餐時間',
														        'en_name'       => 'dinnertime_manger',
														        'link'          => 'timeconfig',  
						    								), 
												        ),            
						    ),
						),
	
	),
);
