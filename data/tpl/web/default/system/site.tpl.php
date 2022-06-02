<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div id="system-site-setting" ng-controller="systemSiteSettingCtrl" ng-cloak>
	<ul class="we7-page-tab">
		<li<?php  if($do == 'basic') { ?> class="active"<?php  } ?>><a href="<?php  echo url('system/site/basic');?>">基本信息</a></li>
		<li<?php  if($do == 'icps') { ?> class="active"<?php  } ?>><a href="<?php  echo url('system/site/icps');?>">备案执照</a></li>
		
		<li<?php  if($do == 'copyright') { ?> class="active"<?php  } ?>><a href="<?php  echo url('system/site/copyright');?>">SEO</a></li>
		<li<?php  if($do == 'about') { ?> class="active"<?php  } ?>><a href="<?php  echo url('system/site/about');?>">关于我们</a></li>
	    <li <?php  if($do == 'sms') { ?>class="active"<?php  } ?>><a href="<?php  echo url('system/site/sms');?>">短信配置</a></li>
		
		
	</ul>
	<div class="clearfix">
		<div class="form-files-box">
			<?php  if($do == 'basic') { ?>
			<div>
				<!-- 基本设置 start -->
				<div class="form-files we7-margin-bottom">
					<div class="form-file header">基本设置</div>
					<!-- 站点开关 start -->
					<div class="form-file">
						<div class="form-label">关闭站点</div>
						<div class="form-value"></div>
						<div class="form-edit">
							<label>
								<div ng-class="settings.status == undefined || settings.status == 0 ? 'switch' : 'switch switchOn'"  ng-click="saveSettingSwitch('status', settings.status)"></div>
							</label>
						</div>
					</div>
					<!-- 站点开关 end -->

					<!-- 关闭原因 start -->
					<div class="form-file">
						<div class="form-label">关闭原因</div>
						<div class="form-value" ng-bind="settings.reason"></div>
						<div class="form-edit">
							<we7-modal-form label="'关闭原因'" on-confirm="saveSetting(formValue, 'reason', 'string')" value="settings['reason']"></we7-modal-form>
						</div>
					</div>
					<!-- 关闭原因 end -->

					<!-- 自动退出 start -->
					<div class="form-file">
						<div class="form-label">自动退出</div>
						<div class="form-value" ng-bind="settings.autosignout_notice"></div>
						<div class="form-edit">
							<a href="#autosignout" data-toggle="modal" data-target="#autosignout">修改</a>
						</div>
						<div class="modal fade modal-form" id="autosignout" role="dialog">
							<div class="we7-modal-dialog modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
										<div class="modal-title">设置自动退出时间</div>
									</div>
									<div class="modal-body we7-form">
										<div class="alert alert-info we7-page-alert">
											<p><i class="wi wi-info-sign"></i>登录状态下，若长时间无操作系统将自动退出。</p>
										</div>
										<div class="form-group" ng-init="autosignout = settings.autosignout; autosignoutCopy = autosignout">
											<input type="radio" id="autosignout1" value="30" ng-change="autosignoutCopy = autosignout" ng-model="autosignout">
											<label for="autosignout1">30分钟退出 <span class="active"></span></label>
											<input type="radio" id="autosignout2" ng-change="autosignoutCopy = autosignout" value="60" ng-model="autosignout" >
											<label for="autosignout2">1小时退出<span></span></label>
											<input type="radio" id="autosignout3" ng-change="autosignoutCopy = autosignout" value="180" ng-model="autosignout" >
											<label for="autosignout3">3小时退出<span></span></label>
											<input type="radio" id="autosignout4" ng-change="autosignoutCopy = autosignout" value="" ng-model="autosignout" ng-checked="autosignout != 30 && autosignout != 60 && autosignout != 180">
											<label for="autosignout4">自行设置<span></span></label>
										</div>
										<div class="form-group marbot0" ng-show="autosignout != 30 && autosignout != 60 && autosignout != 180">
											<!-- <label class="col-sm-2 co	ntrol-label">备案号</label> -->
											<div class="input-group" >
												<input type="text" ng-model="autosignoutCopy" class="form-control" placeholder="自动退出时间" autocomplete="off" >
												<span class="input-group-addon">
													分钟
												</span>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-primary" ng-click="saveSetting({value: autosignoutCopy}, 'autosignout', 'int')" data-dismiss="modal">确定</button>
										<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- 自动退出 end -->
					
					<!-- 是否显示首页 start -->
					<div class="form-file">
						<div class="form-label">是否显示首页</div>
						<div class="form-value">设置“否”后，打开地址时将直接跳转到登录页面，否则会跳转到首页。</div>
						<div class="form-edit">
							<div ng-class="settings.showhomepage == undefined || settings.showhomepage == 0 ? 'switch' : 'switch switchOn'"  ng-click="saveSettingSwitch('showhomepage', settings.showhomepage)"></div>
						</div>
					</div>
					<!-- 是否显示首页 end -->
					
					<!-- 调试模式 start -->
					<div class="form-file">
						<div class="form-label">调试模式</div>
						<div class="form-value"></div>
						<div class="form-edit">
							<div ng-class="settings.develop_status == undefined || settings.develop_status == 0 ? 'switch' : 'switch switchOn'"  ng-click="saveSettingSwitch('develop_status', settings.develop_status)"></div>
						</div>
					</div>
					<!-- 调试模式 end -->

					<!-- 日志开关 start -->
					<div class="form-file">
						<div class="form-label">日志开关</div>
						<div class="form-value"></div>
						<div class="form-edit">
							<div ng-class="settings.log_status == undefined || settings.log_status == 0 ? 'switch' : 'switch switchOn'"  ng-click="saveSettingSwitch('log_status', settings.log_status)"></div>
						</div>
					</div>
					<!-- 日志开关 end -->

					<!-- 异地登录验证 start -->
					<div class="form-file">
						<div class="form-label">异地登录验证</div>
						<div class="form-value">{{'<?php  echo $_W['user']['mobile'];?>' | mobile}}</div>
						<div class="form-edit">
							<div ng-class="settings.login_verify_status == undefined || settings.login_verify_status == 0 ? 'switch' : 'switch switchOn'" ng-click="saveSettingSwitch('login_verify_status', settings.login_verify_status, '<?php  echo $_W['user']['mobile'];?>')"></div>
						</div>
						<div class="modal fade modal-form" id="login_verify" role="dialog">
							<div class="we7-modal-dialog modal-dialog modal-tip">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									</div>
									<div class="modal-body ">
										<div class="text-center">
											<i class="text-info wi wi-info"></i>
											<p class="title">系统提示</p>
											<p class="content">您的账号还未绑定手机号，请先绑定手机号后，再开启此功能</p>
										</div>
									</div>
									<div class="modal-footer">
										<a href="<?php  echo url('user/profile', array('user_type' => 4));?>" class="btn btn-primary" >前去设置</a>
										<button type="button" class="btn btn-default" data-dismiss="modal">确认</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- 异地登录验证 end -->
				</div>
				<!-- 基本设置 end -->

				<!-- 站点风格 start -->
				
				<div class="form-files">
					<div class="form-file header">站点风格</div>
					<!-- 左侧菜单定位 start -->
					<div class="form-file">
						<div class="form-label">左侧菜单定位</div>
						<div class="form-value"></div>
						<div class="form-edit">
							<div ng-class="settings.leftmenufixed == undefined || settings.leftmenufixed == 0 ? 'switch' : 'switch switchOn'"  ng-click="saveSettingSwitch('leftmenufixed', settings.leftmenufixed)"></div>
						</div>
					</div>
					<!-- 左侧菜单定位 end -->

					<!-- 后台风格设置 start -->
					<div class="form-file">
						<div class="form-label">后台风格设置</div>
						<div class="form-value"><?php  echo $template_ch_name[$_W['setting']['basic']['template']];?></div>
						<div class="form-edit">
							<we7-modal-form type="'select'" label="'后台风格设置'" on-confirm="saveSetting(formValue, 'template', 'string')" value="'<?php  echo $_W['setting']['basic']['template'];?>'" options="template_ch_name"></we7-modal-form>
						</div>
					</div>
					<!-- 后台风格设置 end -->
					

					
					<!-- 后台登录风格设置 start -->
					<div class="form-file">
						<div class="form-label">登录页风格</div>
						<div class="form-value"><?php  echo $login_ch_name[$_W['setting']['basic']['login_template']];?></div>
						<div class="form-edit">
							<we7-modal-form type="'select'" label="'登录页风格设置'" on-confirm="saveSetting(formValue, 'login_template', 'string')" value="'<?php  echo $_W['setting']['basic']['login_template'];?>'" options="login_ch_name"></we7-modal-form>
						</div>
					</div>
					<!-- 后台登录风格设置 end -->
					
					
					<!-- 缩略图标 start -->
					<div class="form-file">
						<div class="form-label" >favorite icon</div>
						<div class="form-value">
							<img src="<?php  echo to_global_media($settings['icon'])?>" alt="">
							<span class="we7-margin-left">建议尺寸16*16px,是指显示在浏览器地址栏,收藏夹或者导航条的图标</span>
						</div>
						<div class="form-edit">
							<a href="javascript:;" ng-click="changePicture('icon')">修改</a>
						</div>
					</div>
					<!-- 缩略图标 end -->

					<!-- 前台LOGO start -->
					<div class="form-file">
						<div class="form-label" >LOGO</div>
						<div class="form-value">
							<img src="<?php  echo to_global_media($settings['flogo'])?>" alt="">
							<span class="we7-margin-left">建议尺寸 48*24px, 此logo是指登录/注册页面左上角的图标</span>
						</div>
						<div class="form-edit">
							<a href="javascript:;" ng-click="changePicture('flogo')">修改</a>
						</div>
					</div>
					<!-- 前台LOGO start -->

					<!-- 幻灯片LOGO start -->
					<div class="form-file">
						<div class="form-label" >首页LOGO</div>
						<div class="form-value">
							<img src="<?php  echo to_global_media($settings['slide_logo'])?>" alt="">
							<span class="we7-margin-left">建议尺寸 220*50px, 此logo是指开启了首页后, 首页幻灯片上的图标</span>
						</div>
						<div class="form-edit">
							<a href="javascript:;" ng-click="changePicture('slide_logo')">修改</a>
						</div>
					</div>
					<!-- 幻灯片LOGO end -->

					<!-- 前台幻灯片 start -->
					<div class="form-file">
						<div class="form-label" >首页幻灯片</div>
						<div class="form-value">
							<?php  if(is_array($settings['slides'])) { foreach($settings['slides'] as $slide) { ?>
							<img src="<?php  echo to_global_media($slide)?>" alt="">
							<?php  } } ?>
							<span class="we7-margin-left">建议尺寸 1920*650px, 数量 <= 5张</span>
						</div>
						<div class="form-edit">
							<a href="javascript:;" ng-click="changePicture('slides', true)">修改</a>
						</div>
					</div>
					<!-- 前台幻灯片 start -->

					<!-- 前台幻灯片显示文字 start -->
					<div class="form-file">
						<div class="form-label">前台幻灯片显示文字</div>
						<div class="form-value" ng-bind="settings.notice"></div>
						<div class="form-edit">
							<we7-modal-form label="'前台幻灯片显示文字'" on-confirm="saveSetting(formValue, 'notice', 'string')" value="settings.notice"></we7-modal-form>
						</div>
					</div>
					<!-- 前台幻灯片显示文字 end -->

					<!-- 背景图片 start -->
					<div class="form-file">
						<div class="form-label" >登录页背景图</div>
						<div class="form-value">
							<img src="<?php  echo to_global_media($settings['background_img'])?>" alt="">
							<span class="we7-margin-left">设置提示：1.建议尺寸：1920px*1080px，系统按比例裁切；2.背景上展示内容，内容尽量显示在图片中间确保能够正常显示，效果自行调整</span>
							<!-- <div class="help-block">背景图片</div> -->
						</div>
						<div class="form-edit">
							<a href="javascript:;" ng-click="changeDefault('background_img')">恢复默认</a>
							<a href="javascript:;" ng-click="changePicture('background_img')">修改</a>
						</div>
					</div>
					<!-- 背景图片 start -->

					<!-- 后台LOGO start -->
					<div class="form-file">
						<div class="form-label" >后台LOGO</div>
						<div class="form-value">
							<img src="<?php  echo to_global_media($settings['blogo'])?>" alt="">
							<span class="we7-margin-left">此logo是指登录后在本系统左上角显示的logo。(最佳尺寸：宽度最宽为60px
								)</span>
						</div>
						<div class="form-edit">
							<a href="javascript:;" ng-click="changePicture('blogo')">修改</a>
						</div>
					</div>
					<!-- 后台LOGO start -->

					<!-- 第三方统计代码 start -->
					<div class="form-file">
						<div class="form-label">第三方统计代码</div>
						<div class="form-value"><span ng-bind="settings.statcode"></span><span class="we7-margin-left">只支持百度统计和QQ统计</span></div>
						<div class="form-edit">
							<we7-modal-form label="'第三方统计代码'" type="'textarea'" on-confirm="saveSetting(formValue, 'statcode', 'string')" value="settings.statcode"></we7-modal-form>
						</div>
					</div>
					<!-- 第三方统计代码 end -->

					<!-- 底部右侧信息（上 start -->
					<div class="form-file">
						<div class="form-label">底部右侧信息（上）</div>
						<div class="form-value" ng-bind="settings.footerright ? settings.footerright : '微信开发 微信应用 系统论坛 联系客服'"></div>
						<div class="form-edit">
							<we7-modal-form label="'底部右侧信息（上）'" rows="10" type="'textarea'" on-confirm="saveSetting(formValue, 'footerright', 'string')" value="settings.footerright"></we7-modal-form>
						</div>
					</div>
					<!-- 底部右侧信息（上 end -->

					<!-- 底部左侧信息（下 start -->
					<div class="form-file">
						<div class="form-label">底部左侧信息（下）</div>
						<div class="form-value" ng-bind="settings.footerleft ? settings.footerleft : 'Powered by 系统 v2.0.0 © 2014-2018'"></div>
						<div class="form-edit">
							<we7-modal-form label="'底部左侧信息（下）'" rows="10" type="'textarea'" on-confirm="saveSetting(formValue, 'footerleft', 'string')" value="settings.footerleft"></we7-modal-form>
						</div>
					</div>
					<!-- 底部左侧信息（下 end -->
				</div>
				
				<!-- 站点风格 end -->
			</div>
			<?php  } ?>
			<?php  if($do == 'icps') { ?>
			<div class="search-box we7-margin-bottom">
				<form action="" method="get" class="search-form" role="form">
					<input type="hidden" name="c" value="system">
					<input type="hidden" name="a" value="site">
					<input type="hidden" name="do" value="icps">
					<div class="input-group col-sm-4 pull-left">
						<input class="form-control" name="keyword" placeholder="" id="" type="text" value="<?php  echo $_GPC['keyword'];?>">
						<div class="input-group-btn">
							<button class="btn btn-default"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</form>
				<a class="btn btn-primary we7-margin-left" ng-click="addIcps()">+添加</a>
			</div>
			<table class="table we7-table table-hover">
				<tr>
					<th style="width:100px;">域名地址</th>
					<th style="width:100px;">ICP备案号</th>
					<th style="width:100px;">公安联网备案</th>
					<th style="width:300px;">电子执照</th>
					<th style="width:150px;">操作</th>
				</tr>
				<tr ng-repeat="(key, icp) in icps" ng-if="icps">
					<td ng-bind="icp.domain"></td>
					<td ng-bind="icp.icp"></td>
					<td>{{icp.policeicp_location}} {{icp.policeicp_code}}号</td>
					<td ng-bind="icp.electronic_license"></td>
					<td>
						<div class="link-group">
							<a href="javascript:;" ng-click="editIcp(key)">修改</a>
							<we7-modal-tip on-confirm="deleteIcp(icp.id)" content="'确认删除吗?'"><a href="javascript:;" >删除</a></we7-modal-tip>
						</div>
					</td>
				</tr>
			</table>
			<div class="we7-empty-block we7-margin-top" style="line-height: 20px;" ng-if="icps | we7IsEmpty" >
				<p  class="color-dark"><i class="wi wi-info color-default font-lg vertical-middle"></i>暂无添加的域名ICP备案号</p>
				<p>目前针对网站备案问题严查，需要根据域名显示不同的备案号，建议暂无备案的请及时完善信息。</p>
				<a class="color-default" href="javascript:;" ng-click="addIcps()">立即添加></a>
			</div>
			<div class="text-right">
				<?php  echo $pager;?>
			</div>
			<div class="modal fade bs-example-modal-sm" id="add-icps" tabindex="-1" style="z-index:1039" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog we7-modal-dialog ">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title">添加备案/执照信息</h4>
						</div>
						<div class="modal-body overflow-auto">
							<form action="" method="post" name="icps_form" enctype="multipart/form-data" class="we7-form form" >
								<div class="icp-list">
									<div class="color-gray we7-padding-left we7-margin-top-sm">网站链接（必填）</div>
									<div class="icp-item">
										<div class="form-box">
											<div class="form-group">
												<label class="col-sm-2 control-label">域名</label>
												<div class="col-sm-10">
													<input type="text" name="domain" required min="0" ng-model="currentIcp.domain" class="form-control" placeholder="请填写域名">
												</div>
											</div>
										</div>
									</div>
									<div class="color-gray we7-padding-left we7-margin-top-sm">ICP备案</div>
									<div class="icp-item">
										<div class="form-box">
											<div class="form-group">
												<label class="col-sm-2 control-label">备案号</label>
												<div class="col-sm-10">
													<input type="text"  min="0" name="icp" ng-model="currentIcp.icp" class="form-control" placeholder="请填写备案号">
												</div>
											</div>
										</div>
									</div>
									<div class="color-gray we7-padding-left we7-margin-top-sm">联网备案</div>
									<div class="icp-item">
										<div class="form-box">
											<div class="form-group we7-margin-bottom">
												<label class="col-sm-2 control-label">备案地</label>
												<div class="col-sm-10">
													<input type="text"  min="0" name="icp" ng-model="currentIcp.policeicp_location" class="form-control" placeholder="例如：京公网安备">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label">备案号</label>
												<div class="col-sm-10">
													<input type="text"  min="0" name="icp" ng-model="currentIcp.policeicp_code" class="form-control" placeholder="例如：1100000200001">
												</div>
											</div>
										</div>
									</div>
									<div class="color-gray we7-padding-left we7-margin-top-sm">电子执照亮照</div>
									<div class="icp-item">
										<div class="form-box">
											<div class="form-group">
												<label class="col-sm-2 control-label">链接</label>
												<div class="col-sm-10">
													<input type="text"  min="0" name="icp" placeholder="亮照链接" ng-model="currentIcp.electronic_license" class="form-control">
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" name="submit" value="保存" ng-disabled="icps_form.$pristine&&icps_form.$invalid " ng-click="saveIcps()">保存</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade bs-example-modal-sm" id="edit-icp" tabindex="-1" style="z-index:1039" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog we7-modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title">修改备案/执照信息</h4>
						</div>
						<div class="modal-body">
							<form action="" method="post" name="icps_edit" enctype="multipart/form-data" class="we7-form form" >
								<div class="icp-list ">
									<div class="color-gray we7-padding-left we7-margin-top-sm">网站链接（必填）</div>
									<div class="icp-item">
										<div class="form-box">
											<div class="form-group">
												<label class="col-sm-2 control-label">域名</label>
												<div class="col-sm-10">
													<input type="text" name="domain" required min="0" ng-model="currentIcp.domain" class="form-control" placeholder="请填写域名">
												</div>
											</div>
										</div>
									</div>
									<div class="color-gray we7-padding-left we7-margin-top-sm">ICP备案</div>
									<div class="icp-item">
										<div class="form-box">
											<div class="form-group">
												<label class="col-sm-2 control-label">备案号</label>
												<div class="col-sm-10">
													<input type="text"  min="0" name="icp" ng-model="currentIcp.icp" class="form-control" placeholder="请填写备案号">
												</div>
											</div>
										</div>
									</div>
									<div class="color-gray we7-padding-left we7-margin-top-sm">联网备案</div>
									<div class="icp-item">
										<div class="form-box">
											<div class="form-group we7-margin-bottom">
												<label class="col-sm-2 control-label">备案地</label>
												<div class="col-sm-10">
													<input type="text"  min="0" name="icp" ng-model="currentIcp.policeicp_location" class="form-control" placeholder="例如：京公网安备">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label">备案号</label>
												<div class="col-sm-10">
													<input type="text"  min="0" name="icp" ng-model="currentIcp.policeicp_code" class="form-control" placeholder="例如：1100000200001">
												</div>
											</div>
										</div>
									</div>
									<div class="color-gray we7-padding-left we7-margin-top-sm">电子执照亮照</div>
									<div class="icp-item">
										<div class="form-box">
											<div class="form-group">
												<label class="col-sm-2 control-label">链接</label>
												<div class="col-sm-10">
													<input type="text"  min="0" name="icp" placeholder="亮照链接" ng-model="currentIcp.electronic_license" class="form-control">
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-primary" name="submit" value="保存" ng-click="updateIcp()">保存</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
						</div>
					</div>
				</div>
			</div>
			<?php  } ?>
			<?php  if($do == 'copyright') { ?>
				
				<div class="form-files">
					<div class="form-file header">SEO信息</div>
					<!-- 网站名称 start -->
					<div class="form-file">
						<div class="form-label">网站名称</div>
						<div class="form-value" ng-bind="settings.sitename"></div>
						<div class="form-edit">
							<we7-modal-form label="'网站名称'" on-confirm="saveSetting(formValue, 'sitename', 'string')" value="settings.sitename"></we7-modal-form>
						</div>
					</div>
					<!-- 网站名称 end -->

					<!-- 网站URL start -->
					<div class="form-file">
						<div class="form-label">网站URL</div>
						<div class="form-value" ng-bind="settings.url"></div>
						<div class="form-edit">
							<we7-modal-form label="'网站URL'" on-confirm="saveSetting(formValue, 'url', 'string')" value="settings.url"></we7-modal-form>
						</div>
					</div>
					<!-- 网站URL end -->

					<!-- keywords start -->
					<div class="form-file">
						<div class="form-label">keywords</div>
						<div class="form-value" ng-bind="settings.keywords"></div>
						<div class="form-edit">
							<we7-modal-form label="'keywords'" on-confirm="saveSetting(formValue, 'keywords', 'string')" value="settings.keywords"></we7-modal-form>
						</div>
					</div>
					<!-- keywords end -->

					<!-- description start -->
					<div class="form-file">
						<div class="form-label">description</div>
						<div class="form-value" ng-bind="settings.description"></div>
						<div class="form-edit">
							<we7-modal-form label="'description'" on-confirm="saveSetting(formValue, 'description', 'string')" value="settings.description"></we7-modal-form>
						</div>
					</div>
					<!-- description end -->
				</div>
				
			<?php  } ?>

			<?php  if($do == 'about') { ?>
				
				<div class="form-files">
					<div class="form-file header">关于我们</div>
					<!-- 联系人 start -->
					<div class="form-file">
						<div class="form-label">联系人</div>
						<div class="form-value" ng-bind="settings.person"></div>
						<div class="form-edit">
							<we7-modal-form label="'联系人'" on-confirm="saveSetting(formValue, 'person', 'string')" value="settings.person"></we7-modal-form>
						</div>
					</div>
					<!-- 联系人 end -->

					<!-- 联系电话 start -->
					<div class="form-file">
						<div class="form-label">联系电话</div>
						<div class="form-value" ng-bind="settings.phone"></div>
						<div class="form-edit">
							<we7-modal-form label="'联系电话'" on-confirm="saveSetting(formValue, 'phone', 'string')" value="settings.phone"></we7-modal-form>
						</div>
					</div>
					<!-- 联系电话 end -->

					<!-- QQ start -->
					<div class="form-file">
						<div class="form-label">QQ</div>
						<div class="form-value" ng-bind="settings.qq"></div>
						<div class="form-edit">
							<we7-modal-form label="'QQ'" on-confirm="saveSetting(formValue, 'qq', 'string')" value="settings.qq"></we7-modal-form>
						</div>
					</div>
					<!-- QQ end -->

					<!-- 邮箱 start -->
					<div class="form-file">
						<div class="form-label">邮箱</div>
						<div class="form-value" ng-bind="settings.email"></div>
						<div class="form-edit">
							<we7-modal-form label="'邮箱'" on-confirm="saveSetting(formValue, 'email', 'string')" value="settings.email"></we7-modal-form>
						</div>
					</div>
					<!-- 邮箱 end -->

					<!-- 公司名称 start -->
					<div class="form-file">
						<div class="form-label">公司名称</div>
						<div class="form-value" ng-bind="settings.company"></div>
						<div class="form-edit">
							<we7-modal-form label="'公司名称'" on-confirm="saveSetting(formValue, 'company', 'string')" value="settings.company"></we7-modal-form>
						</div>
					</div>
					<!-- 公司名称 end -->

					<!-- 关于我们 start -->
					<div class="form-file">
						<div class="form-label">关于我们</div>
						<div class="form-value" ng-bind="settings.companyprofile"></div>
						<div class="form-edit">
							<we7-modal-form label="'关于我们'" type="'textarea'" on-confirm="saveSetting(formValue, 'companyprofile', 'string')" value="settings.companyprofile"></we7-modal-form>
						</div>
						<!--<span class="help-block">该文字显示在个人中心->关于我们中</span>-->
					</div>
					<!-- 关于我们 end -->

					<!-- 详细地址 start -->
					<div class="form-file">
						<div class="form-label">详细地址</div>
						<div class="form-value" ng-bind="settings.address"></div>
						<div class="form-edit">
							<we7-modal-form label="'详细地址'" on-confirm="saveSetting(formValue, 'address', 'string')" value="settings.address"></we7-modal-form>
						</div>
					</div>
					<!-- 详细地址 end -->

					<!-- 地理位置 start -->
					<div class="form-file">
						<div class="form-label">地理位置</div>
						<div class="form-value" >
							经度: <?php  echo $settings['baidumap']['lng'];?>,
							纬度: <?php  echo $settings['baidumap']['lat'];?>
						</div>
						<div class="form-edit">
							<a ng-click="showCoordinate(this);" >修改</a>
						</div>
					</div>
					<!-- 地理位置 end -->
				</div>
				
			<?php  } ?>
           <?php  if($do == 'sms') { ?>
				<div class="form-files">
					<div class="form-file header">短信配置</div>

					<div class="form-file">
						<div class="form-label">短信宝账号</div>
						<div class="form-value">没有短信宝账号？点击<a class="btn btn-primary span3" target="_blank" href="http://www.smsbao.com/reg?r=11671">免费注册</a></div>
						<div class="form-value" ng-bind="settings.sms_name"></div>
						<div class="form-edit">
							<we7-modal-form label="'短信宝账号'" on-confirm="saveSetting(formValue, 'sms_name', 'string')" value="settings.sms_name"></we7-modal-form>
						</div>
					
					</div>
					<div class="form-file">
						<div class="form-label">短信宝密码</div>
						<div class="form-value" ng-bind="settings.sms_password"></div>
						<div class="form-edit">
							<we7-modal-form label="'短信宝密码'" on-confirm="saveSetting(formValue, 'sms_password', 'password')" value="settings.sms_password"></we7-modal-form>
						</div>
					</div>
					<div class="form-file">
						<div class="form-label">短信签名</div>
						<div class="form-value" ng-bind="settings.sms_sign"></div>
						<div class="form-edit">
							<we7-modal-form label="'短信签名'" on-confirm="saveSetting(formValue, 'sms_sign', 'string')" value="settings.sms_sign"></we7-modal-form>
						</div>
					</div>
				</div>			
	<?php  } ?>			
		
		</div>
	</div>
</div>

<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=F51571495f717ff1194de02366bb8da9&s=1"></script>
<script type="text/javascript">
	$("input[name='status']").click(function() {
		if ($(this).val() == 1) {
			$(".reason").show();
			var reason = $("input[name='reasons']").val();
			$("textarea[name='reason']").text(reason);
		} else {
			$(".reason").hide();
		}
	});
	$("input[name='mobile_status']").click(function() {
		if ($(this).val() == 0) {
			$("#login_type_status-1").attr("checked", false);
			$("#login_type_status-0").prop("checked", true);
			$("#login_type_status-1").attr("disabled", true);
		} else {
			$("#login_type_status-1").attr("disabled", false);
		}
	});
</script>

<script>
	angular.module('systemApp').value('config', {
		'settings' : <?php  echo json_encode($settings)?>,
		'template' : <?php  echo json_encode($template)?>,
		'template_ch_name' : <?php  echo json_encode($template_ch_name)?>,
		'login_ch_name' : <?php  echo json_encode($login_ch_name)?>,
		'icps': <?php  echo json_encode($icps)?>,
		links: {
			'saveSettingUrl' : "<?php  echo url('system/site/save_setting')?>",
			'editIcpUrl': "<?php  echo url('system/site/edit_icp')?>",
			'delIcpUrl': "<?php  echo url('system/site/del_icp')?>",
		}
	});

	angular.bootstrap($('#system-site-setting'), ['systemApp']);
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>