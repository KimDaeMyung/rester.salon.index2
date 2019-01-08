<?php if (!defined('__RESTER__')) exit;

rester::msg("Get page contents");

$path = rester::param('path');

// 페이지 내용 불러오기
return rester::sql('page','select',array('path'=>$path));

//return $result;
