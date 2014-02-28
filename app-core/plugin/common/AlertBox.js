var alerts = [];
function AlertBox(title,content,yesCallBack,noCallBack,extendCallBack) {
	this.id = '#myDialog';
	this.okBtn = $(this.id).find(".ok");
	alerts.push(this);
	
	this.noCallBack = noCallBack || function(){};
	this.extendCallBack = extendCallBack || function(){};
	$(this.id).find('.close').show();
	this.enableAlert = function() { //设置成警告窗口
		$(this.id).find('.close').hide();
	};
	this.setId = function(id) {
		this.id = '#'+id;
	};
	this.setTitle = function(title) {
		$(this.id).find('.title').html(title);
	};
	this.setContent = function(content) {
		$(this.id).find('.content').html(content);
	};
	this.backCloseEnable = function(available) {
		this.backClose = available;
	};
	this.show = function(callback) {
		if(this.isShow) {
			return false;
		}
		this.isShow = true;
		callback = callback || function(){};
		var defaults = {
				top : 100,
				overlay : 0.3,
				closeButton : null
		};
		var overlay = $("<div id='lean_overlay'></div>"); //遮罩层
		$("body").append(overlay);
		options = $.extend(defaults, {});
		var o = options;
		var modal_id = this.id;
		var _this = this;
		
		$("#lean_overlay").unbind('click');
		$("#lean_overlay").click(function() { //点击遮罩层，关掉那啥
			//_this.close_modal();
			if(this.backClose ) {
				this.close_modal();
			}
		}.bind(this));
		
		$(this.id).find(".close").unbind('click');
		$(this.id).find(".close").click(function(){
			_this.close_modal();
		});
		
		$(this.id).find(".ok").unbind('click');
		
		
		
		
		$(this.id).find(".ok").click(function(e) {
			_this.yesCallBack(e);
		});
		
		//标题
		this.setTitle(title);
		this.setContent(content);
		
		var modal_height = $(this.id).outerHeight();
		var modal_width = $(this.id).outerWidth();
		$("#lean_overlay").css({ //遮罩层
			"display" : "block",
			opacity : 0,
			"z-index":2000
		});
		if(Commonjs.Util.isIE8()) {
			$("#lean_overlay").css("opacity", o.overlay); //遮罩层
		} else {
			$("#lean_overlay").fadeTo(200, o.overlay); //遮罩层
		}
		//RSF.log($(window).width());
		$(this.id).css({
			'display':"block"
		});
		$(this.id).css({
			"position" : "fixed",
			"opacity" : (Commonjs.Util.isIE8()?1:0),
			"z-index" : 3000,
			"left" : ($(window).width()-$(this.id)[0].offsetWidth)/2+'px',
			"top" : ($(window).height()-$(this.id)[0].offsetHeight)/2+'px'
		});
		if(!Commonjs.Util.isIE8()) {
			$(this.id).fadeTo(200, 1,callback);
		}
		this.extendCallBack();
	};
	
	this.close_modal = function(notCloseBack) {
		this.isShow = false;
		if(!notCloseBack) { 
			if(!Commonjs.Util.isIE8()) {
				$("#lean_overlay").fadeOut(200);
			} else {
				$("#lean_overlay").hide();
			}
		}
		$(this.id).css({
			"display" : "none"
		});
		this.noCallBack();
		alerts.pop();
	};
	this.close = function(notCloseBack) {
		if(!notCloseBack) { 
			$("#lean_overlay").fadeOut(200);
		}
		$(this.id).css({
			"display" : "none"
		});
		this.noCallBack();
		alerts.pop();
		this.isShow = false;
	};
	this.yesCallBack = yesCallBack || (function(){this.close_modal();}.bind(this));
}

