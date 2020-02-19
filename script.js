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

        console.log(data);

        $.post(ajaxurl, data, function (response) {
            $(".data-container").html(response);
            $(e.target).addClass(dir);

        });
    });

    $('.caret input').on('change', (e)=>{

        //alert("hello")
        //console.log(e.target);
        $(e.target).closest('li').find('.chkBox input').prop("checked", $(e.target).prop("checked"));

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


});
