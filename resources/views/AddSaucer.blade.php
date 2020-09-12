<!Doctype>
<html>
    <head>
        <title>Add Restaurant</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    </head>
    <body>
        @include( 'shared.header' )
        @if( isset( $success ) && ($success === true) )
            <div class="alert alert-success" role="alert">
                Your sauce was saved.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @elseif( isset( $success ) && ($success === false) )
            <div class="alert alert-danger" role="alert">
                There was a problem saving your sauce, please try again or contact us for support.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="container">
            <form method="post" action="<?php echo route('saucer.add'); ?>"  class="m-4" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="exampleInputSaucerName1">Sauce name</label>
                    <input type="text" class="form-control" name="SaucerName" id="exampleInputSaucerName1" aria-describedby="RestaurantHelp">
                </div>
                <div class="form-group">
                    <label for="exampleInputSaucerPrice1">Sauce price</label>
                    <input type="number" class="form-control" name="SaucerPrice" step="0.01" id="exampleInputSaucerPrice1" aria-describedby="RestaurantHelp">
                </div>
                <div class="form-group">
                    <label for="exampleInputDescription">Description</label>
                    <input type="text" class="form-control" name="SaucerDescription" id="exampleInputDescription">
                </div>
                <div class="form-group">
                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" name="SaucerImage" id="validatedCustomFile" required>
                        <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    </body>
</html>
