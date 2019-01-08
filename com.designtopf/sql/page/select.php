<?php use rester\sql\db;
use rester\sql\rester;

if(!defined('__RESTER__')) exit;

$path = rester::param('path');
$type = rester::param('type');
if($path && $type)
{
    $query = <<<SQL
SELECT *
FROM `pages`
WHERE `pg_path`='{$path}'
AND `pg_type`='{$type}'
AND `pg_archive`=0
ORDER BY `pg_order` ASC 
SQL;
    $data = null;
    foreach(db::get()->query($query,PDO::FETCH_ASSOC) as $row)
    {
        $pg_config = @json_decode($row['pg_config'],true);
        if($pg_config!==null) $row['pg_config'] = $pg_config;
        $pg_content = @json_decode($row['pg_content'],true);
        if($pg_content!==null) $row['pg_content'] = $pg_content;
        $data[] = $row;
    }
    return $data;
}
else
{
    rester::failure();
    rester::msg("path 값이 필요합니다.");
    return null;
}


