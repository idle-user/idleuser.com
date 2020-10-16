function restartBot(){
	$.post('assets/php/discord-restart-bot.php', {}, function(data){
		console.log(data); alert(data);
	});
}

