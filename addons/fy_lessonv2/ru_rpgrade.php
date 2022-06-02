<?php
$sql="
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_aliyun_upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `teacherid` int(11) DEFAULT NULL COMMENT '讲师id(讲师id为空表示后台上传)',
  `name` varchar(500) DEFAULT NULL COMMENT '文件名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '一级分类',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '二级分类',
  `ccid` int(11) NOT NULL DEFAULT '0' COMMENT '三级分类',
  `videoid` varchar(255) DEFAULT NULL COMMENT '视频ID',
  `object` varchar(255) DEFAULT NULL,
  `size` decimal(10,2) DEFAULT NULL COMMENT '视频大小',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `teacherid` (`teacherid`),
  KEY `videoid` (`videoid`),
  KEY `pid` (`pid`),
  KEY `cid` (`cid`),
  KEY `ccid` (`ccid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='阿里云点播存储';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_aliyunoss_upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `teacherid` int(11) DEFAULT NULL COMMENT '讲师id(讲师id为空表示后台上传)',
  `name` varchar(500) DEFAULT NULL COMMENT '文件名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '一级分类',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '二级分类',
  `ccid` int(11) NOT NULL DEFAULT '0' COMMENT '三级分类',
  `com_name` varchar(1000) DEFAULT NULL COMMENT '完整文件名',
  `size` decimal(10,2) DEFAULT NULL COMMENT '视频大小',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `teacherid` (`teacherid`),
  KEY `pid` (`pid`),
  KEY `cid` (`cid`),
  KEY `ccid` (`ccid`)
) ENGINE=MyISAM AUTO_INCREMENT=192 DEFAULT CHARSET=utf8 COMMENT='阿里云OSS存储';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '文章分类',
  `author` varchar(100) DEFAULT NULL COMMENT '作者',
  `content` longtext COMMENT '内容',
  `linkurl` varchar(1000) DEFAULT NULL COMMENT '原文链接',
  `images` varchar(255) DEFAULT NULL COMMENT '分享图片',
  `describes` varchar(500) DEFAULT NULL COMMENT '分享描述',
  `desc` text NOT NULL COMMENT '分享描述',
  `commend` tinyint(1) NOT NULL DEFAULT '1' COMMENT '首页展示',
  `is_vip` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'VIP会员可见',
  `isshow` tinyint(1) DEFAULT '1' COMMENT '状态 0.下架 1.上架',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序 数值越大越靠前',
  `view` int(11) NOT NULL DEFAULT '0' COMMENT '访问量',
  `virtual_view` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟访问量',
  `addtime` int(10) DEFAULT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_title` (`title`),
  KEY `idx_author` (`author`),
  KEY `idx_isshow` (`isshow`),
  KEY `idx_addtime` (`addtime`),
  KEY `cate_id` (`cate_id`),
  KEY `commend` (`commend`),
  KEY `is_vip` (`is_vip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章公告';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_article_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `name` varchar(255) NOT NULL COMMENT '分类名称',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0.隐藏 1.显示',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `name` (`name`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章分类';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_attribute` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `attr_type` varchar(255) NOT NULL COMMENT '属性类型',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `name` (`name`),
  KEY `attr_type` (`attr_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程属性';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_banner` (
  `banner_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `banner_name` varchar(255) DEFAULT NULL COMMENT '广告位名称',
  `picture` varchar(255) NOT NULL COMMENT '图片路径',
  `link` varchar(255) DEFAULT NULL COMMENT '图片链接',
  `is_pc` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0.移动端 1.PC端',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0.不显示 1.显示',
  `banner_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '图片类型 0.首页轮播图 1.底部课程广告',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`banner_id`),
  KEY `uniacid` (`uniacid`),
  KEY `is_pc` (`is_pc`),
  KEY `is_show` (`is_show`),
  KEY `banner_type` (`banner_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图片表';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_cashlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `uid` int(11) DEFAULT NULL,
  `openid` varchar(255) NOT NULL,
  `lesson_type` tinyint(1) NOT NULL COMMENT '佣金类型：1.分销佣金  2.课程佣金',
  `cash_type` tinyint(1) NOT NULL COMMENT '提现方式 1.管理员审核 2.自动到账',
  `cash_way` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1.系统余额  2.微信钱包  3.支付宝 4.银行卡 5.二维码收款',
  `bank_name` varchar(255) DEFAULT NULL COMMENT '提现银行名称',
  `bank_row` varchar(255) NOT NULL COMMENT '银行卡开户行',
  `pay_account` varchar(50) DEFAULT NULL COMMENT '提现帐号',
  `pay_name` varchar(255) DEFAULT NULL COMMENT '账号户主姓名',
  `cash_num` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `service_num` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现手续费',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0.待审核 1.提现成功 -1.作废，无效佣金 -2.驳回申请',
  `disposetime` int(10) NOT NULL DEFAULT '0' COMMENT '处理时间',
  `partner_trade_no` varchar(255) DEFAULT NULL COMMENT '商户订单号',
  `payment_no` varchar(255) DEFAULT NULL COMMENT '微信订单号',
  `remark` text COMMENT '管理员文字备注',
  `admin_img` varchar(255) DEFAULT NULL COMMENT '管理员图片备注',
  `user_img` varchar(255) DEFAULT NULL COMMENT '用户图片备注',
  `addtime` int(10) NOT NULL COMMENT '申请时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_cash_type` (`cash_type`),
  KEY `idx_cash_way` (`cash_way`),
  KEY `idx_uid` (`uid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_status` (`status`),
  KEY `idx_lesson_type` (`lesson_type`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='佣金提现记录';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `ico` varchar(255) DEFAULT NULL COMMENT '分类图标',
  `link` varchar(1000) DEFAULT NULL COMMENT '(手机端)自定义链接',
  `link_pc` varchar(255) DEFAULT NULL COMMENT '(PC端)自定义链接',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `attribute1` text COMMENT '课程属性1',
  `attribute2` text COMMENT '课程属性2',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '热门推荐 0.否 1.是',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示在首页',
  `search_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示在分类页',
  `addtime` int(10) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `name` (`name`),
  KEY `parentid` (`parentid`),
  KEY `is_hot` (`is_hot`),
  KEY `is_show` (`is_show`),
  KEY `search_show` (`search_show`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程分类';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_collect` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `outid` int(11) NOT NULL COMMENT '外部编号(课程编号或讲师编号)',
  `ctype` tinyint(1) NOT NULL COMMENT '收藏类型 1.课程 2.讲师',
  `addtime` int(10) NOT NULL COMMENT '收藏时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_ctype` (`ctype`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏表';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_commission_level` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `levelname` varchar(50) DEFAULT NULL COMMENT '分销等级名称',
  `commission1` decimal(10,2) DEFAULT '0.00' COMMENT '一级分销佣金比例',
  `commission2` decimal(10,2) DEFAULT '0.00' COMMENT '二级分销佣金比例',
  `commission3` decimal(10,2) DEFAULT '0.00' COMMENT '三级分销佣金比例',
  `updatemoney` decimal(10,2) DEFAULT '0.00' COMMENT '升级条件(分销佣金满多少)',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_levelname` (`levelname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销等级';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_commission_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `orderid` varchar(255) DEFAULT NULL COMMENT '订单id',
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `bookname` varchar(255) DEFAULT NULL COMMENT '课程名称',
  `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `change_num` decimal(10,2) DEFAULT '0.00' COMMENT '变动数目',
  `grade` tinyint(1) DEFAULT NULL COMMENT '佣金等级',
  `remark` varchar(255) DEFAULT NULL COMMENT '变动说明',
  `company_income` tinyint(1) NOT NULL DEFAULT '0' COMMENT '机构收入 0.否 1.是',
  `buyer_uid` int(11) DEFAULT NULL COMMENT '购买者uid',
  `source` tinyint(1) NOT NULL DEFAULT '0' COMMENT '来源 1.课程订单 2.VIP订单 3.讲师服务订单 4.管理员操作',
  `addtime` int(10) DEFAULT NULL COMMENT '变动时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_orderid` (`orderid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_bookname` (`bookname`),
  KEY `idx_grade` (`grade`),
  KEY `source` (`source`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='佣金日志';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_commission_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `vip_sale` tinyint(1) DEFAULT '0' COMMENT 'VIP订单分销开关',
  `vipcard_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'VIP卡密开通入口',
  `vipdesc` text COMMENT 'vip服务描述',
  `sharelink` text COMMENT '链接分享',
  `sharelesson` text COMMENT '分享课程',
  `shareteacher` text COMMENT '分享讲师',
  `is_sale` tinyint(1) DEFAULT '0' COMMENT '分销功能 0.关闭 1.开启',
  `hidden_sale` tinyint(1) NOT NULL DEFAULT '0' COMMENT '小程序审核模式 0.关闭 1.开启',
  `hidden_login` tinyint(1) NOT NULL DEFAULT '0' COMMENT '关闭微信端强制登录',
  `self_sale` tinyint(1) DEFAULT '0' COMMENT '分销内购 0.关闭 1.开启',
  `show_lately_cashlog` tinyint(1) NOT NULL DEFAULT '0' COMMENT '显示最近提现明细',
  `commission_credit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '扩展字段(分销integral)',
  `sale_rank` tinyint(1) DEFAULT '1' COMMENT '分销身份 1.任何人 2.VIP身份',
  `commission` text COMMENT '默认课程佣金比例',
  `viporder_commission` text COMMENT 'VIP订单佣金比例(如果该值不设定，则使用全局分销佣金比例)',
  `level` tinyint(1) DEFAULT '3' COMMENT '分销等级',
  `upgrade_condition` tinyint(1) NOT NULL DEFAULT '1' COMMENT '分销升级条件 1.累计佣金 2.支付订单额 3.支付订单笔数',
  `cash_type` tinyint(1) DEFAULT '1' COMMENT '提现处理方式 1.管理员审核 2.自动到账',
  `cash_way` text COMMENT '提现方式',
  `cash_lower_vip` decimal(10,2) DEFAULT '1.00' COMMENT 'VIP提现最低额度 0.关闭',
  `cash_lower_common` decimal(10,2) NOT NULL DEFAULT '1.00' COMMENT '普通用户最低提现额度 0为关闭',
  `cash_lower_teacher` decimal(10,2) NOT NULL DEFAULT '1.00' COMMENT '讲师最低提现额度 0.关闭',
  `cash_service_num` tinyint(3) NOT NULL DEFAULT '0' COMMENT '提现手续费',
  `cash_interval_common` tinyint(1) NOT NULL DEFAULT '0' COMMENT '普通用户提现间隔(天)',
  `cash_interval_vip` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'VIP用户提现间隔(天)',
  `cash_interval_teacher` tinyint(1) NOT NULL DEFAULT '0' COMMENT '讲师佣金提现间隔(天)',
  `mchid` varchar(50) DEFAULT NULL COMMENT '微信支付商户号',
  `mchkey` varchar(50) DEFAULT NULL COMMENT '微信支付商户支付密钥',
  `serverIp` varchar(20) DEFAULT NULL COMMENT '服务器Ip',
  `agent_status` tinyint(1) DEFAULT '1' COMMENT '分销商状态 0.关闭 1.开启 2.冻结',
  `agent_condition` text COMMENT '分销商条件 1.消费金额满x元  2.消费订单满x笔  3.注册满x天',
  `sale_desc` text COMMENT '推广海报页面说明',
  `level_desc` text COMMENT '分销等级描述',
  `rec_income` text COMMENT '直接推荐奖励',
  `qrcode_cache` tinyint(1) NOT NULL DEFAULT '1' COMMENT '会员海报缓存 0.不缓存  1.缓存',
  `font` text COMMENT '分享文字(以json格式保存)',
  `vip_agreement` mediumtext COMMENT '购买VIP协议',
  `sale_model` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推广绑定模式 0.保护模式 1.保护期模式',
  `protect_time` int(11) NOT NULL DEFAULT '0' COMMENT '保护期(小时)',
  `protect_rank` tinyint(1) NOT NULL DEFAULT '0' COMMENT '可发展为下级的身份 0.任何人 1.非VIP身份',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销设置';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_coupon` (
  `card_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `password` varchar(18) NOT NULL COMMENT '优惠码密钥',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠码面值',
  `validity` int(10) NOT NULL COMMENT '有效期',
  `conditions` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '使用条件(满x元可用)',
  `use_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '使用类型 1.按课程分类 2.按指定课程',
  `category_id` int(11) NOT NULL COMMENT '使用条件 指定分类课程使用 0.为全部课程',
  `lesson_ids` text COMMENT '可使用的课程id',
  `is_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0.未使用 1.已使用',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `uid` int(11) DEFAULT NULL COMMENT '会员编号',
  `ordersn` varchar(50) DEFAULT NULL COMMENT '订单编号',
  `use_time` int(10) DEFAULT NULL COMMENT '使用时间',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`card_id`),
  UNIQUE KEY `password` (`password`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_validity` (`validity`),
  KEY `idx_addtime` (`addtime`),
  KEY `ordersn` (`ordersn`),
  KEY `use_type` (`use_type`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程优惠码';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_discount` (
  `discount_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned DEFAULT NULL COMMENT '公众号编号',
  `title` varchar(255) DEFAULT NULL COMMENT '活动标题',
  `content` text COMMENT '活动描述内容',
  `member_discount` tinyint(1) NOT NULL DEFAULT '0' COMMENT '同时享受会员折扣 0.否 1.是',
  `starttime` int(11) DEFAULT NULL COMMENT '开始时间',
  `endtime` int(11) DEFAULT NULL COMMENT '结束时间',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`discount_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='限时折扣活动';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_discount_lesson` (
  `discount_lesson_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号编号',
  `discount_id` int(11) DEFAULT NULL COMMENT '折扣活动编号',
  `lesson_id` int(11) DEFAULT NULL COMMENT '课程编号',
  `discount` int(4) DEFAULT '0' COMMENT '课程折扣 单位%',
  `member_discount` tinyint(1) DEFAULT '0' COMMENT '同时享受会员折扣 0.否 1.是',
  `starttime` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `addtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`discount_lesson_id`),
  KEY `uniacid` (`uniacid`),
  KEY `discount_id` (`discount_id`),
  KEY `lesson_id` (`lesson_id`),
  KEY `starttime` (`starttime`),
  KEY `endtime` (`endtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='限时折扣课程';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_diy_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `page_title` varchar(255) NOT NULL,
  `page_desc` varchar(255) DEFAULT NULL COMMENT '页面描述',
  `page_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型 1.首页模板 2.自定义页面',
  `cover` varchar(255) DEFAULT NULL COMMENT '封面图',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0.未启用 1.启用',
  `data` longtext NOT NULL COMMENT '模板数据',
  `addtime` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`),
  KEY `page_title` (`page_title`),
  KEY `addtime` (`addtime`),
  KEY `page_type` (`page_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='DIY模板';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_document` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL COMMENT '公众号编号',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `lessonid` int(11) NOT NULL COMMENT '课程id',
  `filepath` varchar(255) NOT NULL COMMENT '文件路径',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `lessonid` (`lessonid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程资料文件';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_evaluate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `orderid` int(11) unsigned NOT NULL COMMENT '订单id',
  `ordersn` varchar(255) NOT NULL COMMENT '订单编号',
  `lessonid` int(11) NOT NULL COMMENT '课程id',
  `bookname` varchar(255) NOT NULL COMMENT '课程名称',
  `teacherid` int(11) DEFAULT NULL COMMENT '讲师id(与fy_lesson_teacher表的id字段对应)',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `virtual_nickname` varchar(100) DEFAULT NULL COMMENT '虚拟用户昵称',
  `virtual_avatar` varchar(255) DEFAULT NULL COMMENT '虚拟用户头像',
  `grade` tinyint(1) NOT NULL COMMENT '评价 1.好评 2.中评 3.差评',
  `global_score` decimal(2,1) NOT NULL DEFAULT '5.0' COMMENT '综合评分(5分制)',
  `content_score` decimal(2,1) NOT NULL DEFAULT '5.0' COMMENT '内容实用(5分制)',
  `understand_score` decimal(2,1) NOT NULL DEFAULT '5.0' COMMENT '通俗易懂(5分制)',
  `content` text NOT NULL COMMENT '评价内容',
  `reply` text COMMENT '评价回复',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '评论状态 -1.审核未通过 0.待审核 1.审核通过',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '评价类型 0.系统默认好评  1.用户评价',
  `addtime` int(10) NOT NULL COMMENT '评价时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_orderid` (`orderid`),
  KEY `idx_ordersn` (`ordersn`),
  KEY `idx_lessonid` (`lessonid`),
  KEY `idx_bookname` (`bookname`),
  KEY `idx_teacherid` (`teacherid`),
  KEY `idx_grade` (`grade`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程评价';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_evaluate_score` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `lessonid` int(11) NOT NULL COMMENT '课程编号',
  `global_score` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '综合评分(平均)',
  `content_score` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '内容实用(平均)',
  `understand_score` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '通俗易懂(平均)',
  `total_global` int(11) NOT NULL DEFAULT '0' COMMENT '综合评分(总分数)',
  `total_content` int(11) NOT NULL DEFAULT '0' COMMENT '内容实用(总分数)',
  `total_understand` int(11) NOT NULL DEFAULT '0' COMMENT '通俗易懂(总分数)',
  `score` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '课程好评率',
  `total_goods` int(11) NOT NULL DEFAULT '0' COMMENT '好评总条数',
  `total_number` int(11) NOT NULL DEFAULT '0' COMMENT '评价总人数',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `lessonid` (`lessonid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程评分';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_footer_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `title` varchar(255) NOT NULL COMMENT '分组名称',
  `article_ids` text COMMENT '文章编号(json格式保存)',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_pc` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0.手机端 1.PC',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `title` (`title`),
  KEY `displayorder` (`displayorder`),
  KEY `is_pc` (`is_pc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='底部文章分组';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `lessonid` int(11) NOT NULL COMMENT '课程id',
  `vipview` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'VIP免费学习课程 0.否 1.是',
  `teacherview` tinyint(1) NOT NULL DEFAULT '0' COMMENT '讲师服务免费学习课程 0.否 1.是',
  `study_process` tinyint(3) NOT NULL DEFAULT '0' COMMENT '课程学习进度',
  `addtime` int(10) NOT NULL COMMENT '最后进入时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员课程足迹';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_index_module` (
  `index_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `module_ident` varchar(100) NOT NULL,
  `module_name` varchar(100) NOT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  `displayorder` int(3) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`index_id`),
  KEY `uniacid` (`uniacid`),
  KEY `module_ident` (`module_ident`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='首页模块排序';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_inform` (
  `inform_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `lesson_id` int(11) DEFAULT NULL COMMENT '课程id',
  `book_name` varchar(255) DEFAULT NULL COMMENT '课程名称',
  `content` text COMMENT '模版消息内容(json格式保存)',
  `user_type` tinyint(1) DEFAULT NULL COMMENT '用户类型 1.全部会员 2.VIP会员 3.购买过该讲师的会员',
  `inform_number` int(11) DEFAULT NULL COMMENT '发送总量',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1.发送中 0.发送完毕',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`inform_id`),
  KEY `uniacid` (`uniacid`),
  KEY `lesson_id` (`lesson_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='课程通知列表';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_inform_fans` (
  `inform_fans_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `inform_id` int(11) DEFAULT NULL COMMENT '通知id',
  `openid` varchar(50) DEFAULT NULL COMMENT '粉丝编号',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`inform_fans_id`),
  KEY `uniacid` (`uniacid`),
  KEY `inform_id` (`inform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='课程通知粉丝列表';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_lessoncard` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `password` varchar(100) NOT NULL COMMENT '卡密',
  `cardtime` decimal(10,2) NOT NULL COMMENT '卡密时长(天)',
  `lesson_id` int(11) NOT NULL COMMENT '课程id',
  `is_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0.未使用 1.已使用',
  `ordersn` varchar(50) DEFAULT NULL COMMENT '使用订单编号(对应课程订单表的ordersn)',
  `use_uid` int(11) DEFAULT NULL COMMENT '使用用户uid',
  `use_time` int(10) DEFAULT NULL COMMENT '使用时间',
  `validity` int(11) NOT NULL COMMENT '卡密有效期',
  `open_uid` int(11) DEFAULT NULL COMMENT '开通用户uid',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `password` (`password`),
  KEY `uniacid` (`uniacid`),
  KEY `is_use` (`is_use`),
  KEY `use_uid` (`use_uid`),
  KEY `validity` (`validity`),
  KEY `open_uid` (`open_uid`)
) ENGINE=MyISAM AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='课程卡密';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_login_pc` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `login_token` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `login_ip` varchar(100) DEFAULT NULL COMMENT '登录ip',
  `login_time` int(11) DEFAULT NULL,
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='PC端扫码登录';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_market` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `deduct_switch` tinyint(1) DEFAULT '0' COMMENT '积分抵扣开关',
  `deduct_money` decimal(10,2) DEFAULT '0.00' COMMENT '1积分抵扣金额',
  `study_duration` text COMMENT '学习时长兑换积分设置(json格式保存)',
  `reg_give` text COMMENT '注册赠送优惠券',
  `reg_coupon_image` varchar(255) DEFAULT NULL COMMENT '新会员优惠券图片',
  `recommend` text COMMENT '推荐下级赠送优惠券',
  `recommend_time` int(11) NOT NULL DEFAULT '0' COMMENT '最多可获取次数 0.不限制',
  `buy_lesson` text COMMENT '购买课程赠送优惠券',
  `buy_lesson_time` int(11) NOT NULL DEFAULT '0' COMMENT '最多可获取次数 0.不限制',
  `share_lesson` text COMMENT '分享课程赠送优惠券',
  `share_lesson_time` int(11) NOT NULL DEFAULT '0' COMMENT '最多可获取次数 0.不限制',
  `coupon_desc` text COMMENT '优惠券页面说明',
  `signin` text NOT NULL COMMENT '签到奖励积分设置',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='优惠券规则';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_mcoupon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '优惠券面值',
  `images` varchar(255) NOT NULL COMMENT ' 优惠券封面图',
  `validity_type` text COMMENT '有效期 1.固定有效期 2.自增有效期',
  `days1` int(11) NOT NULL COMMENT '固定有效期',
  `days2` int(11) NOT NULL COMMENT '自增有效期(天)',
  `conditions` decimal(10,2) DEFAULT '0.00' COMMENT '使用条件 满x元可使用',
  `is_exchange` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支持积分兑换 0.不支持 1.支持',
  `exchange_integral` int(11) NOT NULL COMMENT '每张优惠券兑换积分',
  `max_exchange` int(11) NOT NULL COMMENT '每位用户最大兑换数量',
  `total_exchange` int(11) NOT NULL COMMENT '总共优惠券张数',
  `already_exchange` int(11) NOT NULL COMMENT '已兑换数量',
  `use_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '使用类型 1.按课程分类 2.按指定课程',
  `category_id` int(11) DEFAULT NULL COMMENT '使用条件 指定分类课程使用 0.为全部课程',
  `lesson_ids` text COMMENT '可使用的课程id',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 0.下架 1.上架',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `receive_link` tinyint(1) NOT NULL DEFAULT '0' COMMENT '链接领取 0.不支持 1.支持',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程优惠券';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `nickname` varchar(100) DEFAULT NULL COMMENT '昵称',
  `gohome` tinyint(1) NOT NULL DEFAULT '0' COMMENT '学员是否进群 0.未进群 1.已进群',
  `openid` varchar(255) DEFAULT NULL COMMENT '粉丝标识',
  `parentid` int(11) NOT NULL DEFAULT '0' COMMENT '推荐人id',
  `leaderunion` tinyint(1) NOT NULL DEFAULT '0' COMMENT '联合发起人身份 0.否 1.是',
  `nopay_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '未结算佣金',
  `pay_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '已结算佣金',
  `nopay_lesson` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '未提现课程收入',
  `pay_lesson` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '已提现课程收入',
  `payment_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '购买订单总额',
  `payment_order` int(11) NOT NULL DEFAULT '0' COMMENT '购买订单笔数',
  `vip` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否vip 0.否 1.是',
  `agent_level` int(11) NOT NULL DEFAULT '0' COMMENT '分销代理级别',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '分销状态 0.冻结 1.正常',
  `blacklist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '黑名单：0.正常 1.禁止下单 2.禁止访问',
  `article_duration` int(11) NOT NULL DEFAULT '0' COMMENT '图文学习时长',
  `audio_duration` int(11) NOT NULL DEFAULT '0' COMMENT '音频学习时长',
  `video_duration` int(11) NOT NULL DEFAULT '0' COMMENT '视频学习时长',
  `remark` text COMMENT '备注信息',
  `coupon_tip` tinyint(1) NOT NULL DEFAULT '1' COMMENT '新会员优惠券提醒',
  `duration_uptime` int(11) NOT NULL DEFAULT '0' COMMENT '最后学习时间',
  `uptime` int(10) NOT NULL COMMENT '更新时间',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uid` (`uid`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_parentid` (`parentid`),
  KEY `idx_vip` (`vip`),
  KEY `idx_status` (`status`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员表';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_member_bind` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `parentid` int(11) NOT NULL DEFAULT '0' COMMENT '上级uid',
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `operator_uid` int(11) NOT NULL DEFAULT '0' COMMENT '后台操作员编号  0.系统',
  `operator_name` varchar(100) DEFAULT NULL COMMENT '操作员用户名',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `parentid` (`parentid`),
  KEY `uid` (`uid`),
  KEY `operator_uid` (`operator_uid`),
  KEY `operator_name` (`operator_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销推广绑定记录';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_member_buyteacher` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `uid` int(11) NOT NULL COMMENT '会员编号',
  `teacherid` int(11) NOT NULL COMMENT '讲师编号',
  `validity` bigint(20) NOT NULL COMMENT '有效期',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `teacherid` (`teacherid`),
  KEY `validity` (`validity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='已购买讲师服务';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_member_coupon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '优惠券面值',
  `conditions` decimal(10,2) DEFAULT '0.00' COMMENT '使用条件',
  `validity` int(11) DEFAULT NULL COMMENT '有效期',
  `use_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '使用类型 1.按课程分类 2.按指定课程',
  `category_id` int(11) NOT NULL COMMENT '指定分类课程可用',
  `lesson_ids` text COMMENT '可使用的课程id',
  `password` varchar(100) DEFAULT NULL COMMENT '优惠券密码(优惠码转换过来的)',
  `ordersn` varchar(100) DEFAULT NULL COMMENT '使用订单号',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态 -1.已过期 0.未使用 1.已使用',
  `source` tinyint(1) NOT NULL COMMENT '来源 1.优惠码转换 2.购买课程赠送 3.邀请下级成员赠送 4.分享课程赠送 5.积分兑换  6.新会员注册  7.后台发放 8.链接领取 9.好友转赠',
  `coupon_id` int(11) DEFAULT NULL COMMENT '优惠券id',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `validity` (`validity`),
  KEY `status` (`status`),
  KEY `source` (`source`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员优惠券';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_member_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `acid` int(11) DEFAULT '0',
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `ordersn` varchar(255) NOT NULL COMMENT '订单编号',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `level_id` int(11) NOT NULL COMMENT 'vip会员等级id(与fy_lesson_vip_level表id对应)',
  `level_name` varchar(255) DEFAULT NULL COMMENT 'VIP等级名称',
  `viptime` decimal(10,2) NOT NULL COMMENT '会员服务时间',
  `vipmoney` decimal(10,2) NOT NULL COMMENT '会员服务价格',
  `discount_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '续费优惠金额',
  `integral` int(11) DEFAULT '0' COMMENT '赠送积分',
  `paytype` varchar(50) NOT NULL COMMENT '支付方式',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态 0.未支付 1.已支付 -2.已退款',
  `paytime` int(10) DEFAULT '0' COMMENT '订单支付时间',
  `member1` int(11) NOT NULL COMMENT '一级代理会员id',
  `commission1` decimal(10,2) NOT NULL COMMENT '一级代理佣金',
  `member2` int(11) NOT NULL COMMENT '二级代理会员id',
  `commission2` decimal(10,2) NOT NULL COMMENT '二级代理佣金',
  `member3` int(11) NOT NULL COMMENT '三级代理会员id',
  `commission3` decimal(10,2) NOT NULL COMMENT '三级代理佣金',
  `refer_id` int(11) DEFAULT NULL COMMENT '充值卡id(与vip卡的id对应)',
  `addtime` int(10) NOT NULL COMMENT '订单添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_ordersn` (`ordersn`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_paytype` (`paytype`),
  KEY `idx_status` (`status`),
  KEY `idx_refer_id` (`refer_id`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='VIP订单';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_member_vip` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `level_id` int(11) DEFAULT NULL COMMENT 'vip等级id',
  `validity` bigint(11) DEFAULT NULL COMMENT '有效期',
  `discount` int(4) DEFAULT '100' COMMENT '折扣',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `level_id` (`level_id`),
  KEY `validity` (`validity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='已购买VIP';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_navigation` (
  `nav_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `nav_ident` varchar(100) DEFAULT NULL COMMENT '导航标识：手机端底部导航(index首页、search全部课程、diynav自定义导航、mylesson我的课程、self个人中心)、pc-PC端导航、mobile-手机端其他导航',
  `nav_name` varchar(100) NOT NULL COMMENT '导航名称',
  `unselected_icon` varchar(255) DEFAULT NULL COMMENT '未选中图标',
  `selected_icon` varchar(255) DEFAULT NULL COMMENT '已选中图标',
  `url_link` varchar(1000) DEFAULT NULL COMMENT '跳转链接',
  `displayorder` int(4) DEFAULT '0' COMMENT '排序',
  `is_pc` tinyint(1) NOT NULL DEFAULT '0' COMMENT '平台类型： 0.移动端  1.PC端',
  `icon` varchar(255) DEFAULT NULL COMMENT '导航小图标',
  `location` tinyint(1) NOT NULL DEFAULT '1' COMMENT '导航位置 1.底部导航 2.顶部导航',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`nav_id`),
  KEY `uniacid` (`uniacid`),
  KEY `is_pc` (`is_pc`),
  KEY `location` (`location`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='导航表';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `acid` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `ordersn` varchar(255) NOT NULL COMMENT '订单编号',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `lesson_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '课程类型 0.普通课程  1.报名课程',
  `appoint_info` text COMMENT '预约信息(json格式保存)',
  `spec_id` int(11) NOT NULL DEFAULT '0' COMMENT '规格id',
  `spec_name` varchar(255) DEFAULT NULL COMMENT '课程规格名称',
  `spec_day` int(4) DEFAULT NULL COMMENT '课程规格(多少天内有效)',
  `verify_number` tinyint(1) NOT NULL DEFAULT '1' COMMENT '核销次数(报名课程专用)',
  `is_verify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0.未核销 1.已核销 2.核销完成(报名课程线下核销使用)',
  `verify_info` text COMMENT '核销信息(核销人、核销时间)',
  `lessonid` int(11) NOT NULL COMMENT '课程id',
  `bookname` varchar(255) NOT NULL COMMENT '课程名称',
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `coupon` varchar(50) DEFAULT NULL COMMENT '课程优惠码',
  `coupon_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠码面值',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '课程价格',
  `teacherid` int(11) DEFAULT NULL COMMENT '讲师id(与haoshu_teacher表的id字段对应)',
  `teacher_income` tinyint(1) NOT NULL DEFAULT '0' COMMENT '讲师申请 0.关闭 1.开启',
  `company_uid` int(11) NOT NULL DEFAULT '0' COMMENT '机构uid',
  `company_income` tinyint(3) NOT NULL DEFAULT '0' COMMENT '机构收入(课程价格分成%)',
  `integral` int(4) NOT NULL DEFAULT '0' COMMENT '赠送积分',
  `deduct_integral` int(11) NOT NULL DEFAULT '0' COMMENT '积分抵扣数量',
  `vip_discount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'VIP折扣优惠',
  `paytype` varchar(50) NOT NULL DEFAULT '0' COMMENT '支付方式',
  `paytime` int(10) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `validity` int(11) NOT NULL DEFAULT '0' COMMENT '有效期 在有效期内可观看学习课程',
  `member1` int(11) NOT NULL DEFAULT '0' COMMENT '一级代理会员id',
  `commission1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '一级佣金',
  `member2` int(11) NOT NULL DEFAULT '0' COMMENT '二级代理会员id',
  `commission2` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '二级佣金',
  `member3` int(11) NOT NULL DEFAULT '0' COMMENT '三级代理会员id',
  `commission3` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '三级佣金',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态 -1.已取消 0.未支付 1.已支付 2.已评价',
  `remark` varchar(500) DEFAULT NULL COMMENT '文字备注',
  `admin_img` varchar(255) DEFAULT NULL COMMENT '图片备注',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `addtime` int(10) DEFAULT NULL COMMENT '下单时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_ordersn` (`ordersn`),
  KEY `idx_acid` (`acid`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_lessonid` (`lessonid`),
  KEY `idx_bookname` (`bookname`),
  KEY `idx_teacherid` (`teacherid`),
  KEY `idx_paytype` (`paytype`),
  KEY `idx_status` (`status`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='课程订单';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_order_verify` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `orderid` int(11) NOT NULL COMMENT '订单id',
  `verify_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '核销员类型 0.核销员 1.后台管理员',
  `verify_uid` int(11) DEFAULT NULL COMMENT '核销人uid',
  `verify_name` varchar(100) DEFAULT NULL COMMENT '核销人昵称',
  `addtime` int(11) NOT NULL COMMENT '核销时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `orderid` (`orderid`),
  KEY `verify_uid` (`verify_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单核销记录';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_parent` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父分类id',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '子分类ID',
  `attribute1` int(11) NOT NULL DEFAULT '0' COMMENT '课程自定义属性1',
  `attribute2` int(11) NOT NULL DEFAULT '0' COMMENT '课程自定义属性2',
  `lesson_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '课程类型 0.普通课程  1.预约课程',
  `appoint_info` text COMMENT '报名课程必填项',
  `appoint_dir` tinyint(1) NOT NULL DEFAULT '1' COMMENT '报名课程详情页目录 0.隐藏 1.显示',
  `verify_number` tinyint(1) NOT NULL DEFAULT '1' COMMENT '核销次数(报名课程专用)',
  `saler_uids` text COMMENT '报名课程核销人员uid(以json格式存储)',
  `buynow_info` text COMMENT '立即购买信息(json格式保存)',
  `bookname` varchar(255) NOT NULL COMMENT '课程名称',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '课程价格',
  `live_info` text,
  `isdiscount` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启该课程折扣',
  `vipdiscount` int(3) NOT NULL DEFAULT '0' COMMENT 'vip会员折扣',
  `vipdiscount_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'VIP优惠金额',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '购买赠送积分',
  `integral_rate` decimal(5,1) DEFAULT '0.0' COMMENT '赠送积分比例',
  `deduct_integral` int(11) NOT NULL DEFAULT '0' COMMENT '积分最多抵扣数量',
  `recommend_free_limit` int(4) NOT NULL DEFAULT '0' COMMENT '免费学习推广期限',
  `recommend_free_num` int(4) NOT NULL DEFAULT '0' COMMENT '免费学习直接推荐人数',
  `recommend_free_day` int(4) NOT NULL DEFAULT '0' COMMENT '免费学习期限',
  `images` varchar(255) DEFAULT NULL COMMENT '课程封图',
  `poster` text COMMENT '视频播放封面图',
  `descript` longtext COMMENT '课程介绍',
  `stock` int(11) NOT NULL COMMENT '库存',
  `buynum` int(11) NOT NULL DEFAULT '0' COMMENT '正常购买人数',
  `virtual_buynum` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟购买人数',
  `vip_number` int(11) NOT NULL DEFAULT '0' COMMENT 'VIP学习人数',
  `teacher_number` int(11) NOT NULL DEFAULT '0' COMMENT '讲师服务学习人数',
  `visit_number` int(11) NOT NULL DEFAULT '0' COMMENT '访问人数',
  `score` decimal(5,2) NOT NULL COMMENT '课程好评率',
  `teacherid` int(11) NOT NULL COMMENT '主讲老师id',
  `commission` text COMMENT '佣金比例',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '课程排序',
  `lesson_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认显示 0.跟随系统 1.详情 2.目录',
  `drag_play` tinyint(1) NOT NULL DEFAULT '1' COMMENT '拖拽播放',
  `status` tinyint(1) NOT NULL COMMENT '课程状态 -1.暂停销售 0.下架 1.上架 2.审核中',
  `show_time` int(11) NOT NULL DEFAULT '0' COMMENT '自动上架时间',
  `section_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '连载状态 0.更新中 1.已完结',
  `recommendid` varchar(255) DEFAULT NULL COMMENT '推荐板块id',
  `vipview` varchar(1000) DEFAULT NULL COMMENT '免费学习的VIP等级集合',
  `teacher_income` tinyint(3) NOT NULL DEFAULT '0' COMMENT '课程讲师分成%',
  `award_income` tinyint(3) NOT NULL DEFAULT '0' COMMENT '打赏讲师分成%',
  `company_income` tinyint(3) NOT NULL DEFAULT '0' COMMENT '机构分成%',
  `link` varchar(1000) DEFAULT NULL COMMENT '自定义链接',
  `validity` int(11) NOT NULL DEFAULT '0' COMMENT '有效期 即购买时起多少天内有效',
  `share` text COMMENT '分享信息',
  `support_coupon` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否支持优惠券抵扣 0.不支持 1.支持',
  `poster_config` text COMMENT '课程海报配置',
  `ico_name` varchar(100) DEFAULT NULL COMMENT '课程标识',
  `poster_setting` text COMMENT '课程海报参数',
  `poster_bg` varchar(255) DEFAULT NULL COMMENT '海报背景图',
  `service` text COMMENT '加群客服',
  `like_lesson_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '关联课程类型 0.指定分类 1.指定课程',
  `like_lesson_content` text NOT NULL COMMENT '关联指定分类ID(整型)或课程ID(json保存)',
  `like_goods_ids` text NOT NULL COMMENT '关联商品ID',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '章节最后更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_cid` (`cid`),
  KEY `idx_bookname` (`bookname`),
  KEY `idx_teacherid` (`teacherid`),
  KEY `idx_displayorder` (`displayorder`),
  KEY `idx_status` (`status`),
  KEY `idx_recommendid` (`recommendid`),
  KEY `idx_addtime` (`addtime`),
  KEY `pid` (`pid`),
  KEY `attribute1` (`attribute1`),
  KEY `attribute2` (`attribute2`),
  KEY `lesson_type` (`lesson_type`),
  KEY `vipview` (`vipview`(333)),
  KEY `show_time` (`show_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程主表';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_playrecord` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `lessonid` int(11) DEFAULT NULL COMMENT '课程id',
  `sectionid` int(11) DEFAULT NULL COMMENT '章节id',
  `playtime` int(11) NOT NULL DEFAULT '0' COMMENT '最长播放时间 单位：秒',
  `duration` int(11) NOT NULL DEFAULT '0' COMMENT '总时间 单位：秒',
  `is_end` tinyint(1) NOT NULL DEFAULT '0' COMMENT '章节学习完成',
  `playcount` int(11) NOT NULL DEFAULT '1' COMMENT '播放次数',
  `playtoken` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '记录播放次数token',
  `addtime` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_addtime` (`addtime`),
  KEY `is_end` (`is_end`),
  KEY `lessonid` (`lessonid`),
  KEY `sectionid` (`sectionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='播放学习记录';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_poster` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `poster_name` varchar(255) DEFAULT NULL COMMENT '海报名称',
  `poster_bg` varchar(255) DEFAULT NULL COMMENT '海报背景图',
  `poster_setting` text COMMENT '海报配置参数',
  `poster_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1.分销海报  2.课程海报',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `poster_type` (`poster_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销海报';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_qcloud_upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `teacherid` int(11) DEFAULT NULL COMMENT '讲师id(讲师id为空表示后台上传)',
  `name` varchar(500) DEFAULT NULL COMMENT '文件名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '一级分类',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '二级分类',
  `ccid` int(11) NOT NULL DEFAULT '0' COMMENT '三级分类',
  `com_name` varchar(1000) DEFAULT NULL COMMENT '完整文件名',
  `sys_link` varchar(1000) DEFAULT NULL COMMENT '原始链接',
  `size` decimal(10,2) DEFAULT NULL COMMENT '视频大小',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `teacherid` (`teacherid`),
  KEY `pid` (`pid`),
  KEY `cid` (`cid`),
  KEY `ccid` (`ccid`)
) ENGINE=MyISAM AUTO_INCREMENT=164 DEFAULT CHARSET=utf8 COMMENT='腾讯云对象存储';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_qcloudvod_upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `teacherid` int(11) DEFAULT NULL COMMENT '讲师id(讲师id为空表示后台上传)',
  `name` varchar(500) DEFAULT NULL COMMENT '文件名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '一级分类',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '二级分类',
  `ccid` int(11) NOT NULL DEFAULT '0' COMMENT '三级分类',
  `videoid` varchar(255) DEFAULT NULL COMMENT '视频ID',
  `videourl` varchar(1000) DEFAULT NULL COMMENT '视频原始地址',
  `size` decimal(10,2) DEFAULT NULL COMMENT '视频大小',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `teacherid` (`teacherid`),
  KEY `videoid` (`videoid`),
  KEY `pid` (`pid`),
  KEY `cid` (`cid`),
  KEY `ccid` (`ccid`)
) ENGINE=MyISAM AUTO_INCREMENT=116 DEFAULT CHARSET=utf8 COMMENT='腾讯云点播存储';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_qiniu_upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `uid` int(11) DEFAULT NULL COMMENT '会员编号',
  `teacher` int(11) DEFAULT NULL COMMENT '讲师编号',
  `name` varchar(500) DEFAULT NULL COMMENT '文件名',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '一级分类',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '二级分类',
  `ccid` int(11) NOT NULL DEFAULT '0' COMMENT '三级分类',
  `com_name` varchar(1000) DEFAULT NULL COMMENT '完成文件名',
  `qiniu_url` varchar(1000) DEFAULT NULL COMMENT '文件链接',
  `size` varchar(100) DEFAULT NULL COMMENT '文件大小',
  `addtime` int(10) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `cid` (`cid`),
  KEY `ccid` (`ccid`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `teacher` (`teacher`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='七牛云对象存储';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_recommend` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `rec_name` varchar(255) DEFAULT NULL COMMENT '板块名称',
  `icon` varchar(255) DEFAULT NULL COMMENT '图标',
  `show_style` tinyint(1) NOT NULL DEFAULT '1' COMMENT '显示样式 1.单课程模式 2.课程+专题模式 3.专题模式',
  `displayorder` int(4) DEFAULT NULL COMMENT '排序',
  `limit_number` int(4) NOT NULL DEFAULT '6' COMMENT '手机端首页显示数量',
  `limit_number_pc` int(4) NOT NULL DEFAULT '4' COMMENT 'PC端首页显示数量',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_is_show` (`is_show`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程推荐板块';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_recommend_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `uid` int(11) NOT NULL COMMENT '会员编号',
  `lessonid` int(11) NOT NULL COMMENT '课程编号',
  `bookname` varchar(255) DEFAULT NULL COMMENT '课程名称',
  `images` varchar(255) DEFAULT NULL COMMENT '课程封面图',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 -1.已失败 0.未完成 1.已完成',
  `invite_number` int(4) NOT NULL DEFAULT '0' COMMENT '已邀请人数',
  `addtime` int(11) NOT NULL COMMENT '参加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `lessonid` (`lessonid`),
  KEY `status` (`status`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='推广课程免费学习列表';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_recommend_junior` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `activity_id` int(11) NOT NULL COMMENT '活动编号',
  `uid` int(11) NOT NULL COMMENT '会员编号',
  `lessonid` int(11) NOT NULL COMMENT '课程编号',
  `junior_uid` int(11) NOT NULL COMMENT '下级会员编号',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `lessonid` (`lessonid`),
  KEY `junior_uid` (`junior_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程推荐下级成员';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `logo` varchar(255) NOT NULL COMMENT 'app端logo',
  `manageopenid` text NOT NULL COMMENT '新订单提醒(管理员)',
  `sitename` varchar(100) DEFAULT NULL,
  `template` varchar(100) NOT NULL DEFAULT 'default' COMMENT '手机端模版名称',
  `copyright` varchar(255) NOT NULL COMMENT '版权',
  `site_icp` varchar(255) NOT NULL COMMENT 'ICP备案号',
  `closespace` int(4) NOT NULL DEFAULT '60' COMMENT '关闭未付款订单时间间隔',
  `closelast` int(10) NOT NULL DEFAULT '0' COMMENT '上次执行关闭未付款订单时间',
  `savetype` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0.其他存储 1.七牛云存储 2.腾讯云存储 3.阿里云点播 4.腾讯云点播 5.阿里云OSS',
  `qiniu` text NOT NULL COMMENT '七牛云存储参数',
  `qcloud` text COMMENT '腾讯云存储参数',
  `qcloudvod` text COMMENT '腾讯云点播配置参数',
  `aliyunoss` text COMMENT '阿里云oss参数',
  `aliyunvod` text COMMENT '阿里云点播参数',
  `show_teacher_income` tinyint(1) NOT NULL DEFAULT '1' COMMENT '后台发布课程显示讲师分成',
  `ios_pay` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'ios支付开关 0.关闭 1.开启',
  `company_income` tinyint(1) NOT NULL DEFAULT '0' COMMENT '机构分成 0.关闭 1.开启',
  `isfollow` text COMMENT '引导关注公众号',
  `qrcode` varchar(255) DEFAULT NULL COMMENT '公众号二维码',
  `mustinfo` tinyint(1) NOT NULL DEFAULT '0' COMMENT '下单前必须完善手机号码和姓名',
  `user_info` text COMMENT '填写选项(以json格式保存)',
  `autogood` int(4) NOT NULL DEFAULT '0' COMMENT '超时自动好评 默认0为关闭',
  `posterbg` text COMMENT '推广海报背景图',
  `poster_type` tinyint(1) NOT NULL DEFAULT '1',
  `poster_config` text COMMENT '海报参数设置',
  `category_ico` varchar(255) NOT NULL COMMENT '所有分类图标',
  `stock_config` tinyint(1) DEFAULT '0' COMMENT '是否启用库存 0.否 1.是',
  `lesson_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '课程详情页默认显示',
  `follow_word` varchar(100) DEFAULT NULL COMMENT '引导关注提示语',
  `audit_evaluate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '课程评价是否需要审核  0.否 1.是',
  `show_evaluate_time` tinyint(1) NOT NULL DEFAULT '1' COMMENT '课程评价时间 0.隐藏  1.显示',
  `show_study_number` tinyint(1) NOT NULL DEFAULT '1' COMMENT '显示学习人数',
  `repeat_record_lesson` tinyint(1) NOT NULL DEFAULT '0' COMMENT '保存多条章节学习记录',
  `login_visit` text COMMENT '需要登录访问的控制器',
  `visit_limit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '非微信端访问 0.不允许 1.允许',
  `show_newlesson` tinyint(2) NOT NULL DEFAULT '0' COMMENT '首页显示最新课程章节数',
  `lesson_follow_title` varchar(255) DEFAULT NULL COMMENT '课程页强制关注标题',
  `lesson_follow_desc` varchar(255) DEFAULT NULL COMMENT '课程页强制关注描述',
  `sms` text COMMENT '短信配置信息',
  `modify_mobile` tinyint(1) NOT NULL DEFAULT '0' COMMENT '绑定手机号码 0.不开启 1.开启个人中心 2.开启首页和个人中心',
  `qun_service` text COMMENT '加群客服人员',
  `index_verify` text COMMENT '首页验证绑定选项',
  `common` text COMMENT '公共设置',
  `front_color` text COMMENT '手机端自定义样式',
  `lesson_poster_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '课程海报入口 0.隐藏  1.显示',
  `lesson_vip_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '课程详情页开通VIP入口',
  `lesson_agreement` mediumtext COMMENT '购买课程协议',
  `privacy_agreement` mediumtext COMMENT '注册(或绑定手机)隐私协议',
  `teacher_agreement` mediumtext COMMENT '讲师注册协议',
  `lesson_config` text COMMENT '课程相关设置',
  `video_live` text COMMENT '直播配置信息',
  `im_config` text NOT NULL COMMENT '即时通信im配置',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='基本设置';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_setting_pc` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `sitename` varchar(255) DEFAULT NULL COMMENT '网站名称',
  `template` varchar(255) DEFAULT NULL COMMENT 'PC端模板',
  `logo` varchar(255) DEFAULT NULL COMMENT '网站顶部logo',
  `bottom_logo` varchar(255) DEFAULT NULL COMMENT '网站底部logo',
  `mobile_qrcode` varbinary(255) DEFAULT NULL COMMENT '手机端二维码',
  `favicon_icon` varchar(255) DEFAULT NULL COMMENT 'pc端favicon图标',
  `login_visit` text COMMENT '需登录访问控制器',
  `payment` text NOT NULL COMMENT '支付方式',
  `video_watermark` tinyint(1) NOT NULL DEFAULT '1' COMMENT '视频水印(仅支持阿里云点播和其他存储视频)',
  `teacher_contact` tinyint(1) NOT NULL DEFAULT '1' COMMENT '讲师联系方式 0.隐藏 1.显示',
  `site_root` varchar(255) DEFAULT NULL COMMENT '自定义绑定域名',
  `mobile_site_root` varchar(255) DEFAULT NULL COMMENT '公众号端域名',
  `jump_setting` text COMMENT 'PC跳转手机端设置',
  `hot_search` text COMMENT '热门搜索',
  `service_right_pic` varchar(255) DEFAULT NULL COMMENT '搜索框右侧图片',
  `service_right_url` varchar(255) DEFAULT NULL COMMENT '搜索框右侧图片链接',
  `new_notice` text COMMENT '首页最新通知',
  `rec_teacher` text COMMENT '首页名师风采',
  `new_lesson` text COMMENT '首页最新更新课程',
  `friendly_link` text COMMENT '友情链接',
  `company_info` text COMMENT '公司信息',
  `site_icp` varchar(255) DEFAULT NULL COMMENT '网站ICP备案号',
  `site_network` varchar(100) DEFAULT NULL COMMENT '联网备案号',
  `site_culture` varchar(255) DEFAULT NULL COMMENT '网络文化许可证编号',
  `site_added` varchar(255) NOT NULL COMMENT '增值电信业务许可证号',
  `login_register` text COMMENT '登录注册页信息',
  `front_css` text COMMENT '前端样式',
  `keywords` text COMMENT '网站关键词',
  `description` text COMMENT '网站描述',
  `teacher_platform` varchar(255) DEFAULT NULL COMMENT '讲师平台链接',
  `about_title` text COMMENT '关于我们自定义标题',
  `aboutus_desc` text COMMENT '关于我们描述',
  `culture_desc` text COMMENT '企业文化描述',
  `environment_desc` text COMMENT '办公环境描述',
  `contact_desc` text COMMENT '联系我们描述',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_signin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `award` int(4) NOT NULL DEFAULT '0' COMMENT '签到奖励',
  `timer` tinyint(3) NOT NULL DEFAULT '1' COMMENT '连续签到计时器',
  `days` tinyint(3) NOT NULL COMMENT '签到号数',
  `sign_date` date NOT NULL COMMENT '签到日期',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `sign_date` (`sign_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户签到';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_son` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `parentid` int(11) NOT NULL COMMENT '课程关联id',
  `title` varchar(255) NOT NULL COMMENT '章节名称',
  `title_id` int(11) NOT NULL DEFAULT '0' COMMENT '目录ID(与课程目录表title_id对应)',
  `images` varchar(255) DEFAULT NULL COMMENT '章节封面图',
  `savetype` tinyint(1) NOT NULL COMMENT '存储方式 0.其他存储 1.七牛云存储 2.内嵌代码 3.腾讯云存储 4.阿里云点播 5.腾讯云点播 6.阿里云OSS',
  `sectiontype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '章节类型 1.视频 2.图文 3.音频  4.外链',
  `videourl` text COMMENT '章节视频url',
  `videotime` varchar(100) NOT NULL COMMENT '视频时长',
  `content` longtext COMMENT '章节内容',
  `displayorder` int(4) NOT NULL DEFAULT '0',
  `is_free` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为试听章节 0.否 1.是',
  `test_time` int(4) NOT NULL DEFAULT '0' COMMENT '试听时间(单位:秒，0为关闭)',
  `is_live` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否直播 0.否 1.是',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 0.隐藏 1.显示',
  `auto_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '自动上架 0.关闭 1.开启',
  `show_time` int(11) NOT NULL DEFAULT '0' COMMENT '自动上架时间',
  `password` varchar(50) DEFAULT NULL COMMENT '访问密码',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_parentid` (`parentid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程章节';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_spec` (
  `spec_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `lessonid` int(11) NOT NULL COMMENT '课程id',
  `spec_day` int(11) DEFAULT NULL COMMENT '有效期(天)',
  `spec_price` decimal(10,2) DEFAULT '0.00' COMMENT '规格价格',
  `spec_name` varchar(255) DEFAULT NULL COMMENT '规格名称',
  `spec_stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `spec_sort` int(3) NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`spec_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程规格';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_static` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `lessonOrder_num` int(11) NOT NULL DEFAULT '0' COMMENT '课程订单总量',
  `lessonOrder_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '课程订单总额',
  `vipOrder_num` int(11) NOT NULL DEFAULT '0' COMMENT 'vip订单总量',
  `vipOrder_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'VIP订单总额',
  `teacherOrder_num` int(11) NOT NULL DEFAULT '0' COMMENT '讲师订单总量',
  `teacherOrder_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '讲师订单总额',
  `static_time` int(11) NOT NULL COMMENT '统计日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='财务统计';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_study_duration` (
  `study_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `uid` int(11) NOT NULL COMMENT '会员编号',
  `date` varchar(50) NOT NULL COMMENT '日期',
  `article` int(11) NOT NULL DEFAULT '0' COMMENT '学习图文时长',
  `audio` int(11) NOT NULL DEFAULT '0' COMMENT '学习音频时长',
  `video` int(11) NOT NULL DEFAULT '0' COMMENT '学习视频时长',
  `exchange` int(11) NOT NULL DEFAULT '0' COMMENT '今日兑换学习时长(秒)',
  `ranking` tinyint(3) NOT NULL DEFAULT '0' COMMENT '超过今日学员百分比',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`study_id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `datetime` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员每日学习时长';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_subscribe_msg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `uid` int(11) NOT NULL COMMENT '会员编号',
  `openid` varchar(255) DEFAULT NULL COMMENT '粉丝编号',
  `subscribe` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订阅消息',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订阅模板消息';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_suggest` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `uid` int(11) NOT NULL COMMENT '用户编号',
  `category_id` varchar(255) NOT NULL COMMENT '投诉类型id',
  `content` varchar(3000) NOT NULL COMMENT '投诉内容',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系手机号码',
  `picture` text COMMENT '图片凭证',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0.未处理 1.处理中 2.已处理',
  `remark` text COMMENT '备注',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `category_id` (`category_id`),
  KEY `status` (`status`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='投诉建议';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_suggest_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='投诉建议分类';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_syslog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `admin_uid` int(11) DEFAULT NULL COMMENT '管理员id',
  `admin_username` varchar(50) DEFAULT NULL COMMENT '管理员昵称',
  `log_type` tinyint(1) DEFAULT NULL COMMENT '操作类型 1.增加 2.删除 3更新',
  `function` varchar(100) DEFAULT NULL COMMENT '操作的功能',
  `content` varchar(1000) DEFAULT NULL COMMENT '操作描述',
  `ip` varchar(50) DEFAULT NULL COMMENT '操作IP地址',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_admin_uid` (`admin_uid`),
  KEY `idx_log_type` (`log_type`),
  KEY `idx_function` (`function`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='操作日志记录';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_teacher` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `openid` varchar(100) NOT NULL DEFAULT '0' COMMENT '粉丝编号',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '讲师分类',
  `teacher_income` tinyint(2) NOT NULL DEFAULT '0' COMMENT '讲师分成(单位%)',
  `account` varchar(20) DEFAULT NULL COMMENT '讲师登录帐号',
  `password` varchar(32) DEFAULT NULL COMMENT '讲师登录密码',
  `teacher` varchar(100) NOT NULL COMMENT '讲师名称',
  `idcard` varchar(50) DEFAULT NULL COMMENT '身份证号码',
  `mobile` varchar(50) DEFAULT NULL COMMENT '手机号码',
  `is_distribution` tinyint(1) NOT NULL DEFAULT '0' COMMENT '购买讲师分销 0.关闭 1.开启',
  `commission` text COMMENT '一二三级佣金(以json格式保存)',
  `qq` varchar(20) DEFAULT NULL COMMENT '讲师QQ',
  `qqgroup` varchar(20) DEFAULT NULL COMMENT '讲师QQ群',
  `qqgroupLink` varchar(255) DEFAULT NULL COMMENT 'QQ群加群链接',
  `weixin_qrcode` varchar(255) NOT NULL COMMENT '微信二维码',
  `online_url` varchar(500) DEFAULT NULL COMMENT '在线咨询链接',
  `teacher_bg` varchar(255) DEFAULT NULL COMMENT '讲师主页背景图（手机端）',
  `teacher_bg_pc` varchar(255) DEFAULT NULL COMMENT '讲师主页背景图（PC端）',
  `first_letter` varchar(10) DEFAULT NULL COMMENT '讲师名称首字母拼音',
  `teacherdes` text COMMENT '讲师介绍',
  `digest` varchar(500) NOT NULL COMMENT '讲师简介',
  `teacherphoto` varchar(255) DEFAULT NULL COMMENT '讲师相片',
  `status` tinyint(1) NOT NULL DEFAULT '2' COMMENT '讲师状态 -1.审核不通过 1.正常 2.审核中',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐首页 0.否 1.是',
  `upload` tinyint(1) NOT NULL DEFAULT '1' COMMENT '上传权限 0.禁止 1.允许',
  `addlive` tinyint(1) NOT NULL DEFAULT '1' COMMENT '允许使用直播',
  `avoid_audit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '免审核课程章节 0.否 1.是',
  `teacher_home_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '讲师主页默认显示 0.默认 1.全部课程 2.讲师介绍',
  `addexam` tinyint(1) NOT NULL DEFAULT '1' COMMENT '题库管理',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `company_uid` int(11) NOT NULL DEFAULT '0' COMMENT '机构uid',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_account` (`account`),
  KEY `idx_status` (`status`),
  KEY `idx_upload` (`upload`),
  KEY `is_recommend` (`is_recommend`),
  KEY `cate_id` (`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='讲师表';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_teacher_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL COMMENT '公众号编号',
  `name` varchar(255) NOT NULL COMMENT '分类名称',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0.隐藏 1.显示',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `name` (`name`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='讲师分类';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_teacher_income` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `teacher` varchar(255) DEFAULT NULL COMMENT '讲师名称',
  `ordersn` varchar(100) DEFAULT NULL COMMENT '订单编号',
  `ordertype` tinyint(1) NOT NULL DEFAULT '2' COMMENT '订单类型 2.课程订单 3.购买讲师订单 4.打赏讲师订单',
  `bookname` varchar(255) DEFAULT NULL COMMENT '课程名称',
  `orderprice` decimal(10,2) DEFAULT '0.00' COMMENT '订单价格',
  `teacher_income` tinyint(3) DEFAULT NULL COMMENT '讲师分成',
  `income_amount` decimal(10,2) DEFAULT '0.00' COMMENT '讲师实际收入',
  `addtime` int(10) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_teacher` (`teacher`),
  KEY `idx_ordersn` (`ordersn`),
  KEY `idx_bookname` (`bookname`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='讲师收入记录';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_teacher_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `ordersn` varchar(255) NOT NULL COMMENT '订单编号',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `ordertime` int(11) DEFAULT NULL COMMENT '服务时长(天)',
  `price` decimal(10,2) NOT NULL COMMENT '服务价格',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '赠送积分',
  `paytype` varchar(50) NOT NULL COMMENT '支付方式',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态 0.未支付 1.已支付',
  `paytime` int(10) NOT NULL DEFAULT '0' COMMENT '订单支付时间',
  `member1` int(11) NOT NULL COMMENT '一级分销员id',
  `commission1` decimal(10,2) NOT NULL COMMENT '一级分销佣金',
  `member2` int(11) NOT NULL COMMENT '二级分销员id',
  `commission2` decimal(10,2) NOT NULL COMMENT '二级分销佣金',
  `member3` int(11) NOT NULL COMMENT '三级分销员id',
  `commission3` decimal(10,2) NOT NULL COMMENT '三级分销佣金',
  `teacherid` int(11) NOT NULL COMMENT '讲师id',
  `teacher_name` varchar(255) DEFAULT NULL COMMENT '讲师名称',
  `teacher_income` tinyint(3) NOT NULL DEFAULT '0' COMMENT '讲师分成',
  `addtime` int(10) NOT NULL COMMENT '订单添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_ordersn` (`ordersn`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_paytype` (`paytype`),
  KEY `idx_status` (`status`),
  KEY `idx_teacherid` (`teacherid`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购买讲师订单';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_teacher_price` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `teacherid` int(11) NOT NULL COMMENT '讲师编号',
  `price` decimal(10,0) NOT NULL COMMENT '购买价格',
  `validity_time` int(4) NOT NULL COMMENT '有效时长(天)',
  `teacher_income` tinyint(3) NOT NULL DEFAULT '0' COMMENT '讲师分成',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '赠送积分',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `teacherid` (`teacherid`),
  KEY `validity_time` (`validity_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购买讲师价格';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_title` (
  `title_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `title` varchar(255) DEFAULT NULL COMMENT '目录名称',
  `lesson_id` int(11) NOT NULL COMMENT '课程id',
  `displayorder` int(4) DEFAULT '0' COMMENT '排序',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`title_id`),
  KEY `uniacid` (`uniacid`),
  KEY `lesson_id` (`lesson_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程目录';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_tplmessage` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `buysucc` varchar(255) DEFAULT NULL COMMENT '用户购买成功通知',
  `buysucc_format` text COMMENT '购买成功模版格式',
  `cnotice` varchar(255) DEFAULT NULL,
  `cnotice_format` text,
  `newjoin` varchar(255) DEFAULT NULL,
  `newjoin_format` text,
  `newlesson` varchar(255) DEFAULT NULL,
  `neworder` varchar(255) DEFAULT NULL,
  `neworder_format` text,
  `newcash` varchar(255) DEFAULT NULL,
  `newcash_format` text,
  `apply_teacher` varchar(255) DEFAULT NULL,
  `apply_teacher_format` text,
  `receive_coupon` varchar(255) DEFAULT NULL,
  `receive_coupon_format` text,
  `teacher_notice` varchar(255) DEFAULT NULL,
  `teacher_notice_format` text,
  `recommend_junior` varchar(255) DEFAULT NULL,
  `deliver` varchar(255) DEFAULT NULL COMMENT '订单发货通知',
  `deliver_format` text COMMENT '订单发货通知模板消息格式',
  `grade` varchar(255) DEFAULT NULL COMMENT '考试成绩通知',
  `grade_format` text COMMENT '考试成绩通知模板消息格式',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模版消息';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_video_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `parentid` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `teacherid` int(11) NOT NULL DEFAULT '0' COMMENT '讲师id(讲师id为空表示后台上传)',
  `name` varchar(255) NOT NULL COMMENT '分类名称',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `parentid` (`parentid`),
  KEY `teacherid` (`teacherid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='视频分类';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_vip_level` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL COMMENT '公众号id',
  `level_name` varchar(100) DEFAULT NULL COMMENT 'vip等级名称',
  `level_icon` varchar(255) DEFAULT NULL COMMENT '图标',
  `level_validity` int(11) DEFAULT NULL COMMENT '有效期',
  `level_price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '赠送积分',
  `discount` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '购买课程折扣 0.没有折扣',
  `open_discount` tinyint(3) NOT NULL DEFAULT '100' COMMENT '开通VIP折扣比例(%)',
  `renew_discount` tinyint(3) NOT NULL DEFAULT '100' COMMENT '会员等级到期前续费折扣比例(%)',
  `sort` int(4) DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '显示状态 0.隐藏  1.显示',
  `commission` text COMMENT '佣金比例',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_is_show` (`is_show`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='VIP等级';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_vipcard` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `card_id` varchar(50) DEFAULT NULL COMMENT '卡号',
  `password` varchar(100) DEFAULT NULL COMMENT '服务卡密码',
  `viptime` decimal(10,2) DEFAULT NULL COMMENT '服务卡时长',
  `level_id` int(11) NOT NULL,
  `is_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0.未使用 1.已使用',
  `nickname` varchar(100) DEFAULT NULL COMMENT '会员昵称',
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `ordersn` varchar(50) DEFAULT NULL COMMENT '使用订单编号(对应vip订单表的ordersn)',
  `use_time` int(10) DEFAULT NULL COMMENT '使用时间',
  `validity` int(10) DEFAULT NULL COMMENT '有效期',
  `own_uid` int(11) NOT NULL DEFAULT '0' COMMENT '拥有该卡的会员编号',
  `addtime` int(10) unsigned DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `password` (`password`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_card_id` (`card_id`),
  KEY `idx_is_use` (`is_use`),
  KEY `idx_uid` (`uid`),
  KEY `idx_nickname` (`nickname`),
  KEY `idx_ordersn` (`ordersn`),
  KEY `idx_validity` (`validity`),
  KEY `idx_use_time` (`use_time`),
  KEY `own_uid` (`own_uid`)
) ENGINE=MyISAM AUTO_INCREMENT=10000001 DEFAULT CHARSET=utf8 COMMENT='VIP服务卡';

";
pdo_run($sql);
if(!pdo_fieldexists("fy_lesson_aliyun_upload", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyun_upload")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_aliyun_upload", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyun_upload")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_aliyun_upload", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyun_upload")." ADD `uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_aliyun_upload", "teacherid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyun_upload")." ADD `teacherid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_aliyun_upload", "name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyun_upload")." ADD `name` varchar(500);");
}
if(!pdo_fieldexists("fy_lesson_aliyun_upload", "pid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyun_upload")." ADD `pid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_aliyun_upload", "cid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyun_upload")." ADD `cid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_aliyun_upload", "ccid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyun_upload")." ADD `ccid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_aliyun_upload", "videoid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyun_upload")." ADD `videoid` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_aliyun_upload", "object")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyun_upload")." ADD `object` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_aliyun_upload", "size")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyun_upload")." ADD `size` decimal(10,2);");
}
if(!pdo_fieldexists("fy_lesson_aliyun_upload", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyun_upload")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_aliyunoss_upload", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyunoss_upload")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_aliyunoss_upload", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyunoss_upload")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_aliyunoss_upload", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyunoss_upload")." ADD `uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_aliyunoss_upload", "teacherid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyunoss_upload")." ADD `teacherid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_aliyunoss_upload", "name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyunoss_upload")." ADD `name` varchar(500);");
}
if(!pdo_fieldexists("fy_lesson_aliyunoss_upload", "pid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyunoss_upload")." ADD `pid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_aliyunoss_upload", "cid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyunoss_upload")." ADD `cid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_aliyunoss_upload", "ccid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyunoss_upload")." ADD `ccid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_aliyunoss_upload", "com_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyunoss_upload")." ADD `com_name` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_aliyunoss_upload", "size")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyunoss_upload")." ADD `size` decimal(10,2);");
}
if(!pdo_fieldexists("fy_lesson_aliyunoss_upload", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_aliyunoss_upload")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_article", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_article", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_article", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `title` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_article", "cate_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `cate_id` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_article", "author")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `author` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_article", "content")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `content` longtext;");
}
if(!pdo_fieldexists("fy_lesson_article", "linkurl")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `linkurl` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_article", "images")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `images` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_article", "describes")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `describes` varchar(500);");
}
if(!pdo_fieldexists("fy_lesson_article", "desc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `desc` text NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_article", "commend")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `commend` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_article", "is_vip")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `is_vip` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_article", "isshow")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `isshow` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_article", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_article", "view")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `view` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_article", "virtual_view")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `virtual_view` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_article", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article")." ADD `addtime` int(10);");
}
if(!pdo_fieldexists("fy_lesson_article_category", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article_category")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_article_category", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article_category")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_article_category", "name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article_category")." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_article_category", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article_category")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_article_category", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article_category")." ADD `status` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_article_category", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_article_category")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_attribute", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_attribute")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_attribute", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_attribute")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_attribute", "name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_attribute")." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_attribute", "attr_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_attribute")." ADD `attr_type` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_attribute", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_attribute")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_attribute", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_attribute")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_banner", "banner_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_banner")." ADD `banner_id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_banner", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_banner")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_banner", "banner_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_banner")." ADD `banner_name` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_banner", "picture")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_banner")." ADD `picture` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_banner", "link")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_banner")." ADD `link` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_banner", "is_pc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_banner")." ADD `is_pc` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_banner", "is_show")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_banner")." ADD `is_show` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_banner", "banner_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_banner")." ADD `banner_type` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_banner", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_banner")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_banner", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_banner")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_banner", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_banner")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "openid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `openid` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "lesson_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `lesson_type` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "cash_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `cash_type` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "cash_way")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `cash_way` tinyint(1) NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "bank_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `bank_name` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "bank_row")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `bank_row` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "pay_account")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `pay_account` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "pay_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `pay_name` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "cash_num")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `cash_num` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "service_num")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `service_num` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "disposetime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `disposetime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "partner_trade_no")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `partner_trade_no` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "payment_no")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `payment_no` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "remark")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `remark` text;");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "admin_img")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `admin_img` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "user_img")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `user_img` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_cashlog", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_cashlog")." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_category", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_category")." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_category", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_category")." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_category", "name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_category")." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_category", "parentid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_category")." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_category", "ico")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_category")." ADD `ico` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_category", "link")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_category")." ADD `link` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_category", "link_pc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_category")." ADD `link_pc` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_category", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_category")." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_category", "attribute1")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_category")." ADD `attribute1` text;");
}
if(!pdo_fieldexists("fy_lesson_category", "attribute2")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_category")." ADD `attribute2` text;");
}
if(!pdo_fieldexists("fy_lesson_category", "is_hot")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_category")." ADD `is_hot` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_category", "is_show")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_category")." ADD `is_show` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_category", "search_show")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_category")." ADD `search_show` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_category", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_category")." ADD `addtime` int(10);");
}
if(!pdo_fieldexists("fy_lesson_collect", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_collect")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_collect", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_collect")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_collect", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_collect")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_collect", "outid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_collect")." ADD `outid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_collect", "ctype")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_collect")." ADD `ctype` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_collect", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_collect")." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_commission_level", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_level")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_commission_level", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_level")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_commission_level", "levelname")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_level")." ADD `levelname` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_commission_level", "commission1")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_level")." ADD `commission1` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_commission_level", "commission2")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_level")." ADD `commission2` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_commission_level", "commission3")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_level")." ADD `commission3` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_commission_level", "updatemoney")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_level")." ADD `updatemoney` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_commission_log", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_log")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_commission_log", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_log")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_commission_log", "orderid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_log")." ADD `orderid` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_commission_log", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_log")." ADD `uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_commission_log", "bookname")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_log")." ADD `bookname` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_commission_log", "order_amount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_log")." ADD `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_commission_log", "change_num")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_log")." ADD `change_num` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_commission_log", "grade")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_log")." ADD `grade` tinyint(1);");
}
if(!pdo_fieldexists("fy_lesson_commission_log", "remark")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_log")." ADD `remark` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_commission_log", "company_income")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_log")." ADD `company_income` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_log", "buyer_uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_log")." ADD `buyer_uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_commission_log", "source")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_log")." ADD `source` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_log", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_log")." ADD `addtime` int(10);");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "vip_sale")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `vip_sale` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "vipcard_show")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `vipcard_show` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "vipdesc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `vipdesc` text;");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "sharelink")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `sharelink` text;");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "sharelesson")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `sharelesson` text;");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "shareteacher")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `shareteacher` text;");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "is_sale")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `is_sale` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "hidden_sale")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `hidden_sale` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "hidden_login")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `hidden_login` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "self_sale")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `self_sale` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "show_lately_cashlog")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `show_lately_cashlog` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "commission_credit")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `commission_credit` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "sale_rank")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `sale_rank` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "commission")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `commission` text;");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "viporder_commission")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `viporder_commission` text;");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "level")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `level` tinyint(1) DEFAULT '3';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "upgrade_condition")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `upgrade_condition` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "cash_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `cash_type` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "cash_way")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `cash_way` text;");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "cash_lower_vip")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `cash_lower_vip` decimal(10,2) DEFAULT '1.00';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "cash_lower_common")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `cash_lower_common` decimal(10,2) NOT NULL DEFAULT '1.00';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "cash_lower_teacher")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `cash_lower_teacher` decimal(10,2) NOT NULL DEFAULT '1.00';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "cash_service_num")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `cash_service_num` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "cash_interval_common")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `cash_interval_common` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "cash_interval_vip")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `cash_interval_vip` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "cash_interval_teacher")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `cash_interval_teacher` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "mchid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `mchid` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "mchkey")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `mchkey` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "serverIp")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `serverIp` varchar(20);");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "agent_status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `agent_status` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "agent_condition")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `agent_condition` text;");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "sale_desc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `sale_desc` text;");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "level_desc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `level_desc` text;");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "rec_income")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `rec_income` text;");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "qrcode_cache")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `qrcode_cache` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "font")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `font` text;");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "vip_agreement")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `vip_agreement` mediumtext;");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "sale_model")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `sale_model` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "protect_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `protect_time` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "protect_rank")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `protect_rank` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_commission_setting", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_commission_setting")." ADD `addtime` int(11);");
}
if(!pdo_fieldexists("fy_lesson_coupon", "card_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_coupon")." ADD `card_id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_coupon", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_coupon")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_coupon", "password")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_coupon")." ADD `password` varchar(18) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_coupon", "amount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_coupon")." ADD `amount` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_coupon", "validity")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_coupon")." ADD `validity` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_coupon", "conditions")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_coupon")." ADD `conditions` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_coupon", "use_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_coupon")." ADD `use_type` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_coupon", "category_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_coupon")." ADD `category_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_coupon", "lesson_ids")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_coupon")." ADD `lesson_ids` text;");
}
if(!pdo_fieldexists("fy_lesson_coupon", "is_use")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_coupon")." ADD `is_use` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_coupon", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_coupon")." ADD `nickname` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_coupon", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_coupon")." ADD `uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_coupon", "ordersn")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_coupon")." ADD `ordersn` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_coupon", "use_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_coupon")." ADD `use_time` int(10);");
}
if(!pdo_fieldexists("fy_lesson_coupon", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_coupon")." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_discount", "discount_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount")." ADD `discount_id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_discount", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount")." ADD `uniacid` int(11) unsigned;");
}
if(!pdo_fieldexists("fy_lesson_discount", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount")." ADD `title` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_discount", "content")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount")." ADD `content` text;");
}
if(!pdo_fieldexists("fy_lesson_discount", "member_discount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount")." ADD `member_discount` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_discount", "starttime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount")." ADD `starttime` int(11);");
}
if(!pdo_fieldexists("fy_lesson_discount", "endtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount")." ADD `endtime` int(11);");
}
if(!pdo_fieldexists("fy_lesson_discount", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_discount", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_discount", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount")." ADD `update_time` int(11);");
}
if(!pdo_fieldexists("fy_lesson_discount_lesson", "discount_lesson_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount_lesson")." ADD `discount_lesson_id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_discount_lesson", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount_lesson")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_discount_lesson", "discount_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount_lesson")." ADD `discount_id` int(11);");
}
if(!pdo_fieldexists("fy_lesson_discount_lesson", "lesson_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount_lesson")." ADD `lesson_id` int(11);");
}
if(!pdo_fieldexists("fy_lesson_discount_lesson", "discount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount_lesson")." ADD `discount` int(4) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_discount_lesson", "member_discount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount_lesson")." ADD `member_discount` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_discount_lesson", "starttime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount_lesson")." ADD `starttime` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_discount_lesson", "endtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount_lesson")." ADD `endtime` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_discount_lesson", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_discount_lesson")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_diy_template", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_diy_template")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_diy_template", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_diy_template")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_diy_template", "page_title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_diy_template")." ADD `page_title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_diy_template", "page_desc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_diy_template")." ADD `page_desc` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_diy_template", "page_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_diy_template")." ADD `page_type` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_diy_template", "cover")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_diy_template")." ADD `cover` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_diy_template", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_diy_template")." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_diy_template", "data")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_diy_template")." ADD `data` longtext NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_diy_template", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_diy_template")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_diy_template", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_diy_template")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_document", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_document")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_document", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_document")." ADD `uniacid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_document", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_document")." ADD `title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_document", "lessonid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_document")." ADD `lessonid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_document", "filepath")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_document")." ADD `filepath` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_document", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_document")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_document", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_document")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "orderid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `orderid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "ordersn")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `ordersn` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "lessonid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `lessonid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "bookname")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `bookname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "teacherid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `teacherid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "virtual_nickname")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `virtual_nickname` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "virtual_avatar")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `virtual_avatar` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "grade")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `grade` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "global_score")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `global_score` decimal(2,1) NOT NULL DEFAULT '5.0';");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "content_score")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `content_score` decimal(2,1) NOT NULL DEFAULT '5.0';");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "understand_score")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `understand_score` decimal(2,1) NOT NULL DEFAULT '5.0';");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "content")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "reply")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `reply` text;");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `status` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `type` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_evaluate", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate")." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_evaluate_score", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate_score")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_evaluate_score", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate_score")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_evaluate_score", "lessonid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate_score")." ADD `lessonid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_evaluate_score", "global_score")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate_score")." ADD `global_score` decimal(5,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_evaluate_score", "content_score")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate_score")." ADD `content_score` decimal(5,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_evaluate_score", "understand_score")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate_score")." ADD `understand_score` decimal(5,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_evaluate_score", "total_global")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate_score")." ADD `total_global` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_evaluate_score", "total_content")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate_score")." ADD `total_content` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_evaluate_score", "total_understand")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate_score")." ADD `total_understand` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_evaluate_score", "score")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate_score")." ADD `score` decimal(5,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_evaluate_score", "total_goods")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate_score")." ADD `total_goods` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_evaluate_score", "total_number")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate_score")." ADD `total_number` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_evaluate_score", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_evaluate_score")." ADD `update_time` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_footer_group", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_footer_group")." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_footer_group", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_footer_group")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_footer_group", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_footer_group")." ADD `title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_footer_group", "article_ids")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_footer_group")." ADD `article_ids` text;");
}
if(!pdo_fieldexists("fy_lesson_footer_group", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_footer_group")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_footer_group", "is_pc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_footer_group")." ADD `is_pc` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_footer_group", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_footer_group")." ADD `addtime` int(11);");
}
if(!pdo_fieldexists("fy_lesson_history", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_history")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_history", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_history")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_history", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_history")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_history", "lessonid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_history")." ADD `lessonid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_history", "vipview")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_history")." ADD `vipview` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_history", "teacherview")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_history")." ADD `teacherview` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_history", "study_process")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_history")." ADD `study_process` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_history", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_history")." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_index_module", "index_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_index_module")." ADD `index_id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_index_module", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_index_module")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_index_module", "module_ident")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_index_module")." ADD `module_ident` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_index_module", "module_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_index_module")." ADD `module_name` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_index_module", "is_show")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_index_module")." ADD `is_show` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_index_module", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_index_module")." ADD `displayorder` int(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_index_module", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_index_module")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_index_module", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_index_module")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_inform", "inform_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_inform")." ADD `inform_id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_inform", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_inform")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_inform", "lesson_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_inform")." ADD `lesson_id` int(11);");
}
if(!pdo_fieldexists("fy_lesson_inform", "book_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_inform")." ADD `book_name` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_inform", "content")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_inform")." ADD `content` text;");
}
if(!pdo_fieldexists("fy_lesson_inform", "user_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_inform")." ADD `user_type` tinyint(1);");
}
if(!pdo_fieldexists("fy_lesson_inform", "inform_number")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_inform")." ADD `inform_number` int(11);");
}
if(!pdo_fieldexists("fy_lesson_inform", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_inform")." ADD `status` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_inform", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_inform")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_inform", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_inform")." ADD `update_time` int(11);");
}
if(!pdo_fieldexists("fy_lesson_inform_fans", "inform_fans_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_inform_fans")." ADD `inform_fans_id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_inform_fans", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_inform_fans")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_inform_fans", "inform_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_inform_fans")." ADD `inform_id` int(11);");
}
if(!pdo_fieldexists("fy_lesson_inform_fans", "openid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_inform_fans")." ADD `openid` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_inform_fans", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_inform_fans")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_lessoncard", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_lessoncard")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_lessoncard", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_lessoncard")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_lessoncard", "password")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_lessoncard")." ADD `password` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_lessoncard", "cardtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_lessoncard")." ADD `cardtime` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_lessoncard", "lesson_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_lessoncard")." ADD `lesson_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_lessoncard", "is_use")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_lessoncard")." ADD `is_use` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_lessoncard", "ordersn")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_lessoncard")." ADD `ordersn` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_lessoncard", "use_uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_lessoncard")." ADD `use_uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_lessoncard", "use_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_lessoncard")." ADD `use_time` int(10);");
}
if(!pdo_fieldexists("fy_lesson_lessoncard", "validity")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_lessoncard")." ADD `validity` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_lessoncard", "open_uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_lessoncard")." ADD `open_uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_lessoncard", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_lessoncard")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_login_pc", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_login_pc")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_login_pc", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_login_pc")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_login_pc", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_login_pc")." ADD `uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_login_pc", "login_token")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_login_pc")." ADD `login_token` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_login_pc", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_login_pc")." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_login_pc", "login_ip")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_login_pc")." ADD `login_ip` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_login_pc", "login_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_login_pc")." ADD `login_time` int(11);");
}
if(!pdo_fieldexists("fy_lesson_login_pc", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_login_pc")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_market", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_market", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_market", "deduct_switch")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `deduct_switch` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_market", "deduct_money")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `deduct_money` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_market", "study_duration")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `study_duration` text;");
}
if(!pdo_fieldexists("fy_lesson_market", "reg_give")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `reg_give` text;");
}
if(!pdo_fieldexists("fy_lesson_market", "reg_coupon_image")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `reg_coupon_image` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_market", "recommend")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `recommend` text;");
}
if(!pdo_fieldexists("fy_lesson_market", "recommend_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `recommend_time` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_market", "buy_lesson")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `buy_lesson` text;");
}
if(!pdo_fieldexists("fy_lesson_market", "buy_lesson_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `buy_lesson_time` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_market", "share_lesson")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `share_lesson` text;");
}
if(!pdo_fieldexists("fy_lesson_market", "share_lesson_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `share_lesson_time` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_market", "coupon_desc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `coupon_desc` text;");
}
if(!pdo_fieldexists("fy_lesson_market", "signin")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `signin` text NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_market", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_market")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "amount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `amount` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "images")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `images` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "validity_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `validity_type` text;");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "days1")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `days1` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "days2")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `days2` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "conditions")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `conditions` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "is_exchange")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `is_exchange` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "exchange_integral")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `exchange_integral` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "max_exchange")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `max_exchange` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "total_exchange")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `total_exchange` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "already_exchange")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `already_exchange` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "use_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `use_type` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "category_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `category_id` int(11);");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "lesson_ids")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `lesson_ids` text;");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "receive_link")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `receive_link` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_mcoupon", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_mcoupon")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_member", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `nickname` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_member", "gohome")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `gohome` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member", "openid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `openid` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_member", "parentid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `parentid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member", "leaderunion")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `leaderunion` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member", "nopay_commission")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `nopay_commission` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_member", "pay_commission")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `pay_commission` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_member", "nopay_lesson")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `nopay_lesson` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_member", "pay_lesson")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `pay_lesson` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_member", "payment_amount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `payment_amount` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_member", "payment_order")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `payment_order` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member", "vip")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `vip` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member", "agent_level")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `agent_level` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `status` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_member", "blacklist")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `blacklist` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member", "article_duration")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `article_duration` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member", "audio_duration")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `audio_duration` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member", "video_duration")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `video_duration` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member", "remark")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `remark` text;");
}
if(!pdo_fieldexists("fy_lesson_member", "coupon_tip")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `coupon_tip` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_member", "duration_uptime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `duration_uptime` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member", "uptime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `uptime` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_bind", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_bind")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_member_bind", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_bind")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_bind", "parentid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_bind")." ADD `parentid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member_bind", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_bind")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_bind", "operator_uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_bind")." ADD `operator_uid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member_bind", "operator_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_bind")." ADD `operator_name` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_member_bind", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_bind")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_buyteacher", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_buyteacher")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_member_buyteacher", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_buyteacher")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_buyteacher", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_buyteacher")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_buyteacher", "teacherid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_buyteacher")." ADD `teacherid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_buyteacher", "validity")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_buyteacher")." ADD `validity` bigint(20) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_buyteacher", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_buyteacher")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_buyteacher", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_buyteacher")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "amount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `amount` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "conditions")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `conditions` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "validity")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `validity` int(11);");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "use_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `use_type` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "category_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `category_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "lesson_ids")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `lesson_ids` text;");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "password")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `password` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "ordersn")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `ordersn` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `status` tinyint(4);");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "source")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `source` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "coupon_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `coupon_id` int(11);");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_coupon", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_coupon")." ADD `update_time` int(11);");
}
if(!pdo_fieldexists("fy_lesson_member_order", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_member_order", "acid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `acid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member_order", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_order", "ordersn")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `ordersn` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_order", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_order", "level_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `level_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_order", "level_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `level_name` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_member_order", "viptime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `viptime` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_order", "vipmoney")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `vipmoney` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_order", "discount_money")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `discount_money` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_member_order", "integral")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `integral` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member_order", "paytype")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `paytype` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_order", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member_order", "paytime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `paytime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_member_order", "member1")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `member1` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_order", "commission1")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `commission1` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_order", "member2")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `member2` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_order", "commission2")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `commission2` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_order", "member3")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `member3` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_order", "commission3")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `commission3` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_order", "refer_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `refer_id` int(11);");
}
if(!pdo_fieldexists("fy_lesson_member_order", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_order", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_order")." ADD `update_time` int(10);");
}
if(!pdo_fieldexists("fy_lesson_member_vip", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_vip")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_member_vip", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_vip")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_vip", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_vip")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_vip", "level_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_vip")." ADD `level_id` int(11);");
}
if(!pdo_fieldexists("fy_lesson_member_vip", "validity")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_vip")." ADD `validity` bigint(11);");
}
if(!pdo_fieldexists("fy_lesson_member_vip", "discount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_vip")." ADD `discount` int(4) DEFAULT '100';");
}
if(!pdo_fieldexists("fy_lesson_member_vip", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_vip")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_member_vip", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_member_vip")." ADD `update_time` int(11);");
}
if(!pdo_fieldexists("fy_lesson_navigation", "nav_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_navigation")." ADD `nav_id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_navigation", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_navigation")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_navigation", "nav_ident")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_navigation")." ADD `nav_ident` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_navigation", "nav_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_navigation")." ADD `nav_name` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_navigation", "unselected_icon")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_navigation")." ADD `unselected_icon` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_navigation", "selected_icon")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_navigation")." ADD `selected_icon` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_navigation", "url_link")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_navigation")." ADD `url_link` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_navigation", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_navigation")." ADD `displayorder` int(4) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_navigation", "is_pc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_navigation")." ADD `is_pc` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_navigation", "icon")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_navigation")." ADD `icon` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_navigation", "location")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_navigation")." ADD `location` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_navigation", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_navigation")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_navigation", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_navigation")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_order", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_order", "acid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `acid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_order", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_order", "ordersn")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `ordersn` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_order", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_order", "lesson_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `lesson_type` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "appoint_info")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `appoint_info` text;");
}
if(!pdo_fieldexists("fy_lesson_order", "spec_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `spec_id` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "spec_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `spec_name` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_order", "spec_day")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `spec_day` int(4);");
}
if(!pdo_fieldexists("fy_lesson_order", "verify_number")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `verify_number` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_order", "is_verify")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `is_verify` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "verify_info")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `verify_info` text;");
}
if(!pdo_fieldexists("fy_lesson_order", "lessonid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `lessonid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_order", "bookname")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `bookname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_order", "marketprice")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_order", "coupon")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `coupon` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_order", "coupon_amount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `coupon_amount` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_order", "price")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_order", "teacherid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `teacherid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_order", "teacher_income")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `teacher_income` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "company_uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `company_uid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "company_income")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `company_income` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "integral")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `integral` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "deduct_integral")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `deduct_integral` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "vip_discount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `vip_discount` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_order", "paytype")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `paytype` varchar(50) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "paytime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `paytime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "validity")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `validity` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "member1")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `member1` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "commission1")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `commission1` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_order", "member2")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `member2` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "commission2")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `commission2` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_order", "member3")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `member3` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "commission3")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `commission3` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_order", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "remark")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `remark` varchar(500);");
}
if(!pdo_fieldexists("fy_lesson_order", "admin_img")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `admin_img` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_order", "is_delete")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `is_delete` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order")." ADD `addtime` int(10);");
}
if(!pdo_fieldexists("fy_lesson_order_verify", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order_verify")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_order_verify", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order_verify")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_order_verify", "orderid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order_verify")." ADD `orderid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_order_verify", "verify_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order_verify")." ADD `verify_type` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_order_verify", "verify_uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order_verify")." ADD `verify_uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_order_verify", "verify_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order_verify")." ADD `verify_name` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_order_verify", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_order_verify")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_parent", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_parent", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_parent", "pid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `pid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "cid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `cid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "attribute1")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `attribute1` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "attribute2")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `attribute2` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "lesson_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `lesson_type` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "appoint_info")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `appoint_info` text;");
}
if(!pdo_fieldexists("fy_lesson_parent", "appoint_dir")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `appoint_dir` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_parent", "verify_number")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `verify_number` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_parent", "saler_uids")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `saler_uids` text;");
}
if(!pdo_fieldexists("fy_lesson_parent", "buynow_info")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `buynow_info` text;");
}
if(!pdo_fieldexists("fy_lesson_parent", "bookname")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `bookname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_parent", "price")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_parent", "live_info")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `live_info` text;");
}
if(!pdo_fieldexists("fy_lesson_parent", "isdiscount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `isdiscount` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_parent", "vipdiscount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `vipdiscount` int(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "vipdiscount_money")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `vipdiscount_money` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_parent", "integral")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `integral` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "integral_rate")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `integral_rate` decimal(5,1) DEFAULT '0.0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "deduct_integral")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `deduct_integral` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "recommend_free_limit")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `recommend_free_limit` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "recommend_free_num")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `recommend_free_num` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "recommend_free_day")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `recommend_free_day` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "images")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `images` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_parent", "poster")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `poster` text;");
}
if(!pdo_fieldexists("fy_lesson_parent", "descript")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `descript` longtext;");
}
if(!pdo_fieldexists("fy_lesson_parent", "stock")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `stock` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_parent", "buynum")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `buynum` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "virtual_buynum")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `virtual_buynum` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "vip_number")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `vip_number` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "teacher_number")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `teacher_number` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "visit_number")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `visit_number` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "score")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `score` decimal(5,2) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_parent", "teacherid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `teacherid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_parent", "commission")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `commission` text;");
}
if(!pdo_fieldexists("fy_lesson_parent", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "lesson_show")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `lesson_show` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "drag_play")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `drag_play` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_parent", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_parent", "show_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `show_time` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "section_status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `section_status` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_parent", "recommendid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `recommendid` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_parent", "vipview")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `vipview` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_parent", "teacher_income")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `teacher_income` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "award_income")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `award_income` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "company_income")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `company_income` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "link")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `link` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_parent", "validity")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `validity` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "share")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `share` text;");
}
if(!pdo_fieldexists("fy_lesson_parent", "support_coupon")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `support_coupon` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_parent", "poster_config")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `poster_config` text;");
}
if(!pdo_fieldexists("fy_lesson_parent", "ico_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `ico_name` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_parent", "poster_setting")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `poster_setting` text;");
}
if(!pdo_fieldexists("fy_lesson_parent", "poster_bg")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `poster_bg` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_parent", "service")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `service` text;");
}
if(!pdo_fieldexists("fy_lesson_parent", "like_lesson_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `like_lesson_type` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_parent", "like_lesson_content")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `like_lesson_content` text NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_parent", "like_goods_ids")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `like_goods_ids` text NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_parent", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_parent", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_parent")." ADD `update_time` int(11);");
}
if(!pdo_fieldexists("fy_lesson_playrecord", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_playrecord")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_playrecord", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_playrecord")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_playrecord", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_playrecord")." ADD `uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_playrecord", "lessonid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_playrecord")." ADD `lessonid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_playrecord", "sectionid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_playrecord")." ADD `sectionid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_playrecord", "playtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_playrecord")." ADD `playtime` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_playrecord", "duration")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_playrecord")." ADD `duration` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_playrecord", "is_end")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_playrecord")." ADD `is_end` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_playrecord", "playcount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_playrecord")." ADD `playcount` int(11) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_playrecord", "playtoken")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_playrecord")." ADD `playtoken` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_playrecord", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_playrecord")." ADD `addtime` int(10);");
}
if(!pdo_fieldexists("fy_lesson_poster", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_poster")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_poster", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_poster")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_poster", "poster_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_poster")." ADD `poster_name` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_poster", "poster_bg")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_poster")." ADD `poster_bg` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_poster", "poster_setting")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_poster")." ADD `poster_setting` text;");
}
if(!pdo_fieldexists("fy_lesson_poster", "poster_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_poster")." ADD `poster_type` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_poster", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_poster")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_poster", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_poster")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_qcloud_upload", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloud_upload")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_qcloud_upload", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloud_upload")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_qcloud_upload", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloud_upload")." ADD `uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_qcloud_upload", "teacherid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloud_upload")." ADD `teacherid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_qcloud_upload", "name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloud_upload")." ADD `name` varchar(500);");
}
if(!pdo_fieldexists("fy_lesson_qcloud_upload", "pid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloud_upload")." ADD `pid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_qcloud_upload", "cid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloud_upload")." ADD `cid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_qcloud_upload", "ccid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloud_upload")." ADD `ccid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_qcloud_upload", "com_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloud_upload")." ADD `com_name` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_qcloud_upload", "sys_link")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloud_upload")." ADD `sys_link` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_qcloud_upload", "size")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloud_upload")." ADD `size` decimal(10,2);");
}
if(!pdo_fieldexists("fy_lesson_qcloud_upload", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloud_upload")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_qcloudvod_upload", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloudvod_upload")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_qcloudvod_upload", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloudvod_upload")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_qcloudvod_upload", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloudvod_upload")." ADD `uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_qcloudvod_upload", "teacherid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloudvod_upload")." ADD `teacherid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_qcloudvod_upload", "name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloudvod_upload")." ADD `name` varchar(500);");
}
if(!pdo_fieldexists("fy_lesson_qcloudvod_upload", "pid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloudvod_upload")." ADD `pid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_qcloudvod_upload", "cid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloudvod_upload")." ADD `cid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_qcloudvod_upload", "ccid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloudvod_upload")." ADD `ccid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_qcloudvod_upload", "videoid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloudvod_upload")." ADD `videoid` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_qcloudvod_upload", "videourl")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloudvod_upload")." ADD `videourl` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_qcloudvod_upload", "size")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloudvod_upload")." ADD `size` decimal(10,2);");
}
if(!pdo_fieldexists("fy_lesson_qcloudvod_upload", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qcloudvod_upload")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_qiniu_upload", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qiniu_upload")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_qiniu_upload", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qiniu_upload")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_qiniu_upload", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qiniu_upload")." ADD `uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_qiniu_upload", "teacher")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qiniu_upload")." ADD `teacher` int(11);");
}
if(!pdo_fieldexists("fy_lesson_qiniu_upload", "name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qiniu_upload")." ADD `name` varchar(500);");
}
if(!pdo_fieldexists("fy_lesson_qiniu_upload", "pid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qiniu_upload")." ADD `pid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_qiniu_upload", "cid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qiniu_upload")." ADD `cid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_qiniu_upload", "ccid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qiniu_upload")." ADD `ccid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_qiniu_upload", "com_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qiniu_upload")." ADD `com_name` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_qiniu_upload", "qiniu_url")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qiniu_upload")." ADD `qiniu_url` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_qiniu_upload", "size")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qiniu_upload")." ADD `size` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_qiniu_upload", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_qiniu_upload")." ADD `addtime` int(10);");
}
if(!pdo_fieldexists("fy_lesson_recommend", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_recommend", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_recommend", "rec_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend")." ADD `rec_name` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_recommend", "icon")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend")." ADD `icon` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_recommend", "show_style")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend")." ADD `show_style` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_recommend", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend")." ADD `displayorder` int(4);");
}
if(!pdo_fieldexists("fy_lesson_recommend", "limit_number")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend")." ADD `limit_number` int(4) NOT NULL DEFAULT '6';");
}
if(!pdo_fieldexists("fy_lesson_recommend", "limit_number_pc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend")." ADD `limit_number_pc` int(4) NOT NULL DEFAULT '4';");
}
if(!pdo_fieldexists("fy_lesson_recommend", "is_show")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend")." ADD `is_show` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_recommend", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend")." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_recommend_activity", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_activity")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_recommend_activity", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_activity")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_recommend_activity", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_activity")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_recommend_activity", "lessonid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_activity")." ADD `lessonid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_recommend_activity", "bookname")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_activity")." ADD `bookname` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_recommend_activity", "images")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_activity")." ADD `images` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_recommend_activity", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_activity")." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_recommend_activity", "invite_number")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_activity")." ADD `invite_number` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_recommend_activity", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_activity")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_recommend_activity", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_activity")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_recommend_junior", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_junior")." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_recommend_junior", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_junior")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_recommend_junior", "activity_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_junior")." ADD `activity_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_recommend_junior", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_junior")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_recommend_junior", "lessonid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_junior")." ADD `lessonid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_recommend_junior", "junior_uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_junior")." ADD `junior_uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_recommend_junior", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_recommend_junior")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_setting", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_setting", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_setting", "logo")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `logo` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_setting", "manageopenid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `manageopenid` text NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_setting", "sitename")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `sitename` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_setting", "template")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `template` varchar(100) NOT NULL DEFAULT 'default';");
}
if(!pdo_fieldexists("fy_lesson_setting", "copyright")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `copyright` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_setting", "site_icp")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `site_icp` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_setting", "closespace")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `closespace` int(4) NOT NULL DEFAULT '60';");
}
if(!pdo_fieldexists("fy_lesson_setting", "closelast")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `closelast` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_setting", "savetype")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `savetype` tinyint(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_setting", "qiniu")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `qiniu` text NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_setting", "qcloud")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `qcloud` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "qcloudvod")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `qcloudvod` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "aliyunoss")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `aliyunoss` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "aliyunvod")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `aliyunvod` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "show_teacher_income")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `show_teacher_income` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_setting", "ios_pay")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `ios_pay` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_setting", "company_income")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `company_income` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_setting", "isfollow")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `isfollow` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "qrcode")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `qrcode` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_setting", "mustinfo")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `mustinfo` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_setting", "user_info")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `user_info` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "autogood")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `autogood` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_setting", "posterbg")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `posterbg` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "poster_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `poster_type` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_setting", "poster_config")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `poster_config` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "category_ico")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `category_ico` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_setting", "stock_config")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `stock_config` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_setting", "lesson_show")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `lesson_show` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_setting", "follow_word")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `follow_word` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_setting", "audit_evaluate")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `audit_evaluate` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_setting", "show_evaluate_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `show_evaluate_time` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_setting", "show_study_number")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `show_study_number` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_setting", "repeat_record_lesson")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `repeat_record_lesson` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_setting", "login_visit")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `login_visit` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "visit_limit")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `visit_limit` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_setting", "show_newlesson")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `show_newlesson` tinyint(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_setting", "lesson_follow_title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `lesson_follow_title` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_setting", "lesson_follow_desc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `lesson_follow_desc` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_setting", "sms")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `sms` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "modify_mobile")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `modify_mobile` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_setting", "qun_service")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `qun_service` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "index_verify")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `index_verify` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "common")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `common` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "front_color")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `front_color` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "lesson_poster_status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `lesson_poster_status` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_setting", "lesson_vip_status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `lesson_vip_status` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_setting", "lesson_agreement")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `lesson_agreement` mediumtext;");
}
if(!pdo_fieldexists("fy_lesson_setting", "privacy_agreement")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `privacy_agreement` mediumtext;");
}
if(!pdo_fieldexists("fy_lesson_setting", "teacher_agreement")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `teacher_agreement` mediumtext;");
}
if(!pdo_fieldexists("fy_lesson_setting", "lesson_config")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `lesson_config` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "video_live")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `video_live` text;");
}
if(!pdo_fieldexists("fy_lesson_setting", "im_config")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `im_config` text NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_setting", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting")." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "sitename")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `sitename` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "template")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `template` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "logo")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `logo` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "bottom_logo")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `bottom_logo` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "mobile_qrcode")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `mobile_qrcode` varbinary(255);");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "favicon_icon")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `favicon_icon` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "login_visit")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `login_visit` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "payment")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `payment` text NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "video_watermark")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `video_watermark` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "teacher_contact")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `teacher_contact` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "site_root")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `site_root` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "mobile_site_root")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `mobile_site_root` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "jump_setting")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `jump_setting` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "hot_search")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `hot_search` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "service_right_pic")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `service_right_pic` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "service_right_url")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `service_right_url` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "new_notice")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `new_notice` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "rec_teacher")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `rec_teacher` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "new_lesson")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `new_lesson` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "friendly_link")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `friendly_link` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "company_info")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `company_info` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "site_icp")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `site_icp` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "site_network")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `site_network` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "site_culture")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `site_culture` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "site_added")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `site_added` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "login_register")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `login_register` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "front_css")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `front_css` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "keywords")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `keywords` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "description")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `description` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "teacher_platform")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `teacher_platform` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "about_title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `about_title` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "aboutus_desc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `aboutus_desc` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "culture_desc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `culture_desc` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "environment_desc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `environment_desc` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "contact_desc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `contact_desc` text;");
}
if(!pdo_fieldexists("fy_lesson_setting_pc", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_setting_pc")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_signin", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_signin")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_signin", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_signin")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_signin", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_signin")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_signin", "award")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_signin")." ADD `award` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_signin", "timer")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_signin")." ADD `timer` tinyint(3) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_signin", "days")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_signin")." ADD `days` tinyint(3) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_signin", "sign_date")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_signin")." ADD `sign_date` date NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_son", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_son", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_son", "parentid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `parentid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_son", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_son", "title_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `title_id` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_son", "images")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `images` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_son", "savetype")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `savetype` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_son", "sectiontype")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `sectiontype` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_son", "videourl")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `videourl` text;");
}
if(!pdo_fieldexists("fy_lesson_son", "videotime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `videotime` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_son", "content")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `content` longtext;");
}
if(!pdo_fieldexists("fy_lesson_son", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_son", "is_free")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `is_free` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_son", "test_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `test_time` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_son", "is_live")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `is_live` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_son", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `status` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_son", "auto_show")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `auto_show` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_son", "show_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `show_time` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_son", "password")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `password` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_son", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_son")." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_spec", "spec_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_spec")." ADD `spec_id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_spec", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_spec")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_spec", "lessonid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_spec")." ADD `lessonid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_spec", "spec_day")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_spec")." ADD `spec_day` int(11);");
}
if(!pdo_fieldexists("fy_lesson_spec", "spec_price")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_spec")." ADD `spec_price` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_spec", "spec_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_spec")." ADD `spec_name` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_spec", "spec_stock")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_spec")." ADD `spec_stock` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_spec", "spec_sort")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_spec")." ADD `spec_sort` int(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_spec", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_spec")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_static", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_static")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_static", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_static")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_static", "lessonOrder_num")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_static")." ADD `lessonOrder_num` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_static", "lessonOrder_amount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_static")." ADD `lessonOrder_amount` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_static", "vipOrder_num")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_static")." ADD `vipOrder_num` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_static", "vipOrder_amount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_static")." ADD `vipOrder_amount` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_static", "teacherOrder_num")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_static")." ADD `teacherOrder_num` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_static", "teacherOrder_amount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_static")." ADD `teacherOrder_amount` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_static", "static_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_static")." ADD `static_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_study_duration", "study_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_study_duration")." ADD `study_id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_study_duration", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_study_duration")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_study_duration", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_study_duration")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_study_duration", "date")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_study_duration")." ADD `date` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_study_duration", "article")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_study_duration")." ADD `article` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_study_duration", "audio")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_study_duration")." ADD `audio` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_study_duration", "video")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_study_duration")." ADD `video` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_study_duration", "exchange")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_study_duration")." ADD `exchange` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_study_duration", "ranking")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_study_duration")." ADD `ranking` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_study_duration", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_study_duration")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_subscribe_msg", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_subscribe_msg")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_subscribe_msg", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_subscribe_msg")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_subscribe_msg", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_subscribe_msg")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_subscribe_msg", "openid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_subscribe_msg")." ADD `openid` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_subscribe_msg", "subscribe")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_subscribe_msg")." ADD `subscribe` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_subscribe_msg", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_subscribe_msg")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_subscribe_msg", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_subscribe_msg")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_suggest", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_suggest", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_suggest", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_suggest", "category_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest")." ADD `category_id` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_suggest", "content")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest")." ADD `content` varchar(3000) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_suggest", "mobile")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest")." ADD `mobile` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("fy_lesson_suggest", "picture")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest")." ADD `picture` text;");
}
if(!pdo_fieldexists("fy_lesson_suggest", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest")." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_suggest", "remark")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest")." ADD `remark` text;");
}
if(!pdo_fieldexists("fy_lesson_suggest", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_suggest", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_suggest_category", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest_category")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_suggest_category", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest_category")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_suggest_category", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest_category")." ADD `title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_suggest_category", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest_category")." ADD `displayorder` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_suggest_category", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_suggest_category")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_syslog", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_syslog")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_syslog", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_syslog")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_syslog", "admin_uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_syslog")." ADD `admin_uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_syslog", "admin_username")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_syslog")." ADD `admin_username` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_syslog", "log_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_syslog")." ADD `log_type` tinyint(1);");
}
if(!pdo_fieldexists("fy_lesson_syslog", "function")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_syslog")." ADD `function` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_syslog", "content")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_syslog")." ADD `content` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_syslog", "ip")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_syslog")." ADD `ip` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_syslog", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_syslog")." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_teacher", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `uid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher", "openid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `openid` varchar(100) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher", "cate_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `cate_id` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher", "teacher_income")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `teacher_income` tinyint(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher", "account")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `account` varchar(20);");
}
if(!pdo_fieldexists("fy_lesson_teacher", "password")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `password` varchar(32);");
}
if(!pdo_fieldexists("fy_lesson_teacher", "teacher")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `teacher` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher", "idcard")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `idcard` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_teacher", "mobile")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `mobile` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_teacher", "is_distribution")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `is_distribution` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher", "commission")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `commission` text;");
}
if(!pdo_fieldexists("fy_lesson_teacher", "qq")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `qq` varchar(20);");
}
if(!pdo_fieldexists("fy_lesson_teacher", "qqgroup")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `qqgroup` varchar(20);");
}
if(!pdo_fieldexists("fy_lesson_teacher", "qqgroupLink")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `qqgroupLink` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_teacher", "weixin_qrcode")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `weixin_qrcode` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher", "online_url")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `online_url` varchar(500);");
}
if(!pdo_fieldexists("fy_lesson_teacher", "teacher_bg")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `teacher_bg` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_teacher", "teacher_bg_pc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `teacher_bg_pc` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_teacher", "first_letter")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `first_letter` varchar(10);");
}
if(!pdo_fieldexists("fy_lesson_teacher", "teacherdes")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `teacherdes` text;");
}
if(!pdo_fieldexists("fy_lesson_teacher", "digest")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `digest` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher", "teacherphoto")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `teacherphoto` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_teacher", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `status` tinyint(1) NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists("fy_lesson_teacher", "is_recommend")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `is_recommend` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher", "upload")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `upload` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_teacher", "addlive")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `addlive` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_teacher", "avoid_audit")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `avoid_audit` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher", "teacher_home_show")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `teacher_home_show` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher", "addexam")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `addexam` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_teacher", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher", "company_uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `company_uid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_category", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_category")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_teacher_category", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_category")." ADD `uniacid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_category", "name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_category")." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_category", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_category")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher_category", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_category")." ADD `status` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_teacher_category", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_category")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_income", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_income")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_teacher_income", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_income")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_teacher_income", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_income")." ADD `uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_teacher_income", "teacher")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_income")." ADD `teacher` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_teacher_income", "ordersn")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_income")." ADD `ordersn` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_teacher_income", "ordertype")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_income")." ADD `ordertype` tinyint(1) NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists("fy_lesson_teacher_income", "bookname")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_income")." ADD `bookname` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_teacher_income", "orderprice")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_income")." ADD `orderprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_teacher_income", "teacher_income")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_income")." ADD `teacher_income` tinyint(3);");
}
if(!pdo_fieldexists("fy_lesson_teacher_income", "income_amount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_income")." ADD `income_amount` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_teacher_income", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_income")." ADD `addtime` int(10);");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "ordersn")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `ordersn` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "ordertime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `ordertime` int(11);");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "price")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `price` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "integral")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `integral` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "paytype")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `paytype` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "paytime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `paytime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "member1")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `member1` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "commission1")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `commission1` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "member2")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `member2` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "commission2")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `commission2` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "member3")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `member3` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "commission3")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `commission3` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "teacherid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `teacherid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "teacher_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `teacher_name` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "teacher_income")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `teacher_income` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_order", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_order")." ADD `update_time` int(10);");
}
if(!pdo_fieldexists("fy_lesson_teacher_price", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_price")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_teacher_price", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_price")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_price", "teacherid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_price")." ADD `teacherid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_price", "price")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_price")." ADD `price` decimal(10,0) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_price", "validity_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_price")." ADD `validity_time` int(4) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_price", "teacher_income")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_price")." ADD `teacher_income` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher_price", "integral")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_price")." ADD `integral` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_teacher_price", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_price")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_teacher_price", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_teacher_price")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_title", "title_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_title")." ADD `title_id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_title", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_title")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_title", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_title")." ADD `title` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_title", "lesson_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_title")." ADD `lesson_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_title", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_title")." ADD `displayorder` int(4) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_title", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_title")." ADD `update_time` int(11);");
}
if(!pdo_fieldexists("fy_lesson_title", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_title")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "buysucc")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `buysucc` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "buysucc_format")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `buysucc_format` text;");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "cnotice")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `cnotice` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "cnotice_format")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `cnotice_format` text;");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "newjoin")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `newjoin` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "newjoin_format")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `newjoin_format` text;");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "newlesson")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `newlesson` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "neworder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `neworder` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "neworder_format")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `neworder_format` text;");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "newcash")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `newcash` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "newcash_format")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `newcash_format` text;");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "apply_teacher")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `apply_teacher` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "apply_teacher_format")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `apply_teacher_format` text;");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "receive_coupon")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `receive_coupon` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "receive_coupon_format")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `receive_coupon_format` text;");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "teacher_notice")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `teacher_notice` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "teacher_notice_format")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `teacher_notice_format` text;");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "recommend_junior")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `recommend_junior` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "deliver")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `deliver` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "deliver_format")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `deliver_format` text;");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "grade")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `grade` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "grade_format")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `grade_format` text;");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_tplmessage", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_tplmessage")." ADD `update_time` int(11);");
}
if(!pdo_fieldexists("fy_lesson_video_category", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_video_category")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_video_category", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_video_category")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_video_category", "parentid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_video_category")." ADD `parentid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_video_category", "teacherid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_video_category")." ADD `teacherid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_video_category", "name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_video_category")." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_video_category", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_video_category")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_video_category", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_video_category")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_vip_level", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vip_level")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_vip_level", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vip_level")." ADD `uniacid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_vip_level", "level_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vip_level")." ADD `level_name` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_vip_level", "level_icon")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vip_level")." ADD `level_icon` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_vip_level", "level_validity")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vip_level")." ADD `level_validity` int(11);");
}
if(!pdo_fieldexists("fy_lesson_vip_level", "level_price")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vip_level")." ADD `level_price` decimal(10,2);");
}
if(!pdo_fieldexists("fy_lesson_vip_level", "integral")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vip_level")." ADD `integral` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_vip_level", "discount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vip_level")." ADD `discount` int(4) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_vip_level", "open_discount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vip_level")." ADD `open_discount` tinyint(3) NOT NULL DEFAULT '100';");
}
if(!pdo_fieldexists("fy_lesson_vip_level", "renew_discount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vip_level")." ADD `renew_discount` tinyint(3) NOT NULL DEFAULT '100';");
}
if(!pdo_fieldexists("fy_lesson_vip_level", "sort")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vip_level")." ADD `sort` int(4) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_vip_level", "is_show")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vip_level")." ADD `is_show` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_vip_level", "commission")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vip_level")." ADD `commission` text;");
}
if(!pdo_fieldexists("fy_lesson_vip_level", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vip_level")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_vip_level", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vip_level")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_vipcard", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vipcard")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_vipcard", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vipcard")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_vipcard", "card_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vipcard")." ADD `card_id` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_vipcard", "password")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vipcard")." ADD `password` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_vipcard", "viptime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vipcard")." ADD `viptime` decimal(10,2);");
}
if(!pdo_fieldexists("fy_lesson_vipcard", "level_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vipcard")." ADD `level_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_vipcard", "is_use")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vipcard")." ADD `is_use` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_vipcard", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vipcard")." ADD `nickname` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_vipcard", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vipcard")." ADD `uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_vipcard", "ordersn")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vipcard")." ADD `ordersn` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_vipcard", "use_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vipcard")." ADD `use_time` int(10);");
}
if(!pdo_fieldexists("fy_lesson_vipcard", "validity")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vipcard")." ADD `validity` int(10);");
}
if(!pdo_fieldexists("fy_lesson_vipcard", "own_uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vipcard")." ADD `own_uid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_vipcard", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_vipcard")." ADD `addtime` int(10) unsigned;");
}
