$(document).ready(
    function()
    {
        $('#editable-page-content-pane').load(
            function()
            {
                $('#editable-page-content-pane').contents().find('.easy_cms_editable_section').each(
                    function()
                    {
                        var original_background_color = $(this).css('background-color');
                        $(this).mouseenter(
                            function()
                            {
                                $(this).prepend('<div class="easy_cms_editable_section_highlight" title="Double click to edit" style="position: absolute; left:0; top:0; width:100%; height:100%; background-color:#2E8FFF; filter: alpha(opacity=30); -khtml-opacity: 0.3; opacity: 0.3;"></div>');
                            }
                        );
                        $(this).mouseleave(
                            function()
                            {
                                $(this).children('.easy_cms_editable_section_highlight:first').remove();
                            }
                        );
                    }
                );
            }
        );
        
    }
);



