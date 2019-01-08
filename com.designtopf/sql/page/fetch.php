<?php if(!defined('__RESTER__')) exit;

use rester\sql\db;
use rester\sql\rester;

$key = rester::param('no');
if($key)
{
    $query = <<<SQL
SELECT *
FROM `pages`
WHERE pg_no='{$key}'
LIMIT 1 
SQL;
    $stmt = db::get()->prepare($query);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $pg_config = @json_decode($data['pg_config'],true);
    if($pg_config!==null) $data['pg_config'] = $pg_config;
    $pg_content = @json_decode($data['pg_content'],true);
    if($pg_content!==null) $data['pg_content'] = $pg_content;
    return $data;
}
else
{
    rester::failure();
    rester::msg("path 값이 필요합니다.");
    return null;
}


