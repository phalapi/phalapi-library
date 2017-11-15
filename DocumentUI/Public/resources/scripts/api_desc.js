function getData() {
	var data = new FormData();
	$("td input").each(function(index, e) {
		if ($.trim(e.value)) {
			if (e.type != 'file') {
				data.append(e.name, e.value);
			} else {
				let files = e.files;
				if (files.length == 1) {
					data.append(e.name, files[0]);
				} else {
					for (let i = 0; i < files.length; i++) {
						data.append(e.name + '[]', files[i]);
					}
				}
			}
		}
	});
	return data;
}

$(function() {
	$("#json_output").hide();
})
$("#submit").on("click",function() {
	$.ajax({
		url : $("input[name=request_url]").val(),
		type : $("select").val(),
		data : getData(),
		cache : false,
		processData : false,
		contentType : false,
		success : function(res, status, xhr) {
			console.log(xhr);
			var statu = xhr.status + ' ' + xhr.statusText;
			var header = xhr.getAllResponseHeaders();
			var json_text = JSON.stringify(res, null, 4); // 缩进4个空格
			$("#json_output").html(
					'<pre>' + statu + '<br/>' + header + '<br/>'
							+ json_text + '</pre>');
			$("#json_output").show();
		},
		error : function(error) {
			console.log(error)
		}
	})
})