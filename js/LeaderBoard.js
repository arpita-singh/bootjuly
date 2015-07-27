// Builds and manages the Trivia Leader Board.

var $ = window.$;

function LeaderBoard() {
	this.rawData = null;
	this.scores = [];
	this.$el = null;
};

LeaderBoard.prototype.build = function(data) {
	console.log(data);
	this.rawData = data;

	this.scores = [];
	for (var i in data.sessions) {
		var score = {};
		var raw = data.sessions[i];

		score.rawData = raw;
		score.correct = parseInt(raw.count_score_correct);
		score.max = parseInt(raw.count_score);
		score.percent = ((score.correct / score.max) * 100).toFixed(2);
		score.username = raw.user_id;
		score.time = raw.time_completed;
		score.rank = null;
		score.$el = null;

		this.scores.push(score);
	}

	this.rank();
	this.buildHtml();
	
};

LeaderBoard.prototype.rank = function() {
	var d = 0.001;
	this.scores.sort(function (a,b) {
			if (Math.abs(a.percent - b.percent) > d) {
				return a.percent - b.percent;
			}
			if (a.username != b.username) {
				return a.username < b.username? -1 : 1;
			}
			return a.time < b.time? -1 : 1;
		});
	this.scores.reverse();

	var r = 1;
	for (var i in this.scores) {
		this.scores[i].rank = r++;
	}
};

LeaderBoard.prototype.buildHtml = function() {
	this.$el = null;

	var html = $('<tbody></tbody>');
	for (var i in this.scores) {
		var score = this.scores[i];
		score.$el = $(''
				+'<tr>'
				+'    <td class="text-left">'+score.rank+'</td>'
				+'    <td class="text-left">'+score.username+'</td>'
				+'    <td class="text-center">'+Math.round(score.percent)+'</td>'
				+'    <td class="text-right">'+score.correct+'/'+score.max+'</td>'
				+'</tr>'
			);

		html.append(score.$el);
	}
	this.$el = html;
};

LeaderBoard.prototype.attach = function(tbody) {
	$('#'+tbody).replaceWith(this.$el);
};

