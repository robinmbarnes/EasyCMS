$(document).ready(
function()
{
    $('.flash-message').each(
        function()
        {
            $(this).fadeIn(1500);
        }
    );
    $('.flash-message a.close').each(
        function()
        {
            $(this).click(
                function() 
                {
                    $(this).closest('.flash-message').slideUp();        
                    return false;
                }
            );
        }
    );
});

function templateDescriptions(template_dropdown, template_descriptions)
{
    template_dropdown.change(
        function()
        {
            var element_container = template_dropdown.parent();
            var selected_template_id = template_dropdown.find(':selected').val();
            if(!element_container.children('.description').length)
            {
                if(!template_descriptions[selected_template_id])
                {
                    return;
                }
                element_container.append('<div class="description"></div>');
                element_container.children('.description:first').html('<div class="description-inner">' + template_descriptions[selected_template_id] + '</div>');
            }
            else
            {
                if(!template_descriptions[selected_template_id])
                {
                    element_container.children('.description:first').remove();
                }
                else
                {
                    element_container.children('.description:first .description-inner:first').text(template_descriptions[selected_template_id]);
                }
            }
        }
    );
}
