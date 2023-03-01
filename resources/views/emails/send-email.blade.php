<!DOCTYPE html>
<html>
<head>
    <title>Digits Asset Management System</title>
</head>
<body>
<p>Hi<span style="font-weight: 700;">&nbsp;{{ $infos['assign_to'] }}</span></p>

<p>Your request of New Item Sourcing has been approved,  details are below.</p>

<p><span style="font-weight: 700;">REF#:&nbsp;</span>
<span style="text-align: center;">{{ $infos['reference_number'] }}</span>
</p>
    <table border="1" width="100%" style="text-align: center;">
        <tbody>
            <tr>
                <td><span style="font-weight: 700;">Item Description<br></span></td>
                <td><span style="font-weight: 700;">Category<br></span></td>
                <td><span style="font-weight: 700;">Sub Category</span><br></td>
            </tr>
            @foreach($infos['items'] as $item)
            <tr>
                <td>{{ $item->item_description }}<br></td>
                <td>{{ $item->category_id }}<br></td>
                <td>{{ $item->sub_category_id }}<br></td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>