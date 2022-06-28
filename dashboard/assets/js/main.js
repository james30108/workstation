$(document).ready(function(){

    var lang = $("#lang").val();

    // Address 
    $("#provinces").change(function(){
        var provinces_id = $(this).val();
        
        $("#amphures_data").val("");
        $("#districts_data").val("");
        $("#districts_select").val("");
        $("#postcode_data").val("");

        $.get( "process/ajax/ajax_main.php", { provinces: provinces_id, lang: lang}, function( data ) {
            $("#amphures").html( data );

            // --- เลือกอำเภอเพื่อล๊อกตำบล
            $("#amphures_select").change(function(){
                var amphures_id = $(this).val();
                $("#amphures_data").val(amphures_id);
                $("#districts_data").val("");
                $("#postcode_data").val("");
                
                $.get( "process/ajax/ajax_main.php", { amphures: amphures_id, lang: lang }, function( data ) {
                    $("#districts").html( data );

                    // --- เลือกตำบลเพื่อล๊อกรหัสไปรษณีย์
                    $("#districts_select").change(function(){
                        var districts_id = $(this).val();
                        $("#districts_data").val(districts_id);
                        
                        $.get( "process/ajax/ajax_main.php", { districts: districts_id, lang: lang }, function( data ) {
                            $("#postcode").html( data );  
                        });
                    });
                    // -----      
                });
            });
            // -----      
        });
    });
    
    // Address 2
    $("#provinces_2").change(function(){
        var provinces_id_2 = $(this).val();
        $("#amphures_data_2").val("");
        $("#districts_data_2").val("");
        $("#districts_select_2").val("");
        $("#postcode_data_2").val("");

        $.get( "process/ajax/ajax_main.php", { provinces_2: provinces_id_2, lang: lang }, function( data ) {
            $("#amphures_2").html( data );

            // --- เลือกอำเภอเพื่อล๊อกตำบล
            $("#amphures_select_2").change(function(){
                var amphures_id_2 = $(this).val();
                $("#amphures_data_2").val(amphures_id_2);
                $("#districts_data_2").val("");
                $("#postcode_data_2").val("");
                
                $.get( "process/ajax/ajax_main.php", { amphures_2: amphures_id_2, lang: lang }, function( data ) {
                    $("#districts_2").html( data );

                    // --- เลือกตำบลเพื่อล๊อกรหัสไปรษณีย์
                    $("#districts_select_2").change(function(){
                        var districts_id_2 = $(this).val();
                        $("#districts_data_2").val(districts_id_2);
                        
                        $.get( "process/ajax/ajax_main.php", { districts_2: districts_id_2, lang: lang }, function( data ) {
                            $("#postcode_2").html( data );  
                        });
                    });
                    // -----      
                });
            });
            // -----      
        });
    });

    // Switch On, Off
    $("#switch").click(function(){
        if($(this).prop("checked") == true){
            $(".switch").css("display", "none");
            $('.switch').removeAttr('required');
        }
        else if($(this).prop("checked") == false){
            $(".switch").css("display", "block");
            $('.switch').attr('required', 'required');
        }
    });

    // Check Upline
    $("#check_upline").change(function(){
        var check_upline = $(this).val();
        
        $.get( "process/ajax/ajax_main.php", { check_upline: check_upline }, function( data ) {
            
            if (data == 'full') {
                alert('จำนวนสมาชิกของสมาชิกท่านนี้เต็มแล้ว');
                $("#direct_name").val("");
                $("#direct_id").val("");
            }
            else if (data == 'none') {
                alert('This member code is not in the system');
                $("#direct_name").val("");
                $("#direct_id").val("");
            }
            else {
                let direct      = data.split(",");
                let direct_id   = direct[0];
                let direct_name = direct[1];

                $("#direct_id").val(direct[0]);
                $("#direct_name").val(direct[1]);
            }

        });
    });

    // Check IDCard (insert)
    $("#id_card").change(function(){
        var id_card = $(this).val();
        
        $.get( "process/ajax/ajax_main.php", { id_card: id_card, lang: lang }, function( data ) {

            if (data == '') {
                alert('ID Card is duplicate');
                $("#id_card").val("");
            }
            else {
                $("#id_card").val( data );
            }
        });
    });

    // Check IDCard (Edit)
    var id_old = $("#id_card2").val();
    $("#id_card2").change(function(){
        var id_card2 = $(this).val();
        var id       = $("#member_id").val();
        
        $.get( "process/ajax/ajax_main.php", { id_card2: id_card2, id: id, lang: lang }, function( data ) {

            if (data == '') {
                alert('ID Card is duplicate');
                $("#id_card2").val( id_old );
            }
            else {
                $("#id_card2").val( data );
            }
        });
    });

    // Check IDBank (insert)
    $("#id_bank").change(function(){
        var id_bank = $(this).val();
        
        $.get( "process/ajax/ajax_main.php", { id_bank: id_bank, lang: lang }, function( data ) {

            if (data == '') {
                alert('ID Bank is duplicate');
                $("#id_bank").val("");
            }
            else {
                $("#id_bank").val( data );
            }
        });
    });

    // Check IDBank (Edit)
    var id_old2 = $("#id_bank2").val();
    $("#id_bank2").change(function(){
        var id_bank2 = $(this).val();
        var id       = $("#member_id").val();
        
        $.get( "process/ajax/ajax_main.php", { id_bank2: id_bank2, id: id, lang: lang }, function( data ) {

            if (data == '') {
                alert('ID Bank is duplicate');
                $("#id_bank2").val( id_old2 );
            }
            else {
                $("#id_bank2").val( data );
            }
        });
    });

    // Show Upline
    $("#check_member").change(function(){

        var direct_code = $(this).val();

        $.get( "process/ajax/ajax_main.php", { direct_code: direct_code }, function( data ) {

            if (data == 'none') {
                alert('This member code is not in the system');
                $("#check_member").val("");
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
    $("#check_member2").change(function(){

        var direct_code = $(this).val();
        
        $.get( "process/ajax/ajax_main.php", { direct_code: direct_code }, function( data ) {

            if (data == 'none') {
                alert('This member code is not in the system');
                $("#check_member2").val("");
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
    
    // Check name (for insert)
    $("#check_name").change(function(){
        var check_name = $(this).val();

        $.get( "process/ajax/ajax_main.php", { check_name: check_name, lang: lang }, function( data ) {

            if (data == '') {
                alert('Name is duplicate');
                $("#check_name").val("");
            }
            else {
                $("#check_name").val( data );
            }
        });
    });

    // Check name (for edit)
    var id_old3 = $("#check_name2").val();
    $("#check_name2").change(function(){
        var check_name2 = $(this).val();
        var id          = $("#member_id").val();
        
        $.get( "process/ajax/ajax_main.php", { check_name2: check_name2, id: id, lang: lang }, function( data ) {

            if (data == '') {
                alert('Name is duplicate');
                $("#check_name2").val( id_old3 );
            }
            else {
                $("#check_name2").val( data );
            }
        });
    });

    // Check email (for insert)
    $("#check_email").change(function(){
        var check_email = $(this).val();
        
        $.get( "process/ajax/ajax_main.php", { check_email: check_email, lang: lang }, function( data ) {

            if (data == '') {
                alert('Email is duplicate');
                $("#check_email").val("");
            }
            else {
                $("#check_email").val( data );
            }
        });
    });

    // Check email (for edit)
    var id_old4 = $("#check_email2").val();
    $("#check_email2").change(function(){
        var check_email2 = $(this).val();
        var id           = $("#member_id").val();
        
        $.get( "process/ajax/ajax_main.php", { check_email2: check_email2, id: id, lang: lang }, function( data ) {

            if (data == '') {
                alert('Email is duplicate');
                $("#check_email2").val( id_old4 );
            }
            else {
                $("#check_email2").val( data );
            }
        });
    });

});