(function() {

    function bulk_optimize_callback(error, data, assets, i) {
        var row = jQuery(jQuery('#media-items').children("div")[i])
        var status

        status = row.find('.bar')

        if (error) {
            status.addClass('err')
            row.find('.bar').css('width', '100%')
            row.find('.percent').html(optimusOptimize.internalError)
            row.find('.progress').attr("title", error.toString())
        } else if (data.error) {
            status.addClass('err')
            row.find('.bar').css('width', '100%')
            row.find('.percent').html(data.error)
            row.find('.progress').attr("title", data.error)
        } else if (data.info) {
            status.addClass('info')
            row.find('.bar').css('width', '100%')
            row.find('.percent').html(data.info)
            row.find('.progress').attr("title", data.info)
        } else if (typeof data.optimus.quantity != "undefined") {
            if (data.optimus.quantity < 100) {
                status.addClass('partial')
                row.find('.bar').css('width', data.optimus.quantity + '%')
                row.find('.percent').html(data.optimus.quantity + "% " + optimusOptimize.optimized)
                row.find('.progress').attr("title", data.message)
            } else {
                status.addClass('success')
                row.find('.bar').css('width', '100%')
                row.find('.percent').html(data.optimus.quantity + "% " + optimusOptimize.optimized)
            }
        } else {
            status.addClass('err')
            row.find('.bar').css('width', '100%')
            row.find('.percent').html(optimusOptimize.internalError)
            row.find('.progress').attr("title", error.toString())
        }

        if (assets[++i]) {
            bulk_optimize_asset(assets, i)
        } else {
            var message = jQuery('<div class="updated"><p></p></div>');
            message.find('p').html(optimusOptimize.bulkDone)
            message.insertAfter(jQuery("#optimus-bulk-optimizer h2"))
        }
    }

    function bulk_optimize_asset(assets, i) {
        var asset = assets[i]
        var row = jQuery(jQuery('#media-items').children("div")[i])
        row.find('.percent').html(optimusOptimize.optimizing)
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            dataType: "json",
            data: {
                _nonce: optimusOptimize.nonce,
                action: 'optimus_optimize_image',
                id: assets[i].ID
            },
            success: function(data) {
                bulk_optimize_callback(null, data, assets, i)
            },
            error: function(xhr, textStatus, errorThrown) {
                bulk_optimize_callback(errorThrown, {}, assets, i)
            }
        })
        jQuery('#optimus-progress span').html(i + 1)
    }

    function bulk_optimize(assets) {
        var list = jQuery('#media-items')
        var row
        for (var i = 0; i < assets.length; i++) {
            row = jQuery('<div class="media-item"><div class="progress"><div class="percent"></div><div class="bar"></div></div><div class="filename"></div></div>')

            row.find('.percent').html(optimusOptimize.waiting)
            row.find('.filename').html(assets[i].post_title + ' <small>' + assets[i].post_mime_type + '</small>')
            list.append(row)
        }
        bulk_optimize_asset(assets, 0)
    }
    window.optimusBulkOptimizer = bulk_optimize
}).call()

jQuery(document).ready(function() {
    if (typeof adminpage != "undefined" && adminpage === "upload-php") {
        jQuery('<option>').val('optimus_bulk_optimizer').text(optimusOptimize.bulkAction).appendTo('select[name="action"]')
        jQuery('<option>').val('optimus_bulk_optimizer').text(optimusOptimize.bulkAction).appendTo('select[name="action2"]')
    }
    
    var i = location.search.split('=');
    if (i[0] == '?_optimus_action' && i[1] == 'rekey') {
        jQuery("input[value='optimus/optimus.php']").prop('checked', true)
    }
 });
