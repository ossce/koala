<?php
set_time_limit(0);

if ($op == "display") {
    
    $pindex = max(1, intval($_GPC['page']));
    $psize = 15;
    
    $condition = " a.uniacid=:uniacid ";
    $params[':uniacid'] = $uniacid;
    
    $list = pdo_fetchall("SELECT a.id,a.lesson_type,a.pid,a.cid,a.bookname,a.price,a.buynum,a.stock,a.displayorder,a.status,a.section_status,a.vip_number,a.teacher_number,a.visit_number,b.teacher FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE {$condition} ORDER BY a.displayorder DESC,a.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
    
    foreach($list as $key=>$value){
    	$cat_id = $value['cid'] ? $value['cid'] : $value['pid'];
    	if($cat_id>0){
    		$list[$key]['category'] = pdo_fetch("SELECT name FROM " .tablename($this->table_category). " WHERE id=:id", array(':id'=>$cat_id));
    	}
    }
    
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " . tablename($this->table_teacher) . " b ON a.teacherid=b.id WHERE {$condition}", $params);
    $pager = pagination($total, $pindex, $psize);
    include $this->template("web/uplesson");
} elseif ($op == "tongkecheng") {
    load()->func('file');
	
	$upset = pdo_get($this->table_upset, array('uniacid'=>$_W['uniacid']));
	/*授权检测*/
    $url = "http://sqym.510zxc.co/addons/fy_lessonv2_plugin_supersync/tongbu.php?opt=checkauth";
    $authresponse = ihttp_request($url,'',array(
	    'CURLOPT_REFERER' => $_SERVER['SERVER_NAME']
	));
	$authcontent = json_decode($authresponse['content'], true);
	if(!$authcontent['code']){
	    message($authcontent['msg'], "", "error");
	}
	/*授权检测*/
	
	$pagesize  = $_GPC['amount'] ? $_GPC['amount'] : 10;
	$url = $upset['domain'] . "/addons/fy_lessonv2_plugin_supersync/tongbu.php?opt=getcourse&uniacid=".$upset['tongbu_uniacid']."&pagesize=".$pagesize;
	$response = ihttp_request($url,'',array(
	    'CURLOPT_REFERER' => $_SERVER['SERVER_NAME']
	));
	$content = json_decode($response['content'], true);
// 	print_r('<pre>');print_r($content);print_r('<pre>');exit;
	if(!$content['code']){
	    message($content['msg'], "", "error");
	}
	
    $lessonList = $content['data'];
    foreach ($lessonList as $key => $lesson) {
        /*同步分类数据1*/
        if($lesson['lesson_category1']){
            $new_lesson_category1 = creatNewData($upset,'fy_lesson_category',$lesson['lesson_category1'],$uniacid);
            $lesson['pid'] = $new_lesson_category1['id'];
        }
        
        /*同步分类数据2*/
        if($lesson['lesson_category2']){
            $new_lesson_category2 = creatNewData($upset,'fy_lesson_category',$lesson['lesson_category2'],$uniacid);
            $lesson['cid'] = $new_lesson_category2['id'];
        }
        
        /*同步课程讲师分类*/
        if($lesson['lesson_teacher'] && $lesson['lesson_teacher']['cate_id']){
            $new_lesson_teacher_category = creatNewData($upset,'fy_lesson_teacher_category',$lesson['lesson_teacher']['teacher_category'],$uniacid);
            $lesson['lesson_teacher']['cate_id'] = $new_lesson_teacher_category['id'];
        }
        
        /*同步课程讲师*/
        if($lesson['lesson_teacher']){
            $new_lesson_teacher = creatNewData($upset,'fy_lesson_teacher',$lesson['lesson_teacher'],$uniacid);
            $lesson['teacherid'] = $new_lesson_teacher['id'];
        }
        
        /*同步课程数据*/
        $new_lesson = creatNewData($upset,'fy_lesson_parent',$lesson,$uniacid);
        
        /*同步目录*/
        // $lesson_title = $lesson['lesson_title'];
        // foreach ($lesson_title as $k => $catalog) {
        //     $catalog['lesson_id'] = $new_lesson['id'];
        //     $new_catalog = creatNewData($upset,'fy_lesson_title',$catalog,$uniacid,'title_id');
            
        //     /*同步章节*/
        //     $lesson_son = $catalog['lesson_son'];
        //     foreach ($lesson_son as $kk => $chapter) {
        //         $chapter['parentid']  = $new_lesson['id'];
        //         $chapter['title_id'] = $new_catalog['title_id'];
        //         $new_catalog = creatNewData($upset,'fy_lesson_son',$chapter,$uniacid);
        //     }
        // }
        
        /*同步章节*/
        $lesson_son = $lesson['lesson_son'];
        foreach ($lesson_son as $k => $catalog) {
            if($catalog['lesson_title']){
                /*同步目录*/
                $catalog['lesson_title']['lesson_id'] = $new_lesson['id'];
                $new_lesson_title = creatNewData($upset,'fy_lesson_title',$catalog['lesson_title'],$uniacid,'title_id');
                $catalog['title_id'] = $new_lesson_title['title_id'];
            }
            /*同步章节*/
            $catalog['parentid'] = $new_lesson['id'];
            $new_lesson_son = creatNewData($upset,'fy_lesson_son',$catalog,$uniacid);
        }
        
        /*同步课程规格*/
        $lesson_spec = $lesson['lesson_spec'];
        foreach ($lesson_spec as $k => $spec) {
            $spec['lessonid'] = $new_lesson['id'];
            $new_spec = creatNewData($upset,'fy_lesson_spec',$spec,$uniacid,'spec_id');
        }
	}
	
    message('更新成功','referer','success');
}


function loadImage($imgurl){
    $parse_url = parse_url($imgurl);
    $pathinfo = pathinfo($imgurl);
    $ext = $pathinfo['extension'];
    if (!in_array($ext, array('png','jpg','jpeg'))){
        return false;
    }
        	    
    $filename = $pathinfo['basename'];
    if(strpos($imgurl,'attachment') !== false){ 
        $save_dir = IA_ROOT . str_replace($filename,"",$parse_url['path']);
    }else{
        $save_dir = IA_ROOT .'/attachment'. str_replace($filename,"",$parse_url['path']);
    }
    
    if(strpos($imgurl,'attachment') !== false){
        $save_file = IA_ROOT.$parse_url['path'];
    }else{
        $save_file = IA_ROOT.'/attachment'.$parse_url['path'];
    }
    
    if(!file_exists($save_file)){
        //创建保存目录
        if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
            return false;
        }
        $content = file_get_contents($imgurl);
        file_put_contents($save_file, $content);
        return true;
    }
    return true;
}

function getNewData($config,$table,$data,$uniacid,$key){
    if($table == 'fy_lesson_parent'){
        if($data['images_full']){
            loadImage($data['images_full']);
        }
        unset($data[$key]);
        unset($data['images_full']);
        unset($data['lesson_category1']);
        unset($data['lesson_category2']);
        unset($data['lesson_title']);
        unset($data['lesson_son']);
        unset($data['lesson_spec']);
        unset($data['lesson_teacher']);
    }

    if($table == 'fy_lesson_title'){
        unset($data[$key]);
    }
    
    if($table == 'fy_lesson_son'){
        unset($data[$key]);
        unset($data['lesson_title']);
    }
    
    if($table == 'fy_lesson_teacher_category'){
        unset($data[$key]);
    }
    
    if($table == 'fy_lesson_teacher'){
        unset($data[$key]);
        unset($data['teacher_category']);
    }
    
    if($table == 'fy_lesson_category'){
        if($data['ico_full']){
            loadImage($data['ico_full']);
        }
        unset($data[$key]);
        unset($data['ico_full']);
        /*存在父级*/
        if($data['parentid']){
            $new_parent_data = creatNewData($config,$table,$data['parent'],$uniacid,$key);
            $data['parentid'] = $new_parent_data[$key];
            unset($data['parent']);
        }
    }
    
    return $data;
}

function creatNewData($config,$table,$data,$uniacid,$key='id'){
    if(!$data){
        return false;
    }
    $data['uniacid'] = $uniacid;
    $tongbu_domain = parse_url($config['domain'])['host'];
    
    $tongbu_uniacid = $config['tongbu_uniacid'];
    $old_id = $data[$key];
    
    $log = pdo_get('fy_lesson_supersync_uplog', array(
        'uniacid' => $uniacid,
        'domain' => $tongbu_domain,
        'old_uniacid' => $tongbu_uniacid,
        'table' => $table,
        'old_id' => $old_id
    ));
    $new_data = getNewData($config,$table,$data,$uniacid,$key);
    
    if($log){
        /*更新数据*/
        $true_data = pdo_get($table, array(
            'uniacid' => $uniacid,
            $key => $log['new_id'],
        ));
        if($true_data){
            pdo_update($table, $new_data, array($key => $log['new_id']));
            $new_data[$key] = $log['new_id'];
        }else{
            pdo_insert($table, $new_data);
            $new_id = pdo_insertid();
            pdo_update('fy_lesson_supersync_uplog', array('new_id' => $new_id), array('id' => $log['id']));
            $new_data[$key] = $new_id;
        }
    }else{
        /*同步数据*/
        pdo_insert($table, $new_data);
        $new_id = pdo_insertid();
        /*同步分类日志*/
        pdo_insert('fy_lesson_supersync_uplog', array(
            'uniacid' => $uniacid,
            'domain' => $tongbu_domain,
            'old_uniacid' => $tongbu_uniacid,
            'table' => $table,
            'old_id' => $old_id,
            'new_id' => $new_id,
            'ctime' => date('Y-m-d h:i:s')
        ));
        $new_data[$key] = $new_id;
    }
    return $new_data;
}