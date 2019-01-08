<?php if (!defined('__RESTER__')) exit;

rester::msg("Image Upload");

$key = rester::param('no');
$content = rester::param('content');

if(!rester::sql('page','update_content_html',['no'=>$key,'content'=>$content]))
{
    rester::failure();
    rester::msg("데이터베이스 입력 실패");
}

return true;
