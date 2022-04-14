<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$requisition->name}}</title>
    <style>
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
        }
        .text-center{
            text-align: center;
        }
        .items-table-cont{
            margin-top: 2.5em;
            text-align: center;
        }
        .items-table{
            width: 100%;
        }
    </style>
</head>
<body>

    <h2 class="text-center">Requisition ({{$requisition->name}}) Details</h2>
    <ul>
        <li><strong>Reference</strong>: {{$requisition->reference}}</li>
        <li><strong>Description</strong>: {{$requisition->description}}</li>
    </ul>

    <div class="items-table-cont">
        <h3>Items</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Reference</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requisition->items as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->reference}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
