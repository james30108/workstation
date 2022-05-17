
$(document).ready(function(){

    // Show Upline
    $("#direct_code").change(function(){
        var direct_code = $(this).val();
        
        $.get( "process/ajax/ajax_check_member.php", { direct_code: direct_code }, function( data ) {

            if (data == 'none') {
                alert('This member code is not in the system');
                $("#direct_code").val("");
                $("#direct_name").val("");
                $("#direct_id").val("");
            }
            else {
                let direct      = data.split(",");
                let direct_id   = direct[0];
                let direct_name = direct[1];

                $("#direct_id").val(direct_id);
                $("#direct_name").val(direct_name);
            }
        });
    });

    // Show Upline
    $("#direct_code2").change(function(){
        var direct_code = $(this).val();
        
        $.get( "process/ajax/ajax_check_member.php", { direct_code: direct_code }, function( data ) {

            if (data == 'none') {
                alert('This member code is not in the system');
                $("#direct_code2").val("");
                $("#direct_name2").val("");
                $("#direct_id2").val("");
            }
            else {
                let direct      = data.split(",");
                let direct_id   = direct[0];
                let direct_name = direct[1];

                $("#direct_id2").val(direct_id);
                $("#direct_name2").val(direct_name);
            }
        });
    });

});