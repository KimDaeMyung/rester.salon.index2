<?php if (!defined('__RESTER__')) exit;

$http_host = cfg::Get('default','http_host');

rester::msg("Get page contents");

$path = rester::param('path');
$type = 'front';
// 페이지 수정일 때
// 관리자 페이지 pages/ 경로 제거
if(strpos($path,'pages/')!==false)
{
    $path = str_replace('pages/','',$path);
}
else
{
    $type = 'admin';
}

// 페이지 내용 불러오기
$pages = [];
foreach (rester::sql('page','select',['path'=>$path,'type'=>$type]) as $item)
{
    switch ($item['pg_kind'])
    {
        case 'text':
            $item['href_action'] = $http_host.'/v1/page-admin/update_text?no='.$item['pg_no'];
            break;

        case 'image':
            $_f = new file($item['pg_content']);
            $item['href_image'] = $_f->get_cdn_path();
            $item['href_action'] = $http_host.'/v1/page-admin/update_image?no='.$item['pg_no'];
            break;

        case 'string':
            $item['href_action'] = $http_host.'/v1/page-admin/update_string?no='.$item['pg_no'];
            $list = [];
            foreach($item['pg_content'] as $name=>$value)
            {
                $_list = [];
                foreach($item['pg_config']['content'] as $vv)
                {
                    if($name==$vv['name'])
                    {
                        $vv['value'] = $value;
                        $_list[] = $vv;
                    }
                }
                $list[] = $_list;
            }
            // 목록이 없을 때
            if(sizeof($list)==0)
            {
                $list[] = $item['pg_config']['content'];
            }
            $item['pg_content'] = $list;
            break;

        case 'list':
            $item['href_action'] = $http_host.'/v1/page-admin/update_list?no='.$item['pg_no'];
            $list = [];
            foreach($item['pg_content']['list'] as $list_idx=>$v)
            {
                $_list = [];
                foreach($item['pg_config']['content'] as $vv)
                {
                    if($vv['type']=='image')
                    {
                        $_f = new file($v['image']);
                        $vv['thumb'] = [
                            'href_thumb'=> $_f->get_cdn_path(),
                            'href_image'=> $_f->get_cdn_path(),
                        ];
                    }
                    $vv['idx'] = $list_idx;
                    $vv['value'] = $v[$vv['name']];
                    $vv['type_'.$vv['type']] = true;
                    $_list[] = $vv;
                }
                $list[] = $_list;
            }
            // 목록이 없을 때
            if(sizeof($list)==0)
            {
                $_list = [];
                foreach($item['pg_config']['content'] as $vv)
                {
                    $vv['type_'.$vv['type']] = true;
                    $_list[] = $vv;
                }
                $list[] = $_list;
            }
            $item['pg_content'] = $list;
            break;
    }

    $item['pg_display_name'] = $item['pg_display_name']?$item['pg_display_name']:$item['pg_name'];
    $pg = [
        'name'=>$item['pg_name'],
        'display-name'=>$item['pg_display_name']?$item['pg_display_name']:$item['pg_name'],
        'rester-skin'=>true,
        'rester-skin-name'=>'pages-'.$item['pg_kind'],
        'rester-skin-contents'=> $item
    ];
    $pages[$item['pg_name']] = $pg;
}

return $pages;
