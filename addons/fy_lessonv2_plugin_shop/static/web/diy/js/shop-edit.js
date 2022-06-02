require(['jquery', 'jquery.ui', 'underscore', 'ueditor', 'select2', 'fileUploader'], function($, jQueryUI, UnderScore, UEditor, Select2, FileUploader){
    window.Diy = {
        Designer: {
            init: function () {
                this.Phone = {
                    title: $('.phone_title'),
                    body: $('.phone_body'),
                    content: $('.phone_content'),
                };
                this.Editor = {
                    body: $('.editor'),
                    content: $('.editor_content'),
                };
                this.Toolbar = {
                    body: $('.toolbar'),
                    block: $('.toolbar ul li button'),
                };
                this.Block = {
                    body: $('.block', Diy.Designer.Phone.content),
                    edit: $('.block .edit', Diy.Designer.Phone.content),
                    delete: $('.block .delete', Diy.Designer.Phone.content),
                    active: $('.active', Diy.Designer.Phone.content),
                    hotArea: $('.block .hot-area', Diy.Designer.Phone.content),
                };
                this.Sortable = {
                    startHtml: '<div><i class="fa fa-info-circle"></i> 拖动到这里</div>',
                };
                this.Modal = {
                    delete: $('.modal-delete'),
                    selectItem: $('.modal-select-goods'),
                    itemContent: $('.modal-select-goods .item-content'),
                    searchButton: $('.modal-select-goods .search-button'),
                    selectUrl: $('.modal-select-url'),
                };
            }
        },
        Title: {
            init: function () {
                var name = 'page';
                var guid = Diy.Tools.guid('page');
                var title = $('input[name=title]').val();
                title = title!=''?title:'\u8bfe\u5802\u5546\u57ce';
                Diy.Editor.load(name, guid);
                Diy.Designer.Phone.title.attr('data-guid', guid)
                    .attr('data-name', name).click(function () {
					if(fylesson.development){
						fylesson.log('Title click');
					}
                    var current_name = $(this).attr('data-name');
                    var current_guid = $(this).attr('data-guid');

                    if (current_guid == Diy.Designer.Editor.content.attr('data-guid')) {
                        return;
                    }
                    Diy.Designer.Block.body.each(function () {
                        $(this).removeClass('active');
                        $('.block_btn', this).hide();
                    });
                    Diy.Editor.load(current_name, current_guid).refreshPosition();
                }).html(title);
            }
        },
        Block: {
            init: function (first = false) {
				if(fylesson.development){
					fylesson.log('Block init');
				}

				if (first && Object.getOwnPropertyNames(Diy.Editor.data).length) {
                    for (var key in Diy.Editor.data) {
                        var item = Diy.Editor.data[key];
                        var tpl = $('#tpl_block_'+item.name).html();
                        var html = _.template(tpl)(item);
                        if (item.name == 'richtext') {
                            var poster = $('.richtext_block p video', html).attr('poster');
                            html = html.replace(poster, item.style.imgurl);
                        }
                        Diy.Designer.Phone.content.append(html);
                    }
                    $('.block_btn', Diy.Designer.Phone.content).hide();
                    Diy.Designer.init();
                }
                Diy.Designer.Phone.content.sortable({
                    placeholder: 'sortable-placeholder',
                    opacity: 0.6,
                    scroll: false,
                    revert: 100, //ms
                    cancel: '.cancel-sortable',
                    start: function (e, ui) {
                        $('.sortable-placeholder').html(Diy.Designer.Sortable.startHtml).css({
                            'height': ui.item.height()+'px',
                            'line-height': ui.item.height()+'px'
                        });
                    },
                    update: function (e, ui) {
                        Diy.Block.sort();
                    }
                });
                Diy.Designer.Block.body.unbind('click').click(function (e) {
                    var block = $(this);
                    Diy.Block.switch(block);
                });
                Diy.Designer.Block.delete.unbind('click').click(function (e) {
                    e.stopPropagation();
                    var id = $(this).data('id');
                    Diy.Designer.Modal.delete
                        .attr('data-id', id)
                        .attr('data-name', '')
                        .attr('data-guid', '');
                    Diy.Tools.modal.delete(function (ele, id, name, guid) {
                        Diy.Block.delete($('#'+id));
                    });
                });
                Diy.Designer.Block.hotArea.each(function () {
                    var name = $(this).parent().parent().parent().parent().data('name');
                    var guid = $(this).parent().parent().parent().parent().data('guid');
                    var hot_area = $(this);
                    hot_area.unbind('mousedown').mousedown(function (e) {
                        var offsetX = e.pageX - $(this).position().left;
                        var offsetY = e.pageY - $(this).position().top;
                        var type = hot_area.data('type');
                        var target = hot_area.data('target');
                        $(document).unbind('mousemove mouseup').bind('mousemove', function(e) {
                            var x = e.pageX - offsetX;
                            var y = e.pageY - offsetY;
                            hot_area.css({
                                left: x,
                                top: y,
                            });
                            if (type == 'hot_area' && target) {
                                var x2 = x + hot_area.width();
                                var y2 = y + hot_area.height();
                                $(target).val(x+','+y+','+x2+','+y2);
                                Diy.Editor.UI.Common.refresh(name, guid);
                            }
                        }).mouseup(function () {
                            $(document).unbind('mousemove');
                        });
                    });
                    $('.hot-area-close', hot_area).unbind('click').click(function (e) {
                        var parent = $(this).parent();
                        var target = $(parent).data('target');
                        $(target).parent().parent().parent().remove();
                        $(parent).remove();
                        Diy.Editor.UI.Common.refresh(name, guid);
                        e.stopPropagation();
                    });
                    $('.hot-area-resize', hot_area).unbind('mousedown').mousedown(function (e) {
                        var offsetX = e.pageX - $(this).position().left;
                        var offsetY = e.pageY - $(this).position().top;
                        var width = $(this).width();
                        var height = $(this).height();
                        var target = $(this).data('target');
                        $(document).unbind('mousemove mouseup').bind('mousemove', function(e) {
                            var x = e.pageX - offsetX + width;
                            var y = e.pageY - offsetY + height;
                            x = x>30?x:30;
                            y = y>30?y:30;
                            hot_area.css({
                                width: x+'px',
                                height: y+'px',
                            });
                            if (target) {
                                $(target).val(x+','+y);
                                Diy.Editor.UI.Common.refresh(name, guid);
                            }
                        }).mouseup(function () {
                            $(document).unbind('mousemove');
                        });
                        e.stopPropagation();
                    });
                });
                return this;
            },

            sort: function () {
				if(fylesson.development){
					fylesson.log('Block sort');
				}
                
                Diy.Designer.init();
                var obj = Diy.Designer.Block.body;
                if (obj.length > 1) {
                    obj.each(function (index, ele) {
                        var guid = $(this).data('guid');
                        if (typeof Diy.Editor.data[guid] != 'undefined') {
                            Diy.Editor.data[guid].displayorder = index;
                        }
                    });
                }
            },
            add: function (name, guid) {
				if(fylesson.development){
					fylesson.log('Block add('+name+', '+guid+')');
				}
                

                var active = Diy.Designer.Block.active;
                if (active.length) {
                    var active_name = active.data('name');
                    var active_guid = active.data('guid');
                    Diy.Editor.setData(active_name, active_guid).save(active_name, active_guid);
                }

                Diy.Editor.load(name, guid).setData(name, guid);
                var tpl = $('#tpl_block_'+name).html();
                var html = _.template(tpl)(Diy.Editor.data[guid]);
                Diy.Designer.Phone.content.append(html);
                var block = Diy.Block.lastOne();

                Diy.Designer.Block.body.each(function () {
                    $(this).removeClass('active');
                    $('.block_btn', this).hide();
                });
                Diy.Designer.Block.active = block;
                Diy.Editor.refreshPosition();
                block.addClass('active');
                $('.block_btn', block).show();

                block.data('guid', guid);
                Diy.Block.init().sort();
                return this;
            },
            switch: function (block, loadEditor = true) {
				if(fylesson.development){
					fylesson.log('Block switch');
				}
                
                if (block && Diy.Designer.Block.active.is(block)) {
                    return;
                }

                Diy.Designer.Block.body.each(function () {
                    $(this).removeClass('active');
                    $('.block_btn', this).hide();
                });

                var active = Diy.Designer.Block.active;
                if (active.length) {
                    var active_name = active.data('name');
                    var active_guid = active.data('guid');
                    Diy.Editor.setData(active_name, active_guid).save(active_name, active_guid);
                }

                if (block && !block.hasClass('active')) {
                    Diy.Designer.Block.active = block;
                    Diy.Editor.refreshPosition();
                    block.addClass('active');
                    $('.block_btn', block).show();
                    if (loadEditor) {
                        Diy.Editor.load(block.data('name'), block.data('guid'));
                    }
                }

                Diy.Designer.init();
                return this;
            },
            load: function (name, guid) {
				if(fylesson.development){
					fylesson.log('Block load('+name+', '+guid+')');
				}
                
                var tpl = $('#tpl_block_'+name).html();
                var html = _.template(tpl)(Diy.Editor.data[guid]);
                if (name == 'richtext') {
                    var poster = $('.richtext_block p video', html).attr('poster');
                    html = html.replace(poster, Diy.Editor.data[guid].style.imgurl);
                }
                html = html.replace('class="block"', 'class="block active"');
                if (Diy.Designer.Block.active.length) {
                    Diy.Designer.Block.active.prop('outerHTML', html);
                }
                Diy.Designer.init();
                Diy.Block.init();
                return this;
            },
            delete: function (block) {
				if(fylesson.development){
					fylesson.log('Block delete');
				}
                
                var next = block.next();
                var prev = block.prev();
                var name = block.data('name');
                var guid = block.data('guid');
                if (typeof Diy.Editor.data[guid] != 'undefined') {
                    delete Diy.Editor.data[guid];
                }
                block.remove();
                Diy.Designer.init();
                if (next.is('div.block')) {
                    Diy.Block.switch(next);
                } else if (prev.is('div.block')) {
                    Diy.Block.switch(prev);
                } else {
                    Diy.Editor.load('page').refreshPosition();
                }
                Diy.Designer.init();
                return this;
            },
            lastOne: function () {
                Diy.Designer.init();
                return $('div.block:last-child', Diy.Designer.Phone.content);
            },
            isExist: function (name) {
                return $('[data-name='+name+']', Diy.Designer.Phone.content).length?true:false;
            },
        },
        Editor: {
            data: Diy.Editor.data,
            defaultData: Diy.Editor.defaultData,
            UI: {
                init: function () {
					if(fylesson.development){
						fylesson.log('Editor UI init');
					}
                    
                    for (var i in Diy.Editor.UI) {
                        if (this.hasOwnProperty(i)) {
                            if (typeof this[i] == 'object' && this[i].hasOwnProperty('init')) {
                                this[i].init();
                            }
                        }
                    }
                },
                Data: {
                    imgOptions: {
                        'global':false,
                        'class_extra':'',
                        'direct':true,
                        'multiple':false,
                        'fileSizeLimit':5120000,
                    },
                    UEImgOptions :{
                        type: 'image',
                        direct: false,
                        multiple: true,
                        tabs: {'upload': 'active', 'browser': '', 'crawler': ''},
                        path: '',
                        dest_dir: '',
                        global: false,
                        thumb: false,
                        width: 0
                    },
                    UEOptions :{
                        toolbars: [['fullscreen', 'source', 'preview', '|', 'bold', 'italic', 'underline', 'strikethrough', 'forecolor', 'backcolor', '|', 'justifyleft', 'justifycenter', 'justifyright', '|', 'insertorderedlist', 'insertunorderedlist', 'blockquote', 'emotion', 'link', 'removeformat', '|', 'rowspacingtop', 'rowspacingbottom', 'lineheight','indent', 'paragraph', 'fontsize', '|', 'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', '|', 'anchor', 'map', 'print', 'drafts']],
                        elementPathEnabled: false,
                        initialFrameHeight: 150,
                        maximumWords: 1000000,
                        autoClearinitialContent: false,
                    },
                },
                Common: {
                    add: function () {
                        var obj = $('.ep-item-add', Diy.Designer.Editor.content);
                        if (!obj.length) {
                            return;
                        }
						if(fylesson.development){
							fylesson.log('Editor UI add init');
						}
                        
                        obj.unbind('click').click(function () {
                            var name = $(this).data('name');
                            var guid = $(this).data('guid');
                            var itemMax = $(this).data('item-max');
                            var currentItem = $('.ep-item', $(this).parent().prev()).length;
                            if (currentItem >= itemMax) {
                                util.message('该元素数量最多为'+itemMax+'个', '', 'warning');
                                return;
                            }
                            if ($(this).data('type') == 'video') {
                                var tpl = $('#tpl_block_swiper_video').html();
                            } else {
                                var tpl = $('#tpl_block_'+name+'_item').html();
                            }
                            var data = Diy.Editor.defaultData[name];
                            var wrap = obj.parent().prev();
                            data.name = name;
                            data.guid = guid;
                            data.index = currentItem+1;
                            data.itemIndex = -1;
                            var itemIndex = -1;
                            var lastItem = $('.ep-item', wrap).last();
                            if (lastItem.length) {
                                var itemId = lastItem.attr('id');
                                if (itemId) {
                                    var arrId = itemId.split('-');
                                    itemIndex = arrId[arrId.length - 1];
                                    if (itemIndex >= 0) {
                                        data.itemIndex = parseInt(itemIndex) + 1;
                                    }
                                }
                            }
                            var html = _.template(tpl)(data);
                            wrap.append(html);

                            var blockTarget = $(this).data('blockTarget');
                            if (blockTarget) {
                                var tpl = $($(this).data('tpl')).html();
                                var html = _.template(tpl)({
                                    guid: guid,
                                    index: data.index,
                                });
                                $('#'+guid+' '+blockTarget).append(html);
                            }

                            Diy.Editor.UI.Common.Item.init();
                            Diy.Editor.UI.Common.delete();
                            Diy.Editor.UI.input.init();
                            Diy.Editor.UI.Common.selectImage();
                            Diy.Editor.UI.Common.selectUrl();
                            if (Diy.Editor.UI[name] && Diy.Editor.UI[name].hasOwnProperty('selectVideo')) {
                                Diy.Editor.UI[name].selectVideo();
                            }
                            Diy.Editor.UI.Common.refresh(name, guid);
                        });
                        return this;
                    },
                    delete: function () {
                        var obj = $('.ep-item-delete', Diy.Designer.Editor.content);
                        if (!obj.length) {
                            return;
                        }
						if(fylesson.development){
							fylesson.log('Editor UI delete init');
						}
                        
                        obj.each(function () {
                            $(this).unbind('click').click(function () {
                                var id = Diy.Tools.guid('ep-item-delete');
                                var name = $(this).data('name');
                                var guid = $(this).data('guid');
                                var itemMin = $(this).data('item-min');
                                var currentItem = $('.ep-item', $(this).parent().parent()).length;
                                if (currentItem <= itemMin) {
                                    util.message('该元素数量至少为'+itemMin+'个', '', 'warning');
                                    return;
                                }
                                $(this).parent().attr('id', id);
                                Diy.Designer.Modal.delete
                                    .attr('data-id', id)
                                    .attr('data-name', name)
                                    .attr('data-guid', guid);
                                Diy.Tools.modal.delete(function (ele, id, name, guid) {
                                    $('#'+id).remove();
                                    Diy.Editor.UI.Common.refresh(name, guid);
                                });
                            });
                        });
                        return this;
                    },
                    sortable: function () {
                        var obj = $('.ep-item-sortable', Diy.Designer.Editor.content);
                        if (!obj.length) {
                            return;
                        }
						if(fylesson.development){
							fylesson.log('Editor UI sortable init');
						}
                        
                        obj.sortable({
                            placeholder: 'sortable-placeholder',
                            opacity: 0.6,
                            scroll: false,
                            revert: 100, //ms
                            start: function (e, ui) {
                                $('.sortable-placeholder').html(Diy.Designer.Sortable.startHtml).css({
                                    'height': ui.item.height()+'px',
                                    'line-height': ui.item.height()+'px'
                                });
                            },
                            update: function (e, ui) {
                                var ipt = $('input', ui.item);
                                var name = ipt.data('name');
                                var guid = ipt.data('guid');
                                Diy.Editor.setData(name, guid);
                                Diy.Block.load(name, guid);
                            }
                        });
                        return this;
                    },
                    Item: {
                        init: function () {
                            var obj = $('.ep-select-goods', Diy.Designer.Editor.content);
                            if (!obj.length) {
                                return;
                            }
							if(fylesson.development){
								fylesson.log('Editor UI Item select init');
							}
                            
                            obj.each(function () {
                                $(this).unbind('click').click(function () {
                                    var itemId = $(this).parent().parent().parent().attr('id');
                                    Diy.Designer.Modal.selectItem.modal('show').data('ep-item-id', itemId);
                                    var goods_type = $('#goods_type').val();
									var goods_status = $('#goods_status').val();

                                    var searchFunc = function () {
                                        var keyword = $('input[name=keyword]', Diy.Designer.Modal.selectItem);
                                        var data = 'keyword=' + keyword.val() + '&goods_type=' + goods_type + '&goods_status=' + goods_status;
                                        Diy.Editor.UI.Common.Item.search({
                                            url: Diy.Designer.Modal.selectItem.data('url'),
                                            data: data,
                                        }, function (itemData) {
                                            Diy.Editor.UI.Common.Item.select(itemData);
                                        });
                                    };
                                    Diy.Designer.Modal.searchButton.unbind('click').click(function () {
                                        searchFunc();
                                    }).trigger('click');
                                    $('#goods_type').change(function(){
                                        goods_type = $('#goods_type').val();
                                        searchFunc();
                                    });
									$('#goods_status').change(function(){
                                        goods_status = $('#goods_status').val();
                                        searchFunc();
                                    });
                                    $('input[name=keyword]', Diy.Designer.Modal.selectItem).unbind('keyup').keyup(function (e) {
                                        if (e && e.keyCode == 13) {
                                            searchFunc();
                                        }
                                    });
                                });
                            });
                        },
                        search: function (data, cb) {
							if(fylesson.development){
								fylesson.log('Editor UI Item search init');
							}
                            
                            $.ajax({
                                url: data.url,
                                data: data.data,
                                dataType: 'json',
                                beforeSend: function () {
                                    Diy.Designer.Modal.itemContent.html('');
                                },
                                complete: function () {
                                },
                                success: function (resp) {
                                    if (resp.data.length <= 0) {
                                    }
                                    var tpl = '';
                                    tpl += '<% _.each(data, function(item) { %>'+
                                        '<div class="item" data-item-id="<%= item.id %>" data-item-title="<%= item.title %>" data-item-cover="<%= item.cover %>" style="background: #eee url(<%= item.cover %>); background-size: cover;">'+
                                        '   <div class="text"><%= item.title %></div>'+
                                        '</div>'+
                                        '<% }); %>';
                                    html = _.template(tpl)(resp);
                                    Diy.Designer.Modal.itemContent.html(html);

                                    $('.item', Diy.Designer.Modal.itemContent).unbind('click').click(function () {
                                        Diy.Designer.Modal.selectItem.modal('hide');
                                        if (typeof cb == 'function') {
                                            var itemData = {
                                                goodsid: $(this).data('item-id'),
                                                title: $(this).data('item-title'),
                                                cover: $(this).data('item-cover'),
                                            };
                                            cb(itemData);
                                        }
                                    });
                                    $('.modal-footer', Diy.Designer.Modal.selectItem).html(resp.pager);
                                },
                            });
                        },
                        select: function (data) {
							if(fylesson.development){
								fylesson.log('Editor UI Item click init');
							}
                            
                            var obj = $('#'+Diy.Designer.Modal.selectItem.data('ep-item-id'));
							$('[data-key=imgurl]', obj).attr('src', data.cover);
                            $('[data-key=goodsid]', obj).val(data.goodsid);
                            $('[data-key=title]', obj).val(data.title);
                            var name = $('[data-key=imgurl]', obj).data('name');
                            var guid = $('[data-key=imgurl]', obj).data('guid');
                            Diy.Editor.UI.Common.refresh(name, guid);
                        },
                        paging: function (url, page, ele) {
							if(fylesson.development){
								fylesson.log('Editor UI Item paging init');
							}
                            
                            var keyword = $('input[name=keyword]', Diy.Designer.Modal.selectItem);
                            var data = 'keyword=' + keyword.val();
							data += '&goods_type=' + $('#goods_type').val();
							data += '&goods_status=' + $('#goods_status').val();
                            data += '&page=' + page;
                            Diy.Editor.UI.Common.Item.search({
                                url: Diy.Designer.Modal.selectItem.data('url'),
                                data: data,
                            }, function (itemData) {
                                Diy.Editor.UI.Common.Item.select(itemData);
                            });
                        },
                    },


                    refresh: function (name, guid) {
                        Diy.Editor.setData(name, guid);
                        Diy.Block.load(name, guid);
                        return this;
                    },
                    selectImage: function () {
                        var obj = $('.ep-select-image', Diy.Designer.Editor.content);
                        if (obj.length) {
                            obj.each(function () {
                                $(this).unbind('click').click(function () {
                                    var ipt = $(this).prev();
                                    var img = $(this).parent().parent().prev().children();
                                    if (!$(img).is('img')) {
                                        img = $(this).parent().next().children();
                                    }
                                    var name = ipt.data('name');
                                    var guid = ipt.data('guid');
                                    util.image('', function (ret) {
                                        if (ret.url || ret.attachment) {
                                            if ($(ipt).is('input')) {
                                                ipt.val(ret.url);
                                            } else if ($(ipt).is('img')) {
                                                ipt.attr('src', ret.url);
                                            }
                                            if ($(img).is('img')) {
                                                img.attr('src', ret.url);
                                            }
                                            Diy.Editor.UI.Common.refresh(name, guid);
                                        }
                                    }, Diy.Editor.UI.Data.imgOptions);
                                });
                            });
                        }
                        return this;
                    },
                    selectUrl: function () {
                        var obj = $('.ep-select-url', Diy.Designer.Editor.content);
                        if (obj.length) {
                            obj.each(function () {
                                $(this).unbind('click').click(function () {
                                    var ipt = $(this).prev();
                                    var name = ipt.data('name');
                                    var guid = ipt.data('guid');
                                    Diy.Designer.Modal.selectUrl.modal('show');
                                    $('nav', Diy.Designer.Modal.selectUrl).unbind('click').click(function () {
                                        var url = $.trim($(this).data('url'));
                                        $(ipt).val(url);
                                        Diy.Editor.UI.Common.refresh(name, guid);
                                        Diy.Designer.Modal.selectUrl.modal('hide');
                                    })
                                })
                            })
                        }
                    },
                },
                input: {
                    init: function () {
                        var obj = $('.input_binding', Diy.Designer.Editor.content);
                        if (!obj.length) {
                            return;
                        }
						if(fylesson.development){
							fylesson.log('Editor UI input init');
						}
                        
                        obj.each(function () {
                            var ele = $(this);
                            var type = $(ele).attr('type');
                            if (type == 'text') {
                                ele.unbind('keyup').keyup(function () {
                                    var name = $(this).data('name');
                                    var guid = $(this).data('guid');
                                    var key = $(this).data('key');
                                    var value = $(this).val();
                                    $(this).attr('value', value);
                                    if (name == 'page') {
                                        $('input[name='+key+']').val(value);
                                        if (key == 'title') {
                                            Diy.Designer.Phone.title.html(value);
                                        }
                                    } else {
                                        Diy.Editor.setData(name, guid);
                                        Diy.Block.load(name, guid);
                                    }
                                });
                            } else if ($(this).is('textarea')) {
                                ele.unbind('change').change(function () {
                                    var name = $(this).data('name');
                                    var guid = $(this).data('guid');
                                    var value = $(this).val();
                                    Diy.Editor.setData(name, guid);
                                    Diy.Block.load(name, guid);
                                });
                            } else if ($.inArray(type, ['radio', 'checkbox']) != '-1') {
                                ele.unbind('click').click(function () {
                                    var name = $(this).data('name');
                                    var guid = $(this).data('guid');
                                    var toggle = $(this).data('toggle');
                                    if (typeof toggle != 'undefined') {
                                        var target = $(this).data('target');
                                        if (target != '') {
                                            if ($(this).data('show') == 1) {
                                                $(target).show();
                                            } else {
                                                $(target).hide();
                                            }
                                        }
                                    }
                                    $(this).attr('checked', 'checked');
                                    Diy.Editor.setData(name, guid);
                                    Diy.Block.load(name, guid);
                                });
                            } else if (type == 'color') {
                                ele.unbind('change').change(function () {
                                    var name = $(this).data('name');
                                    var guid = $(this).data('guid');
                                    $(this).attr('data-value', this.value);
                                    $(this).attr('value', this.value);
                                    Diy.Editor.setData(name, guid);
                                    Diy.Block.load(name, guid);
                                });
                            }
                        });
                    },
                },
                slider: {
                    init: function () {
                        var obj = $('.ui-slider', Diy.Designer.Editor.content);
                        if (!obj.length) {
                            return;
                        }
						if(fylesson.development){
							fylesson.log('Editor UI slider init');
						}
                        
                        obj.each(function () {
                            var t = this;
                            var max = parseInt($(t).data('max'));
                            var min = parseInt($(t).data('min'));
                            var value = parseInt($(t).data('value'));
                            $(t).slider({
                                classes: {
                                    'ui-slider': 'highlight'
                                },
                                min: min,
                                max: max,
                                value: value,
                                slide: function (event, ui) {
                                    var name = $(this).data('name');
                                    var guid = $(this).data('guid');
                                    var counter = $(this).parent().next().find('small');
                                    var value = ui.value+$(this).data('unit');
                                    counter.html(value);
                                    $(this).prev().val(value);
                                    Diy.Editor.setData(name, guid);
                                    Diy.Block.load(name, guid);
                                },
                                stop: function (event, ui) {
                                    $(this).attr('data-value', ui.value);
                                }
                            });
                        });
                    },
                },
                swiper: {
                    init: function () {
						if(fylesson.development){
							fylesson.log('Editor UI swiper init');
						}
                        
                        Diy.Editor.UI.Common.sortable();
                        Diy.Editor.UI.Common.delete();
                        Diy.Editor.UI.Common.add();
                        Diy.Editor.UI.Common.selectImage();
                    },
                },
				navicon: {
                    init: function () {
						if(fylesson.development){
							fylesson.log('Editor UI navicon init');
						}
                        
                        Diy.Editor.UI.Common.sortable();
                        Diy.Editor.UI.Common.delete();
                        Diy.Editor.UI.Common.add();
                        Diy.Editor.UI.Common.selectImage();
                    },
                },
                notice: {
                    init: function () {
						if(fylesson.development){
							fylesson.log('Editor UI notice init');
						}
                        
                        Diy.Editor.UI.Common.sortable();
                        Diy.Editor.UI.Common.delete();
                        Diy.Editor.UI.Common.add();
                        Diy.Editor.UI.Common.selectImage();
                    },
                },
				singleimg: {
                    init: function () {
						if(fylesson.development){
							fylesson.log('Editor UI singleimg init');
						}
                        Diy.Editor.UI.Common.selectImage();
                    },
                },
				cubeimg: {
                    init: function () {
						if(fylesson.development){
							fylesson.log('Editor UI cubeimg init');
						}
                        
                        Diy.Editor.UI.Common.sortable();
                        Diy.Editor.UI.Common.delete();
                        Diy.Editor.UI.Common.add();
                        Diy.Editor.UI.Common.selectImage();
                    },
                },
				slidegoods: {
                    init: function () {
						if(fylesson.development){
							fylesson.log('Editor UI slidegoods init');
						}
                        
                        Diy.Editor.UI.Common.delete();
                        Diy.Editor.UI.Common.add();
						Diy.Editor.UI.Common.Item.init();
                    },
                },
				staticgoods: {
                    init: function () {
						if(fylesson.development){
							fylesson.log('Editor UI staticgoods init');
						}
                        
                        Diy.Editor.UI.Common.delete();
                        Diy.Editor.UI.Common.add();
						Diy.Editor.UI.Common.Item.init();
                    },
                },
                richtext: {
                    init: function () {
                        var ueId = 'ep-ueditor';
                        var obj = $('#'+ueId, Diy.Designer.Editor.content);
                        if (!obj.length) {
                            return false;
                        }
						if(fylesson.development){
							fylesson.log('Editor UI richtext init');
						}
                        
                        Diy.Editor.UI.Common.selectImage();
                        var textarea = obj.next();
                        var name = textarea.data('name');
                        var guid = textarea.data('guid');
                        if (typeof(UE) != 'undefined') {
                            UE.delEditor(ueId);
                        }
                        var lastNum = $(".richtext_block").length;
                        var richtext_switch = true;

                        if (Diy.Designer.Block.active.context !== document) {
                            var content = Diy.Designer.Block.active.context.children[0].innerHTML;
                            var richtext_content = true;
                        }
                        var ue = UE.getEditor(ueId, Diy.Editor.UI.Data.UEOptions);
                        ue.ready(function () {
                            if (richtext_switch == true) {
                                var noBlank1 = Diy.Editor.defaultData['richtext']['style']['content'];
                                var noBlank2 = Diy.Designer.Block.active[0].children[0].innerHTML.replace(/&nbsp;/ig, ' ');
                                if (noBlank1.replace(/\s/g, '') == noBlank2.replace(/\s/g, '')) {
                                    if ($(".richtext_block").length == lastNum) {
                                        var content = Diy.Designer.Block.active[0].children[0].innerHTML;
                                    } else {
                                        var content = '<p>这里是富文本编辑器，您可以点击此处，然后在右侧进行编辑内容。</p><p>您可以对文字进行处理，如<strong style="white-space: normal;">加粗</strong>、<em style="white-space: normal;">斜体</em>、<span style="text-decoration-line: underline;">下划线</span>、<span style="text-decoration-line: line-through;">删除线</span>、文字<span style="color: rgb(255, 0, 0);">颜色</span>、<span style="color: rgb(255, 255, 255); background-color: rgb(0, 176, 80);">背景色</span><span style="color: rgb(0, 0, 0);">、字体类型等进行设置。</span></p>';
                                    }
                                    if (richtext_content == true) {
                                        var content = Diy.Designer.Block.active[0].children[0].innerHTML;
                                    }
                                    ue.setContent(content);
                                    textarea.html(ue.getContent()).trigger('change');
                                } else if (content == undefined) {
                                    var content = Diy.Designer.Block.active[0].children[0].innerHTML;
                                }
                                ue.setContent(content);
                                ue.addListener('contentChange', function () {
                                    textarea.html(ue.getContent()).trigger('change');
                                    Diy.Editor.setData(name, guid);
                                    Diy.Block.load(name, guid);
                                });
                                richtext_switch = false;
                            }
                        });

                        UE.registerUI('ep-ueditor-insertimage', function (editor, uiName) {
                            editor.registerCommand(uiName, {
                                execCommand: function () {
                                    FileUploader.show(function(imgs) {
                                        if (imgs.length == 0) {
                                            return
                                        } else if (imgs.length == 1) {
                                            editor.execCommand('insertimage', {
                                                'src': imgs[0]['url'],
                                                '_src': imgs[0]['url'],
                                                'width': '100%',
                                                'alt': imgs[0].filename
                                            })
                                        } else {
                                            var imglist = [];
                                            for (var i in imgs) {
                                                imglist.push({
                                                    'src': imgs[i]['url'],
                                                    '_src': imgs[i]['url'],
                                                    'width': '100%',
                                                    'alt': imgs[i].filename
                                                })
                                            }
                                            editor.execCommand('insertimage', imglist)
                                        }
                                    }, Diy.Editor.UI.Data.UEImgOptions);
                                }
                            });
                            var btn = new UE.ui.Button({
                                name: '插入图片',
                                title: '插入图片',
                                cssRules: 'background-position: -726px -77px',
                                onclick: function () {
                                    editor.execCommand(uiName)
                                }
                            });
                            editor.addListener('selectionchange', function () {
                                var state = editor.queryCommandState(uiName);
                                if (state == -1) {
                                    btn.setDisabled(true);
                                    btn.setChecked(false)
                                } else {
                                    btn.setDisabled(false);
                                    btn.setChecked(state)
                                }
                            });
                            return btn
                        }, 48);
                        UE.registerUI('ep-ueditor-insertvideo', function (editor, uiName) {
                            editor.registerCommand(uiName, {
                                execCommand: function () {
                                    FileUploader.show(function(video) {
                                        if (!video) {
                                            return
                                        }
                                        var videoType = video.isRemote ? 'iframe' : 'video';
                                        editor.execCommand('insertvideo', {
                                            'url': video.url,
                                            'width': '100%',
                                            'height': '100%'
                                        }, videoType)
                                    }, {
                                        fileSizeLimit: 5120000,
                                        type: 'video',
                                        allowUploadVideo: true
                                    });
                                }
                            });
                            var btn = new UE.ui.Button({
                                name: '插入视频',
                                title: '插入视频',
                                cssRules: 'background-position: -320px -20px',
                                onclick: function () {
                                    editor.execCommand(uiName)
                                }
                            });
                            editor.addListener('selectionchange', function () {
                                var state = editor.queryCommandState(uiName);
                                if (state == -1) {
                                    btn.setDisabled(true);
                                    btn.setChecked(false)
                                } else {
                                    btn.setDisabled(false);
                                    btn.setChecked(state)
                                }
                            });
                            return btn
                        }, 20);
                        UE.registerUI('ep-ueditor-insertlink', function (editor, uiName) {
                            var btn = new UE.ui.Button({
                                name: '插入链接',
                                title: '插入链接',
                                cssRules: 'background-position: -500px 0px;',
                                onclick: function () {
                                    Diy.Designer.Modal.selectUrl.modal('show');
                                    $('nav', Diy.Designer.Modal.selectUrl).unbind('click').click(function () {
                                        var url = $.trim($(this).data('url'));
                                        editor.execCommand('link', {href: url, 'data-nocache': 'true'});
                                        Diy.Designer.Modal.selectUrl.modal('hide');
                                    });
                                }
                            });
                            editor.addListener('selectionchange', function () {
                                var state = editor.queryCommandState(uiName);
                                if (state == -1) {
                                    btn.setDisabled(true);
                                    btn.setChecked(false)
                                } else {
                                    btn.setDisabled(false);
                                    btn.setChecked(state)
                                }
                            });
                            return btn
                        });
                    },
                },
				footnav: {
                    init: function () {
						if(fylesson.development){
							fylesson.log('Editor UI footnav init');
						}
                        Diy.Editor.UI.Common.sortable();
                        Diy.Editor.UI.Common.delete();
                        Diy.Editor.UI.Common.add();
                        Diy.Editor.UI.Common.selectImage();
                    },
                },
            },
            load: function(name, guid) {
				if(fylesson.development){
					fylesson.log('Editor load('+name+', '+guid+')');
				}
                
                var obj = $('#editor-'+guid);
                var html = '';
                if (!obj.length) {

                    $('.editor_params').each(function () {
                        if (name == $(this).data('name')) {
                            obj = $(this);
                            return false;
                        }
                    });
                    if (!obj) {
                        throw 'Editor load failed, name='+name;
                    }
                    var tpl = obj.html();
                    var data = {};
                    if (name == 'page') {
                        data = {
                            name: name,
                            guid: guid,
                            page_title: $('input[name=page_title]').val(),
                            title: $('input[name=title]').val(),
                            keyword: $('input[name=keyword]').val(),
                            description: $('input[name=description]').val()
                        };
                    } else {
                        if (typeof Diy.Editor.data[guid] == 'undefined') {
                            data = {
                                name: name,
                                guid: guid,
                                style: Diy.Editor.defaultData[name]['style'],
                                data: Diy.Editor.defaultData[name]['data'],
                            };
                            Diy.Editor.data[guid] = data;
                        } else {
                            data = Diy.Editor.data[guid];
                        }
                    }
                    html = _.template(tpl)(data);
                } else {
                    html = obj.html();
                }
                Diy.Designer.Editor.content.attr('data-guid', guid).html(html);
                Diy.Editor.UI.init();
                Diy.Designer.init();
                return this;
            },
            setData: function (name, guid) {
				if(fylesson.development){
					fylesson.log('Editor setData('+name+', '+guid+')');
				}
                
                if (typeof Diy.Editor.data[guid] == 'undefined') {
                    Diy.Editor.data[guid] = {
                        name: name,
                        guid: guid,
                        displayorder: 0,
                        style: {},
                    };
                }
                Diy.Editor.data[guid]['data'] = [];
                Diy.Editor.data[guid]['style'] = {};
                $('[data-type=style]', Diy.Designer.Editor.content).each(function () {
                    var type = $(this).attr('type');
                    var key = $(this).data('key');
                    var value = $(this).val();
                    if ($(this).is('img')) {
                        value = $(this).attr('src');
                    }

                    if ($.inArray(type, ['radio', 'checkbox']) != -1) {
                        var checked_key = $(this).data('checked-key');
                        if ($(this).prop('checked')) {
                            Diy.Editor.data[guid]['style'][checked_key] = 'checked';
                        } else {
                            Diy.Editor.data[guid]['style'][checked_key] = '';
                        }
                        if (!$(this).prop('checked')) {
                            return;
                        }
                    }
                    if ($(this).is('input')) {
                        if ($.inArray(type, ['radio', 'checkbox']) != -1
                            && !$(this).prop('checked')) {
                            return;
                        }
                    } else {
                        var unit = $(this).data('unit');
                        if (unit != undefined) {
                            value += unit;
                        }
                    }
                    if (typeof key != 'undefined') {
                        Diy.Editor.data[guid]['style'][key] = value;

                    }
                });
                $('.ep-item', Diy.Designer.Editor.content).each(function () {
                    var data = {};
                    $('[data-type=data]', this).each(function () {
                        var key = $(this).data('key');
                        if (typeof key != 'undefined') {
                            var value = $(this).val();
                            if ($(this).is('img')) {
                                value = $(this).attr('src');
                            }
                            data[key] = value;

                        }
                    });
                    Diy.Editor.data[guid]['data'].push(data);
                });
                return this;
            },
            save: function (name, guid) {
				if(fylesson.development){
					fylesson.log('Editor save('+name+', '+guid+')');
				}
                
                var html = Diy.Designer.Editor.content.html();
                var obj = $('#editor-'+guid);
                if (!obj.length) {
                    var script = document.createElement('script');
                    $(script).attr('type', 'text/template');
                    $(script).attr('id', '#editor-'+guid);
                    $(script).attr('data-name', name);
                    $(script).html(html);
                    $(document.body).append(script);
                } else {
                    obj.html(html);
                }
            },
            refreshPosition: function() {
                var offsetTop, obj;
                if (Diy.Designer.Block.active.length) {
                    obj = Diy.Designer.Block.active;
                } else {
                    obj = Diy.Designer.Phone.title;
                }
                offsetTop = obj.offset().top - 160;
                Diy.Designer.Editor.body.animate({
                    marginTop: offsetTop
                }, 100);

                $("html,body").animate({scrollTop:offsetTop},1000);
                return this;
            }
        },
        Toolbar: {
            init: function () {
				if(fylesson.development){
					fylesson.log('Toolbar init');
				}
                
                Diy.Designer.Toolbar.block.unbind('click').click(function (e) {
                    var name = $(this).data('name');
                    var guid = Diy.Tools.guid('block');
                    var repeat = $(this).data('repeat');
                    if (repeat == 'no') {
                        if (Diy.Block.isExist(name)) {
                            util.message('不可以重复添加该组件', '', 'warning');
                            return;
                        }
                    }
                    Diy.Block.add(name, guid);
                    $.initContentHeight();
                });
            },
            refreshPosition: function() {
                var windowHeight = $(window).height();
                var scrollTop = $(window).scrollTop();
                var scrollHeight = $(document).height();
                var offset = scrollHeight - scrollTop;
                if (offset == windowHeight) {
                    Diy.Designer.Toolbar.body.animate({
                        bottom: 118
                    }, 10).css({
                        boxShadow: '0px 0px 0px'
                    });
                } else {
                    Diy.Designer.Toolbar.body.animate({
                        bottom: 10
                    }, 10).css({
                        boxShadow: '0px 0px 20px #999'
                    });
                }
            },
        },
        Tools: {
            offsetTop: function (ele, offset = 0) {
                return ele.offset().top + offset;
            },
            S4: function () {
                return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
            },
            guid: function (prefix) {
                var t = this;
                return (prefix!=''?prefix+'-':'')+(t.S4()+t.S4()+t.S4());
            },
            modal: {
                delete: function (callback) {
					if(fylesson.development){
						fylesson.log('Tools modal delete');
					}
                    
                    Diy.Designer.Modal.delete.modal('show');
                    $('.remove-btn', Diy.Designer.Modal.delete).unbind('click').click(function () {
                        var id = Diy.Designer.Modal.delete.attr('data-id');
                        var name = Diy.Designer.Modal.delete.attr('data-name');
                        var guid = Diy.Designer.Modal.delete.attr('data-guid');
                        if (typeof callback == 'function') {
                            callback(this, id, name, guid);
                        }
                        Diy.Designer.Modal.delete.modal('hide')
                            .attr('data-id', '')
                            .attr('data-name', '')
                            .attr('data-guid', '');
                    });
                },
            },
        },
        Event: {
            init: function () {
				if(fylesson.development){
					fylesson.log('Event init');
				}
                
                Diy.Title.init();
                Diy.Block.init(true);
                Diy.Toolbar.init();
                $(window).scroll(function(){
                    Diy.Toolbar.refreshPosition();
                }).keydown(function (e) {
                    if (e.keyCode == 116
                        || (e.altKey && e.keyCode == 115)
                        || (e.ctrlKey && e.keyCode == 116)
                        || (e.ctrlKey && e.keyCode == 78)) {
						if (confirm('刷新不会保存您的数据，确认刷新?')) {
                            return true;
                        } else {
                            e.returnValue = false;
                            return false;
                        }
                    }

                    if (e.altKey && (e.keyCode == 37 || e.keyCode == 39)) {
                        console.log('Alt+←/Alt+→ disabled, keyCode='+e.keyCode);
                        e.returnValue = false;
                    }

                    if (e.keyCode == 8) {
                        console.log('Backspace disabled, keyCode='+e.keyCode);
                        e.returnValue = false;
                    }

                    if (e.keyCode == 112) {
                        console.log('F1 disabled, keyCode='+e.keyCode);
                        e.returnValue = false;
                    }
                });
            }
        },
        init: function () {
			if(fylesson.development){
				fylesson.log('Diy init start', 'info');
			}
            
            Diy.Designer.init();
            Diy.Event.init();
			if(fylesson.development){
				fylesson.log('Diy init end', 'info');
			}
        },
    };
    Diy.init();
    $('.btnSave').click(function () {
        var editorData = JSON.stringify(Diy.Editor.data);
        if (editorData == '{}') {
            util.message('您还没有添加任何组件', '', 'error');
            return;
        }
        var t = this;
        var url = window.location.href;
        var token = $('input[name=token]').val();
        var type = $('input[name=type]').val();
        var page_title = $('input[name=page_title]').val();
		var page_type = $('input[name=page_type]').val();
		var cover = $('input[name=cover]').val();
        var data = 'submit=yes&token='+token+'&type='+type;
        if (page_title == '') {
            Diy.Designer.Phone.title.trigger('click');
            util.message('请输入页面名称', '', 'error');
            return;
        }
        if ($(t).hasClass('disabled')) {
            return false;
        }
        var original_text = $(t).data('original-text');
        $(t).addClass('disabled').text('保存中...');
        data += '&page_title=' + page_title + '&page_type=' + page_type + '&cover=' + cover;
        data += '&data='+encodeURIComponent(editorData);
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: data,
            complete: function () {
                $(t).removeClass('disabled').text(original_text);
            },
            success: function (res) {
                if (res.code == 0) {
                    var url = res.backurl;
                    if (typeof res.id != 'undefined') {
                        util.message('保存成功', url, 'success');
                        return;
                    } else {
                        util.message('保存成功', url, 'success');
                    }
                } else {
                    util.message(res.errmsg + '(' + res.code + ')', '', 'error');
                }
            }
        });
        return false;
    });
});