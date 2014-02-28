//<![CDATA[
RSF.regist("Web.Index",function() {
	this.init = function(){
		$("#songlist .playMusic").hover(function(){
			$(this).attr('src',"<?=UrlBuilder::build_static('images/btn/btn_play_hover.jpg')?>");
		}, function(){
			$(this).attr('src',"<?=UrlBuilder::build_static('images/btn/btn_play.jpg')?>");
		});
		
		$("#recommendList .playAlbum").click(function(){
			var albumId = $(this).attr('album_id');
			PLAYERAPI.openPlayer();
			PLAYERAPI.playAlbum(albumId);
		});
		$("#albumRankList .playBtn").click(function(){
			var albumId = $(this).attr('album_id');
			PLAYERAPI.openPlayer();
			PLAYERAPI.playAlbum(albumId);
		});
		
		$("#singerRankList .playBtn").click(function(){
			var userId = $(this).attr('user_id');
			PLAYERAPI.openPlayer();
			PLAYERAPI.playSinger(userId);
		});
		
		
		$("#albumRankList .playBtn").click(function(){
			var albumId = $(this).attr('album_id');
			PLAYERAPI.openPlayer();
			PLAYERAPI.playAlbum(albumId);
		});
		
		
		
		$(".albumRankList h3").hover(function(){
			$(this).find('.playBtn').show();
		}, function(){
			$(this).find('.playBtn').hide();
		});
		$(".singerRankList h3").hover(function(){
			$(this).find('.playBtn').show();
		}, function(){
			$(this).find('.playBtn').hide();
		});
		
		$('#recommendList li').hover(function(){
			$(this).find('.playAlbum').show();
		}, function(){
			$(this).find('.playAlbum').hide();
		});
        $("#bx-pager").css("visibility","visible");
        
		var slider = $(".bannerTopInner").bxSlider({
			mode: 'fade',
			controls: false,
			auto: true,
			speed: 500,
			captions: true,
			pagerCustom: "#bx-pager"
		});
		var timeout;
		$("#bx-pager a").hover(function(){
			clearTimeout('timeout');
			$(this).click();
		});
		
		var table = $(".albumRankTitle .slider a");
		table.click(function(){
			$(this).addClass("disable").siblings().removeClass("disable");
			var index = table.index(this);
			$("#albumRankList > ul").eq(index).fadeIn(1000).siblings().fadeOut();
		});
		
		var table2 = $(".singerRankTitle .slider a");
		table2.click(function(){
			$(this).addClass("disable").siblings().removeClass("disable");
			var index = table2.index(this);
			$("#singerRankList > ul").eq(index).fadeIn(1000).siblings().fadeOut();
		});
		
		$("#bx-pager span").width($("#bx-pager").width()/12.6);
		//播放榜单		
		$("#playRank").click(function(){
			var musicIds = [];
			$("#songlist .share").each(function(index,item){
				if($(item).attr('is_pub')==1) {
					musicIds.push($(item).attr('music_id'));
				}
			});
			PLAYERAPI.openPlayer();
			PLAYERAPI.addMusics(musicIds);
		});
		this.playdemo();
		
	}
	this.currentMusicPlayNumPlus = function(id) {
		$.getJSON('/ajax/getinfo/', {
			music_id : id,
			action : 'demo_play_num_plus',
			t : new Date().getTime()
		}, function(data) {

		});
	}
	this.playdemo = function (){
		var myPlayer = $("#jquery_jplayer_1");
		
		$("#jquery_jplayer_1").jPlayer({
			swfPath:"<?=UrlBuilder::build_static('swf')?>",
			supplied: "mp3"
		});
		var me = this;
		$("#demolist .playIcon a").click(function(){
			var id = $(this).attr('music_id');
			me.currentMusicPlayNumPlus(id);
			$(".jp-audio").hide();
			$("#demolist .playIcon").show();
			$(this).parent().hide().parent().find('.jp-audio').show();
			$.getJSON('/ajax/getinfo/?music_id='+id,{},function(data){
				 myPlayer.jPlayer("clearMedia").jPlayer("setMedia", {
					 mp3:RSF.get_user_index(data.htap),
				 }).jPlayer("play");   
			});
		});   
		
	}
});
