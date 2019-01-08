<?php if (!defined('__RESTER__')) exit;

rester::msg("Page content update (string)");

$key = rester::param('no');
$list = rester::param('string');

if(!rester::sql('page','update_content',['no'=>$key,'content'=>json_encode($list)]))
{
    rester::failure();
    rester::msg("데이터베이스 입력 실패");
}

return true;
