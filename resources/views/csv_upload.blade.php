<html>
<head lang="en">
    <meta charset="utf-8">
    <title></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>
<body>
<div class="container">
    <div class="row">

        <div class="col-md-8">

            <hr>

            {!! Form::open(array('id' => 'form', 'method' => 'POST',
            'url' => url('csv-upload'), 'files'=>true)) !!}

                <input id="uploadCsv" type="file" name="csv_import" />

                <input class="btn btn-success" type="submit" value="Upload">
            </form>

            <div id="err"></div>
            <div id="err1"></div>

            <div id="success"></div>
            <hr>

        </div>
    </div>
</div>
<script>
    $(document).ready(function (e) {
        $("#form").on('submit',(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{url('csv-upload')}}",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                beforeSend : function()
                {
                    //$("#preview").fadeOut();
                    $("#err").fadeOut();
                },
                success: function(data)
                {
                    // console.log(data);
                    if(data.meta.statusCode == 200)
                    {
                        // invalid file format.
                        $("#success").html(data.meta.message).fadeIn();
                    }
                    else if(data.meta.statusCode == 401)
                    {
                        $("#err").html(data.meta.message).fadeIn();
                    }
                    else if(data.meta.statusCode == 400)
                    {
                        console.log(data.meta.message);
                        $("#err1").html(data.meta.message);
                    }
                },
                error: function(e)
                {
                    $("#err").html(e).fadeIn();
                }
            });
        }));
    });
</script>
</body></html>