$(document).ready(
    function()
    {
        $(window).scroll(
            function()
            {
                $('#editor-controls').css('top', $(document).scrollTop()+"px");
            }
        );
        $('#quit-button').click(
            function()
            {
                window.location.href = $('#folder-view-url').val();
            }
        );
        $('#save-button').click(
            function()
            {
                var post_data = {};
                $('#editable-page-content-pane').contents().find('.easy_cms_editable_section').each(
                    function()
                    {
                        var matches = $(this).id.match(/easy_cms_section_(.*)/);
                        post_data['sections['+matches[1]+']'] = $(this).html();
                    }
                );
                $.post(global_script_vars.save_url, post_data);
            }
        );
        var editor_controls_hidden = false;
        $('#show-hide-control').click(
            function()
            {
                if(editor_controls_hidden)
                {
                    $('#main-controls').show();
                    $('#editor-controls').css('width', '800px').css('padding-left', '10px');
                    editor_controls_hidden = false;
                }
                else
                {
                    $('#main-controls').hide();
                    $('#editor-controls').css('width', '80px').css('padding-left', '0');
                    editor_controls_hidden = true;
                }
            }
        );
        $('#editable-page-content-pane').load(
            function()
            {
                $('#editable-page-content-pane').contents().find('.easy_cms_editable_section').each(
                    function()
                    {
                        var editable_section = $(this);
                        var is_in_edit_mode = false;
                        $(this).mouseenter(
                            function()
                            {
                                if(!is_in_edit_mode)
                                {
                                    $(this).prepend('<div class="easy_cms_editable_section_highlight" title="Double click to edit" style="position: absolute; left:0; top:0; width:100%; height:100%; background-color:#2E8FFF; filter: alpha(opacity=30); -khtml-opacity: 0.3; opacity: 0.3;"></div>');
                                }
                            }
                        );
                        $(this).mouseleave(
                            function()
                            {
                                if(!is_in_edit_mode)
                                {
                                    $(this).children('.easy_cms_editable_section_highlight:first').remove();
                                }
                            }
                        );
                        $(this).dblclick(
                            function()
                            {
                                if(is_in_edit_mode)
                                {
                                    return true;
                                }
                                $(this).children('.easy_cms_editable_section_highlight:first').remove();
                                var content = editable_section.html();
                                var width = (editable_section.width() ? editable_section.width() + 'px' : '100px');
                                var height = (editable_section.height() ? (editable_section.height() + 30) + 'px' : '100px');
                                editable_section.html('');
                                var textarea = $('<textarea></textarea>').html(content).css('height', height).css('width', width);
                                editable_section.append(textarea);
                                textarea.focus();
                                textarea.htmlarea({
                                    toolbar: [
                                        ["bold", "italic", "underline", "|", "forecolor"],
                                        ["p", "h1", "h2", "h3", "h4", "h5", "h6"],
                                        ["link", "unlink", "|", "image"], 
                                       [{
                                           css: "custom_disk_button",
                                           text: "Save",
                                           action: 
                                            function(btn) {
                                                // 'this' = jHtmlArea object
                                                // 'btn' = jQuery object that represents the <A> "anchor" tag for the Toolbar Button
                                                var new_content = this.toHtmlString();                                               
                                                textarea.htmlarea('dispose');
                                                editable_section.html(new_content);
                                                is_in_edit_mode = false;
                                                textarea.remove();
                                             }
                                         },
                                        {
                                           css: "custom_disk_button",
                                           text: "Cancel",
                                           action: 
                                            function(btn) {
                                                // 'this' = jHtmlArea object
                                                // 'btn' = jQuery object that represents the <A> "anchor" tag for the Toolbar Button                                               
                                                textarea.htmlarea('dispose');
                                                editable_section.html(content);
                                                is_in_edit_mode = false;
                                                textarea.remove();
                                            }
                    
                                        }
                                        ]
                                    ]
                                });                                
                                is_in_edit_mode = true;
                            }
                        );
                    }
                );
            }
        );
        
    }
);



