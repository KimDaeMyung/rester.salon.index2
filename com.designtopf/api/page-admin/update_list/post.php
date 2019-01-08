<?php if (!defined('__RESTER__')) exit;

rester::msg("Update Page List");

$key = rester::param('no');
$list = rester::param('list');

$old = rester::sql('page','fetch',['no'=>$key]);
$cfg = $old['pg_config']['content'];
$old_content = $old['pg_content']['list'];

// 이미지 설정 검사
$cfg_image_name = [];
foreach($cfg as $v)
{
    if($v['type']=='image') $cfg_image_name[] = $v['name'];
}

$file = new file();
$file_list = $file->upload('image');

// arrange data
$arranged_list = [];
foreach($list as $name=>$values)
{
    foreach($values as $idx=>$v)
    {
        $arranged_list[$idx][$name] = $v;
        if(is_array($file_list))
        {
            foreach($file_list as $img_name=>$img_list)
            {
                if($img_list[$idx]) $arranged_list[$idx][$img_name] = $img_list[$idx];
            }
        }
    }
}

// 이미지 정리
if(sizeof($cfg_image_name)>0)
{
    foreach($arranged_list as $idx=>$v)
    {
        foreach($cfg_image_name as $img_name)
        {
            // 이미지가 업로드 안되었다면 이전 설정으로
            if(!$v[$img_name])
            {
                $arranged_list[$idx][$img_name] = $old_content[$idx][$img_name];
            }
            // 이미지가 업로드 되었고 이전 이미지가 있을 경우 삭제
            elseif($old_content[$idx][$img_name])
            {
                $old_file = new file($old_content[$idx][$img_name]);
                $old_file->delete();
            }

        }
        unset($old_content[$idx]);
    }

    // 남은 파일 제거
    foreach ($old_content as $old)
    {
        foreach($old as $name=>$v)
        {
            if($v['file_datetime'])
            {
                $old_file = new file($v);
                $old_file->delete();
            }
        }
    }
}

$data = ['list'=>array_values($arranged_list)];

if(!rester::sql('page','update_content',['no'=>$key,'content'=>json_encode($data)]))
{
    rester::failure();
    rester::msg("데이터베이스 입력 실패");
}

return true;
