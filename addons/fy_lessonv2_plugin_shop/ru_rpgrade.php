<?php
$sql="
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `uid` int(11) NOT NULL,
  `realname` varchar(50) NOT NULL COMMENT '收货人姓名',
  `mobile` varchar(11) NOT NULL COMMENT '收货人手机',
  `province` varchar(50) NOT NULL COMMENT '省份',
  `city` varchar(50) NOT NULL COMMENT '城市',
  `area` varchar(50) NOT NULL COMMENT '区域',
  `address` varchar(255) NOT NULL COMMENT '详细地址',
  `isdefault` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认地址',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '已删除',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `isdefault` (`isdefault`),
  KEY `deleted` (`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收货地址';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_attr` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `title` varchar(100) NOT NULL COMMENT '规格名',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `title` (`title`),
  KEY `uniacid_2` (`uniacid`),
  KEY `title_2` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品规格';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_banner` (
  `banner_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `banner_name` varchar(255) DEFAULT NULL COMMENT '广告位名称',
  `picture` varchar(255) NOT NULL COMMENT '图片路径',
  `link` varchar(255) DEFAULT NULL COMMENT '图片链接',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0.不显示 1.显示',
  `banner_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '图片类型(位置)',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`banner_id`),
  KEY `uniacid` (`uniacid`),
  KEY `is_show` (`is_show`),
  KEY `banner_type` (`banner_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告位图片';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `uid` int(11) NOT NULL COMMENT '用户编号',
  `goods_id` int(11) NOT NULL COMMENT '商品编号',
  `goods_type` tinyint(1) DEFAULT '0' COMMENT '商品性质 1.实物 2.虚拟',
  `sku_id` int(11) NOT NULL DEFAULT '0' COMMENT '规格编号',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `total` int(11) NOT NULL DEFAULT '1' COMMENT '数量',
  `status` tinyint(1) NOT NULL COMMENT '状态 1.正常 -1失效',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `goods_id` (`goods_id`),
  KEY `sku_id` (`sku_id`),
  KEY `addtime` (`addtime`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `name` varchar(255) NOT NULL COMMENT '分类名称',
  `parentid` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类id(0:顶级分类)',
  `icon` varchar(255) DEFAULT NULL COMMENT '分类图片',
  `link` varchar(500) DEFAULT NULL COMMENT '自定义链接',
  `adv_cover` varchar(255) DEFAULT NULL COMMENT '广告图片',
  `adv_link` varchar(500) DEFAULT NULL COMMENT '广告链接',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0.下架 1.上架',
  `addtime` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `name` (`name`),
  KEY `parentid` (`parentid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品分类';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_commission_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `orderid` int(11) NOT NULL COMMENT '订单id',
  `uid` int(11) NOT NULL COMMENT '用户编号',
  `goods_name` varchar(1000) DEFAULT NULL COMMENT '商品名称',
  `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单总额',
  `commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金',
  `grade` tinyint(4) DEFAULT NULL COMMENT '等级 1.一级佣金 2.二级佣金 3.三级佣金',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `buyer_uid` int(11) DEFAULT NULL COMMENT '购买者编号',
  `status` tinyint(4) NOT NULL COMMENT '状态 0.待发放 1.已发放',
  `predict_sendtime` int(11) DEFAULT NULL COMMENT '预计发放佣金时间',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`),
  KEY `grade` (`grade`),
  KEY `remark` (`remark`),
  KEY `status` (`status`),
  KEY `buyer_uid` (`buyer_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='佣金记录';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_diy_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `page_title` varchar(255) NOT NULL,
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


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_express` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `title` varchar(255) NOT NULL COMMENT '快递名称',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '默认费用',
  `content` text NOT NULL COMMENT '区域费用明细',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0.关闭 1.开启',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='快递方式';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `title` varchar(255) NOT NULL COMMENT '商品名称',
  `cover` varchar(255) NOT NULL COMMENT '封面图',
  `goods_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '商品性质 1.实物 2.虚拟物品',
  `sell_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '出售方式: 1.积分兑换 2.现金购买 3.积分+现金',
  `express_ids` text COMMENT '配送快递id集(json格式保存)',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价格',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '销售价格',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '兑换积分',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '总库存',
  `spec_switch` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品规格开关 0.关闭 1.开启',
  `unit` varchar(50) DEFAULT NULL COMMENT '单位',
  `sales` int(11) NOT NULL DEFAULT '0' COMMENT '真实销量',
  `virtual_sales` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟销量',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '一级分类id',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '二级分类id',
  `ccid` int(11) NOT NULL DEFAULT '0' COMMENT '三级分类id',
  `album` text COMMENT '商品照片集(json格式保存)',
  `content` longtext COMMENT '商品详情',
  `visits` int(11) NOT NULL DEFAULT '0' COMMENT '浏览人数',
  `score` decimal(2,1) NOT NULL DEFAULT '0.0' COMMENT '评分',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `share` text COMMENT '自定义分享信息',
  `minus_total` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1.付款减库存 2.拍下减库存',
  `icon_name` varchar(50) DEFAULT NULL COMMENT '商品右上角标识(热门、新品、推荐)',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0.下架 1.上架',
  `order_buy_num` int(4) NOT NULL DEFAULT '0' COMMENT '单次最多购买数量 0.不限制',
  `extend` text COMMENT '扩展内容',
  `poster_bg` varchar(255) DEFAULT NULL COMMENT '自定义海报背景',
  `poster_setting` text COMMENT '自定义海报参数',
  `commission` text COMMENT '分销佣金(json格式保存)',
  `goods_like_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐商品类型 0.全部商品 1.指定商品',
  `like_goods_ids` text COMMENT '推荐指定商品ID',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `title` (`title`),
  KEY `is_virtual` (`goods_type`),
  KEY `sale_type` (`sell_type`),
  KEY `pid` (`pid`),
  KEY `cid` (`cid`),
  KEY `ccid` (`ccid`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品主表';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_goods_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `orderid` int(11) unsigned NOT NULL COMMENT '订单id',
  `ordersn` varchar(255) NOT NULL COMMENT '订单编号',
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `title` varchar(255) NOT NULL COMMENT '商品名称',
  `sku_name` varchar(255) NOT NULL DEFAULT '' COMMENT '规格名称',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `virtual_nickname` varchar(50) NOT NULL COMMENT '虚拟用户昵称',
  `virtual_avatar` varchar(255) NOT NULL COMMENT '虚拟用户头像',
  `score` decimal(2,1) NOT NULL DEFAULT '5.0' COMMENT '评分(5分制)',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '评价内容',
  `picture` text COMMENT '评价晒图(json格式保存)',
  `reply` varchar(1000) DEFAULT '' COMMENT '评价回复',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '评论状态 -1.审核未通过 0.待审核 1.审核通过',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '评价类型 0.系统默认好评  1.用户评价',
  `addtime` int(10) NOT NULL COMMENT '评价时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `ordersn` (`ordersn`),
  KEY `goods_id` (`goods_id`),
  KEY `title` (`title`),
  KEY `score` (`score`),
  KEY `status` (`status`),
  KEY `type` (`type`),
  KEY `orderid` (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品评价';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_navigation` (
  `nav_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `nav_name` varchar(100) NOT NULL COMMENT '导航名称',
  `menu_icon` varchar(255) DEFAULT NULL COMMENT '菜单图标',
  `url_link` varchar(1000) DEFAULT NULL COMMENT '跳转链接',
  `location` varchar(100) NOT NULL COMMENT '导航位置：rightTop-手机端右上角快捷菜单',
  `displayorder` int(4) DEFAULT '0' COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`nav_id`),
  KEY `uniacid` (`uniacid`),
  KEY `location` (`location`),
  KEY `nav_name` (`nav_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='导航表';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_notice` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `title_color` varchar(50) DEFAULT NULL COMMENT '标题颜色',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '公告分类',
  `author` varchar(100) DEFAULT NULL COMMENT '作者',
  `content` longtext COMMENT '内容',
  `linkurl` varchar(1000) DEFAULT NULL COMMENT '原文链接',
  `images` varchar(255) DEFAULT NULL COMMENT '分享图片',
  `describes` varchar(500) DEFAULT NULL COMMENT '分享描述',
  `identify` varchar(1000) DEFAULT NULL COMMENT '公告标识(json_格式保存)',
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
  KEY `cate_id` (`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商城公告';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_notice_category` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='通知公告分类';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `ordersn` varchar(255) NOT NULL COMMENT '订单编号',
  `uid` int(11) NOT NULL COMMENT '用户编号',
  `title` varchar(1000) DEFAULT NULL COMMENT '所有商品标题',
  `total` int(11) NOT NULL COMMENT '数量',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '商品积分',
  `remark` varchar(500) DEFAULT NULL COMMENT '备注',
  `receive_name` varchar(100) DEFAULT NULL COMMENT '收货人姓名',
  `receive_mobile` varchar(50) DEFAULT NULL COMMENT '收货人手机',
  `address` varchar(255) DEFAULT NULL COMMENT '收货人地址',
  `shipping` varchar(100) DEFAULT NULL COMMENT '配送方式',
  `express_info` text COMMENT '快递物流信息(多个物流以json格式保存)',
  `express_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '快递费用',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单总金额',
  `paytype` varchar(50) NOT NULL COMMENT '支付方式',
  `paytime` int(11) NOT NULL COMMENT '支付时间',
  `member1` int(11) NOT NULL DEFAULT '0' COMMENT '一级会员编号',
  `commission1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '一级佣金',
  `member2` int(11) NOT NULL DEFAULT '0' COMMENT '二级会员编号',
  `commission2` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '二级佣金',
  `member3` int(11) NOT NULL DEFAULT '0' COMMENT '三级会员编号',
  `commission3` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '三级佣金',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态',
  `commented` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否评价 0.未评价 1.已评价',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '逻辑删除 0.未删除 1.已删除',
  `is_virtual` tinyint(1) NOT NULL DEFAULT '0' COMMENT '虚拟订单 0.否 1.是',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `deliver_time` int(11) DEFAULT NULL COMMENT '发货时间',
  `finish_time` int(11) DEFAULT NULL COMMENT '确认收货时间',
  `auto_finish_time` int(11) DEFAULT NULL COMMENT '自动收货时间',
  `refund_time` int(11) DEFAULT NULL COMMENT '退款完成时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ordersn` (`ordersn`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `price` (`price`),
  KEY `integral` (`integral`),
  KEY `paytype` (`paytype`),
  KEY `paytime` (`paytime`),
  KEY `status` (`status`),
  KEY `title` (`title`(255)),
  KEY `deleted` (`deleted`),
  KEY `commented` (`commented`),
  KEY `receive_name` (`receive_name`),
  KEY `receive_mobile` (`receive_mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单主表';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_order_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `orderid` int(11) NOT NULL COMMENT '订单编号',
  `goods_id` int(11) NOT NULL COMMENT '商品编号',
  `title` varchar(255) DEFAULT NULL COMMENT '商品名称',
  `goods_type` tinyint(1) NOT NULL COMMENT '商品类型',
  `cover` varchar(255) DEFAULT NULL COMMENT '封面图',
  `sell_type` tinyint(1) NOT NULL COMMENT '出售方式',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '兑换积分',
  `total` int(11) NOT NULL DEFAULT '0' COMMENT '购买数量',
  `sku_id` int(11) DEFAULT NULL COMMENT '规格编号',
  `sku_name` varchar(50) DEFAULT NULL COMMENT '规格名称',
  `virtual_text` text COMMENT '虚拟卡密信息',
  `commission1` decimal(10,2) DEFAULT '0.00' COMMENT '一级佣金',
  `commission2` decimal(10,2) DEFAULT '0.00' COMMENT '二级佣金',
  `commission3` decimal(10,2) DEFAULT '0.00' COMMENT '三级佣金',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态',
  `paytime` int(11) DEFAULT NULL COMMENT '支付时间',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `orderid` (`orderid`),
  KEY `goods_id` (`goods_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单商品表';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_refund` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `orderid` int(11) NOT NULL COMMENT '订单id',
  `refund_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退款金额',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '退款积分',
  `reason` varchar(100) NOT NULL COMMENT '退款理由',
  `express_title` varchar(100) DEFAULT NULL COMMENT '退货快递公司名称',
  `express_code` varchar(100) DEFAULT NULL COMMENT '退货快递公司编码',
  `express_no` varchar(100) DEFAULT NULL COMMENT '退货物流单号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 -1.拒绝 0.未审核 1.同意退款 2.完成退款',
  `addtime` int(11) NOT NULL COMMENT '申请时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `orderid` (`orderid`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单申请退款';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_refund_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `refund_id` int(11) NOT NULL COMMENT '退款id(与shop_order_refund表id对应)',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `content` varchar(1000) DEFAULT NULL COMMENT '内容',
  `picture` text COMMENT '图片',
  `role` tinyint(1) NOT NULL DEFAULT '0' COMMENT '角色 0.商家 1.用户 2.系统',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单退款操作日志';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_refund_reason` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `title` varchar(100) NOT NULL COMMENT '退货理由',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='退货原因';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `is_sale` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商城分销功能 0.关闭 1.开启',
  `sitename` varchar(255) DEFAULT NULL COMMENT '商城标题',
  `copyright` varchar(255) DEFAULT NULL COMMENT '商城版权',
  `audit_comment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单评价审核',
  `sysnc_right_menu` tinyint(1) NOT NULL DEFAULT '0' COMMENT '同步课堂右侧悬浮菜单',
  `close_order` int(11) NOT NULL DEFAULT '0' COMMENT '未付款订单关闭时间(分钟)',
  `finish_order` int(11) NOT NULL DEFAULT '7' COMMENT '自动确认收货时间(天)',
  `send_commission` int(11) NOT NULL DEFAULT '7' COMMENT '订单完成后发放佣金(天)',
  `refund_order` int(11) NOT NULL DEFAULT '7' COMMENT '确认收货后可申请退款(天)',
  `comment_order` int(11) NOT NULL DEFAULT '15' COMMENT '确认订单后可评价(天)',
  `express_config` text COMMENT '快递查询接口参数(json格式保存)',
  `visit_limit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '微信外访问 0.禁止 1.允许',
  `share_shop` text COMMENT '分享商城首页(json格式保存)',
  `share_goods` text COMMENT '分享商品(json格式保存)',
  `manageopenid` text COMMENT '管理员openid',
  `refund_receive` varchar(1000) DEFAULT NULL COMMENT '默认退货收货人地址',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_sku` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `value_ids` varchar(1000) DEFAULT NULL COMMENT '规格值id集(以英文,分开)',
  `cover` varchar(255) DEFAULT NULL COMMENT '封面图',
  `unit` varchar(50) DEFAULT NULL COMMENT '单位',
  `integral` int(11) unsigned DEFAULT '0' COMMENT '兑换积分',
  `market_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '市场价',
  `price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '销售价',
  `total` int(11) unsigned DEFAULT '0' COMMENT '库存',
  `sales` int(11) unsigned DEFAULT '0' COMMENT '销量',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品库存';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_syslog` (
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
  KEY `uniacid` (`uniacid`),
  KEY `admin_uid` (`admin_uid`),
  KEY `log_type` (`log_type`),
  KEY `function` (`function`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='操作日志记录';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_value` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `attr_id` int(11) NOT NULL COMMENT '规格id(对应表fy_lesson_plugin_shop_attr)',
  `value` varchar(100) NOT NULL COMMENT '规格值',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品规格值';


CREATE TABLE IF NOT EXISTS `ims_fy_lesson_plugin_shop_virtual` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号编号',
  `goods_id` int(11) NOT NULL COMMENT '商品编号',
  `value` varchar(255) NOT NULL COMMENT '虚拟物品信息',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0.未出售 1.已出售',
  `ordersn` varchar(255) DEFAULT NULL COMMENT '订单编号',
  `uid` int(11) DEFAULT NULL COMMENT '购买用户编号',
  `buy_time` int(11) DEFAULT NULL COMMENT '购买时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `goods_id` (`goods_id`),
  KEY `value` (`value`),
  KEY `status` (`status`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='虚拟商品信息';

";
pdo_run($sql);
if(!pdo_fieldexists("fy_lesson_plugin_shop_address", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_address")." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_address", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_address")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_address", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_address")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_address", "realname")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_address")." ADD `realname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_address", "mobile")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_address")." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_address", "province")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_address")." ADD `province` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_address", "city")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_address")." ADD `city` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_address", "area")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_address")." ADD `area` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_address", "address")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_address")." ADD `address` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_address", "isdefault")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_address")." ADD `isdefault` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_address", "deleted")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_address")." ADD `deleted` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_attr", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_attr")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_attr", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_attr")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_attr", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_attr")." ADD `title` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_attr", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_attr")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_attr", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_attr")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_banner", "banner_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_banner")." ADD `banner_id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_banner", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_banner")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_banner", "banner_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_banner")." ADD `banner_name` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_banner", "picture")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_banner")." ADD `picture` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_banner", "link")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_banner")." ADD `link` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_banner", "is_show")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_banner")." ADD `is_show` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_banner", "banner_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_banner")." ADD `banner_type` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_banner", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_banner")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_banner", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_banner")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_banner", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_banner")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_cart", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_cart")." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_cart", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_cart")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_cart", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_cart")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_cart", "goods_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_cart")." ADD `goods_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_cart", "goods_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_cart")." ADD `goods_type` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_cart", "sku_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_cart")." ADD `sku_id` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_cart", "price")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_cart")." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_cart", "integral")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_cart")." ADD `integral` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_cart", "total")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_cart")." ADD `total` int(11) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_cart", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_cart")." ADD `status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_cart", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_cart")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_category", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_category")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_category", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_category")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_category", "name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_category")." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_category", "parentid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_category")." ADD `parentid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_category", "icon")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_category")." ADD `icon` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_category", "link")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_category")." ADD `link` varchar(500);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_category", "adv_cover")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_category")." ADD `adv_cover` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_category", "adv_link")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_category")." ADD `adv_link` varchar(500);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_category", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_category")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_category", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_category")." ADD `status` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_category", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_category")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_category", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_category")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_commission_log", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_commission_log")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_commission_log", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_commission_log")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_commission_log", "orderid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_commission_log")." ADD `orderid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_commission_log", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_commission_log")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_commission_log", "goods_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_commission_log")." ADD `goods_name` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_commission_log", "order_amount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_commission_log")." ADD `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_commission_log", "commission")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_commission_log")." ADD `commission` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_commission_log", "grade")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_commission_log")." ADD `grade` tinyint(4);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_commission_log", "remark")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_commission_log")." ADD `remark` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_commission_log", "buyer_uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_commission_log")." ADD `buyer_uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_commission_log", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_commission_log")." ADD `status` tinyint(4) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_commission_log", "predict_sendtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_commission_log")." ADD `predict_sendtime` int(11);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_commission_log", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_commission_log")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_diy_template", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_diy_template")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_diy_template", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_diy_template")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_diy_template", "page_title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_diy_template")." ADD `page_title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_diy_template", "page_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_diy_template")." ADD `page_type` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_diy_template", "cover")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_diy_template")." ADD `cover` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_diy_template", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_diy_template")." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_diy_template", "data")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_diy_template")." ADD `data` longtext NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_diy_template", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_diy_template")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_diy_template", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_diy_template")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_express", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_express")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_express", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_express")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_express", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_express")." ADD `title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_express", "price")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_express")." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_express", "content")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_express")." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_express", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_express")." ADD `status` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_express", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_express")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_express", "remark")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_express")." ADD `remark` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_express", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_express")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_express", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_express")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "cover")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `cover` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "goods_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `goods_type` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "sell_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `sell_type` tinyint(1) NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "express_ids")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `express_ids` text;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "market_price")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `market_price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "price")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "integral")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `integral` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "stock")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `stock` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "spec_switch")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `spec_switch` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "unit")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `unit` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "sales")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `sales` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "virtual_sales")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `virtual_sales` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "pid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `pid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "cid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `cid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "ccid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `ccid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "album")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `album` text;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "content")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `content` longtext;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "visits")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `visits` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "score")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `score` decimal(2,1) NOT NULL DEFAULT '0.0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "share")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `share` text;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "minus_total")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `minus_total` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "icon_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `icon_name` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "order_buy_num")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `order_buy_num` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "extend")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `extend` text;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "poster_bg")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `poster_bg` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "poster_setting")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `poster_setting` text;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "commission")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `commission` text;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "goods_like_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `goods_like_type` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "like_goods_ids")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `like_goods_ids` text;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "orderid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `orderid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "ordersn")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `ordersn` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "goods_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `goods_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "sku_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `sku_name` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "virtual_nickname")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `virtual_nickname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "virtual_avatar")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `virtual_avatar` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "score")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `score` decimal(2,1) NOT NULL DEFAULT '5.0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "content")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `content` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "picture")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `picture` text;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "reply")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `reply` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `status` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `type` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_goods_comment", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_goods_comment")." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_navigation", "nav_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_navigation")." ADD `nav_id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_navigation", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_navigation")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_navigation", "nav_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_navigation")." ADD `nav_name` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_navigation", "menu_icon")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_navigation")." ADD `menu_icon` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_navigation", "url_link")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_navigation")." ADD `url_link` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_navigation", "location")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_navigation")." ADD `location` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_navigation", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_navigation")." ADD `displayorder` int(4) DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_navigation", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_navigation")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_navigation", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_navigation")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `title` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "title_color")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `title_color` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "cate_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `cate_id` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "author")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `author` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "content")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `content` longtext;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "linkurl")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `linkurl` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "images")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `images` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "describes")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `describes` varchar(500);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "identify")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `identify` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "isshow")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `isshow` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "view")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `view` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "virtual_view")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `virtual_view` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice")." ADD `addtime` int(10);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice_category", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice_category")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice_category", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice_category")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice_category", "name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice_category")." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice_category", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice_category")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice_category", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice_category")." ADD `status` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_notice_category", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_notice_category")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "ordersn")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `ordersn` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `title` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "total")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `total` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "price")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "integral")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `integral` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "remark")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `remark` varchar(500);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "receive_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `receive_name` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "receive_mobile")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `receive_mobile` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "address")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `address` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "shipping")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `shipping` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "express_info")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `express_info` text;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "express_price")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `express_price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "total_amount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "paytype")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `paytype` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "paytime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `paytime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "member1")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `member1` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "commission1")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `commission1` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "member2")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `member2` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "commission2")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `commission2` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "member3")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `member3` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "commission3")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `commission3` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "commented")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `commented` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "deleted")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `deleted` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "is_virtual")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `is_virtual` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "deliver_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `deliver_time` int(11);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "finish_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `finish_time` int(11);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "auto_finish_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `auto_finish_time` int(11);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "refund_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `refund_time` int(11);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "orderid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `orderid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "goods_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `goods_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `title` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "goods_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `goods_type` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "cover")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `cover` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "sell_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `sell_type` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "price")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "integral")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `integral` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "total")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `total` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "sku_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `sku_id` int(11);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "sku_name")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `sku_name` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "virtual_text")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `virtual_text` text;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "commission1")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `commission1` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "commission2")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `commission2` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "commission3")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `commission3` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "paytime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `paytime` int(11);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_order_goods", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_order_goods")." ADD `update_time` int(11);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund", "orderid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund")." ADD `orderid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund", "refund_amount")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund")." ADD `refund_amount` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund", "integral")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund")." ADD `integral` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund", "reason")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund")." ADD `reason` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund", "express_title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund")." ADD `express_title` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund", "express_code")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund")." ADD `express_code` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund", "express_no")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund")." ADD `express_no` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund")." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund", "update_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund")." ADD `update_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund_log", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund_log")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund_log", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund_log")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund_log", "refund_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund_log")." ADD `refund_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund_log", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund_log")." ADD `title` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund_log", "content")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund_log")." ADD `content` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund_log", "picture")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund_log")." ADD `picture` text;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund_log", "role")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund_log")." ADD `role` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund_log", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund_log")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund_reason", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund_reason")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund_reason", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund_reason")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund_reason", "title")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund_reason")." ADD `title` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_refund_reason", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_refund_reason")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "is_sale")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `is_sale` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "sitename")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `sitename` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "copyright")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `copyright` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "audit_comment")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `audit_comment` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "sysnc_right_menu")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `sysnc_right_menu` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "close_order")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `close_order` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "finish_order")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `finish_order` int(11) NOT NULL DEFAULT '7';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "send_commission")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `send_commission` int(11) NOT NULL DEFAULT '7';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "refund_order")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `refund_order` int(11) NOT NULL DEFAULT '7';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "comment_order")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `comment_order` int(11) NOT NULL DEFAULT '15';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "express_config")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `express_config` text;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "visit_limit")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `visit_limit` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "share_shop")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `share_shop` text;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "share_goods")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `share_goods` text;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "manageopenid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `manageopenid` text;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "refund_receive")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `refund_receive` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_setting", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_setting")." ADD `addtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_sku", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_sku")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_sku", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_sku")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_sku", "goods_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_sku")." ADD `goods_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_sku", "value_ids")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_sku")." ADD `value_ids` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_sku", "cover")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_sku")." ADD `cover` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_sku", "unit")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_sku")." ADD `unit` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_sku", "integral")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_sku")." ADD `integral` int(11) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_sku", "market_price")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_sku")." ADD `market_price` decimal(10,2) unsigned DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_sku", "price")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_sku")." ADD `price` decimal(10,2) unsigned DEFAULT '0.00';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_sku", "total")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_sku")." ADD `total` int(11) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_sku", "sales")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_sku")." ADD `sales` int(11) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_syslog", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_syslog")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_syslog", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_syslog")." ADD `uniacid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_syslog", "admin_uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_syslog")." ADD `admin_uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_syslog", "admin_username")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_syslog")." ADD `admin_username` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_syslog", "log_type")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_syslog")." ADD `log_type` tinyint(1);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_syslog", "function")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_syslog")." ADD `function` varchar(100);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_syslog", "content")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_syslog")." ADD `content` varchar(1000);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_syslog", "ip")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_syslog")." ADD `ip` varchar(50);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_syslog", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_syslog")." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_value", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_value")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_value", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_value")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_value", "attr_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_value")." ADD `attr_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_value", "value")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_value")." ADD `value` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_value", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_value")." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_virtual", "id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_virtual")." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_virtual", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_virtual")." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_virtual", "goods_id")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_virtual")." ADD `goods_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_virtual", "value")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_virtual")." ADD `value` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_virtual", "status")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_virtual")." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_virtual", "ordersn")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_virtual")." ADD `ordersn` varchar(255);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_virtual", "uid")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_virtual")." ADD `uid` int(11);");
}
if(!pdo_fieldexists("fy_lesson_plugin_shop_virtual", "buy_time")) {
 pdo_query("ALTER TABLE ".tablename("fy_lesson_plugin_shop_virtual")." ADD `buy_time` int(11);");
}
