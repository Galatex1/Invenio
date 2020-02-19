$(document).ready(function(){

    $('.btnFilter').click(function(e){

        e.preventDefault();

        var clickBtnValue = $(this).val(); 

        if(clickBtnValue > 0)
        {

            var fils = $('.fil').length;
            var ajaxurl = 'filter.php',
            data =  {'filters': fils};

            $.post(ajaxurl, data, function (response) {
                //location.reload();
                $(".filter").append(response);
            });
        }
        else
        {
            $(".fil").last().remove();
        }

    });

    $('.btnSend').click(function(e){
        e.preventDefault();
        var ajaxurl = 'getData.php',
        data =  $("#filtering").serialize();

        $.post(ajaxurl, data, function (response) {
            $(".data-container").html(response);
        });
    });

    $(document).on('click','.column',function(e){
        e.preventDefault();
        var ajaxurl = 'getData.php',
        data =  $("#filtering").serialize();
        data += "&orderBy="+$(e.target).text();

        var dir = $(e.target).hasClass("DESC") ? "ASC" : "DESC";

        data += "&orderDir="+ dir;

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
        //e.preventDefault();
        //alert("hello");
        $("#tbls").submit();
    })


    $(document).on('click', '.btnDel', (e)=>{

        e.preventDefault();
        var ajaxurl = 'getData.php',
        data =  $("#filtering").serialize();
        data += "&delete="+$(e.currentTarget).val();
        console.log(data);


        $.post(ajaxurl, data, function (response) {
            // $(".data-container").html(response);
            $(e.currentTarget).closest("tr").addClass("deleted");
            var time = $(".deleted").css('transition-duration').slice(0, -1);

            setTimeout(() => {
                $(".deleted").remove();
            }, (time*1000+10));


        });
    })

    $(".btnNew").click((e)=>{
        e.preventDefault();
        var ajaxurl = 'add.php',
        data;

        $.post(ajaxurl, data, function (response) {
            $(".data-container").html(response);
            
        });
    })

    $(document).on('click', ".btnSave", (e)=>{
        e.preventDefault();
        var ajaxurl = 'save.php',
        data = $('.addEdit').serialize();

        $.post(ajaxurl, data, function (response) {
            $(".response").fadeIn(500).removeClass("error");
            $(".response").html(response);

            if(response.includes("Error - "))
                $(".response").addClass("error");
            // else
            //     $(".btnReset").trigger('click');

            setTimeout(() => {
                $(".response").fadeOut(500)
            }, 2000);

            //alert(response);          
        });
    })

    $(document).on('click', ".btnSettings", (e)=>{
        e.preventDefault();
        var ajaxurl = 'settings.php',
        data;

        $.post(ajaxurl, data, function (response) {
            $(".data-container").html(response);
            //alert(response);          
        });
    })

    $(document).on('click', ".btnNapoveda", (e)=>{
        e.preventDefault();
        var ajaxurl = 'napoveda.php',
        data;

        $.post(ajaxurl, data, function (response) {
            $(".data-container").html(response);
            //alert(response);          
        });
    })
});
