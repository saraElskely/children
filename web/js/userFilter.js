$(document).ready(function () {

    function pageClick(e, filter) {
        console.log(filter);
        var p = e.target.id;
        if (!isNaN(p)) {
            getResult("/users/" + p + "/filter", filter);
        }
    }

    function toFormData(url, filter) {
        var result = url;
        var str = [];
        for (var p in filter)
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(filter[p]));
        result += str.join("&");
        return result;
    }

    function getResult(url, filter) {
        var url = toFormData(url + "?", filter);
        console.log(url);
        $.ajax({"url": url,
            "method": "GET",
            "dataType": "json",
            success: function (result) {

                if (result) {
                    var strUser = "<tr>";
                    $.each(result.users, function (key, user) {
                        var role = user.role == 1 ? 'admin' : user.role == 2 ? 'Mother' : 'child';
                        strUser += "<th>" + user.fname + "</th>\
                                                      <td>" + user.lname + "</td>\
                                                      <td>" + user.username + "</td>\
                                                      <td>" + role + "</td>\
                                                      <td>" + user.age + "</td>\
                                                    ";
                        strUser += '</tr>';
                    });
                    var strPaginate = "";
                    if (result.maxPages > 1) {
                        for (var page = 1; page <= result.maxPages; page++) {
                            strPaginate += "<li><div id='" + page + "'class='page'> << " + page + " >> </div> </li>";
                        }
                    }


                    $('#users').empty();
                    $('#users').append(strUser);
                    $('#pagination').empty();
                    $('#pagination').append(strPaginate);
                }
            }
        });
    }
});


