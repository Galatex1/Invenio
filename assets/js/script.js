$(document).ready(function(){

    $('.btnFilter').click(function(e){

        e.preventDefault();

        var clickBtnValue = $(this).val(); 

        if(clickBtnValue > 0)
        {

            var fils = $('.fil').length;
            var ajaxurl = 'page/filter.php',
            data =  {'filters': fils};

            if(fils < 10)
            {

                $.post(ajaxurl, data, function (response) {
                    //location.reload();
                    $(".filter").append(response);
                });
            }
        }
        else
        {
            $(".fil").last().remove();
        }

    });

    $('.btnQuery').click(function(e){
        e.preventDefault();

        $(".loading").show();

        var ajaxurl = 'page/data.php',
        data = $("#filtering").serialize();

        $.post(ajaxurl, data, function (response) {
            $(".data-container").html(response);
            $(".loading").hide();
        });
    });

    $(document).on('click','.column',function(e){
        e.preventDefault();

        //alert("hez");

        $("#orderBy").val($(e.target).text());

        var dir = $(e.target).hasClass("DESC") ? "ASC" : "DESC";

        $("#orderDir").val(dir);

        var ajaxurl = 'page/data.php',
        data =  $("#filtering").serialize();

        console.log(data);

        

        data += "&o_r_d_e_rDir="+ dir;

        $.post(ajaxurl, data, function (response) {
            $(".data-container").html(response);
            $(e.target).addClass(dir);

        });
    });

    $('.caret input').on('change', (e)=>{
        $(e.target).closest('li').find('input').prop("checked", $(e.target).prop("checked"));
    })

    $('input').on('change', (e)=>{
        console.log($(e.currentTarget).prop("checked"))
        if($(e.currentTarget).prop("checked") == true)
        {
            $(e.currentTarget).closest('.nest').find('span input').eq(0).prop("checked", $(e.currentTarget).prop("checked"));
            $(e.currentTarget).closest('.nest').parent().closest('.nest').find('span input').eq(0).prop("checked", $(e.currentTarget).prop("checked"));
            
        }
    })


    var toggler = document.getElementsByClassName("caret::before");
    toggler = $(".caret").not('input');
    var i;

    $(".caret input").click((e)=>{
        e.stopPropagation();
    });

    for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function() {
            this.parentElement.querySelector(".nested").classList.toggle("active");
            this.classList.toggle("caret-down");
        });
    }


    $(document).on("change", "header .table", () =>{

        $("#tbls").submit();
    })

    $(document).on("click", ".btnRefresh", ()=>{
        $("#tbls").submit();
    })



    $(document).on("change", "header #DB", () =>{

        $("#tblDB").submit();
    })


    $(document).on('click', '.btnDel', (e)=>{

        e.preventDefault();
        var ajaxurl = 'action/delete.php';
        var data = {}
        data.delete = flattenArray(JSON.parse(($(e.currentTarget).val())));

        $.post(ajaxurl, data, function (response) {
            // $(".data-container").html(response);

            showResponse(response, 2000);

            $(e.currentTarget).closest("tr").addClass("deleted");
            var time = $(".deleted").css('transition-duration').slice(0, -1);

            setTimeout(() => {
                $(".deleted").remove();
            }, (time*1000+10));


        });
    })

    $(".btnNew").click((e)=>{
        e.preventDefault();
        var ajaxurl = 'page/add.php',
        data;

        $.post(ajaxurl, data, function (response) {
            $(".data-container").html(response);
            
        });
    })

    $(document).on('click', ".btnEdit", (e)=>{
        e.preventDefault();
        var ajaxurl = 'page/edit.php';
        var data = {};
        data.edit = flattenArray(JSON.parse(($(e.currentTarget).val())));

        console.log(data);

        $.post(ajaxurl, data, function (response) {
            $(".data-container").html(response);
            
        });
    })


    $(document).on('click', ".btnSave", (e)=>{
        e.preventDefault();
        var ajaxurl = 'action/save.php',
        data = $('.addEdit').serialize();

        $.post(ajaxurl, data, function (response) {
            showResponse(response);        
        });
    })

    $(document).on('click', ".btnCookies", (e)=>{
        e.preventDefault();
        var ajaxurl = 'action/cookies.php',
        data = $('#settingsForm').serialize();

        $.post(ajaxurl, data, function (response) {

            //location.reload()+".<br><br><br><br><button class='btn' onclick='location.reload()'>Klikněte pro obnovení</button>";
            showResponse(response+".<br><br>Aplikace se teď obnoví s novým nastavením.", 3000, ()=>{
                $("body").css({'pointer-events': "none"});
                setTimeout(() => {
                    location.reload();
                }, 2700);
            });        
        });
    })

    $(document).on('click', ".btnSettings", (e)=>{
        e.preventDefault();
        var ajaxurl = 'page/settings.php',
        data;

        $.post(ajaxurl, data, function (response) {
            $(".data-container").html(response);
            //alert(response);          
        });
    })

    $(document).on('click', ".btnNapoveda", (e)=>{
        e.preventDefault();
        var ajaxurl = 'page/napoveda.php',
        data;

        $.post(ajaxurl, data, function (response) {
            $(".data-container").html(response);
            //alert(response);          
        });
    })

    $(document).on('click', ".btnReload", (e)=>{
        e.preventDefault();
        var ajaxurl = 'action/resetSession.php',
        data;

        $.post(ajaxurl, data, function (response) {
            location.reload();
            
            //alert(response);          
        });
    })

    $(document).on('click', ".btnConnect", (e)=>{
        e.preventDefault();
        var ajaxurl = 'action/connect.php',
        data = $("#connForm").serialize();

        $.post(ajaxurl, data, function (response) {

            showResponse(response, 4000, () => {location.reload();});
                 
        });
    })

});


function showResponse(response, time = 4000, success = () => {}, error = () => {})
{
    if(response && response.length > 0)
    {
        $(".response").fadeIn(500).removeClass("error");
        $(".response").html(response);

        if(response.includes("Error - "))
        {
            $(".response").addClass("error");
            error();
        }
        else
        {
            success();
        }

        setTimeout(() => {
            $(".response").fadeOut(500)
        }, time);
    }
}


function flattenArray(array)
{
    var newArr = {};

    for (const row of array) {
        if(row[1] !== undefined || row[1] != "")
            newArr[row[0]] = row[1];
    }
    return newArr;
}
