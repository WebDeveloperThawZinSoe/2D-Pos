<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print</title>
</head>
<body>
    @isset($orderType)
        {{ $orderType }}
    @endisset

    @isset($reversedOutput)
        @foreach($reversedOutput as $pair)
            <p>{{ $pair }}{{$price}}</p>
        @endforeach
    @endisset
</body>
</html>
