<!DOCTYPE html>
<html>
<head>
  <title>Sleep Apnea Classification</title>


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
  <link href={{ URL::asset('css/app.css') }} rel="stylesheet" type="text/css">



</head>
<body>

    <div class="container py-5">


        <header class="text-black text-center">
            <h1 class="display-4">Sleep Apnea Classification</h1>
            <p class="lead mb-0">Upload your csv file to get the result</p>

        </header>

        <br>
        <br><br><br><br><br><br>
        <div class="row py-4">
            <div class="col-lg-6 mx-auto">

                <form method="POST" enctype="multipart/form-data" id="upload-file" action="{{ url('upload') }}" >
                    {{ csrf_field() }}
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="file" name="file" placeholder="Choose file" id="file" class="form-control border-0">
                                  @error('file')
                                  <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                  @enderror
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>

                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary " id="submit">Submit</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
</body>
</html>
