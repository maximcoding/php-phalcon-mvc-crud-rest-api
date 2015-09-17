rootURL = 'http://localhost/exam/user';

function getUsers() {
    $.ajax({
	async: false,
	type: "GET",
	url: rootURL,
	contentType: "application/json; charset=utf-8",
	dataType: "json",
	data: JSON.stringify(),
	success: function (response) {
	    var html = '';
	    $.each(response, function (i, item) {
		html += '<tr><td>' + item.id +
			'</td><td>' + item.username +
			'</td><td>' + item.email +
			'</td><td><a\n\
                     class="btn" onclick="getUser('+ item.id +')">' + item.username + '</a></td></tr>';
	    });
	    $('.table-striped').append(html);
	},
	error: onError
    });
}


function getUser(userId) {
    $('#user-details').empty();
    $.ajax({
	async: false,
	type: "GET",
	url: rootURL + '/' + userId,
	contentType: "application/json; charset=utf-8",
	dataType: "json",
	data: getRawJson(),
	success: onSuccess,
	error: onError

    });
    return false;
}




function addUser() {
    $.ajax({
	async: false,
	type: "POST",
	url: rootURL,
	data: getRawJson(),
	contentType: "application/json; charset=utf-8",
	dataType: "json",
	success: onSuccess,
	error: onError
    });
}


function updateUser() {
    userId = $("input[name=id]").val();
    $.ajax({
	async: false,
	type: "PUT",
	url: rootURL + '/' + userId,
	contentType: "application/json; charset=utf-8",
	data: getRawJson(),
	dataType: "json",
	success: onSuccess,
	error: onError
    });
}

function deleteUser(userId) {
    $.ajax({
	async: false,
	type: "DELETE",
	url: rootURL + '/' + userId,
	contentType: "application/json; charset=utf-8",
	dataType: "json",
	data: getRawJson(),
	success: onSuccess,
	error: onError
    });
}


function getRawJson() {
    return JSON.stringify($('form').serializeArray().reduce(function (a, x) {
	a[x.name] = x.value;
	return a;
    }, {}));
}
function onSuccess(response, status) {
    $('#user-details').append(JSON.stringify(response));
    //  console.log(JSON.stringify(response));
}
function onError(response, status) {
    console.log('Error from ajax response ->' + status + ' data -> ' + data);
}


