<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 讲师中心
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/teacherCenter.css?v=<?php  echo $versions;?>" rel="stylesheet" />

<div class="header-2 cbox">
	<a href="javascript:history.go(-1);" class="ico go-back"></a>
	<div class="flex title"><?php  echo $title;?></div>
</div>

<section class="aui-scrollView">
	<div class="aui-flex-box aui-panel">
		<a href="javascript:void(0);" class="aui-panel-cell">
			<div class="aui-panel-cell-hd">
				<img src="<?php  echo $avatar;?>" alt="头像">
			</div>
			<div class="aui-panel-cell-bd">
				<h4><?php  echo $teacher['nickname'];?></h4>
				<p><?php echo $font['joinTime'] ? $font['joinTime'] : '加入时间';?>：<?php  echo date('Y-m-d H:i', $teacher['addtime']);?></p>
			</div>
			<div class="aui-panel-cell-fr">
				<?php  if(empty($teacher)) { ?>
					未申请
				<?php  } else if($teacher['status']==-1) { ?>
					<span class="label label-default">未通过</span>
				<?php  } else if($teacher['status']==1) { ?>
					<span class="label label-success">通过</span>
				<?php  } else if($teacher['status']==2) { ?>
					<span class="label label-danger">审核中</span>
				<?php  } ?>
			</div>
		</a>
	</div>

	<div class="aui-flex-box">
		<div class="aui-flex-box-bd">
			<h2><?php echo $font['cashAmount'] ? $font['cashAmount'] : '可提现收入(元)';?></h2>
			<h3><?php  echo $member['nopay_lesson'];?></h3>
		</div>
		<div class="aui-flex-box-fr"></div>
	</div>

	<div class="aui-rankings">
		<a href="javascript:;" class="aui-rankings-item clearfix">
			<span><?php echo $font['countAmount'] ? $font['countAmount'] : '累计收入(元)';?>：</span>
			<span class="aui-rankings-title"><?php  echo sprintf("%.2f",$member['pay_lesson']+$member['nopay_lesson']);?></span>
		</a>
	</div>
	<div class="divHeight"></div>

    <div class="teache-menu"> 
        <a href="<?php  echo $this->createMobileUrl('teacher', array('teacherid'=>$teacher['id']));?>">
			<div class="nav nav1">
				<i class="ico ico-mylesson"></i>
				<div class="title">
					<?php echo $font['mylesson'] ? $font['mylesson'] : '我的课程';?>
				</div>
				<div class="con">
					<span><?php  echo $mylessoncount;?></span>节课程
				</div>
			</div>
		</a>
        <a href="<?php  echo $this->createMobileUrl('income');?>">
			<div class="nav nav2">
				<i class="ico ico-lesson-income"></i>
				<div class="title">
					<?php echo $font['incomeLog'] ? $font['incomeLog'] : '讲师收入';?>
				</div>
				<div class="con">
					<?php echo $font['incomeLog2'] ? $font['incomeLog2'] : '讲师收入明细';?>
				</div>
			</div>
		</a>
        <a href="<?php  echo $this->createMobileUrl('lessoncashlog');?>">
			<div class="nav nav3">
				<i class="ico ico-lessoncash-log"></i>
				<div class="title">
					<?php echo $font['cashLog'] ? $font['cashLog'] : '提现记录';?>
				</div>
				<div class="con">
					<?php echo $font['cashLog2'] ? $font['cashLog2'] : '我的提现记录';?>
				</div>
			</div>
		</a>        
    </div>
    <div class="teache-menu">
		<a href="<?php  echo $this->createMobileUrl('lessoncash', array('op'=>'display'));?>">
			<div class="nav nav1">
				<i class="ico ico-cash"></i>
				<div class="title">
					<?php echo $font['cash'] ? $font['cash'] : '申请提现';?>
				</div>
				<div class="con">
					<?php echo $font['cash2'] ? $font['cash2'] : '讲师收入申请提现';?>
				</div>
			</div>
		</a>
        <a href="<?php  echo $this->createMobileUrl('teachercenter', array('op'=>'account'));?>">
			<div class="nav nav1">
				<i class="ico ico-account"></i>
				<div class="title">
					<?php echo $font['account'] ? $font['account'] : '账号管理';?>
				</div>
				<div class="con">
					<?php echo $font['account2'] ? $font['account2'] : '登录讲师平台凭证';?>
				</div>
			</div>
		</a>
    </div>
</section>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>
