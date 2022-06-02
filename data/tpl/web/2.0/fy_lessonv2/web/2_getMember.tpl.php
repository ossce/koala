<?php defined('IN_IA') or exit('Access Denied');?><table class="table table-hover select-member-table mar-b-0">
	<tbody>
		<tr>
			<th>用户uid</th>
			<th>昵称</th>
			<th>姓名</th>
			<th>手机号码</th>
			<th>操作</th>
		</tr>
		<?php  if(is_array($list)) { foreach($list as $row) { ?>
		<tr>
			<td><?php  echo $row['uid'];?></td>
			<td>
				<img src="<?php  echo $row['avatar'];?>" style="width:30px;height:30px;padding:1px;border:1px solid #ccc;"/> <?php  echo $row['nickname'];?>
			</td>
			<td><?php  echo $row['realname'];?></td>
			<td><?php  echo $row['mobile'];?></td>
			<td style="width:80px;">
				<a href="javascript:;" data-nickname="<?php  echo $row['nickname'];?>" data-avatar="<?php  echo $row['avatar'];?>" data-uid="<?php  echo $row['uid'];?>" onclick="selectMember(this);" data-dismiss="modal">选择</a>
			</td>
		</tr>
		<?php  } } ?>
	</tbody>
</table>
<span class="hide member_curr_page"><?php  echo $pindex;?></span><span class="hide member_total_page"><?php  echo $total_page;?></span>