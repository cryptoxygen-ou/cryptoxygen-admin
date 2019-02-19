$(document).ready(function () {
    $(".search_list").hide();
    $(".friends_show").hide();
    $(".fof_show").hide();
    $(".o_show").hide();
    //$("#sort_by").click
    $('#sort_by').change(function () {
        var sort = $("#sort_by option:selected").val();
        $("#search_text").val('');
        $(".search_list").hide();
        $(".friends_show").hide();
        $(".fof_show").hide();
        $(".o_show").hide();

        var urlParams = new URLSearchParams(window.location.search);

        console.log(urlParams.get('search_text'));

        if (history.pushState) {
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            console.log(newurl);
            window.history.pushState({path: newurl}, '', newurl);
        }


        if (sort == 'all') {
            $(".friends_show").show();
            $(".fof_show").show();
            $(".o_show").show();
            load_resources(type, sort);
            fof_load_resources(type = '', sort = '');
            other_load_resources(type = '', sort = '');
        }

        if (sort == 'friends') {
            $(".friends_show").show();
            load_resources(type, sort);
        }
        if (sort == 'fof') {
            $(".fof_show").show();
            fof_load_resources(type = '', sort = '');
        }

        load_resources(type, sort);
    });


    var pre_text = $("#search_text_pre").val();
    if (pre_text == '') {
        $(".friends_show").show();
        $(".fof_show").show();
        $(".o_show").show();
        load_resources(type = '', sort = '');
    }
    function load_resources(type, sort) {
        $.ajax({
            type: "POST",
            data: {'type': type},
            url: "dashboard/get_friends_count",
            success: function (result) {
                if (result != 0 || result != '') {
                    var item = [];
                    for (var i = 1; i <= result; i++) {
                        item.push(i);
                    }
                    console.log(item);
                    $('.friends_list').pagination({
                        dataSource: item,
                        totalNumber: result,
                        pageSize: 9,
                        pageRange: 1,
                        showPageNumbers: true,
                        showLastOnEllipsisShow: true,
                        showNavigator: true,
                        callback: function (data, pagination) {
                            var html = template(data, pagination, type, sort);
                        }
                    });
                } else {
                    // $("#ajax_table").html("<h2>No result available.</h2>");
                    if ($('#sort_by').val() == 'friends' || $('#sort_by').val() == 'all') {
                        $(".friends_show").show();
                        $("#ajax_table").html("<h2>No result available.</h2>");
                    } else {
                        $(".friends_show").hide();
                    }

                }
            }
        });
    }

    function template(data, pagination, type, sort) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: {'type': type, 'sort': sort, 'range': pagination.pageNumber, 'size': pagination.pageSize},
            url: "dashboard/get_friends_list",
            success: function (result) {
                $("#ajax_table").html(result);
            }
        });
    }

    /*
     * For friends of friends
     */
    if (pre_text == '') {
        fof_load_resources(type = '', sort = '');
    }
    function fof_load_resources(type, sort) {
        $.ajax({
            type: "POST",
            data: {'type': type},
            url: "dashboard/get_fof_count",
            success: function (result) {
                if (result != 0 || result != '') {
                    var item = [];
                    for (var i = 1; i <= result; i++) {
                        item.push(i);
                    }

                    $('.fof_list').pagination({
                        dataSource: item,
                        totalNumber: result,
                        pageSize: 9,
                        pageRange: 1,
                        showPageNumbers: true,
                        showLastOnEllipsisShow: true,
                        showNavigator: true,
                        callback: function (data, pagination) {
                            var html = templatefof(data, pagination, type, sort);
                        }
                    });
                } else {
                    if ($('#sort_by').val() == 'fof' || $('#sort_by').val() == 'all') {
                        $(".fof_show").show();
                        $("#ajax_fof").html("<h2>No result available.</h2>");
                    } else {
                        $(".fof_show").hide();
                    }
                }
            }
        });
    }

    function templatefof(data, pagination, type, sort) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: {'type': type, 'sort': sort, 'range': pagination.pageNumber, 'size': pagination.pageSize},
            url: "dashboard/get_fof_list",
            success: function (result) {
                $("#ajax_fof").html(result);
            }
        });
    }
    /*
     * For other
     */
    if (pre_text == '') {
        other_load_resources(type = '', sort = '');
    }
    function other_load_resources(type, sort) {
        $.ajax({
            type: "POST",
            data: {'type': type},
            url: "dashboard/get_other_count",
            success: function (result) {
                if (result != 0) {
                    var item = [];
                    for (var i = 1; i <= result; i++) {
                        item.push(i);
                    }

                    $('.other_list').pagination({
                        dataSource: item,
                        totalNumber: result,
                        pageSize: 9,
                        pageRange: 1,
                        showPageNumbers: true,
                        showLastOnEllipsisShow: true,
                        showNavigator: true,
                        callback: function (data, pagination) {
                            var html = templateother(data, pagination, type, sort);
                        }
                    });
                } else {
//                    $(".other_show").hide();
//                    $("#ajax_other").html("<h2>No result available.</h2>");

                    if ($('#sort_by').val() == 'all' || $('#sort_by').val() == '') {
                        $(".other_show").show();
                        $("#ajax_other").html("<h2>No result available.</h2>");
                    } else {
                        $(".other_show").hide();
                    }
                }
            }
        });
    }

    function templateother(data, pagination, type, sort) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: {'type': type, 'sort': sort, 'range': pagination.pageNumber, 'size': pagination.pageSize},
            url: "dashboard/get_other_list",
            success: function (result) {
                $("#ajax_other").html(result);
            }
        });
    }

    /*
     * For Search
     */
    var pre_text = $("#search_text_pre").val();
    if (pre_text != '') {
        $(".search_list").show();
        $(".friends_show").hide();
        $(".fof_show").hide();
        $(".o_show").hide();
        search_load_resources(type = '', sort = '', pre_text);
    }

    $("#search_button").click(function () {
        $(".search_list").show();
        $(".friends_show").hide();
        $(".fof_show").hide();
        $(".o_show").hide();
        var search_text = $("#search_text").val();
        search_load_resources(type = '', sort = '', search_text);
    });

    function search_load_resources(type, sort, search_text) {
        var urll = window.location.href;
        if (search_text != '' || search_text != 'undefined') {
            //urll = urll + "?search_text=" + search_text;

            var urlParams = new URLSearchParams(window.location.search);

            console.log(urlParams.get('search_text'));

            if (history.pushState) {
                var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?search_text=' + search_text;
                console.log(newurl);
                window.history.pushState({path: newurl}, '', newurl);
            }

        }

        $.ajax({
            type: "POST",
            data: {'type': type, 'search_text': search_text},
            url: "dashboard/get_search_count",
            success: function (result) {
                if (result != 0) {
                    var item = [];
                    for (var i = 1; i <= result; i++) {
                        item.push(i);
                    }
                    $('.search_list').pagination({
                        dataSource: item,
                        totalNumber: result,
                        pageSize: 9,
                        pageRange: 1,
                        showPageNumbers: true,
                        showLastOnEllipsisShow: true,
                        showNavigator: true,
                        callback: function (data, pagination) {
                            var html = templatesearch(data, pagination, type, sort, search_text);
                        }
                    });
                } else {
                    // $(".fof_show").hide();
                    $("#ajax_search").html("<h2>No result found for " + search_text + ".</h2>");
                }
            }
        });

    }

    function templatesearch(data, pagination, type, sort, search_text) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: {'type': type, 'sort': sort, 'range': pagination.pageNumber, 'size': pagination.pageSize, 'search_text': search_text},
            url: "dashboard/get_search_list",
            success: function (result) {
                $("#ajax_search").html(result);
            }
        });
    }

});

function fam_link(id){
    window.location.href = "family/"+id+"/view";
}

//$(".share").click(function () {
    $(document).on("click",".share",function() {
    $('#myModal').modal('toggle');
    var id = $(this).attr('id');
    var rs = id.split("_");
    var bases = document.getElementsByTagName('base');
    var base_url = null;

    if (bases.length > 0) {
        base_url = bases[0].href;
    }
    var url = base_url+$("#link_" + rs[1]).attr('href');
    // var url = "http://google.com";
    var text = "MindForMe";

    call_this(url, text);
});

$(function () {

    var url = "http://google.com";
    var text = "MindForMe";
    call_this(url, text);
});

function call_this(url, text) {
    $("#shareRoundIcons").jsSocials({
        url: url,
        text: text,
        showLabel: false,
        showCount: false,
        shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest"]
    });
}
