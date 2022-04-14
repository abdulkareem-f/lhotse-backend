<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$requisition->name}}</title>
</head>
<body>

    <p>Dear Client</p>

    <p>This is a confirm of submitting requisition successfully, also you can see more details in the attached PDF file</p>

    <ul>
        <li><strong>Name</strong>: {{$requisition->name}}</li>
        <li><strong>Reference</strong>: {{$requisition->reference}}</li>
        <li><strong>Description</strong>: {{$requisition->description}}</li>
    </ul>

    <p>Best Regards</p>

</body>
</html>
