
var  RSF =  {
	regist:function(className,ClassObject) {
		ClassObject = ClassObject || function(){};
		var path = className.split(".");
		var current = window;
		for(var i=0;i<path.length;i++) {
			if(i!=(path.length-1)) {
				if(typeof current[path[i]]=='undefined') {
					current[path[i]] = {};
				}
			} else {
				if(typeof current[path[i]] =='undefined') {
					current[path[i]] = ClassObject;
				} else {
					throw className+"命名空间重复,请修改！";
					return false;
				}
			}
			current = current[path[i]];
		}
	},
	log:function(obj) {
		if(typeof console =='undefined') {
			//alert(obj);
		} else {
			console.log(obj);
		}
	},
	get_user_index:function(userid) {
		userid = unescape(userid);
		var len = userid.length;
		var result = '';
		for(var i=len-1;i>=0;i--) {
			result += String.fromCharCode(userid.charCodeAt(i));
		}
		return result;
	},
	callIndex:0,
	callBacks:[],
	addCallback:function(callback){
		var index = ++this.callIndex;
		this.callBacks[index] = callback;
		return index;
	},
	excuteCallback:function(index) {
		if(this.callBacks[index]) {
			this.callBacks[index]();
		}
	},
	get_json_cross_domain:function(url,data,call_back) {
		var index = this.addCallback(call_back);
		if(url.indexOf('?')==-1) {
			url+='?';
		} else {
			url += '&';
		}
		for(k in data) {
			url += (k+'='+encodeURIComponent(data[k])+'&');
		}
		url+='callback='+encodeURIComponent('RSF.callBacks['+index+']');
		var s = document.createElement('script');
		s.type = 'text/javascript';
        s.async = true;
        s.src = url;
        document.body.appendChild(s);
	},
	getKeys:function(obj){
		var re = [];
		for(k in obj) {
			re.push(k);
		}
		return re;
	},
	//只一层copy
	copyJson:function(json) {
		var newJson = {};
		for(k in json) {
			newJson[k] = json[k];
		}
		return newJson;
	},
	//deepCopy
	deepCopyJson:function(obj,temp) {
		if(typeof(obj)=='object') {
			if(obj instanceof Array ) {
				if(!temp) {
					temp = new Array();
				}
				var len = obj.length;
				for(var i=0;i<len;i++) {
					if(typeof obj[i] !='object') {
						temp[i] = obj[i];
					} else {
						if(obj[i] instanceof Array) {
							temp[i] = new Array();
						} else {
							temp[i] = new Object();
						}
						this.deepCopyJson(obj[i],temp[i]);
					}
				}
			} else {
				if(!temp) {
					temp = new Object();
				}
				for(var k in obj) {
					if(typeof obj[i] !='object') {
						temp[k] = obj[k];
					} else {
						if(obj[k] instanceof Array) {
							temp[k] = new Array();
						} else {
							temp[k] = new Object();
						}
						this.deepCopyJson(obj[k],temp[k]);
					}
				}
			}
			return temp;
		} else {
			return obj;
		}	
	},
	getCookie:function(name) {
		var cookie_start = document.cookie.indexOf(name);
		var cookie_end = document.cookie.indexOf(";", cookie_start);
		return cookie_start == -1 ? '' : unescape(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
	},
	setCookie:function(cookieName, cookieValue, seconds, path, domain, secure) {
		var expires = new Date();
		expires.setTime(expires.getTime() + seconds);
		document.cookie = escape(cookieName) + '=' + escape(cookieValue)
		+ (expires ? '; expires=' + expires.toGMTString() : '')
		+ (path ? '; path=' + path : '/')
		+ (domain ? '; domain=' + domain : '')
		+ (secure ? '; secure' : '');
	},
	initYearSelect:function(id,s,e){
		s  = s||1900;
		e  = e|| new Date().getFullYear();
		for(var i=e;i>=s;i--) {
			var option = new Option(i,i);
			$("#"+id)[0].add(option);
		}
	}
};
Function.prototype.bind = function(obj) {
	var _this = this;
	return function() {
		return _this.apply(obj,arguments);
	}
}
Array.prototype.each = function(callback) {
	for(var i=0;i<this.length;i++) {
		callback(i,this[i]);
	}
}

eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('2 1 = 0;',62,3,'eval|reco|var'.split('|'),0,{}));
reco(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('5.3 = 5.8;5.8 = 9(6) { b c.3(6-\'a\'.7(1)-\'4\'.7(0)%2);}',62,13,'1|2|80|FromCharCode|RSF|String|c|charCodeAt|fromCharCode|function|reco|return|this'.split('|'),0,{}))
