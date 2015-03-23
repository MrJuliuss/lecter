var ajaxContent = function(url, callback) {
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json'
    })
    .done(function(data) {
        $('.content').empty().html(data.html);

        if(typeof url !== "undefined" && url != window.location) {
            window.history.pushState(data, '', this.url);
        }

        if(typeof callback !== 'undefined') {
            callback();
        }
    });
};

$(document).ajaxStart(function() {
    $('.ajax-loader').show();
}).ajaxComplete(function() {
    $('.ajax-loader').hide();
});

$(document).ready(function() {
    $(document).on('click', '#edit', function() {
        $.ajax({
            url: document.location.href+'?raw',
            type: 'GET',
            dataType: 'json'
        })
        .done(function(data) {
            React.render(
                React.createElement(MarkdownEditor, {content: $.trim(data['content'])}),
                document.getElementById('editor-container')
            );

            $('#content').hide();
            $('#edit').hide();
            $('#cancel').show();
            $('#save').show();
        });

    }).on('click', '#cancel', function() {
        $('#content').show();
        $('#cancel').hide();
        $('#save').hide();
        $('#edit').show();
        $('#editor-container').empty();
    }).on('click', '#save', function(){

    }).on('click', 'a.ajax', function() {
        ajaxContent($(this).attr('href'));

        return false;
    }).on('click', '#delete-content', function() {
        $.ajax({
            url: document.location.href,
            type: 'DELETE',
            dataType: 'json',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        .done(function(data) {
            if(data.success === true) {
                ajaxContent('/', function(){
                    $('#alert-success').show().html(data.message);
                });
            } else {
                $('#delete-modal').modal('hide');
                $('#alert-error').show().html(data.message);
            }
        });
    });
});

window.onpopstate = function(e) {
    if(e.state) {
        $(".content").empty().html(e.state.html);
    }
};