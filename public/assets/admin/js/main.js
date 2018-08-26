

 function getAjaxData(selectorChange, selectorHtml, url, col) {

        $(selectorChange).change(function(){

            $(selectorHtml).html('');

            var id = $(this).val();

            $.ajax({

                method : 'GET',
                url : url + '/' + id + '/' + col + '/' ,
                cache : false

            }).success(function(data) {

                var json = JSON.parse(data)

                console.log(data);
                $(selectorHtml).append('<option value="">برجاء الاختيار</option');
                $.each(json, function(key, val) {

                    $(selectorHtml).append('<option value="' + val.id + '">' + val.name + '</option>');

                });


            }).error(function(data) {

                console.log(data);

            });

        });

    }
    // <div {{ Session::has('notification') ? 'data-notification' : '' }} 
    // data-notification-type='{{ Session::get('alert_type', 'info') }}' 
    // data-notification-message='{{ json_encode(Session::get('message')) }}'>
    // // ...
    // </div>


