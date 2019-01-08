<?php use rester\sql\db;
use rester\sql\rester;

if(!defined('__RESTER__')) exit;

$key = rester::param('no');
$content = addslashes(rester::param('content'));

$query = <<<SQL
    UPDATE `pages`
    SET `pg_content`='{$content}'
    WHERE `pg_no`={$key}
    LIMIT 1
SQL;

if($key && db::get()->prepare($query)->execute())
{
}
else
{
    rester::failure();
    rester::msg("key 값이 필요합니다.");
    return null;
}


