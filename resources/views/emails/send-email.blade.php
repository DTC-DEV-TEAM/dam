<!DOCTYPE html>
<html>
<head>
    <title>Digits Asset Management System</title>
</head>
<body>
    
<p>Hi<span style="font-weight: 700;">&nbsp;{{ $infos['assign_to'] }}</span></p>

<p>Your request of New Item Sourcing has been approved,  details are below.</p>

    <div class="row" style="display:flex;">
        <div class="col1" style="margin-right: 600px">
        <p><span style="font-weight: 700;">REF#:&nbsp;</span>
            <span style="text-align: center;">{{ $infos['reference_number'] }}</span>
            </p>
        </div>
        <div class="col2" style="float: right;">
            <p><span style="font-weight: 700;">Date Needed:&nbsp;</span>
            <span style="text-align: center;">{{ $infos['date_needed'] }}</span>
            </p>
        </div>
    </div>
    <div class="row" style="display:flex;">
        <div class="col1" style="margin-right: 400px">
        <p><span style="font-weight: 700;">Department:&nbsp;</span>
            <span style="text-align: center;">{{ $infos['department'] }}</span>
            </p>
        </div>
    </div>


    <table border="1" width="100%" style="text-align: center;">
        <tbody>
            <tr>
                <td><span style="font-weight: 700;">Item Description<br></span></td>
                <td><span style="font-weight: 700;">Category<br></span></td>
                <td><span style="font-weight: 700;">Sub Category</span><br></td>
                <td><span style="font-weight: 700;">Quantity</span><br></td>
                <td><span style="font-weight: 700;">Budget Range</span><br></td>
            </tr>
            @foreach($infos['items'] as $item)
            <tr>
                <td>{{ $item->item_description }}<br></td>
                <td>{{ $item->category_id }}<br></td>
                <td>{{ $item->sub_category_id }}<br></td>
                <td>{{ $item->quantity }}<br></td>
                <td>{{ $item->budget }}<br></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="col2">
        <p><span style="font-weight: 700;">Suggested Supplier:&nbsp;</span>
        <span>{{ $infos['suggested_supplier'] }}</span>
        </p>
    </div>

</body>
</html>