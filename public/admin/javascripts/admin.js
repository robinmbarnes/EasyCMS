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
