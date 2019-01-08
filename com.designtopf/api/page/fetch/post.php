<?php if (!defined('__RESTER__')) exit;


rester::msg("Get page contents");

$path = rester::param('path');

// 페이지 내용 불러오기
$pages = [];
foreach (rester::sql('page','select',['path'=>$path,'type'=>'front']) as $item)
{
    $_item = [];
    switch ($item['pg_kind'])
    {
        case 'image':
            $_f = new file($item['pg_content']);
            $_item['href_image'] = $_f->get_cdn_path();
            break;

        case 'list':
            if(sizeof($item['pg_content']['list'])==1) $_item['item_single'] = true;
            $_item['item_count'] = sizeof($item['pg_content']['list']);
            $list = [];
            foreach($item['pg_content']['list'] as $list_idx=>$v)
            {
                $_list = [];
                foreach($item['pg_config']['content'] as $vv)
                {
                    $_vv = [];
                    if($vv['type']=='image')
                    {
                        $_f = new file($v['image']);
                        $_vv['href_image'] = $_f->get_cdn_path();
                    }
                    else
                    {
                        $_vv = $v[$vv['name']];
                    }
                    $_list[$vv['name']] = $_vv;
                }
                $list[] = $_list;
            }
            $_item['list'] = $list;
            break;
        default:
            $_item = $item['pg_content'];
    }
    $pages[$item['pg_name']] = $_item;
}

return $pages;
