<?php

//前端控制器
class FormerController extends CController
{
	public $layout='//layouts/site';
	
	//輸出錯誤資訊
	protected function errorOutput($data = array())
	{
		echo json_encode($data);
		exit();
	} 
	
	//輸出資訊
	protected function output($data = array())
	{
		echo json_encode($data);
		exit();
	} 
}
