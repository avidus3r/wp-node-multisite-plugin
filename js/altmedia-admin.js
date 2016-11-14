(function($){
    $(document).on('ready', function(){
        $('#cpclip').on('click', function(e){
            var urlTxt = document.querySelector('#feedUrl');
            urlTxt.select();
            try{
                var success = document.execCommand('copy');
                var msg = success ? 'URL copied to clipboard!' : 'Oops, something went wrong. Here\'s the link:<br />' + urlTxt.text();
                $('.cc-message').text(msg);
                var to = success ? 2000 : 15000;

                setTimeout(function(){
                    $('.cc-message').addClass('fade-out');
                    $('.cc-message').text('');
                }, to);
            }catch(err){
                console.error(err);
            }
        });


        /*$('.explicit-cb, .explicit-type-cb').on('change', function(e) {
         var cb = $(e.currentTarget);

         if (cb.hasClass('explicit-type-cb')) {
         var vals = $('.explicit-type-cb:checked');
         explicitTypes = [];
         explicitNames = [];
         for (var i = 0; i < vals.length; i++) {
         explicitNames.push($(vals[i]).attr('name'));
         explicitTypes.push($(vals[i]).val());
         }
         } else {
         var vals = $('.explicit-cb:checked');
         explicitTypes = [];
         explicitNames = [];
         for (var i = 0; i < vals.length; i++) {
         explicitTypes.push($(vals[i]).val());
         explicitNames.push($(vals[i]).attr('name'));
         }
         }
         });*/

        var updateBtn = $('<button/>')
            .text('Update')
            .attr('class', 'btn update')
            .on('click', function(e){

                var newTags = $('.newtag').val();
                var postID = Number($('.post-edit-wrap').attr('id'));
                var cbs = $('.explicit input[type="checkbox"]');
                var fieldValues = [];
                var fieldNames = [];

                for(var i=0;i<cbs.length;i++){
                    var item = $(cbs[i]);
                    var fieldName = item.attr('name');
                    var fieldValue = item.val();


                    if(!item.is(":checked")){
                        fieldNames.push(fieldName);
                        fieldValues.push(null);
                    }else{

                        fieldNames.push(fieldName);
                        fieldValues.push([$(cbs[i]).val()]);
                    }
                }

                var data = {
                    'action'        :'altmedia_admin_update_post',
                    'field_keys'    : fieldNames,
                    'newtags'       : newTags,
                    'postID'        : postID,
                    'field_values'  : fieldValues
                };
                console.log(data);
                $.post('/wp-admin/admin-ajax.php', data, function(response) {
                    if(response === false){
                        //didn't work
                    }else {
                        location.reload();
                    }
                });

            });
        $('.post-edit-wrap').append(updateBtn);


        $('.altmedia-tagcloud').append($('<a/>')
            .text('+ Add Tags')
            .attr('class','add-new-tag')
            .on('click', function(e){
                $('.altmedia-tagcloud').append($('<input/>')
                    .attr('type','text')
                    .attr('required','required')
                    .attr('name','newtag')
                    .attr('class','newtag')
                    .attr('placeholder','Add tags separated by comma')
                    .on('keydown', function(e){

                        var keyCode = e.which;
                        var postID = Number($('.post-edit-wrap').attr('id'));
                        var newTags = $(e.currentTarget).val();

                        if(keyCode === 13){
                            var data = {
                                'action'    : 'altmedia_admin_update_tags',
                                'newtags'   : newTags,
                                'postname'  : location.href,
                                'postID'    : postID
                            };
                            if(newTags !== "") {
                                $.post('/wp-admin/admin-ajax.php', data, function (response) {
                                    if (response === false) {
                                        //didnt work
                                    } else {
                                        //location.reload();

                                        var tags = newTags.split(',');
                                        for (var i = 0; i < tags.length; i++) {
                                            var tag = tags[i];
                                            $('.altmedia-tagcloud').prepend(
                                                $('<a/>')
                                                    .attr('href', '/tag/' + tag)
                                                    .attr('rel', 'tag')
                                                    .text(tag)
                                            )
                                        }
                                        $(e.currentTarget).val('');
                                    }
                                });
                            }
                        }
                    })
                )
            })
        );
    });
})(jQuery);