<?php

$list = pdo_fetchall("SELECT count(1),uid FROM " .tablename($this->table_member)." GROUP BY uid HAVING count(uid) >=2");
if(!empty($list)){
	$i = 0;
	foreach($list as $k=>$v){
		$items = pdo_fetchall("SELECT id,uid FROM " .tablename($this->table_member)." WHERE uid={$v['uid']} ORDER BY id DESC");
		$number = count($items);
		if($number < 2){
			continue;
		}
		foreach($items as $k1=>$v1){
			if($k1 < $number-1){
				pdo_delete($this->table_member, array('id'=>$v1['id']));
				$i++;
			}
		}
	}

	echo '共处理记录：'.$i;
	exit();
}else{
	if (pdo_indexexists('fy_lesson_member', 'uid')) {
		pdo_query("ALTER TABLE  " . tablename('fy_lesson_member') . " DROP INDEX `uid`;");
	}
	if (pdo_indexexists('fy_lesson_member', 'idx_uid')) {
		pdo_query("ALTER TABLE  " . tablename('fy_lesson_member') . " DROP INDEX `idx_uid`;");
	}else{
		pdo_query("ALTER TABLE  " . tablename('fy_lesson_member') . " ADD UNIQUE `idx_uid`(`uid`) COMMENT '';");
	}

	echo '处理结束：已创建索引idx_uid';
	exit();
}


?>