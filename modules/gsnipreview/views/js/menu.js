/**
 * mitrocops
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
 /*
 * 
 * @author    mitrocops
 * @category seo
 * @package gsnipreview
 * @copyright Copyright mitrocops
 * @license   mitrocops
 */


function tabs_custom(id){

    if(id>100){

        if(id == 101){
            tabs_custom(3);
            tabs_custom_in(101);
        }


        if(id == 102){
            tabs_custom(3);
            tabs_custom_in(34);

        }

        if(id == 103){
            tabs_custom(3);
            tabs_custom_in(55);
        }

        if(id == 104){
            tabs_custom(3);
            tabs_custom_in(101);
        }

        if(id == 114){
            tabs_custom(3);
            tabs_custom_in(42);
        }



        if(id == 115){
            tabs_custom(5);
            tabs_custom_in_three(74);
        }

        if(id == 110){
            tabs_custom(5);
            tabs_custom_in_three(80);
        }

    } else {
        tabs_custom_my(id);
    }
}

function tabs_custom_my(id){
    for (i = 0; i < 100; i++) {
        $('#tab-menu-' + i).removeClass('selected');
    }
    $('#tab-menu-' + id).addClass('selected');
    for (i = 0; i < 100; i++) {
        $('#tabs-' + i).hide();
    }
    $('#tabs-' + id).show();
}

function init_tabs(id){


        $('document').ready(function () {
            for (i = 0; i < 100; i++) {
                $('#tabs-' + i).hide();
            }
            $('#tabs-' + id).show();


            tabs_custom(id);
        });

}

init_tabs(1);


function tabs_custom_in(id){

    for(i=0;i<103;i++){
        $('#tab-menuin-'+i).removeClass('selected');
    }
    $('#tab-menuin-'+id).addClass('selected');
    for(i=0;i<103;i++){
        $('#tabsin-'+i).hide();
    }
    $('#tabsin-'+id).show();
}





function init_tabs_in(id){
    $('document').ready( function() {
        for(i=0;i<103;i++){
            $('#tabsin-'+i).hide();
        }
        $('#tabsin-'+id).show();
        tabs_custom_in(id);
    });
}

init_tabs_in(31);




function tabs_custom_in_three(id){

    for(i=0;i<103;i++){
        $('#tab-menuin_three-'+i).removeClass('selected');
    }
    $('#tab-menuin_three-'+id).addClass('selected');


    for(i=0;i<103;i++){
        $('#tabsin_three-'+i).hide();
    }
    $('#tabsin_three-'+id).show();

}





function init_tabs_in_three(id){
    $('document').ready( function() {
        for(i=0;i<103;i++){
            $('#tabsin_three-'+i).hide();
        }
        $('#tabsin_three-'+id).show();
        tabs_custom_in_three(id);
    });
}

init_tabs_in_three(73);

