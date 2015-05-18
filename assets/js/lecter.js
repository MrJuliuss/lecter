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

        if(data.navBarView !== null) {
            $('#navigation').empty().html(data.navBarView);
        }

        if(typeof callback !== 'undefined') {
            callback();
        }
    });
};

var editMode = function() {
    $('#content').hide();
    $('#edit').hide();
    $('#cancel').show();
    $('#save').show();
    $('.panel-edit').show();
    $('#new').hide();
};

var viewMode = function() {
    $('#content').show();
    $('#cancel').hide();
    $('#save').hide();
    $('#edit').show();
    $('.panel-edit').hide();
    $('#editor-container').empty();
    $('#new').show();
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
                React.createElement(MarkdownEditor, {content: $.trim(data.content)}),
                document.getElementById('editor-container')
            );

            $('#input-title').val(data.title);

            if(data.isFile === true) {
                $('#editor-container').show();
            }

            editMode();
        });

    }).on('click', '#cancel', function() {
        viewMode();
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
                ajaxContent(prefixUrl+'/?nav=1', function(){
                    $('#alert-success').show().html(data.message);
                });
            } else {
                $('#delete-modal').modal('hide');
                $('#alert-error').show().html(data.message);
            }
        });
    }).on('click', '#save', function() {
        $.ajax({
            url: document.location.href,
            type: 'PUT',
            dataType: 'json',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'content': $('.markdown-editor textarea').val(),
                'name': $('#input-title').val()
            },
        })
        .done(function(data) {
            if(data.success === true) {
                viewMode();
                $('#content').html(data.content);

                var callback = function() {
                    $('#alert-success').show().html(data.message);
                };

                if(data.newPath !== '') {
                    ajaxContent(prefixUrl+'/'+data.newPath+'?nav=1', callback);
                } else {
                    callback();
                }

            } else {
                $('#alert-error').show().html(data.message);
            }
        });
    }).on('click', '#new', function() {
        $('.panel-edit').show();
        $('.select-container').show();
        $('#input-title').empty();
        $('#edit').hide();
    }).on('click', '#cancel-new', function() {
        $('.panel-edit').hide();
        $('.select-container').hide();
        $('#edit').show();
    }).on('click', '#add', function() {
        $.ajax({
            url: document.location.href,
            type: 'POST',
            dataType: 'json',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'name': $('#input-title').val(),
                'type': $('#select-type').val(),
            },
        })
        .done(function(data) {
            if(data.success === true) {
                callback = function() {
                    $('#alert-success').show().html(data.message);
                }

                ajaxContent(prefixUrl+'/'+data.newPath+'?nav=1', callback);
            } else {
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
